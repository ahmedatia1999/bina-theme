<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Contractors_Success_Stories_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_contractors_success_stories';
    }

    public function get_title() {
        return __('Contractors Success Stories (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Success Stories Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('قصص نجاح', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('مقاولون حققوا النجاح معنا', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('اسمع من مقاولين حقيقيين كيف ساعدتهم بناء سنتر في تنمية أعمالهم', 'bina'),
            ]
        );

        $item = new Repeater();

        $item->add_control(
            'quote_icon',
            [
                'label' => __('Quote Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-quote-left',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $item->add_control(
            'review_text',
            [
                'label' => __('Review Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 4,
                'default' => __('"قبل بناء سنتر كنت أعتمد على التوصيات فقط، الآن أستقبل أكثر من 10 طلبات مشاريع شهرياً. المنصة غيرت حياتي المهنية بالكامل!"', 'bina'),
            ]
        );

        $item->add_control(
            'avatar_icon',
            [
                'label' => __('Avatar Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-user',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $item->add_control(
            'contractor_title',
            [
                'label' => __('Contractor Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('مقاول ترميم - الرياض', 'bina'),
            ]
        );

        $item->add_control(
            'verified_icon',
            [
                'label' => __('Verified Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-circle-check',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $item->add_control(
            'verified_text',
            [
                'label' => __('Verified Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('مقاول موثق', 'bina'),
            ]
        );

        $item->add_control(
            'projects_count',
            [
                'label' => __('Projects Count', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => '47',
            ]
        );

        $item->add_control(
            'projects_label',
            [
                'label' => __('Projects Label', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('مشروع منجز', 'bina'),
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => __('Stories', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $item->get_controls(),
                'default' => [
                    [
                        'review_text' => '"قبل بناء سنتر كنت أعتمد على التوصيات فقط، الآن أستقبل أكثر من 10 طلبات مشاريع شهرياً. المنصة غيرت حياتي المهنية بالكامل!"',
                        'contractor_title' => 'مقاول ترميم - الرياض',
                        'verified_text' => 'مقاول موثق',
                        'projects_count' => '47',
                        'projects_label' => 'مشروع منجز',
                    ],
                    [
                        'review_text' => '"أفضل قرار اتخذته هو التسجيل في بناء سنتر. خلال 6 أشهر ضاعفت دخلي 3 مرات وبنيت سمعة قوية من خلال التقييمات."',
                        'contractor_title' => 'مقاول بناء فلل - جدة',
                        'verified_text' => 'مقاول موثق',
                        'projects_count' => '32',
                        'projects_label' => 'مشروع منجز',
                    ],
                    [
                        'review_text' => '"كمقاول مبتدئ، ساعدتني المنصة في بناء اسمي وسمعتي من الصفر. الآن لدي عملاء دائمون يطلبونني بالاسم!"',
                        'contractor_title' => 'مقاول صيانة - مكة',
                        'verified_text' => 'مقاول موثق',
                        'projects_count' => '89',
                        'projects_label' => 'مشروع منجز',
                    ],
                ],
                'title_field' => '{{{ contractor_title }}}',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Bottom Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ابدأ عملية التسجيل الآن', 'bina'),
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label' => __('Bottom Button URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'footer_note',
            [
                'label' => __('Bottom Note', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('سيتم مراجعة طلبك خلال 24-48 ساعة عمل', 'bina'),
            ]
        );

        $this->end_controls_section();
    }

    private function render_star_icon() {
        ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-star w-5 h-5 fill-primary text-primary">
            <path
                d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z">
            </path>
        </svg>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $items = $settings['items'] ?? [];
        $button_url = $settings['button_url']['url'] ?? '#';
        ?>

        <section class="py-20 bg-muted/30">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mb-12"><span
                            class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                        <h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                        <p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <?php foreach ($items as $item): ?>
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 relative overflow-hidden">
                                <div class="absolute top-4 left-4 opacity-10">
                                    <span class="w-16 h-16 text-primary block">
                                        <?php
                                        if (!empty($item['quote_icon'])) {
                                            Icons_Manager::render_icon(
                                                $item['quote_icon'],
                                                ['aria-hidden' => 'true', 'class' => 'w-16 h-16'],
                                                'span'
                                            );
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="flex gap-1 mb-4">
                                    <?php $this->render_star_icon(); ?>
                                    <?php $this->render_star_icon(); ?>
                                    <?php $this->render_star_icon(); ?>
                                    <?php $this->render_star_icon(); ?>
                                    <?php $this->render_star_icon(); ?>
                                </div>
                                <p class="text-muted-foreground mb-6 leading-relaxed text-lg relative z-10"><?php echo esc_html($item['review_text'] ?? ''); ?></p>
                                <div class="flex items-center justify-between pt-4 border-t border-border/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-white">
                                            <span class="w-6 h-6 text-white block">
                                                <?php
                                                if (!empty($item['avatar_icon'])) {
                                                    Icons_Manager::render_icon(
                                                        $item['avatar_icon'],
                                                        ['aria-hidden' => 'true', 'class' => 'w-6 h-6'],
                                                        'span'
                                                    );
                                                }
                                                ?>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-secondary"><?php echo esc_html($item['contractor_title'] ?? ''); ?></p>
                                            <p class="text-sm text-muted-foreground flex items-center gap-1">
                                                <span class="w-4 h-4 text-green-500 block">
                                                    <?php
                                                    if (!empty($item['verified_icon'])) {
                                                        Icons_Manager::render_icon(
                                                            $item['verified_icon'],
                                                            ['aria-hidden' => 'true', 'class' => 'w-4 h-4'],
                                                            'span'
                                                        );
                                                    }
                                                    ?>
                                                </span>
                                                <?php echo esc_html($item['verified_text'] ?? ''); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-primary"><?php echo esc_html($item['projects_count'] ?? ''); ?></p>
                                        <p class="text-xs text-muted-foreground"><?php echo esc_html($item['projects_label'] ?? ''); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center mt-12">
                        <?php if (!empty($button_url) && $button_url !== '#'): ?>
                            <a href="<?php echo esc_url($button_url); ?>">
                        <?php endif; ?>
                        <button
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg text-lg px-8 py-6"><?php echo esc_html($settings['button_text'] ?? ''); ?></button>
                        <?php if (!empty($button_url) && $button_url !== '#'): ?>
                            </a>
                        <?php endif; ?>
                        <p class="text-sm text-muted-foreground mt-4"><?php echo esc_html($settings['footer_note'] ?? ''); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

