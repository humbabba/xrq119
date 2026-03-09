<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
    $cats = get_the_category();
    $tags = get_the_tags();
    $thumb_id  = get_post_thumbnail_id();
    $caption   = $thumb_id ? wp_get_attachment_caption( $thumb_id ) : '';
?>

<article class="single-post max-w-2xl mx-auto px-6 py-16 sm:py-24">

    <?php if ( $cats ) :
        // Build ancestor chains, deduplicating parents already shown as a direct category
        $cat_ids = array_map( fn( $c ) => $c->term_id, $cats );
        $chains  = [];
        foreach ( $cats as $cat ) {
            $ancestors = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
            // Skip cats whose only role is being an ancestor of another assigned cat
            $chain = [];
            foreach ( $ancestors as $anc_id ) {
                $anc = get_category( $anc_id );
                if ( $anc ) $chain[] = $anc;
            }
            $chain[] = $cat;
            $chains[] = $chain;
        }
        // Remove chains that are a subset of another (e.g. "News" if "News > Breaking" exists)
        $chains = array_filter( $chains, function( $chain ) use ( $chains ) {
            $ids = array_map( fn( $c ) => $c->term_id, $chain );
            foreach ( $chains as $other ) {
                if ( $other === $chain ) continue;
                $other_ids = array_map( fn( $c ) => $c->term_id, $other );
                if ( count( $ids ) < count( $other_ids ) && $ids === array_slice( $other_ids, 0, count( $ids ) ) ) {
                    return false;
                }
            }
            return true;
        } );
    ?>
        <nav class="mb-4 font-mono text-xs tracking-widest uppercase">
            <?php foreach ( array_values( $chains ) as $ci => $chain ) : ?>
                <?php if ( $ci > 0 ) : ?>
                    <span class="text-gray-400 mx-1">/</span>
                <?php endif; ?>
                <?php foreach ( $chain as $j => $crumb ) : ?>
                    <?php if ( $j > 0 ) : ?>
                        <span class="text-gray-400 mx-0.5">&gt;</span>
                    <?php endif; ?>
                    <a href="<?= esc_url( get_category_link( $crumb ) ); ?>" class="text-cyan-600 hover:text-cyan-400 transition-colors"><?= esc_html( $crumb->name ); ?></a>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>

    <h1>
        <?php the_title(); ?>
    </h1>

    <?php if ( has_excerpt() ) : ?>
        <blockquote class="single-post__pullquote">
            <?= esc_html( get_the_excerpt() ); ?>
        </blockquote>
    <?php endif; ?>

    <?php if ( has_post_thumbnail() ) : ?>
        <figure class="single-post__figure">
            <?php the_post_thumbnail( 'large', [ 'class' => 'w-full rounded-lg' ] ); ?>
            <?php if ( $caption ) : ?>
                <figcaption class="single-post__caption"><?= wp_kses_post( $caption ); ?></figcaption>
            <?php endif; ?>
        </figure>
    <?php endif; ?>

    <div class="single-post__body">
        <?php the_content(); ?>
    </div>

    <?php if ( $tags ) : ?>
        <footer class="mt-12 pt-6 border-t border-gray-200">
            <div class="flex flex-wrap gap-2">
                <?php foreach ( $tags as $tag ) : ?>
                    <a href="<?= esc_url( get_tag_link( $tag ) ); ?>" class="px-2.5 py-0.5 text-xs font-medium rounded bg-cyan-50 text-cyan-700 border border-cyan-200/50 hover:bg-cyan-100 transition-colors"><?= esc_html( $tag->name ); ?></a>
                <?php endforeach; ?>
            </div>
        </footer>
    <?php endif; ?>

</article>

<?php endwhile; ?>

<?php get_footer(); ?>
