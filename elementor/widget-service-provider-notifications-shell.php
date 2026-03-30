<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Service_Provider_Notifications_Shell_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_service_provider_notifications_shell';
	}

	public function get_title() {
		return __( 'Service Provider — الإشعارات', 'bina' );
	}

	public function get_icon() {
		return 'eicon-bell';
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
		$this->add_control( 'url_browse_projects', array( 'label' => __( 'تصفح المشاريع', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-browse-projects' ) ) );
		$this->add_control( 'url_my_projects', array( 'label' => __( 'مشاريعي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-my-projects' ) ) );
		$this->add_control( 'url_profile', array( 'label' => __( 'الملف الشخصي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-profile' ) ) );
		$this->add_control( 'url_subscription', array( 'label' => __( 'الاشتراك', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-subscription' ) ) );
		$this->add_control( 'url_offers', array( 'label' => __( 'عروضي', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-offers' ) ) );
		$this->add_control( 'url_chat', array( 'label' => __( 'المحادثات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-chat' ) ) );
		$this->add_control( 'url_conflicts', array( 'label' => __( 'النزاعات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-conflicts' ) ) );
		$this->add_control( 'url_payments', array( 'label' => __( 'المدفوعات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-payments' ) ) );
		$this->add_control( 'url_notifications', array( 'label' => __( 'الإشعارات', 'bina' ), 'type' => Controls_Manager::URL, 'default' => $u( '/service-provider-notifications' ) ) );

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

		if ( function_exists( 'bina_customer_portal_enqueue_shell_assets' ) ) {
			bina_customer_portal_enqueue_shell_assets();
		}

		$urls = bina_get_service_provider_portal_urls( $s );
		$help_url = bina_dashboard_resolve_url( $s['help_url']['url'] ?? 'https://wa.me/966590000474' );
		$stats = bina_get_service_provider_dashboard_stats( $user->ID );

		// Reuse the same layout used for provider pages.
		include get_template_directory() . '/inc/partials/service-provider-chat-layout.php';

		bina_render_service_provider_chat_layout_start(
			array(
				'user'       => $user,
				'urls'       => $urls,
				'stats'      => $stats,
				'logo_url'   => '',
				'help_url'   => $help_url,
				'active_nav' => 'notifications',
			)
		);

		?>
		<div class="w-full max-w-3xl mx-auto space-y-4">
			<div class="rounded-2xl border border-border/80 bg-card p-6 shadow-sm ring-1 ring-border/20">
				<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
					<div>
						<h1 class="text-xl font-semibold"><?php esc_html_e( 'الإشعارات', 'bina' ); ?></h1>
						<p class="text-muted-foreground text-sm leading-relaxed mt-1"><?php esc_html_e( 'عرض إشعاراتك', 'bina' ); ?></p>
					</div>
					<div class="rounded-xl border border-border/80 bg-muted/20 px-4 py-2 text-sm">
						<span class="text-muted-foreground"><?php esc_html_e( 'غير المقروء:', 'bina' ); ?></span>
						<span class="ms-2 font-semibold tabular-nums" data-bina-unread-count>0</span>
					</div>
				</div>
			</div>

			<div class="rounded-2xl border border-border/80 bg-card shadow-sm overflow-hidden">
				<div class="px-4 py-3 border-b border-border/70 bg-muted/20">
					<div class="flex items-center justify-between gap-3">
						<div class="text-sm font-medium"><?php esc_html_e( 'قائمة الإشعارات', 'bina' ); ?></div>
						<button type="button" class="text-sm px-3 py-1 rounded-md border border-border/80 hover:bg-accent" data-bina-mark-all>
							<?php esc_html_e( 'تمييز الكل كمقروء', 'bina' ); ?>
						</button>
					</div>
				</div>

				<div class="p-4" data-bina-notifications-app
					data-ajaxurl="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'bina_notifications' ) ); ?>"
					data-chat-base-url="<?php echo esc_url( bina_get_service_provider_chat_url() ); ?>"
					data-poll-ms="8000"
				>
					<div data-bina-notifications-list class="space-y-3">
						<div class="rounded-xl border bg-muted/30 p-8 text-center text-muted-foreground text-sm">
							<?php esc_html_e( 'جارٍ التحميل...', 'bina' ); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php

		$script_path2 = get_template_directory() . '/assets/js/bina-notifications.js';
		wp_enqueue_script(
			'bina-notifications',
			get_template_directory_uri() . '/assets/js/bina-notifications.js',
			array(),
			file_exists( $script_path2 ) ? filemtime( $script_path2 ) : null,
			true
		);

		bina_render_service_provider_chat_layout_end();
	}
}

