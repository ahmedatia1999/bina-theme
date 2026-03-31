<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Customer_Disputes_Shell_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_disputes_shell';
	}

	public function get_title() {
		return __( 'Customer — النزاعات', 'bina' );
	}

	public function get_icon() {
		return 'eicon-warning';
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
		$this->add_control( 'url_dashboard', array( 'label' => __( 'لوحة التحكم', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/customer-dashboard' ) ) );
		$this->add_control( 'url_profile', array( 'label' => __( 'الملف الشخصي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/customer-profile' ) ) );
		$this->add_control( 'url_my_projects', array( 'label' => __( 'مشاريعي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/customer-my-projects' ) ) );
		$this->add_control( 'url_chat', array( 'label' => __( 'المحادثات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/customer-chat' ) ) );
		$this->add_control( 'url_notifications', array( 'label' => __( 'الإشعارات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/customer-notifications' ) ) );
		$this->add_control( 'url_disputes', array( 'label' => __( 'النزاعات (هذه الصفحة)', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/customer-disputes' ) ) );
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
		$urls  = bina_get_customer_portal_urls( $s );
		$stats = bina_get_customer_dashboard_stats( $user->ID );

		$js = get_template_directory() . '/assets/js/bina-disputes.js';
		if ( file_exists( $js ) ) {
			wp_enqueue_script(
				'bina-disputes',
				get_template_directory_uri() . '/assets/js/bina-disputes.js',
				array(),
				filemtime( $js ),
				true
			);
		}

		$projects = ( new WP_Query(
			array(
				'post_type'              => 'bina_project',
				'post_status'            => array( 'publish', 'pending', 'draft' ),
				'author'                 => (int) $user->ID,
				'posts_per_page'         => 200,
				'orderby'                => 'modified',
				'order'                  => 'DESC',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
			)
		) )->posts;

		bina_render_customer_portal_shell_start(
			array(
				'user'       => $user,
				'urls'       => $urls,
				'logo_url'   => '',
				'help_url'   => bina_dashboard_resolve_url( 'https://wa.me/966590000474' ),
				'stats'      => $stats,
				'active_nav' => 'disputes',
			)
		);

		include get_template_directory() . '/inc/partials/customer-disputes-app.php';

		bina_render_customer_portal_shell_end();
	}
}

