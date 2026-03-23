<?php
/**
 * Customizer additions
 */

if ( ! function_exists( 'theme_customize_register' ) ) {
    function theme_customize_register( $wp_customize ) {
        // Add sections, settings, and controls here
    }
}
add_action( 'customize_register', 'theme_customize_register' );
?>
