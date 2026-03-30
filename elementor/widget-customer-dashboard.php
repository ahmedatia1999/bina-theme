<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Customer_Dashboard_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_dashboard';
	}

	public function get_title() {
		return __( 'Customer Dashboard (App)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-dashboard';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'sec_urls',
			array(
				'label' => __( 'روابط الصفحات', 'bina' ),
			)
		);
		$this->add_control(
			'url_dashboard',
			array(
				'label'       => __( 'لوحة التحكم', 'bina' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => '/customer-dashboard',
				'default'     => array( 'url' => '/customer-dashboard' ),
			)
		);
		$this->add_control(
			'url_my_projects',
			array(
				'label'       => __( 'مشاريعي', 'bina' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '/customer-my-projects' ),
			)
		);
		$this->add_control(
			'url_my_projects_create',
			array(
				'label'       => __( 'إنشاء مشروع', 'bina' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '/customer-create-project' ),
			)
		);
		$this->add_control(
			'url_chat',
			array(
				'label'       => __( 'المحادثات', 'bina' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '/customer-chat' ),
			)
		);
		$this->add_control(
			'url_notifications',
			array(
				'label'       => __( 'الإشعارات', 'bina' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '/customer-notifications' ),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'sec_misc',
			array(
				'label' => __( 'أخرى', 'bina' ),
			)
		);
		$this->add_control(
			'logo',
			array(
				'label' => __( 'شعار الشريط الجانبي', 'bina' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);
		$this->add_control(
			'help_url',
			array(
				'label'       => __( 'رابط المساعدة (واتساب أو صفحة)', 'bina' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => 'https://wa.me/966590000474' ),
			)
		);
		$this->add_control(
			'login_url',
			array(
				'label'       => __( 'رابط تسجيل الدخول (للزوار)', 'bina' ),
				'type'        => Controls_Manager::URL,
				'default'     => array( 'url' => '/login' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		bina_customer_portal_enqueue_shell_assets();

		if ( ! is_user_logged_in() ) {
			$login = isset( $s['login_url']['url'] ) ? bina_dashboard_resolve_url( $s['login_url']['url'] ) : wp_login_url();
			echo '<p class="p-6 text-center text-muted-foreground">';
			echo esc_html__( 'يجب تسجيل الدخول لعرض لوحة التحكم.', 'bina' );
			echo ' <a class="underline text-primary" href="' . esc_url( $login ) . '">' . esc_html__( 'تسجيل الدخول', 'bina' ) . '</a>';
			echo '</p>';
			return;
		}

		$user = wp_get_current_user();
		if ( ! bina_user_is_customer( $user ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه اللوحة مخصصة لحساب العملاء.', 'bina' ) . '</p>';
			return;
		}

		$urls = bina_get_customer_portal_urls( $s );

		$logo_url = '';
		if ( ! empty( $s['logo']['url'] ) ) {
			$logo_url = $s['logo']['url'];
		}

		$help_url = bina_dashboard_resolve_url( $s['help_url']['url'] ?? 'https://wa.me/966590000474' );

		$stats = bina_get_customer_dashboard_stats( $user->ID );

		$status_counts   = bina_get_customer_project_status_counts( $user->ID );
		$recent_projects = bina_get_customer_recent_projects( $user->ID, 5 );

		include get_template_directory() . '/inc/partials/dashboard-customer-app.php';
	}
}
