<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Service_FAQ_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_service_faq';
	}

	public function get_title() {
		return __( 'Bina Service — FAQ', 'bina' );
	}

	public function get_icon() {
		return 'eicon-help-o';
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
				'default' => __( 'الأسئلة الشائعة', 'bina' ),
			)
		);

		$this->add_control(
			'open_first',
			array(
				'label'        => __( 'فتح أول سؤال افتراضيًا', 'bina' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$rep = new Repeater();
		$rep->add_control(
			'q',
			array(
				'label'   => __( 'السؤال', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'ما هي المدة المتوقعة لبناء منزل؟', 'bina' ),
			)
		);
		$rep->add_control(
			'a',
			array(
				'label'   => __( 'الإجابة', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => __( 'تختلف المدة حسب حجم المشروع ومستوى التشطيب، لكن عادة تتراوح بين 8-14 شهراً لفيلا متوسطة الحجم.', 'bina' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'الأسئلة', 'bina' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rep->get_controls(),
				'title_field' => '{{{ q }}}',
				'default'     => array(
					array(
						'q' => __( 'ما هي المدة المتوقعة لبناء منزل؟', 'bina' ),
						'a' => __( 'تختلف المدة حسب حجم المشروع ومستوى التشطيب، لكن عادة تتراوح بين 8-14 شهراً لفيلا متوسطة الحجم.', 'bina' ),
					),
					array(
						'q' => __( 'هل توفرون ضمانات على البناء؟', 'bina' ),
						'a' => __( 'نعم، جميع المقاولين المعتمدين لدينا يقدمون ضمانات على أعمالهم تتراوح بين 5-10 سنوات حسب البند.', 'bina' ),
					),
					array(
						'q' => __( 'كيف يتم الدفع للمقاول؟', 'bina' ),
						'a' => __( 'يتم الدفع عبر نظام الدفعات المرحلية المرتبط بإنجاز كل مرحلة من المشروع، لضمان حقوق الطرفين.', 'bina' ),
					),
					array(
						'q' => __( 'هل يمكنني متابعة المشروع عن بعد؟', 'bina' ),
						'a' => __( 'نعم، نوفر تحديثات دورية مع صور ومقاطع فيديو لمراحل التنفيذ عبر المنصة.', 'bina' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$s          = $this->get_settings_for_display();
		$heading    = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$items      = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		$open_first = isset( $s['open_first'] ) && 'yes' === $s['open_first'];
		?>
		<section class="py-20 bg-muted/30">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html( $heading ); ?></h2>
					</div>
				</div>

				<div class="max-w-3xl mx-auto space-y-4">
					<?php foreach ( $items as $i => $it ) : ?>
						<?php
						$q = isset( $it['q'] ) ? (string) $it['q'] : '';
						$a = isset( $it['a'] ) ? (string) $it['a'] : '';
						$is_open = ( 0 === (int) $i ) && $open_first;
						?>
						<div>
							<div class="bg-card rounded-xl border border-border overflow-hidden">
								<button class="w-full px-6 py-4 flex items-center justify-between text-start" type="button">
									<span class="font-semibold text-foreground"><?php echo esc_html( $q ); ?></span>
									<?php if ( $is_open ) : ?>
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up w-5 h-5 text-primary flex-shrink-0"><path d="m18 15-6-6-6 6"></path></svg>
									<?php else : ?>
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down w-5 h-5 text-muted-foreground flex-shrink-0"><path d="m6 9 6 6 6-6"></path></svg>
									<?php endif; ?>
								</button>
								<div class="overflow-hidden" style="height: <?php echo $is_open ? 'auto' : '0px'; ?>;">
									<div class="px-6 pb-4 text-muted-foreground"><?php echo esc_html( $a ); ?></div>
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

