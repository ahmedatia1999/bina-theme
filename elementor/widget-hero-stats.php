<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Icons_Manager;

class bina_Hero_Stats_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_hero_stats';
    }

    public function get_title() {
        return __('Hero + Stats (Static)', 'bina');
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
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('منصة البناء الأولى في السعودية', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('بناء', 'bina'),
            ]
        );

        $this->add_control(
            'subtitle_primary',
            [
                'label' => __('Primary Subtitle', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('نربط بين أصحاب المشاريع والمقاولين المحترفين', 'bina'),
            ]
        );

        $this->add_control(
            'subtitle_secondary',
            [
                'label' => __('Secondary Subtitle', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __('منصة سعودية رائدة تجمع أصحاب مشاريع البناء والترميم مع نخبة من المقاولين المعتمدين', 'bina'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'primary_button_text',
            [
                'label' => __('Primary Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ابدأ مشروعك الآن', 'bina'),
            ]
        );

        $this->add_control(
            'primary_button_url',
            [
                'label' => __('Primary Button Link', 'bina'),
                'type' => Controls_Manager::URL,
                'placeholder' => '/customer-create-project',
                'default' => [
                    'url' => '/customer-create-project',
                ],
            ]
        );

        $this->add_control(
            'secondary_button_text',
            [
                'label' => __('Secondary Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('تواصل معنا', 'bina'),
            ]
        );

        $this->add_control(
            'secondary_button_url',
            [
                'label' => __('Secondary Button Link', 'bina'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://wa.me/966590000474',
                'default' => [
                    'url' => 'https://wa.me/966590000474',
                    'is_external' => true,
                ],
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'icon',
            [
                'label' => __('Card Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-hard-hat',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $repeater->add_control(
            'value',
            [
                'label' => __('Value', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '3,000+',
            ]
        );
        $repeater->add_control(
            'label',
            [
                'label' => __('Label', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('مقاول محترف', 'bina'),
            ]
        );

        $this->add_control(
            'stats',
            [
                'label' => __('Stats Cards', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'value' => '3,000+', 'label' => 'مقاول محترف' ],
                    [ 'value' => '3,800+', 'label' => 'مشروع منجز' ],
                    [ 'value' => '95%', 'label' => 'رضا العملاء' ],
                ],
                'title_field' => '{{{ value }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $stats = $settings['stats'] ?? [];

        $primary_href = $settings['primary_button_url']['url'] ?? '';
        $primary_href = function_exists('bina_dashboard_resolve_url') ? bina_dashboard_resolve_url($primary_href) : $primary_href;
        $primary_href = $primary_href ? esc_url($primary_href) : '#';
        $primary_target = !empty($settings['primary_button_url']['is_external']) ? ' target="_blank"' : '';
        $primary_rel = !empty($settings['primary_button_url']['is_external']) ? ' rel="noopener noreferrer"' : '';

        $secondary_href = $settings['secondary_button_url']['url'] ?? '';
        $secondary_href = function_exists('bina_dashboard_resolve_url') ? bina_dashboard_resolve_url($secondary_href) : $secondary_href;
        $secondary_href = $secondary_href ? esc_url($secondary_href) : '#';
        $secondary_target = !empty($settings['secondary_button_url']['is_external']) ? ' target="_blank"' : '';
        $secondary_rel = !empty($settings['secondary_button_url']['is_external']) ? ' rel="noopener noreferrer"' : '';
        ?>

                <section class="relative py-8 md:py-12 lg:py-16 overflow-hidden bg-background">
                    <div class="absolute inset-0">
                        <div class="absolute inset-0" style="background-image: linear-gradient(to right, hsl(var(--border)) 1px, transparent 1px),
              linear-gradient(to bottom, hsl(var(--border)) 1px, transparent 1px); background-size: 60px 60px;"></div>
                        <div class="absolute inset-0 bg-gradient-to-b from-background via-transparent to-muted/30">
                        </div>
                    </div>
                    <div class="absolute text-primary opacity-40"
                        style="top: 12%; left: 2%; opacity: 0; transform: translateY(-0.365208px) scale(1.00487) rotate(-0.673048deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-house hidden md:block">
                            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                            <path
                                d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-house block md:hidden">
                            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                            <path
                                d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="absolute text-primary opacity-40"
                        style="top: 35%; left: 1%; opacity: 0; transform: translateY(-0.231106px) scale(1.00308) rotate(-0.425322deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-building2 hidden md:block">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                            <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                            <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                            <path d="M10 6h4"></path>
                            <path d="M10 10h4"></path>
                            <path d="M10 14h4"></path>
                            <path d="M10 18h4"></path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-building2 block md:hidden">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                            <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                            <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                            <path d="M10 6h4"></path>
                            <path d="M10 10h4"></path>
                            <path d="M10 14h4"></path>
                            <path d="M10 18h4"></path>
                        </svg>
                    </div>
                    <div class="absolute text-blue-500 opacity-40"
                        style="top: 55%; left: 3%; opacity: 0; transform: translateY(-0.15956px) scale(1.00213) rotate(-0.292166deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-paint-bucket hidden md:block">
                            <path d="m19 11-8-8-8.6 8.6a2 2 0 0 0 0 2.8l5.2 5.2c.8.8 2 .8 2.8 0L19 11Z"></path>
                            <path d="m5 2 5 5"></path>
                            <path d="M2 13h15"></path>
                            <path d="M22 20a2 2 0 1 1-4 0c0-1.6 1.7-2.4 2-4 .3 1.6 2 2.4 2 4Z"></path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-paint-bucket block md:hidden">
                            <path d="m19 11-8-8-8.6 8.6a2 2 0 0 0 0 2.8l5.2 5.2c.8.8 2 .8 2.8 0L19 11Z"></path>
                            <path d="m5 2 5 5"></path>
                            <path d="M2 13h15"></path>
                            <path d="M22 20a2 2 0 1 1-4 0c0-1.6 1.7-2.4 2-4 .3 1.6 2 2.4 2 4Z"></path>
                        </svg>
                    </div>
                    <div class="absolute text-primary opacity-40"
                        style="top: 75%; left: 2%; opacity: 0; transform: translateY(-4.72036px) scale(1.06294) rotate(7.69739deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-construction hidden md:block">
                            <rect x="2" y="6" width="20" height="8" rx="1"></rect>
                            <path d="M17 14v7"></path>
                            <path d="M7 14v7"></path>
                            <path d="M17 3v3"></path>
                            <path d="M7 3v3"></path>
                            <path d="M10 14 2.3 6.3"></path>
                            <path d="m14 6 7.7 7.7"></path>
                            <path d="m8 6 8 8"></path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-construction block md:hidden">
                            <rect x="2" y="6" width="20" height="8" rx="1"></rect>
                            <path d="M17 14v7"></path>
                            <path d="M7 14v7"></path>
                            <path d="M17 3v3"></path>
                            <path d="M7 3v3"></path>
                            <path d="M10 14 2.3 6.3"></path>
                            <path d="m14 6 7.7 7.7"></path>
                            <path d="m8 6 8 8"></path>
                        </svg>
                    </div>
                    <div class="absolute text-blue-600 opacity-40"
                        style="top: 15%; right: 2%; opacity: 0; transform: translateY(-0.368924px) scale(1.00492) rotate(0.673048deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-hammer hidden md:block">
                            <path d="m15 12-8.373 8.373a1 1 0 1 1-3-3L12 9"></path>
                            <path d="m18 15 4-4"></path>
                            <path
                                d="m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172V7l-2.26-2.26a6 6 0 0 0-4.202-1.756L9 2.96l.92.82A6.18 6.18 0 0 1 12 8.4V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-hammer block md:hidden">
                            <path d="m15 12-8.373 8.373a1 1 0 1 1-3-3L12 9"></path>
                            <path d="m18 15 4-4"></path>
                            <path
                                d="m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172V7l-2.26-2.26a6 6 0 0 0-4.202-1.756L9 2.96l.92.82A6.18 6.18 0 0 1 12 8.4V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5">
                            </path>
                        </svg>
                    </div>
                    <div class="absolute text-primary opacity-40"
                        style="top: 38%; right: 1%; opacity: 0; transform: translateY(-0.252497px) scale(1.00337) rotate(0.464895deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-wrench hidden md:block">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                            </path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-wrench block md:hidden">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                            </path>
                        </svg>
                    </div>
                    <div class="absolute text-blue-500 opacity-40"
                        style="top: 58%; right: 3%; opacity: 0; transform: translateY(-10.9259px) scale(1.14568) rotate(11.9606deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-ruler hidden md:block">
                            <path
                                d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z">
                            </path>
                            <path d="m14.5 12.5 2-2"></path>
                            <path d="m11.5 9.5 2-2"></path>
                            <path d="m8.5 6.5 2-2"></path>
                            <path d="m17.5 15.5 2-2"></path>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-ruler block md:hidden">
                            <path
                                d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z">
                            </path>
                            <path d="m14.5 12.5 2-2"></path>
                            <path d="m11.5 9.5 2-2"></path>
                            <path d="m8.5 6.5 2-2"></path>
                            <path d="m17.5 15.5 2-2"></path>
                        </svg>
                    </div>
                    <div class="absolute text-primary opacity-40"
                        style="top: 78%; right: 2%; opacity: 0; transform: translateY(-14.7689px) scale(1.19692) rotate(5.27476deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-hard-hat hidden md:block">
                            <path d="M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5"></path>
                            <path d="M14 6a6 6 0 0 1 6 6v3"></path>
                            <path d="M4 15v-3a6 6 0 0 1 6-6"></path>
                            <rect x="2" y="15" width="20" height="4" rx="1"></rect>
                        </svg><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-hard-hat block md:hidden">
                            <path d="M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5"></path>
                            <path d="M14 6a6 6 0 0 1 6 6v3"></path>
                            <path d="M4 15v-3a6 6 0 0 1 6-6"></path>
                            <rect x="2" y="15" width="20" height="4" rx="1"></rect>
                        </svg>
                    </div>
                    <div class="absolute text-blue-400 opacity-40 hidden md:block"
                        style="top: 22%; left: 12%; opacity: 0; transform: translateY(-4.43244px) scale(1.04432) rotate(7.21991deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-house">
                            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                            <path
                                d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="absolute text-blue-400 opacity-40 hidden md:block"
                        style="top: 45%; right: 10%; opacity: 0; transform: translateY(-17.642px) scale(1.17642) rotate(-13.8008deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-building2">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                            <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                            <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                            <path d="M10 6h4"></path>
                            <path d="M10 10h4"></path>
                            <path d="M10 14h4"></path>
                            <path d="M10 18h4"></path>
                        </svg>
                    </div>
                    <div class="absolute text-blue-400 opacity-40 hidden md:block"
                        style="top: 65%; left: 10%; opacity: 0; transform: translateY(-18.509px) scale(1.18509) rotate(-12.2113deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-hammer">
                            <path d="m15 12-8.373 8.373a1 1 0 1 1-3-3L12 9"></path>
                            <path d="m18 15 4-4"></path>
                            <path
                                d="m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172V7l-2.26-2.26a6 6 0 0 0-4.202-1.756L9 2.96l.92.82A6.18 6.18 0 0 1 12 8.4V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5">
                            </path>
                        </svg>
                    </div>
                    <div class="absolute text-blue-400 opacity-40 hidden md:block"
                        style="top: 28%; right: 12%; opacity: 0; transform: translateY(-10.2563px) scale(1.10256) rotate(-13.2315deg);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-wrench">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z">
                            </path>
                        </svg>
                    </div>
                    <div class="absolute top-20 right-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl"
                        style="transform: scale(1.00487);"></div>
                    <div class="absolute bottom-20 left-10 w-96 h-96 bg-accent/10 rounded-full blur-3xl"
                        style="transform: scale(1.15959);"></div>
                    <div class="container-custom relative z-10">
                        <div class="flex flex-col items-center text-center max-w-4xl mx-auto">
                            <div class="" style="opacity: 1; transform: none;"><span
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4 md:mb-6"
                                    style="opacity: 1; transform: none;"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-building2 w-4 h-4">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                                        <path d="M10 6h4"></path>
                                        <path d="M10 10h4"></path>
                                        <path d="M10 14h4"></path>
                                        <path d="M10 18h4"></path>
                                    </svg><?php echo esc_html($settings['badge_text'] ?? ''); ?></span></div>
                            <div class="" style="opacity: 1; transform: none;">
                                <h1
                                    class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-secondary leading-tight mb-3 md:mb-6">
                                    <?php echo esc_html($settings['heading'] ?? ''); ?></h1>
                            </div>
                            <div class="" style="opacity: 1; transform: none;">
                                <p
                                    class="text-base sm:text-lg md:text-xl lg:text-2xl text-primary font-semibold mb-2 md:mb-4">
                                    <?php echo esc_html($settings['subtitle_primary'] ?? ''); ?></p>
                            </div>
                            <div class="" style="opacity: 1; transform: none;">
                                <p
                                    class="text-sm sm:text-base md:text-lg text-muted-foreground max-w-2xl leading-relaxed mb-5 md:mb-8 px-2">
                                    <?php echo esc_html($settings['subtitle_secondary'] ?? ''); ?>
                                </p>
                            </div>
                            <div class="" style="opacity: 1; transform: none;">
                                <div class="flex flex-wrap gap-3 md:gap-4 justify-center">
                                    <a href="<?php echo $primary_href; ?>"<?php echo $primary_target; ?><?php echo $primary_rel; ?>
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md px-8 bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg group">
                                        <?php echo esc_html($settings['primary_button_text'] ?? ''); ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-arrow-left w-5 h-5 group-hover:-translate-x-1 transition-transform">
                                            <path d="m12 19-7-7 7-7"></path>
                                            <path d="M19 12H5"></path>
                                        </svg>
                                    </a>
                                    <a href="<?php echo $secondary_href; ?>"<?php echo $secondary_target; ?><?php echo $secondary_rel; ?>
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background h-11 rounded-md px-8 border-green-600 text-green-600 hover:bg-green-600 hover:text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-message-circle w-5 h-5">
                                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                                        </svg>
                                        <?php echo esc_html($settings['secondary_button_text'] ?? ''); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 md:mt-12 lg:mt-16 px-2">
                            <div class="grid grid-cols-3 gap-2 sm:gap-4 lg:gap-8">
                                <?php foreach ($stats as $i => $item): ?>
                                <?php
                                    $icon = $item['icon'] ?? null;
                                    $value = $item['value'] ?? '';
                                    $label = $item['label'] ?? '';
                                ?>
                                <div class="" style="opacity: 1; transform: none;">
                                    <div
                                        class="relative bg-card rounded-xl sm:rounded-2xl p-3 sm:p-4 md:p-6 border border-border shadow-card group hover:shadow-card-hover transition-all">
                                        <div class="flex flex-col items-center text-center gap-2 sm:gap-3">
                                            <div
                                                class="w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-lg sm:rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                                                <?php if (!empty($icon['value'])): ?>
                                                    <span class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-primary group-hover:text-white transition-colors inline-flex items-center justify-center">
                                                        <?php Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <?php if ((int)$i === 0): ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-hard-hat w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-primary group-hover:text-white transition-colors">
                                                            <path d="M10 10V5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5"></path>
                                                            <path d="M14 6a6 6 0 0 1 6 6v3"></path>
                                                            <path d="M4 15v-3a6 6 0 0 1 6-6"></path>
                                                            <rect x="2" y="15" width="20" height="4" rx="1"></rect>
                                                        </svg>
                                                    <?php elseif ((int)$i === 1): ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-building2 w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-primary group-hover:text-white transition-colors">
                                                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                                                            <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                                                            <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                                                            <path d="M10 6h4"></path>
                                                            <path d="M10 10h4"></path>
                                                            <path d="M10 14h4"></path>
                                                            <path d="M10 18h4"></path>
                                                        </svg>
                                                    <?php else: ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="lucide lucide-star w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-primary group-hover:text-white transition-colors">
                                                            <path
                                                                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
                                                            </path>
                                                        </svg>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p
                                                    class="text-lg sm:text-2xl md:text-3xl lg:text-4xl font-bold text-secondary">
                                                    <span class=""><?php echo esc_html($value); ?></span>
                                                </p>
                                                <p
                                                    class="text-muted-foreground text-[10px] sm:text-xs md:text-sm mt-0.5 sm:mt-1">
                                                    <?php echo esc_html($label); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>

        <?php
    }
}

