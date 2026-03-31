<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Materials_Steps_Cards_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_materials_steps_cards';
	}

	public function get_title() {
		return __( 'Bina Service — خطوات (كروت)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-post-list';
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
				'default' => __( 'كيف تحصل على الخدمة؟', 'bina' ),
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
				'default' => __( 'أرسل قائمة المواد', 'bina' ),
			)
		);
		$rep->add_control(
			'text',
			array(
				'label'   => __( 'النص', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'أرسل لنا قائمة المواد المطلوبة مع الكميات والمواصفات', 'bina' ),
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
						'title'  => __( 'أرسل قائمة المواد', 'bina' ),
						'text'   => __( 'أرسل لنا قائمة المواد المطلوبة مع الكميات والمواصفات', 'bina' ),
					),
					array(
						'number' => '2',
						'title'  => __( 'استلم العروض', 'bina' ),
						'text'   => __( 'نجمع لك أفضل العروض من الموردين والمصانع المعتمدين', 'bina' ),
					),
					array(
						'number' => '3',
						'title'  => __( 'قارن واختر', 'bina' ),
						'text'   => __( 'قارن بين العروض واختر الأنسب لميزانيتك ومتطلباتك', 'bina' ),
					),
					array(
						'number' => '4',
						'title'  => __( 'استلم في موقعك', 'bina' ),
						'text'   => __( 'يتم توصيل المواد مباشرة إلى موقع مشروعك', 'bina' ),
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
		<section class="py-20 bg-muted/30">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html( $title ); ?></h2>
					</div>
				</div>

				<div class="grid md:grid-cols-4 gap-6">
					<?php foreach ( $steps as $st ) : ?>
						<?php
						$num = isset( $st['number'] ) ? (string) $st['number'] : '';
						$h   = isset( $st['title'] ) ? (string) $st['title'] : '';
						$tx  = isset( $st['text'] ) ? (string) $st['text'] : '';
						?>
						<div>
							<div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 relative">
								<div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg"><?php echo esc_html( $num ); ?></div>
								<div class="pt-8">
									<h3 class="text-lg font-bold text-secondary mb-2"><?php echo esc_html( $h ); ?></h3>
									<p class="text-muted-foreground text-sm"><?php echo esc_html( $tx ); ?></p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

