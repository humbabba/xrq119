<?php

remove_filter( 'the_content', 'convert_smilies', 20 );

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'xrq119-tailwind',
        get_template_directory_uri() . '/dist/css/app.css',
        [],
        filemtime( get_template_directory() . '/dist/css/app.css' )
    );
} );

add_action( 'after_setup_theme', function () {
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', [
        'height'      => 40,
        'width'       => 160,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_editor_style( 'dist/css/app.css' );
    register_nav_menus( [ 'header' => 'Header Navigation' ] );
} );

/* ── Customizer: HUD settings ── */

add_action( 'customize_register', function ( $wp_customize ) {
    $wp_customize->add_section( 'xrq119_hud', [
        'title'    => 'HUD Bar Settings',
        'priority' => 30,
    ] );

    $wp_customize->add_setting( 'xrq119_header_screen_html', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'xrq119_header_screen_html', [
        'label'       => 'Header Screen HTML',
        'description' => 'Replaces the code rain screen with custom HTML. Leave empty for the default code rain effect.',
        'section'     => 'xrq119_hud',
        'type'        => 'textarea',
    ] );

    $wp_customize->add_setting( 'xrq119_footer_status_html', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ] );
    $wp_customize->add_control( 'xrq119_footer_status_html', [
        'label'       => 'Footer Status HTML',
        'description' => 'Replaces the pulsing "sys.online" indicator with custom HTML. Leave empty for the default.',
        'section'     => 'xrq119_hud',
        'type'        => 'textarea',
    ] );
} );

add_filter( 'body_class', function ( $classes ) {
    return array_merge( $classes, [ 'bg-gray-50', 'text-gray-900', 'font-sans' ] );
} );

add_filter( 'block_categories_all', function ( $categories ) {
    array_unshift( $categories, [
        'slug'  => 'xrq119',
        'title' => 'xrq119',
    ] );
    return $categories;
} );

/* ── External link meta ── */

add_action( 'init', function () {
    $meta_fields = [
        '_xrq119_external_link_enabled' => 'boolean',
        '_xrq119_external_url'          => 'string',
        '_xrq119_external_new_tab'      => 'boolean',
        '_xrq119_card_buttons'          => 'string',
    ];
    foreach ( $meta_fields as $key => $type ) {
        register_post_meta( 'post', $key, [
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => $type,
            'auth_callback' => fn() => current_user_can( 'edit_posts' ),
        ] );
    }
} );

function xrq119_get_external_link( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    if ( ! get_post_meta( $post_id, '_xrq119_external_link_enabled', true ) ) {
        return null;
    }
    $url = get_post_meta( $post_id, '_xrq119_external_url', true );
    if ( ! $url ) {
        return null;
    }
    return [
        'url'     => $url,
        'new_tab' => (bool) get_post_meta( $post_id, '_xrq119_external_new_tab', true ),
    ];
}

/* ── External link redirect ── */

add_action( 'template_redirect', function () {
    if ( ! is_singular( 'post' ) || isset( $_GET['xrq119'] ) ) {
        return;
    }
    $ext = xrq119_get_external_link( get_queried_object_id() );
    if ( $ext ) {
        wp_redirect( $ext['url'], 301 );
        exit;
    }
} );

/* ── Theme docs admin page ── */

add_action( 'admin_menu', function () {
    add_theme_page(
        'xrq119 Docs',
        'xrq119 Docs',
        'edit_posts',
        'xrq119-docs',
        'xrq119_render_docs_page'
    );
} );

