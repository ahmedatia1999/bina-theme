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
    require_once(__DIR__ . '/widget-financing-beneficiaries.php');
    require_once(__DIR__ . '/widget-financing-how-it-works.php');
    require_once(__DIR__ . '/widget-financing-form-shortcode.php');
    require_once(__DIR__ . '/widget-financing-team-message.php');
    require_once(__DIR__ . '/widget-financing-disclaimer.php');
    require_once(__DIR__ . '/widget-contractors-hero.php');
    require_once(__DIR__ . '/widget-contractors-benefits.php');
    require_once(__DIR__ . '/widget-contractors-mawthooq.php');
    require_once(__DIR__ . '/widget-contractors-success-stories.php');
    require_once(__DIR__ . '/widget-contractors-requirements.php');
    require_once(__DIR__ . '/widget-contractors-faq.php');
    require_once(__DIR__ . '/widget-brokers-hero.php');
    require_once(__DIR__ . '/widget-brokers-about.php');
    require_once(__DIR__ . '/widget-brokers-how-it-works.php');
    require_once(__DIR__ . '/widget-brokers-commission.php');
    require_once(__DIR__ . '/widget-brokers-why-join.php');
    require_once(__DIR__ . '/widget-brokers-cta.php');
    require_once(__DIR__ . '/widget-engineers-hero.php');
    require_once(__DIR__ . '/widget-engineers-why-join.php');
    require_once(__DIR__ . '/widget-engineers-how-it-works.php');
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
    $widgets_manager->register(new \bina_Financing_Beneficiaries_Widget());
    $widgets_manager->register(new \bina_Financing_How_It_Works_Widget());
    $widgets_manager->register(new \bina_Financing_Form_Shortcode_Widget());
    $widgets_manager->register(new \bina_Financing_Team_Message_Widget());
    $widgets_manager->register(new \bina_Financing_Disclaimer_Widget());
    $widgets_manager->register(new \bina_Contractors_Hero_Widget());
    $widgets_manager->register(new \bina_Contractors_Benefits_Widget());
    $widgets_manager->register(new \bina_Contractors_Mawthooq_Widget());
    $widgets_manager->register(new \bina_Contractors_Success_Stories_Widget());
    $widgets_manager->register(new \bina_Contractors_Requirements_Widget());
    $widgets_manager->register(new \bina_Contractors_FAQ_Widget());
    $widgets_manager->register(new \bina_Brokers_Hero_Widget());
    $widgets_manager->register(new \bina_Brokers_About_Widget());
    $widgets_manager->register(new \bina_Brokers_How_It_Works_Widget());
    $widgets_manager->register(new \bina_Brokers_Commission_Widget());
    $widgets_manager->register(new \bina_Brokers_Why_Join_Widget());
    $widgets_manager->register(new \bina_Brokers_CTA_Widget());
    $widgets_manager->register(new \bina_Engineers_Hero_Widget());
    $widgets_manager->register(new \bina_Engineers_Why_Join_Widget());
    $widgets_manager->register(new \bina_Engineers_How_It_Works_Widget());
    $widgets_manager->register(new \bina_Partners_Marquee_Widget());
    $widgets_manager->register(new \bina_Services_Widget());
    $widgets_manager->register(new \bina_Why_Us_Widget());
    $widgets_manager->register(new \bina_Implementation_Service_Widget());
    $widgets_manager->register(new \bina_FAQ_Widget());
    $widgets_manager->register(new \bina_Reviews_Swiper_Widget());
    $widgets_manager->register(new \bina_Footer_Widget());
}
add_action('elementor/widgets/register', 'register_new_widgets');
