<?php
/**
 * Enqueue scripts and styles
 */
 
 if ( ! function_exists( 'theme_scripts' ) ) {
    function theme_scripts() {

        // Enqueue Google Fonts or Custom Font CDN links
        echo '<link rel="preload" href="https://fonts.cdnfonts.com/css/avenir-lt-pro?styles=60926,60921,60923,60919,60915" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        echo '<link rel="preload" href="https://fonts.cdnfonts.com/css/somar-sans?styles=143705,143693,143669" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';

        // Enqueue jQuery
        wp_enqueue_script( 'jquery' );

        // Enqueue Bootstrap CSS file
        wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' );

        // Enqueue Font Awesome CSS file
        wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css' );

        // Enqueue main stylesheet
        wp_enqueue_style( 'bina-style', get_template_directory_uri() . '/assets/css/style.css', array(), mt_rand() );
        wp_enqueue_style( 'bina-responsive-style', get_template_directory_uri() . '/assets/css/responsive.css', array(), mt_rand() );

        // Enqueue main script file
        wp_enqueue_script( 'bina-script', get_template_directory_uri() . '/assets/js/script.js', array( 'jquery' ), mt_rand(), true );

        // Localize the script with new data
        wp_localize_script('bina-script', 'bina', array(
            'home_url' => esc_url(home_url('/')),
            'theme_url' => esc_url(THEME_URL),
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));

    }
}

// add action to enqueue scripts and styles in the last position
add_action( 'wp_enqueue_scripts', 'theme_scripts', 9999999 );

