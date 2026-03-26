<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Contractors_Requirements_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_contractors_requirements';
    }

    public function get_title() {
        return __('Contractors Requirements (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-document-file';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Requirements Content', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('متطلبات التسجيل', 'bina'),
        ]);

        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('لضمان جودة الخدمة وحماية حقوق جميع الأطراف، نحتاج إلى التحقق من المستندات التالية', 'bina'),
        ]);

        $card = new Repeater();
        $card->add_control('number', [
            'label' => __('Number', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => '1',
        ]);
        $card->add_control('icon', [
            'label' => __('Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-file-lines', 'library' => 'fa-solid'],
        ]);
        $card->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('وثائق رسمية', 'bina'),
        ]);
        $card->add_control('point_1', [
            'label' => __('Point 1', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('صورة من السجل التجاري ساري المفعول', 'bina'),
        ]);
        $card->add_control('point_2', [
            'label' => __('Point 2', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('رخصة مزاولة المهنة (في حال التخصص)', 'bina'),
        ]);
        $card->add_control('point_3', [
            'label' => __('Point 3', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('شهادة الزكاة والدخل', 'bina'),
        ]);

        $this->add_control('cards', [
            'label' => __('Cards', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $card->get_controls(),
            'default' => [
                [
                    'number' => '1',
                    'icon' => ['value' => 'fas fa-file-lines', 'library' => 'fa-solid'],
                    'title' => 'وثائق رسمية',
                    'point_1' => 'صورة من السجل التجاري ساري المفعول',
                    'point_2' => 'رخصة مزاولة المهنة (في حال التخصص)',
                    'point_3' => 'شهادة الزكاة والدخل',
                ],
                [
                    'number' => '2',
                    'icon' => ['value' => 'fas fa-user', 'library' => 'fa-solid'],
                    'title' => 'معلومات الملف الشخصي',
                    'point_1' => 'نبذة عن خبراتك ومجالات تخصصك',
                    'point_2' => 'صور لمشاريع سابقة (3 مشاريع على الأقل)',
                    'point_3' => 'معلومات التواصل',
                ],
                [
                    'number' => '3',
                    'icon' => ['value' => 'fas fa-shield-halved', 'library' => 'fa-solid'],
                    'title' => 'التحقق من الهوية',
                    'point_1' => 'إثبات هوية المالك/المسؤول',
                    'point_2' => 'توثيق رقم الهاتف والبريد الإلكتروني',
                    'point_3' => '',
                ],
            ],
            'title_field' => '{{{ number }}} - {{{ title }}}',
        ]);

        $this->add_control('button_text', [
            'label' => __('Button Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('ابدأ عملية التسجيل الآن', 'bina'),
        ]);
        $this->add_control('button_url', [
            'label' => __('Button URL', 'bina'),
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);
        $this->add_control('footer_note', [
            'label' => __('Footer Note', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('سيتم مراجعة طلبك خلال 24-48 ساعة عمل', 'bina'),
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cards = $settings['cards'] ?? [];
        $button_url = $settings['button_url']['url'] ?? '#';
        ?>
        <section class="py-20 bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    </div>
                </div>
                <div class="grid md:grid-cols-3 gap-8 mb-12">
                    <?php foreach ($cards as $card): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 relative overflow-hidden" style="transform: none;">
                                <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg">
                                    <?php echo esc_html($card['number'] ?? ''); ?>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                                    <span class="w-7 h-7 text-primary block">
                                        <?php if (!empty($card['icon'])) { Icons_Manager::render_icon($card['icon'], ['aria-hidden' => 'true', 'class' => 'w-7 h-7'], 'span'); } ?>
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-secondary mb-4"><?php echo esc_html($card['title'] ?? ''); ?></h3>
                                <ul class="space-y-2">
                                    <?php foreach (['point_1','point_2','point_3'] as $pk): ?>
                                        <?php if (!empty($card[$pk])): ?>
                                            <li class="flex items-start gap-2 text-muted-foreground text-sm"><span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0 mt-2"></span><span><?php echo esc_html($card[$pk]); ?></span></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center">
                        <a href="<?php echo esc_url($button_url); ?>" class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg text-lg px-8 py-6"><?php echo esc_html($settings['button_text'] ?? ''); ?></a>
                        <p class="text-sm text-muted-foreground mt-4"><?php echo esc_html($settings['footer_note'] ?? ''); ?></p>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

