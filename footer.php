<?php

    if(is_front_page()){
        $footer_title = 'footer';
    } else {
        $footer_title = 'footer';
    }

    $footer_query = new WP_Query(array(
        'post_type' => 'footer',
        'posts_per_page' => 1, // Adjust the number of posts to display as needed
        'post_title'     => $footer_title,
    ));

    if ($footer_query->have_posts()) {
        while ($footer_query->have_posts()) : $footer_query->the_post();
            the_content();
        endwhile;
        wp_reset_postdata();
    }
?>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="<?php echo THEME_URL . '/assets/';?>js/bootstrap.min.js"></script>
    <script src="<?php echo THEME_URL . '/assets/';?>js/all.min.js"></script>
    <script src="<?php echo THEME_URL . '/assets/';?>js/owl.carousel.min.js"></script>
    <script src="<?php echo THEME_URL . '/assets/';?>js/wow.js"></script>
    <script src="<?php echo THEME_URL . '/assets/';?>js/jquery.easing.min.js"></script>
</body>

</html>


<?php

// if(is_front_page()){
//     $footer_title = 'footer home';
// } else {
//     $footer_title = 'footer';
// }

// $footer_query = new WP_Query(array(
//     'post_type' => 'footer',
//     'posts_per_page' => 1, // Adjust the number of posts to display as needed
//     'post_title'     => $footer_title,
// ));

// if ($footer_query->have_posts()) {
//     while ($footer_query->have_posts()) : $footer_query->the_post();
//         the_content();
//     endwhile;
//     wp_reset_postdata();
// }


wp_footer();