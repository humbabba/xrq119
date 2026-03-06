<?php get_header(); ?>

<main class="max-w-5xl mx-auto px-6 py-16 sm:py-24">

    <?php if ( is_home() && ! is_front_page() ) : ?>
        <p class="font-mono text-cyan-600 text-xs tracking-[0.2em] uppercase mb-3">// portfolio</p>
        <h1 class="text-3xl sm:text-4xl font-bold mb-12 tracking-tight">Projects</h1>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ( have_posts() ) : the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="group corner-accent border border-gray-200 rounded-xl overflow-hidden bg-white hover-glow block">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="aspect-video overflow-hidden">
                            <?php the_post_thumbnail( 'medium_large', [ 'class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105' ] ); ?>
                        </div>
                    <?php else : ?>
                        <div class="aspect-video bg-gradient-to-br from-gray-900 via-slate-900 to-cyan-950 flex items-center justify-center">
                            <span class="font-mono text-cyan-400/50 text-sm">// <?php echo esc_html( get_the_date( 'Y' ) ); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="p-5">
                        <h2 class="font-bold text-lg mb-2 group-hover:text-cyan-600 transition-colors"><?php the_title(); ?></h2>
                        <?php if ( has_excerpt() ) : ?>
                            <p class="text-gray-600 text-sm leading-relaxed"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <?php endif; ?>
                        <p class="font-mono text-cyan-600 text-xs mt-3 uppercase tracking-wider"><?php echo esc_html( get_the_date( 'M Y' ) ); ?></p>
                    </div>
                </a>
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
        <p class="text-gray-500">No projects found.</p>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
