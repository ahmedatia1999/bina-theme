<?php

wp_reset_query();

?>
<nav>
    <form action="<?php echo home_url();?>" class="search-from-widget">
        <input type="search" name="s" value="" placeholder="البحث عن .." />
        <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>
    <h2 class="h2-widget mt-5 mb-4">آخر الأخبار</h2>
    <div class="recent-posts-widget">
        <ul>
            <?php
                // Define the query arguments
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    'order' => 'DESC',
                    'orderby' => 'date'
                );

                // Create a new query using the arguments
                $query = new WP_Query($args);

                // Display the posts in a loop
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        ?>
                        <li>
                            <div class="thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <figure><?php 
                                    if (has_post_thumbnail()) {
                                        ?><img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" onerror="this.onerror=null;this.src='<?php echo THEME_URL . '/assets/images/empty.png';?>'"><?php
                                    } else {
                                        ?><img src="<?php echo THEME_URL; ?>/src/img/transparent.png" alt="<?php the_title(); ?>" onerror="this.onerror=null;this.src='<?php echo THEME_URL . '/assets/images/empty.png';?>'"><?php
                                    }
                                    ?></figure>
                                </a>
                            </div>
                            <div class="content">
                                <div class="title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </div>
                                <div class="date"><?php the_time('F j, Y')?></div>
                            </div>
                        </li>
                        <?php
                    }
                }

                // Reset the post data to the main query
                wp_reset_postdata();
            ?>
        </ul>
    </div>

    <h2 class="h2-widget">التصنيفات</h2>
    <div class="links-list-widget">
        <ul>
            <?php
                $args = array(
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => true //Set to false if you want to include empty categories
                );

                $categories = get_categories($args);

                if ($categories) {
                    foreach ($categories as $category) {
                        echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
                    }
                }
            ?>
        </ul>
    </div>


    <h2 class="h2-widget">الوسوم</h2>

    <div class="tags-cloud-widget">
        <div class="tags-cloud">
            <?php
                $tags = get_tags(array(
                    'orderby' => 'count',
                    'order' => 'DESC',
                    'number' => 10 //Change this number to display more or less tags
                ));

                if ($tags) {
                    foreach ($tags as $tag) {
                        $tag_link = get_tag_link($tag->term_id);
                        $tag_name = $tag->name;
                        $tag_count = $tag->count;
                        echo '<a href="' . $tag_link . '" class="post-tag tag-link-' . $tag->term_id . '" title="' . $tag_name . ' (' . $tag_count . ')">' . $tag_name . '</a> ';
                    }
                }
            ?>
        </div>
    </div>

</nav>