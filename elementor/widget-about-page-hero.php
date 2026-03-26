<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager;
class bina_About_Page_Hero_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_hero';}
    public function get_title(){return __('About Page Hero (Static)','bina');}
    public function get_icon(){return 'eicon-banner';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'تعرف علينا']);
        $this->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'من نحن']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'بناء سنتر هي منصة سعودية رقمية مبتكرة، انطلقت من الرياض لتكون نقطة التحول في سوق البناء والترميم والديكور في المملكة']);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); ?>
    <section class="pt-20 md:pt-24 pb-12 bg-background relative overflow-hidden"><div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(hsl(var(--border)) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--border)) 1px, transparent 1px); background-size: 60px 60px;"></div><div class="container-custom relative z-10"><div class="text-center max-w-4xl mx-auto" style="opacity: 1; transform: none;"><span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-6"><?php echo esc_html($s['badge']??''); ?></span><h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-secondary mb-6"><?php echo esc_html($s['title']??''); ?></h1><p class="text-xl text-muted-foreground mb-8 max-w-3xl mx-auto leading-relaxed"><?php echo esc_html($s['desc']??''); ?></p></div></div></section>
    <?php }
}

