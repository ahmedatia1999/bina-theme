<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class bina_Reviews_Swiper_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_reviews_swiper';
    }

    public function get_title() {
        return __('Reviews Swiper (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-testimonial-carousel';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Reviews Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('آراء عملائنا', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ماذا يقول عملاؤنا؟', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('نفخر بثقة آلاف العملاء والمقاولين الذين اختاروا بناء لمشاريعهم', 'bina'),
            ]
        );

        $review = new Repeater();
        $review->add_control(
            'quote',
            [
                'label' => __('Quote', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 4,
                'default' => __('"تجربة رائعة مع بناء وجدت مقاول ممتاز لبناء فيلتي وتم العمل باحترافية عالية. أنصح الجميع بالتعامل معهم."', 'bina'),
            ]
        );
        $review->add_control(
            'initial',
            [
                'label' => __('Avatar Initial', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('أ', 'bina'),
            ]
        );
        $review->add_control(
            'name',
            [
                'label' => __('Name', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('أحمد الغامدي', 'bina'),
            ]
        );
        $review->add_control(
            'meta',
            [
                'label' => __('Meta', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('صاحب فيلا - جدة', 'bina'),
            ]
        );

        $this->add_control(
            'reviews',
            [
                'label' => __('Reviews', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $review->get_controls(),
                'default' => [
                    [
                        'quote' => '"تجربة رائعة مع بناء وجدت مقاول ممتاز لبناء فيلتي وتم العمل باحترافية عالية. أنصح الجميع بالتعامل معهم."',
                        'initial' => 'أ',
                        'name' => 'أحمد الغامدي',
                        'meta' => 'صاحب فيلا - جدة',
                    ],
                    [
                        'quote' => '"المنصة سهلت علي كثيراً في إيجاد مقاول موثوق لترميم منزلي. الأسعار واضحة والتوثيق ممتاز."',
                        'initial' => 'م',
                        'name' => 'محمد العتيبي',
                        'meta' => 'مشروع ترميم - الرياض',
                    ],
                    [
                        'quote' => '"خدمة التصميم الداخلي كانت مميزة جداً. فريق العمل محترف والنتيجة فاقت توقعاتي."',
                        'initial' => 'س',
                        'name' => 'سارة الدوسري',
                        'meta' => 'تصميم داخلي - الدمام',
                    ],
                    [
                        'quote' => '"بناء وفرت علي الوقت والجهد في البحث عن مقاول. نظام الدفع الآمن أعطاني ثقة كبيرة."',
                        'initial' => 'ع',
                        'name' => 'عبدالله الشهري',
                        'meta' => 'بناء ملحق - مكة',
                    ],
                    [
                        'quote' => '"كمقاول، المنصة ساعدتني في الوصول لعملاء جدد وتوسيع أعمالي. نظام التقييم عادل ويشجع على الجودة."',
                        'initial' => 'ف',
                        'name' => 'فهد القحطاني',
                        'meta' => 'مقاول معتمد',
                    ],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'footer_clients_text',
            [
                'label' => __('Footer Clients Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('+3000 عميل راضٍ', 'bina'),
            ]
        );

        $this->add_control(
            'footer_rating_text',
            [
                'label' => __('Footer Rating Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('4.9 متوسط التقييم', 'bina'),
            ]
        );

        $this->end_controls_section();
    }

    private function render_star_svg() {
        ?>
        <svg class="w-4 h-4 fill-primary text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
        </svg>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $reviews = $settings['reviews'] ?? [];
        ?>

                <section class="py-12 md:py-20 bg-muted/30">
                    <div class="container-custom">

                        <!-- العنوان -->
                        <div class="text-center mb-8 md:mb-12 px-4">
                            <span
                                class="inline-block px-3 py-1 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4">
                                <?php echo esc_html($settings['badge_text'] ?? ''); ?>
                            </span>
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                            <p class="text-muted-foreground max-w-2xl mx-auto">
                                <?php echo esc_html($settings['description'] ?? ''); ?>
                            </p>
                        </div>

                        <!-- Swiper -->
                        <div class="relative px-6">
                            <div class="swiper reviews-swiper">
                                <div class="swiper-wrapper">
                                    <?php foreach ($reviews as $r): ?>
                                        <div class="swiper-slide">
                                            <div
                                                class="bg-card rounded-2xl p-6 shadow-card border border-border/50 h-full flex flex-col relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
                                                <div class="flex gap-1 mb-4">
                                                    <?php $this->render_star_svg(); ?>
                                                    <?php $this->render_star_svg(); ?>
                                                    <?php $this->render_star_svg(); ?>
                                                    <?php $this->render_star_svg(); ?>
                                                    <?php $this->render_star_svg(); ?>
                                                </div>
                                                <p class="text-muted-foreground flex-grow mb-6 leading-relaxed">
                                                    <?php echo esc_html($r['quote'] ?? ''); ?>
                                                </p>
                                                <div class="flex items-center gap-3 pt-4 border-t border-border/50">
                                                    <div
                                                        class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-white font-bold text-lg">
                                                        <?php echo esc_html($r['initial'] ?? ''); ?></div>
                                                    <div>
                                                        <h4 class="font-semibold text-secondary"><?php echo esc_html($r['name'] ?? ''); ?></h4>
                                                        <p class="text-sm text-muted-foreground"><?php echo esc_html($r['meta'] ?? ''); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div><!-- end swiper-wrapper -->

                                <!-- أزرار التنقل -->
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>

                            </div><!-- end swiper -->
                        </div>

                        <!-- Footer Stats -->
                        <div
                            class="flex flex-wrap justify-center items-center gap-8 mt-12 pt-8 border-t border-border/50">
                            <div class="flex items-center gap-2">
                                <div class="flex -space-x-2 rtl:space-x-reverse">
                                    <div
                                        class="w-8 h-8 rounded-full border-2 border-background bg-primary flex items-center justify-center text-white text-xs font-bold">
                                        أ</div>
                                    <div
                                        class="w-8 h-8 rounded-full border-2 border-background bg-primary flex items-center justify-center text-white text-xs font-bold">
                                        م</div>
                                    <div
                                        class="w-8 h-8 rounded-full border-2 border-background bg-primary flex items-center justify-center text-white text-xs font-bold">
                                        س</div>
                                    <div
                                        class="w-8 h-8 rounded-full border-2 border-background bg-primary flex items-center justify-center text-white text-xs font-bold">
                                        ع</div>
                                </div>
                                <span class="text-sm text-muted-foreground"><?php echo esc_html($settings['footer_clients_text'] ?? ''); ?></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex gap-0.5">
                                    <?php $this->render_star_svg(); ?>
                                    <?php $this->render_star_svg(); ?>
                                    <?php $this->render_star_svg(); ?>
                                    <?php $this->render_star_svg(); ?>
                                    <?php $this->render_star_svg(); ?>
                                </div>
                                <span class="text-sm text-muted-foreground"><?php echo esc_html($settings['footer_rating_text'] ?? ''); ?></span>
                            </div>
                        </div>

                    </div>
                </section>

        <?php
    }
}

