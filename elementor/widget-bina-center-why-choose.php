<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Service_Why_Choose_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_service_why_choose';
	}

	public function get_title() {
		return __( 'Bina Service — لماذا تختار', 'bina' );
	}

	public function get_icon() {
		return 'eicon-favorite';
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
				'default' => __( 'لماذا تختار بناء سنتر؟', 'bina' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'الوصف', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'نحن لا نبني، بل نجمع لك أفضل المقاولين ونضمن حقوقك', 'bina' ),
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'title',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مقاولون معتمدون', 'bina' ),
			)
		);
		$rep->add_control(
			'text',
			array(
				'label'   => __( 'النص', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'نجمع لك عروض من مقاولين موثوقين ومعتمدين لبناء مشروعك', 'bina' ),
			)
		);
		$rep->add_control(
			'icon_image',
			array(
				'label' => __( 'أيقونة (صورة)', 'bina' ),
				'type'  => Controls_Manager::MEDIA,
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
						'title' => __( 'مقاولون معتمدون', 'bina' ),
						'text'  => __( 'نجمع لك عروض من مقاولين موثوقين ومعتمدين لبناء مشروعك', 'bina' ),
					),
					array(
						'title' => __( 'عقود موثقة', 'bina' ),
						'text'  => __( 'عقود واضحة ومحمية قانونياً لضمان حقوقك', 'bina' ),
					),
					array(
						'title' => __( 'ضمان الجودة', 'bina' ),
						'text'  => __( 'متابعة ومراقبة جودة التنفيذ طوال مراحل المشروع', 'bina' ),
					),
					array(
						'title' => __( 'التزام بالمواعيد', 'bina' ),
						'text'  => __( 'جدول زمني واضح مع متابعة دورية للتأكد من الالتزام', 'bina' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s        = $this->get_settings_for_display();
		$heading  = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$desc     = isset( $s['description'] ) ? (string) $s['description'] : '';
		$items    = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		?>
		<section class="py-20 bg-muted/30">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html( $heading ); ?></h2>
						<p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html( $desc ); ?></p>
					</div>
				</div>

				<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
					<?php foreach ( $items as $idx => $it ) : ?>
						<?php
						$t  = isset( $it['title'] ) ? (string) $it['title'] : '';
						$tx = isset( $it['text'] ) ? (string) $it['text'] : '';
						$icon_url = '';
						if ( isset( $it['icon_image']['url'] ) && is_string( $it['icon_image']['url'] ) ) {
							$icon_url = $it['icon_image']['url'];
						}
						?>
						<div>
							<div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 h-full text-center">
								<div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center mb-4 mx-auto">
									<?php if ( $icon_url ) : ?>
										<img class="w-7 h-7 object-contain" src="<?php echo esc_url( $icon_url ); ?>" alt="" loading="lazy" />
									<?php else : ?>
										<?php if ( 0 === (int) $idx ) : ?>
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-7 h-7 text-white"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
										<?php elseif ( 1 === (int) $idx ) : ?>
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-7 h-7 text-white"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
										<?php elseif ( 2 === (int) $idx ) : ?>
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield w-7 h-7 text-white"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>
										<?php else : ?>
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-7 h-7 text-white"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								<h3 class="text-lg font-bold text-foreground mb-2"><?php echo esc_html( $t ); ?></h3>
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

