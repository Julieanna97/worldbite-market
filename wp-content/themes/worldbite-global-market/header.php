<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'worldbite' ); ?></a>

<div class="wb-utility-bar">
    <div class="wb-container wb-utility-inner">
        <span><b aria-hidden="true">◎</b><?php esc_html_e( 'Worldwide Shipping', 'worldbite' ); ?></span>
        <span><b aria-hidden="true">★</b><?php esc_html_e( 'Premium Quality', 'worldbite' ); ?></span>
        <span><b aria-hidden="true">▣</b><?php esc_html_e( 'Secure Checkout', 'worldbite' ); ?></span>
    </div>
</div>

<header class="wb-site-header">
    <div class="wb-container wb-main-header">
        <?php if ( has_custom_logo() ) : ?>
            <div class="wb-brand"><?php the_custom_logo(); ?></div>
        <?php else : ?>
            <a class="wb-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php esc_attr_e( 'WorldBite Market homepage', 'worldbite' ); ?>">
                <span class="wb-brand-line"><span>World</span><em>Bite</em></span>
                <small>MARKET</small>
            </a>
        <?php endif; ?>

        <form role="search" method="get" class="wb-product-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label class="screen-reader-text" for="wb-product-search-field"><?php esc_html_e( 'Search for products', 'worldbite' ); ?></label>
            <input id="wb-product-search-field" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search for products, cuisines or ingredients…', 'worldbite' ); ?>">
            <input type="hidden" name="post_type" value="product">
            <button type="submit" aria-label="<?php esc_attr_e( 'Search', 'worldbite' ); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
            </button>
        </form>

        <div class="wb-header-actions">
            <a class="wb-header-action wb-account-link" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4.5 21a7.5 7.5 0 0 1 15 0"></path></svg>
                <span><?php esc_html_e( 'My Account', 'worldbite' ); ?></span>
            </a>
            <a class="wb-header-action wb-cart-link" href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ) ); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 3h2l2.2 11a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 7H6"></path><circle cx="10" cy="20" r="1"></circle><circle cx="18" cy="20" r="1"></circle></svg>
                <span><?php esc_html_e( 'Cart', 'worldbite' ); ?></span>
                <span class="wb-cart-count"><?php echo esc_html( worldbite_cart_count() ); ?></span>
            </a>
            <button class="wb-menu-toggle" type="button" aria-expanded="false" aria-controls="wb-category-navigation" aria-label="<?php esc_attr_e( 'Open menu', 'worldbite' ); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>
            </button>
        </div>
    </div>

    <nav id="wb-category-navigation" class="wb-category-nav" aria-label="<?php esc_attr_e( 'Shop navigation', 'worldbite' ); ?>">
        <div class="wb-container">
            <ul>
                <li class="wb-shop-all"><a href="<?php echo esc_url( worldbite_shop_url() ); ?>"><?php esc_html_e( 'Shop All', 'worldbite' ); ?> <span>⌄</span></a></li>
                <li><a href="<?php echo esc_url( worldbite_category_url( 'asian-pantry' ) ); ?>"><?php esc_html_e( 'Asian', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( worldbite_category_url( 'mediterranean' ) ); ?>"><?php esc_html_e( 'Mediterranean', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( worldbite_category_url( 'latin-american' ) ); ?>"><?php esc_html_e( 'Latin American', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( worldbite_category_url( 'middle-eastern' ) ); ?>"><?php esc_html_e( 'Middle Eastern', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( worldbite_category_url( 'african-caribbean' ) ); ?>"><?php esc_html_e( 'African', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( worldbite_category_url( 'european-classics' ) ); ?>"><?php esc_html_e( 'European', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( worldbite_category_url( 'south-asian' ) ); ?>"><?php esc_html_e( 'South Asian', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( get_post_type_archive_link( 'collection' ) ?: home_url( '/collections/' ) ); ?>"><?php esc_html_e( 'Collections', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/#about' ) ); ?>"><?php esc_html_e( 'About Us', 'worldbite' ); ?></a></li>
                <li><a href="<?php echo esc_url( home_url( '/#collections' ) ); ?>"><?php esc_html_e( 'Recipes', 'worldbite' ); ?></a></li>
                <li class="wb-deals"><a href="<?php echo esc_url( home_url( '/deals/' ) ); ?>"><span aria-hidden="true">◆</span> <?php esc_html_e( 'Deals', 'worldbite' ); ?></a></li>
            </ul>
        </div>
    </nav>
</header>
