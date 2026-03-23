<?php
/* Front Page Template - bina7 (ACF Free, No Static Content) */
get_header();

$has_acf = function_exists('get_field'); // لا تستخدم $acf
if (!$has_acf) { get_footer(); return; }

function bina_get_text($key) {
    $v = get_field($key);
    return is_string($v) ? trim($v) : '';
}
function bina_get_url($key) {
    $v = get_field($key);
    return is_string($v) ? trim($v) : '';
}
function bina_get_img_url($key) {
    $img = get_field($key);
    if (is_array($img) && !empty($img['url'])) return $img['url'];
    if (is_string($img) && !empty($img)) return $img;
    return '';
}
?>

<!-- HERO -->
<section class="section_home">
  <div class="container">
    <div class="row align-items-center">

      <div class="col-lg-6">
        <div class="home_txt wow fadeInUp">
          <h1>
            <?php echo esc_html(bina_get_text('hero_title_1')); ?>
            <span><?php echo esc_html(bina_get_text('hero_title_highlight')); ?></span>
            <?php echo esc_html(bina_get_text('hero_title_2')); ?>
          </h1>

          <p><?php echo esc_html(bina_get_text('hero_description')); ?></p>

          <ul>
            <?php
              $b1t = bina_get_text('hero_btn1_title');
              $b1l = bina_get_url('hero_btn1_link');
              $b2t = bina_get_text('hero_btn2_title');
              $b2l = bina_get_url('hero_btn2_link');
            ?>
            <?php if ($b1t && $b1l): ?>
              <li><a href="<?php echo esc_url($b1l); ?>" class="btn-site"><span><?php echo esc_html($b1t); ?></span></a></li>
            <?php endif; ?>
            <?php if ($b2t && $b2l): ?>
              <li><a href="<?php echo esc_url($b2l); ?>" class="btn-site btn-oth"><span><?php echo esc_html($b2t); ?></span></a></li>
            <?php endif; ?>
          </ul>

        </div>
      </div>

      <div class="col-lg-6">
        <div class="thumb-hero wow fadeInUp">
          <?php $hero_img = bina_get_img_url('hero_image'); ?>
          <?php if ($hero_img): ?>
            <img src="<?php echo esc_url($hero_img); ?>" alt="Hero Image" />
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- HINT -->
<section class="section_hint">
  <div class="container">
    <div class="cont-hint wow fadeInUp">
      <p><?php echo esc_html(bina_get_text('hint_text')); ?></p>
    </div>
  </div>
</section>

<!-- WHY US -->
<section class="section_why_us">
  <div class="container">
    <div class="sec_head_why wow fadeInUp">
      <p><?php echo esc_html(bina_get_text('why_title')); ?></p>
    </div>

    <div class="row wow fadeInUp">
      <?php for ($i=1; $i<=4; $i++): ?>
        <div class="col-lg-3">
          <div class="item-why-us">
            <h4><?php echo esc_html(bina_get_text("why_{$i}_title")); ?></h4>
            <p><?php echo esc_html(bina_get_text("why_{$i}_desc")); ?></p>
            <?php $im = bina_get_img_url("why_{$i}_image"); ?>
            <?php if ($im): ?><figure><img src="<?php echo esc_url($im); ?>" alt="Image Why" /></figure><?php endif; ?>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- SUBSCRIPTIONS -->
<section class="section_subscriptions">
  <div class="container">
    <div class="sec_head wow fadeInUp">
      <h2><?php echo esc_html(bina_get_text('subs_title')); ?></h2>
      <p><?php echo esc_html(bina_get_text('subs_subtitle')); ?></p>
    </div>

    <div class="owl-carousel" id="subscriptions-slider">
      <?php for ($p=1; $p<=3; $p++): ?>
        <div class="item">
          <?php if ($p==2): ?>
            <div class="popular-plan wow fadeInUp">
              <div class="sp-popular"><strong><?php echo esc_html(bina_get_text('subs_2_popular_badge')); ?></strong></div>
              <div class="item-plan">
          <?php else: ?>
            <div class="item-plan wow fadeInUp">
          <?php endif; ?>

                <div class="info-plan">
                  <h6><?php echo esc_html(bina_get_text("subs_{$p}_title")); ?></h6>
                  <span><?php echo esc_html(bina_get_text("subs_{$p}_small_text")); ?></span>
                  <p>
                    <i class="icon-rsa"></i>
                    <b><?php echo esc_html(bina_get_text("subs_{$p}_price")); ?> / </b>
                    <?php echo esc_html(bina_get_text("subs_{$p}_duration")); ?>
                  </p>
                </div>

                <div class="includes-plan">
                  <b><?php echo esc_html__('Includes:', 'bina'); ?></b>
                  <ul>
                    <?php for ($k=1; $k<=4; $k++): ?>
                      <li><span><i class="fa-solid fa-check"></i></span> <?php echo esc_html(bina_get_text("subs_{$p}_inc_{$k}")); ?></li>
                    <?php endfor; ?>
                  </ul>
                </div>

                <?php
                  $bt = bina_get_text("subs_{$p}_btn_title");
                  $bl = bina_get_url("subs_{$p}_btn_link");
                ?>
                <?php if ($bt && $bl): ?>
                  <a href="<?php echo esc_url($bl); ?>" class="btn-site <?php echo ($p==2 ? '' : 'btn-oth'); ?>">
                    <span><?php echo esc_html($bt); ?></span>
                  </a>
                <?php endif; ?>

          <?php if ($p==2): ?>
              </div></div>
          <?php else: ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ARTICLES -->
