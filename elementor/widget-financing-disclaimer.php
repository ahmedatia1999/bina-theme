<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Financing_Disclaimer_Widget extends Widget_Base {

    public function get_name() {
        return 'bina_financing_disclaimer';
    }

    public function get_title() {
        return __('Financing - Disclaimer (Static)', 'bina');
    }

    public function get_icon() {
        return 'eicon-alert';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Disclaimer Content', 'bina'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'bina'),
                'type' => Controls_Manager::TEXT,
                'default' => __('إخلاء مسؤولية', 'bina'),
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => __('Text', 'bina'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 5,
                'default' => __('بناء لا تُعد جهة تمويلية ولا تمارس نشاط التمويل أو الوساطة المالية. يقتصر دورها على تسهيل التواصل وتقديم التوجيه المعلوماتي لمساعدة الأفراد والشركات والمقاولين في الوصول إلى الجهات التمويلية المرخّصة في المملكة. جميع عمليات التمويل تتم مباشرة بين المستخدم والجهة المختارة وفق أنظمتها ولوائح البنك المركزي السعودي.', 'bina'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>

        <section class="py-12 bg-secondary/5">
            <div class="container-custom">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="max-w-4xl mx-auto">
                        <h3 class="text-lg font-bold mb-4 text-foreground"><?php echo esc_html($settings['heading'] ?? ''); ?></h3>
                        <p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html($settings['text'] ?? ''); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

