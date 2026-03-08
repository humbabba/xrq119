<?php $footer_html = get_theme_mod( 'xrq119_footer_status_html', '' ); ?>

<footer class="hud-bar hud-bar--bottom">
    <div class="max-w-5xl mx-auto px-6 flex items-center justify-between h-full gap-4 font-mono text-[11px] tracking-widest uppercase">

        <?php if ( $footer_html ) : ?>
            <span class="text-cyan-300"><?= wp_kses_post( $footer_html ); ?></span>
        <?php else : ?>
            <span class="flex items-center gap-2 text-cyan-300">
                <span class="pulse-dot"></span>
                sys.online
            </span>
        <?php endif; ?>

        <span class="text-gray-400">&copy; <?= date( 'Y' ); ?> <?= esc_html( get_bloginfo( 'name' ) ); ?></span>

        <span class="text-gray-500 hidden sm:inline">xrq119</span>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
