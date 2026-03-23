<?php
// Path: template-parts/post-card.php

$thumbnail_url  = get_the_post_thumbnail_url();
$title          = get_the_title();
$excerpt        = get_the_excerpt();
$link           = get_the_permalink();


?>

<div class="post-card">
    <div class="thumbnail">
        <a href="<?= $link ?>">
            <img src="<?= $thumbnail_url ?>" alt="<?= $title ?>" onerror="this.onerror=null;this.src='<?php echo THEME_URL . '/assets/images/empty.png';?>'">
            <figcaption><?= $title ?></figcaption>
        </a>
    </div>
    <div class="title">
        <a href="<?= $link ?>"><?= $title ?></a>
    </div>
    <div class="excerpt">
        <?= $excerpt ?>
    </div>
    <div class="read-more">
        <a href="<?= $link ?>">قراءة المزيد <i class="fa-solid fa-arrow-left"></i></a>
    </div>
</div>