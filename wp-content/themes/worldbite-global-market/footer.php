<footer class="wb-site-footer">
    <div class="wb-container">
        <div class="wb-footer-grid">
            <div class="wb-footer-brand">
                <a class="wb-brand wb-brand-footer" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <span class="wb-brand-line"><span>World</span><em>Bite</em></span>
                    <small>MARKET</small>
                </a>
                <p><?php esc_html_e( 'Authentic ingredients, global inspiration and shareable shopping collections—all in one welcoming marketplace.', 'worldbite' ); ?></p>
            </div>
            <div>
                <h3><?php esc_html_e( 'Shop', 'worldbite' ); ?></h3>
                <ul>
                    <li><a href="<?php echo esc_url( worldbite_shop_url() ); ?>"><?php esc_html_e( 'All products', 'worldbite' ); ?></a></li>
                    <li><a href="<?php echo esc_url( worldbite_category_url( 'asian-pantry' ) ); ?>"><?php esc_html_e( 'Asian pantry', 'worldbite' ); ?></a></li>
                    <li><a href="<?php echo esc_url( worldbite_category_url( 'mediterranean' ) ); ?>"><?php esc_html_e( 'Mediterranean', 'worldbite' ); ?></a></li>
                    <li><a href="<?php echo esc_url( worldbite_category_url( 'latin-american' ) ); ?>"><?php esc_html_e( 'Latin American', 'worldbite' ); ?></a></li>
                </ul>
            </div>
            <div>
                <h3><?php esc_html_e( 'Discover', 'worldbite' ); ?></h3>
                <ul>
                    <li><a href="<?php echo esc_url( get_post_type_archive_link( 'collection' ) ?: home_url( '/collections/' ) ); ?>"><?php esc_html_e( 'Collections', 'worldbite' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/create-collection/' ) ); ?>"><?php esc_html_e( 'Create a collection', 'worldbite' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>"><?php esc_html_e( 'About WorldBite', 'worldbite' ); ?></a></li>
                </ul>
            </div>
            <div>
                <h3><?php esc_html_e( 'Account', 'worldbite' ); ?></h3>
                <ul>
                    <li><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>"><?php esc_html_e( 'My account', 'worldbite' ); ?></a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ) ); ?>"><?php esc_html_e( 'Cart', 'worldbite' ); ?></a></li>
                    <li><a href="<?php echo esc_url( function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' ) ); ?>"><?php esc_html_e( 'Checkout', 'worldbite' ); ?></a></li>
                </ul>
            </div>
        </div>
        <div class="wb-footer-bottom">
            <span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.</span>
            <span><?php esc_html_e( 'Portfolio demonstration — no real orders are fulfilled.', 'worldbite' ); ?></span>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
