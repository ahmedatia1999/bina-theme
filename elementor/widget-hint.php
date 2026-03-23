<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Hint_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_hint';
    }

    public function get_title() {
        return __('Hint Text', 'bina');
    }

    public function get_icon() {
        return 'eicon-editor-paragraph';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_hint',
            [
                'label' => __('Hint Content', 'bina'),
            ]
        );

        $this->add_control(
            'hint_text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('bina 7 for General Aptitude is dedicated to empowering students and providing them with all the necessary tools to excel in the General Aptitude Test (GAT), driven by an ambitious vision and clear strategic objectives.', 'bina'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <section class="section_hint">
            <div class="container">
                <div class="cont-hint wow fadeInUp">
                    <p><?php echo esc_html($s['hint_text']); ?></p>
                </div>
            </div>
        </section>
        <?php
    }
}
