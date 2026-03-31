<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Partnership_Types_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_partnership_types';
	}

	public function get_title() {
		return __( 'Bina Service — أنواع الشراكات', 'bina' );
	}

	public function get_icon() {
		return 'eicon-posts-group';
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
				'default' => __( 'أنواع الشراكات', 'bina' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'الوصف', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'نقدم نماذج شراكة مرنة تناسب احتياجاتك وأهدافك', 'bina' ),
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'title',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'شراكة بنسبة', 'bina' ),
			)
		);
		$rep->add_control(
			'text',
			array(
				'label'   => __( 'النص', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'تحصل على نسبة من الأرباح أو الوحدات العقارية بعد اكتمال المشروع', 'bina' ),
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
						'title' => __( 'شراكة بنسبة', 'bina' ),
						'text'  => __( 'تحصل على نسبة من الأرباح أو الوحدات العقارية بعد اكتمال المشروع', 'bina' ),
					),
					array(
						'title' => __( 'شراكة بوحدات', 'bina' ),
						'text'  => __( 'تحصل على عدد معين من الوحدات العقارية كحصتك من المشروع', 'bina' ),
					),
					array(
						'title' => __( 'شراكة مختلطة', 'bina' ),
						'text'  => __( 'مزيج من النسبة والوحدات حسب الاتفاق ودراسة الجدوى', 'bina' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	private function default_svg_for_index( $i ) {
		$svgs = array(
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-8 h-8 text-primary"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>',
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 w-8 h-8 text-primary"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>',
			'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-handshake w-8 h-8 text-primary"><path d="m11 17 2 2a1 1 0 1 0 3-3"></path><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"></path><path d="m21 3 1 11h-2"></path><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"></path><path d="M3 4h8"></path></svg>',
		);
		return $svgs[ $i % count( $svgs ) ];
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$title = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$desc  = isset( $s['description'] ) ? (string) $s['description'] : '';
		$items = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		?>
		<section class="py-20 bg-background">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html( $title ); ?></h2>
						<p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html( $desc ); ?></p>
					</div>
				</div>

				<div class="grid md:grid-cols-3 gap-6">
					<?php foreach ( $items as $i => $it ) : ?>
						<?php
						$h   = isset( $it['title'] ) ? (string) $it['title'] : '';
						$tx  = isset( $it['text'] ) ? (string) $it['text'] : '';
						$svg = isset( $it['icon_svg'] ) ? trim( (string) $it['icon_svg'] ) : '';
						if ( '' === $svg ) {
							$svg = $this->default_svg_for_index( (int) $i );
						}
						?>
						<div>
							<div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 text-center">
								<div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-4 mx-auto">
									<?php echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<h3 class="text-xl font-bold text-secondary mb-3"><?php echo esc_html( $h ); ?></h3>
								<p class="text-muted-foreground"><?php echo esc_html( $tx ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

