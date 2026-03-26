<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Brokers_About_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_brokers_about';
    }

    public function get_title() {
        return __('Brokers About (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('About Section Content', 'bina'),
        ]);

        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('تعرف علينا', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('ما هي بناء', 'bina'),
        ]);

        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 3,
            'default' => __('بناء منصة سعودية ذكية تربط أصحاب المشاريع بالمقاولين والموردين المعتمدين، لتوفر لهم تجربة بناء وترميم احترافية ومضمونة.', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label' => __('Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-users', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مقاولون معتمدون', 'bina'),
        ]);
        $repeater->add_control('text', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('شبكة واسعة من المقاولين المعتمدين والموثوقين', 'bina'),
        ]);

        $this->add_control('cards', [
            'label' => __('Cards', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'icon' => ['value' => 'fas fa-users', 'library' => 'fa-solid'],
                    'title' => 'مقاولون معتمدون',
                    'text' => 'شبكة واسعة من المقاولين المعتمدين والموثوقين',
                ],
                [
                    'icon' => ['value' => 'fas fa-building', 'library' => 'fa-solid'],
                    'title' => 'مكاتب هندسية',
                    'text' => 'مكاتب هندسية متخصصة لضمان جودة التنفيذ',
                ],
                [
                    'icon' => ['value' => 'fas fa-credit-card', 'library' => 'fa-solid'],
                    'title' => 'تمويل ميسر',
                    'text' => 'خيارات تمويل مرنة تناسب جميع المشاريع',
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

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
                        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-muted-foreground max-w-3xl mx-auto text-lg"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <?php foreach ($cards as $card): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 text-center h-full" style="transform: none;">
                                <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                                    <span class="w-8 h-8 text-primary block">
                                        <?php
                                        if (!empty($card['icon'])) {
                                            Icons_Manager::render_icon($card['icon'], ['aria-hidden' => 'true', 'class' => 'w-8 h-8']);
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-secondary mb-3"><?php echo esc_html($card['title'] ?? ''); ?></h3>
                                <p class="text-muted-foreground"><?php echo esc_html($card['text'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}
