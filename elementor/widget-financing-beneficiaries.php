<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Financing_Beneficiaries_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_financing_beneficiaries';
    }

    public function get_title() {
        return __('Financing - Beneficiaries (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Beneficiaries Content', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('من يمكنه الاستفادة من الخدمة؟', 'bina'),
            ]
        );

        $item = new Repeater();

        $item->add_control(
            'bar_gradient_from',
            [
                'label' => __('Bar Gradient From Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'from-blue-500',
            ]
        );

        $item->add_control(
            'bar_gradient_to',
            [
                'label' => __('Bar Gradient To Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'to-cyan-600',
            ]
        );

        $item->add_control(
            'icon_gradient_from',
            [
                'label' => __('Icon Gradient From Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'from-blue-500',
            ]
        );

        $item->add_control(
            'icon_gradient_to',
            [
                'label' => __('Icon Gradient To Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'to-cyan-600',
            ]
        );

        $item->add_control(
            'icon',
            [
                'label' => __('Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $item->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('الأفراد', 'bina'),
            ]
        );

        $item->add_control(
            'text',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('لترميم منزل، بناء جديد، أو تشطيب داخلي', 'bina'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => __('Beneficiary Cards', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $item->get_controls(),
                'default' => [
                    [
                        'bar_gradient_from' => 'from-blue-500',
                        'bar_gradient_to' => 'to-cyan-600',
                        'icon_gradient_from' => 'from-blue-500',
                        'icon_gradient_to' => 'to-cyan-600',
                        'icon' => ['value' => 'fas fa-user', 'library' => 'fa-solid'],
                        'title' => 'الأفراد',
                        'text' => 'لترميم منزل، بناء جديد، أو تشطيب داخلي',
                    ],
                    [
                        'bar_gradient_from' => 'from-amber-500',
                        'bar_gradient_to' => 'to-yellow-600',
                        'icon_gradient_from' => 'from-amber-500',
                        'icon_gradient_to' => 'to-yellow-600',
                        'icon' => ['value' => 'fas fa-briefcase', 'library' => 'fa-solid'],
                        'title' => 'المكاتب والمقاولون',
                        'text' => 'لتمويل المشاريع الجارية أو القادمة',
                    ],
                    [
                        'bar_gradient_from' => 'from-emerald-500',
                        'bar_gradient_to' => 'to-green-600',
                        'icon_gradient_from' => 'from-emerald-500',
                        'icon_gradient_to' => 'to-green-600',
                        'icon' => ['value' => 'fas fa-building', 'library' => 'fa-solid'],
                        'title' => 'الشركات',
                        'text' => 'لدعم المشاريع العقارية والتوسعية',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items = $settings['items'] ?? [];
        ?>

        <section class="section-padding bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <?php foreach ($items as $item): ?>
                        <?php
                        $bar_gradient = trim(($item['bar_gradient_from'] ?? '') . ' ' . ($item['bar_gradient_to'] ?? ''));
                        $icon_gradient = trim(($item['icon_gradient_from'] ?? '') . ' ' . ($item['icon_gradient_to'] ?? ''));
                        ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="relative overflow-hidden rounded-2xl bg-background border border-border shadow-lg p-8">
                                <div class="absolute top-0 right-0 w-2 h-full bg-gradient-to-b <?php echo esc_attr($bar_gradient); ?>">
                                </div>
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br <?php echo esc_attr($icon_gradient); ?> flex items-center justify-center mb-6 shadow-lg">
                                    <span class="w-8 h-8 text-white block">
                                        <?php
                                        if (!empty($item['icon'])) {
                                            Icons_Manager::render_icon(
                                                $item['icon'],
                                                ['aria-hidden' => 'true', 'class' => 'w-8 h-8'],
                                                'span'
                                            );
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold mb-3 text-foreground"><?php echo esc_html($item['title'] ?? ''); ?></h3>
                                <p class="text-muted-foreground"><?php echo esc_html($item['text'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

