<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Customer_Chat_Shell_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_chat_shell';
	}

	public function get_title() {
		return __( 'Customer — المحادثات', 'bina' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'sec',
			array(
				'label' => __( 'روابط', 'bina' ),
			)
		);
		$this->add_control(
			'back_url',
			array(
				'label'   => __( 'رابط لوحة التحكم (اختياري)', 'bina' ),
				'type'    => Controls_Manager::URL,
				'default' => array( 'url' => '/customer-dashboard' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		if ( ! is_user_logged_in() ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'يجب تسجيل الدخول.', 'bina' ) . '</p>';
			return;
		}

		$user = wp_get_current_user();
		if ( ! bina_user_is_customer( $user ) ) {
			echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه الصفحة للعملاء فقط.', 'bina' ) . '</p>';
			return;
		}

		bina_customer_portal_enqueue_shell_assets();

		$urls     = bina_get_customer_portal_urls( null );
		$stats    = bina_get_customer_dashboard_stats( $user->ID );
		$help_url = bina_dashboard_resolve_url( 'https://wa.me/966590000474' );

		$chat_base = bina_get_customer_chat_url();
		$back      = isset( $s['back_url']['url'] ) ? trim( (string) $s['back_url']['url'] ) : '';
		if ( $back !== '' ) {
			$urls['dashboard'] = bina_dashboard_resolve_url( $back );
		}

		$project_id = isset( $_GET['project_id'] ) ? absint( $_GET['project_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$inbox_ids = bina_get_project_ids_for_messages_inbox( $user->ID );

		$thread_allowed = false;
		if ( $project_id > 0 ) {
			$thread_allowed = bina_user_can_access_project_messages( $user->ID, $project_id );
		}

		if ( $project_id > 0 && $thread_allowed ) {
			bina_enqueue_project_messages_script();
		}

		bina_render_customer_portal_shell_start(
			array(
				'user'       => $user,
				'urls'       => $urls,
				'logo_url'   => '',
				'help_url'   => $help_url,
				'stats'      => $stats,
				'active_nav' => 'chat',
			)
		);

		$portal_role       = 'customer';
		$chat_base_url     = $chat_base;
		$inbox_project_ids = $inbox_ids;

		include get_template_directory() . '/inc/partials/bina-project-chat-app.php';

		bina_render_customer_portal_shell_end();
	}
}
