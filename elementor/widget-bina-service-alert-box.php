<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Bina_Service_Alert_Box_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_service_alert_box';
	}

	public function get_title() {
		return __( 'Bina Service — تنبيه (Box)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-alert';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'المحتوى', 'bina' ),
			)
		);

		$this->add_control(
			'section_css',
			array(
				'label'   => __( 'كلاسات السكشن', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'py-8',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تنبيه مهم', 'bina' ),
			)
		);

		$this->add_control(
			'message',
			array(
				'label'   => __( 'الرسالة', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => __( 'نحن لا نبيع وحدات عقارية. بناء سنتر هي منصة وسيطة تساعدك على استكشاف المشاريع المتاحة في السوق والتواصل مباشرة مع المطورين العقاريين.', 'bina' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$pad   = isset( $s['section_css'] ) ? trim( (string) $s['section_css'] ) : 'py-8';
		$title = isset( $s['title'] ) ? (string) $s['title'] : '';
		$msg   = isset( $s['message'] ) ? (string) $s['message'] : '';
		if ( '' === $pad ) {
			$pad = 'py-8';
		}
		?>
		<section class="<?php echo esc_attr( $pad ); ?>">
			<div class="container-custom">
				<div>
					<div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-6 flex items-start gap-4">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert w-6 h-6 text-amber-600 shrink-0 mt-1"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg>
						<div>
							<h3 class="font-bold text-amber-800 dark:text-amber-400 mb-2"><?php echo esc_html( $title ); ?></h3>
							<p class="text-amber-700 dark:text-amber-300"><?php echo esc_html( $msg ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}

