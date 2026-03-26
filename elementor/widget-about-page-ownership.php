<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Icons_Manager;
class bina_About_Page_Ownership_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_ownership';}
    public function get_title(){return __('About Ownership (Static)','bina');}
    public function get_icon(){return 'eicon-single-post';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-building','library'=>'fa-solid']]);
        $this->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'الملكية']);
        $this->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'بناء سنتر مملوكة بالكامل لشركة بوابة الترميم ويتم تطويرها وتشغيلها من خلال فريق عمل متخصص في المقاولات والتقنية والعمليات التشغيلية']);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); ?>
    <section class="py-20 bg-muted/30"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="max-w-3xl mx-auto bg-card rounded-2xl p-8 lg:p-12 shadow-card border border-border/50 text-center"><div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6"><?php if(!empty($s['icon'])) Icons_Manager::render_icon($s['icon'],['class'=>'w-8 h-8 text-primary']); ?></div><h2 class="text-2xl md:text-3xl font-bold text-secondary mb-4"><?php echo esc_html($s['title']??''); ?></h2><p class="text-muted-foreground leading-relaxed text-lg"><?php echo esc_html($s['text']??''); ?></p></div></div></div></section>
    <?php }
}

