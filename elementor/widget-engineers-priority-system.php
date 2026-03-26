<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Engineers_Priority_System_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_engineers_priority_system';
    }

    public function get_title() {
        return __('Engineers Priority System (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-sitemap';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Priority System Content', 'bina'),
        ]);

        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('نظام الأولوية', 'bina'),
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('كيف توزع المشاريع؟', 'bina'),
        ]);

        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('المشاريع تُوزع على المهندسين وفق نظام أولوية ذكي يضمن العدالة والكفاءة', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('icon', [
            'label' => __('Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid'],
        ]);
        $repeater->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('المدينة', 'bina'),
        ]);
        $repeater->add_control('text', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('الأولوية للمهندسين في نفس مدينة المشروع', 'bina'),
        ]);

        $this->add_control('items', [
            'label' => __('Priority Factors', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'icon' => ['value' => 'fas fa-map-marker-alt', 'library' => 'fa-solid'],
                    'title' => 'المدينة',
                    'text' => 'الأولوية للمهندسين في نفس مدينة المشروع',
                ],
                [
                    'icon' => ['value' => 'fas fa-bolt', 'library' => 'fa-solid'],
                    'title' => 'سرعة التقديم',
                    'text' => 'كلما كنت أسرع في التقديم، زادت فرصتك',
                ],
                [
                    'icon' => ['value' => 'fas fa-star', 'library' => 'fa-solid'],
                    'title' => 'تقييم المهندس',
                    'text' => 'تقييمات العملاء السابقين تؤثر على أولويتك',
                ],
                [
                    'icon' => ['value' => 'fas fa-heart-pulse', 'library' => 'fa-solid'],
                    'title' => 'مستوى النشاط',
                    'text' => 'المهندسون الأكثر نشاطاً يحصلون على مشاريع أكثر',
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
        <section class="py-20 bg-background">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12">
                        <span class="inline-block px-4 py-1.5 bg-accent/10 text-accent-foreground rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge_text'] ?? ''); ?></span>
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($s['heading'] ?? ''); ?></h2>
                        <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html($s['description'] ?? ''); ?></p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
                    <?php foreach ($items as $item): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="bg-card rounded-xl p-5 border border-border/50 text-center">
                                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-3">
                                    <span class="w-6 h-6 text-primary block">
                                        <?php
                                        if (!empty($item['icon'])) {
                                            Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true', 'class' => 'w-6 h-6']);
                                        }
                                        ?>
                                    </span>
                                </div>
                                <h4 class="font-bold text-secondary mb-1"><?php echo esc_html($item['title'] ?? ''); ?></h4>
                                <p class="text-sm text-muted-foreground"><?php echo esc_html($item['text'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

