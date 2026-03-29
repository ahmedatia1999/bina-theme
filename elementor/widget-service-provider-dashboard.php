<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Service_Provider_Dashboard_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_service_provider_dashboard';
	}

	public function get_title() {
		return __( 'Service Provider Dashboard (App)', 'bina' );
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

		$u = function ( $path ) {
			return array( 'url' => $path );
		};

		$this->add_control( 'url_dashboard', array( 'label' => __( 'لوحة التحكم', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-dashboard' ) ) );
		$this->add_control( 'url_browse_projects', array( 'label' => __( 'تصفح المشاريع', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/browse-projects' ) ) );
		$this->add_control( 'url_my_projects', array( 'label' => __( 'مشاريعي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/my-projects' ) ) );
		$this->add_control( 'url_profile', array( 'label' => __( 'الملف الشخصي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/profile' ) ) );
		$this->add_control( 'url_subscription', array( 'label' => __( 'الاشتراك', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/subscription' ) ) );
		$this->add_control( 'url_offers', array( 'label' => __( 'عروضي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/offers' ) ) );
		$this->add_control( 'url_chat', array( 'label' => __( 'المحادثات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/chat' ) ) );
		$this->add_control( 'url_conflicts', array( 'label' => __( 'النزاعات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/conflicts' ) ) );
		$this->add_control( 'url_payments', array( 'label' => __( 'المدفوعات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/payments' ) ) );
		$this->add_control( 'url_notifications', array( 'label' => __( 'الإشعارات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/notifications' ) ) );
		$this->add_control( 'url_verification', array( 'label' => __( 'التوثيق (بطاقة سريعة)', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/profile#verification' ) ) );

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
				'label'   => __( 'رابط المساعدة', 'bina' ),
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => 'https://wa.me/966590000474' ),
			)
		);
		$this->add_control(
			'login_url',
			array(
				'label'   => __( 'رابط تسجيل الدخول (للزوار)', 'bina' ),
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '/login' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$script_path = get_template_directory() . '/assets/js/dashboard-shell.js';
		if ( file_exists( $script_path ) ) {
			wp_enqueue_script(
				'bina-dashboard-shell',
				get_template_directory_uri() . '/assets/js/dashboard-shell.js',
				array(),
				filemtime( $script_path ),
				true
			);
		}

		if ( ! is_user_logged_in() ) {
			$login = isset( $s['login_url']['url'] ) ? bina_dashboard_resolve_url( $s['login_url']['url'] ) : wp_login_url();
			echo '<p class="p-6 text-center text-muted-foreground">';
			echo esc_html__( 'يجب تسجيل الدخول لعرض لوحة التحكم.', 'bina' );
			echo ' <a class="underline text-primary" href="' . esc_url( $login ) . '">' . esc_html__( 'تسجيل الدخول', 'bina' ) . '</a>';
			echo '</p>';
			return;
		}

		$user = wp_get_current_user();
		if ( ! bina_user_is_service_provider( $user ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه اللوحة مخصصة لمقدمي الخدمة.', 'bina' ) . '</p>';
			return;
		}

		$dash = isset( $s['url_dashboard']['url'] ) ? trim( (string) $s['url_dashboard']['url'] ) : '';
		$urls = array(
			'dashboard'        => $dash !== '' ? bina_dashboard_resolve_url( $dash ) : bina_get_service_provider_dashboard_url(),
			'browse_projects'  => bina_dashboard_resolve_url( $s['url_browse_projects']['url'] ?? '/service-provider/browse-projects' ),
			'my_projects'      => bina_dashboard_resolve_url( $s['url_my_projects']['url'] ?? '/service-provider/my-projects' ),
			'profile'          => bina_dashboard_resolve_url( $s['url_profile']['url'] ?? '/service-provider/profile' ),
			'subscription'     => bina_dashboard_resolve_url( $s['url_subscription']['url'] ?? '/service-provider/subscription' ),
			'offers'           => bina_dashboard_resolve_url( $s['url_offers']['url'] ?? '/service-provider/offers' ),
			'chat'             => bina_dashboard_resolve_url( $s['url_chat']['url'] ?? '/service-provider/chat' ),
			'conflicts'        => bina_dashboard_resolve_url( $s['url_conflicts']['url'] ?? '/service-provider/conflicts' ),
			'payments'         => bina_dashboard_resolve_url( $s['url_payments']['url'] ?? '/service-provider/payments' ),
			'notifications'    => bina_dashboard_resolve_url( $s['url_notifications']['url'] ?? '/service-provider/notifications' ),
			'verification'     => bina_dashboard_resolve_url( $s['url_verification']['url'] ?? '/service-provider/profile' ),
		);

		$logo_url = ! empty( $s['logo']['url'] ) ? $s['logo']['url'] : '';
		$help_url = bina_dashboard_resolve_url( $s['help_url']['url'] ?? 'https://wa.me/966590000474' );

		$stats = bina_get_service_provider_dashboard_stats( $user->ID );

		$browse_status_counts   = bina_get_browseable_project_status_counts_for_provider( $user->ID );
		$recent_market_projects = bina_get_recent_browseable_projects_for_provider( $user->ID, 5 );

		include get_template_directory() . '/inc/partials/dashboard-service-provider-app.php';
	}
}
