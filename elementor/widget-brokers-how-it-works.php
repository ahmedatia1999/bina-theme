<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Brokers_How_It_Works_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_brokers_how_it_works';
    }

    public function get_title() {
        return __('Brokers How It Works (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-number-field';
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
            'default' => __('4 خطوات بسيطة', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('كيف يعمل البرنامج؟', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('step_number', [
            'label' => __('Step Number', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => '1',
        ]);
        $repeater->add_control('icon', [
            'label' => __('Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-users', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('سجّل الآن', 'bina'),
        ]);
        $repeater->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('سجّل في برنامج الوسطاء واحصل على حسابك الخاص', 'bina'),
        ]);

        $this->add_control('steps', [
            'label' => __('Steps', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'step_number' => '1',
                    'icon' => ['value' => 'fas fa-users', 'library' => 'fa-solid'],
                    'title' => 'سجّل الآن',
                    'description' => 'سجّل في برنامج الوسطاء واحصل على حسابك الخاص',
                ],
                [
                    'step_number' => '2',
                    'icon' => ['value' => 'fas fa-link', 'library' => 'fa-solid'],
                    'title' => 'احصل على رابطك',
                    'description' => 'احصل على رابطك الخاص لمشاركته مع عملائك',
                ],
                [
                    'step_number' => '3',
                    'icon' => ['value' => 'fas fa-bullseye', 'library' => 'fa-solid'],
                    'title' => 'اقنع العميل',
                    'description' => 'اقنع المشتري أو المالك بتنفيذ مشروعه من خلال بناء',
                ],
                [
                    'step_number' => '4',
                    'icon' => ['value' => 'fas fa-chart-column', 'library' => 'fa-solid'],
                    'title' => 'تابع أرباحك',
                    'description' => 'تابع المشاريع وشاهد أرباحك تكبر مع كل صفقة',
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
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($steps as $step): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="relative bg-card rounded-2xl p-6 shadow-card border border-border/50 h-full" style="transform: none;">
                                <div class="absolute -top-3 -right-3 w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-bold text-lg shadow-lg">
                                    <?php echo esc_html($step['step_number'] ?? ''); ?>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-4 mt-2">
                                    <span class="w-7 h-7 text-primary block">
                                        <?php
                                        if (!empty($step['icon'])) {
                                            Icons_Manager::render_icon($step['icon'], ['aria-hidden' => 'true', 'class' => 'w-7 h-7']);
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-secondary mb-2"><?php echo esc_html($step['title'] ?? ''); ?></h3>
                                <p class="text-muted-foreground text-sm"><?php echo esc_html($step['description'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

