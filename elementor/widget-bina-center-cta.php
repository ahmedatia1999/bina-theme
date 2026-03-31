<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Bina_Service_CTA_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_service_cta';
	}

	public function get_title() {
		return __( 'Bina Service — CTA', 'bina' );
	}

	public function get_icon() {
		return 'eicon-call-to-action';
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
				'label'       => __( 'كلاسات السكشن (اختياري)', 'bina' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'py-20 bg-primary',
				'default'     => 'py-20 bg-primary',
			)
		);

		$this->add_control(
			'heading_css',
			array(
				'label'       => __( 'كلاسات العنوان (اختياري)', 'bina' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'text-primary-foreground',
				'default'     => 'text-primary-foreground',
			)
		);

		$this->add_control(
			'description_css',
			array(
				'label'       => __( 'كلاسات الوصف (اختياري)', 'bina' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'text-primary-foreground/80',
				'default'     => 'text-primary-foreground/80',
			)
		);

		$this->add_control(
			'heading',
			array(
				'label'   => __( 'العنوان', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'جاهز لبناء مشروعك؟', 'bina' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'الوصف', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'أضف مشروعك الآن واحصل على عروض من أفضل المقاولين في منطقتك', 'bina' ),
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
		$url = isset( $s['primary_button_url']['url'] ) ? (string) $s['primary_button_url']['url'] : '';
		$url = function_exists( 'bina_dashboard_resolve_url' ) ? bina_dashboard_resolve_url( $url ) : $url;
		$url = $url ? esc_url( $url ) : '#';
		$h   = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$d   = isset( $s['description'] ) ? (string) $s['description'] : '';
		$bt  = isset( $s['primary_button_text'] ) ? (string) $s['primary_button_text'] : '';
		$sec_css = isset( $s['section_css'] ) ? trim( (string) $s['section_css'] ) : '';
		$h_css   = isset( $s['heading_css'] ) ? trim( (string) $s['heading_css'] ) : '';
		$d_css   = isset( $s['description_css'] ) ? trim( (string) $s['description_css'] ) : '';
		if ( '' === $sec_css ) {
			$sec_css = 'py-20 bg-primary';
		}
		if ( '' === $h_css ) {
			$h_css = 'text-primary-foreground';
		}
		if ( '' === $d_css ) {
			$d_css = 'text-primary-foreground/80';
		}

		$btn_target = '';
		$btn_rel    = '';
		if ( isset( $s['primary_button_url']['is_external'] ) && $s['primary_button_url']['is_external'] ) {
			$btn_target = ' target="_blank"';
			$btn_rel    = ' rel="noopener noreferrer"';
		}
		?>
		<section class="<?php echo esc_attr( $sec_css ); ?>">
			<div class="container-custom text-center">
				<div>
					<h2 class="text-3xl md:text-4xl font-bold <?php echo esc_attr( $h_css ); ?> mb-6"><?php echo esc_html( $h ); ?></h2>
					<p class="<?php echo esc_attr( $d_css ); ?> mb-8 max-w-2xl mx-auto"><?php echo esc_html( $d ); ?></p>
					<a href="<?php echo $url; ?>"<?php echo $btn_target; ?><?php echo $btn_rel; ?> class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-secondary text-secondary-foreground hover:bg-secondary/80 h-11 rounded-md text-lg px-8 py-6">
						<?php echo esc_html( $bt ); ?>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left w-5 h-5"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
					</a>
				</div>
			</div>
		</section>
		<?php
	}
}

