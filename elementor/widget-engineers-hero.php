<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

class bina_Engineers_Hero_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_engineers_hero';
    }

    public function get_title() {
        return __('Engineers Hero (Static)', 'bina');
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

        $this->add_control('float_icon_1', [
            'label' => __('Floating Icon 1 (Top Left)', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-hard-hat', 'library' => 'fa-solid'],
        ]);
        $this->add_control('float_icon_2', [
            'label' => __('Floating Icon 2 (Top Right)', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-building', 'library' => 'fa-solid'],
        ]);
        $this->add_control('float_icon_3', [
            'label' => __('Floating Icon 3 (Bottom Left)', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-clipboard-check', 'library' => 'fa-solid'],
        ]);
        $this->add_control('float_icon_4', [
            'label' => __('Floating Icon 4 (Bottom Right)', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-award', 'library' => 'fa-solid'],
        ]);
        $this->add_control('float_icon_5', [
            'label' => __('Floating Icon 5 (Top Center)', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-network-wired', 'library' => 'fa-solid'],
        ]);
        $this->add_control('float_icon_6', [
            'label' => __('Floating Icon 6 (Left Side)', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-briefcase', 'library' => 'fa-solid'],
        ]);
        $this->add_control('float_icon_7', [
            'label' => __('Floating Icon 7 (Right Side)', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-dollar-sign', 'library' => 'fa-solid'],
        ]);

        $this->add_control('heading_line_1', [
            'label' => __('Heading Line 1', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('انضم إلى شبكة مهندسي', 'bina'),
        ]);
        $this->add_control('heading_line_2', [
            'label' => __('Heading Line 2', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('بناء', 'bina'),
        ]);
        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('ابدأ بتحقيق دخل إضافي من مشاريع حقيقية في مدينتك — دون أن تبحث أنت عن العميل', 'bina'),
        ]);

        $this->add_control('primary_text', [
            'label' => __('Primary Button Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('سجّل الآن كمهندس شريك', 'bina'),
        ]);
        $this->add_control('primary_url', [
            'label' => __('Primary Button URL', 'bina'),
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);
        $this->add_control('primary_icon', [
            'label' => __('Primary Button Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-arrow-left', 'library' => 'fa-solid'],
        ]);

        $this->add_control('secondary_text', [
            'label' => __('Secondary Button Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('اعرف أكثر', 'bina'),
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
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(hsl(var(--border)) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--border)) 1px, transparent 1px); background-size: 60px 60px;"></div>

            <div class="absolute top-24 left-[8%] text-primary/15 hidden md:block" style="transform: translateY(-12.183px) rotate(8.12201deg);">
                <?php if (!empty($s['float_icon_1'])) { Icons_Manager::render_icon($s['float_icon_1'], ['aria-hidden' => 'true', 'class' => 'w-16 h-16']); } ?>
            </div>
            <div class="absolute top-32 right-[10%] text-accent/20 hidden md:block" style="transform: translateY(0.163937px) rotate(-0.109291deg);">
                <?php if (!empty($s['float_icon_2'])) { Icons_Manager::render_icon($s['float_icon_2'], ['aria-hidden' => 'true', 'class' => 'w-14 h-14']); } ?>
            </div>
            <div class="absolute bottom-20 left-[15%] text-primary/10 hidden md:block" style="transform: translateX(5.87453px) translateY(-7.34316px);">
                <?php if (!empty($s['float_icon_3'])) { Icons_Manager::render_icon($s['float_icon_3'], ['aria-hidden' => 'true', 'class' => 'w-12 h-12']); } ?>
            </div>
            <div class="absolute bottom-28 right-[12%] text-accent/15 hidden md:block" style="transform: translateY(0.243472px) rotate(0.365208deg);">
                <?php if (!empty($s['float_icon_4'])) { Icons_Manager::render_icon($s['float_icon_4'], ['aria-hidden' => 'true', 'class' => 'w-14 h-14']); } ?>
            </div>
            <div class="absolute top-16 left-[40%] text-primary/10" style="transform: scale(1.12183);">
                <?php if (!empty($s['float_icon_5'])) { Icons_Manager::render_icon($s['float_icon_5'], ['aria-hidden' => 'true', 'class' => 'w-10 h-10 md:w-20 md:h-20']); } ?>
            </div>
            <div class="absolute top-40 left-[5%] text-primary/10" style="transform: translateY(-2.46281px) rotate(-3.69422deg);">
                <?php if (!empty($s['float_icon_6'])) { Icons_Manager::render_icon($s['float_icon_6'], ['aria-hidden' => 'true', 'class' => 'w-8 h-8 md:w-12 md:h-12']); } ?>
            </div>
            <div class="absolute bottom-16 right-[5%] text-accent/10" style="transform: translateX(-4.35055px) translateY(10.1513px);">
                <?php if (!empty($s['float_icon_7'])) { Icons_Manager::render_icon($s['float_icon_7'], ['aria-hidden' => 'true', 'class' => 'w-8 h-8 md:w-12 md:h-12']); } ?>
            </div>

            <div class="absolute top-20 left-10 w-64 h-64 bg-accent/10 rounded-full blur-3xl" style="transform: scale(1.14155);"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-primary/10 rounded-full blur-3xl" style="transform: scale(1.05599);"></div>

            <div class="container-custom relative z-10">
                <div class="text-center max-w-4xl mx-auto" style="opacity: 1; transform: none;">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-secondary mb-6">
                        <?php echo esc_html($s['heading_line_1'] ?? ''); ?>
                        <span class="block text-primary mt-2"><?php echo esc_html($s['heading_line_2'] ?? ''); ?></span>
                    </h1>
                    <p class="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto"><?php echo esc_html($s['description'] ?? ''); ?></p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url($primary_url); ?>">
                            <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg text-lg px-8 py-6 group">
                                <?php echo esc_html($s['primary_text'] ?? ''); ?>
                                <span class="w-5 h-5 group-hover:-translate-x-1 transition-transform block text-current">
                                    <?php if (!empty($s['primary_icon'])) { Icons_Manager::render_icon($s['primary_icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5'], 'span'); } ?>
                                </span>
                            </button>
                        </a>
                        <a href="<?php echo esc_url($secondary_url); ?>">
                            <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background hover:text-accent-foreground h-11 rounded-md border-primary/30 text-primary hover:bg-primary/10 text-lg px-8 py-6">
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

