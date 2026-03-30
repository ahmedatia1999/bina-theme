<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Customer_Notifications_Shell_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_notifications_shell';
	}

	public function get_title() {
		return __( 'Customer — الإشعارات (قريباً)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-bell';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'sec',
			array(
				'label' => __( 'محتوى', 'bina' ),
			)
		);
		$this->add_control(
			'note',
			array(
				'label'       => __( 'ملاحظة', 'bina' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'الإشعارات', 'bina' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s    = $this->get_settings_for_display();
		$note = isset( $s['note'] ) ? $s['note'] : '';

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

		bina_render_customer_portal_shell_start(
			array(
				'user'       => $user,
				'urls'       => $urls,
				'logo_url'   => '',
				'help_url'   => $help_url,
				'stats'      => $stats,
				'active_nav' => 'notifications',
			)
		);
		?>
		<div class="w-full max-w-3xl mx-auto space-y-4">
			<div class="rounded-2xl border border-border/80 bg-card p-6 sm:p-8 shadow-sm ring-1 ring-border/20">
				<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
					<div>
						<h1 class="text-xl font-semibold"><?php esc_html_e( 'الإشعارات', 'bina' ); ?></h1>
						<p class="text-muted-foreground text-sm leading-relaxed mt-1"><?php echo esc_html( $note ); ?></p>
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
					data-chat-base-url="<?php echo esc_url( bina_get_customer_chat_url() ); ?>"
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

		$script_path = get_template_directory() . '/assets/js/bina-notifications.js';
		wp_enqueue_script(
			'bina-notifications',
			get_template_directory_uri() . '/assets/js/bina-notifications.js',
			array(),
			file_exists( $script_path ) ? filemtime( $script_path ) : null,
			true
		);

		bina_render_customer_portal_shell_end();
	}
}
