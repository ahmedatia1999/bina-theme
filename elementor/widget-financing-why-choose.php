<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Financing_Why_Choose_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_financing_why_choose';
    }

    public function get_title() {
        return __('Financing - Why Choose (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-favorite';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Why Choose Content', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('لماذا تختار التمويل عبر بناء', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('كل ما تحتاجه في خطوة واحدة', 'bina'),
            ]
        );

        $item = new Repeater();

        $item->add_control(
            'gradient_from',
            [
                'label' => __('Gradient From Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'from-blue-500',
            ]
        );

        $item->add_control(
            'gradient_to',
            [
                'label' => __('Gradient To Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'to-blue-600',
            ]
        );

        $item->add_control(
            'icon',
            [
                'label' => __('Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-bullseye',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $item->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('سهولة الوصول', 'bina'),
            ]
        );

        $item->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('عروض متنوعة من جهات مرخّصة', 'bina'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => __('Items', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $item->get_controls(),
                'default' => [
                    [
                        'gradient_from' => 'from-blue-500',
                        'gradient_to' => 'to-blue-600',
                        'icon' => ['value' => 'fas fa-bullseye', 'library' => 'fa-solid'],
                        'title' => 'سهولة الوصول',
                        'text' => 'عروض متنوعة من جهات مرخّصة',
                    ],
                    [
                        'gradient_from' => 'from-amber-500',
                        'gradient_to' => 'to-orange-600',
                        'icon' => ['value' => 'fas fa-bolt', 'library' => 'fa-solid'],
                        'title' => 'توجيه ذكي',
                        'text' => 'حسب نوع المشروع والمبلغ',
                    ],
                    [
                        'gradient_from' => 'from-green-500',
                        'gradient_to' => 'to-emerald-600',
                        'icon' => ['value' => 'fas fa-money-bill-wave', 'library' => 'fa-solid'],
                        'title' => 'سرعة الإجراءات',
                        'text' => 'بدون تنقل بين الجهات',
                    ],
                    [
                        'gradient_from' => 'from-purple-500',
                        'gradient_to' => 'to-violet-600',
                        'icon' => ['value' => 'fas fa-shield-halved', 'library' => 'fa-solid'],
                        'title' => 'خصوصية تامة',
                        'text' => 'بياناتك في أمان',
                    ],
                    [
                        'gradient_from' => 'from-pink-500',
                        'gradient_to' => 'to-rose-600',
                        'icon' => ['value' => 'fas fa-headset', 'library' => 'fa-solid'],
                        'title' => 'دعم مستمر',
                        'text' => 'فريق متخصص لمساعدتك',
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

        <section class="section-padding">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-lg text-muted-foreground"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 lg:grid-cols-5 gap-6">
                    <?php foreach ($items as $item): ?>
                        <?php $gradient = trim(($item['gradient_from'] ?? '') . ' ' . ($item['gradient_to'] ?? '')); ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="text-center p-6 rounded-2xl bg-background border border-border shadow-sm h-full">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br <?php echo esc_attr($gradient); ?> flex items-center justify-center mx-auto mb-4 shadow-lg">
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
                                <h3 class="text-lg font-bold mb-2 text-foreground"><?php echo esc_html($item['title'] ?? ''); ?></h3>
                                <p class="text-sm text-muted-foreground"><?php echo esc_html($item['text'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

