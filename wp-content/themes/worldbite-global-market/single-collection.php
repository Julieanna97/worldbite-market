<?php get_header(); ?>
<main id="primary" class="wb-main wb-collection-single">
    <div class="wb-container">
        <?php while ( have_posts() ) : the_post(); $ids = worldbite_collection_product_ids( get_the_ID() ); ?>
            <article>
                <div class="wb-collection-hero">
                    <div class="wb-collection-cover"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } else { echo '<span aria-hidden="true">🧺</span>'; } ?></div>
                    <div>
                        <p class="wb-eyebrow"><?php echo esc_html( sprintf( _n( '%d product', '%d products', count( $ids ), 'worldbite' ), count( $ids ) ) ); ?></p>
                        <h1><?php the_title(); ?></h1>
                        <div class="wb-collection-description"><?php the_content(); ?></div>
                        <?php if ( $ids && function_exists( 'mp_get_add_all_to_cart_url' ) ) : ?>
                            <a class="wb-button wb-button--coral" href="<?php echo esc_url( mp_get_add_all_to_cart_url( get_the_ID() ) ); ?>"><?php esc_html_e( 'Add all to cart', 'worldbite' ); ?> →</a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ( $ids ) : ?>
                    <h2><?php esc_html_e( 'Inside this collection', 'worldbite' ); ?></h2>
                    <div class="wb-collection-products">
                        <?php foreach ( $ids as $product_id ) : $product = wc_get_product( $product_id ); if ( ! $product ) { continue; } ?>
                            <a class="wb-collection-product" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                                <span class="wb-collection-product-media"><?php if ( has_post_thumbnail( $product_id ) ) { echo wp_kses_post( get_the_post_thumbnail( $product_id, 'woocommerce_thumbnail' ) ); } else { echo esc_html( worldbite_product_emoji( $product_id ) ); } ?></span>
                                <span><h3><?php echo esc_html( $product->get_name() ); ?></h3><span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="wb-empty"><div class="wb-empty-emoji">🧺</div><h2><?php esc_html_e( 'This collection is empty', 'worldbite' ); ?></h2></div>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
