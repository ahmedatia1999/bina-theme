<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Bina_Materials_FAQ_Accordion_Widget extends Widget_Base {

	public function get_name() {
		return 'bina_bina_materials_faq_accordion';
	}

	public function get_title() {
		return __( 'Bina Service — FAQ (Accordion)', 'bina' );
	}

	public function get_icon() {
		return 'eicon-accordion';
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

		$rep = new Repeater();
		$rep->add_control(
			'q',
			array(
				'label'   => __( 'السؤال', 'bina' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'ما هي المواد المتوفرة؟', 'bina' ),
			)
		);
		$rep->add_control(
			'a',
			array(
				'label'   => __( 'الإجابة', 'bina' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => __( 'نوفر جميع مواد البناء الأساسية بالتعاون مع شريكنا msamer.com.', 'bina' ),
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
						'q' => __( 'ما هي المواد المتوفرة؟', 'bina' ),
						'a' => __( 'نوفر جميع مواد البناء الأساسية من حديد وأسمنت وبلوك ورمل وحصى ومواد العزل والتشطيبات بالتعاون مع شريكنا msamer.com.', 'bina' ),
					),
					array(
						'q' => __( 'كيف يتم التوفير في التكاليف؟', 'bina' ),
						'a' => __( 'نتعاون مباشرة مع المصانع والموردين المعتمدين مما يختصر سلسلة الوسطاء ويقلل التكلفة النهائية بنسبة تصل إلى 30-50%.', 'bina' ),
					),
					array(
						'q' => __( 'هل يشمل السعر التوصيل؟', 'bina' ),
						'a' => __( 'تكلفة التوصيل منفصلة وتُحسب بناءً على موقع المشروع وكمية المواد. نقدم أسعار توصيل تنافسية.', 'bina' ),
					),
					array(
						'q' => __( 'ما هي مدة التوصيل؟', 'bina' ),
						'a' => __( 'تختلف مدة التوصيل حسب نوع المواد والكميات، عادةً من 2-7 أيام عمل بعد تأكيد الطلب.', 'bina' ),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	private function icon_svg() {
		return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-help w-5 h-5 text-primary"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><path d="M12 17h.01"></path></svg>';
	}

	protected function render() {
		$s     = $this->get_settings_for_display();
		$title = isset( $s['heading'] ) ? (string) $s['heading'] : '';
		$items = isset( $s['items'] ) && is_array( $s['items'] ) ? $s['items'] : array();
		$uid   = 'binaFaq' . substr( md5( wp_json_encode( $items ) . microtime( true ) ), 0, 8 );
		?>
		<section class="py-20 bg-background">
			<div class="container-custom">
				<div>
					<div class="text-center mb-12">
						<h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html( $title ); ?></h2>
					</div>
				</div>

				<div class="max-w-3xl mx-auto">
					<div class="grid gap-4" data-bina-faq-acc="<?php echo esc_attr( $uid ); ?>">
						<?php foreach ( $items as $i => $it ) : ?>
							<?php
							$q = isset( $it['q'] ) ? (string) $it['q'] : '';
							$a = isset( $it['a'] ) ? (string) $it['a'] : '';
							$open = ( 0 === (int) $i );
							?>
							<div>
								<div class="bg-card rounded-xl border border-border/50 overflow-hidden">
									<button type="button" class="w-full flex items-center gap-4 p-5 text-start hover:bg-muted/50 transition-colors" data-bina-faq-btn="<?php echo esc_attr( $uid ); ?>">
										<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
											<?php echo $this->icon_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
										</div>
										<span class="flex-grow font-semibold text-secondary"><?php echo esc_html( $q ); ?></span>
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide <?php echo $open ? 'lucide-chevron-up' : 'lucide-chevron-down'; ?> w-5 h-5 text-muted-foreground shrink-0" data-bina-faq-ico="<?php echo esc_attr( $uid ); ?>"><path d="<?php echo $open ? 'm18 15-6-6-6 6' : 'm6 9 6 6 6-6'; ?>"></path></svg>
									</button>
									<div class="overflow-hidden" data-bina-faq-panel="<?php echo esc_attr( $uid ); ?>" style="height: <?php echo $open ? 'auto' : '0px'; ?>; opacity: <?php echo $open ? '1' : '0'; ?>;">
										<div class="px-5 pb-5 pt-0 text-muted-foreground border-t border-border/50">
											<p class="pt-4 whitespace-pre-line"><?php echo esc_html( $a ); ?></p>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</section>
		<script>
		(function(){
			var root = document.querySelector('[data-bina-faq-acc="<?php echo esc_js( $uid ); ?>"]');
			if(!root) return;
			var btns = root.querySelectorAll('[data-bina-faq-btn="<?php echo esc_js( $uid ); ?>"]');
			btns.forEach(function(btn){
				btn.addEventListener('click', function(){
					var card = btn.closest('.bg-card');
					var panel = card ? card.querySelector('[data-bina-faq-panel="<?php echo esc_js( $uid ); ?>"]') : null;
					var ico = btn.querySelector('[data-bina-faq-ico="<?php echo esc_js( $uid ); ?>"]');
					if(!panel) return;
					var isOpen = panel.style.height !== '0px' && panel.style.height !== '';
					// close all
					root.querySelectorAll('[data-bina-faq-panel="<?php echo esc_js( $uid ); ?>"]').forEach(function(p){
						p.style.height = '0px';
						p.style.opacity = '0';
					});
					root.querySelectorAll('[data-bina-faq-ico="<?php echo esc_js( $uid ); ?>"]').forEach(function(i2){
						i2.classList.remove('lucide-chevron-up');
						i2.classList.add('lucide-chevron-down');
						var path = i2.querySelector('path');
						if(path) path.setAttribute('d', 'm6 9 6 6 6-6');
					});
					if(!isOpen){
						panel.style.height = 'auto';
						panel.style.opacity = '1';
						if(ico){
							ico.classList.remove('lucide-chevron-down');
							ico.classList.add('lucide-chevron-up');
							var path2 = ico.querySelector('path');
							if(path2) path2.setAttribute('d', 'm18 15-6-6-6 6');
						}
					}
				});
			});
		})();
		</script>
		<?php
	}
}

