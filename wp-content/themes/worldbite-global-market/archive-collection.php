<?php
get_header();
$taxonomy = taxonomy_exists( 'product_collection' ) ? 'product_collection' : 'collection_category';
$search   = isset( $_GET['collection_search'] ) ? sanitize_text_field( wp_unslash( $_GET['collection_search'] ) ) : '';
$sort     = isset( $_GET['sort'] ) ? sanitize_key( $_GET['sort'] ) : 'latest';
$cat_id   = isset( $_GET['cat'] ) ? absint( $_GET['cat'] ) : 0;
$paged    = max( 1, get_query_var( 'paged' ) );
$args = array(
    'post_type'      => 'collection',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
    's'              => $search,
    'orderby'        => 'alpha' === $sort ? 'title' : 'date',
    'order'          => 'alpha' === $sort ? 'ASC' : 'DESC',
);
if ( $cat_id ) {
    $args['tax_query'] = array( array( 'taxonomy' => $taxonomy, 'field' => 'term_id', 'terms' => $cat_id ) );
}
$query = new WP_Query( $args );
$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
?>
<main id="primary" class="wb-main">
    <section class="wb-archive-hero"><div class="wb-container"><p class="wb-eyebrow"><?php esc_html_e( 'Community favourites', 'worldbite' ); ?></p><h1><?php esc_html_e( 'Food collections', 'worldbite' ); ?></h1><p><?php esc_html_e( 'Browse ready-made product bundles for themed meals, pantry restocks and flavour adventures.', 'worldbite' ); ?></p></div></section>
    <div class="wb-container wb-page-shell">
        <form class="wb-filter-bar" method="get">
            <label><span><?php esc_html_e( 'Category', 'worldbite' ); ?></span><select name="cat"><option value="0"><?php esc_html_e( 'All categories', 'worldbite' ); ?></option><?php foreach ( $terms as $term ) : ?><option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $cat_id, $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option><?php endforeach; ?></select></label>
            <label><span><?php esc_html_e( 'Sort', 'worldbite' ); ?></span><select name="sort"><option value="latest" <?php selected( $sort, 'latest' ); ?>><?php esc_html_e( 'Newest first', 'worldbite' ); ?></option><option value="alpha" <?php selected( $sort, 'alpha' ); ?>><?php esc_html_e( 'A–Z', 'worldbite' ); ?></option></select></label>
            <label><span><?php esc_html_e( 'Search', 'worldbite' ); ?></span><input type="search" name="collection_search" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Search collections…', 'worldbite' ); ?>"></label>
            <button type="submit"><?php esc_html_e( 'Apply', 'worldbite' ); ?></button>
        </form>

        <?php if ( $query->have_posts() ) : ?>
            <div class="wb-collection-grid">
                <?php while ( $query->have_posts() ) : $query->the_post(); $ids = worldbite_collection_product_ids( get_the_ID() ); ?>
                    <article class="wb-collection-card">
                        <a class="wb-collection-card-image" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } else { echo '<span aria-hidden="true">🧺</span>'; } ?></a>
                        <div class="wb-collection-card-body">
                            <span class="wb-collection-meta"><?php echo esc_html( sprintf( _n( '%d product', '%d products', count( $ids ), 'worldbite' ), count( $ids ) ) ); ?></span>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html( worldbite_collection_excerpt( get_the_ID() ) ); ?></p>
                            <a class="wb-text-link" href="<?php the_permalink(); ?>"><?php esc_html_e( 'View collection', 'worldbite' ); ?> →</a>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
            <div class="wb-pagination"><?php echo wp_kses_post( paginate_links( array( 'total' => $query->max_num_pages, 'current' => $paged ) ) ); ?></div>
        <?php else : ?>
            <div class="wb-empty"><div class="wb-empty-emoji">🧺</div><h2><?php esc_html_e( 'No collections found', 'worldbite' ); ?></h2><p><?php esc_html_e( 'Try a different search or create the first collection.', 'worldbite' ); ?></p></div>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