function xrq119_render_docs_page() {
    $readme = get_template_directory() . '/README.md';
    if ( ! file_exists( $readme ) ) {
        echo '<div class="wrap"><h1>xrq119 Docs</h1><p>README.md not found.</p></div>';
        return;
    }
    $content = file_get_contents( $readme );

    // Simple Markdown to HTML conversion for the subset we use
    $html = esc_html( $content );

    // Code blocks (``` ... ```)
    $html = preg_replace_callback( '/^```(\w*)\n(.*?)^```/ms', function ( $m ) {
        return '<pre style="background:#f6f8fa;padding:16px;border-radius:6px;overflow-x:auto;font-size:13px;line-height:1.5"><code>' . $m[2] . '</code></pre>';
    }, $html );

    // Inline code
    $html = preg_replace( '/`([^`]+)`/', '<code style="background:#f6f8fa;padding:2px 6px;border-radius:3px;font-size:13px">$1</code>', $html );

    // Tables
    $html = preg_replace_callback( '/^(\|.+\|)\n(\|[-| :]+\|)\n((?:\|.+\|\n?)+)/m', function ( $m ) {
        $headers = array_filter( array_map( 'trim', explode( '|', $m[1] ) ) );
        $rows_raw = array_filter( explode( "\n", trim( $m[3] ) ) );
        $out = '<table class="widefat striped" style="margin:12px 0;max-width:100%"><thead><tr>';
        foreach ( $headers as $h ) {
            $out .= '<th style="padding:8px 12px;text-align:left">' . trim( $h ) . '</th>';
        }
        $out .= '</tr></thead><tbody>';
        foreach ( $rows_raw as $row ) {
            $cells = array_filter( array_map( 'trim', explode( '|', $row ) ) );
            $out .= '<tr>';
            foreach ( $cells as $cell ) {
                $out .= '<td style="padding:8px 12px">' . trim( $cell ) . '</td>';
            }
            $out .= '</tr>';
        }
        $out .= '</tbody></table>';
        return $out;
    }, $html );

    // Headings
    $html = preg_replace( '/^#### (.+)$/m', '<h4 style="margin-top:24px">$1</h4>', $html );
    $html = preg_replace( '/^### (.+)$/m', '<h3 style="margin-top:28px;font-size:16px">$1</h3>', $html );
    $html = preg_replace( '/^## (.+)$/m', '<h2 style="margin-top:32px;font-size:20px">$1</h2>', $html );
    $html = preg_replace( '/^# (.+)$/m', '<h1>$1</h1>', $html );

    // Bold and italic
    $html = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html );
    $html = preg_replace( '/&mdash;/', '&mdash;', $html );
    $html = preg_replace( '/&ndash;/', '&ndash;', $html );

    // Horizontal rules
    $html = preg_replace( '/^---$/m', '<hr style="margin:24px 0;border:none;border-top:1px solid #ddd">', $html );

    // Line breaks into paragraphs (blank lines)
    $html = preg_replace( '/\n{2,}/', '</p><p>', $html );
    $html = '<p>' . $html . '</p>';
    $html = str_replace( '<p></p>', '', $html );

    echo '<div class="wrap" style="max-width:800px;font-size:14px;line-height:1.7">';
    echo $html;
    echo '</div>';
}

/* ── Drag-and-drop ordering (categories + posts per category) ── */

add_action( 'wp_ajax_xrq119_save_cat_order', function () {
    check_ajax_referer( 'xrq119_sort' );
    if ( ! current_user_can( 'manage_categories' ) ) {
        wp_send_json_error();
    }
    $order = array_map( 'intval', $_POST['order'] ?? [] );
    foreach ( $order as $pos => $term_id ) {
        update_term_meta( $term_id, '_xrq119_order', $pos );
    }
    wp_send_json_success();
} );

