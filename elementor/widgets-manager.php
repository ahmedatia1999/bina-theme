<?php
function register_new_widgets($widgets_manager)
{

    // Require the widget files
    require_once(__DIR__ . '/widget-about-us.php');

    // Register the widgets
    $widgets_manager->register(new \bina_About_Us_Widget());
}
add_action('elementor/widgets/register', 'register_new_widgets');
