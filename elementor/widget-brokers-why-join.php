<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Brokers_Why_Join_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_brokers_why_join';
    }

    public function get_title() {
        return __('Brokers Why Join (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-star';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Why Join Content', 'bina'),
        ]);

        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('المميزات', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('لماذا تنضم اليوم؟', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label' => __('Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-chart-line', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('ضاعف دخلك بسهولة', 'bina'),
        ]);
        $repeater->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('لا تكتفِ ببيع العقار، اربح أيضًا من بنائه أو ترميمه', 'bina'),
        ]);

        $this->add_control('items', [
            'label' => __('Feature Cards', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'icon' => ['value' => 'fas fa-chart-line', 'library' => 'fa-solid'],
                    'title' => 'ضاعف دخلك بسهولة',
                    'description' => 'لا تكتفِ ببيع العقار، اربح أيضًا من بنائه أو ترميمه',
                ],
                [
                    'icon' => ['value' => 'fas fa-wrench', 'library' => 'fa-solid'],
                    'title' => 'خدمة متكاملة',
                    'description' => 'نوفر لعملائك كل ما يحتاجونه من البناء للتشطيب',
                ],
                [
                    'icon' => ['value' => 'fas fa-table-columns', 'library' => 'fa-solid'],
                    'title' => 'لوحة تحكم ذكية',
                    'description' => 'تابع إحالاتك وأرباحك من لوحة تحكم سهلة الاستخدام',
                ],
                [
                    'icon' => ['value' => 'fas fa-file-circle-check', 'library' => 'fa-solid'],
                    'title' => 'عقود رسمية آمنة',
                    'description' => 'جميع التعاملات موثقة بعقود رسمية تحمي حقوقك',
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $items = $s['items'] ?? [];
        ?>
        <section class="py-20 bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge_text'] ?? ''); ?></span>
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($s['heading'] ?? ''); ?></h2>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($items as $item): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 h-full">
                                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                                    <span class="w-7 h-7 text-primary block">
                                        <?php
                                        if (!empty($item['icon'])) {
                                            Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => 'w-7 h-7']);
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-secondary mb-2"><?php echo esc_html($item['title'] ?? ''); ?></h3>
                                <p class="text-muted-foreground text-sm"><?php echo esc_html($item['description'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

