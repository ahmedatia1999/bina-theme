<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

class bina_Financing_Hero_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_financing_hero';
    }

    public function get_title() {
        return __('Financing Hero (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-price-table';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Financing Hero Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_icon',
            [
                'label' => __('Badge Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-money-bill-wave',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('خدمة مجانية', 'bina'),
            ]
        );

        $this->add_control(
            'heading_before',
            [
                'label' => __('Heading (Before Gradient)', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('تمويلك', 'bina'),
            ]
        );

        $this->add_control(
            'heading_gradient',
            [
                'label' => __('Heading (Gradient Part)', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ينتظرك', 'bina'),
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('مشروعك يستحق أن يبدأ… ونحن نسهّل لك الطريق', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('كل مشروع يبدأ بحلم بيتك الجديد، مكتبك، أو مشروعك القادم. في بناء  جعلنا الحصول على التمويل أبسط: نوفر لك طريقة ذكية وسريعة للتعرف على الجهات التمويلية المرخّصة.', 'bina'),
            ]
        );

        $this->add_control(
            'notice_text',
            [
                'label' => __('Notice Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('بناء  لا تمنح التمويل بنفسها، لكنها تساعدك في الوصول إلى الجهة الصحيحة', 'bina'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <section class="relative py-12 md:py-16 overflow-hidden bg-background">
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,hsl(var(--border))_1px,transparent_1px),linear-gradient(to_bottom,hsl(var(--border))_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_110%)]">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 via-transparent to-transparent"></div>
            <div class="container-custom relative z-10 text-center">
                <div style="opacity: 1; transform: none;">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-6"
                        style="opacity: 1; transform: none;">
                        <span class="w-4 h-4 text-primary block">
                            <?php
                            if (!empty($settings['badge_icon'])) {
                                Icons_Manager::render_icon(
                                    $settings['badge_icon'],
                                    ['aria-hidden' => 'true', 'class' => 'w-4 h-4'],
                                    'span'
                                );
                            }
                            ?>
                        </span>
                        <span class="text-sm font-medium text-primary"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                        <span class="text-foreground"><?php echo esc_html($settings['heading_before'] ?? ''); ?> </span>
                        <span class="text-gradient"><?php echo esc_html($settings['heading_gradient'] ?? ''); ?></span>
                    </h1>

                    <p class="text-xl md:text-2xl text-muted-foreground mb-4 max-w-3xl mx-auto">
                        <?php echo esc_html($settings['subtitle'] ?? ''); ?>
                    </p>
                    <p class="text-lg text-muted-foreground mb-8 max-w-4xl mx-auto">
                        <?php echo esc_html($settings['description'] ?? ''); ?>
                    </p>

                    <div class="inline-block px-6 py-3 rounded-xl bg-amber-500/10 border border-amber-500/20 mb-8"
                        style="opacity: 1; transform: none;">
                        <p class="text-amber-600 dark:text-amber-400 font-medium">
                            <?php echo esc_html($settings['notice_text'] ?? ''); ?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

