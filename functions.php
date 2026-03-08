<?php

remove_filter( 'the_content', 'convert_smilies', 20 );

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'xrq119-tailwind',
        get_template_directory_uri() . '/dist/app.css',
        [],
        filemtime( get_template_directory() . '/dist/app.css' )
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
    add_editor_style( 'dist/app.css' );
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

/* ── Blocks ── */

add_action( 'init', function () {
    if ( ! file_exists( get_template_directory() . '/build/index.asset.php' ) ) {
        return;
    }
    $asset = include get_template_directory() . '/build/index.asset.php';
    wp_register_script(
        'xrq119-blocks',
        get_template_directory_uri() . '/build/index.js',
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
