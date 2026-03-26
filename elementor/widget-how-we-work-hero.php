<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

class bina_How_We_Work_Hero_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_how_we_work_hero';
    }

    public function get_title() {
        return __('How We Work Hero (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-heading';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Hero Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('رحلتك معنا', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('كيف نعمل', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXT,
                'rows' => 2,
                'default' => __('خطوات بسيطة للحصول على أفضل خدمة', 'bina'),
            ]
        );

        $this->add_control(
            'arrow_icon',
            [
                'label' => __('Arrow Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-down',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <section class="relative py-12 md:py-16 overflow-hidden bg-background">
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,hsl(var(--border))_1px,transparent_1px),linear-gradient(to_bottom,hsl(var(--border))_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_110%)]">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 via-transparent to-transparent"></div>
            <div class="container-custom relative z-10">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center max-w-4xl mx-auto">
                        <span
                            class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-6"
                            style="opacity: 1; transform: none;"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-foreground mb-6"
                            style="opacity: 1; transform: none;"><?php echo esc_html($settings['heading'] ?? ''); ?></h1>
                        <p class="text-xl text-muted-foreground leading-relaxed max-w-2xl mx-auto"
                            style="opacity: 1; transform: none;"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                        <div class="mt-12" style="opacity: 1; transform: translateY(2.78224px);">
                            <span class="w-8 h-8 text-primary mx-auto block">
                                <?php
                                if (!empty($settings['arrow_icon'])) {
                                    Icons_Manager::render_icon(
                                        $settings['arrow_icon'],
                                        ['aria-hidden' => 'true', 'class' => 'w-8 h-8'],
                                        'span'
                                    );
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

