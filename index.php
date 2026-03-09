<?php get_header(); ?>

<main class="max-w-5xl mx-auto px-6 py-16 sm:py-24">

    <?php if ( is_archive() || is_home() ) :
        if (is_category()) {
            $archive_title = single_cat_title('', false);
        } elseif (is_tag()) {
            $archive_title = single_tag_title('', false);
        } elseif (is_author()) {
            $archive_title = get_the_author();
        } else {
            $archive_title = 'Posts';
        }
        $archive_desc  = is_archive() ? get_the_archive_description() : '';
    ?>
        <h1 class="text-3xl sm:text-4xl font-bold mb-4 font-mono text-cyan-600 tracking-[0.2em] uppercase">// <?= esc_html( $archive_title ); ?></h1>
        <?php if ( $archive_desc ) : ?>
            <div class="text-gray-600 text-lg mb-12 max-w-2xl"><?= wp_kses_post( $archive_desc ); ?></div>
        <?php else : ?>
            <div class="mb-12"></div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ( have_posts() ) : the_post();
                $ext    = xrq119_get_external_link();
                $href   = $ext ? esc_url( $ext['url'] ) : get_the_permalink();
                $target = $ext && $ext['new_tab'] ? ' target="_blank" rel="noopener"' : '';
                $card_buttons = json_decode( get_post_meta( get_the_ID(), '_xrq119_card_buttons', true ) ?: '[]', true );
            ?>
                <div class="corner-accent border border-gray-200 rounded-xl overflow-hidden bg-white hover-glow flex flex-col">
                    <a href="<?= $href; ?>"<?= $target; ?> class="group block">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="aspect-video overflow-hidden">
                                <?php the_post_thumbnail( 'medium_large', [ 'class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105' ] ); ?>
                            </div>
                        <?php else : ?>
                            <div class="aspect-video bg-gradient-to-br from-gray-900 via-slate-900 to-cyan-950 flex items-center justify-center">
                                <span class="font-mono text-cyan-400/50 text-sm">// <?= esc_html( get_the_date( 'Y' ) ); ?></span>
                            </div>
                        <?php endif; ?>
                    </a>
                    <div class="p-5 flex flex-col grow">
                        <h2 class="font-bold text-lg mb-2">
                            <a href="<?= $href; ?>"<?= $target; ?> class="hover:text-cyan-600 transition-colors"><?php the_title(); ?></a>
                        </h2>
                        <?php if ( has_excerpt() ) : ?>
                            <p class="text-gray-600 text-sm leading-relaxed"><?= esc_html( get_the_excerpt() ); ?></p>
                        <?php endif; ?>
                        <?php
                        $tags = get_the_tags();
                        if ( $tags ) : ?>
                            <div class="flex flex-wrap gap-2 mt-3">
                                <?php foreach ( $tags as $tag ) : ?>
                                    <span class="px-2.5 py-0.5 text-xs font-medium rounded bg-cyan-50 text-cyan-700 border border-cyan-200/50"><?= esc_html( $tag->name ); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ( $card_buttons ) : ?>
                            <div class="flex flex-wrap gap-2 mt-4 pt-3 border-t border-gray-100">
                                <?php foreach ( $card_buttons as $btn ) :
                                    if ( empty( $btn['label'] ) ) continue;
                                    $btn_url        = ! empty( $btn['url'] ) ? $btn['url'] : get_the_permalink();
                                    $btn_color      = ! empty( $btn['color'] ) ? $btn['color'] : '#0891b2';
                                    $btn_text_color = ! empty( $btn['textColor'] ) ? $btn['textColor'] : '#ffffff';
                                    $btn_target     = ! empty( $btn['new_tab'] ) ? ' target="_blank" rel="noopener"' : '';
                                    $btn_class      = ! empty( $btn['className'] ) ? ' ' . esc_attr( $btn['className'] ) : '';
                                ?>
                                    <a href="<?= esc_url( $btn_url ); ?>"<?= $btn_target; ?> class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-mono font-medium rounded transition-opacity hover:opacity-80<?= $btn_class; ?>" style="background-color: <?= esc_attr( $btn_color ); ?>; color: <?= esc_attr( $btn_text_color ); ?>"><?= esc_html( $btn['label'] ); ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="mt-12">
            <?php the_posts_pagination( [
                'prev_text' => '&larr; Previous',
                'next_text' => 'Next &rarr;',
                'class'     => 'font-mono text-sm',
            ] ); ?>
        </div>

    <?php else : ?>
        <p class="text-gray-500">Silence is golden.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
