<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_About_Page_Stats_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_stats';}
    public function get_title(){return __('About Page Stats (Static)','bina');}
    public function get_icon(){return 'eicon-counter';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $r=new Repeater();
        $r->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-hard-hat','library'=>'fa-solid']]);
        $r->add_control('number',['type'=>Controls_Manager::TEXT,'default'=>'3,000+']);
        $r->add_control('label',['type'=>Controls_Manager::TEXT,'default'=>'مقاول محترف']);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'title_field'=>'{{{ label }}}','default'=>[
            ['icon'=>['value'=>'fas fa-hard-hat','library'=>'fa-solid'],'number'=>'3,000+','label'=>'مقاول محترف'],
            ['icon'=>['value'=>'fas fa-building','library'=>'fa-solid'],'number'=>'3,800+','label'=>'مشروع منجز'],
            ['icon'=>['value'=>'fas fa-star','library'=>'fa-solid'],'number'=>'95%','label'=>'رضا العملاء'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; ?>
    <section class="py-12 bg-background -mt-8 relative z-10"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl shadow-card border border-border p-8 lg:p-10"><div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12"><?php foreach($items as $it): ?><div class="text-center group" style="opacity: 1; transform: none;"><div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-7 h-7 text-primary group-hover:text-white transition-colors']); ?></div><div class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary mb-2"><span class=""><?php echo esc_html($it['number']??''); ?></span></div><p class="text-muted-foreground font-medium"><?php echo esc_html($it['label']??''); ?></p></div><?php endforeach; ?></div></div></div></div></section>
    <?php }
}

