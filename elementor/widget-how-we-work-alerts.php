<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_How_We_Work_Alerts_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_how_we_work_alerts';
    }

    public function get_title() {
        return __('How We Work - Alerts (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-warning';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Alerts Content', 'bina'),
            ]
        );

        $this->add_control(
            'sparkles_icon',
            [
                'label' => __('Top Sparkles Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-sparkles',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'main_icon',
            [
                'label' => __('Main Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-triangle-exclamation',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('تنبيهات هامة', 'bina'),
            ]
        );

        $item = new Repeater();

        $item->add_control(
            'icon',
            [
                'label' => __('List Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle-check',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $item->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('جميع المقاولين خاضعين للتحقق والفحص قبل القبول', 'bina'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => __('Alert Items', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $item->get_controls(),
                'default' => [
                    [
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'text' => 'جميع المقاولين خاضعين للتحقق والفحص قبل القبول',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'text' => 'نوصي دائماً بتوثيق العقود لحماية الطرفين',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'text' => 'تحقق من تقييمات المقاول والأعمال السابقة قبل الاختيار',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'text' => 'لا تدفع أي مبلغ قبل توقيع العقد وتوثيقه',
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items = $settings['items'] ?? [];
        ?>

        <section class="py-24 bg-muted/30">
            <div class="container-custom max-w-4xl">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/50 dark:to-orange-950/30 border-2 border-amber-200 dark:border-amber-800 rounded-3xl p-10 relative overflow-hidden"
                        style="transform: none;">
                        <div class="absolute top-4 right-4" style="transform: rotate(79.83deg);">
                            <span class="w-8 h-8 text-amber-400 block">
                                <?php
                                if (!empty($settings['sparkles_icon'])) {
                                    Icons_Manager::render_icon(
                                        $settings['sparkles_icon'],
                                        ['aria-hidden' => 'true', 'class' => 'w-8 h-8'],
                                        'span'
                                    );
                                }
                                ?>
                            </span>
                        </div>

                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-16 h-16 rounded-2xl bg-amber-500 flex items-center justify-center"
                                style="transform: rotate(-0.804724deg);">
                                <span class="w-8 h-8 text-white block">
                                    <?php
                                    if (!empty($settings['main_icon'])) {
                                        Icons_Manager::render_icon(
                                            $settings['main_icon'],
                                            ['aria-hidden' => 'true', 'class' => 'w-8 h-8'],
                                            'span'
                                        );
                                    }
                                    ?>
                                </span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold text-amber-800 dark:text-amber-200">
                                <?php echo esc_html($settings['heading'] ?? ''); ?>
                            </h3>
                        </div>

                        <ul class="space-y-5">
                            <?php foreach ($items as $item): ?>
                                <li class="flex items-start gap-4" style="opacity: 1; transform: none;">
                                    <div>
                                        <span class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5 block">
                                            <?php
                                            if (!empty($item['icon'])) {
                                                Icons_Manager::render_icon(
                                                    $item['icon'],
                                                    ['aria-hidden' => 'true', 'class' => 'w-6 h-6'],
                                                    'span'
                                                );
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <span class="text-lg text-amber-800 dark:text-amber-200">
                                        <?php echo esc_html($item['text'] ?? ''); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

