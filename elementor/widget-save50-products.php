<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_Save50_Products_Widget extends Widget_Base {
    public function get_name(){return 'bina_save50_products';}
    public function get_title(){return __('Save50 Products (Static)','bina');}
    public function get_icon(){return 'eicon-gallery-grid';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'فئات المنتجات']);
        $this->add_control('heading',['type'=>Controls_Manager::TEXT,'default'=>'مواد بناء متنوعة']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'نوفر جميع أنواع مواد البناء التي يحتاجها مشروعك من المصدر مباشرة']);
        $r=new Repeater();
        $r->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-industry','library'=>'fa-solid']]);
        $r->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'الحديد والصلب']);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'title_field'=>'{{{ title }}}','default'=>[
            ['icon'=>['value'=>'fas fa-industry','library'=>'fa-solid'],'title'=>'الحديد والصلب'],
            ['icon'=>['value'=>'fas fa-boxes-stacked','library'=>'fa-solid'],'title'=>'الإسمنت'],
            ['icon'=>['value'=>'fas fa-hammer','library'=>'fa-solid'],'title'=>'البلوك والطوب'],
            ['icon'=>['value'=>'fas fa-box-open','library'=>'fa-solid'],'title'=>'الخرسانة الجاهزة'],
            ['icon'=>['value'=>'fas fa-eye-dropper','library'=>'fa-solid'],'title'=>'السباكة والصرف'],
            ['icon'=>['value'=>'fas fa-lightbulb','library'=>'fa-solid'],'title'=>'الكهرباء والإنارة'],
            ['icon'=>['value'=>'fas fa-paintbrush','library'=>'fa-solid'],'title'=>'الدهانات والعزل'],
            ['icon'=>['value'=>'fas fa-door-open','library'=>'fa-solid'],'title'=>'الأبواب والنوافذ'],
            ['icon'=>['value'=>'fas fa-building','library'=>'fa-solid'],'title'=>'السيراميك والبلاط'],
            ['icon'=>['value'=>'fas fa-temperature-three-quarters','library'=>'fa-solid'],'title'=>'العزل الحراري'],
            ['icon'=>['value'=>'fas fa-fan','library'=>'fa-solid'],'title'=>'التكييف والتهوية'],
            ['icon'=>['value'=>'fas fa-lock','library'=>'fa-solid'],'title'=>'أنظمة الأمان'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; ?>
    <section class="section-padding bg-background"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center max-w-3xl mx-auto mb-12"><div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground hover:bg-primary/80 mb-4"><?php echo esc_html($s['badge']??''); ?></div><h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html($s['heading']??''); ?></h2><p class="text-muted-foreground text-lg"><?php echo esc_html($s['desc']??''); ?></p></div></div><div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"><?php foreach($items as $it): ?><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl p-6 text-center border border-border card-hover"><div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-3" style="transform: rotate(4.45703deg);"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-7 h-7 text-primary']); ?></div><p class="font-semibold text-foreground text-sm"><?php echo esc_html($it['title']??''); ?></p></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

