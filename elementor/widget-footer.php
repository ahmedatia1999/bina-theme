<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class bina_Footer_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_footer';
    }

    public function get_title() {
        return __('Footer', 'bina');
    }

    public function get_icon() {
        return 'eicon-footer';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_footer',
            [
                'label' => __('Footer Content', 'bina'),
            ]
        );

        $this->add_control(
            'footer_logo',
            [
                'label' => __('Logo', 'bina'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $menu_repeater = new Repeater();

        $menu_repeater->add_control(
            'menu_text',
            [
                'label' => __('Menu Text', 'bina'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $menu_repeater->add_control(
            'menu_link',
            [
                'label' => __('Menu Link', 'bina'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'footer_menu',
            [
                'label' => __('Footer Menu', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $menu_repeater->get_controls(),
                'title_field' => '{{{ menu_text }}}',
            ]
        );

        $this->add_control(
            'phone_text',
            [
                'label' => __('Phone', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '+966 XX XXX XXXX',
            ]
        );

        $this->add_control(
            'phone_link',
            [
                'label' => __('Phone Link', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '+96622334455',
            ]
        );

        $this->add_control(
            'email_text',
            [
                'label' => __('Email', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'support@bina7.com',
            ]
        );

        $this->add_control(
            'email_link',
            [
                'label' => __('Email Link', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'support@bina7.com',
            ]
        );

        $this->add_control(
            'copyright_text',
            [
                'label' => __('Copyright Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '© 2026 Copyright - bina 7',
            ]
        );

        $this->add_control(
            'privacy_text',
            [
                'label' => __('Privacy Policy Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'Privacy Policy',
            ]
        );

        $this->add_control(
            'privacy_link',
            [
                'label' => __('Privacy Policy Link', 'bina'),
                'type' => Controls_Manager::URL,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <footer id="footer">
            <div class="container">
                <div class="top-footer">
                    <div class="row align-items-center">
                        <div class="col-lg-2">
                            <div class="cont-ft wow fadeInUp">
                                <figure class="logo-ft">
                                    <img src="<?php echo esc_url($s['footer_logo']['url']); ?>" alt="" class="img-fluid">
                                </figure>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="menu-ft wow fadeInUp">
                                <ul class="li-ft">
                                    <?php foreach ($s['footer_menu'] as $item) : ?>
                                        <li>
                                            <a href="<?php echo esc_url($item['menu_link']['url']); ?>">
                                                <?php echo esc_html($item['menu_text']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="menu-contact wow fadeInUp">
                                <ul class="list-contact">
                                    <li>
                                        <a href="tel:<?php echo esc_attr($s['phone_link']); ?>">
                                            <i class="fa-solid fa-phone"></i> <?php echo esc_html($s['phone_text']); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="mailto:<?php echo esc_attr($s['email_link']); ?>">
                                            <i class="fa-solid fa-envelope"></i> <?php echo esc_html($s['email_text']); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bottom-ft">
                    <div class="cont-bt">
                        <p class="copyRight wow fadeInUp"><?php echo esc_html($s['copyright_text']); ?></p>
                        <p>
                            <a href="<?php echo esc_url($s['privacy_link']['url']); ?>">
                                <?php echo esc_html($s['privacy_text']); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <?php
    }
}
