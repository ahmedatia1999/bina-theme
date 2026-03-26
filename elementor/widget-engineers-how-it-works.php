<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Engineers_How_It_Works_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_engineers_how_it_works';
    }

    public function get_title() {
        return __('Engineers How It Works (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-time-line';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('How It Works Content', 'bina'),
        ]);

        $this->add_control('section_id', [
            'label' => __('Section ID', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => 'how-it-works',
        ]);

        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('7 خطوات بسيطة', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('كيف تعمل الشراكة؟', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('step_number', [
            'label' => __('Step Number', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => '1',
        ]);
        $repeater->add_control('icon', [
            'label' => __('Step Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-paper-plane', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('المشروع يصل إلينا', 'bina'),
        ]);
        $repeater->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('العميل يطرح مشروعه على المنصة (بناء جديد، ترميم، تشطيب)', 'bina'),
        ]);

        $this->add_control('steps', [
            'label' => __('Steps', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'step_number' => '1',
                    'icon' => ['value' => 'fas fa-paper-plane', 'library' => 'fa-solid'],
                    'title' => 'المشروع يصل إلينا',
                    'description' => 'العميل يطرح مشروعه على المنصة (بناء جديد، ترميم، تشطيب)',
                ],
                [
                    'step_number' => '2',
                    'icon' => ['value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid'],
                    'title' => 'إرساله للمهندسين',
                    'description' => 'يتم إرسال المشروع للمهندسين حسب المدينة والتخصص تلقائياً',
                ],
                [
                    'step_number' => '3',
                    'icon' => ['value' => 'fas fa-bolt', 'library' => 'fa-solid'],
                    'title' => 'أولوية التقديم',
                    'description' => 'لديك أولوية في التقديم على المشروع قبل غيرك خلال فترة محددة',
                ],
                [
                    'step_number' => '4',
                    'icon' => ['value' => 'fas fa-clipboard-check', 'library' => 'fa-solid'],
                    'title' => 'مراجعة التفاصيل مع العميل',
                    'description' => 'تتواصل مع العميل لتأكيد نطاق العمل والميزانية والمواصفات الفنية',
                ],
                [
                    'step_number' => '5',
                    'icon' => ['value' => 'fas fa-users', 'library' => 'fa-solid'],
                    'title' => 'المقاولون يتواصلون معك',
                    'description' => 'المقاولون المعتمدون يقدمون عروضهم لك بناءً على تفاصيل المشروع',
                ],
                [
                    'step_number' => '6',
                    'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                    'title' => 'اختيار أفضل عرض',
                    'description' => 'تختار العرض الأنسب من حيث السعر والجودة والجدول الزمني',
                ],
                [
                    'step_number' => '7',
                    'icon' => ['value' => 'fas fa-handshake', 'library' => 'fa-solid'],
                    'title' => 'إغلاق الصفقة',
                    'description' => 'تربط المقاول بالعميل وتُغلق الصفقة باحترافية — وتحصل على عمولتك',
                ],
            ],
            'title_field' => '{{{ step_number }}} - {{{ title }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $steps = $s['steps'] ?? [];
        $section_id = !empty($s['section_id']) ? $s['section_id'] : 'how-it-works';
        ?>
        <section id="<?php echo esc_attr($section_id); ?>" class="py-20 bg-background">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge_text'] ?? ''); ?></span>
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($s['heading'] ?? ''); ?></h2>
                    </div>
                </div>
                <div class="max-w-4xl mx-auto">
                    <div class="relative">
                        <div class="absolute top-0 bottom-0 start-6 w-0.5 bg-primary/20 hidden md:block"></div>
                        <div class="space-y-6">
                            <?php foreach ($steps as $step): ?>
                                <div class="" style="opacity: 1; transform: none;">
                                    <div class="relative flex items-start gap-4 md:gap-6" style="transform: none;">
                                        <div class="relative z-10 w-12 h-12 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-bold text-lg shrink-0 shadow-lg">
                                            <?php echo esc_html($step['step_number'] ?? ''); ?>
                                        </div>
                                        <div class="bg-card rounded-2xl p-5 border border-border/50 shadow-card flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                                    <span class="w-5 h-5 text-primary block">
                                                        <?php
                                                        if (!empty($step['icon'])) {
                                                            Icons_Manager::render_icon($step['icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5']);
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                                <h3 class="text-lg font-bold text-secondary"><?php echo esc_html($step['title'] ?? ''); ?></h3>
                                            </div>
                                            <p class="text-muted-foreground text-sm"><?php echo esc_html($step['description'] ?? ''); ?></p>
                                        </div>
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

