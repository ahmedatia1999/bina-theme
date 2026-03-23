<?php
/**
 * Theme setup functions
 */

if ( ! function_exists( 'theme_setup' ) ) {
    function theme_setup() {
        // Make theme available for translation
        load_theme_textdomain( 'bina', get_template_directory() . '/languages' );

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable support for post thumbnails
        add_theme_support( 'post-thumbnails' );

        // Register navigation menus
        register_nav_menus(
            array(
                'primary' => esc_html__( 'Primary', 'bina'),
            )
        );

        // Switch default core markup for search form, comment form, and comments to output valid HTML5
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        // Add support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );
    }
}
add_action( 'after_setup_theme', 'theme_setup' );

?>
