<?php
function register_new_widgets($widgets_manager)
{

    // Require the widget files
    require_once(__DIR__ . '/widget-about-us.php');
    require_once(__DIR__ . '/widget-header.php');
    require_once(__DIR__ . '/widget-hero-stats.php');
    require_once(__DIR__ . '/widget-how-we-work.php');
    require_once(__DIR__ . '/widget-partners-marquee.php');

    // Register the widgets
    $widgets_manager->register(new \bina_About_Us_Widget());
    $widgets_manager->register(new \bina_Header_Widget());
    $widgets_manager->register(new \bina_Hero_Stats_Widget());
    $widgets_manager->register(new \bina_How_We_Work_Widget());
    $widgets_manager->register(new \bina_Partners_Marquee_Widget());
}
add_action('elementor/widgets/register', 'register_new_widgets');
