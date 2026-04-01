<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Engineers_Why_Join_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_engineers_why_join';
    }

    public function get_title() {
        return __('Engineers Why Join (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Why Join Content', 'bina'),
        ]);

        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('لماذا تنضم؟', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('لماذا ينضم المهندسون إلى بناء ؟', 'bina'),
        ]);

        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 3,
            'default' => __('في كل مدينة هناك مشاريع ترميم وبناء تبحث عن إشراف هندسي وتنظيم احترافي. بناء يربطك مباشرة بمشاريع جاهزة للتنفيذ.', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label' => __('Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-briefcase', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('icon_rotate', [
            'label' => __('Icon Rotate (deg)', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => '-2.80511',
        ]);
        $repeater->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مشاريع جاهزة وليست افتراضية', 'bina'),
        ]);
        $repeater->add_control('text', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('تصلك مشاريع حقيقية من عملاء جادين يبحثون عن تنفيذ فعلي — لا إعلانات وهمية', 'bina'),
        ]);

        $this->add_control('items', [
            'label' => __('Cards', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'icon' => ['value' => 'fas fa-briefcase', 'library' => 'fa-solid'],
                    'icon_rotate' => '-2.80511',
                    'title' => 'مشاريع جاهزة وليست افتراضية',
                    'text' => 'تصلك مشاريع حقيقية من عملاء جادين يبحثون عن تنفيذ فعلي — لا إعلانات وهمية',
                ],
                [
                    'icon' => ['value' => 'fas fa-dollar-sign', 'library' => 'fa-solid'],
                    'icon_rotate' => '-4.40803',
                    'title' => 'دخل إضافي بجانب عملك',
                    'text' => 'اربح عمولة على كل مشروع تُغلقه دون الحاجة لترك وظيفتك الأساسية',
                ],
                [
                    'icon' => ['value' => 'fas fa-network-wired', 'library' => 'fa-solid'],
                    'icon_rotate' => '-4.99804',
                    'title' => 'توسّع شبكتك المهنية',
                    'text' => 'تواصل مع مقاولين وعملاء ومهندسين آخرين وابنِ علاقات مهنية قوية',
                ],
                [
                    'icon' => ['value' => 'fas fa-award', 'library' => 'fa-solid'],
                    'icon_rotate' => '-4.09271',
                    'title' => 'عزّز سمعتك كمستشار موثوق',
                    'text' => 'كل مشروع ناجح يضيف لسجلك المهني ويعزز مكانتك في السوق',
                ],
                [
                    'icon' => ['value' => 'fas fa-shield-halved', 'library' => 'fa-solid'],
                    'icon_rotate' => '-1.10152',
                    'title' => 'لا مسؤولية تعاقدية مباشرة',
                    'text' => 'المنصة أداة ربط فقط — لا تتحمل مسؤولية تنفيذ المشروع أو العقود مع المقاولين',
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $items = $s['items'] ?? [];
        ?>
        <section class="py-20 bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge_text'] ?? ''); ?></span>
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($s['heading'] ?? ''); ?></h2>
                        <p class="text-muted-foreground max-w-3xl mx-auto text-lg"><?php echo esc_html($s['description'] ?? ''); ?></p>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($items as $item): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 h-full group" style="transform: none;">
                                <div class="w-14 h-14 rounded-xl bg-muted/50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform"
                                     style="transform: rotate(<?php echo esc_attr($item['icon_rotate'] ?? '-2.80511'); ?>deg);">
                                    <span class="w-7 h-7 text-black block">
                                        <?php
                                        if (!empty($item['icon'])) {
                                            Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => 'w-7 h-7 text-black']);
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold text-secondary mb-2"><?php echo esc_html($item['title'] ?? ''); ?></h3>
                                <p class="text-muted-foreground text-sm"><?php echo esc_html($item['text'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

