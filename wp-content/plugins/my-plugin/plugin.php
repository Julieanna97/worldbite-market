<?php
/**
 * Plugin Name: My Plugin
 * Description: Recipe/Collection builder for WooCommerce. Front-end creation (min 2 product), and one-click "Add all to cart". Pushes a GTM event when a collection is created.
 * Version: 1.0.0
 * Author: Julie Anne
 * Text Domain: mp
 */

if ( ! defined('ABSPATH') ) exit;

define('MP_COLLECTION_META', 'mp_collection_products');

function mp_register_collections() {

    $collections_label = apply_filters('mp_get_collection_name', 'Collections');

    $cpt_labels = array(
        'name'               => $collections_label,
        'singular_name'      => __('Collection','mp'),
        'add_new'            => __('Add New','mp'),
        'add_new_item'       => __('Add New Collection','mp'),
        'edit_item'          => __('Edit Collection','mp'),
        'new_item'           => __('New Collection','mp'),
        'view_item'          => __('View Collection','mp'),
        'search_items'       => __('Search Collections','mp'),
        'not_found'          => __('No collections found','mp'),
        'not_found_in_trash' => __('No collections found in Trash','mp'),
        'all_items'          => __('All Collections','mp'),
        'menu_name'          => $collections_label,
    );

    $cpt_args = array(
        'labels'        => $cpt_labels,
        'public'        => true,
        'has_archive'   => true, // archive at /collections
        'rewrite'       => array( 'slug' => 'collections', 'with_front' => false ),
        'show_in_rest'  => true,
        'supports'      => array('title','editor','author','thumbnail'),
        'menu_icon'     => 'dashicons-screenoptions',
    );

    $cpt_args = apply_filters('mp_collection_params', $cpt_args);

    register_post_type('collection', $cpt_args);

    $tax_args = array(
        'labels' => array(
            'name'          => __('Collection Categories','mp'),
            'singular_name' => __('Collection Category','mp'),
        ),
        'public'            => true,
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => array(
            'slug'       => 'collection-category',
            'with_front' => false,
        ),
    );

    $tax_args = apply_filters('mp_collection_taxonomy_params', $tax_args);

    register_taxonomy('collection_category', array('collection'), $tax_args);
    
}
add_action('init', 'mp_register_collections');

function mp_get_collection_product_ids( $post_id ) {
    $ids = get_post_meta($post_id, MP_COLLECTION_META, true);
    if ( ! is_array($ids) ) $ids = array();
    $ids = array_map('intval', $ids);
    return array_values( array_unique( array_filter($ids) ) );
}

function mp_get_add_all_to_cart_url( $post_id ) {
    return add_query_arg('mp_add_all', '1', get_permalink($post_id));
}


function mp_handle_add_all_to_cart() {
    if ( ! function_exists('WC') ) return;
    if ( ! is_singular('collection') ) return;
    if ( empty($_GET['mp_add_all']) ) return;

    $ids = mp_get_collection_product_ids( get_queried_object_id() );
    if ( $ids ) {
        if ( ! WC()->cart ) wc_load_cart();
        foreach ($ids as $pid) {
            $product = wc_get_product($pid);
            if ( $product ) {
                WC()->cart->add_to_cart($pid, 1);
            }
        }
    }
    wp_safe_redirect( wc_get_cart_url() );
    exit;
}
add_action('template_redirect', 'mp_handle_add_all_to_cart');


