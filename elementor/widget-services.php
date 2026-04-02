<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class bina_Services_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_services';
    }

    public function get_title() {
        return __('Services (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Services Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('ماذا نقدم', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('خدماتنا', 'bina'),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => __('نقدم مجموعة شاملة من خدمات البناء والمقاولات', 'bina'),
            ]
        );

        $card = new Repeater();

        $card->add_control(
            'url',
            [
                'label' => __('Card URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $card->add_control(
            'gradient_from',
            [
                'label' => __('Gradient From Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'from-orange-500',
            ]
        );

        $card->add_control(
            'gradient_to',
            [
                'label' => __('Gradient To Class', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'to-amber-500',
            ]
        );

        $card->add_control(
            'icon',
            [
                'label' => __('Card Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-building',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $card->add_control(
            'icon_transform_style',
            [
                'label' => __('Icon Wrapper Style', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'transform: scale(1.00122) rotate(-0.280437deg);',
                'description' => __('Keep the same inline style string from the static HTML (optional).', 'bina'),
            ]
        );

        $card->add_control(
            'title',
            [
                'label' => __('Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('بناء جديد', 'bina'),
            ]
        );

        $card->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('إنشاء المباني السكنية والتجارية من الصفر وفق أعلى المواصفات الهندسية، من الحفر إلى التسليم الكامل.', 'bina'),
            ]
        );

        $card->add_control(
            'more_text',
            [
                'label' => __('More Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('اعرف المزيد', 'bina'),
            ]
        );

        $this->add_control(
            'cards',
            [
                'label' => __('Service Cards', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $card->get_controls(),
                'default' => [
                    [
                        'url' => [ 'url' => '/services/new-construction' ],
                        'gradient_from' => 'from-orange-500',
                        'gradient_to' => 'to-amber-500',
                        'icon' => [ 'value' => 'fas fa-building', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.00122) rotate(-0.280437deg);',
                        'title' => 'بناء جديد',
                        'text' => 'إنشاء المباني السكنية والتجارية من الصفر وفق أعلى المواصفات الهندسية، من الحفر إلى التسليم الكامل.',
                    ],
                    [
                        'url' => [ 'url' => '/services/renovation' ],
                        'gradient_from' => 'from-blue-500',
                        'gradient_to' => 'to-cyan-500',
                        'icon' => [ 'value' => 'fas fa-home', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.00456) rotate(-1.0436deg);',
                        'title' => 'ترميم شامل',
                        'text' => 'إعادة تأهيل وتحديث المنازل والمباني القديمة باستخدام أحدث التقنيات لتحسين الجودة والمظهر العام.',
                    ],
                    [
                        'url' => [ 'url' => '/services/building-materials' ],
                        'gradient_from' => 'from-emerald-500',
                        'gradient_to' => 'to-green-500',
                        'icon' => [ 'value' => 'fas fa-box-open', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.01007) rotate(-2.21309deg);',
                        'title' => 'مواد البناء',
                        'text' => 'شراء مباشر بالتعاون مع شريكنا msamer.com، مع نموذج شراء ذكي يختصر سلسلة الوسطاء ويوفر حتى 50% من التكاليف.',
                    ],
                    [
                        'url' => [ 'url' => '/services/real-estate-development' ],
                        'gradient_from' => 'from-violet-500',
                        'gradient_to' => 'to-purple-500',
                        'icon' => [ 'value' => 'fas fa-handshake', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.01747) rotate(-3.47096deg);',
                        'title' => 'التطوير العقاري',
                        'text' => 'نربطك بمطورين عقاريين للدخول في شراكات تطوير مبنية على دراسة وجدوى واضحة لتحويل أرضك لمشروع مربح.',
                    ],
                    [
                        'url' => [ 'url' => '/services/off-plan-sales' ],
                        'gradient_from' => 'from-sky-500',
                        'gradient_to' => 'to-blue-500',
                        'icon' => [ 'value' => 'far fa-map', 'library' => 'fa-regular' ],
                        'icon_transform_style' => 'transform: scale(1.02593) rotate(-4.43978deg);',
                        'title' => 'عروض البيع على الخارطة',
                        'text' => 'استكشف مشاريع عقارية مقدمة من مطورين معتمدين، وقارنها بسهولة قبل التواصل المباشر معهم.',
                    ],
                    [
                        'url' => [ 'url' => '/services/building-calculator' ],
                        'gradient_from' => 'from-amber-500',
                        'gradient_to' => 'to-yellow-500',
                        'icon' => [ 'value' => 'fas fa-calculator', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.03427) rotate(-4.93169deg);',
                        'title' => 'حاسبة البناء',
                        'text' => 'اعرف تكاليف البناء قبل البدء، حاسبة تعطيك تصورًا واقعيًا للتكلفة وتساعدك تخطط صح من أول خطوة.',
                    ],
                    [
                        'url' => [ 'url' => '/services/project-management' ],
                        'gradient_from' => 'from-teal-500',
                        'gradient_to' => 'to-cyan-500',
                        'icon' => [ 'value' => 'fas fa-clipboard-list', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.0413) rotate(-4.91431deg);',
                        'title' => 'إدارة المشروع العقاري',
                        'text' => 'إشراف هندسي مباشر على مشروعك، فريقنا يتابع ميدانيًا ويحرص على الجودة والالتزام بالجدول الزمني.',
                    ],
                    [
                        'url' => [ 'url' => '/services/inspection-reports' ],
                        'gradient_from' => 'from-rose-500',
                        'gradient_to' => 'to-pink-500',
                        'icon' => [ 'value' => 'fas fa-file-alt', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.04637) rotate(-4.03889deg);',
                        'title' => 'تقارير الفحص',
                        'text' => 'فحص شامل قبل الاستلام يكشف أي ملاحظات، عشان تستلم مشروعك وأنت متأكد إنه على أعلى مستوى.',
                    ],
                    [
                        'url' => [ 'url' => '/services/contract-review' ],
                        'gradient_from' => 'from-indigo-500',
                        'gradient_to' => 'to-blue-500',
                        'icon' => [ 'value' => 'fas fa-balance-scale', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.04922) rotate(-2.20447deg);',
                        'title' => 'مراجعة عقود المقاولات',
                        'text' => 'نراجع عقد المقاولة بدقة ونوضح لك البنود المهمة بالتعاون مع مستشارين قانونيين مختصين.',
                    ],
                    [
                        'url' => [ 'url' => '/financing' ],
                        'gradient_from' => 'from-green-500',
                        'gradient_to' => 'to-emerald-500',
                        'icon' => [ 'value' => 'fas fa-money-bill-wave', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.04999) rotate(0.28185deg);',
                        'title' => 'التمويل',
                        'text' => 'نوفر لك خيارات تمويل مرنة عبر عدد كبير من شركائنا الماليين لتسهيل بدء مشروعك بثقة وراحة.',
                    ],
                    [
                        'url' => [ 'url' => '/services/documentation' ],
                        'gradient_from' => 'from-purple-500',
                        'gradient_to' => 'to-violet-500',
                        'icon' => [ 'value' => 'fas fa-file-signature', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.04878) rotate(2.67752deg);',
                        'title' => 'توثيق العقد قانونيًا',
                        'text' => 'نوثق عقود المشاريع رقميًا من خلال شركائنا القانونيين المعتمدين لضمان الحقوق وسير العمل بشفافية تامة.',
                    ],
                    [
                        'url' => [ 'url' => '/services/inspection' ],
                        'gradient_from' => 'from-red-500',
                        'gradient_to' => 'to-rose-500',
                        'icon' => [ 'value' => 'fas fa-search', 'library' => 'fa-solid' ],
                        'icon_transform_style' => 'transform: scale(1.04544) rotate(4.31463deg);',
                        'title' => 'الفحص والمعاينة',
                        'text' => 'نقدّم فحصًا ومعاينة ميدانية دقيقة قبل وأثناء وبعد التنفيذ لضمان الجودة والتأكد من مطابقة الأعمال للمواصفات.',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $cards = $settings['cards'] ?? [];
        $is_en = function_exists( 'bina_trp_current_lang' ) ? ( bina_trp_current_lang() === 'en' ) : false;
        ?>

                <section id="services" class="py-12 md:py-20 bg-gradient-section scroll-mt-20 content-visibility-auto">
                    <div class="container-custom">
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="text-center max-w-3xl mx-auto mb-8 md:mb-16 px-4"><span
                                    class="inline-block px-3 py-1 sm:px-4 sm:py-1.5 bg-primary/10 text-primary rounded-full text-xs sm:text-sm font-medium mb-3 md:mb-4"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                                <h2
                                    class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-foreground mb-3 md:mb-4">
                                    <?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                                <p class="text-sm sm:text-base md:text-lg text-muted-foreground px-2"><?php echo esc_html($settings['description'] ?? ''); ?></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-8 px-2"
                            style="opacity: 1;">
                            <?php foreach ($cards as $c): ?>
                                <?php
                                    $href = $c['url']['url'] ?? '#';
                                    $from = $c['gradient_from'] ?? 'from-orange-500';
                                    $to = $c['gradient_to'] ?? 'to-amber-500';
                                    $title = $c['title'] ?? '';
                                    $text = $c['text'] ?? '';
                                    $more = $c['more_text'] ?? 'اعرف المزيد';
                                    $icon_style = trim($c['icon_transform_style'] ?? '');
                                ?>
                                <div class="group" style="opacity: 1; transform: none;"><a href="<?php echo esc_url($href); ?>">
                                        <div
                                            class="relative h-full bg-card rounded-xl sm:rounded-2xl p-3 sm:p-4 lg:p-8 border border-border shadow-card hover:shadow-card-hover transition-all duration-300 overflow-hidden group-hover:-translate-y-2">
                                            <div
                                                class="absolute inset-0 bg-gradient-to-br <?php echo esc_attr($from); ?> <?php echo esc_attr($to); ?> opacity-0 group-hover:opacity-5 transition-opacity duration-300">
                                            </div>
                                            <div class="relative w-10 h-10 sm:w-12 sm:h-12 md:w-14 md:h-14 rounded-lg sm:rounded-xl bg-gradient-to-br <?php echo esc_attr($from); ?> <?php echo esc_attr($to); ?> flex items-center justify-center mb-3 sm:mb-4 md:mb-6 mx-auto md:mx-0"
                                                <?php if ($icon_style): ?>style="<?php echo esc_attr($icon_style); ?>"<?php endif; ?>>
                                                <span class="w-5 h-5 sm:w-6 sm:h-6 md:w-7 md:h-7 text-white inline-flex items-center justify-center">
                                                    <?php if (!empty($c['icon']['value'])) Icons_Manager::render_icon($c['icon'], ['aria-hidden' => 'true']); ?>
                                                </span>
                                            </div>
                                            <h3
                                                class="text-sm sm:text-base md:text-xl font-bold text-foreground mb-1.5 sm:mb-2 md:mb-3 group-hover:text-primary transition-colors text-center md:text-start">
                                                <?php echo esc_html($title); ?></h3>
                                            <p
                                                class="text-muted-foreground text-xs sm:text-sm md:text-base leading-relaxed mb-2 md:mb-4 text-center md:text-start hidden sm:block">
                                                <?php echo esc_html($text); ?></p><span
                                                class="hidden md:inline-flex items-center gap-2 text-primary font-medium opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0"><?php echo esc_html($more); ?>
                                                <?php if ( $is_en ) : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-arrow-right w-4 h-4">
                                                        <path d="M5 12h14"></path>
                                                        <path d="m12 5 7 7-7 7"></path>
                                                    </svg>
                                                <?php else : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-arrow-left w-4 h-4">
                                                        <path d="m12 19-7-7 7-7"></path>
                                                        <path d="M19 12H5"></path>
                                                    </svg>
                                                <?php endif; ?>
                                            </span>
                                            <div
                                                class="absolute -bottom-10 -right-10 w-20 sm:w-32 h-20 sm:h-32 bg-gradient-to-br <?php echo esc_attr($from); ?> <?php echo esc_attr($to); ?> rounded-full opacity-10 group-hover:opacity-20 transition-opacity">
                                            </div>
                                        </div>
                                    </a></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

        <?php
    }
}

