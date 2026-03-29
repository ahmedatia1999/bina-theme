<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Service_Provider_Stub_Shell_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_service_provider_stub_shell';
	}

	public function get_title() {
		return __( 'Service Provider — صفحة فرعية (نص)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-text';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'sec_content',
			array(
				'label' => __( 'المحتوى', 'bina' ),
			)
		);
		$this->add_control(
			'active_nav',
			array(
				'label'   => __( 'تفعيل عنصر القائمة', 'bina' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'my_projects'    => __( 'مشاريعي', 'bina' ),
					'profile'        => __( 'الملف الشخصي', 'bina' ),
					'subscription'   => __( 'الاشتراك', 'bina' ),
					'offers'         => __( 'عروضي', 'bina' ),
					'conflicts'      => __( 'النزاعات', 'bina' ),
					'payments'       => __( 'المدفوعات', 'bina' ),
					'notifications'  => __( 'الإشعارات', 'bina' ),
				),
				'default' => 'my_projects',
			)
		);
		$this->add_control(
			'heading',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'قريباً', 'bina' ),
			)
		);
		$this->add_control(
			'description',
			array(
				'label'   => __( 'الوصف', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'هذه الصفحة قيد الإعداد.', 'bina' ),
			)
		);
		$this->end_controls_section();

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
		$this->add_control( 'url_browse_projects', array( 'label' => __( 'تصفح المشاريع', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-browse-projects' ) ) );
		$this->add_control( 'url_my_projects', array( 'label' => __( 'مشاريعي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-my-projects' ) ) );
		$this->add_control( 'url_profile', array( 'label' => __( 'الملف الشخصي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-profile' ) ) );
		$this->add_control( 'url_subscription', array( 'label' => __( 'الاشتراك', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-subscription' ) ) );
		$this->add_control( 'url_offers', array( 'label' => __( 'عروضي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-offers' ) ) );
		$this->add_control( 'url_chat', array( 'label' => __( 'المحادثات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-chat' ) ) );
		$this->add_control( 'url_conflicts', array( 'label' => __( 'النزاعات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-conflicts' ) ) );
		$this->add_control( 'url_payments', array( 'label' => __( 'المدفوعات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-payments' ) ) );
		$this->add_control( 'url_notifications', array( 'label' => __( 'الإشعارات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-notifications' ) ) );
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

		$urls     = bina_get_service_provider_portal_urls( $s );
		$logo_url = ! empty( $s['logo']['url'] ) ? $s['logo']['url'] : '';
		$help_url = bina_dashboard_resolve_url( $s['help_url']['url'] ?? 'https://wa.me/966590000474' );
		$stats    = bina_get_service_provider_dashboard_stats( $user->ID );

		$active = isset( $s['active_nav'] ) ? (string) $s['active_nav'] : 'my_projects';

		require_once get_template_directory() . '/inc/partials/service-provider-chat-layout.php';

		bina_render_service_provider_chat_layout_start(
			array(
				'user'       => $user,
				'urls'       => $urls,
				'stats'      => $stats,
				'logo_url'   => $logo_url,
				'help_url'   => $help_url,
				'active_nav' => $active,
			)
		);

		$heading = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$desc    = isset( $s['description'] ) ? (string) $s['description'] : '';
		?>
		<div class="rounded-2xl border border-border/80 bg-card p-8 text-center space-y-3 shadow-sm max-w-xl mx-auto">
			<h1 class="text-xl font-semibold"><?php echo esc_html( $heading ); ?></h1>
			<p class="text-muted-foreground text-sm leading-relaxed"><?php echo esc_html( $desc ); ?></p>
		</div>
		<?php

		bina_render_service_provider_chat_layout_end();
	}
}
