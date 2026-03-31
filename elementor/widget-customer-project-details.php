<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Customer_Project_Details_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_project_details';
	}

	public function get_title() {
		return __( 'Customer — تفاصيل المشروع', 'bina' );
	}

	public function get_icon() {
		return 'eicon-single-post';
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
			'list_url',
			array(
				'label'   => __( 'رجوع لمشاريعي', 'bina' ),
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '/customer-my-projects' ),
			)
		);
		$this->add_control(
			'preview_post_id',
			array(
				'label'       => __( 'معاينة (محرر فقط)', 'bina' ),
				'description' => __( 'للمعاينة في Elementor فقط — على الموقع يُستخدم ?project_id=', 'bina' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$l = isset( $s['list_url']['url'] ) ? trim( (string) $s['list_url']['url'] ) : '';
		$list_url = $l !== '' ? bina_dashboard_resolve_url( $l ) : bina_get_customer_my_projects_url();

		$project_id = isset( $_GET['project_id'] ) ? absint( $_GET['project_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$is_editor = class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode();
		if ( $is_editor && ! empty( $s['preview_post_id'] ) ) {
			$project_id = absint( $s['preview_post_id'] );
		}

		if ( ! is_user_logged_in() ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'يجب تسجيل الدخول.', 'bina' ) . '</p>';
			return;
		}

		$user = wp_get_current_user();
		if ( ! bina_user_is_customer( $user ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه الصفحة للعملاء فقط.', 'bina' ) . '</p>';
			return;
		}

		if ( $project_id < 1 ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'لم يُحدد مشروع.', 'bina' ) . '</p>';
			return;
		}

		$post = get_post( $project_id );
		if ( ! $post || $post->post_type !== 'bina_project' ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'المشروع غير موجود.', 'bina' ) . '</p>';
			return;
		}

		if ( ! current_user_can( 'read_post', $project_id ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'لا يمكنك عرض هذا المشروع.', 'bina' ) . '</p>';
			return;
		}

		$category = get_post_meta( $project_id, '_bina_category', true );
		$city     = get_post_meta( $project_id, '_bina_city', true );
		$city_disp = $city;
		foreach ( bina_get_cities_for_select() as $c ) {
			if ( isset( $c['value'] ) && $c['value'] === $city ) {
				$city_disp = isset( $c['label'] ) ? $c['label'] : $city;
				break;
			}
		}
		$reminder = get_post_meta( $project_id, '_bina_reminder', true );
		$st_key   = bina_get_project_status_meta( (int) $project_id );
		$statuses = bina_get_project_status_labels();
		$st_label = isset( $statuses[ $st_key ] ) ? $statuses[ $st_key ] : $st_key;

		$extra_raw = get_post_meta( $project_id, '_bina_extra', true );
		$extra     = bina_project_extra_from_meta( is_string( $extra_raw ) ? $extra_raw : '' );

		$plans_attachment_ids       = bina_get_project_attachment_ids( $project_id, 'plans' );
		$site_photos_attachment_ids = bina_get_project_attachment_ids( $project_id, 'site_photos' );
		$proposals                  = function_exists( 'bina_proposals_fetch_for_project' ) ? bina_proposals_fetch_for_project( (int) $project_id ) : array();
		$proposal_nonce             = wp_create_nonce( 'bina_proposals' );
		$ajaxurl                    = admin_url( 'admin-ajax.php' );
		$delete_nonce               = wp_create_nonce( 'bina_project' );
		$my_projects_url            = function_exists( 'bina_get_customer_my_projects_url' ) ? bina_get_customer_my_projects_url() : $list_url;

		$city = $city_disp;

		$edit_url = '';
		if ( current_user_can( 'edit_post', $project_id ) ) {
			$edit_url = bina_get_customer_edit_project_url( $project_id );
		}

		bina_customer_portal_enqueue_shell_assets();
		$customer_props_js = get_template_directory() . '/assets/js/bina-customer-proposals.js';
		if ( file_exists( $customer_props_js ) ) {
			wp_enqueue_script(
				'bina-customer-proposals',
				get_template_directory_uri() . '/assets/js/bina-customer-proposals.js',
				array(),
				filemtime( $customer_props_js ),
				true
			);
		}

		$customer_delete_js = get_template_directory() . '/assets/js/bina-customer-project-delete.js';
		if ( file_exists( $customer_delete_js ) ) {
			wp_enqueue_script(
				'bina-customer-project-delete',
				get_template_directory_uri() . '/assets/js/bina-customer-project-delete.js',
				array(),
				filemtime( $customer_delete_js ),
				true
			);
		}

		$ms_js = get_template_directory() . '/assets/js/bina-milestones.js';
		if ( file_exists( $ms_js ) ) {
			wp_enqueue_script(
				'bina-milestones',
				get_template_directory_uri() . '/assets/js/bina-milestones.js',
				array(),
				filemtime( $ms_js ),
				true
			);
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
				'active_nav' => 'my_projects',
			)
		);

		include get_template_directory() . '/inc/partials/customer-project-details-app.php';

		bina_render_customer_portal_shell_end();
	}
}
