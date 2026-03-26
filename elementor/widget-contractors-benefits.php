<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Contractors_Benefits_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_contractors_benefits';
    }

    public function get_title() {
        return __('Contractors Benefits (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-icon-box';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Benefits Content', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('مزايا الانضمام لبناء سنتر', 'bina'),
            ]
        );

        $card = new Repeater();

        $card->add_control(
            'icon',
            [
                'label' => __('Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-briefcase',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $card->add_control(
            'title',
            [
                'label' => __('Card Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('فرص عمل متجددة', 'bina'),
            ]
        );

        $card->add_control(
            'point_1',
            [
                'label' => __('Point 1', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('استقبال طلبات مشاريع جديدة يومياً', 'bina'),
            ]
        );

        $card->add_control(
            'point_2',
            [
                'label' => __('Point 2', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('الوصول لشريحة واسعة من العملاء', 'bina'),
            ]
        );

        $card->add_control(
            'point_3',
            [
                'label' => __('Point 3', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('تنويع مصادر الدخل وزيادة حجم الأعمال', 'bina'),
            ]
        );

        $this->add_control(
            'cards',
            [
                'label' => __('Cards', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $card->get_controls(),
                'default' => [
                    [
                        'icon' => ['value' => 'fas fa-briefcase', 'library' => 'fa-solid'],
                        'title' => 'فرص عمل متجددة',
                        'point_1' => 'استقبال طلبات مشاريع جديدة يومياً',
                        'point_2' => 'الوصول لشريحة واسعة من العملاء',
                        'point_3' => 'تنويع مصادر الدخل وزيادة حجم الأعمال',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-user', 'library' => 'fa-solid'],
                        'title' => 'ملف تعريفي احترافي',
                        'point_1' => 'إنشاء ملف شخصي يعرض خبراتك',
                        'point_2' => 'الحصول على شارات تميز وتصنيفات',
                        'point_3' => 'بناء سمعة قوية من خلال التقييمات',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-wrench', 'library' => 'fa-solid'],
                        'title' => 'أدوات إدارة احترافية',
                        'point_1' => 'نظام مراسلات داخلي للتواصل',
                        'point_2' => 'إدارة المشاريع والعروض',
                        'point_3' => 'توثيق مراحل العمل والتحديثات',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cards = $settings['cards'] ?? [];
        ?>

        <section class="py-20 bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <?php foreach ($cards as $card): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 h-full" style="transform: none;">
                                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                                    <span class="w-7 h-7 text-primary block">
                                        <?php
                                        if (!empty($card['icon'])) {
                                            Icons_Manager::render_icon(
                                                $card['icon'],
                                                ['aria-hidden' => 'true', 'class' => 'w-7 h-7'],
                                                'span'
                                            );
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-secondary mb-4"><?php echo esc_html($card['title'] ?? ''); ?></h3>
                                <ul class="space-y-3">
                                    <li class="flex items-start gap-2 text-muted-foreground"><span class="text-primary">•</span><span><?php echo esc_html($card['point_1'] ?? ''); ?></span></li>
                                    <li class="flex items-start gap-2 text-muted-foreground"><span class="text-primary">•</span><span><?php echo esc_html($card['point_2'] ?? ''); ?></span></li>
                                    <li class="flex items-start gap-2 text-muted-foreground"><span class="text-primary">•</span><span><?php echo esc_html($card['point_3'] ?? ''); ?></span></li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

