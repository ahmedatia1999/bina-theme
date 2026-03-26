<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_How_We_Work_Customer_Journey_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_how_we_work_customer_journey';
    }

    public function get_title() {
        return __('How We Work - Customer Journey (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-flow';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Customer Journey Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('للعملاء', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('رحلة العميل', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('6 خطوات بسيطة للحصول على مشروعك بأعلى جودة', 'bina'),
            ]
        );

        $card = new Repeater();

        $card->add_control(
            'number',
            [
                'label' => __('Step Number', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '01',
            ]
        );

        $card->add_control(
            'gradient_from',
            [
                'label' => __('Gradient From Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'from-blue-500',
            ]
        );

        $card->add_control(
            'gradient_to',
            [
                'label' => __('Gradient To Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'to-blue-600',
            ]
        );

        $card->add_control(
            'icon',
            [
                'label' => __('Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-file-alt',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $card->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('أضف مشروعك', 'bina'),
            ]
        );

        $card->add_control(
            'text',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('قم بإضافة تفاصيل مشروعك والميزانية المتوقعة والموقع', 'bina'),
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
                        'number' => '01',
                        'gradient_from' => 'from-blue-500',
                        'gradient_to' => 'to-blue-600',
                        'icon' => ['value' => 'fas fa-file-alt', 'library' => 'fa-solid'],
                        'title' => 'أضف مشروعك',
                        'text' => 'قم بإضافة تفاصيل مشروعك والميزانية المتوقعة والموقع',
                    ],
                    [
                        'number' => '02',
                        'gradient_from' => 'from-green-500',
                        'gradient_to' => 'to-green-600',
                        'icon' => ['value' => 'fas fa-comment-dots', 'library' => 'fa-solid'],
                        'title' => 'استلم العروض',
                        'text' => 'ستصلك عروض أسعار من مقاولين محترفين ومعتمدين',
                    ],
                    [
                        'number' => '03',
                        'gradient_from' => 'from-purple-500',
                        'gradient_to' => 'to-purple-600',
                        'icon' => ['value' => 'fas fa-balance-scale', 'library' => 'fa-solid'],
                        'title' => 'قارن واختر',
                        'text' => 'قارن العروض والتقييمات واختر المقاول المناسب لك',
                    ],
                    [
                        'number' => '04',
                        'gradient_from' => 'from-orange-500',
                        'gradient_to' => 'to-orange-600',
                        'icon' => ['value' => 'fas fa-file-signature', 'library' => 'fa-solid'],
                        'title' => 'وثّق العقد',
                        'text' => 'وثق العقد رقمياً عبر شركائنا القانونيين لحماية حقوقك',
                    ],
                    [
                        'number' => '05',
                        'gradient_from' => 'from-cyan-500',
                        'gradient_to' => 'to-cyan-600',
                        'icon' => ['value' => 'fas fa-eye', 'library' => 'fa-solid'],
                        'title' => 'تابع التنفيذ',
                        'text' => 'تابع مراحل التنفيذ خطوة بخطوة بشفافية كاملة',
                    ],
                    [
                        'number' => '06',
                        'gradient_from' => 'from-emerald-500',
                        'gradient_to' => 'to-emerald-600',
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'title' => 'استلم المشروع',
                        'text' => 'استلم مشروعك بعد التأكد من جودة العمل ومطابقة المواصفات',
                    ],
                ],
                'title_field' => '{{{ number }}} - {{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cards = $settings['cards'] ?? [];
        ?>

        <section class="py-24 bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4">
                            <?php echo esc_html($settings['badge_text'] ?? ''); ?>
                        </span>
                        <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4">
                            <?php echo esc_html($settings['heading'] ?? ''); ?>
                        </h2>
                        <p class="text-muted-foreground text-lg"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($cards as $card): ?>
                        <?php
                        $from = trim($card['gradient_from'] ?? '');
                        $to = trim($card['gradient_to'] ?? '');
                        $gradient = trim($from . ' ' . $to);
                        ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="relative bg-card rounded-2xl p-8 border border-border shadow-card hover:shadow-card-hover transition-all group h-full"
                                tabindex="0" style="transform: none;">
                                <div class="absolute -top-4 right-6 bg-gradient-to-br <?php echo esc_attr($gradient); ?> text-white text-xl font-bold px-5 py-2 rounded-xl shadow-lg"
                                    style="transform: none;"><?php echo esc_html($card['number'] ?? ''); ?></div>
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br <?php echo esc_attr($gradient); ?> bg-opacity-10 flex items-center justify-center mb-6 mt-4"
                                    style="transform: none;">
                                    <span class="w-8 h-8 text-white">
                                        <?php
                                        if (!empty($card['icon'])) {
                                            Icons_Manager::render_icon(
                                                $card['icon'],
                                                ['aria-hidden' => 'true', 'class' => 'w-8 h-8'],
                                                'span'
                                            );
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-foreground mb-3"><?php echo esc_html($card['title'] ?? ''); ?></h3>
                                <p class="text-muted-foreground leading-relaxed"><?php echo esc_html($card['text'] ?? ''); ?></p>
                                <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-br <?php echo esc_attr($gradient); ?> opacity-5 rounded-tl-full"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

