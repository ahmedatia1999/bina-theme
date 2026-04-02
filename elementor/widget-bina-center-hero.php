<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Bina_Service_Hero_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_service_hero';
	}

	public function get_title() {
		return __( 'Bina Service — Hero', 'bina' );
	}

	public function get_icon() {
		return 'eicon-banner';
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
			'badge_url',
			array(
				'label'       => __( 'رابط الشارة (اختياري)', 'bina' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
			)
		);

		$this->add_control(
			'badge_css',
			array(
				'label'       => __( 'كلاسات الشارة (اختياري)', 'bina' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'bg-primary/10 text-primary',
				'default'     => 'bg-primary/10 text-primary',
			)
		);

		$this->add_control(
			'badge_text',
			array(
				'label'   => __( 'نص الشارة', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'بناء جديد', 'bina' ),
			)
		);

		$this->add_control(
			'badge_icon',
			array(
				'label' => __( 'أيقونة الشارة (صورة اختيارية)', 'bina' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'منصة تجمع لك أفضل المقاولين', 'bina' ),
			)
		);

		$this->add_control(
			'heading_css',
			array(
				'label'       => __( 'كلاسات العنوان (اختياري)', 'bina' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'text-foreground',
				'default'     => 'text-foreground',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'الوصف', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => __( 'ابنِ منزلك مع مقاولين موثوقين. نجمع لك العروض، تقارن وتختار الأنسب لك.', 'bina' ),
			)
		);

		$this->add_control(
			'primary_button_text',
			array(
				'label'   => __( 'نص الزر', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'أضف مشروعك الآن', 'bina' ),
			)
		);

		$this->add_control(
			'primary_button_url',
			array(
				'label'       => __( 'رابط زر "أضف مشروعك الآن"', 'bina' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => '/customer-create-project',
				'default'     => array( 'url' => '/customer-create-project' ),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s   = $this->get_settings_for_display();
		$url = isset( $s['primary_button_url']['url'] ) ? $s['primary_button_url']['url'] : '';
		$url = function_exists( 'bina_dashboard_resolve_url' ) ? bina_dashboard_resolve_url( $url ) : $url;
		$url = $url ? esc_url( $url ) : '#';

		$badge_text = isset( $s['badge_text'] ) ? (string) $s['badge_text'] : '';
		$heading    = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$desc       = isset( $s['description'] ) ? (string) $s['description'] : '';
		$btn_text   = isset( $s['primary_button_text'] ) ? (string) $s['primary_button_text'] : '';
		$is_en       = function_exists( 'bina_trp_current_lang' ) ? ( bina_trp_current_lang() === 'en' ) : false;
		$badge_css  = isset( $s['badge_css'] ) ? trim( (string) $s['badge_css'] ) : '';
		$heading_css = isset( $s['heading_css'] ) ? trim( (string) $s['heading_css'] ) : '';
		if ( '' === $badge_css ) {
			$badge_css = 'bg-primary/10 text-primary';
		}
		if ( '' === $heading_css ) {
			$heading_css = 'text-foreground';
		}

		$badge_icon_url = '';
		if ( isset( $s['badge_icon']['url'] ) && is_string( $s['badge_icon']['url'] ) ) {
			$badge_icon_url = $s['badge_icon']['url'];
		}

		$badge_href   = '';
		$badge_target = '';
		$badge_rel    = '';
		if ( isset( $s['badge_url']['url'] ) && is_string( $s['badge_url']['url'] ) && '' !== trim( $s['badge_url']['url'] ) ) {
			$badge_href = $s['badge_url']['url'];
			if ( isset( $s['badge_url']['is_external'] ) && $s['badge_url']['is_external'] ) {
				$badge_target = ' target="_blank"';
				$badge_rel    = ' rel="noopener noreferrer"';
			}
		}

		?>
		<section class="pt-20 md:pt-24 pb-12 bg-background relative overflow-hidden">
			<div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(hsl(var(--border)) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--border)) 1px, transparent 1px); background-size: 60px 60px;"></div>
			<div class="container-custom relative z-10">
				<div class="text-center max-w-4xl mx-auto">
					<?php if ( $badge_href ) : ?>
						<a href="<?php echo esc_url( $badge_href ); ?>"<?php echo $badge_target; ?><?php echo $badge_rel; ?> class="inline-flex items-center gap-2 px-4 py-2 <?php echo esc_attr( $badge_css ); ?> rounded-full text-sm font-medium mb-6 hover:opacity-90 transition-colors">
					<?php else : ?>
						<div class="inline-flex items-center gap-2 px-4 py-2 <?php echo esc_attr( $badge_css ); ?> rounded-full text-sm font-medium mb-6">
					<?php endif; ?>
						<?php if ( $badge_icon_url ) : ?>
							<img class="w-4 h-4 object-contain" src="<?php echo esc_url( $badge_icon_url ); ?>" alt="" loading="lazy" />
						<?php else : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 w-4 h-4"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
						<?php endif; ?>
						<?php echo esc_html( $badge_text ); ?>
					<?php if ( $badge_href ) : ?>
						</a>
					<?php else : ?>
						</div>
					<?php endif; ?>
					<h1 class="text-4xl md:text-5xl lg:text-6xl font-bold <?php echo esc_attr( $heading_css ); ?> mb-6"><?php echo esc_html( $heading ); ?></h1>
					<p class="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto"><?php echo esc_html( $desc ); ?></p>
					<a href="<?php echo $url; ?>" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg text-lg px-8 py-6">
						<?php echo esc_html( $btn_text ); ?>
						<?php if ( $is_en ) : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right w-5 h-5">
								<path d="M5 12h14"></path>
								<path d="m12 5 7 7-7 7"></path>
							</svg>
						<?php else : ?>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left w-5 h-5">
								<path d="m12 19-7-7 7-7"></path>
								<path d="M19 12H5"></path>
							</svg>
						<?php endif; ?>
					</a>
				</div>
			</div>
		</section>
		<?php
	}
}

