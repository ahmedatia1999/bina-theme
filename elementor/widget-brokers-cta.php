<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

class bina_Brokers_CTA_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_brokers_cta';
    }

    public function get_title() {
        return __('Brokers CTA (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-call-to-action';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('CTA Content', 'bina'),
        ]);

        $this->add_control('top_icon', [
            'label' => __('Top Circle Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-shield-halved', 'library' => 'fa-solid'],
        ]);

        $this->add_control('heading', [
            'label' => __('Heading', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('ابدأ رحلتك معنا اليوم', 'bina'),
        ]);

        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('انضم لآلاف الوسطاء الذين يضاعفون دخلهم مع بناء', 'bina'),
        ]);

        $this->add_control('button_text', [
            'label' => __('Button Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('سجّل الآن كوسيط', 'bina'),
        ]);

        $this->add_control('button_url', [
            'label' => __('Button URL', 'bina'),
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);

        $this->add_control('button_icon', [
            'label' => __('Button Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-arrow-left', 'library' => 'fa-solid'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $button_url = $s['button_url']['url'] ?? '#';
        ?>
        <section class="py-20 bg-gradient-to-br from-primary via-primary/90 to-primary/80 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            </div>
            <div class="container-custom relative z-10">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="text-center max-w-3xl mx-auto">
                        <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-6" style="transform: scale(1.01682);">
                            <span class="w-10 h-10 text-white block">
                                <?php
                                if (!empty($s['top_icon'])) {
                                    Icons_Manager::render_icon($s['top_icon'], ['aria-hidden' => 'true', 'class' => 'w-10 h-10']);
                                }
                                ?>
                            </span>
                        </div>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6"><?php echo esc_html($s['heading'] ?? ''); ?></h2>
                        <p class="text-xl text-white/90 mb-8"><?php echo esc_html($s['description'] ?? ''); ?></p>
                        <a href="<?php echo esc_url($button_url); ?>">
                            <button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-white text-primary hover:bg-white/90 shadow-lg text-lg px-10 py-6 group">
                                <?php echo esc_html($s['button_text'] ?? ''); ?>
                                <span class="w-5 h-5 group-hover:-translate-x-1 transition-transform block text-current">
                                    <?php
                                    if (!empty($s['button_icon'])) {
                                        Icons_Manager::render_icon($s['button_icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5']);
                                    }
                                    ?>
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

