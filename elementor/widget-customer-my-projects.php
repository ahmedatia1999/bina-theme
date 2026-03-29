<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Customer_My_Projects_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_my_projects';
	}

	public function get_title() {
		return __( 'Customer — مشاريعي', 'bina' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'sec_urls',
			array(
				'label' => __( 'روابط', 'bina' ),
			)
		);
		$this->add_control(
			'create_url',
			array(
				'label'   => __( 'إنشاء مشروع', 'bina' ),
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '/customer-create-project' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$c = isset( $s['create_url']['url'] ) ? trim( (string) $s['create_url']['url'] ) : '';
		$create_url = $c !== '' ? bina_dashboard_resolve_url( $c ) : bina_get_customer_create_project_url();

		if ( ! is_user_logged_in() ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'يجب تسجيل الدخول.', 'bina' ) . '</p>';
			return;
		}

		$user = wp_get_current_user();
		if ( ! bina_user_is_customer( $user ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه الصفحة للعملاء فقط.', 'bina' ) . '</p>';
			return;
		}

		$filter_status   = isset( $_GET['project_status'] ) ? sanitize_text_field( wp_unslash( $_GET['project_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter_category = isset( $_GET['project_category'] ) ? sanitize_text_field( wp_unslash( $_GET['project_category'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$filter_city     = isset( $_GET['project_city'] ) ? sanitize_text_field( wp_unslash( $_GET['project_city'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$status_keys = array_keys( bina_get_project_status_labels() );
		$cat_allowed = bina_get_project_categories();
		$city_vals   = wp_list_pluck( bina_get_cities_for_select(), 'value' );

		$meta_query = array();
		if ( $filter_status !== '' && in_array( $filter_status, $status_keys, true ) ) {
			$meta_query[] = array(
				'key'   => '_bina_project_status',
				'value' => $filter_status,
			);
		}
		if ( $filter_category !== '' && in_array( $filter_category, $cat_allowed, true ) ) {
			$meta_query[] = array(
				'key'   => '_bina_category',
				'value' => $filter_category,
			);
		}
		if ( $filter_city !== '' && in_array( $filter_city, $city_vals, true ) ) {
			$meta_query[] = array(
				'key'   => '_bina_city',
				'value' => $filter_city,
			);
		}

		$query_args = array(
			'post_type'      => 'bina_project',
			'author'         => get_current_user_id(),
			'post_status'    => array( 'publish', 'pending', 'draft' ),
			'posts_per_page' => 50,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		if ( count( $meta_query ) > 1 ) {
			$query_args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta_query );
		} elseif ( count( $meta_query ) === 1 ) {
			$query_args['meta_query'] = $meta_query;
		}

		$q = new WP_Query( $query_args );

		$count      = (int) $q->found_posts;
		$statuses   = bina_get_project_status_labels();
		$cities     = bina_get_cities_for_select();
		$categories = bina_get_project_categories();
		$filter_action_url = get_permalink();

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

		include get_template_directory() . '/inc/partials/customer-my-projects-app.php';

		bina_render_customer_portal_shell_end();

		wp_reset_postdata();
	}
}
