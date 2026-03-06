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
} );

add_filter( 'body_class', function ( $classes ) {
    return array_merge( $classes, [ 'bg-gray-50', 'text-gray-900', 'font-sans' ] );
} );
