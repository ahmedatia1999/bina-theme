<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager;
class bina_Contact_Form_Shortcode_Widget extends Widget_Base {
    public function get_name(){return 'bina_contact_form_shortcode';}
    public function get_title(){return __('Contact Form Shortcode (Static)','bina');}
    public function get_icon(){return 'eicon-form-horizontal';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'أرسل رسالتك']);
        $this->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'نسعد بتواصلك معنا']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXT,'default'=>'سنرد عليك خلال 24 ساعة عمل']);
        $this->add_control('shortcode',['label'=>__('Contact Form 7 Shortcode','bina'),'type'=>Controls_Manager::TEXT,'default'=>'[contact-form-7 id="0" title="Contact Form"]']);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); ?>
    <section class="section-padding bg-background"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center max-w-3xl mx-auto mb-16"><span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge']??''); ?></span><h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html($s['title']??''); ?></h2><p class="text-muted-foreground text-lg"><?php echo esc_html($s['desc']??''); ?></p></div></div><div class="max-w-3xl mx-auto"><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-3xl p-8 lg:p-12 border border-border shadow-card" style="box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgba(0, 0, 0, 0) 0px 0px 0px 0px, rgb(255, 255, 255) 0px 10px 40px -10px;"><div class="bina-contact-cf7"><?php echo do_shortcode($s['shortcode'] ?? ''); ?></div></div></div></div></div></section>
    <?php }
}

