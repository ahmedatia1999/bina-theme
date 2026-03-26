<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Financing_About_Service_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_financing_about_service';
    }

    public function get_title() {
        return __('Financing - About Service (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('About Service Content', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ما هي خدمة التمويل من بناء', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('هي خدمة مجانية تتيح لك طلب توجيه تمويلي يناسب نوع مشروعك، سواء كنت:', 'bina'),
            ]
        );

        $card = new Repeater();

        $card->add_control(
            'icon',
            [
                'label' => __('Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-house',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $card->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ترغب في ترميم أو بناء منزل جديد', 'bina'),
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
                        'icon' => ['value' => 'fas fa-house', 'library' => 'fa-solid'],
                        'text' => 'ترغب في ترميم أو بناء منزل جديد',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-paint-roller', 'library' => 'fa-solid'],
                        'text' => 'تحتاج تمويلًا لتوسعة أو تشطيب مشروعك',
                    ],
                    [
                        'icon' => ['value' => 'fas fa-helmet-safety', 'library' => 'fa-solid'],
                        'text' => 'مقاولًا يبحث عن دعم مالي لمشروعه القادم',
                    ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->add_control(
            'goal_heading',
            [
                'label' => __('Goal Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('هدفنا الأساسي', 'bina'),
            ]
        );

        $this->add_control(
            'goal_text',
            [
                'label' => __('Goal Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('يقوم فريق بناء بتحليل طلبك، ثم يوجّهك إلى الجهة التمويلية الأنسب من بين الجهات المرخصة في المملكة لتبدأ رحلتك بثقة ووضوح.', 'bina'),
            ]
        );

        $this->add_control(
            'goal_highlight',
            [
                'label' => __('Goal Highlight', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('نوفر لك الوقت ونفتح أمامك كل الخيارات التمويلية… من مكان واحد', 'bina'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cards = $settings['cards'] ?? [];
        ?>

        <section class="section-padding bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-lg text-muted-foreground max-w-3xl mx-auto">
                            <?php echo esc_html($settings['description'] ?? ''); ?>
                        </p>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    <?php foreach ($cards as $card): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="flex items-center gap-4 p-6 rounded-2xl bg-background border border-border shadow-sm"
                                style="transform: none;">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="w-6 h-6 text-primary block">
                                        <?php
                                        if (!empty($card['icon'])) {
                                            Icons_Manager::render_icon(
                                                $card['icon'],
                                                ['aria-hidden' => 'true', 'class' => 'w-6 h-6'],
                                                'span'
                                            );
                                        }
                                        ?>
                                    </span>
                                </div>
                                <p class="text-foreground font-medium"><?php echo esc_html($card['text'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="" style="opacity: 1; transform: none;">
                    <div
                        class="text-center p-8 rounded-2xl bg-gradient-to-r from-primary/10 to-accent/10 border border-primary/20">
                        <h3 class="text-2xl font-bold mb-4 text-foreground"><?php echo esc_html($settings['goal_heading'] ?? ''); ?></h3>
                        <p class="text-lg text-muted-foreground max-w-4xl mx-auto mb-4">
                            <?php echo esc_html($settings['goal_text'] ?? ''); ?>
                        </p>
                        <p class="text-primary font-semibold text-lg"><?php echo esc_html($settings['goal_highlight'] ?? ''); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

