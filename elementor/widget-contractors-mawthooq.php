<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Contractors_Mawthooq_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_contractors_mawthooq';
    }

    public function get_title() {
        return __('Contractors Mawthooq (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-shield-check';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Mawthooq Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_icon',
            [
                'label' => __('Badge Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shield-halved',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('خدمة حصرية للمقاولين', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('ارفع مصداقيتك مع خدمة "موثوق"', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('احصل على علامة موثوق المعتمدة بعد زيارة ميدانية وتصوير احترافي لأعمالك. زد ثقة العملاء وفرص الفوز بالمشاريع بنسبة تصل إلى 89%!', 'bina'),
            ]
        );

        $point = new Repeater();

        $point->add_control(
            'icon',
            [
                'label' => __('Point Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle-check',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $point->add_control(
            'text',
            [
                'label' => __('Point Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('أولوية في الظهور بنتائج البحث', 'bina'),
            ]
        );

        $this->add_control(
            'points',
            [
                'label' => __('Points', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $point->get_controls(),
                'default' => [
                    [
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'text' => 'أولوية في الظهور بنتائج البحث',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'text' => 'شارة التحقق المميزة على ملفك',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'text' => 'زيادة ملحوظة في طلبات العملاء',
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('تعرف على خدمة موثوق', 'bina'),
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label' => __('Button URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/mawthooq',
                ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => __('Button Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-arrow-left',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'circle_icon',
            [
                'label' => __('Circle Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shield-halved',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'percent_text',
            [
                'label' => __('Percent Label', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('+89%', 'bina'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $points = $settings['points'] ?? [];
        $button_url = $settings['button_url']['url'] ?? '#';
        ?>

        <section class="py-16 bg-background">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="relative bg-gradient-to-br from-primary/10 via-primary/5 to-accent/10 rounded-3xl p-8 lg:p-12 border border-primary/20 overflow-hidden"
                        style="transform: none;">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
                        </div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-accent/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
                        </div>
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center">
                            <div>
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/20 text-primary rounded-full text-sm font-medium mb-4">
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
                                    <?php echo esc_html($settings['badge_text'] ?? ''); ?>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-bold text-secondary mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h3>
                                <p class="text-muted-foreground mb-6 leading-relaxed"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                                <ul class="space-y-3 mb-6">
                                    <?php foreach ($points as $point): ?>
                                        <li class="flex items-center gap-3 text-foreground">
                                            <span class="w-5 h-5 text-primary flex-shrink-0 block">
                                                <?php
                                                if (!empty($point['icon'])) {
                                                    Icons_Manager::render_icon(
                                                        $point['icon'],
                                                        ['aria-hidden' => 'true', 'class' => 'w-5 h-5'],
                                                        'span'
                                                    );
                                                }
                                                ?>
                                            </span>
                                            <span><?php echo esc_html($point['text'] ?? ''); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <a href="<?php echo esc_url($button_url); ?>">
                                    <button
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md px-8 bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg group">
                                        <?php echo esc_html($settings['button_text'] ?? ''); ?>
                                        <span class="w-5 h-5 group-hover:-translate-x-1 transition-transform text-current block">
                                            <?php
                                            if (!empty($settings['button_icon'])) {
                                                Icons_Manager::render_icon(
                                                    $settings['button_icon'],
                                                    ['aria-hidden' => 'true', 'class' => 'w-5 h-5'],
                                                    'span'
                                                );
                                            }
                                            ?>
                                        </span>
                                    </button>
                                </a>
                            </div>
                            <div class="flex justify-center">
                                <div class="relative" style="transform: translateY(-0.387412px);">
                                    <div class="w-40 h-40 md:w-48 md:h-48 bg-gradient-to-br from-primary to-accent rounded-full flex items-center justify-center shadow-lg">
                                        <span class="w-20 h-20 md:w-24 md:h-24 text-white block">
                                            <?php
                                            if (!empty($settings['circle_icon'])) {
                                                Icons_Manager::render_icon(
                                                    $settings['circle_icon'],
                                                    ['aria-hidden' => 'true', 'class' => 'w-20 h-20 md:w-24 md:h-24'],
                                                    'span'
                                                );
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <div class="absolute -top-2 -right-2 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-lg"
                                        style="transform: scale(1.06791);"><?php echo esc_html($settings['percent_text'] ?? ''); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

