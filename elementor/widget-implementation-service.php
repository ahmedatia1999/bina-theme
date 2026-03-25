<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Implementation_Service_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_implementation_service';
    }

    public function get_title() {
        return __('Implementation Service (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-call-to-action';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Implementation Service Content', 'bina'),
            ]
        );

        $this->add_control(
            'sparkles_icon',
            [
                'label' => __('Sparkles Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-sparkles',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'pill_text',
            [
                'label' => __('Pill Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('خدمة تنفيذي', 'bina'),
            ]
        );

        $this->add_control(
            'heading_text',
            [
                'label' => __('Heading (Main)', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('الحل المتكامل', 'bina'),
            ]
        );

        $this->add_control(
            'heading_highlight',
            [
                'label' => __('Heading (Highlight)', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('لإدارة مشاريع التطوير العقاري', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 4,
                'default' => __('جهة واحدة تدير مشروعك بالكامل من أول مخطط حتى تسليم المفتاح. مصمم خصيصاً للمشاريع الكبيرة التي تحتاج تنسيقاً مع عدة مقاولين.', 'bina'),
            ]
        );

        $this->add_control(
            'primary_button_text',
            [
                'label' => __('Primary Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('اعرف المزيد', 'bina'),
            ]
        );

        $this->add_control(
            'primary_button_url',
            [
                'label' => __('Primary Button URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/implement',
                ],
            ]
        );

        $this->add_control(
            'secondary_button_text',
            [
                'label' => __('Secondary Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('استشارة مجانية', 'bina'),
            ]
        );

        $this->add_control(
            'secondary_button_url',
            [
                'label' => __('Secondary Button URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => 'https://api.whatsapp.com/send?phone=966573572442&text=أريد استشارة مجانية عن خدمة تنفيذي',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $feature = new Repeater();

        $feature->add_control(
            'icon',
            [
                'label' => __('Feature Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-building',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $feature->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('إدارة متكاملة', 'bina'),
            ]
        );

        $feature->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('جهة واحدة تدير كل المقاولين والمهندسين', 'bina'),
            ]
        );

        $this->add_control(
            'features',
            [
                'label' => __('Feature Cards', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $feature->get_controls(),
                'default' => [
                    [
                        'icon' => [ 'value' => 'fas fa-building', 'library' => 'fa-solid' ],
                        'title' => 'إدارة متكاملة',
                        'text' => 'جهة واحدة تدير كل المقاولين والمهندسين',
                    ],
                    [
                        'icon' => [ 'value' => 'fas fa-users', 'library' => 'fa-solid' ],
                        'title' => '+3000 مقاول',
                        'text' => 'شبكة واسعة من المقاولين المعتمدين',
                    ],
                    [
                        'icon' => [ 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ],
                        'title' => 'ضمان الجودة',
                        'text' => 'إشراف هندسي وتقارير مستمرة',
                    ],
                    [
                        'icon' => [ 'value' => 'far fa-check-circle', 'library' => 'fa-regular' ],
                        'title' => 'تسليم مضمون',
                        'text' => 'مشروع جاهز مطابق للمخططات',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $features = $settings['features'] ?? [];

        $primary_url = $settings['primary_button_url']['url'] ?? '#';
        $secondary_url = $settings['secondary_button_url']['url'] ?? '#';

        $secondary_target = !empty($settings['secondary_button_url']['is_external']) ? ' target="_blank"' : '';
        $secondary_rel = ' rel="noopener noreferrer' . (!empty($settings['secondary_button_url']['nofollow']) ? ' nofollow' : '') . '"';
        ?>

                <section
                    class="py-16 md:py-24 bg-gradient-to-br from-primary/5 via-background to-accent/5 relative overflow-hidden content-visibility-auto">
                    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
                    <div class="container-custom relative z-10">
                        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                            <div class="" style="opacity: 1; transform: none;">
                                <div class="space-y-6">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 text-primary inline-flex items-center justify-center">
                                            <?php if (!empty($settings['sparkles_icon']['value'])) Icons_Manager::render_icon($settings['sparkles_icon'], ['aria-hidden' => 'true']); ?>
                                        </span><span
                                            class="inline-block px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-semibold"><?php echo esc_html($settings['pill_text'] ?? ''); ?></span></div>
                                    <h2
                                        class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-foreground leading-tight">
                                        <?php echo esc_html($settings['heading_text'] ?? ''); ?> <span class="text-primary"><?php echo esc_html($settings['heading_highlight'] ?? ''); ?></span>
                                    </h2>
                                    <p class="text-base md:text-lg text-muted-foreground leading-relaxed"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                                    <div class="flex flex-wrap gap-3 pt-2"><a
                                            class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 bg-primary text-primary-foreground hover:bg-primary/90 h-11 rounded-md px-8 gap-2"
                                            href="<?php echo esc_url($primary_url); ?>"><?php echo esc_html($settings['primary_button_text'] ?? ''); ?><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="lucide lucide-arrow-left w-4 h-4">
                                                <path d="m12 19-7-7 7-7"></path>
                                                <path d="M19 12H5"></path>
                                            </svg></a><a
                                            href="<?php echo esc_url($secondary_url); ?>"<?php echo $secondary_target; ?><?php echo $secondary_rel; ?>
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 rounded-md px-8"><?php echo esc_html($settings['secondary_button_text'] ?? ''); ?></a></div>
                                </div>
                            </div>
                            <div class="" style="opacity: 1; transform: none;">
                                <div class="grid grid-cols-2 gap-4">
                                    <?php foreach ($features as $f): ?>
                                        <div
                                            class="bg-card border rounded-2xl p-5 hover:shadow-lg transition-all hover:-translate-y-1 group">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-primary-foreground transition-colors">
                                                <span class="w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors inline-flex items-center justify-center">
                                                    <?php if (!empty($f['icon']['value'])) Icons_Manager::render_icon($f['icon'], ['aria-hidden' => 'true']); ?>
                                                </span>
                                            </div>
                                            <h3 class="font-bold text-foreground mb-1"><?php echo esc_html($f['title'] ?? ''); ?></h3>
                                            <p class="text-sm text-muted-foreground"><?php echo esc_html($f['text'] ?? ''); ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

        <?php
    }
}

