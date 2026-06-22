<?php
get_header();

$sale_product_ids = class_exists( 'WooCommerce' ) ? wc_get_product_ids_on_sale() : array();
?>

<main id="primary" class="wb-main">
    <section class="wb-archive-hero">
        <div class="wb-container">
            <p class="wb-eyebrow"><?php esc_html_e( 'WorldBite specials', 'worldbite' ); ?></p>
            <h1><?php esc_html_e( 'Deals', 'worldbite' ); ?></h1>
            <p><?php esc_html_e( 'Browse discounted global pantry products and limited-time offers.', 'worldbite' ); ?></p>
        </div>
    </section>

    <div class="wb-container wb-page-shell">
        <?php if ( ! class_exists( 'WooCommerce' ) ) : ?>
            <div class="wb-empty">
                <div class="wb-empty-emoji">🛒</div>
                <h2><?php esc_html_e( 'WooCommerce is not active', 'worldbite' ); ?></h2>
                <p><?php esc_html_e( 'Activate WooCommerce to show sale products.', 'worldbite' ); ?></p>
            </div>

        <?php elseif ( empty( $sale_product_ids ) ) : ?>
            <div class="wb-empty">
                <div class="wb-empty-emoji">🏷️</div>
                <h2><?php esc_html_e( 'No deals right now', 'worldbite' ); ?></h2>
                <p><?php esc_html_e( 'Sale products will appear here when you add sale prices in WooCommerce.', 'worldbite' ); ?></p>
                <a class="wb-button" href="<?php echo esc_url( worldbite_shop_url() ); ?>">
                    <?php esc_html_e( 'Browse all products', 'worldbite' ); ?>
                </a>
            </div>

        <?php else : ?>
            <?php
            echo do_shortcode(
                '[products ids="' . esc_attr( implode( ',', array_map( 'absint', $sale_product_ids ) ) ) . '" columns="4" orderby="date" order="DESC"]'
            );
            ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();