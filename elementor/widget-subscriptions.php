<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Subscriptions_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_subscriptions';
    }

    public function get_title() {
        return __('Subscriptions', 'bina');
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_subscriptions',
            [
                'label' => __('Subscriptions Content', 'bina'),
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Subscriptions', 'bina'),
            ]
        );

        $this->add_control(
            'section_desc',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('Sign up for one of the packages and enjoy a unique experience', 'bina'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'plan_title',
            [
                'label' => __('Plan Title', 'bina'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'plan_subtitle',
            [
                'label' => __('Subtitle', 'bina'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'plan_price',
            [
                'label' => __('Price', 'bina'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'plan_duration',
            [
                'label' => __('Duration', 'bina'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'plan_features',
            [
                'label' => __('Features (one per line)', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
            ]
        );

        $repeater->add_control(
            'plan_button_text',
            [
                'label' => __('Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Sign Up Now', 'bina'),
            ]
        );

        $repeater->add_control(
            'plan_button_link',
            [
                'label' => __('Button Link', 'bina'),
                'type' => Controls_Manager::URL,
            ]
        );

        $repeater->add_control(
            'is_popular',
            [
                'label' => __('Mark as Popular', 'bina'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'plans',
            [
                'label' => __('Plans', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ plan_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <section class="section_subscriptions">
            <div class="container">
                <div class="sec_head wow fadeInUp">
                    <h2><?php echo esc_html($s['section_title']); ?></h2>
                    <p><?php echo esc_html($s['section_desc']); ?></p>
                </div>

                <div class="owl-carousel" id="subscriptions-slider">
                    <?php foreach ($s['plans'] as $plan) : ?>
                        <div class="item">
                            <?php if ($plan['is_popular'] === 'yes') : ?>
                                <div class="popular-plan wow fadeInUp">
                                    <div class="sp-popular"><strong>Most Popular</strong></div>
                            <?php endif; ?>

                            <div class="item-plan wow fadeInUp">
                                <div class="info-plan">
                                    <h6><?php echo esc_html($plan['plan_title']); ?></h6>
                                    <span><?php echo esc_html($plan['plan_subtitle']); ?></span>
                                    <p><i class="icon-rsa"></i><b><?php echo esc_html($plan['plan_price']); ?> / </b> <?php echo esc_html($plan['plan_duration']); ?></p>
                                </div>

                                <div class="includes-plan">
                                    <b>Includes:</b>
                                    <ul>
                                        <?php
                                        $features = explode("\n", $plan['plan_features']);
                                        foreach ($features as $feature) :
                                        ?>
                                            <li><span><i class="fa-solid fa-check"></i></span> <?php echo esc_html($feature); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                                <a href="<?php echo esc_url($plan['plan_button_link']['url']); ?>" class="btn-site <?php echo ($plan['is_popular'] !== 'yes') ? 'btn-oth' : ''; ?>">
                                    <span><?php echo esc_html($plan['plan_button_text']); ?></span>
                                </a>
                            </div>

                            <?php if ($plan['is_popular'] === 'yes') : ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