function mp_create_collection_shortcode( $atts = array() ) {
    if ( ! function_exists( 'WC' ) ) {
        return '<p>' . esc_html__( 'WooCommerce is required.', 'mp' ) . '</p>';
    }

    /*
     * Admin only.
     * This prevents normal visitors/customers from creating public collections.
     */
    if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
        return '<div class="mp-form-message mp-form-message--locked" style="padding:1rem;border:1px solid #ddd;border-radius:12px;background:#fff;">
            <strong>' . esc_html__( 'Admin only', 'mp' ) . '</strong><br>
            ' . esc_html__( 'This page is used by the site administrator to create recipe collections.', 'mp' ) . '
        </div>';
    }

    $out = '';

    if (
        isset( $_POST['mp_create_collection'] ) &&
        isset( $_POST['_wpnonce'] ) &&
        wp_verify_nonce( $_POST['_wpnonce'], 'mp_create_collection' )
    ) {
        $title    = isset( $_POST['mp_title'] ) ? sanitize_text_field( wp_unslash( $_POST['mp_title'] ) ) : '';
        $cat_id   = isset( $_POST['mp_category'] ) ? absint( $_POST['mp_category'] ) : 0;
        $products = isset( $_POST['mp_products'] ) ? array_map( 'intval', (array) $_POST['mp_products'] ) : array();
        $products = array_values( array_unique( array_filter( $products ) ) );

        if ( $title === '' ) {
            $out .= '<div class="notice notice-error" style="padding:.8rem">' . esc_html__( 'Please enter a title.', 'mp' ) . '</div>';
        } elseif ( count( $products ) < 2 ) {
            $out .= '<div class="notice notice-error" style="padding:.8rem">' . esc_html__( 'Please select at least 2 products.', 'mp' ) . '</div>';
        } else {
            $post_id = wp_insert_post(
                array(
                    'post_type'   => 'collection',
                    'post_title'  => $title,
                    'post_status' => 'publish',
                    'post_author' => get_current_user_id(),
                )
            );

            if ( $post_id && ! is_wp_error( $post_id ) ) {
                update_post_meta( $post_id, MP_COLLECTION_META, $products );

                $taxonomy = 'collection_category';

                if ( $cat_id && taxonomy_exists( $taxonomy ) ) {
                    wp_set_post_terms( $post_id, array( $cat_id ), $taxonomy, false );
                }

                /*
                 * Optional image upload.
                 * This becomes the Featured Image for the collection.
                 */
                if (
                    ! empty( $_FILES['mp_collection_image']['name'] ) &&
                    isset( $_FILES['mp_collection_image']['tmp_name'] ) &&
                    is_uploaded_file( $_FILES['mp_collection_image']['tmp_name'] )
                ) {
                    require_once ABSPATH . 'wp-admin/includes/file.php';
                    require_once ABSPATH . 'wp-admin/includes/media.php';
                    require_once ABSPATH . 'wp-admin/includes/image.php';

                    $attachment_id = media_handle_upload( 'mp_collection_image', $post_id );

                    if ( ! is_wp_error( $attachment_id ) ) {
                        set_post_thumbnail( $post_id, $attachment_id );
                    }
                }

                $out .= '<div class="notice notice-success" style="padding:.8rem">'
                    . esc_html__( 'Collection created!', 'mp' ) . ' '
                    . '<a href="' . esc_url( get_permalink( $post_id ) ) . '">' . esc_html__( 'View it', 'mp' ) . '</a>'
                    . '</div>';

                $out .= '<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push({'
                    . 'event:"collection_created",'
                    . 'collection_id:' . (int) $post_id . ','
                    . 'collection_title:' . wp_json_encode( get_the_title( $post_id ) ) . ','
                    . 'product_count:' . (int) count( $products )
                    . '});</script>';
            } else {
                $out .= '<div class="notice notice-error" style="padding:.8rem">' . esc_html__( 'Something went wrong.', 'mp' ) . '</div>';
            }
        }
    }

    $taxonomy = 'collection_category';

    $terms = taxonomy_exists( $taxonomy )
        ? get_terms(
            array(
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
            )
        )
        : array();

    $products_q = new WP_Query(
        array(
            'post_type'      => array( 'product' ),
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
            'no_found_rows'  => true,
        )
    );

    ob_start();
    ?>

    <form method="post" enctype="multipart/form-data" class="mp-form" style="max-width:800px">
        <p>
            <label>
                <?php echo esc_html__( 'Recipe title', 'mp' ); ?><br>
                <input type="text" name="mp_title" required style="width:100%">
            </label>
        </p>

        <?php if ( $taxonomy && ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
            <p>
                <label>
                    <?php echo esc_html__( 'Category', 'mp' ); ?><br>
                    <select name="mp_category">
                        <option value="0"><?php echo esc_html__( '— Select —', 'mp' ); ?></option>
                        <?php foreach ( $terms as $t ) : ?>
                            <option value="<?php echo esc_attr( $t->term_id ); ?>">
                                <?php echo esc_html( $t->name ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </p>
        <?php endif; ?>

        <p>
            <label>
                <?php echo esc_html__( 'Recipe image', 'mp' ); ?><br>
                <input type="file" name="mp_collection_image" accept="image/*">
            </label>
        </p>

        <fieldset>
            <legend><?php echo esc_html__( 'Choose at least 2 products', 'mp' ); ?></legend>

            <div style="max-height:320px;overflow:auto;border:1px solid #ddd;padding:10px">
                <?php if ( $products_q->have_posts() ) : ?>
                    <?php while ( $products_q->have_posts() ) : ?>
                        <?php $products_q->the_post(); ?>

                        <label style="display:block;margin-bottom:6px">
                            <input type="checkbox" name="mp_products[]" value="<?php echo esc_attr( get_the_ID() ); ?>">
                            <?php the_title(); ?>
                        </label>
                    <?php endwhile; ?>

                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <em><?php echo esc_html__( 'No products yet. Add some under WooCommerce → Products.', 'mp' ); ?></em>
                <?php endif; ?>
            </div>
        </fieldset>

        <?php wp_nonce_field( 'mp_create_collection' ); ?>

        <p>
            <button type="submit" name="mp_create_collection" class="button button-primary">
                <?php echo esc_html__( 'Create collection', 'mp' ); ?>
            </button>
        </p>
    </form>

    <?php
    $out .= ob_get_clean();

    return $out;
}
add_shortcode('mp_create_collection', 'mp_create_collection_shortcode');

/**
 * Admin product selector for Collection ingredients.
 * This lets you edit which WooCommerce products appear inside a recipe collection.
 */

function mp_add_collection_products_meta_box() {
    add_meta_box(
        'mp_collection_products_box',
        __('Collection Ingredients / Products', 'mp'),
        'mp_render_collection_products_meta_box',
        'collection',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'mp_add_collection_products_meta_box');

function mp_render_collection_products_meta_box( $post ) {
    wp_nonce_field( 'mp_save_collection_products', 'mp_collection_products_nonce' );

    $selected_products = get_post_meta( $post->ID, MP_COLLECTION_META, true );

    if ( ! is_array( $selected_products ) ) {
        $selected_products = array();
    }

    $selected_products = array_map( 'intval', $selected_products );

    $products = get_posts( array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ) );

    if ( empty( $products ) ) {
        echo '<p>' . esc_html__( 'No products found. Add products first under Products → Add New.', 'mp' ) . '</p>';
        return;
    }

    echo '<p><strong>' . esc_html__( 'Choose the products that belong inside this recipe collection:', 'mp' ) . '</strong></p>';

    echo '<div style="max-height: 380px; overflow: auto; border: 1px solid #ddd; padding: 12px; background: #fff;">';

    foreach ( $products as $product_post ) {
        $product = wc_get_product( $product_post->ID );

        if ( ! $product ) {
            continue;
        }

        $checked = in_array( $product_post->ID, $selected_products, true ) ? 'checked' : '';

        echo '<label style="display:block; margin-bottom:10px;">';
        echo '<input type="checkbox" name="mp_collection_products[]" value="' . esc_attr( $product_post->ID ) . '" ' . $checked . '> ';
        echo esc_html( $product->get_name() );

        if ( $product->get_price_html() ) {
            echo ' <span style="color:#666;">— ' . wp_kses_post( $product->get_price_html() ) . '</span>';
        }

        echo '</label>';
    }

    echo '</div>';

    echo '<p style="color:#666; margin-top:10px;">';
    echo esc_html__( 'These selected products will appear under “Inside this collection” and will be added when the visitor clicks “Add all to cart”.', 'mp' );
    echo '</p>';
}

function mp_save_collection_products_meta_box( $post_id ) {
    if ( ! isset( $_POST['mp_collection_products_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['mp_collection_products_nonce'], 'mp_save_collection_products' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( get_post_type( $post_id ) !== 'collection' ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $products = isset( $_POST['mp_collection_products'] )
        ? array_map( 'intval', (array) $_POST['mp_collection_products'] )
        : array();

    $products = array_values( array_unique( array_filter( $products ) ) );

    update_post_meta( $post_id, MP_COLLECTION_META, $products );
}
add_action( 'save_post_collection', 'mp_save_collection_products_meta_box' );