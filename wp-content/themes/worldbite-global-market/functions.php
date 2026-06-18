<?php
/**
 * WorldBite Global Market theme functions.
 *
 * @package WorldBiteGlobal
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function worldbite_global_setup() {
    load_theme_textdomain( 'worldbite', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 80,
            'width'       => 300,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );

    add_theme_support(
        'woocommerce',
        array(
            'thumbnail_image_width' => 520,
            'single_image_width'    => 820,
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 6,
            ),
        )
    );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    register_nav_menus(
        array(
            'primary' => __( 'Primary Menu', 'worldbite' ),
            'footer'  => __( 'Footer Menu', 'worldbite' ),
        )
    );
}
add_action( 'after_setup_theme', 'worldbite_global_setup' );

function worldbite_global_assets() {
    $version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style( 'worldbite-global-style', get_stylesheet_uri(), array(), $version );
    wp_enqueue_script( 'worldbite-global-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), $version, true );
}
add_action( 'wp_enqueue_scripts', 'worldbite_global_assets' );

function worldbite_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'worldbite_content_width', 1320 );
}
add_action( 'after_setup_theme', 'worldbite_content_width', 0 );

/**
 * Replace WooCommerce's default wrappers with the theme layout.
 */
function worldbite_woocommerce_wrapper_before() {
    echo '<main id="primary" class="wb-main"><div class="wb-container wb-page-shell">';
}

function worldbite_woocommerce_wrapper_after() {
    echo '</div></main>';
}

function worldbite_woocommerce_hooks() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
    add_action( 'woocommerce_before_main_content', 'worldbite_woocommerce_wrapper_before', 10 );
    add_action( 'woocommerce_after_main_content', 'worldbite_woocommerce_wrapper_after', 10 );

    remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
    add_action( 'woocommerce_before_shop_loop_item_title', 'worldbite_loop_product_media', 10 );
}
add_action( 'wp', 'worldbite_woocommerce_hooks' );

function worldbite_products_per_page() {
    return 18;
}
add_filter( 'loop_shop_per_page', 'worldbite_products_per_page', 20 );

function worldbite_shop_url() {
    return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}

function worldbite_category_url( $slug ) {
    $term = get_term_by( 'slug', $slug, 'product_cat' );
    if ( $term && ! is_wp_error( $term ) ) {
        $url = get_term_link( $term );
        if ( ! is_wp_error( $url ) ) {
            return $url;
        }
    }
    return worldbite_shop_url();
}

function worldbite_cart_count() {
    return function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

function worldbite_cart_fragment( $fragments ) {
    ob_start();
    ?>
    <span class="wb-cart-count"><?php echo esc_html( worldbite_cart_count() ); ?></span>
    <?php
    $fragments['.wb-cart-count'] = ob_get_clean();
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'worldbite_cart_fragment' );

/**
 * Product placeholder image chosen by product name/category.
 */
function worldbite_product_placeholder_asset( $product_id ) {
    $name = strtolower( get_the_title( $product_id ) );
    $map  = array(
        'gochujang' => 'product-gochujang.jpg',
        'chilli paste' => 'product-gochujang.jpg',
        'chili paste' => 'product-gochujang.jpg',
        'olive oil' => 'product-olive-oil.jpg',
        'coconut milk' => 'product-coconut-milk.jpg',
        'pasta' => 'product-pasta.jpg',
        'penne' => 'product-pasta.jpg',
        'za’atar' => 'product-zaatar.jpg',
        "za'atar" => 'product-zaatar.jpg',
        'zaatar' => 'product-zaatar.jpg',
        'rice' => 'product-rice.jpg',
    );

    foreach ( $map as $needle => $file ) {
        if ( false !== strpos( $name, $needle ) ) {
            return get_template_directory_uri() . '/assets/images/' . $file;
        }
    }

    $terms = get_the_terms( $product_id, 'product_cat' );
    $term  = $terms && ! is_wp_error( $terms ) ? strtolower( $terms[0]->name ) : '';
    $category_map = array(
        'asian'         => 'category-asian.jpg',
        'mediterranean' => 'category-mediterranean.jpg',
        'latin'         => 'category-latin-american.jpg',
        'middle eastern'=> 'category-middle-eastern.jpg',
        'african'       => 'category-african.jpg',
        'caribbean'     => 'category-african.jpg',
        'european'      => 'category-european.jpg',
        'south asian'   => 'category-south-asian.jpg',
    );

    foreach ( $category_map as $needle => $file ) {
        if ( false !== strpos( $term, $needle ) ) {
            return get_template_directory_uri() . '/assets/images/' . $file;
        }
    }

    return get_template_directory_uri() . '/assets/images/category-shop-all.jpg';
}

function worldbite_product_emoji( $product_id = 0 ) {
    $product_id = $product_id ? $product_id : get_the_ID();
    $terms      = get_the_terms( $product_id, 'product_cat' );
    $name       = $terms && ! is_wp_error( $terms ) ? strtolower( $terms[0]->name ) : '';
    $map = array(
        'asian' => '🍜', 'south asian' => '🍛', 'mediterranean' => '🫒',
        'latin' => '🌮', 'middle eastern' => '🧆', 'african' => '🍲',
        'caribbean' => '🥥', 'european' => '🍝', 'spice' => '🌶️',
    );
    foreach ( $map as $needle => $emoji ) {
        if ( false !== strpos( $name, $needle ) ) {
            return $emoji;
        }
    }
    return '🥘';
}

function worldbite_loop_product_media() {
    global $product;
    if ( ! $product ) {
        return;
    }

    if ( has_post_thumbnail( $product->get_id() ) ) {
        echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) );
        return;
    }

    printf(
        '<img class="wb-product-fallback-image" src="%1$s" alt="%2$s" loading="lazy">',
        esc_url( worldbite_product_placeholder_asset( $product->get_id() ) ),
        esc_attr( $product->get_name() )
    );
}

function worldbite_fallback_menu() {
    $items = array(
        __( 'Home', 'worldbite' )        => home_url( '/' ),
        __( 'Shop', 'worldbite' )        => worldbite_shop_url(),
        __( 'Collections', 'worldbite' ) => get_post_type_archive_link( 'collection' ) ?: home_url( '/collections/' ),
        __( 'Create a collection', 'worldbite' ) => home_url( '/create-collection/' ),
        __( 'My account', 'worldbite' )  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ),
    );
    echo '<ul class="primary-menu">';
    foreach ( $items as $label => $url ) {
        printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
    }
    echo '</ul>';
}

function worldbite_collection_product_ids( $post_id ) {
    if ( function_exists( 'mp_get_collection_product_ids' ) ) {
        return mp_get_collection_product_ids( $post_id );
    }
    $ids = get_post_meta( $post_id, 'mp_collection_products', true );
    return is_array( $ids ) ? array_values( array_filter( array_map( 'absint', $ids ) ) ) : array();
}

function worldbite_collection_excerpt( $post_id, $words = 19 ) {
    $post = get_post( $post_id );
    if ( ! $post ) {
        return '';
    }
    $text = $post->post_excerpt ? $post->post_excerpt : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
    return $text ? wp_trim_words( $text, $words ) : __( 'A hand-picked food collection ready to explore.', 'worldbite' );
}
