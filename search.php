<?php
// Get the search query string
$search_query = get_search_query();


$paged = (get_query_var('paged')) ? get_query_var('paged'): ((get_query_var('page')) ? get_query_var('page') : 1);
// Define your WP_Query arguments
$args = array(
    'post_type'         => 'post', // Change this to match your post type if needed
    'posts_per_page'    => 12,     // Number of posts per page
    's'                 => $search_query, // The search query
    'paged'             => $paged // For pagination
);

query_posts($args);

get_header();

?>
<main id="content" class="site-content single single-post">

<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?=custom_breadcrumbs()?>
            </div>
        </div>
    </div>
</div>

<div class="site-content-inner pt-5">
    <div class="container">
        <div class="row">

            <!-- content -->
            <div class="col-md-12">
                <div class="row">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <div class="col-md-6">
                        <?php get_template_part('template-parts/post-card', get_post_type()); ?>
                        </div>
                    <?php endwhile; endif;?>
                </div>
                <div>
                    <?php
                        zad_pagination();
                    ?>
                </div>
            </div>
            <!-- site bar -->
            <div class="col-md-4 d-none">
                <?php get_sidebar() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <hr style="margin: 30px 0 70px 0">
            </div>
        </div>
    </div>
</div>
</main>
<?php

get_footer();