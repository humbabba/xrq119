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
    add_editor_style( 'dist/app.css' );
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
