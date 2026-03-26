<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_How_We_Work_Contractor_Journey_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_how_we_work_contractor_journey';
    }

    public function get_title() {
        return __('How We Work - Contractor Journey (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-sitemap';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Contractor Journey Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('للمقاولين', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('رحلة المقاول', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('4 خطوات للانضمام والنمو معنا', 'bina'),
            ]
        );

        $step = new Repeater();

        $step->add_control(
            'number',
            [
                'label' => __('Step Number', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '01',
            ]
        );

        $step->add_control(
            'number_gradient_from',
            [
                'label' => __('Number Gradient From Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'from-indigo-500',
            ]
        );

        $step->add_control(
            'number_gradient_to',
            [
                'label' => __('Number Gradient To Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'to-indigo-600',
            ]
        );

        $step->add_control(
            'icon',
            [
                'label' => __('Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-user-plus',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $step->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('سجل كمقاول', 'bina'),
            ]
        );

        $step->add_control(
            'text',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('أنشئ حسابك وأضف بيانات شركتك وأعمالك السابقة', 'bina'),
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
                        'number' => '01',
                        'number_gradient_from' => 'from-indigo-500',
                        'number_gradient_to' => 'to-indigo-600',
                        'icon' => ['value' => 'fas fa-user-plus', 'library' => 'fa-solid'],
                        'title' => 'سجل كمقاول',
                        'text' => 'أنشئ حسابك وأضف بيانات شركتك وأعمالك السابقة',
                    ],
                    [
                        'number' => '02',
                        'number_gradient_from' => 'from-pink-500',
                        'number_gradient_to' => 'to-pink-600',
                        'icon' => ['value' => 'fas fa-briefcase', 'library' => 'fa-solid'],
                        'title' => 'تصفح المشاريع',
                        'text' => 'تصفح المشاريع المتاحة وقدم عروضك التنافسية',
                    ],
                    [
                        'number' => '03',
                        'number_gradient_from' => 'from-amber-500',
                        'number_gradient_to' => 'to-amber-600',
                        'icon' => ['value' => 'fas fa-award', 'library' => 'fa-solid'],
                        'title' => 'فز بالمشاريع',
                        'text' => 'عند قبول عرضك، ابدأ العمل وابني سمعتك',
                    ],
                    [
                        'number' => '04',
                        'number_gradient_from' => 'from-teal-500',
                        'number_gradient_to' => 'to-teal-600',
                        'icon' => ['value' => 'fas fa-chart-line', 'library' => 'fa-solid'],
                        'title' => 'نمو مستمر',
                        'text' => 'احصل على المزيد من المشاريع وطور عملك باستمرار',
                    ],
                ],
                'title_field' => '{{{ number }}} - {{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $steps = $settings['steps'] ?? [];
        ?>

        <section class="py-24 bg-background relative overflow-hidden">
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,hsl(var(--border))_1px,transparent_1px),linear-gradient(to_bottom,hsl(var(--border))_1px,transparent_1px)] bg-[size:6rem_6rem] opacity-50">
            </div>
            <div class="container-custom relative z-10">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="inline-block px-4 py-1.5 bg-secondary/50 text-secondary-foreground rounded-full text-sm font-medium mb-4">
                            <?php echo esc_html($settings['badge_text'] ?? ''); ?>
                        </span>
                        <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-muted-foreground text-lg"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    </div>
                </div>
                <div class="max-w-5xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                        <div
                            class="hidden lg:block absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-primary/20 via-primary to-primary/20 -translate-y-1/2 z-0">
                        </div>
                        <?php foreach ($steps as $step): ?>
                            <?php
                            $from = trim($step['number_gradient_from'] ?? '');
                            $to = trim($step['number_gradient_to'] ?? '');
                            $number_gradient = trim($from . ' ' . $to);
                            ?>
                            <div class="" style="opacity: 1; transform: none;">
                                <div class="relative bg-card rounded-2xl p-8 border border-border shadow-card hover:shadow-card-hover transition-all group text-center z-10"
                                    tabindex="0" style="transform: none;">
                                    <div class="w-20 h-20 rounded-full bg-gradient-to-br <?php echo esc_attr($number_gradient); ?> text-white text-2xl font-bold flex items-center justify-center mx-auto mb-6 shadow-lg"
                                        style="transform: none;"><?php echo esc_html($step['number'] ?? ''); ?></div>
                                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                                        <span class="w-7 h-7 text-primary">
                                            <?php
                                            if (!empty($step['icon'])) {
                                                Icons_Manager::render_icon(
                                                    $step['icon'],
                                                    ['aria-hidden' => 'true', 'class' => 'w-7 h-7'],
                                                    'span'
                                                );
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <h3 class="text-xl font-bold text-foreground mb-3"><?php echo esc_html($step['title'] ?? ''); ?></h3>
                                    <p class="text-muted-foreground leading-relaxed text-sm"><?php echo esc_html($step['text'] ?? ''); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

