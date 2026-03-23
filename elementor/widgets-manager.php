<?php
function register_new_widgets($widgets_manager)
{

    // Require the widget files
    require_once(__DIR__ . '/widget-about-us.php');
    require_once(__DIR__ . '/widget-hero-home.php');
    require_once(__DIR__ . '/widget-hint.php');
    require_once(__DIR__ . '/widget-why-us.php');
    require_once(__DIR__ . '/widget-subscriptions.php');
    require_once(__DIR__ . '/widget-header.php');
    require_once(__DIR__ . '/widget-footer.php');

    // Register the widgets
    $widgets_manager->register(new \bina_About_Us_Widget());
    $widgets_manager->register(new \bina_Hero_Home_Widget());
    $widgets_manager->register(new \bina_Hint_Widget());
    $widgets_manager->register(new \bina_Why_Us_Widget());
    $widgets_manager->register(new \bina_Subscriptions_Widget());
    $widgets_manager->register(new \bina_Header_Widget());
    $widgets_manager->register(new \bina_Footer_Widget());
    
}
add_action('elementor/widgets/register', 'register_new_widgets');
