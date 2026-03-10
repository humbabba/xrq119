<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php $screen_html = get_theme_mod( 'xrq119_header_screen_html', '' ); ?>

<header class="hud-bar hud-bar--top">
    <div class="max-w-5xl mx-auto px-6 flex items-center h-full gap-6">

        <a href="<?= esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2 shrink-0">
            <?php if ( has_custom_logo() ) :
                $logo_id  = get_theme_mod( 'custom_logo' );
                $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
            ?>
                <img src="<?= esc_url( $logo_url ); ?>" alt="<?= esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-6 w-auto">
            <?php else : ?>
                <span class="font-mono text-cyan-400 text-sm font-bold tracking-[0.15em] uppercase"><?= esc_html( get_bloginfo( 'name' ) ); ?></span>
            <?php endif; ?>
        </a>

        <?php
            // Gather supplemental menu items (bare <li>s, no wrapper)
            $supplemental_items = '';
            if ( has_nav_menu( 'supplemental' ) ) {
                $supplemental_items = wp_nav_menu( [
                    'theme_location' => 'supplemental',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'depth'          => 1,
                    'echo'           => false,
                ] );
            }
        ?>
        <nav class="hud-nav" data-priority-nav>
            <?php if ( has_nav_menu( 'header' ) ) :
                wp_nav_menu( [
                    'theme_location' => 'header',
                    'container'      => false,
                    'menu_class'     => 'flex items-baseline gap-4',
                    'depth'          => 1,
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s' . $supplemental_items . '</ul>',
                ] );
            else :
                $parents = get_categories( [ 'exclude' => get_cat_ID( 'Uncategorized' ), 'hide_empty' => true, 'parent' => 0 ] );
                usort( $parents, function( $a, $b ) {
                    $oa = (int) get_term_meta( $a->term_id, '_xrq119_order', true );
                    $ob = (int) get_term_meta( $b->term_id, '_xrq119_order', true );
                    return $oa - $ob;
                } );
                if ( $parents ) : ?>
                    <ul class="flex items-baseline gap-4">
                        <?php foreach ( $parents as $cat ) :
                            $children = get_categories( [ 'hide_empty' => true, 'parent' => $cat->term_id ] );
                            usort( $children, function( $a, $b ) {
                                $oa = (int) get_term_meta( $a->term_id, '_xrq119_order', true );
                                $ob = (int) get_term_meta( $b->term_id, '_xrq119_order', true );
                                return $oa - $ob;
                            } );
                        ?>
                            <?php if ( $children ) : ?>
                                <li class="hud-nav__has-children">
                                    <button class="hud-nav__parent-toggle" aria-expanded="false">
                                        <?= esc_html( $cat->name ); ?>
                                        <svg viewBox="0 0 12 12"><polyline points="2,4 6,8 10,4"/></svg>
                                    </button>
                                    <ul class="hud-nav__sub">
                                        <li><a href="<?= esc_url( get_category_link( $cat ) ); ?>">All <?= esc_html( $cat->name ); ?></a></li>
                                        <?php foreach ( $children as $child ) : ?>
                                            <li><a href="<?= esc_url( get_category_link( $child ) ); ?>"><?= esc_html( $child->name ); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php else : ?>
                                <li><a href="<?= esc_url( get_category_link( $cat ) ); ?>"><?= esc_html( $cat->name ); ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?= $supplemental_items; ?>
                    </ul>
                <?php endif;
            endif; ?>
        </nav>

        <?php if ( $screen_html ) : ?>
            <div class="hud-screen ml-auto"><?= wp_kses_post( $screen_html ); ?></div>
        <?php else : ?>
            <div class="hud-screen hud-screen--coderain ml-auto">
                <canvas id="xrq119-coderain" class="absolute inset-0 w-full h-full"></canvas>
                <div class="hud-screen__scanline"></div>
            </div>
        <?php endif; ?>

    </div>
</header>
