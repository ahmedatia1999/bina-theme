<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Service_Renovation_Services_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_service_renovation_services';
	}

	public function get_title() {
		return __( 'Bina Service — خدمات الترميم (قائمة)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-check-circle-o';
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
				'default' => __( 'خدمات الترميم والتجديد', 'bina' ),
			)
		);

		$this->add_control(
			'section_padding',
			array(
				'label'   => __( 'Padding السكشن (كلاسات)', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'py-16',
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'text',
			array(
				'label'   => __( 'النص', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'ترميم المباني القديمة', 'bina' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'العناصر', 'bina' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array( 'text' => __( 'ترميم المباني القديمة', 'bina' ) ),
					array( 'text' => __( 'تجديد الديكورات الداخلية', 'bina' ) ),
					array( 'text' => __( 'إعادة تصميم المطابخ والحمامات', 'bina' ) ),
					array( 'text' => __( 'تركيب الأرضيات والسيراميك', 'bina' ) ),
					array( 'text' => __( 'أعمال الدهان والجبس', 'bina' ) ),
					array( 'text' => __( 'الصيانة العامة', 'bina' ) ),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s       = $this->get_settings_for_display();
		$heading = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$items   = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		$pad     = isset( $s['section_padding'] ) ? trim( (string) $s['section_padding'] ) : '';
		if ( '' === $pad ) {
			$pad = 'py-16';
		}
		?>
		<section class="<?php echo esc_attr( $pad ); ?> bg-muted/30">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html( $heading ); ?></h2>
					</div>
				</div>

				<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
					<?php foreach ( $items as $it ) : ?>
						<?php $tx = isset( $it['text'] ) ? (string) $it['text'] : ''; ?>
						<div>
							<div class="flex items-center gap-3 bg-card p-4 rounded-xl border border-border">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check w-5 h-5 text-primary flex-shrink-0"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
								<span class="text-foreground"><?php echo esc_html( $tx ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

