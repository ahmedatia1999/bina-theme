<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Why_Us_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_why_us';
    }

    public function get_title() {
        return __('Why Us (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-info-circle';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Why Us Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('لماذا نحن', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('لماذا تختار بناء ؟', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('نوفر لك تجربة بناء متكاملة بأعلى معايير الجودة والاحترافية', 'bina'),
            ]
        );

        $card = new Repeater();

        $card->add_control(
            'scheme',
            [
                'label' => __('Color Scheme', 'bina'),
                'type' => Controls_Manager::SELECT,
                'default' => 'blue',
                'options' => [
                    'blue' => __('Blue', 'bina'),
                    'green' => __('Green', 'bina'),
                    'purple' => __('Purple', 'bina'),
                    'amber' => __('Amber', 'bina'),
                ],
            ]
        );

        $card->add_control(
            'icon',
            [
                'label' => __('Card Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-shield-alt',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $card->add_control(
            'icon_color',
            [
                'label' => __('Icon Color (CSS color)', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'rgb(59, 130, 246)',
                'description' => __('Keep the original inline color string, e.g. rgb(59, 130, 246).', 'bina'),
            ]
        );

        $card->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('مقاولون معتمدون', 'bina'),
            ]
        );

        $card->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('نختار لك أفضل المقاولين المعتمدين والموثوقين بعد فحص دقيق وتقييم شامل', 'bina'),
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
                        'scheme' => 'blue',
                        'icon' => [ 'value' => 'fas fa-shield-alt', 'library' => 'fa-solid' ],
                        'icon_color' => 'rgb(59, 130, 246)',
                        'title' => 'مقاولون معتمدون',
                        'text' => 'نختار لك أفضل المقاولين المعتمدين والموثوقين بعد فحص دقيق وتقييم شامل',
                    ],
                    [
                        'scheme' => 'green',
                        'icon' => [ 'value' => 'fas fa-file-signature', 'library' => 'fa-solid' ],
                        'icon_color' => 'rgb(34, 197, 94)',
                        'title' => 'توثيق رقمي آمن',
                        'text' => 'عقود رقمية موثقة قانونياً تحمي حقوقك وتضمن شفافية التعامل',
                    ],
                    [
                        'scheme' => 'purple',
                        'icon' => [ 'value' => 'fas fa-layer-group', 'library' => 'fa-solid' ],
                        'icon_color' => 'rgb(168, 85, 247)',
                        'title' => 'خدمات مساندة متكاملة',
                        'text' => 'نوفر خدمات مساندة شاملة تشمل التمويل، مواد البناء، الإشراف الهندسي، والتسويق العقاري',
                    ],
                    [
                        'scheme' => 'amber',
                        'icon' => [ 'value' => 'fas fa-percent', 'library' => 'fa-solid' ],
                        'icon_color' => 'rgb(245, 158, 11)',
                        'title' => 'أسعار تنافسية',
                        'text' => 'مقارنة عروض تنافسية وشفافة من مقاولين متعددين للحصول على أفضل قيمة',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function scheme_classes($scheme) {
        switch ($scheme) {
            case 'green':
                return [
                    'box_bg' => 'bg-green-500/10',
                    'bar_from' => 'from-green-500',
                    'bar_to' => 'to-green-600',
                ];
            case 'purple':
                return [
                    'box_bg' => 'bg-purple-500/10',
                    'bar_from' => 'from-purple-500',
                    'bar_to' => 'to-purple-600',
                ];
            case 'amber':
                return [
                    'box_bg' => 'bg-amber-500/10',
                    'bar_from' => 'from-amber-500',
                    'bar_to' => 'to-amber-600',
                ];
            case 'blue':
            default:
                return [
                    'box_bg' => 'bg-blue-500/10',
                    'bar_from' => 'from-blue-500',
                    'bar_to' => 'to-blue-600',
                ];
        }
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cards = $settings['cards'] ?? [];
        ?>

                <section class="py-16 md:py-24 bg-background content-visibility-auto">
                    <div class="container-custom">
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="text-center max-w-3xl mx-auto mb-12 md:mb-16 px-4"><span
                                    class="inline-block px-3 py-1 sm:px-4 sm:py-1.5 bg-primary/10 text-primary rounded-full text-xs sm:text-sm font-medium mb-3 md:mb-4"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                                <h2
                                    class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-foreground mb-3 md:mb-4">
                                    <?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                                <p class="text-sm sm:text-base md:text-lg text-muted-foreground"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8 px-2">
                            <?php foreach ($cards as $c): ?>
                                <?php
                                    $scheme = $c['scheme'] ?? 'blue';
                                    $cls = $this->scheme_classes($scheme);
                                    $icon_color = trim($c['icon_color'] ?? '');
                                ?>
                                <div class="" style="opacity: 1; transform: none;">
                                    <div class="relative group h-full">
                                        <div
                                            class="h-full bg-card border border-border rounded-2xl p-6 md:p-8 transition-all duration-300 group-hover:shadow-xl group-hover:border-primary/30">
                                            <div
                                                class="w-14 h-14 md:w-16 md:h-16 rounded-xl <?php echo esc_attr($cls['box_bg']); ?> flex items-center justify-center mb-5 md:mb-6 transition-transform duration-300 group-hover:scale-110">
                                                <span class="w-7 h-7 md:w-8 md:h-8 bg-gradient-to-br <?php echo esc_attr($cls['bar_from']); ?> <?php echo esc_attr($cls['bar_to']); ?> bg-clip-text"
                                                    <?php if ($icon_color): ?>style="color: <?php echo esc_attr($icon_color); ?>;"<?php endif; ?>>
                                                    <?php if (!empty($c['icon']['value'])) Icons_Manager::render_icon($c['icon'], ['aria-hidden' => 'true']); ?>
                                                </span>
                                            </div>
                                            <h3 class="text-lg md:text-xl font-bold text-foreground mb-3"><?php echo esc_html($c['title'] ?? ''); ?>
                                            </h3>
                                            <p class="text-sm md:text-base text-muted-foreground leading-relaxed"><?php echo esc_html($c['text'] ?? ''); ?></p>
                                            <div
                                                class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r <?php echo esc_attr($cls['bar_from']); ?> <?php echo esc_attr($cls['bar_to']); ?> rounded-b-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

        <?php
    }
}

