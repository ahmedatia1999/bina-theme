<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

class bina_Hero_Home_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_hero_home';
    }

    public function get_title() {
        return __('Hero Home', 'bina');
    }

    public function get_icon() {
        return 'eicon-banner';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_hero',
            [
                'label' => __('Hero Content', 'bina'),
            ]
        );

        $this->add_control(
            'hero_heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Pass the <span>General Aptitude</span> Test with Confidence', 'bina'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'hero_text',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Prepare for the General Aptitude Test with structured training, realistic practice exams, and clear expert guidance.', 'bina'),
            ]
        );

        $this->add_control(
            'btn_one_text',
            [
                'label' => __('Button One Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Start Learning', 'bina'),
            ]
        );

        $this->add_control(
            'btn_one_link',
            [
                'label' => __('Button One Link', 'bina'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'btn_two_text',
            [
                'label' => __('Button Two Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('View Packages', 'bina'),
            ]
        );

        $this->add_control(
            'btn_two_link',
            [
                'label' => __('Button Two Link', 'bina'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'hero_image',
            [
                'label' => __('Hero Image', 'bina'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <section class="section_home">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="home_txt wow fadeInUp">
                            <h1><?php echo $s['hero_heading']; ?></h1>
                            <p><?php echo esc_html($s['hero_text']); ?></p>
                            <ul>
                                <li>
                                    <a href="<?php echo esc_url($s['btn_one_link']['url']); ?>" class="btn-site">
                                        <span><?php echo esc_html($s['btn_one_text']); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url($s['btn_two_link']['url']); ?>" class="btn-site btn-oth">
                                        <span><?php echo esc_html($s['btn_two_text']); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="thumb-hero wow fadeInUp">
                            <img src="<?php echo esc_url($s['hero_image']['url']); ?>" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}
