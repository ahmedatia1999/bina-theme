<?php

global  $wp_query;
$cat_id = $wp_query->get_queried_object_id();

$paged = (get_query_var('paged')) ? get_query_var('paged'): ((get_query_var('page')) ? get_query_var('page') : 1);
$args = array(
  'post_status' => array( 'publish' ),
  'cat'         => array($cat_id),
  'paged'       => $paged,
  'posts_per_page' => 12,
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

<div class="site-content-inner">
    <div class="container">
        <div class="row">

            <!-- content -->
            <div class="col-md-8">
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
            <div class="col-md-4">
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