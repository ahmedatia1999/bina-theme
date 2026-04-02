<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Financing_How_It_Works_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_financing_how_it_works';
    }

    public function get_title() {
        return __('Financing - How It Works (Static)', 'bina');
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
                'label' => __('How It Works Content', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('كيف تعمل الخدمة؟', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('أربع خطوات بسيطة لبدء مشروعك', 'bina'),
            ]
        );

        $step = new Repeater();

        $step->add_control(
            'number',
            [
                'label' => __('Step Number', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '1',
            ]
        );

        $step->add_control(
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

        $step->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('قدّم طلبك', 'bina'),
            ]
        );

        $step->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('عبر النموذج أدناه', 'bina'),
            ]
        );

        $this->add_control(
            'steps',
            [
                'label' => __('Steps', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $step->get_controls(),
                'default' => [
                    [
                        'number' => '1',
                        'icon' => ['value' => 'fas fa-file-alt', 'library' => 'fa-solid'],
                        'title' => 'قدّم طلبك',
                        'text' => 'عبر النموذج أدناه',
                    ],
                    [
                        'number' => '2',
                        'icon' => ['value' => 'fas fa-search', 'library' => 'fa-solid'],
                        'title' => 'دراسة الطلب',
                        'text' => 'فريقنا يحلل احتياجك',
                    ],
                    [
                        'number' => '3',
                        'icon' => ['value' => 'fas fa-bullseye', 'library' => 'fa-solid'],
                        'title' => 'التوجيه المناسب',
                        'text' => 'نزودك ببيانات الجهة المثالية',
                    ],
                    [
                        'number' => '4',
                        'icon' => ['value' => 'fas fa-circle-check', 'library' => 'fa-solid'],
                        'title' => 'ابدأ مشروعك',
                        'text' => 'التواصل المباشر مع الجهة',
                    ],
                ],
                'title_field' => '{{{ number }}} - {{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $steps = $settings['steps'] ?? [];
        $is_en = function_exists( 'bina_trp_current_lang' ) ? ( bina_trp_current_lang() === 'en' ) : false;
        ?>

        <section class="section-padding">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-lg text-muted-foreground"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    </div>
                </div>
                <div class="grid md:grid-cols-4 gap-6">
                    <?php foreach ($steps as $index => $step): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="relative text-center p-6" style="transform: none;">
                                <div
                                    class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-primary text-white font-bold text-lg flex items-center justify-center shadow-lg">
                                    <?php echo esc_html($step['number'] ?? ''); ?></div>
                                <div class="pt-8 pb-6 px-4 rounded-2xl bg-background border border-border shadow-sm">
                                    <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                                        <span class="w-7 h-7 text-primary block">
                                            <?php
                                            if (!empty($step['icon'])) {
                                                Icons_Manager::render_icon(
                                                    $step['icon'],
                                                    ['aria-hidden' => 'true', 'class' => 'w-7 h-7'],
                                                    'span'
                                                );
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold mb-2 text-foreground"><?php echo esc_html($step['title'] ?? ''); ?></h3>
                                    <p class="text-sm text-muted-foreground"><?php echo esc_html($step['text'] ?? ''); ?></p>
                                </div>
                                <?php if ($index < count($steps) - 1): ?>
                                    <div class="hidden md:block absolute top-1/2 -left-3 transform rotate-180">
                                        <?php if ( $is_en ) : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-arrow-left w-6 h-6 text-primary/40">
                                                <path d="m12 19-7-7 7-7"></path>
                                                <path d="M19 12H5"></path>
                                            </svg>
                                        <?php else : ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-arrow-right w-6 h-6 text-primary/40">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

