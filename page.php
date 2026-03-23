<?php

get_header();

?>


<main id="content" class="site-content single single-page">
    <h1 style="text-align: center; padding: 50px 0; background-color: #15423b17;margin-bottom: 50px;"><?=get_the_title()?></h1>
    <div class="site-content-inner pt-5">
        <div class="container">
            <div class="row" style="justify-content: center; padding-bottom: 50px;">
                <div class="col-md-3" style="display:none; position: relative;">
                    <?php echo file_get_contents(THEME_DIR . '/html/test.html'); ?>
                </div>
                <div class="col-md-9">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
</main>


<?php

get_footer();