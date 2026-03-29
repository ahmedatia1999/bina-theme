<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Customer_Conflicts_Shell_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_customer_conflicts_shell';
	}

	public function get_title() {
		return __( 'Customer — النزاعات (قريباً)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-warning';
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
				'default'     => __( 'سيتم ربط هذه الصفحة بنموذج النزاعات والإجراءات لاحقاً.', 'bina' ),
			)
		);
		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();
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
				'active_nav' => 'conflicts',
			)
		);
		?>
		<div class="w-full max-w-2xl mx-auto rounded-2xl border border-border/80 bg-card p-8 sm:p-10 text-center space-y-4 shadow-sm ring-1 ring-border/20">
			<h1 class="text-xl font-semibold"><?php esc_html_e( 'النزاعات', 'bina' ); ?></h1>
			<p class="text-muted-foreground text-sm leading-relaxed"><?php echo esc_html( $note ); ?></p>
		</div>
		<?php
		bina_render_customer_portal_shell_end();
	}
}
