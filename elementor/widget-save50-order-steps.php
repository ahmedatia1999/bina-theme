<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_Save50_Order_Steps_Widget extends Widget_Base {
    public function get_name(){return 'bina_save50_order_steps';}
    public function get_title(){return __('Save50 Order Steps (Static)','bina');}
    public function get_icon(){return 'eicon-number-field';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'كيف تطلب']);
        $this->add_control('heading',['type'=>Controls_Manager::TEXT,'default'=>'4 خطوات بسيطة']);
        $r=new Repeater();
        $r->add_control('number',['type'=>Controls_Manager::TEXT,'default'=>'01']);
        $r->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'أرسل قائمة المواد']);
        $r->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'أرسل لنا قائمة مواد البناء المطلوبة لمشروعك عبر واتساب']);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'title_field'=>'{{{ number }}} {{{ title }}}','default'=>[
            ['number'=>'01','title'=>'أرسل قائمة المواد','text'=>'أرسل لنا قائمة مواد البناء المطلوبة لمشروعك عبر واتساب'],
            ['number'=>'02','title'=>'نستقبل طلبك','text'=>'فريقنا يراجع القائمة ويتواصل مع الموردين المعتمدين'],
            ['number'=>'03','title'=>'نحصل لك على أفضل عرض','text'=>'نقارن الأسعار من عدة موردين ونختار الأفضل لك'],
            ['number'=>'04','title'=>'يتم التوريد لموقعك','text'=>'يتم شحن وتوريد المواد مباشرة إلى موقع مشروعك'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; $last=count($items)-1; ?>
    <section class="section-padding bg-background"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center max-w-3xl mx-auto mb-12"><div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground hover:bg-primary/80 mb-4"><?php echo esc_html($s['badge']??''); ?></div><h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html($s['heading']??''); ?></h2></div></div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"><?php foreach($items as $i=>$it): ?><div class="" style="opacity: 1; transform: none;"><div class="relative text-center"><div class="w-16 h-16 rounded-full bg-primary text-primary-foreground flex items-center justify-center text-2xl font-bold mx-auto mb-4" style="transform: scale(1.09124);"><?php echo esc_html($it['number']??''); ?></div><h3 class="text-lg font-bold text-foreground mb-2"><?php echo esc_html($it['title']??''); ?></h3><p class="text-muted-foreground text-sm"><?php echo esc_html($it['text']??''); ?></p><?php if($i!==$last): ?><div class="hidden lg:block absolute top-8 -end-3 w-6"><?php Icons_Manager::render_icon(['value'=>'fas fa-arrow-left','library'=>'fa-solid'],['class'=>'w-6 h-6 text-primary/40']); ?></div><?php endif; ?></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

