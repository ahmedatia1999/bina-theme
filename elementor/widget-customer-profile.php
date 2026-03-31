<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;

class bina_Customer_Profile_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_profile';
	}

	public function get_title() {
		return __( 'Customer — الملف الشخصي', 'bina' );
	}

	public function get_icon() {
		return 'eicon-user-circle-o';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function render() {
		bina_customer_portal_enqueue_shell_assets();
		$profile_js = get_template_directory() . '/assets/js/bina-customer-profile.js';
		$pay_js     = get_template_directory() . '/assets/js/bina-customer-payments.js';

		wp_enqueue_script(
			'bina-customer-profile',
			get_template_directory_uri() . '/assets/js/bina-customer-profile.js',
			array(),
			file_exists( $profile_js ) ? filemtime( $profile_js ) : null,
			true
		);
		wp_enqueue_script(
			'bina-customer-payments',
			get_template_directory_uri() . '/assets/js/bina-customer-payments.js',
			array(),
			file_exists( $pay_js ) ? filemtime( $pay_js ) : null,
			true
		);

		if ( ! is_user_logged_in() ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'يجب تسجيل الدخول.', 'bina' ) . '</p>';
			return;
		}

		$user = wp_get_current_user();
		if ( ! bina_user_is_customer( $user ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه الصفحة للعملاء فقط.', 'bina' ) . '</p>';
			return;
		}

		$urls     = bina_get_customer_portal_urls( null );
		$stats    = bina_get_customer_dashboard_stats( $user->ID );
		$help_url = bina_dashboard_resolve_url( 'https://wa.me/966590000474' );

		bina_render_customer_portal_shell_start(
			array(
				'user'       => $user,
				'urls'       => $urls,
				'logo_url'   => '',
				'help_url'   => $help_url,
				'stats'      => $stats,
				'active_nav' => 'profile',
			)
		);

		include get_template_directory() . '/inc/partials/customer-profile-app.php';

		bina_render_customer_portal_shell_end();
	}
}

