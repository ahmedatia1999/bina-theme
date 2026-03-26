<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Financing_Team_Message_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_financing_team_message';
    }

    public function get_title() {
        return __('Financing - Team Message (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-blockquote';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Team Message Content', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('رسالة من فريق بناء', 'bina'),
            ]
        );

        $this->add_control(
            'message',
            [
                'label' => __('Message', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('في بناء نؤمن أن الحصول على التمويل لا يجب أن يكون معقدًا. نحن هنا لنساعدك في إيجاد الجهة المناسبة لمشروعك، بخطوات بسيطة وواضحة.', 'bina'),
            ]
        );

        $this->add_control(
            'highlight',
            [
                'label' => __('Highlight Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('هدفنا أن ترى حلمك يتحقق وأنت مطمئن وواثق بكل خطوة', 'bina'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <section class="section-padding bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="max-w-4xl mx-auto text-center">
                        <h2 class="text-2xl md:text-3xl font-bold mb-6"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <div class="p-8 rounded-2xl bg-background border border-border shadow-lg">
                            <p class="text-lg text-muted-foreground mb-6"><?php echo esc_html($settings['message'] ?? ''); ?></p>
                            <p class="text-primary font-semibold text-lg"><?php echo esc_html($settings['highlight'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

