<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Contractors_FAQ_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_contractors_faq';
    }

    public function get_title() {
        return __('Contractors FAQ (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-help-o';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('FAQ Content', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('الأسئلة الشائعة', 'bina'),
        ]);

        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('إليك أهم الأسئلة التي يطرحها المقاولون عند الانضمام إلى منصتنا', 'bina'),
        ]);

        $item = new Repeater();
        $item->add_control('icon', [
            'label' => __('Question Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-credit-card', 'library' => 'fa-solid'],
        ]);
        $item->add_control('question', [
            'label' => __('Question', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('كيف يتم دفع العمولة؟', 'bina'),
        ]);
        $item->add_control('answer', [
            'label' => __('Answer', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 5,
            'default' => __("تُحتسب العمولة فقط عند ترسية المشروع وإتمامه بنجاح. يمكنك الدفع عبر:\n• تحويل بنكي مباشر\n• خصم من مستحقات المشروع\n• بطاقة ائتمان", 'bina'),
        ]);
        $item->add_control('open_by_default', [
            'label' => __('Open by default', 'bina'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'bina'),
            'label_off' => __('No', 'bina'),
            'return_value' => 'yes',
            'default' => '',
        ]);

        $this->add_control('items', [
            'label' => __('FAQ Items', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $item->get_controls(),
            'default' => [
                [
                    'icon' => ['value' => 'fas fa-credit-card', 'library' => 'fa-solid'],
                    'question' => 'كيف يتم دفع العمولة؟',
                    'answer' => "تُحتسب العمولة فقط عند ترسية المشروع وإتمامه بنجاح. يمكنك الدفع عبر:\n• تحويل بنكي مباشر\n• خصم من مستحقات المشروع\n• بطاقة ائتمان",
                ],
                [
                    'icon' => ['value' => 'fas fa-chart-line', 'library' => 'fa-solid'],
                    'question' => 'كيف أحصل على مشاريع أكثر؟',
                    'answer' => "نقدم عدة طرق لزيادة فرص حصولك على المشاريع:\n• تحديث ملفك الشخصي باستمرار\n• الحصول على تقييمات إيجابية\n• الترقية إلى الباقات المتقدمة\n• الرد السريع على العملاء",
                    'open_by_default' => 'yes',
                ],
                [
                    'icon' => ['value' => 'fas fa-phone', 'library' => 'fa-solid'],
                    'question' => 'كيف يتم التواصل مع العملاء؟',
                    'answer' => "نوفر عدة قنوات للتواصل:\n• نظام المراسلات الداخلي\n• المكالمات الصوتية المباشرة\n• تبادل معلومات التواصل بعد الموافقة",
                ],
                [
                    'icon' => ['value' => 'fas fa-rotate-left', 'library' => 'fa-solid'],
                    'question' => 'ما هي سياسة إلغاء الاشتراك؟',
                    'answer' => "تفاصيل مهمة حول الإلغاء:\n• فترة تجريبية مجانية لمدة 14 يوم\n• إمكانية الإلغاء قبل التوثيق النهائي\n• استرداد جزئي خلال 48 ساعة من الدفع",
                ],
                [
                    'icon' => ['value' => 'fas fa-shield-halved', 'library' => 'fa-solid'],
                    'question' => 'كيف تتم حماية حقوقي؟',
                    'answer' => "نوفر عدة ضمانات لحماية حقوقك:\n• عقود موحدة ومعتمدة\n• نظام الدفعات المرحلية\n• خدمة حل النزاعات\n• تأمين على المشاريع",
                ],
                [
                    'icon' => ['value' => 'fas fa-award', 'library' => 'fa-solid'],
                    'question' => 'كيف يتم تقييم المقاولين؟',
                    'answer' => "يعتمد نظام التقييم على عدة عوامل:\n• تقييمات العملاء\n• جودة تنفيذ المشاريع\n• سرعة الاستجابة\n• الالتزام بالمواعيد",
                ],
            ],
            'title_field' => '{{{ question }}}',
        ]);

        $this->add_control('help_icon', [
            'label' => __('Help Box Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-circle-question', 'library' => 'fa-solid'],
        ]);
        $this->add_control('help_text', [
            'label' => __('Help Box Text', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('لم تجد إجابة لسؤالك؟ فريق الدعم متواجد على مدار الساعة لمساعدتك', 'bina'),
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items = $settings['items'] ?? [];
        ?>
        <section class="py-20 bg-background">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    </div>
                </div>
                <div class="max-w-3xl mx-auto">
                    <div class="grid gap-4">
                        <?php foreach ($items as $item): ?>
                            <?php $is_open = (($item['open_by_default'] ?? '') === 'yes'); ?>
                            <div class="" style="opacity: 1; transform: none;">
                                <div class="bg-card rounded-xl border border-border/50 overflow-hidden">
                                    <button class="w-full flex items-center gap-4 p-5 text-start hover:bg-muted/50 transition-colors">
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                            <span class="w-5 h-5 text-primary block">
                                                <?php if (!empty($item['icon'])) { Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5'], 'span'); } ?>
                                            </span>
                                        </div>
                                        <span class="flex-grow font-semibold text-secondary"><?php echo esc_html($item['question'] ?? ''); ?></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide <?php echo $is_open ? 'lucide-chevron-up' : 'lucide-chevron-down'; ?> w-5 h-5 text-muted-foreground shrink-0">
                                            <?php if ($is_open): ?>
                                                <path d="m18 15-6-6-6 6"></path>
                                            <?php else: ?>
                                                <path d="m6 9 6 6 6-6"></path>
                                            <?php endif; ?>
                                        </svg>
                                    </button>
                                    <div class="overflow-hidden" style="height: <?php echo $is_open ? 'auto' : '0px'; ?>; opacity: <?php echo $is_open ? '1' : '0'; ?>;">
                                        <div class="px-5 pb-5 pt-0 text-muted-foreground border-t border-border/50">
                                            <p class="pt-4 whitespace-pre-line"><?php echo esc_html($item['answer'] ?? ''); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="" style="opacity: 1; transform: none;">
                        <div class="mt-8 text-center p-6 bg-muted/50 rounded-2xl">
                            <span class="w-10 h-10 text-primary mx-auto mb-3 block">
                                <?php if (!empty($settings['help_icon'])) { Icons_Manager::render_icon($settings['help_icon'], ['aria-hidden' => 'true', 'class' => 'w-10 h-10'], 'span'); } ?>
                            </span>
                            <p class="text-muted-foreground"><?php echo esc_html($settings['help_text'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

