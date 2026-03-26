<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Brokers_Commission_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_brokers_commission';
    }

    public function get_title() {
        return __('Brokers Commission (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Commission Section Content', 'bina'),
        ]);

        $this->add_control('badge_icon', [
            'label' => __('Badge Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-percent', 'library' => 'fa-solid'],
        ]);
        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('عمولتك', 'bina'),
        ]);
        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('اربح مع كل مشروع', 'bina'),
        ]);

        $this->add_control('rate_one_value', [
            'label' => __('Rate One Value', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('1%', 'bina'),
        ]);
        $this->add_control('rate_one_label', [
            'label' => __('Rate One Label', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('من قيمة المشروع', 'bina'),
        ]);
        $this->add_control('or_text', [
            'label' => __('OR Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('أو', 'bina'),
        ]);
        $this->add_control('rate_two_value', [
            'label' => __('Rate Two Value', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('30%', 'bina'),
        ]);
        $this->add_control('rate_two_label', [
            'label' => __('Rate Two Label', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('حتى من مبيعات بناء', 'bina'),
        ]);

        $this->add_control('example_label', [
            'label' => __('Example Label', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مثال:', 'bina'),
        ]);
        $this->add_control('example_value', [
            'label' => __('Example Value', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مشروع بقيمة 500,000 ريال = 5,000 ريال عمولة', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label' => __('Feature Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-wallet', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('title', [
            'label' => __('Feature Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('صرف مضمون', 'bina'),
        ]);
        $repeater->add_control('description', [
            'label' => __('Feature Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('تحصل على عمولتك بشكل مضمون بعد إتمام المشروع', 'bina'),
        ]);

        $this->add_control('features', [
            'label' => __('Features', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'icon' => ['value' => 'fas fa-wallet', 'library' => 'fa-solid'],
                    'title' => 'صرف مضمون',
                    'description' => 'تحصل على عمولتك بشكل مضمون بعد إتمام المشروع',
                ],
                [
                    'icon' => ['value' => 'fas fa-award', 'library' => 'fa-solid'],
                    'title' => 'بدون جهد إضافي',
                    'description' => 'لا تحتاج للمتابعة، نحن نتولى كل شيء',
                ],
                [
                    'icon' => ['value' => 'fas fa-chart-line', 'library' => 'fa-solid'],
                    'title' => 'أرباح تلقائية',
                    'description' => 'كلما زادت الإحالات، زادت أرباحك تلقائياً',
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $features = $s['features'] ?? [];
        ?>
        <section class="py-20 bg-gradient-to-br from-primary/5 via-background to-accent/5">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4">
                            <span class="w-4 h-4 inline-block mr-2 text-primary">
                                <?php
                                if (!empty($s['badge_icon'])) {
                                    Icons_Manager::render_icon($s['badge_icon'], ['aria-hidden' => 'true', 'class' => 'w-4 h-4']);
                                }
                                ?>
                            </span>
                            <?php echo esc_html($s['badge_text'] ?? ''); ?>
                        </span>
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($s['heading'] ?? ''); ?></h2>
                    </div>
                </div>
                <div class="max-w-4xl mx-auto">
                    <div class="" style="opacity: 1; transform: none;">
                        <div class="bg-card rounded-3xl p-8 lg:p-12 shadow-card border border-border/50 text-center mb-8">
                            <div class="flex flex-col md:flex-row items-center justify-center gap-6 mb-8">
                                <div class="text-center">
                                    <div class="text-5xl md:text-6xl font-bold text-primary mb-2" style="transform: scale(1.00539);"><?php echo esc_html($s['rate_one_value'] ?? ''); ?></div>
                                    <p class="text-muted-foreground"><?php echo esc_html($s['rate_one_label'] ?? ''); ?></p>
                                </div>
                                <div class="text-3xl text-muted-foreground font-light"><?php echo esc_html($s['or_text'] ?? ''); ?></div>
                                <div class="text-center">
                                    <div class="text-5xl md:text-6xl font-bold text-accent mb-2" style="transform: scale(1.01238);"><?php echo esc_html($s['rate_two_value'] ?? ''); ?></div>
                                    <p class="text-muted-foreground"><?php echo esc_html($s['rate_two_label'] ?? ''); ?></p>
                                </div>
                            </div>
                            <div class="bg-muted/50 rounded-2xl p-6 mb-8">
                                <p class="text-lg text-muted-foreground mb-2"><?php echo esc_html($s['example_label'] ?? ''); ?></p>
                                <p class="text-2xl font-bold text-secondary"><?php echo esc_html($s['example_value'] ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-6">
                        <?php foreach ($features as $feature): ?>
                            <div class="" style="opacity: 1; transform: none;">
                                <div class="bg-card rounded-xl p-5 border border-border/50 flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <span class="w-5 h-5 text-primary block">
                                            <?php
                                            if (!empty($feature['icon'])) {
                                                Icons_Manager::render_icon($feature['icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5']);
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-secondary mb-1"><?php echo esc_html($feature['title'] ?? ''); ?></h4>
                                        <p class="text-sm text-muted-foreground"><?php echo esc_html($feature['description'] ?? ''); ?></p>
                                    </div>
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

