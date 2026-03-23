<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class bina_Header_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_header';
    }

    public function get_title() {
        return __('Header', 'bina');
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_header',
            [
                'label' => __('Header Content', 'bina'),
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => __('Logo', 'bina'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'menu_text',
            [
                'label' => __('Menu Text', 'bina'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'menu_link',
            [
                'label' => __('Menu Link', 'bina'),
                'type' => Controls_Manager::URL,
            ]
        );

        $repeater->add_control(
            'is_button',
            [
                'label' => __('Button Style', 'bina'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'is_icon',
            [
                'label' => __('User Icon', 'bina'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'menu_items',
            [
                'label' => __('Menu Items', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ menu_text }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <div>
            <div class="mobile-menu">
                <div class="logo-mobile">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url($s['logo']['url']); ?>" alt="">
                    </a>
                    <div class="is-closed"><i class="fa-solid fa-xmark"></i></div>
                </div>
                <div class="mmenu">
                    <ul class="main_menu">
                        <?php foreach ($s['menu_items'] as $item) : ?>
                            <li>
                                <a class="page-scroll <?php echo ($item['is_button'] === 'yes') ? 'btn-site' : ''; ?>"
                                   href="<?php echo esc_url($item['menu_link']['url']); ?>">
                                    <?php if ($item['is_icon'] === 'yes') : ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/icon-user.png'); ?>" alt="">
                                    <?php else : ?>
                                        <span><?php echo esc_html($item['menu_text']); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="container">
                <div class="logo-site">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo esc_url($s['logo']['url']); ?>" alt="">
                    </a>
                </div>

                <ul class="main_menu">
                    <?php foreach ($s['menu_items'] as $item) : ?>
                        <li>
                            <a class="page-scroll <?php echo ($item['is_button'] === 'yes') ? 'btn-site' : ''; ?>"
                               href="<?php echo esc_url($item['menu_link']['url']); ?>">
                                <?php if ($item['is_icon'] === 'yes') : ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/images/icon-user.png'); ?>" alt="">
                                <?php else : ?>
                                    <span><?php echo esc_html($item['menu_text']); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="button" class="hamburger">
                    <i class="icon-hamburger"></i>
                </button>
            </div>
        </div>
        <?php
    }
}
