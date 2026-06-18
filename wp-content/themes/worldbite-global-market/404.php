<?php get_header(); ?>
<main id="primary" class="wb-main"><div class="wb-container wb-page-shell"><div class="wb-empty"><div class="wb-empty-emoji">🥣</div><h1><?php esc_html_e( 'This bowl is empty', 'worldbite' ); ?></h1><p><?php esc_html_e( 'The page you are looking for could not be found.', 'worldbite' ); ?></p><a class="wb-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the homepage', 'worldbite' ); ?></a></div></div></main>
<?php get_footer(); ?>
