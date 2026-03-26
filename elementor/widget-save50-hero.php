<?php

if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

class bina_Save50_Hero_Widget extends Widget_Base {
    public function get_name() { return 'bina_save50_hero'; }
    public function get_title() { return __('Save 50 Hero (Static)', 'bina'); }
    public function get_icon() { return 'eicon-banner'; }
    public function get_categories() { return ['general']; }

    protected function _register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'bina')]);
        $this->add_control('badge_logo', ['label' => __('Badge Logo', 'bina'), 'type' => Controls_Manager::MEDIA]);
        $this->add_control('badge_text', ['label' => __('Badge Text', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'بالشراكة مع مسامير']);
        $this->add_control('heading_before', ['label' => __('Heading Before Highlight', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'وفر حتى']);
        $this->add_control('heading_highlight', ['label' => __('Heading Highlight', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => '50%']);
        $this->add_control('heading_after', ['label' => __('Heading After Highlight', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'من تكلفة مواد البناء']);
        $this->add_control('description', ['label' => __('Description', 'bina'), 'type' => Controls_Manager::TEXTAREA, 'default' => 'احصل على مواد بناء بأسعار المصنع مباشرة من الصين وتركيا وأوروبا، بالتعاون مع منصة مسامير الرائدة']);
        $this->add_control('primary_icon', ['label' => __('Primary Icon', 'bina'), 'type' => Controls_Manager::ICONS, 'default' => ['value' => 'fas fa-comment-dots','library'=>'fa-solid']]);
        $this->add_control('primary_text', ['label' => __('Primary Text', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'تواصل عبر واتساب']);
        $this->add_control('primary_url', ['label' => __('Primary URL', 'bina'), 'type' => Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->add_control('secondary_icon', ['label' => __('Secondary Icon', 'bina'), 'type' => Controls_Manager::ICONS, 'default' => ['value' => 'fas fa-arrow-up-right-from-square','library'=>'fa-solid']]);
        $this->add_control('secondary_text', ['label' => __('Secondary Text', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'زيارة موقع مسامير msamer.com']);
        $this->add_control('secondary_url', ['label' => __('Secondary URL', 'bina'), 'type' => Controls_Manager::URL, 'default' => ['url' => '#']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $logo = $s['badge_logo']['url'] ?? '';
        ?>
        <section class="relative bg-gradient-hero text-primary-foreground overflow-hidden">
            <div class="absolute opacity-10 pointer-events-none" style="top: 15%; left: 8%; transform: translateY(-1.28213px) rotate(-0.980384deg);"><?php Icons_Manager::render_icon(['value'=>'fas fa-box-open','library'=>'fa-solid'], ['class'=>'w-7 h-7']); ?></div>
            <div class="absolute opacity-10 pointer-events-none" style="top: 25%; right: 10%; transform: translateY(-6.33417px) rotate(-3.94421deg);"><?php Icons_Manager::render_icon(['value'=>'fas fa-hammer','library'=>'fa-solid'], ['class'=>'w-6 h-6']); ?></div>
            <div class="absolute opacity-10 pointer-events-none" style="bottom: 30%; left: 5%; transform: translateY(-12.2706px) rotate(-4.93809deg);"><?php Icons_Manager::render_icon(['value'=>'fas fa-industry','library'=>'fa-solid'], ['class'=>'w-8 h-8']); ?></div>
            <div class="absolute opacity-10 pointer-events-none" style="bottom: 20%; right: 8%; transform: translateY(-14.9403px) rotate(-1.16591deg);"><?php Icons_Manager::render_icon(['value'=>'fas fa-truck','library'=>'fa-solid'], ['class'=>'w-7 h-7']); ?></div>
            <div class="absolute opacity-10 pointer-events-none" style="top: 60%; left: 12%; transform: translateY(-13.7179px) rotate(4.23811deg);"><?php Icons_Manager::render_icon(['value'=>'fas fa-wrench','library'=>'fa-solid'], ['class'=>'w-6 h-6']); ?></div>
            <div class="absolute opacity-10 pointer-events-none" style="top: 40%; right: 5%; transform: translateY(-10.1247px) rotate(-4.91479deg);"><?php Icons_Manager::render_icon(['value'=>'fas fa-building','library'=>'fa-solid'], ['class'=>'w-6 h-6']); ?></div>
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-10 w-32 h-32 border border-primary-foreground/20 rounded-full"></div>
                <div class="absolute bottom-20 right-10 w-48 h-48 border border-primary-foreground/20 rounded-full"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 border border-primary-foreground/10 rounded-full"></div>
            </div>
            <div class="container-custom py-20 md:py-28 lg:py-36 relative z-10">
                <div class="text-center max-w-4xl mx-auto" style="opacity: 1; transform: none;">
                    <div class="inline-flex items-center rounded-full border font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 hover:bg-primary/80 mb-6 bg-primary/20 text-primary-foreground border-primary-foreground/20 text-sm px-4 py-2">
                        <?php if ($logo): ?><img src="<?php echo esc_url($logo); ?>" alt="" class="w-5 h-5 rounded-full inline-block me-2"><?php endif; ?>
                        <?php echo esc_html($s['badge_text'] ?? ''); ?>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        <?php echo esc_html($s['heading_before'] ?? ''); ?> <span class="text-gradient"><?php echo esc_html($s['heading_highlight'] ?? ''); ?></span> <?php echo esc_html($s['heading_after'] ?? ''); ?>
                    </h1>
                    <p class="text-lg md:text-xl text-primary-foreground/80 mb-8 max-w-2xl mx-auto"><?php echo esc_html($s['description'] ?? ''); ?></p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url($s['primary_url']['url'] ?? '#'); ?>"><button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground shadow-glow text-lg px-8"><?php if (!empty($s['primary_icon'])) { Icons_Manager::render_icon($s['primary_icon'], ['class' => 'w-5 h-5']); } ?><?php echo esc_html($s['primary_text'] ?? ''); ?></button></a>
                        <a href="<?php echo esc_url($s['secondary_url']['url'] ?? '#'); ?>"><button class="inline-flex items-center justify-center gap-2 whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border hover:text-accent-foreground h-11 rounded-md bg-transparent border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground/10 text-lg px-8 font-bold"><?php if (!empty($s['secondary_icon'])) { Icons_Manager::render_icon($s['secondary_icon'], ['class' => 'w-5 h-5']); } ?><?php echo esc_html($s['secondary_text'] ?? ''); ?></button></a>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

