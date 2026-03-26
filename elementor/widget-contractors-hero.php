<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Contractors_Hero_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_contractors_hero';
    }

    public function get_title() {
        return __('Contractors Hero (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-banner';
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
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('فرصتك للوصول إلى مئات العملاء', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('تواصل مع مئات العملاء الباحثين عن مقاولين محترفين وقدم لهم خدماتك', 'bina'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('سجّل كمقاول', 'bina'),
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label' => __('Button URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $button_url = $settings['button_url']['url'] ?? '#';
        ?>

        <section class="pt-20 md:pt-24 pb-12 bg-background relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.03]"
                style="background-image: linear-gradient(hsl(var(--border)) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--border)) 1px, transparent 1px); background-size: 60px 60px;">
            </div>
            <div class="container-custom relative z-10">
                <div class="text-center max-w-4xl mx-auto" style="opacity: 1; transform: none;">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-secondary mb-6"><?php echo esc_html($settings['heading'] ?? ''); ?></h1>
                    <p class="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    <a href="<?php echo esc_url($button_url); ?>"
                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg text-lg px-8 py-6"><?php echo esc_html($settings['button_text'] ?? ''); ?></a>
                </div>
            </div>
        </section>

        <?php
    }
}