<?php
// Helper: estimated reading time (fallback)
function bina_estimated_read_time($post_id) {
    $content = get_post_field('post_content', $post_id);
    $words = str_word_count(wp_strip_all_tags($content));
    $minutes = max(1, (int) ceil($words / 200)); // 200 wpm
    return $minutes . ' Minutes read';
}
?>

<section class="section_articles">
  <div class="container">

    <div class="sec_head wow fadeInUp">
      <h2><?php echo esc_html(get_field('articles_title')); ?></h2>
      <p><?php echo esc_html(get_field('articles_subtitle')); ?></p>
    </div>

    <div class="row">
      <?php
      // 2 featured cards
      $cards_q = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 2,
        'post_status'    => 'publish'
      ]);

      if ($cards_q->have_posts()):
        while ($cards_q->have_posts()): $cards_q->the_post();
          $pid = get_the_ID();
          $img = get_the_post_thumbnail_url($pid, 'large');
          $date = get_the_date('d M Y', $pid);

          // Optional per-post ACF field 'read_time'
          $read_time = function_exists('get_field') ? get_field('read_time', $pid) : '';
          if (!$read_time) $read_time = bina_estimated_read_time($pid);

          $excerpt = get_the_excerpt($pid);
      ?>
        <div class="col-lg-4">
          <div class="item-article wow fadeInUp">
            <?php if ($img): ?><figure><img src="<?php echo esc_url($img); ?>" alt="Image Article" /></figure><?php endif; ?>
            <div class="txt-art">
              <div>
                <span><?php echo esc_html($date); ?></span>
                <small><?php echo esc_html($read_time); ?></small>
              </div>
              <h6><?php the_title(); ?></h6>
              <p>
                <?php echo esc_html($excerpt); ?>
                <a href="<?php the_permalink(); ?>">Read more</a>
              </p>
            </div>
          </div>
        </div>
      <?php
        endwhile;
        wp_reset_postdata();
      endif;
      ?>

      <div class="col-lg-4">
        <div class="lst-articles wow fadeInUp">
          <?php
          // List: next 8 posts after the 2 cards
          $list_q = new WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => 8,
            'offset'         => 2,
            'post_status'    => 'publish'
          ]);

          if ($list_q->have_posts()):
            while ($list_q->have_posts()): $list_q->the_post();
              $pid = get_the_ID();
              $date = get_the_date('d M Y', $pid);
          ?>
            <div>
              <h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
              <span><?php echo esc_html($date); ?></span>
            </div>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- REQUEST -->
<section class="section_request">
  <div class="container">
    <div class="cont-request">
      <div class="row">
        <div class="col-lg-6">
          <div class="thumb-request">
            <?php $ri = bina_get_img_url('req_image'); ?>
            <?php if ($ri): ?><figure><img src="<?php echo esc_url($ri); ?>" alt="" /></figure><?php endif; ?>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="txt-request">
            <h4><?php echo esc_html(bina_get_text('req_title')); ?></h4>
            <p><?php echo esc_html(bina_get_text('req_desc')); ?></p>
            <?php $rbt = bina_get_text('req_btn_title'); $rbl = bina_get_url('req_btn_link'); ?>
            <?php if ($rbt && $rbl): ?>
              <a href="<?php echo esc_url($rbl); ?>" class="btn-site"><span><?php echo esc_html($rbt); ?></span></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
