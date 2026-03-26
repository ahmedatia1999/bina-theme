<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

class bina_Brokers_Hero_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_brokers_hero';
    }

    public function get_title() {
        return __('Brokers Hero (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-banner';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Hero Content', 'bina'),
        ]);

        $this->add_control('badge_icon', [
            'label' => __('Badge Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-percent',
                'library' => 'fa-solid',
            ],
        ]);
        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('برنامج الوسطاء', 'bina'),
        ]);
        $this->add_control('heading_line_1', [
            'label' => __('Heading Line 1', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('ضاعف دخلك كوسيط عقاري', 'bina'),
        ]);
        $this->add_control('heading_line_2', [
            'label' => __('Heading Line 2', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مع بناء', 'bina'),
        ]);
        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('لا تكتفِ ببيع العقار… اربح أيضًا من بنائه أو ترميمه!', 'bina'),
        ]);

        $this->add_control('primary_text', [
            'label' => __('Primary Button Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('سجّل الآن مجانًا', 'bina'),
        ]);
        $this->add_control('primary_url', [
            'label' => __('Primary Button URL', 'bina'),
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);
        $this->add_control('primary_icon', [
            'label' => __('Primary Button Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-arrow-left',
                'library' => 'fa-solid',
            ],
        ]);

        $this->add_control('secondary_text', [
            'label' => __('Secondary Button Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('كيف يعمل البرنامج؟', 'bina'),
        ]);
        $this->add_control('secondary_url', [
            'label' => __('Secondary Button URL', 'bina'),
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $primary_url = $s['primary_url']['url'] ?? '#';
        $secondary_url = $s['secondary_url']['url'] ?? '#';
        ?>

        <section class="pt-20 md:pt-24 pb-16 bg-background relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.03]"
                style="background-image: linear-gradient(hsl(var(--border)) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--border)) 1px, transparent 1px); background-size: 60px 60px;">
            </div>
            <div class="absolute top-20 left-10 w-64 h-64 bg-primary/10 rounded-full blur-3xl"
                style="transform: scale(1.02914);"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent/10 rounded-full blur-3xl"
                style="transform: scale(1.19455);"></div>
            <div class="container-custom relative z-10">
                <div class="text-center max-w-4xl mx-auto" style="opacity: 1; transform: none;">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-medium mb-6"
                        style="opacity: 1; transform: none;">
                        <span class="w-4 h-4 block text-primary">
                            <?php if (!empty($s['badge_icon'])) { Icons_Manager::render_icon($s['badge_icon'], ['aria-hidden' => 'true', 'class' => 'w-4 h-4'], 'span'); } ?>
                        </span>
                        <?php echo esc_html($s['badge_text'] ?? ''); ?>
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-secondary mb-6">
                        <?php echo esc_html($s['heading_line_1'] ?? ''); ?>
                        <span class="block text-primary mt-2"><?php echo esc_html($s['heading_line_2'] ?? ''); ?></span>
                    </h1>
                    <p class="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto"><?php echo esc_html($s['description'] ?? ''); ?></p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url($primary_url); ?>">
                            <button
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg text-lg px-8 py-6 group">
                                <?php echo esc_html($s['primary_text'] ?? ''); ?>
                                <span class="w-5 h-5 group-hover:-translate-x-1 transition-transform block text-current">
                                    <?php if (!empty($s['primary_icon'])) { Icons_Manager::render_icon($s['primary_icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5'], 'span'); } ?>
                                </span>
                            </button>
                        </a>
                        <a href="<?php echo esc_url($secondary_url); ?>">
                            <button
                                class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background hover:text-accent-foreground h-11 rounded-md border-primary/30 text-primary hover:bg-primary/10 text-lg px-8 py-6">
                                <?php echo esc_html($s['secondary_text'] ?? ''); ?>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

