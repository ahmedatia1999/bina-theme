<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Engineers_Specialties_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_engineers_specialties';
    }

    public function get_title() {
        return __('Engineers Specialties (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-post-list';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Specialties Content', 'bina'),
        ]);

        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('من يمكنه الانضمام؟', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('التخصصات المطلوبة', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label' => __('Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-hard-hat', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مهندسو مدني', 'bina'),
        ]);
        $repeater->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('خبرة في الإنشاءات والأساسات والهياكل', 'bina'),
        ]);

        $this->add_control('items', [
            'label' => __('Specialties', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'icon' => ['value' => 'fas fa-hard-hat', 'library' => 'fa-solid'],
                    'title' => 'مهندسو مدني',
                    'description' => 'خبرة في الإنشاءات والأساسات والهياكل',
                ],
                [
                    'icon' => ['value' => 'fas fa-building', 'library' => 'fa-solid'],
                    'title' => 'مهندسو معماري',
                    'description' => 'تصميم وتخطيط المباني والمشاريع السكنية',
                ],
                [
                    'icon' => ['value' => 'fas fa-clipboard-check', 'library' => 'fa-solid'],
                    'title' => 'مهندسو إشراف',
                    'description' => 'إشراف على التنفيذ وضمان مطابقة المواصفات',
                ],
                [
                    'icon' => ['value' => 'fas fa-briefcase', 'library' => 'fa-solid'],
                    'title' => 'مكاتب هندسية',
                    'description' => 'مكاتب استشارية وهندسية مرخصة',
                ],
                [
                    'icon' => ['value' => 'fas fa-user-check', 'library' => 'fa-solid'],
                    'title' => 'مهندسو إدارة مشاريع',
                    'description' => 'تخطيط وإدارة ومتابعة المشاريع حتى التسليم',
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

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <?php foreach ($items as $item): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 text-center h-full" style="transform: none;">
                                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                                    <span class="w-8 h-8 text-primary block">
                                        <?php
                                        if (!empty($item['icon'])) {
                                            Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => 'w-8 h-8']);
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-secondary mb-3"><?php echo esc_html($item['title'] ?? ''); ?></h3>
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

