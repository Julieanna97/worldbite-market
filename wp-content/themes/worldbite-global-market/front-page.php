<?php
get_header();

$cuisines = array(
    array( 'Asian', 'asian-pantry', 'category-asian.jpg' ),
    array( 'Mediterranean', 'mediterranean', 'category-mediterranean.jpg' ),
    array( 'Latin American', 'latin-american', 'category-latin-american.jpg' ),
    array( 'Middle Eastern', 'middle-eastern', 'category-middle-eastern.jpg' ),
    array( 'African', 'african-caribbean', 'category-african.jpg' ),
    array( 'European', 'european-classics', 'category-european.jpg' ),
    array( 'South Asian', 'south-asian', 'category-south-asian.jpg' ),
    array( 'Shop All', '', 'category-shop-all.jpg' ),
);
?>
<main id="primary" class="wb-main wb-home">
    <section class="wb-hero">
        <div class="wb-container wb-hero-shell">
            <div class="wb-hero-copy">
                <h1><?php esc_html_e( 'Flavours from', 'worldbite' ); ?><br><em><?php esc_html_e( 'Every Corner', 'worldbite' ); ?></em><br><?php esc_html_e( 'of the World', 'worldbite' ); ?></h1>
                <p><?php esc_html_e( 'Discover authentic ingredients and global delicacies to cook, share and enjoy.', 'worldbite' ); ?></p>
                <div class="wb-hero-actions">
                    <a class="wb-button" href="<?php echo esc_url( worldbite_shop_url() ); ?>"><?php esc_html_e( 'Shop Now', 'worldbite' ); ?> <span aria-hidden="true">→</span></a>
                    <a class="wb-inline-link" href="<?php echo esc_url( get_post_type_archive_link( 'collection' ) ?: home_url( '/collections/' ) ); ?>"><?php esc_html_e( 'Explore Collections', 'worldbite' ); ?></a>
                </div>
            </div>
            <div class="wb-hero-photo" role="img" aria-label="<?php esc_attr_e( 'Ramen, dumplings, herbs and global ingredients', 'worldbite' ); ?>"></div>
            <div class="wb-hero-dots" aria-hidden="true"><span class="is-active"></span><span></span><span></span></div>
        </div>
    </section>

    <section class="wb-cuisine-strip" aria-labelledby="cuisine-heading">
        <h2 id="cuisine-heading" class="screen-reader-text"><?php esc_html_e( 'Shop by cuisine', 'worldbite' ); ?></h2>
        <div class="wb-container wb-cuisine-row">
            <?php foreach ( $cuisines as $cuisine ) : ?>
                <?php $url = $cuisine[1] ? worldbite_category_url( $cuisine[1] ) : worldbite_shop_url(); ?>
                <a class="wb-cuisine-item" href="<?php echo esc_url( $url ); ?>">
                    <span class="wb-cuisine-image"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $cuisine[2] ); ?>" alt="" loading="lazy"></span>
                    <span><?php echo esc_html( $cuisine[0] ); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
        <section class="wb-section wb-featured-products">
            <div class="wb-container wb-products-panel">
                <div class="wb-section-title">
                    <h2><?php esc_html_e( 'Featured Products', 'worldbite' ); ?></h2>
                    <a href="<?php echo esc_url( worldbite_shop_url() ); ?>"><?php esc_html_e( 'View all products', 'worldbite' ); ?> <span aria-hidden="true">→</span></a>
                </div>
                <?php echo do_shortcode( '[products limit="6" columns="6" orderby="date" order="DESC"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </section>
    <?php endif; ?>

    <section id="about" class="wb-section wb-promise-section">
        <div class="wb-container">
            <div class="wb-section-heading-centered">
                <span><?php esc_html_e( 'Why WorldBite', 'worldbite' ); ?></span>
                <h2><?php esc_html_e( 'A world of flavour, made simple', 'worldbite' ); ?></h2>
                <p><?php esc_html_e( 'Build a pantry inspired by the meals and traditions you love.', 'worldbite' ); ?></p>
            </div>
            <div class="wb-promise-grid">
                <article><span aria-hidden="true">◎</span><h3><?php esc_html_e( 'Explore globally', 'worldbite' ); ?></h3><p><?php esc_html_e( 'Browse pantry staples and flavours from cuisines around the world.', 'worldbite' ); ?></p></article>
                <article><span aria-hidden="true">◇</span><h3><?php esc_html_e( 'Curate your favourites', 'worldbite' ); ?></h3><p><?php esc_html_e( 'Group products into useful collections for meals, gifts and events.', 'worldbite' ); ?></p></article>
                <article><span aria-hidden="true">✓</span><h3><?php esc_html_e( 'Shop with confidence', 'worldbite' ); ?></h3><p><?php esc_html_e( 'Enjoy a clean WooCommerce experience with secure test checkout.', 'worldbite' ); ?></p></article>
            </div>
        </div>
    </section>

    <?php
    $collections = new WP_Query(
        array(
            'post_type'      => 'collection',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
        )
    );
    ?>
    <section id="collections" class="wb-section wb-collections-section">
        <div class="wb-container">
            <div class="wb-section-heading">
                <div><span><?php esc_html_e( 'Curated for you', 'worldbite' ); ?></span><h2><?php esc_html_e( 'Global food collections', 'worldbite' ); ?></h2></div>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'collection' ) ?: home_url( '/collections/' ) ); ?>"><?php esc_html_e( 'Browse all collections', 'worldbite' ); ?> →</a>
            </div>

            <?php if ( $collections->have_posts() ) : ?>
                <div class="wb-collection-grid">
                    <?php while ( $collections->have_posts() ) : $collections->the_post(); ?>
                        <?php $ids = worldbite_collection_product_ids( get_the_ID() ); ?>
                        <article class="wb-collection-card">
                            <a class="wb-collection-card-image" href="<?php the_permalink(); ?>">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'large' ); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/hero-world-food.jpg' ); ?>" alt="" loading="lazy">
                                <?php endif; ?>
                            </a>
                            <div class="wb-collection-card-body">
                                <span class="wb-collection-meta"><?php echo esc_html( sprintf( _n( '%d product', '%d products', count( $ids ), 'worldbite' ), count( $ids ) ) ); ?></span>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo esc_html( worldbite_collection_excerpt( get_the_ID(), 16 ) ); ?></p>
                                <a class="wb-inline-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View collection', 'worldbite' ); ?></a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php else : ?>
                <div class="wb-empty"><h3><?php esc_html_e( 'Your global collections will appear here.', 'worldbite' ); ?></h3><a class="wb-button" href="<?php echo esc_url( home_url( '/create-collection/' ) ); ?>"><?php esc_html_e( 'Create a collection', 'worldbite' ); ?></a></div>
            <?php endif; ?>
        </div>
    </section>

    <section class="wb-section wb-cta-section">
        <div class="wb-container wb-cta-panel">
            <div><span><?php esc_html_e( 'Make it yours', 'worldbite' ); ?></span><h2><?php esc_html_e( 'Create a shopping collection for your next food adventure.', 'worldbite' ); ?></h2></div>
            <a class="wb-button wb-button-light" href="<?php echo esc_url( home_url( '/create-collection/' ) ); ?>"><?php esc_html_e( 'Create a collection', 'worldbite' ); ?> →</a>
        </div>
    </section>
</main>
<?php get_footer(); ?>
