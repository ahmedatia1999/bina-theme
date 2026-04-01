<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_FAQ_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_faq';
    }

    public function get_title() {
        return __('FAQ (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-help-o';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('FAQ Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('أسئلة شائعة', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('الأسئلة الشائعة', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('إجابات على أهم الأسئلة التي قد تدور في ذهنك', 'bina'),
            ]
        );

        $item = new Repeater();
        $item->add_control(
            'question',
            [
                'label' => __('Question', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ما هي بناء وكيف تعمل؟', 'bina'),
            ]
        );
        $item->add_control(
            'answer',
            [
                'label' => __('Answer', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 5,
                'default' => __('بناء هي منصة سعودية رائدة تربط أصحاب مشاريع البناء والترميم مع نخبة من المقاولين المعتمدين. نساعدك في إضافة مشروعك، استلام عروض أسعار من مقاولين محترفين، والمقارنة بينها لاختيار الأفضل.', 'bina'),
            ]
        );
        $item->add_control(
            'open_by_default',
            [
                'label' => __('Open by default', 'bina'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'bina'),
                'label_off' => __('No', 'bina'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => __('FAQ Items', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $item->get_controls(),
                'default' => [
                    [
                        'question' => 'ما هي بناء وكيف تعمل؟',
                        'answer' => 'بناء هي منصة سعودية رائدة تربط أصحاب مشاريع البناء والترميم مع نخبة من المقاولين المعتمدين. نساعدك في إضافة مشروعك، استلام عروض أسعار من مقاولين محترفين، والمقارنة بينها لاختيار الأفضل.',
                    ],
                    [
                        'question' => 'كيف يمكنني إضافة مشروع جديد؟',
                        'answer' => 'يمكنك إضافة مشروعك بكل سهولة من خلال الضغط على زر "أضف مشروعك" في الصفحة الرئيسية، ثم قم بتسجيل الدخول أو إنشاء حساب جديد في منصتنا، وبعدها أضف تفاصيل مشروعك. سيقوم فريقنا بمراجعة طلبك وربطك بالمقاولين المناسبين.',
                    ],
                    [
                        'question' => 'هل الخدمة مجانية لأصحاب المشاريع؟',
                        'answer' => 'نعم، خدمة إضافة المشاريع واستلام العروض مجانية تماماً لأصحاب المشاريع. نحن نهدف لمساعدتك في الحصول على أفضل عروض الأسعار من مقاولين موثوقين دون أي رسوم.',
                    ],
                    [
                        'question' => 'كيف يتم فحص واعتماد المقاولين؟',
                        'answer' => 'نقوم بفحص شامل لكل مقاول يتقدم للانضمام إلى منصتنا. يشمل ذلك التحقق من السجل التجاري، رخص المقاولات، سابقة الأعمال، وتقييمات العملاء السابقين. فقط المقاولين الذين يستوفون معاييرنا الصارمة يتم اعتمادهم.',
                    ],
                    [
                        'question' => 'هل يمكنني الحصول على تمويل لمشروعي؟',
                        'answer' => 'نعم، نوفر خيارات تمويل مرنة بالتعاون مع شركائنا الماليين. يمكنك التقدم بطلب تمويل من خلال صفحة "التمويل" وسنساعدك في الحصول على أفضل العروض التمويلية المناسبة لمشروعك.',
                    ],
                    [
                        'question' => 'كيف تضمنون جودة التنفيذ؟',
                        'answer' => 'نوفر خدمات الفحص والمعاينة الميدانية قبل وأثناء وبعد التنفيذ. كما نقدم خدمة توثيق العقود رقمياً لحماية حقوقك. بالإضافة إلى ذلك، نظام التقييمات يساعد في ضمان التزام المقاولين بأعلى معايير الجودة.',
                    ],
                ],
                'title_field' => '{{{ question }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items = $settings['items'] ?? [];
        $wid = $this->get_id();
        ?>

                <section class="py-16 md:py-24 bg-muted/30 content-visibility-auto">
                    <div class="container-custom">
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="text-center max-w-3xl mx-auto mb-10 md:mb-14 px-4"><span
                                    class="inline-block px-3 py-1 sm:px-4 sm:py-1.5 bg-primary/10 text-primary rounded-full text-xs sm:text-sm font-medium mb-3 md:mb-4"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                                <h2
                                    class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-foreground mb-3 md:mb-4">
                                    <?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                                <p class="text-sm sm:text-base md:text-lg text-muted-foreground"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="max-w-3xl mx-auto px-4">
                                <div class="space-y-4" data-orientation="vertical" data-bina-faq="<?php echo esc_attr($wid); ?>">
                                    <?php foreach ($items as $i => $it): ?>
                                        <?php
                                            $q = $it['question'] ?? '';
                                            $a = $it['answer'] ?? '';
                                            $open = (($it['open_by_default'] ?? '') === 'yes');
                                            $btn_id = 'bina-faq-' . $wid . '-btn-' . $i;
                                            $panel_id = 'bina-faq-' . $wid . '-panel-' . $i;
                                        ?>
                                        <div>
                                            <div class="bg-card rounded-xl border border-border overflow-hidden">
                                                <button
                                                    type="button"
                                                    id="<?php echo esc_attr($btn_id); ?>"
                                                    aria-controls="<?php echo esc_attr($panel_id); ?>"
                                                    aria-expanded="<?php echo $open ? 'true' : 'false'; ?>"
                                                    class="w-full px-6 py-4 flex items-center justify-between text-start"
                                                    data-bina-faq-btn="<?php echo esc_attr($wid); ?>"
                                                >
                                                    <span class="font-semibold text-foreground"><?php echo esc_html($q); ?></span>
                                                    <?php if ($open): ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-chevron-up w-5 h-5 text-primary flex-shrink-0"
                                                            data-bina-faq-ico="<?php echo esc_attr($wid); ?>"
                                                        ><path d="m18 15-6-6-6 6"></path></svg>
                                                    <?php else: ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-chevron-down w-5 h-5 text-muted-foreground flex-shrink-0"
                                                            data-bina-faq-ico="<?php echo esc_attr($wid); ?>"
                                                        ><path d="m6 9 6 6 6-6"></path></svg>
                                                    <?php endif; ?>
                                                </button>
                                                <div
                                                    id="<?php echo esc_attr($panel_id); ?>"
                                                    role="region"
                                                    aria-labelledby="<?php echo esc_attr($btn_id); ?>"
                                                    class="overflow-hidden"
                                                    style="height: <?php echo $open ? 'auto' : '0px'; ?>; transition: height 240ms ease;"
                                                    data-bina-faq-panel="<?php echo esc_attr($wid); ?>"
                                                >
                                                    <div class="px-6 pb-4 text-muted-foreground text-sm md:text-base leading-relaxed">
                                                        <?php echo esc_html($a); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <script>
                (function () {
                    var root = document.querySelector('[data-bina-faq="<?php echo esc_js($wid); ?>"]');
                    if (!root) return;

                    function setOpen(btn, panel, ico, open) {
                        if (!btn || !panel) return;
                        btn.setAttribute('aria-expanded', open ? 'true' : 'false');

                        if (panel.__binaFaqOnEnd) {
                            panel.removeEventListener('transitionend', panel.__binaFaqOnEnd);
                            panel.__binaFaqOnEnd = null;
                        }

                        if (open) {
                            panel.style.height = '0px';
                            panel.offsetHeight; // reflow
                            var target = panel.scrollHeight;
                            requestAnimationFrame(function () {
                                panel.style.height = target + 'px';
                            });
                            var onEnd = function (e) {
                                if (e.propertyName !== 'height') return;
                                panel.removeEventListener('transitionend', onEnd);
                                panel.__binaFaqOnEnd = null;
                                panel.style.height = 'auto';
                            };
                            panel.__binaFaqOnEnd = onEnd;
                            panel.addEventListener('transitionend', onEnd);
                        } else {
                            var currentH = panel.scrollHeight;
                            panel.style.height = currentH + 'px';
                            panel.offsetHeight; // reflow
                            requestAnimationFrame(function () {
                                panel.style.height = '0px';
                            });
                        }

                        if (ico) {
                            ico.classList.remove(open ? 'lucide-chevron-down' : 'lucide-chevron-up');
                            ico.classList.add(open ? 'lucide-chevron-up' : 'lucide-chevron-down');
                            ico.classList.toggle('text-primary', !!open);
                            ico.classList.toggle('text-muted-foreground', !open);
                            var p = ico.querySelector('path');
                            if (p) p.setAttribute('d', open ? 'm18 15-6-6-6 6' : 'm6 9 6 6 6-6');
                        }
                    }

                    var btns = Array.prototype.slice.call(root.querySelectorAll('[data-bina-faq-btn="<?php echo esc_js($wid); ?>"]'));
                    btns.forEach(function (btn) {
                        var panelId = btn.getAttribute('aria-controls') || '';
                        var panel = panelId ? root.querySelector('#' + CSS.escape(panelId)) : null;
                        var ico = btn.querySelector('[data-bina-faq-ico="<?php echo esc_js($wid); ?>"]');
                        if (!panel) return;

                        // Normalize initial state: if open -> auto, else -> 0px
                        var isOpen = btn.getAttribute('aria-expanded') === 'true';
                        panel.style.transition = 'height 240ms ease';
                        panel.style.height = isOpen ? 'auto' : '0px';

                        btn.addEventListener('click', function () {
                            var openNow = btn.getAttribute('aria-expanded') === 'true';
                            if (openNow) {
                                setOpen(btn, panel, ico, false);
                                return;
                            }
                            // close others
                            btns.forEach(function (b2) {
                                if (b2 === btn) return;
                                var pid2 = b2.getAttribute('aria-controls') || '';
                                var p2 = pid2 ? root.querySelector('#' + CSS.escape(pid2)) : null;
                                var i2 = b2.querySelector('[data-bina-faq-ico="<?php echo esc_js($wid); ?>"]');
                                if (p2) setOpen(b2, p2, i2, false);
                            });
                            setOpen(btn, panel, ico, true);
                        });
                    });
                })();
                </script>

        <?php
    }
}

