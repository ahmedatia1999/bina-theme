<?php
/**
 * Register widget area.
 */

if ( ! function_exists( 'theme_widgets_init' ) ) {
    function theme_widgets_init() {
        register_sidebar(
            array(
                'name' => esc_html__( 'Sidebar', 'bina'),
                'id' => 'sidebar-1',
                'description' => esc_html__( 'Add widgets here.', 'bina'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>',
            )
        );
    }
}
add_action( 'widgets_init', 'theme_widgets_init' );
?>
