<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class bina_Why_Us_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_why_us';
    }

    public function get_title() {
        return __('Why Us', 'bina');
    }

    public function get_icon() {
        return 'eicon-featured-image';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_why_us',
            [
                'label' => __('Why Us Content', 'bina'),
            ]
        );

        $this->add_control(
            'why_heading',
            [
                'label' => __('Section Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Why Us', 'bina'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Why Choose Us', 'bina'),
            ]
        );

        $repeater->add_control(
            'item_text',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $repeater->add_control(
            'item_image',
            [
                'label' => __('Image', 'bina'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'why_items',
            [
                'label' => __('Items', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_title' => 'Simplified Learning Approach',
                        'item_text' => 'Complex aptitude concepts are broken down into clear, easy-to-understand lessons for faster comprehension.',
                    ],
                    [
                        'item_title' => 'Real Exam-Style Practice Tests',
                        'item_text' => 'Practice with realistic simulations designed to match the actual General Aptitude Test format.',
                    ],
                    [
                        'item_title' => 'Expert Explanations & Guidance',
                        'item_text' => 'Every question is supported by detailed explanations to help students understand logic, not just answers.',
                    ],
                    [
                        'item_title' => 'Proven Performance Improvement',
                        'item_text' => 'Structured training plans focused on improving accuracy, confidence, and overall exam performance.',
                    ],
                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <section class="section_why_us">
            <div class="container">
                <div class="sec_head_why wow fadeInUp">
                    <p><?php echo esc_html($s['why_heading']); ?></p>
                </div>
                <div class="row wow fadeInUp">
                    <?php foreach ($s['why_items'] as $item) : ?>
                        <div class="col-lg-3">
                            <div class="item-why-us">
                                <h4><?php echo esc_html($item['item_title']); ?></h4>
                                <p><?php echo esc_html($item['item_text']); ?></p>
                                <figure>
                                    <img src="<?php echo esc_url($item['item_image']['url']); ?>" alt="" />
                                </figure>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
