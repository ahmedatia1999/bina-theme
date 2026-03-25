<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class bina_Partners_Marquee_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_partners_marquee';
    }

    public function get_title() {
        return __('Partners Marquee (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-logo';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Partners Content', 'bina'),
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => __('Badge Text', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('شركاء النجاح', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('نفخر بشراكاتنا الاستراتيجية', 'bina'),
            ]
        );

        $partner = new Repeater();

        $partner->add_control(
            'name',
            [
                'label' => __('Partner Name', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('شريك', 'bina'),
            ]
        );

        $partner->add_control(
            'url',
            [
                'label' => __('Partner URL', 'bina'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                    'nofollow' => true,
                ],
            ]
        );

        $partner->add_control(
            'logo',
            [
                'label' => __('Partner Logo', 'bina'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'partners',
            [
                'label' => __('Partners', 'bina'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $partner->get_controls(),
                'default' => [
                    [
                        'name' => 'عقار',
                        'url' => [ 'url' => 'https://sa.aqar.fm', 'is_external' => true, 'nofollow' => true ],
                    ],
                    [
                        'name' => 'عقار ماب',
                        'url' => [ 'url' => 'https://aqarmap.com.sa', 'is_external' => true, 'nofollow' => true ],
                    ],
                    [
                        'name' => 'عاين',
                        'url' => [ 'url' => 'https://ayen.sa', 'is_external' => true, 'nofollow' => true ],
                    ],
                    [
                        'name' => 'الهيئة السعودية للمقاولين',
                        'url' => [ 'url' => 'https://sca.sa', 'is_external' => true, 'nofollow' => true ],
                    ],
                    [
                        'name' => 'مسامير',
                        'url' => [ 'url' => 'https://msamer.com', 'is_external' => true, 'nofollow' => true ],
                    ],
                    [
                        'name' => 'بروبرتي نت',
                        'url' => [ 'url' => 'https://propertynet.co', 'is_external' => true, 'nofollow' => true ],
                    ],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_control(
            'duplicate_for_marquee',
            [
                'label' => __('Duplicate items for seamless marquee', 'bina'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'bina'),
                'label_off' => __('No', 'bina'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    private function render_partner_item($p) {
        $name = $p['name'] ?? '';
        $url = $p['url']['url'] ?? '#';
        $target = !empty($p['url']['is_external']) ? ' target="_blank"' : '';
        $nofollow = !empty($p['url']['nofollow']) ? ' rel="noopener noreferrer nofollow"' : ' rel="noopener noreferrer"';

        $logo_url = '';
        if (!empty($p['logo']['id'])) {
            $logo_url = wp_get_attachment_image_url($p['logo']['id'], 'full');
        }
        if (!$logo_url && !empty($p['logo']['url'])) {
            $logo_url = $p['logo']['url'];
        }
        if (!$logo_url) $logo_url = Utils::get_placeholder_image_src();
        ?>
        <a href="<?php echo esc_url($url); ?>"<?php echo $target; ?><?php echo $nofollow; ?>
           class="flex-shrink-0 group cursor-pointer" title="<?php echo esc_attr($name); ?>">
            <div
                class="w-40 h-28 md:w-56 md:h-36 lg:w-64 lg:h-40 flex items-center justify-center bg-background rounded-2xl shadow-md border border-border/50 p-4 md:p-6 transition-all duration-300 group-hover:shadow-xl group-hover:border-primary/50 group-hover:scale-105">
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($name); ?>"
                    class="w-full h-full object-contain transition-all duration-300" loading="lazy"
                    decoding="async" width="200" height="120">
            </div>
        </a>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $partners = $settings['partners'] ?? [];

        $render_list = $partners;
        if (($settings['duplicate_for_marquee'] ?? 'yes') === 'yes' && count($partners) > 0) {
            $render_list = array_merge($partners, $partners);
        }
        ?>

                <section class="py-12 md:py-20 bg-muted/30 overflow-hidden marquee-wrapper">
                    <div class="container-custom">
                        <div class="" style="opacity: 1; transform: none;">
                            <div class="text-center mb-10 md:mb-14"><span
                                    class="inline-block px-3 py-1 sm:px-4 sm:py-1.5 bg-primary/10 text-primary rounded-full text-xs sm:text-sm font-medium mb-3"><?php echo esc_html($settings['badge_text'] ?? ''); ?></span>
                                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-foreground"><?php echo esc_html($settings['heading'] ?? ''); ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div
                            class="absolute left-0 top-0 bottom-0 w-16 md:w-32 bg-gradient-to-r from-muted/80 to-transparent z-10">
                        </div>
                        <div
                            class="absolute right-0 top-0 bottom-0 w-16 md:w-32 bg-gradient-to-l from-muted/80 to-transparent z-10">
                        </div>
                        <div class="flex items-center gap-6 md:gap-10 marquee-track"
                            style="transform: translateX(39.445%);">
                            <?php foreach ($render_list as $p) { $this->render_partner_item($p); } ?>
                        </div>
                    </div>
                </section>

        <?php
    }
}

