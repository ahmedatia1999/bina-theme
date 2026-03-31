<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Materials_Benefits_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_materials_benefits';
	}

	public function get_title() {
		return __( 'Bina Service — فوائد (كروت)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-info-circle';
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
				'default' => __( 'ما الذي تستفيد منه؟', 'bina' ),
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'title',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'توفير حتى 50%', 'bina' ),
			)
		);
		$rep->add_control(
			'text',
			array(
				'label'   => __( 'النص', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'وفّر من تكاليف المواد بالشراء المباشر من المصانع والموردين المعتمدين', 'bina' ),
			)
		);
		$rep->add_control(
			'icon_svg',
			array(
				'label'       => __( 'SVG الأيقونة (اختياري)', 'bina' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'description' => __( 'الصق كود SVG هنا. إذا تركته فارغًا سيتم استخدام أيقونة افتراضية.', 'bina' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'الكروت', 'bina' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title' => __( 'توفير حتى 50%', 'bina' ),
						'text'  => __( 'وفّر من تكاليف المواد بالشراء المباشر من المصانع والموردين المعتمدين', 'bina' ),
					),
					array(
						'title' => __( 'شراء مباشر', 'bina' ),
						'text'  => __( 'تخطي الوسطاء والموزعين واحصل على أفضل الأسعار من المصدر', 'bina' ),
					),
					array(
						'title' => __( 'توصيل للموقع', 'bina' ),
						'text'  => __( 'توصيل المواد مباشرة إلى موقع مشروعك بأسعار تنافسية', 'bina' ),
					),
					array(
						'title' => __( 'أسعار شفافة', 'bina' ),
						'text'  => __( 'أسعار واضحة بدون تضخيم أو عمولات مخفية', 'bina' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	private function default_svg_for_index( $i ) {
		// Minimal set to match the provided HTML style.
		$svgs = array(
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-badge-percent w-7 h-7 text-primary"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"></path><path d="m15 9-6 6"></path><path d="M9 9h.01"></path><path d="M15 15h.01"></path></svg>',
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-factory w-7 h-7 text-primary"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"></path><path d="M17 18h1"></path><path d="M12 18h1"></path><path d="M7 18h1"></path></svg>',
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck w-7 h-7 text-primary"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"></path><path d="M15 18H9"></path><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"></path><circle cx="17" cy="18" r="2"></circle><circle cx="7" cy="18" r="2"></circle></svg>',
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card w-7 h-7 text-primary"><rect width="20" height="14" x="2" y="5" rx="2"></rect><line x1="2" x2="22" y1="10" y2="10"></line></svg>',
		);
		return $svgs[ $i % count( $svgs ) ];
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$title = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$items = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		?>
		<section class="py-20 bg-muted/30">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html( $title ); ?></h2>
					</div>
				</div>

				<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
					<?php foreach ( $items as $i => $it ) : ?>
						<?php
						$h = isset( $it['title'] ) ? (string) $it['title'] : '';
						$t = isset( $it['text'] ) ? (string) $it['text'] : '';
						$svg = isset( $it['icon_svg'] ) ? trim( (string) $it['icon_svg'] ) : '';
						if ( '' === $svg ) {
							$svg = $this->default_svg_for_index( (int) $i );
						}
						?>
						<div>
							<div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 h-full text-center">
								<div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-4 mx-auto">
									<?php echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<h3 class="text-xl font-bold text-secondary mb-2"><?php echo esc_html( $h ); ?></h3>
								<p class="text-muted-foreground text-sm"><?php echo esc_html( $t ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