add_action( 'wp_ajax_xrq119_save_post_order', function () {
    check_ajax_referer( 'xrq119_sort' );
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error();
    }
    $cat_id = (int) ( $_POST['cat_id'] ?? 0 );
    if ( ! $cat_id || ! term_exists( $cat_id, 'category' ) ) {
        wp_send_json_error();
    }
    $order = array_map( 'intval', $_POST['order'] ?? [] );
    update_term_meta( $cat_id, '_xrq119_post_order', $order );
    wp_send_json_success();
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    $asset_file = get_template_directory() . '/dist/js/admin-sortable.asset.php';
    if ( ! file_exists( $asset_file ) ) return;

    $data = null;

    if ( $hook === 'edit-tags.php' && ( $_GET['taxonomy'] ?? '' ) === 'category' ) {
        $data = [
            'mode'   => 'categories',
            'action' => 'xrq119_save_cat_order',
            'nonce'  => wp_create_nonce( 'xrq119_sort' ),
        ];
    }

    if ( $hook === 'edit.php' ) {
        $cat = null;
        if ( ! empty( $_GET['category_name'] ) ) {
            $cat = get_category_by_slug( sanitize_text_field( $_GET['category_name'] ) );
        } elseif ( ! empty( $_GET['cat'] ) ) {
            $cat = get_category( (int) $_GET['cat'] );
        }
        if ( $cat && ! is_wp_error( $cat ) ) {
            $data = [
                'mode'   => 'posts',
                'action' => 'xrq119_save_post_order',
                'nonce'  => wp_create_nonce( 'xrq119_sort' ),
                'cat_id' => $cat->term_id,
            ];
        }
    }

    if ( ! $data ) return;

    $asset = include $asset_file;
    wp_enqueue_script(
        'xrq119-admin-sortable',
        get_template_directory_uri() . '/dist/js/admin-sortable.js',
        $asset['dependencies'],
        $asset['version'],
        true
    );
    wp_add_inline_script( 'xrq119-admin-sortable', 'window.xrq119Sort = ' . wp_json_encode( $data ) . ';', 'before' );
    wp_add_inline_style( 'dashicons', '
        .xrq119-drag-handle { width: 32px; padding: 8px 4px !important; cursor: grab; color: #999; text-align: center; }
        .xrq119-drag-handle:hover { color: #444; }
        .xrq119-sort-ghost { opacity: 0.4; background: #f0f6fc; }
    ' );
} );

// Helper: resolve category from current admin request
function xrq119_get_admin_cat_id() {
    if ( ! empty( $_GET['category_name'] ) ) {
        $cat = get_category_by_slug( sanitize_text_field( $_GET['category_name'] ) );
        return $cat ? $cat->term_id : 0;
    }
    if ( ! empty( $_GET['cat'] ) ) {
        return (int) $_GET['cat'];
    }
    return 0;
}

// Order posts by saved order (frontend category archives + admin post list)
add_action( 'pre_get_posts', function ( $query ) {
    if ( ! $query->is_main_query() ) return;

    if ( ! is_admin() && $query->is_category() ) {
        $cat = get_queried_object();
        if ( ! $cat ) return;
        $cat_id = $cat->term_id;
    } elseif ( is_admin() ) {
        global $pagenow;
        if ( $pagenow !== 'edit.php' ) return;
        $cat_id = xrq119_get_admin_cat_id();
    } else {
        return;
    }

    if ( empty( $cat_id ) ) return;
    $order = get_term_meta( $cat_id, '_xrq119_post_order', true );
    if ( ! empty( $order ) && is_array( $order ) ) {
        $query->set( 'xrq119_post_order', $order );
    }
} );

add_filter( 'posts_clauses', function ( $clauses, $query ) {
    $order = $query->get( 'xrq119_post_order' );
    if ( empty( $order ) ) return $clauses;
    global $wpdb;
    $ids = implode( ',', array_map( 'intval', $order ) );
    $clauses['orderby'] = "FIELD({$wpdb->posts}.ID, {$ids}) = 0, FIELD({$wpdb->posts}.ID, {$ids}), {$wpdb->posts}.post_date DESC";
    return $clauses;
}, 10, 2 );

// Order categories by saved order in admin
add_filter( 'terms_clauses', function ( $clauses, $taxonomies ) {
    if ( ! is_admin() || ! in_array( 'category', (array) $taxonomies, true ) ) {
        return $clauses;
    }
    $screen = get_current_screen();
    if ( ! $screen || $screen->id !== 'edit-category' ) return $clauses;
    global $wpdb;
    $clauses['join']    .= " LEFT JOIN {$wpdb->termmeta} AS xrq_tm ON t.term_id = xrq_tm.term_id AND xrq_tm.meta_key = '_xrq119_order'";
    $clauses['orderby']  = "ORDER BY COALESCE(xrq_tm.meta_value+0, 999999) ASC, t.name";
    return $clauses;
}, 10, 2 );

// Handle unsort action
add_action( 'admin_init', function () {
    if ( empty( $_GET['xrq119_unsort'] ) ) return;
    check_admin_referer( 'xrq119_unsort' );

    if ( $_GET['xrq119_unsort'] === 'cats' ) {
        if ( ! current_user_can( 'manage_categories' ) ) return;
        $cats = get_categories( [ 'hide_empty' => false ] );
        foreach ( $cats as $cat ) {
            delete_term_meta( $cat->term_id, '_xrq119_order' );
        }
    } else {
        if ( ! current_user_can( 'edit_posts' ) ) return;
        $cat_id = (int) $_GET['xrq119_unsort'];
        if ( $cat_id ) {
            delete_term_meta( $cat_id, '_xrq119_post_order' );
        }
    }
    wp_safe_redirect( remove_query_arg( [ 'xrq119_unsort', '_wpnonce' ] ) );
    exit;
} );

// "Sorted" notice with unsort link
add_action( 'admin_notices', function () {
    $screen = get_current_screen();
    if ( ! $screen ) return;

    if ( $screen->id === 'edit-category' ) {
        $cats = get_categories( [ 'hide_empty' => false ] );
        $has_order = false;
        foreach ( $cats as $cat ) {
            if ( get_term_meta( $cat->term_id, '_xrq119_order', true ) !== '' ) {
                $has_order = true;
                break;
            }
        }
        if ( $has_order ) {
            $url = wp_nonce_url( add_query_arg( 'xrq119_unsort', 'cats' ), 'xrq119_unsort' );
            echo '<div class="notice notice-info"><p><strong>Sorted</strong> — categories are in custom drag-and-drop order. <a href="' . esc_url( $url ) . '">Unsort</a></p></div>';
        }
    }

    if ( $screen->id === 'edit-post' ) {
        $cat_id = xrq119_get_admin_cat_id();
        if ( ! $cat_id ) return;
        $order = get_term_meta( $cat_id, '_xrq119_post_order', true );
        if ( ! empty( $order ) && is_array( $order ) ) {
            $url = wp_nonce_url( add_query_arg( 'xrq119_unsort', $cat_id ), 'xrq119_unsort' );
            echo '<div class="notice notice-info"><p><strong>Sorted</strong> — posts are in custom drag-and-drop order. <a href="' . esc_url( $url ) . '">Unsort</a></p></div>';
        }
    }
} );

/* ── Blocks ── */

add_action( 'init', function () {
    if ( ! file_exists( get_template_directory() . '/dist/js/index.asset.php' ) ) {
        return;
    }
    $asset = include get_template_directory() . '/dist/js/index.asset.php';
    wp_register_script(
        'xrq119-blocks',
        get_template_directory_uri() . '/dist/js/index.js',
        $asset['dependencies'],
        $asset['version']
    );

    $blocks = [ 'feature-card', 'stat-card', 'skill-group', 'timeline-entry', 'icon-card' ];
    foreach ( $blocks as $block ) {
        register_block_type( "xrq119/$block", [
            'editor_script' => 'xrq119-blocks',
        ] );
    }
} );
