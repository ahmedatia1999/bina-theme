<?php
/* Template Name: Contact */
get_header();

$post_id = get_the_ID();

// ACF values (داخل نفس الصفحة)
$title = function_exists('get_field') ? get_field('contact_intro_title', $post_id) : '';
$desc  = function_exists('get_field') ? get_field('contact_intro_desc', $post_id)  : '';
$image = function_exists('get_field') ? get_field('contact_image', $post_id)       : '';
$form_title = function_exists('get_field') ? get_field('contact_form_title', $post_id) : '';
$form_desc  = function_exists('get_field') ? get_field('contact_form_desc', $post_id)  : '';
$form_sc    = function_exists('get_field') ? get_field('contact_form_shortcode', $post_id) : '';

// Fallbacks
if (!$title) $title = get_the_title($post_id);
if (!$desc) $desc = '';
if (!$image) $image = get_the_post_thumbnail_url($post_id, 'full');
if (!$image) $image = get_template_directory_uri() . '/assets/images/placeholder.jpg';

if (!$form_title) $form_title = 'Send Us a Message';
if (!$form_desc)  $form_desc  = 'Fill out the form and we’ll get back to you shortly.';
if (!$form_sc)    $form_sc    = '[contact-form-7 id="123" title="Contact form 1"]';
?>

<section class="section_contact page_sty">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="cont-touch">
          <div class="head-touch wow fadeInUp">
            <h2><?php echo esc_html($title); ?></h2>
            <?php if ($desc): ?>
              <p><?php echo esc_html($desc); ?></p>
            <?php endif; ?>
          </div>

          <div class="thumb-contact wow fadeInUp">
            <figure>
              <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" />
            </figure>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="cont-contact wow fadeInUp">
          <div class="head-contact">
            <h3><?php echo esc_html($form_title); ?></h3>
            <p><?php echo esc_html($form_desc); ?></p>
          </div>

          <div class="form-contact">
            <?php echo do_shortcode($form_sc); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
