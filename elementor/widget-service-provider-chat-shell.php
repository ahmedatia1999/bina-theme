<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Service_Provider_Chat_Shell_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_service_provider_chat_shell';
	}

	public function get_title() {
		return __( 'Service Provider — المحادثات', 'bina' );
	}

	public function get_icon() {
		return 'eicon-comments';
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
		$u = function ( $path ) {
			return array( 'url' => $path );
		};
		$this->add_control( 'url_dashboard', array( 'label' => __( 'لوحة التحكم', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-dashboard' ) ) );
		$this->add_control( 'url_browse_projects', array( 'label' => __( 'تصفح المشاريع', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/browse-projects' ) ) );
		$this->add_control( 'url_my_projects', array( 'label' => __( 'مشاريعي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/my-projects' ) ) );
		$this->add_control( 'url_profile', array( 'label' => __( 'الملف الشخصي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/profile' ) ) );
		$this->add_control( 'url_subscription', array( 'label' => __( 'الاشتراك', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/subscription' ) ) );
		$this->add_control( 'url_offers', array( 'label' => __( 'عروضي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/offers' ) ) );
		$this->add_control( 'url_chat', array( 'label' => __( 'المحادثات (هذه الصفحة)', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-chat' ) ) );
		$this->add_control( 'url_conflicts', array( 'label' => __( 'النزاعات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/conflicts' ) ) );
		$this->add_control( 'url_payments', array( 'label' => __( 'المدفوعات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/payments' ) ) );
		$this->add_control( 'url_notifications', array( 'label' => __( 'الإشعارات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider/notifications' ) ) );
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
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'يجب تسجيل الدخول.', 'bina' ) . '</p>';
			return;
		}

		$user = wp_get_current_user();
		if ( ! bina_user_is_service_provider( $user ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه الصفحة لمقدمي الخدمة فقط.', 'bina' ) . '</p>';
			return;
		}

		$dash = isset( $s['url_dashboard']['url'] ) ? trim( (string) $s['url_dashboard']['url'] ) : '';
		$urls = array(
			'dashboard'       => $dash !== '' ? bina_dashboard_resolve_url( $dash ) : bina_get_service_provider_dashboard_url(),
			'browse_projects' => bina_dashboard_resolve_url( $s['url_browse_projects']['url'] ?? '/service-provider/browse-projects' ),
			'my_projects'     => bina_dashboard_resolve_url( $s['url_my_projects']['url'] ?? '/service-provider/my-projects' ),
			'profile'         => bina_dashboard_resolve_url( $s['url_profile']['url'] ?? '/service-provider/profile' ),
			'subscription'    => bina_dashboard_resolve_url( $s['url_subscription']['url'] ?? '/service-provider/subscription' ),
			'offers'          => bina_dashboard_resolve_url( $s['url_offers']['url'] ?? '/service-provider/offers' ),
			'chat'            => bina_dashboard_resolve_url( $s['url_chat']['url'] ?? '/service-provider-chat' ),
			'conflicts'       => bina_dashboard_resolve_url( $s['url_conflicts']['url'] ?? '/service-provider/conflicts' ),
			'payments'        => bina_dashboard_resolve_url( $s['url_payments']['url'] ?? '/service-provider/payments' ),
			'notifications'   => bina_dashboard_resolve_url( $s['url_notifications']['url'] ?? '/service-provider/notifications' ),
		);

		$logo_url = ! empty( $s['logo']['url'] ) ? $s['logo']['url'] : '';
		$help_url = bina_dashboard_resolve_url( $s['help_url']['url'] ?? 'https://wa.me/966590000474' );
		$stats    = bina_get_service_provider_dashboard_stats( $user->ID );

		$chat_base = $urls['chat'];
		$project_id = isset( $_GET['project_id'] ) ? absint( $_GET['project_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$inbox_ids = bina_get_project_ids_for_messages_inbox( $user->ID );

		$thread_allowed = false;
		if ( $project_id > 0 ) {
			$thread_allowed = bina_user_can_access_project_messages( $user->ID, $project_id );
		}

		if ( $project_id > 0 && $thread_allowed ) {
			bina_enqueue_project_messages_script();
		}

		require_once get_template_directory() . '/inc/partials/service-provider-chat-layout.php';

		bina_render_service_provider_chat_layout_start(
			array(
				'user'       => $user,
				'urls'       => $urls,
				'stats'      => $stats,
				'logo_url'   => $logo_url,
				'help_url'   => $help_url,
				'active_nav' => 'chat',
			)
		);

		$portal_role       = 'provider';
		$chat_base_url     = $chat_base;
		$inbox_project_ids = $inbox_ids;

		include get_template_directory() . '/inc/partials/bina-project-chat-app.php';

		bina_render_service_provider_chat_layout_end();
	}
}
