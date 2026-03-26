<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Icons_Manager;
class bina_About_Page_CTA_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_cta';}
    public function get_title(){return __('About CTA (Static)','bina');}
    public function get_icon(){return 'eicon-call-to-action';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('top_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-sparkles','library'=>'fa-solid']]);
        $this->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'ابدأ مشروعك اليوم']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'انضم إلى آلاف العملاء الراضين واحصل على أفضل العروض من المقاولين المحترفين']);
        $this->add_control('p_text',['type'=>Controls_Manager::TEXT,'default'=>'أضف مشروعك']); $this->add_control('p_url',['type'=>Controls_Manager::URL,'default'=>['url'=>'#']]); $this->add_control('p_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-arrow-left','library'=>'fa-solid']]);
        $this->add_control('s_text',['type'=>Controls_Manager::TEXT,'default'=>'سجل كمقاول']); $this->add_control('s_url',['type'=>Controls_Manager::URL,'default'=>['url'=>'#']]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); ?>
    <section class="py-20 bg-secondary"><div class="container-custom text-center"><div class="" style="opacity: 1; transform: none;"><?php if(!empty($s['top_icon'])) Icons_Manager::render_icon($s['top_icon'],['class'=>'w-16 h-16 text-primary mx-auto mb-6']); ?><h2 class="text-3xl md:text-4xl font-bold text-white mb-4"><?php echo esc_html($s['title']??''); ?></h2><p class="text-lg text-white/80 mb-8 max-w-2xl mx-auto"><?php echo esc_html($s['desc']??''); ?></p><div class="flex flex-wrap justify-center gap-4"><a href="<?php echo esc_url($s['p_url']['url']??'#'); ?>"><button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 h-11 rounded-md bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg text-lg px-8"><?php echo esc_html($s['p_text']??''); ?><?php if(!empty($s['p_icon'])) Icons_Manager::render_icon($s['p_icon'],['class'=>'w-5 h-5']); ?></button></a><a href="<?php echo esc_url($s['s_url']['url']??'#'); ?>"><button class="inline-flex items-center justify-center gap-2 whitespace-nowrap font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 border bg-background hover:text-accent-foreground h-11 rounded-md border-white/30 text-primary hover:bg-white/10 text-lg px-8"><?php echo esc_html($s['s_text']??''); ?></button></a></div></div></div></section>
    <?php }
}

