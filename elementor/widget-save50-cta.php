<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Icons_Manager;
class bina_Save50_CTA_Widget extends Widget_Base {
    public function get_name(){return 'bina_save50_cta';}
    public function get_title(){return __('Save50 CTA (Static)','bina');}
    public function get_icon(){return 'eicon-call-to-action';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('heading',['type'=>Controls_Manager::TEXT,'default'=>'ابدأ التوفير اليوم!']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'تواصل معنا الآن واحصل على عرض أسعار لمواد البناء لمشروعك']);
        $this->add_control('p_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-comment-dots','library'=>'fa-solid']]);
        $this->add_control('p_text',['type'=>Controls_Manager::TEXT,'default'=>'تواصل عبر واتساب']);
        $this->add_control('p_url',['type'=>Controls_Manager::URL,'default'=>['url'=>'#']]);
        $this->add_control('s_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-arrow-up-right-from-square','library'=>'fa-solid']]);
        $this->add_control('s_text',['type'=>Controls_Manager::TEXT,'default'=>'زيارة موقع مسامير msamer.com']);
        $this->add_control('s_url',['type'=>Controls_Manager::URL,'default'=>['url'=>'#']]);
        $this->add_control('note',['type'=>Controls_Manager::TEXT,'default'=>'الخدمة بالشراكة مع منصة مسامير (msamer.com) - أكبر منصة لمواد البناء في المملكة']);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); ?>
    <section class="section-padding bg-gradient-hero text-primary-foreground"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center max-w-3xl mx-auto"><h2 class="text-3xl md:text-4xl font-bold mb-6"><?php echo esc_html($s['heading']??''); ?></h2><p class="text-primary-foreground/80 text-lg mb-8"><?php echo esc_html($s['desc']??''); ?></p><div class="flex flex-col sm:flex-row gap-4 justify-center"><a href="<?php echo esc_url($s['p_url']['url']??'#'); ?>"><button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground text-lg px-8"><?php if(!empty($s['p_icon'])) Icons_Manager::render_icon($s['p_icon'],['class'=>'w-5 h-5']); ?><?php echo esc_html($s['p_text']??''); ?></button></a><a href="<?php echo esc_url($s['s_url']['url']??'#'); ?>"><button class="inline-flex items-center justify-center gap-2 whitespace-nowrap ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border hover:text-accent-foreground h-11 rounded-md bg-transparent border-primary-foreground/30 text-primary-foreground hover:bg-primary-foreground/10 text-lg px-8 font-bold"><?php if(!empty($s['s_icon'])) Icons_Manager::render_icon($s['s_icon'],['class'=>'w-5 h-5']); ?><?php echo esc_html($s['s_text']??''); ?></button></a></div><p class="mt-6 text-primary-foreground/60 text-sm"><?php echo esc_html($s['note']??''); ?></p></div></div></div></section>
    <?php }
}

