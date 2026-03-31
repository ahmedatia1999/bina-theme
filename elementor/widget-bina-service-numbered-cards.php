<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Service_Numbered_Cards_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_service_numbered_cards';
	}

	public function get_title() {
		return __( 'Bina Service — كروت مرقمة', 'bina' );
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
				'default' => __( 'نساعدك على', 'bina' ),
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
				'default' => __( 'استكشاف المشاريع المتاحة', 'bina' ),
			)
		);
		$rep->add_control(
			'text',
			array(
				'label'   => __( 'النص', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'تصفح قائمة محدثة بالمشاريع العقارية على الخارطة', 'bina' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'الكروت', 'bina' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ number }}} — {{{ title }}}',
				'default'     => array(
					array(
						'number' => '1',
						'title'  => __( 'استكشاف المشاريع المتاحة', 'bina' ),
						'text'   => __( 'تصفح قائمة محدثة بالمشاريع العقارية على الخارطة', 'bina' ),
					),
					array(
						'number' => '2',
						'title'  => __( 'فهم الخيارات والأسعار', 'bina' ),
						'text'   => __( 'احصل على معلومات واضحة عن كل مشروع ومميزاته', 'bina' ),
					),
					array(
						'number' => '3',
						'title'  => __( 'التواصل مع المطور المناسب', 'bina' ),
						'text'   => __( 'نسهل لك التواصل المباشر مع الجهة المطوّرة', 'bina' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$title = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$items = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		?>
		<section class="py-20 bg-background">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html( $title ); ?></h2>
					</div>
				</div>

				<div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
					<?php foreach ( $items as $it ) : ?>
						<?php
						$num = isset( $it['number'] ) ? (string) $it['number'] : '';
						$h   = isset( $it['title'] ) ? (string) $it['title'] : '';
						$tx  = isset( $it['text'] ) ? (string) $it['text'] : '';
						?>
						<div>
							<div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 text-center">
								<div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center mb-4 mx-auto font-bold text-xl"><?php echo esc_html( $num ); ?></div>
								<h3 class="text-lg font-bold text-secondary mb-2"><?php echo esc_html( $h ); ?></h3>
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

