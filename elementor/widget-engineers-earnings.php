<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Engineers_Earnings_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_engineers_earnings';
    }

    public function get_title() {
        return __('Engineers Earnings (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Earnings Content', 'bina'),
        ]);

        $this->add_control('badge_icon', [
            'label' => __('Badge Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-percent', 'library' => 'fa-solid'],
        ]);
        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('أرباحك', 'bina'),
        ]);
        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('كم تربح كمهندس شريك؟', 'bina'),
        ]);

        $this->add_control('intro_text', [
            'label' => __('Intro Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('تحصل على:', 'bina'),
        ]);
        $this->add_control('left_value', [
            'label' => __('Left Value', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('30%', 'bina'),
        ]);
        $this->add_control('left_label', [
            'label' => __('Left Label', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('من عمولة بناء', 'bina'),
        ]);
        $this->add_control('middle_symbol', [
            'label' => __('Middle Symbol', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('≈', 'bina'),
        ]);
        $this->add_control('right_value', [
            'label' => __('Right Value', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('1%', 'bina'),
        ]);
        $this->add_control('right_label', [
            'label' => __('Right Label', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('من قيمة المشروع', 'bina'),
        ]);

        $this->add_control('example_title', [
            'label' => __('Example Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مثال عملي:', 'bina'),
        ]);
        $this->add_control('example_line_1', [
            'label' => __('Example Line 1', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مشروع بقيمة 1,000,000 ريال', 'bina'),
        ]);
        $this->add_control('example_line_2', [
            'label' => __('Example Line 2', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('عمولة المنصة 3%', 'bina'),
        ]);
        $this->add_control('example_line_3', [
            'label' => __('Example Line 3', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('نصيبك = 10,000 ريال', 'bina'),
        ]);

        $this->add_control('footnote', [
            'label' => __('Footnote', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('دخل إضافي بدون تسويق، بدون بحث عن عملاء، وبدون مخاطر تنفيذ', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('value', [
            'label' => __('Stat Value', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('30%', 'bina'),
        ]);
        $repeater->add_control('label', [
            'label' => __('Stat Label', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('نسبة العمولة', 'bina'),
        ]);

        $this->add_control('stats', [
            'label' => __('Stats', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['value' => '30%', 'label' => 'نسبة العمولة'],
                ['value' => '0', 'label' => 'رسوم اشتراك'],
                ['value' => '7', 'label' => 'أيام للسداد'],
                ['value' => '∞', 'label' => 'عدد المشاريع'],
            ],
            'title_field' => '{{{ value }}} - {{{ label }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $stats = $s['stats'] ?? [];
        ?>
        <section class="py-20 bg-gradient-to-br from-primary/5 via-background to-accent/5">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4">
                            <span class="w-4 h-4 inline-block ml-2 text-primary">
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
                        <div class="bg-card rounded-3xl p-8 lg:p-12 shadow-card border border-border/50 text-center mb-8" style="transform: none;">
                            <p class="text-lg text-muted-foreground mb-6"><?php echo esc_html($s['intro_text'] ?? ''); ?></p>
                            <div class="flex flex-col md:flex-row items-center justify-center gap-6 mb-8">
                                <div class="text-center">
                                    <div class="text-5xl md:text-6xl font-bold text-accent-foreground mb-2" style="transform: scale(1.01028);"><?php echo esc_html($s['left_value'] ?? ''); ?></div>
                                    <p class="text-muted-foreground"><?php echo esc_html($s['left_label'] ?? ''); ?></p>
                                </div>
                                <div class="text-3xl text-muted-foreground font-light"><?php echo esc_html($s['middle_symbol'] ?? ''); ?></div>
                                <div class="text-center">
                                    <div class="text-5xl md:text-6xl font-bold text-primary mb-2" style="transform: scale(1.04122);"><?php echo esc_html($s['right_value'] ?? ''); ?></div>
                                    <p class="text-muted-foreground"><?php echo esc_html($s['right_label'] ?? ''); ?></p>
                                </div>
                            </div>
                            <div class="bg-muted/50 rounded-2xl p-6 mb-6">
                                <p class="text-lg text-muted-foreground mb-2"><?php echo esc_html($s['example_title'] ?? ''); ?></p>
                                <div class="space-y-2">
                                    <p class="text-xl font-bold text-secondary"><?php echo esc_html($s['example_line_1'] ?? ''); ?></p>
                                    <p class="text-muted-foreground"><?php echo esc_html($s['example_line_2'] ?? ''); ?></p>
                                    <p class="text-2xl font-bold text-primary"><?php echo esc_html($s['example_line_3'] ?? ''); ?></p>
                                </div>
                            </div>
                            <p class="text-muted-foreground text-sm"><?php echo esc_html($s['footnote'] ?? ''); ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <?php foreach ($stats as $stat): ?>
                            <div class="" style="opacity: 1; transform: none;">
                                <div class="bg-card rounded-xl p-4 border border-border/50 text-center">
                                    <p class="text-2xl md:text-3xl font-bold text-primary mb-1"><?php echo esc_html($stat['value'] ?? ''); ?></p>
                                    <p class="text-xs text-muted-foreground"><?php echo esc_html($stat['label'] ?? ''); ?></p>
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

