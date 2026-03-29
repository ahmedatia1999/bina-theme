<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Customer_Create_Project_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_create_project';
	}

	public function get_title() {
		return __( 'Customer — إنشاء مشروع', 'bina' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'sec_nav',
			array(
				'label' => __( 'روابط', 'bina' ),
			)
		);
		$this->add_control(
			'back_url',
			array(
				'label'   => __( 'رابط العودة (مشاريعي)', 'bina' ),
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '/customer-my-projects' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$script_path = get_template_directory() . '/assets/js/bina-create-project.js';
		wp_enqueue_script(
			'bina-create-project',
			get_template_directory_uri() . '/assets/js/bina-create-project.js',
			array(),
			file_exists( $script_path ) ? filemtime( $script_path ) : null,
			true
		);

		$back = isset( $s['back_url']['url'] ) ? trim( (string) $s['back_url']['url'] ) : '';
		$back_url = $back !== '' ? bina_dashboard_resolve_url( $back ) : bina_get_customer_my_projects_url();

		if ( ! is_user_logged_in() ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'يجب تسجيل الدخول.', 'bina' ) . '</p>';
			return;
		}

		$user = wp_get_current_user();
		if ( ! bina_user_is_customer( $user ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه الصفحة للعملاء فقط.', 'bina' ) . '</p>';
			return;
		}

		$nonce       = wp_create_nonce( 'bina_project' );
		$categories  = bina_get_project_categories();
		$reminders   = bina_get_project_reminder_options();
		$cities      = bina_get_cities_for_select();
		$is_edit     = false;
		$edit_post_id = 0;
		$prefill     = array();

		$project_id = isset( $_GET['project_id'] ) ? absint( $_GET['project_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( $project_id > 0 ) {
			$proj = get_post( $project_id );
			if ( ! $proj || $proj->post_type !== 'bina_project' ) {
				echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'المشروع غير موجود.', 'bina' ) . '</p>';
				return;
			}
			if ( ! current_user_can( 'edit_post', $project_id ) ) {
				echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'لا يمكنك تعديل هذا المشروع.', 'bina' ) . '</p>';
				return;
			}
			$is_edit      = true;
			$edit_post_id = $project_id;
			$extra_raw    = get_post_meta( $project_id, '_bina_extra', true );
			$extra        = array();
			if ( is_string( $extra_raw ) && $extra_raw !== '' ) {
				$decoded = json_decode( $extra_raw, true );
				if ( is_array( $decoded ) ) {
					$extra = $decoded;
				}
			}
			$prefill = array(
				'title'        => $proj->post_title,
				'description'  => $proj->post_content,
				'category'     => (string) get_post_meta( $project_id, '_bina_category', true ),
				'reminder'     => (string) get_post_meta( $project_id, '_bina_reminder', true ),
				'city'         => (string) get_post_meta( $project_id, '_bina_city', true ),
				'neighborhood' => isset( $extra['neighborhood'] ) ? (string) $extra['neighborhood'] : '',
				'street'       => isset( $extra['street'] ) ? (string) $extra['street'] : '',
				'start_timing' => isset( $extra['start_timing'] ) ? (string) $extra['start_timing'] : '',
				'has_plans'    => isset( $extra['has_plans'] ) ? (string) $extra['has_plans'] : '',
				'has_photos'   => isset( $extra['has_photos'] ) ? (string) $extra['has_photos'] : '',
			);
		}

		bina_customer_portal_enqueue_shell_assets();
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
				'active_nav' => 'my_projects',
			)
		);

		include get_template_directory() . '/inc/partials/customer-create-project-app.php';

		bina_render_customer_portal_shell_end();
	}
}
