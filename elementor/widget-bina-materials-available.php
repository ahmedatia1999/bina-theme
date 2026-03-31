<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Materials_Available_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_materials_available';
	}

	public function get_title() {
		return __( 'Bina Service — المواد المتوفرة (شبكة)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
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
				'default' => __( 'المواد المتوفرة', 'bina' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'الوصف', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'جميع مواد البناء الأساسية متوفرة من مصادر موثوقة', 'bina' ),
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'label',
			array(
				'label'   => __( 'اسم المادة', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'حديد التسليح', 'bina' ),
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
				'label'       => __( 'العناصر', 'bina' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array( 'label' => __( 'حديد التسليح', 'bina' ) ),
					array( 'label' => __( 'الأسمنت', 'bina' ) ),
					array( 'label' => __( 'البلوك والطوب', 'bina' ) ),
					array( 'label' => __( 'الرمل والحصى', 'bina' ) ),
					array( 'label' => __( 'مواد العزل', 'bina' ) ),
					array( 'label' => __( 'السيراميك والبلاط', 'bina' ) ),
				),
			)
		);

		$this->add_control(
			'footer_link_text',
			array(
				'label'   => __( 'نص الرابط أسفل الشبكة (اختياري)', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'زيارة موقع شريكنا msamer.com', 'bina' ),
			)
		);
		$this->add_control(
			'footer_link_url',
			array(
				'label'       => __( 'رابط أسفل الشبكة (اختياري)', 'bina' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://msamer.com',
				'default'     => array( 'url' => 'https://msamer.com', 'is_external' => true ),
			)
		);

		$this->end_controls_section();
	}

	private function default_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package w-6 h-6 text-primary"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path><path d="M12 22V12"></path><path d="m3.3 7 7.703 4.734a2 2 0 0 0 1.994 0L20.7 7"></path><path d="m7.5 4.27 9 5.15"></path></svg>';
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$heading  = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$desc     = isset( $s['description'] ) ? (string) $s['description'] : '';
		$items    = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		$link_txt = isset( $s['footer_link_text'] ) ? trim( (string) $s['footer_link_text'] ) : '';

		$link_href   = '';
		$link_target = '';
		$link_rel    = '';
		if ( isset( $s['footer_link_url']['url'] ) && is_string( $s['footer_link_url']['url'] ) && '' !== trim( $s['footer_link_url']['url'] ) ) {
			$link_href = $s['footer_link_url']['url'];
			if ( isset( $s['footer_link_url']['is_external'] ) && $s['footer_link_url']['is_external'] ) {
				$link_target = ' target="_blank"';
				$link_rel    = ' rel="noopener noreferrer"';
			}
		}
		?>
		<section class="py-20 bg-background">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html( $heading ); ?></h2>
						<p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html( $desc ); ?></p>
					</div>
				</div>

				<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
					<?php foreach ( $items as $it ) : ?>
						<?php
						$lab = isset( $it['label'] ) ? (string) $it['label'] : '';
						$svg = isset( $it['icon_svg'] ) ? trim( (string) $it['icon_svg'] ) : '';
						if ( '' === $svg ) {
							$svg = $this->default_svg();
						}
						?>
						<div>
							<div class="bg-card rounded-xl p-4 shadow-card border border-border/50 text-center">
								<div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mb-3 mx-auto">
									<?php echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<p class="font-medium text-secondary text-sm"><?php echo esc_html( $lab ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( $link_href && $link_txt ) : ?>
					<div class="mt-8 text-center">
						<a href="<?php echo esc_url( $link_href ); ?>"<?php echo $link_target; ?><?php echo $link_rel; ?> class="inline-flex items-center gap-2 text-primary hover:underline">
							<?php echo esc_html( $link_txt ); ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-external-link w-4 h-4"><path d="M15 3h6v6"></path><path d="M10 14 21 3"></path><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path></svg>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

