<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_About_Page_Comparison_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_comparison';}
    public function get_title(){return __('About Comparison (Static)','bina');}
    public function get_icon(){return 'eicon-table';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'المقارنة']);
        $this->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'كيف تختلف بناء سنتر عن الطرق التقليدية؟']);
        $this->add_control('traditional_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-xmark','library'=>'fa-solid']]);
        $this->add_control('bina_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-circle-check','library'=>'fa-solid']]);
        $this->add_control('left_row_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-xmark','library'=>'fa-solid']]);
        $this->add_control('right_row_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-circle-check','library'=>'fa-solid']]);
        $r=new Repeater(); $r->add_control('left',['type'=>Controls_Manager::TEXTAREA,'default'=>'البحث العشوائي في الإنترنت أو الاعتماد على توصيات غير موثقة']); $r->add_control('right',['type'=>Controls_Manager::TEXTAREA,'default'=>'قائمة موثقة من المقاولين مع تقييمات وتراخيص']);
        $this->add_control('rows',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'default'=>[
            ['left'=>'البحث العشوائي في الإنترنت أو الاعتماد على توصيات غير موثقة','right'=>'قائمة موثقة من المقاولين مع تقييمات وتراخيص'],
            ['left'=>'تفاوت الأسعار دون معرفة معيار واضح','right'=>'عروض متعددة من مقاولين مختلفين للمقارنة'],
            ['left'=>'لا يوجد توثيق أو ضمان في الاتفاق','right'=>'إمكانية طلب توثيق ومتابعة من بناء سنتر'],
            ['left'=>'ضياع الوقت في التواصل والتفاوض','right'=>'تجربة مركزية سهلة وسريعة من مكان واحد'],
            ['left'=>'انعدام الشفافية في الجودة','right'=>'تقييمات حقيقية وأعمال سابقة واضحة'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $rows=$s['rows']??[]; ?>
    <section class="py-20 bg-muted/30"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center mb-12"><span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge']??''); ?></span><h2 class="text-3xl md:text-4xl font-bold text-secondary"><?php echo esc_html($s['title']??''); ?></h2></div></div><div class="max-w-4xl mx-auto"><div class="grid md:grid-cols-2 gap-4 mb-4"><div class="bg-red-50 dark:bg-red-950/30 rounded-xl p-4 text-center"><?php if(!empty($s['traditional_icon'])) Icons_Manager::render_icon($s['traditional_icon'],['class'=>'w-8 h-8 text-red-500 mx-auto mb-2']); ?><h3 class="font-bold text-red-700 dark:text-red-300">الطريقة التقليدية</h3></div><div class="bg-primary/10 dark:bg-green-950/30 rounded-xl p-4 text-center"><?php if(!empty($s['bina_icon'])) Icons_Manager::render_icon($s['bina_icon'],['class'=>'w-8 h-8 text-green-500 mx-auto mb-2']); ?><h3 class="font-bold text-green-700 dark:text-green-300">مع بناء سنتر</h3></div></div><?php foreach($rows as $r): ?><div class="" style="opacity: 1; transform: none;"><div class="grid md:grid-cols-2 gap-4 mb-3"><div class="bg-card rounded-xl p-4 border border-red-200 dark:border-red-800/50"><div class="flex items-start gap-3"><?php if(!empty($s['left_row_icon'])) Icons_Manager::render_icon($s['left_row_icon'],['class'=>'w-5 h-5 text-red-500 flex-shrink-0 mt-0.5']); ?><span class="text-muted-foreground"><?php echo esc_html($r['left']??''); ?></span></div></div><div class="bg-card rounded-xl p-4 border border-green-200 dark:border-green-800/50"><div class="flex items-start gap-3"><?php if(!empty($s['right_row_icon'])) Icons_Manager::render_icon($s['right_row_icon'],['class'=>'w-5 h-5 text-green-500 flex-shrink-0 mt-0.5']); ?><span class="text-foreground"><?php echo esc_html($r['right']??''); ?></span></div></div></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

