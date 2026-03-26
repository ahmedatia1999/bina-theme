<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Financing_Form_Shortcode_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_financing_form_shortcode';
    }

    public function get_title() {
        return __('Financing - Form Shortcode (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Form Section Content', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('قدّم طلبك الآن', 'bina'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ابدأ أول خطوة نحو تمويل مشروعك', 'bina'),
            ]
        );

        $this->add_control(
            'notice_text',
            [
                'label' => __('Notice Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('الخدمة مجانية تمامًا، ولا تتطلب أي التزامات مالية أو مستندات أولية', 'bina'),
            ]
        );

        $this->add_control(
            'form_shortcode',
            [
                'label' => __('Contact Form 7 Shortcode', 'bina'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '[contact-form-7 id="123" title="Financing Form"]',
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $shortcode = trim($settings['form_shortcode'] ?? '');
        ?>

        <section id="financing-form" class="section-padding bg-gradient-to-br from-primary/5 via-background to-accent/5">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-xl text-muted-foreground mb-4"><?php echo esc_html($settings['subtitle'] ?? ''); ?></p>
                        <div class="inline-block px-6 py-3 rounded-xl bg-green-500/10 border border-green-500/20">
                            <p class="text-white dark:text-white font-medium"><?php echo esc_html($settings['notice_text'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>

                <div class="" style="opacity: 1; transform: none;">
                    <div class="max-w-4xl mx-auto">
                        <div class="bg-background rounded-3xl border border-border shadow-xl p-8 md:p-10" style="opacity: 1; transform: none;">
                            <?php if (!empty($shortcode)): ?>
                                <div class="bina-financing-cf7">
                                    <?php echo do_shortcode($shortcode); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted-foreground text-center">ضع شورت كود Contact Form 7 في إعدادات الودجت.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

