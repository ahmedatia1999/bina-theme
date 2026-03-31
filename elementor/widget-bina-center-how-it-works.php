<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Service_How_It_Works_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_service_how_it_works';
	}

	public function get_title() {
		return __( 'Bina Service — كيف تعمل', 'bina' );
	}

	public function get_icon() {
		return 'eicon-number-field';
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
			'heading',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'كيف تعمل المنصة؟', 'bina' ),
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'number',
			array(
				'label'   => __( 'الرقم', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '1',
			)
		);
		$rep->add_control(
			'title',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'أضف مشروعك', 'bina' ),
			)
		);
		$rep->add_control(
			'text',
			array(
				'label'   => __( 'النص', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'أضف تفاصيل مشروعك ومتطلباتك عبر نموذج بسيط', 'bina' ),
			)
		);

		$this->add_control(
			'steps',
			array(
				'label'       => __( 'الخطوات', 'bina' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ number }}} — {{{ title }}}',
				'default'     => array(
					array(
						'number' => '1',
						'title'  => __( 'أضف مشروعك', 'bina' ),
						'text'   => __( 'أضف تفاصيل مشروعك ومتطلباتك عبر نموذج بسيط', 'bina' ),
					),
					array(
						'number' => '2',
						'title'  => __( 'استلم العروض', 'bina' ),
						'text'   => __( 'نجمع لك عروض من مقاولين متخصصين في مدينتك', 'bina' ),
					),
					array(
						'number' => '3',
						'title'  => __( 'قارن واختر', 'bina' ),
						'text'   => __( 'قارن العروض والتقييمات واختر الأنسب لك', 'bina' ),
					),
					array(
						'number' => '4',
						'title'  => __( 'ابدأ البناء', 'bina' ),
						'text'   => __( 'وقّع العقد وابدأ مشروعك مع ضماناتنا', 'bina' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$title = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$steps = isset( $s['steps'] ) && is_array( $s['steps'] ) ? $s['steps'] : array();
		?>
		<section class="py-20 bg-background">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html( $title ); ?></h2>
					</div>
				</div>

				<div class="grid md:grid-cols-4 gap-8">
					<?php
					$total = count( $steps );
					foreach ( $steps as $i => $st ) :
						$num = isset( $st['number'] ) ? (string) $st['number'] : '';
						$h   = isset( $st['title'] ) ? (string) $st['title'] : '';
						$tx  = isset( $st['text'] ) ? (string) $st['text'] : '';
						?>
						<div>
							<div class="text-center relative">
								<div class="w-16 h-16 rounded-full bg-primary text-primary-foreground text-2xl font-bold flex items-center justify-center mx-auto mb-4"><?php echo esc_html( $num ); ?></div>
								<h3 class="text-lg font-bold text-foreground mb-2"><?php echo esc_html( $h ); ?></h3>
								<p class="text-muted-foreground text-sm"><?php echo esc_html( $tx ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

