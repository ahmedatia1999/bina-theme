<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class bina_Header_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_header';
    }

    public function get_title() {
        return __('Header (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-header';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Header Content', 'bina'),
            ]
        );

        $this->add_control(
            'logo_link',
            [
                'label' => __('Logo Link', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => home_url('/'),
                ],
            ]
        );

        $this->add_control(
            'logo_image',
            [
                'label' => __('Logo Image', 'bina'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'label',
            [
                'label' => __('Label', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('الرئيسية', 'bina'),
            ]
        );
        $repeater->add_control(
            'url',
            [
                'label' => __('URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'menu_items',
            [
                'label' => __('Menu Items', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'label' => 'الرئيسية', 'url' => [ 'url' => 'index.html' ] ],
                    [ 'label' => 'كيف نعمل', 'url' => [ 'url' => 'how-we-work.html' ] ],
                    [ 'label' => 'خدماتنا', 'url' => [ 'url' => 'our-services.html' ] ],
                    [ 'label' => 'التمويل', 'url' => [ 'url' => 'financing.html' ] ],
                    [ 'label' => 'للمقاولين', 'url' => [ 'url' => 'contractors.html' ] ],
                    [ 'label' => 'الوسطاء', 'url' => [ 'url' => 'brokers.html' ] ],
                    [ 'label' => 'للمهندسين', 'url' => [ 'url' => 'engineers.html' ] ],
                    [ 'label' => 'وفر 50%', 'url' => [ 'url' => 'save-50.html' ] ],
                ],
                'title_field' => '{{{ label }}}',
            ]
        );

        $this->add_control(
            'lang_button_text',
            [
                'label' => __('Language Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => 'EN',
            ]
        );

        $this->add_control(
            'login_text',
            [
                'label' => __('Login Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('تسجيل الدخول', 'bina'),
            ]
        );
        $this->add_control(
            'login_url',
            [
                'label' => __('Login URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/login',
                ],
            ]
        );

        $this->add_control(
            'add_project_text',
            [
                'label' => __('Add Project Button Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('أضف مشروعك', 'bina'),
            ]
        );
        $this->add_control(
            'add_project_url',
            [
                'label' => __('Add Project URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/add-project',
                ],
            ]
        );

        $this->add_control(
            'mobile_login_text',
            [
                'label' => __('Mobile Login Link Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('→ تسجيل الدخول', 'bina'),
            ]
        );
        $this->add_control(
            'mobile_login_url',
            [
                'label' => __('Mobile Login URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/login',
                ],
            ]
        );
        $this->add_control(
            'mobile_add_text',
            [
                'label' => __('Mobile Add Project Link Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('أضف مشروعك', 'bina'),
            ]
        );
        $this->add_control(
            'mobile_add_url',
            [
                'label' => __('Mobile Add Project URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '/add-project',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $logo_url = !empty($settings['logo_image']['id'])
            ? wp_get_attachment_image_url($settings['logo_image']['id'], 'full')
            : ($settings['logo_image']['url'] ?? '');
        if (!$logo_url) $logo_url = Utils::get_placeholder_image_src();

        $logo_link = $settings['logo_link']['url'] ?? home_url('/');
        $login_link = $settings['login_url']['url'] ?? '#';
        $add_project_link = $settings['add_project_url']['url'] ?? '#';
        $mobile_login_link = $settings['mobile_login_url']['url'] ?? '#';
        $mobile_add_link = $settings['mobile_add_url']['url'] ?? '#';
        ?>

        <header id="header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent py-4">
            <div class="container-custom">
                <nav class="flex items-center justify-between">
                    <a class="relative z-10" href="<?php echo esc_url($logo_link); ?>">
                        <div class="flex items-center gap-2.5 ">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="" class="h-20 w-auto">
                        </div>
                    </a>

                    <div class="hidden lg:flex items-center gap-8">
                        <?php foreach ( ($settings['menu_items'] ?? []) as $item ): ?>
                            <a class="relative font-medium transition-colors hover:text-primary text-foreground"
                               href="<?php echo esc_url($item['url']['url'] ?? '#'); ?>">
                                <?php echo esc_html($item['label'] ?? ''); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <div class="flex items-center gap-2 md:gap-3">
                        <button class="flex items-center gap-1.5 px-2 md:px-3 py-2 rounded-lg transition-all hover:bg-muted text-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-globe w-4 h-4">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                                <path d="M2 12h20"></path>
                            </svg>
                            <span class="text-sm font-medium"><?php echo esc_html($settings['lang_button_text']); ?></span>
                        </button>

                        <div class="hidden lg:block">
                            <a href="<?php echo esc_url($login_link); ?>"
                               class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background hover:text-accent-foreground h-10 px-4 py-2 border-primary/30 text-primary hover:bg-primary/10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-in w-4 h-4">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                    <polyline points="10 17 15 12 10 7"></polyline>
                                    <line x1="15" x2="3" y1="12" y2="12"></line>
                                </svg>
                                <?php echo esc_html($settings['login_text']); ?>
                            </a>
                        </div>

                        <div class="hidden lg:block">
                            <a href="<?php echo esc_url($add_project_link); ?>"
                               class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-10 px-4 py-2 bg-primary hover:bg-primary/90 text-primary-foreground shadow-glow">
                                <?php echo esc_html($settings['add_project_text']); ?>
                            </a>
                        </div>

                        <button class="hamburger lg:hidden p-2 rounded-lg hover:bg-muted transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu w-6 h-6">
                                <line x1="4" x2="20" y1="12" y2="12"></line>
                                <line x1="4" x2="20" y1="6" y2="6"></line>
                                <line x1="4" x2="20" y1="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </nav>
            </div>
        </header>

        <div id="mobile-overlay"></div>

        <div class="mobile-menu">
            <div class="menu-header">
                <button class="close">✕</button>
                <img src="<?php echo esc_url($logo_url); ?>" alt="" style="height: 80px;">
            </div>

            <ul class="main_menu">
                <?php foreach ( ($settings['menu_items'] ?? []) as $item ): ?>
                    <li>
                        <a href="<?php echo esc_url($item['url']['url'] ?? '#'); ?>">
                            <?php echo esc_html($item['label'] ?? ''); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="menu-footer">
                <a href="<?php echo esc_url($mobile_login_link); ?>" class="btn-login"><?php echo esc_html($settings['mobile_login_text']); ?></a>
                <a href="<?php echo esc_url($mobile_add_link); ?>" class="btn-add"><?php echo esc_html($settings['mobile_add_text']); ?></a>
            </div>
        </div>

        <?php
    }
}

