<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

class bina_About_Us_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_about_us';
    }

    public function get_title() {
        return __('About Us', 'bina');
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        // Start About Us Section
        $this->start_controls_section(
            'section_about',
            [
                'label' => __('About Us Content', 'bina'),
            ]
        );

        // Add Heading Control
        $this->add_control(
            'about_heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('About Us', 'bina'),
            ]
        );

        // Add Text Content Control
        $this->add_control(
            'about_content',
            [
                'label' => __('Content', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.', 'bina'),
            ]
        );

        // Add Button Text Control
        $this->add_control(
            'about_button_text',
            [
                'label' => __('Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Explore More', 'bina'),
            ]
        );

        // Add Button Link Control
        $this->add_control(
            'about_button_link',
            [
                'label' => __('Button Link', 'bina'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'bina'),
                'default' => [
                    'url' => 'about.html',
                ],
            ]
        );

        // Add Image Controls
        $this->add_control(
            'about_image',
            [
                'label' => __('Top Image', 'bina'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="section_about">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="txt-about wow fadeInUp">
                            <h2><?php echo esc_html($settings['about_heading']); ?></h2>
                            <p><?php echo esc_html($settings['about_content']); ?></p>
                            <a href="<?php echo esc_url($settings['about_button_link']['url']); ?>" class="btn-site">
                                <span><?php echo esc_html($settings['about_button_text']); ?></span>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="thumb-about wow fadeInUp">
                            <figure class="about-us-image">
                                <img src="<?php echo esc_url($settings['about_image']['url']); ?>" alt="" />
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--section_about-->
        <?php
    }
}
