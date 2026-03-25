<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_How_We_Work_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_how_we_work';
    }

    public function get_title() {
        return __('How We Work (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-check-circle';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('How We Work Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('كيف نعمل', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('كيف يعمل بناء', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('نوفر لك تجربة سهلة وموثقة للبناء الجديد أو الترميم', 'bina'),
            ]
        );

        $step = new Repeater();

        $step->add_control(
            'number',
            [
                'label' => __('Step Number', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '1',
            ]
        );

        $step->add_control(
            'icon',
            [
                'label' => __('Step Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-file-alt',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $step->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('حدد مشروعك', 'bina'),
            ]
        );

        $step->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('اختر نوع الخدمة التي تحتاجها وأضف تفاصيل مشروعك مع الصور والميزانية المتوقعة.', 'bina'),
            ]
        );

        // Feature items (3) — no nested repeater to keep compatibility.
        $step->add_control(
            'feature1_icon',
            [
                'label' => __('Feature 1 Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-comment-dots',
                    'library' => 'fa-regular',
                ],
            ]
        );
        $step->add_control(
            'feature1_text',
            [
                'label' => __('Feature 1 Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('وصف تفصيلي للمشروع', 'bina'),
            ]
        );

        $step->add_control(
            'feature2_icon',
            [
                'label' => __('Feature 2 Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-dollar-sign',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $step->add_control(
            'feature2_text',
            [
                'label' => __('Feature 2 Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('تحديد الميزانية', 'bina'),
            ]
        );

        $step->add_control(
            'feature3_icon',
            [
                'label' => __('Feature 3 Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-image',
                    'library' => 'fa-regular',
                ],
            ]
        );
        $step->add_control(
            'feature3_text',
            [
                'label' => __('Feature 3 Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('إرفاق الصور', 'bina'),
            ]
        );

        $this->add_control(
            'steps',
            [
                'label' => __('Steps', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $step->get_controls(),
                'default' => [
                    [
                        'number' => '1',
                        'title' => 'حدد مشروعك',
                        'text' => 'اختر نوع الخدمة التي تحتاجها وأضف تفاصيل مشروعك مع الصور والميزانية المتوقعة.',
                        'feature1_text' => 'وصف تفصيلي للمشروع',
                        'feature2_text' => 'تحديد الميزانية',
                        'feature3_text' => 'إرفاق الصور',
                    ],
                    [
                        'number' => '2',
                        'icon' => [ 'value' => 'fas fa-users', 'library' => 'fa-solid' ],
                        'title' => 'تلقى العروض',
                        'text' => 'سيقوم المقاولون المؤهلون بتقديم عروضهم التنافسية لمشروعك بكل احترافية.',
                        'feature1_text' => 'عروض تنافسية',
                        'feature2_icon' => [ 'value' => 'far fa-building', 'library' => 'fa-regular' ],
                        'feature2_text' => 'شركات مقاولات',
                        'feature3_icon' => [ 'value' => 'fas fa-balance-scale', 'library' => 'fa-solid' ],
                        'feature3_text' => 'مقارنة سهلة',
                    ],
                    [
                        'number' => '3',
                        'icon' => [ 'value' => 'far fa-user-check', 'library' => 'fa-regular' ],
                        'title' => 'اختر المقاول',
                        'text' => 'راجع العروض والتقييمات السابقة واختر المقاول المناسب لاحتياجاتك.',
                        'feature1_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ],
                        'feature1_text' => 'مراجعة التقييمات',
                        'feature2_icon' => [ 'value' => 'far fa-folder-open', 'library' => 'fa-regular' ],
                        'feature2_text' => 'مشاريع سابقة',
                        'feature3_icon' => [ 'value' => 'far fa-check-circle', 'library' => 'fa-regular' ],
                        'feature3_text' => 'اختيار الأنسب',
                    ],
                    [
                        'number' => '4',
                        'icon' => [ 'value' => 'fas fa-rocket', 'library' => 'fa-solid' ],
                        'title' => 'تنفيذ المشروع',
                        'text' => 'تابع تنفيذ المشروع خطوة بخطوة مع تقييمات للمقاولين ودعم فني متكامل.',
                        'feature1_icon' => [ 'value' => 'fas fa-book', 'library' => 'fa-solid' ],
                        'feature1_text' => 'دليل خدمات الإنشاءات',
                        'feature2_icon' => [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ],
                        'feature2_text' => 'تقييمات للمقاولين',
                        'feature3_icon' => [ 'value' => 'fas fa-headphones', 'library' => 'fa-solid' ],
                        'feature3_text' => 'دعم فني متكامل',
                    ],
                ],
                'title_field' => '{{{ number }}} - {{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function render_feature_row($icon, $text) {
        if (!$text) return;
        ?>
        <div class="flex items-center gap-2 text-xs sm:text-sm bg-muted/50 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2">
            <span class="w-3 h-3 sm:w-4 sm:h-4 text-primary shrink-0 inline-flex items-center justify-center">
                <?php if (!empty($icon['value'])) Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
            </span><span class="text-muted-foreground"><?php echo esc_html($text); ?></span>
        </div>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $steps = $settings['steps'] ?? [];
        ?>

                <section class="py-12 md:py-20 bg-muted/30 content-visibility-auto">
                    <div class="container-custom">
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="text-center mb-8 md:mb-16 px-4"><span
                                    class="inline-block px-3 py-1 sm:px-4 sm:py-1.5 bg-primary/10 text-primary rounded-full text-xs sm:text-sm font-medium mb-3 md:mb-4"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                                <h2
                                    class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-secondary mb-3 md:mb-4">
                                    <?php echo esc_html($settings['heading'] ?? ''); ?> </h2>
                                <p class="text-sm sm:text-base md:text-lg text-muted-foreground max-w-2xl mx-auto px-2">
                                    <?php echo esc_html($settings['description'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 px-2">
                            <?php foreach ($steps as $idx => $step): ?>
                                <div class="" style="opacity: 1; transform: none;">
                                    <div class="bg-card rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 shadow-card border border-border/50 h-full relative group"
                                        style="transform: none;">
                                        <div
                                            class="absolute -top-2 sm:-top-4 right-3 sm:right-6 w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs sm:text-sm md:text-lg shadow-lg">
                                            <?php echo esc_html($step['number'] ?? ''); ?></div>
                                        <div
                                            class="w-10 h-10 sm:w-12 sm:h-12 md:w-16 md:h-16 rounded-xl sm:rounded-2xl bg-primary/10 flex items-center justify-center mb-3 sm:mb-5 group-hover:bg-primary group-hover:scale-110 transition-all duration-300 mx-auto md:mx-0">
                                            <span class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8 text-primary group-hover:text-white transition-colors inline-flex items-center justify-center">
                                                <?php if (!empty($step['icon']['value'])) Icons_Manager::render_icon($step['icon'], ['aria-hidden' => 'true']); ?>
                                            </span>
                                        </div>
                                        <h3
                                            class="text-sm sm:text-base md:text-xl font-bold text-secondary mb-2 md:mb-3 text-center md:text-start">
                                            <?php echo esc_html($step['title'] ?? ''); ?></h3>
                                        <p
                                            class="text-muted-foreground text-xs sm:text-sm mb-3 md:mb-5 leading-relaxed text-center md:text-start hidden sm:block">
                                            <?php echo esc_html($step['text'] ?? ''); ?>
                                        </p>
                                        <div class="space-y-1 sm:space-y-2 hidden md:block">
                                            <?php $this->render_feature_row($step['feature1_icon'] ?? [], $step['feature1_text'] ?? ''); ?>
                                            <?php $this->render_feature_row($step['feature2_icon'] ?? [], $step['feature2_text'] ?? ''); ?>
                                            <?php $this->render_feature_row($step['feature3_icon'] ?? [], $step['feature3_text'] ?? ''); ?>
                                        </div>
                                        <?php if ($idx < (count($steps) - 1)): ?>
                                            <div class="hidden lg:block absolute top-1/2 -right-3 w-6 h-0.5 bg-border"></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

        <?php
    }
}

