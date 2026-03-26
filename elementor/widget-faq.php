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
                                            $state = $open ? 'open' : 'closed';
                                            $btn_id = 'bina-faq-' . $wid . '-btn-' . $i;
                                            $panel_id = 'bina-faq-' . $wid . '-panel-' . $i;
                                        ?>
                                        <div data-state="<?php echo esc_attr($state); ?>" data-orientation="vertical"
                                            class="bg-card border border-border rounded-xl px-4 md:px-6 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                            <h3 data-orientation="vertical" data-state="<?php echo esc_attr($state); ?>" class="flex">
                                                <button
                                                    type="button"
                                                    aria-controls="<?php echo esc_attr($panel_id); ?>"
                                                    aria-expanded="<?php echo $open ? 'true' : 'false'; ?>"
                                                    data-state="<?php echo esc_attr($state); ?>"
                                                    data-orientation="vertical"
                                                    id="<?php echo esc_attr($btn_id); ?>"
                                                    class="flex flex-1 items-center justify-between transition-all [&amp;[data-state=open]&gt;svg]:rotate-180 text-sm md:text-base lg:text-lg font-semibold text-foreground hover:no-underline py-4 md:py-5 text-start"
                                                    data-radix-collection-item=""
                                                ><?php echo esc_html($q); ?><svg
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform duration-200">
                                                        <path d="m6 9 6 6 6-6"></path>
                                                    </svg></button>
                                            </h3>
                                            <div
                                                data-state="<?php echo esc_attr($state); ?>"
                                                id="<?php echo esc_attr($panel_id); ?>"
                                                <?php if (!$open): ?>hidden=""<?php endif; ?>
                                                role="region"
                                                aria-labelledby="<?php echo esc_attr($btn_id); ?>"
                                                data-orientation="vertical"
                                                class="overflow-hidden text-sm transition-all data-[state=closed]:animate-accordion-up data-[state=open]:animate-accordion-down pt-0 text-sm md:text-base text-muted-foreground leading-relaxed"
                                                style="--radix-accordion-content-height: var(--radix-collapsible-content-height); --radix-accordion-content-width: var(--radix-collapsible-content-width);"
                                            >
                                                <?php echo esc_html($a); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

        <?php
    }
}

