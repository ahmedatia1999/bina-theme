<?php
/**
 * Enqueue scripts and styles
 */
 
 if ( ! function_exists( 'theme_scripts' ) ) {
    function theme_scripts() {

        // Enqueue Google Fonts or Custom Font CDN links
        echo '<link rel="preload" href="https://fonts.cdnfonts.com/css/avenir-lt-pro?styles=60926,60921,60923,60919,60915" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        echo '<link rel="preload" href="https://fonts.cdnfonts.com/css/somar-sans?styles=143705,143693,143669" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        // Poppins (for English LTR)
        wp_enqueue_style(
            'bina-poppins',
            'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap',
            array(),
            null
        );

        // Enqueue jQuery
        wp_enqueue_script( 'jquery' );

        // Register (do NOT globally enqueue) Bootstrap CSS.
        // It conflicts with Tailwind utility names like `.bg-primary` (Bootstrap uses `!important`).
        wp_register_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2' );

        // Register Font Awesome (enqueue only when needed)
        wp_register_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );

        // Register theme CSS (from assets/css)
        $style_css = get_template_directory() . '/assets/css/style.css';
        $sonner_css = get_template_directory() . '/assets/css/sonner.css';
        $css2_css = get_template_directory() . '/assets/css/css2.css';

        if ( file_exists( $style_css ) ) {
            wp_enqueue_style( 'bina-style', get_template_directory_uri() . '/assets/css/style.css', array(), filemtime( $style_css ) );
        }

        // Sonner toast styles (moved from assets/index.html inline <style>)
        if ( file_exists( $sonner_css ) ) {
            wp_enqueue_style( 'bina-sonner', get_template_directory_uri() . '/assets/css/sonner.css', array( 'bina-style' ), filemtime( $sonner_css ) );
        }

        if ( file_exists( $css2_css ) ) {
            wp_register_style( 'bina-css2', get_template_directory_uri() . '/assets/css/css2.css', array( 'bina-style' ), filemtime( $css2_css ) );
        }

        // Register theme JS (from assets/js)
        $script_js = get_template_directory() . '/assets/js/script.js';
        $bootstrap_js = get_template_directory() . '/assets/js/bootstrap.min.js';
        $owl_js = get_template_directory() . '/assets/js/owl.carousel.min.js';
        $wow_js = get_template_directory() . '/assets/js/wow.js';
        $easing_js = get_template_directory() . '/assets/js/jquery.easing.min.js';
        $all_min_js = get_template_directory() . '/assets/js/all.min.js';

        // Swiper (CDN) - required for `.reviews-swiper` and any Swiper sections
        // Loaded globally to keep Elementor widgets functional without page-level conditions.
        wp_enqueue_style(
            'bina-swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0.0'
        );
        wp_enqueue_script(
            'bina-swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0.0',
            true
        );

        // Do NOT enqueue bundled jquery-3.2.1.min.js (WordPress already provides jQuery)
        if ( file_exists( $bootstrap_js ) ) {
            wp_register_script( 'bina-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), filemtime( $bootstrap_js ), true );
        }
        if ( file_exists( $owl_js ) ) {
            wp_register_script( 'bina-owl', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array( 'jquery' ), filemtime( $owl_js ), true );
        }
        if ( file_exists( $wow_js ) ) {
            wp_register_script( 'bina-wow', get_template_directory_uri() . '/assets/js/wow.js', array(), filemtime( $wow_js ), true );
        }
        if ( file_exists( $easing_js ) ) {
            wp_register_script( 'bina-easing', get_template_directory_uri() . '/assets/js/jquery.easing.min.js', array( 'jquery' ), filemtime( $easing_js ), true );
        }
        if ( file_exists( $all_min_js ) ) {
            wp_register_script( 'bina-all', get_template_directory_uri() . '/assets/js/all.min.js', array(), filemtime( $all_min_js ), true );
        }

        // Enqueue main script file (depends on jQuery)
        if ( file_exists( $script_js ) ) {
            wp_enqueue_script( 'bina-script', get_template_directory_uri() . '/assets/js/script.js', array( 'jquery', 'bina-swiper' ), filemtime( $script_js ), true );
        }

        // Localize the script with new data
        if ( wp_script_is( 'bina-script', 'enqueued' ) ) {
            wp_localize_script('bina-script', 'bina', array(
                'home_url' => esc_url(home_url('/')),
                'theme_url' => esc_url(THEME_URL),
                'ajaxurl' => admin_url('admin-ajax.php'),
            ));
        }

    }
}

// add action to enqueue scripts and styles in the last position
add_action( 'wp_enqueue_scripts', 'theme_scripts', 9999999 );

/**
 * Remove jQuery Migrate on frontend to silence console message:
 * "JQMIGRATE: Migrate is installed..."
 * Keep it enabled in wp-admin for compatibility.
 */
function bina_disable_jquery_migrate_frontend( $scripts ) {
    if ( is_admin() ) return;
    if ( empty( $scripts->registered['jquery'] ) ) return;

    $deps = $scripts->registered['jquery']->deps;
    $scripts->registered['jquery']->deps = array_diff( $deps, array( 'jquery-migrate' ) );
}
add_action( 'wp_default_scripts', 'bina_disable_jquery_migrate_frontend' );

