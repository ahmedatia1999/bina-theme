<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <?php wp_head(); ?>
    
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->

</head>


<?php

function add_language_class_to_body( $classes ) {
    $current_lang = substr( get_locale(), 0, 2 );

    // Add language class to body
    if( $current_lang == 'ar' ) {
        $classes[] = 'ar'; // Arabic
    } else {
        $classes[] = 'en'; // English or other languages
    }
    
    return $classes;
}
add_filter( 'body_class', 'add_language_class_to_body' );

?>
<body <?php body_class(); ?>>

    <div class="main-wrapper <?php if(substr( get_locale(), 0, 2 ) == 'ar') echo 'rtl-style';?>">
  
    <div class="site-header-shell">
        <div class="header-inner">
<?php



if(is_front_page()){
    $header_slug = 'header';
} else {
    $header_slug = 'header';
}

$header_query = new WP_Query(array(
    'post_type'         => 'header',
    'posts_per_page'    => 1,
    'name'              => $header_slug,
));

if ($header_query->have_posts()) {
    while ($header_query->have_posts()) : $header_query->the_post();
        the_content();
    endwhile;
    wp_reset_postdata();
}


?>

    </div>
</div>
<div id="content">
