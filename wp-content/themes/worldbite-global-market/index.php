<?php get_header(); ?>
<main id="primary" class="wb-main">
    <div class="wb-container wb-page-shell">
        <header class="wb-page-header"><p class="wb-eyebrow"><?php esc_html_e( 'Latest', 'worldbite' ); ?></p><h1><?php bloginfo( 'name' ); ?></h1></header>
        <?php if ( have_posts() ) : ?>
            <div class="wb-collection-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article <?php post_class( 'wb-collection-card' ); ?>>
                        <a class="wb-collection-card-image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } else { echo '<span aria-hidden="true">🍽️</span>'; } ?></a>
                        <div class="wb-collection-card-body"><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p></div>
                    </article>
                <?php endwhile; ?>
            </div>
            <div class="wb-pagination"><?php the_posts_pagination(); ?></div>
        <?php else : ?>
            <div class="wb-empty"><div class="wb-empty-emoji">🍽️</div><h2><?php esc_html_e( 'Nothing here yet', 'worldbite' ); ?></h2></div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
