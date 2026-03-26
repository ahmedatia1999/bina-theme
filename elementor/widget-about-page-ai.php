<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_About_Page_AI_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_ai';}
    public function get_title(){return __('About AI (Static)','bina');}
    public function get_icon(){return 'eicon-brush';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'الذكاء الاصطناعي']);
        $this->add_control('badge_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-brain','library'=>'fa-solid']]);
        $this->add_control('heading',['type'=>Controls_Manager::TEXT,'default'=>'ماذا عن الذكاء الاصطناعي؟']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'بناء سنتر تستخدم الذكاء الاصطناعي لدعم تجربة العميل والمقاول']);
        $r=new Repeater(); $r->add_control('icon',['type'=>Controls_Manager::ICONS]); $r->add_control('title',['type'=>Controls_Manager::TEXT]); $r->add_control('text',['type'=>Controls_Manager::TEXTAREA]);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'default'=>[
            ['icon'=>['value'=>'fas fa-magnifying-glass','library'=>'fa-solid'],'title'=>'اقتراح المقاولين المناسبين','text'=>'حسب نوع المشروع وموقعه ومتطلبات العميل'],
            ['icon'=>['value'=>'fas fa-chart-line','library'=>'fa-solid'],'title'=>'تحليل التقييمات والأداء','text'=>'لعرض المقاولين الأعلى موثوقية أولًا'],
            ['icon'=>['value'=>'fas fa-palette','library'=>'fa-solid'],'title'=>'مكتبة تصاميم ذكية','text'=>'يمكن للعميل تصفحها لاختيار الشكل الذي يريده قبل بدء التنفيذ'],
            ['icon'=>['value'=>'fas fa-rotate-left','library'=>'fa-solid'],'title'=>'تعلم مستمر','text'=>'من البيانات لتحسين توصياتنا وتجربة المستخدم مع مرور الوقت'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; ?>
    <section class="py-20 bg-secondary"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center mb-12"><span class="inline-flex items-center gap-2 px-4 py-2 bg-primary/20 text-primary rounded-full text-sm font-medium mb-4"><?php if(!empty($s['badge_icon'])) Icons_Manager::render_icon($s['badge_icon'],['class'=>'w-4 h-4']); ?><?php echo esc_html($s['badge']??''); ?></span><h2 class="text-3xl md:text-4xl font-bold text-white mb-4"><?php echo esc_html($s['heading']??''); ?></h2><p class="text-white/80 max-w-2xl mx-auto"><?php echo esc_html($s['desc']??''); ?></p></div></div><div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6"><?php foreach($items as $it): ?><div class="" style="opacity: 1; transform: none;"><div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 text-center h-full"><div class="w-14 h-14 rounded-xl bg-primary/20 flex items-center justify-center mx-auto mb-4"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-7 h-7 text-primary']); ?></div><h3 class="text-lg font-bold text-white mb-2"><?php echo esc_html($it['title']??''); ?></h3><p class="text-white/70 text-sm"><?php echo esc_html($it['text']??''); ?></p></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

