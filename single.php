<?php

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

            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <!-- content -->
                <div class="col-md-8 single-post-content">
                    <article>
                        <h1><?=the_title()?></h1>
                        <div class="tag-line"></div>
                        <div class="thumbnail">
                            <figure>
                                <?php the_post_thumbnail() ?>
                                <figcaption><?php the_post_thumbnail_caption() ?></figcaption>
                            </figure>
                        </div>
                        
                        <?php the_content()?>

                    </article>
                </div>
            <?php endwhile; endif;?>
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