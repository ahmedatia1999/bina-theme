<?php
function register_new_widgets($widgets_manager)
{

    // Require the widget files
    require_once(__DIR__ . '/widget-about-us.php');
    require_once(__DIR__ . '/widget-header.php');
    require_once(__DIR__ . '/widget-hero-stats.php');
    require_once(__DIR__ . '/widget-how-we-work.php');
    require_once(__DIR__ . '/widget-how-we-work-hero.php');
    require_once(__DIR__ . '/widget-how-we-work-customer-journey.php');
    require_once(__DIR__ . '/widget-how-we-work-contractor-journey.php');
    require_once(__DIR__ . '/widget-how-we-work-alerts.php');
    require_once(__DIR__ . '/widget-financing-hero.php');
    require_once(__DIR__ . '/widget-financing-about-service.php');
    require_once(__DIR__ . '/widget-financing-why-choose.php');
    require_once(__DIR__ . '/widget-partners-marquee.php');
    require_once(__DIR__ . '/widget-services.php');
    require_once(__DIR__ . '/widget-why-us.php');
    require_once(__DIR__ . '/widget-implementation-service.php');
    require_once(__DIR__ . '/widget-faq.php');
    require_once(__DIR__ . '/widget-reviews-swiper.php');
    require_once(__DIR__ . '/widget-footer.php');

    // Register the widgets
    $widgets_manager->register(new \bina_About_Us_Widget());
    $widgets_manager->register(new \bina_Header_Widget());
    $widgets_manager->register(new \bina_Hero_Stats_Widget());
    $widgets_manager->register(new \bina_How_We_Work_Widget());
    $widgets_manager->register(new \bina_How_We_Work_Hero_Widget());
    $widgets_manager->register(new \bina_How_We_Work_Customer_Journey_Widget());
    $widgets_manager->register(new \bina_How_We_Work_Contractor_Journey_Widget());
    $widgets_manager->register(new \bina_How_We_Work_Alerts_Widget());
    $widgets_manager->register(new \bina_Financing_Hero_Widget());
    $widgets_manager->register(new \bina_Financing_About_Service_Widget());
    $widgets_manager->register(new \bina_Financing_Why_Choose_Widget());
    $widgets_manager->register(new \bina_Partners_Marquee_Widget());
    $widgets_manager->register(new \bina_Services_Widget());
    $widgets_manager->register(new \bina_Why_Us_Widget());
    $widgets_manager->register(new \bina_Implementation_Service_Widget());
    $widgets_manager->register(new \bina_FAQ_Widget());
    $widgets_manager->register(new \bina_Reviews_Swiper_Widget());
    $widgets_manager->register(new \bina_Footer_Widget());
}
add_action('elementor/widgets/register', 'register_new_widgets');
