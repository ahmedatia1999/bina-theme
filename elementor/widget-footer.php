<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Utils;

class bina_Footer_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_footer';
    }

    public function get_title() {
        return __('Footer (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-footer';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Footer Content', 'bina'),
            ]
        );

        $this->add_control(
            'logo',
            [
                'label' => __('Logo', 'bina'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'logo_alt',
            [
                'label' => __('Logo Alt', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('بناء  - Bina Service', 'bina'),
            ]
        );

        $this->add_control(
            'about_text',
            [
                'label' => __('About Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __('بناء - نربط بين أصحاب المشاريع والمقاولين المحترفين في المملكة العربية السعودية', 'bina'),
            ]
        );

        $social = new Repeater();
        $social->add_control(
            'label',
            [
                'label' => __('Label', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Facebook', 'bina'),
            ]
        );
        $social->add_control(
            'icon',
            [
                'label' => __('Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
            ]
        );
        $social->add_control(
            'url',
            [
                'label' => __('URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $this->add_control(
            'social_links',
            [
                'label' => __('Social Links', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $social->get_controls(),
                'default' => [
                    [
                        'label' => 'Facebook',
                        'icon' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
                        'url' => [ 'url' => 'https://www.facebook.com/ibinacenter', 'is_external' => true, 'nofollow' => true ],
                    ],
                    [
                        'label' => 'X (Twitter)',
                        'icon' => [ 'value' => 'fab fa-x-twitter', 'library' => 'fa-brands' ],
                        'url' => [ 'url' => 'https://x.com/ibinacenter', 'is_external' => true, 'nofollow' => true ],
                    ],
                    [
                        'label' => 'Instagram',
                        'icon' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ],
                        'url' => [ 'url' => 'https://www.instagram.com/ibinacenter', 'is_external' => true, 'nofollow' => true ],
                    ],
                    [
                        'label' => 'LinkedIn',
                        'icon' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ],
                        'url' => [ 'url' => 'https://www.linkedin.com/company/binacenter/', 'is_external' => true, 'nofollow' => true ],
                    ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $link = new Repeater();
        $link->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('رابط', 'bina'),
            ]
        );
        $link->add_control(
            'url',
            [
                'label' => __('URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [ 'url' => '#' ],
            ]
        );

        $this->add_control(
            'company_title',
            [
                'label' => __('Company Column Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('الشركة', 'bina'),
            ]
        );
        $this->add_control(
            'company_links',
            [
                'label' => __('Company Links', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $link->get_controls(),
                'default' => [
                    [ 'text' => 'من نحن', 'url' => [ 'url' => '/about' ] ],
                    [ 'text' => 'تواصل معنا', 'url' => [ 'url' => '/contact' ] ],
                    [ 'text' => 'كيف نعمل', 'url' => [ 'url' => '/how-we-work' ] ],
                    [ 'text' => 'التمويل', 'url' => [ 'url' => '/financing' ] ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->add_control(
            'quick_title',
            [
                'label' => __('Quick Links Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('روابط سريعة', 'bina'),
            ]
        );
        $this->add_control(
            'quick_links',
            [
                'label' => __('Quick Links', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $link->get_controls(),
                'default' => [
                    [ 'text' => 'للمقاولين', 'url' => [ 'url' => '/contractors' ] ],
                    [ 'text' => 'الوسطاء', 'url' => [ 'url' => '/brokers' ] ],
                    [ 'text' => 'للمهندسين', 'url' => [ 'url' => '/engineers' ] ],
                    [ 'text' => 'موثوق', 'url' => [ 'url' => '/mawthooq' ] ],
                    [ 'text' => 'تنفيذي', 'url' => [ 'url' => '/implement' ] ],
                    [ 'text' => 'أضف مشروعك', 'url' => [ 'url' => '/add-project' ] ],
                    [ 'text' => 'المدونة', 'url' => [ 'url' => '/blog' ] ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $contact = new Repeater();
        $contact->add_control(
            'icon',
            [
                'label' => __('Icon', 'bina'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-phone',
                    'library' => 'fa-solid',
                ],
            ]
        );
        $contact->add_control(
            'small_label',
            [
                'label' => __('Small Label', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('هاتف', 'bina'),
            ]
        );
        $contact->add_control(
            'value',
            [
                'label' => __('Value', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('+966 57 357 2442', 'bina'),
            ]
        );
        $contact->add_control(
            'href',
            [
                'label' => __('Link (href)', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'tel:+966573572442',
            ]
        );
        $contact->add_control(
            'value_dir',
            [
                'label' => __('Value dir attribute', 'bina'),
                'type' => Controls_Manager::SELECT,
                'default' => 'ltr',
                'options' => [
                    '' => __('(none)', 'bina'),
                    'ltr' => __('ltr', 'bina'),
                    'rtl' => __('rtl', 'bina'),
                ],
            ]
        );

        $this->add_control(
            'contact_title',
            [
                'label' => __('Contact Title', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('معلومات التواصل', 'bina'),
            ]
        );
        $this->add_control(
            'contact_items',
            [
                'label' => __('Contact Items', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $contact->get_controls(),
                'default' => [
                    [
                        'icon' => [ 'value' => 'fas fa-phone', 'library' => 'fa-solid' ],
                        'small_label' => 'هاتف',
                        'value' => '+966 57 357 2442',
                        'href' => 'tel:+966573572442',
                        'value_dir' => 'ltr',
                    ],
                    [
                        'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ],
                        'small_label' => 'البريد الإلكتروني',
                        'value' => 'info@binacenter.com',
                        'href' => 'mailto:info@binacenter.com',
                        'value_dir' => '',
                    ],
                    [
                        'icon' => [ 'value' => 'fas fa-message', 'library' => 'fa-solid' ],
                        'small_label' => 'WhatsApp',
                        'value' => '+966 57 357 2442',
                        'href' => 'https://api.whatsapp.com/send?phone=966573572442',
                        'value_dir' => 'ltr',
                    ],
                ],
                'title_field' => '{{{ small_label }}}',
            ]
        );

        $this->add_control(
            'copyright',
            [
                'label' => __('Copyright', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('© 2026 بناء. جميع الحقوق محفوظة.', 'bina'),
            ]
        );

        $this->add_control(
            'bottom_links',
            [
                'label' => __('Bottom Links', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $link->get_controls(),
                'default' => [
                    [ 'text' => 'سياسة الخصوصية', 'url' => [ 'url' => '/privacy' ] ],
                    [ 'text' => 'الشروط والأحكام', 'url' => [ 'url' => '/terms' ] ],
                    [ 'text' => 'خريطة الموقع', 'url' => [ 'url' => '/sitemap' ] ],
                ],
                'title_field' => '{{{ text }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function render_bulleted_link($item) {
        $text = $item['text'] ?? '';
        $url = $item['url']['url'] ?? '#';
        ?>
        <li><a class="text-secondary-foreground/80 hover:text-primary transition-colors inline-flex items-center gap-2"
                href="<?php echo esc_url($url); ?>"><span class="w-1.5 h-1.5 rounded-full bg-primary"></span><?php echo esc_html($text); ?></a></li>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $logo_url = '';
        if (!empty($settings['logo']['id'])) {
            $logo_url = wp_get_attachment_image_url($settings['logo']['id'], 'full');
        }
        if (!$logo_url && !empty($settings['logo']['url'])) {
            $logo_url = $settings['logo']['url'];
        }
        if (!$logo_url) $logo_url = Utils::get_placeholder_image_src();
        ?>

            <footer class="bg-secondary text-secondary-foreground relative z-20 content-visibility-auto">
                <div class="container-custom section-padding">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-10 gap-8 lg:gap-12">
                        <div class="lg:col-span-3 space-y-4">
                            <div class="flex items-center gap-2.5 [&_span]:text-white"><img src="<?php echo esc_url($logo_url); ?>"
                                    alt="<?php echo esc_attr($settings['logo_alt'] ?? ''); ?>" class="h-20 w-auto">
                            </div>
                            <p class="text-secondary-foreground/80 text-sm leading-relaxed"><?php echo esc_html($settings['about_text'] ?? ''); ?></p>
                            <div class="flex gap-3">
                                <?php foreach (($settings['social_links'] ?? []) as $s): ?>
                                    <?php
                                        $href = $s['url']['url'] ?? '#';
                                        $target = !empty($s['url']['is_external']) ? ' target="_blank"' : '';
                                        $rel = ' rel="noopener noreferrer' . (!empty($s['url']['nofollow']) ? ' nofollow' : '') . '"';
                                    ?>
                                    <a href="<?php echo esc_url($href); ?>"<?php echo $target; ?><?php echo $rel; ?>
                                        class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center hover:bg-primary hover:text-primary-foreground transition-all"
                                        aria-label="<?php echo esc_attr($s['label'] ?? ''); ?>">
                                        <span class="w-5 h-5 inline-flex items-center justify-center">
                                            <?php if (!empty($s['icon']['value'])) Icons_Manager::render_icon($s['icon'], ['aria-hidden' => 'true']); ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold mb-4 text-white"><?php echo esc_html($settings['company_title'] ?? ''); ?></h3>
                            <ul class="space-y-2">
                                <?php foreach (($settings['company_links'] ?? []) as $it) { $this->render_bulleted_link($it); } ?>
                            </ul>
                        </div>
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold mb-4 text-white"><?php echo esc_html($settings['quick_title'] ?? ''); ?></h3>
                            <ul class="space-y-2">
                                <?php foreach (($settings['quick_links'] ?? []) as $it) { $this->render_bulleted_link($it); } ?>
                            </ul>
                        </div>
                        <div class="lg:col-span-3">
                            <h3 class="text-lg font-semibold mb-4 text-white"><?php echo esc_html($settings['contact_title'] ?? ''); ?></h3>
                            <ul class="space-y-3">
                                <?php foreach (($settings['contact_items'] ?? []) as $c): ?>
                                    <?php
                                        $href = $c['href'] ?? '#';
                                        $value_dir = $c['value_dir'] ?? '';
                                        $dir_attr = $value_dir ? ' dir="' . esc_attr($value_dir) . '"' : '';
                                        $target = (is_string($href) && strpos($href, 'http') === 0) ? ' target="_blank"' : '';
                                        $rel = $target ? ' rel="noopener noreferrer"' : '';
                                    ?>
                                    <li><a href="<?php echo esc_url($href); ?>"<?php echo $target; ?><?php echo $rel; ?>
                                            class="flex items-center gap-3 text-secondary-foreground/80 hover:text-primary transition-colors">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                                                <span class="w-5 h-5 inline-flex items-center justify-center">
                                                    <?php if (!empty($c['icon']['value'])) Icons_Manager::render_icon($c['icon'], ['aria-hidden' => 'true']); ?>
                                                </span>
                                            </div>
                                            <div><span class="text-xs text-secondary-foreground/60"><?php echo esc_html($c['small_label'] ?? ''); ?></span>
                                                <p class="font-medium"<?php echo $dir_attr; ?>><?php echo esc_html($c['value'] ?? ''); ?></p>
                                            </div>
                                        </a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="border-t border-white/10">
                    <div class="container-custom py-6">
                        <div
                            class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-secondary-foreground/60">
                            <p><?php echo esc_html($settings['copyright'] ?? ''); ?></p>
                            <div class="flex flex-wrap justify-center gap-4">
                                <?php foreach (($settings['bottom_links'] ?? []) as $it): ?>
                                    <a class="hover:text-primary transition-colors" href="<?php echo esc_url($it['url']['url'] ?? '#'); ?>"><?php echo esc_html($it['text'] ?? ''); ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        <?php
    }
}

