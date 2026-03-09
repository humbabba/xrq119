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

        <nav class="hud-nav" data-priority-nav>
            <?php if ( has_nav_menu( 'header' ) ) :
                wp_nav_menu( [
                    'theme_location' => 'header',
                    'container'      => false,
                    'menu_class'     => 'flex items-baseline gap-4',
                    'depth'          => 1,
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

<script>
(function(){
    var nav = document.querySelector('[data-priority-nav]');
    if (!nav) return;
    var ul = nav.querySelector('ul');
    if (!ul) return;

    // ── Category sub-menu toggles (event delegation) ──
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.hud-nav__parent-toggle');
        if (btn) {
            e.stopPropagation();
            var li = btn.closest('.hud-nav__has-children');
            var open = li.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', String(open));
            // Close other open category dropdowns
            document.querySelectorAll('.hud-nav__has-children.is-open').forEach(function(other){
                if (other !== li) {
                    other.classList.remove('is-open');
                    other.querySelector('.hud-nav__parent-toggle').setAttribute('aria-expanded', 'false');
                }
            });
            return;
        }
        // Close all category dropdowns when clicking outside
        document.querySelectorAll('.hud-nav__has-children.is-open').forEach(function(li){
            li.classList.remove('is-open');
            li.querySelector('.hud-nav__parent-toggle').setAttribute('aria-expanded', 'false');
        });
    });

    // ── Priority+ overflow nav ──
    ul.style.flexWrap = 'nowrap';

    var moreLi = document.createElement('li');
    moreLi.className = 'hud-nav__more';
    moreLi.style.display = 'none';
    moreLi.innerHTML =
        '<button class="hud-nav__more-toggle" aria-expanded="false">' +
            '<svg viewBox="0 0 12 12"><polyline points="2,4 6,8 10,4"/></svg>' +
        '</button>' +
        '<ul class="hud-nav__dropdown"></ul>';
    ul.appendChild(moreLi);

    var toggle = moreLi.querySelector('.hud-nav__more-toggle');
    var dropdown = moreLi.querySelector('.hud-nav__dropdown');
    var items = Array.from(ul.children).filter(function(li){ return li !== moreLi; });

    var widths = items.map(function(li){ return li.getBoundingClientRect().width; });
    moreLi.style.display = '';
    var moreWidth = moreLi.getBoundingClientRect().width;
    moreLi.style.display = 'none';

    var gap = 16;

    toggle.addEventListener('click', function(e){
        e.stopPropagation();
        var open = moreLi.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', String(open));
    });

    document.addEventListener('click', function(e){
        if (!moreLi.contains(e.target)) {
            moreLi.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    // Flatten a category-with-children <li> into plain dropdown links
    function flattenChildren(srcLi) {
        var sub = srcLi.querySelector('.hud-nav__sub');
        if (!sub) {
            var clone = srcLi.cloneNode(true);
            clone.style.display = '';
            return [clone];
        }
        var flatItems = [];
        var subLinks = sub.querySelectorAll('li');
        subLinks.forEach(function(child, idx){
            var li = document.createElement('li');
            if (idx === 0) {
                li.innerHTML = child.innerHTML;
            } else {
                var a = child.querySelector('a');
                li.innerHTML = '<a href="' + a.getAttribute('href') + '">- ' + a.textContent + '</a>';
            }
            flatItems.push(li);
        });
        return flatItems;
    }

    function update() {
        items.forEach(function(li){ li.style.display = ''; });
        dropdown.innerHTML = '';
        moreLi.style.display = 'none';
        moreLi.classList.remove('is-open');

        var available = nav.getBoundingClientRect().width;

        var total = 0;
        var breakIdx = -1;
        for (var i = 0; i < items.length; i++) {
            total += widths[i] + (i > 0 ? gap : 0);
            if (total > available) {
                breakIdx = i;
                break;
            }
        }

        if (breakIdx === -1) return;

        var limit = available - moreWidth - gap;
        total = 0;
        breakIdx = 0;
        for (var i = 0; i < items.length; i++) {
            total += widths[i] + (i > 0 ? gap : 0);
            if (total > limit) {
                breakIdx = i;
                break;
            }
        }

        moreLi.style.display = '';
        for (var i = breakIdx; i < items.length; i++) {
            items[i].style.display = 'none';
            flattenChildren(items[i]).forEach(function(li){
                dropdown.appendChild(li);
            });
        }
    }

    update();
    window.addEventListener('resize', update);
})();
</script>

<?php if ( ! $screen_html ) : ?>
<script>
(function(){
    const c = document.getElementById('xrq119-coderain');
    if (!c) return;
    const ctx = c.getContext('2d');
    const chars = 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン0123456789ABCDEF';
    let cols, drops;
    function init() {
        c.width = c.offsetWidth;
        c.height = c.offsetHeight;
        cols = Math.floor(c.width / 10);
        drops = Array(cols).fill(1);
    }
    function draw() {
        ctx.fillStyle = 'rgba(0, 5, 2, 0.15)';
        ctx.fillRect(0, 0, c.width, c.height);
        ctx.font = '10px monospace';
        for (let i = 0; i < cols; i++) {
            const ch = chars[Math.floor(Math.random() * chars.length)];
            ctx.fillStyle = drops[i] * 10 > c.height - 10 ? '#0f0' : 'rgba(0,255,70,0.6)';
            ctx.fillText(ch, i * 10, drops[i] * 10);
            if (drops[i] * 10 > c.height && Math.random() > 0.975) drops[i] = 0;
            drops[i]++;
        }
    }
    init();
    window.addEventListener('resize', init);
    setInterval(draw, 60);
})();
</script>
<?php endif; ?>
