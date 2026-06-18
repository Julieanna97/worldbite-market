<?php get_header(); ?>
<main id="primary" class="wb-main">
    <div class="wb-container wb-page-shell">
        <?php while ( have_posts() ) : the_post(); ?>
            <header class="wb-page-header"><p class="wb-eyebrow"><?php esc_html_e( 'WorldBite', 'worldbite' ); ?></p><h1><?php the_title(); ?></h1></header>
            <article <?php post_class( 'wb-content' ); ?>><div class="entry-content"><?php the_content(); ?></div></article>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
