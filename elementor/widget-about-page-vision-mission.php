<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_About_Page_Vision_Mission_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_vision_mission';}
    public function get_title(){return __('About Vision Mission (Static)','bina');}
    public function get_icon(){return 'eicon-dual-button';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('v',['label'=>__('Vision','bina')]);
        $this->add_control('v_title',['type'=>Controls_Manager::TEXT,'default'=>'رؤيتنا']);
        $this->add_control('v_desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'أن نصبح الخيار الأول والأكثر ثقة في المملكة لكل من يرغب في تنفيذ مشروع بناء، أو ترميم، أو توريد مواد البناء، عبر منصة رقمية متكاملة تجمع بين:']);
        $this->add_control('v_header_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-eye','library'=>'fa-solid']]);
        $rv=new Repeater(); $rv->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-shield-halved','library'=>'fa-solid']]); $rv->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'الثقة المبنية على تقييمات شفافة ونظام متابعة واضح لكل خطوة']);
        $this->add_control('v_items',['type'=>Controls_Manager::REPEATER,'fields'=>$rv->get_controls(),'default'=>[
            ['icon'=>['value'=>'fas fa-shield-halved','library'=>'fa-solid'],'text'=>'الثقة المبنية على تقييمات شفافة ونظام متابعة واضح لكل خطوة'],
            ['icon'=>['value'=>'fas fa-bullseye','library'=>'fa-solid'],'text'=>'التخصص من خلال ربط العملاء بأفضل المقاولين والخبراء في مجالاتهم'],
            ['icon'=>['value'=>'fas fa-eye','library'=>'fa-solid'],'text'=>'الشفافية عبر تقديم معلومات دقيقة، عروض أسعار واضحة، وخيارات متعددة'],
        ]]);
        $this->end_controls_section();
        $this->start_controls_section('m',['label'=>__('Mission','bina')]);
        $this->add_control('m_title',['type'=>Controls_Manager::TEXT,'default'=>'مهمتنا']);
        $this->add_control('m_header_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-bullseye','library'=>'fa-solid']]);
        $this->add_control('m_item_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-circle-check','library'=>'fa-solid']]);
        $rm=new Repeater(); $rm->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'نسهل الوصول إلى المقاولين المناسبين لمشروعك']);
        $this->add_control('m_items',['type'=>Controls_Manager::REPEATER,'fields'=>$rm->get_controls(),'default'=>[
            ['text'=>'نسهل الوصول إلى المقاولين المناسبين لمشروعك'],
            ['text'=>'تمكين العملاء من المقارنة واتخاذ قرارات أفضل'],
            ['text'=>'دعم المقاولين الجادين في الحصول على فرص عمل'],
            ['text'=>'رفع جودة الخدمات في السوق'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $vi=$s['v_items']??[]; $mi=$s['m_items']??[]; ?>
    <section class="py-20 bg-muted/30"><div class="container-custom"><div class="grid lg:grid-cols-2 gap-12 items-start"><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl p-8 shadow-card border border-border/50 h-full" style="transform: none;"><div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-6"><?php if(!empty($s['v_header_icon'])) Icons_Manager::render_icon($s['v_header_icon'],['class'=>'w-7 h-7 text-primary']); ?></div><h2 class="text-2xl md:text-3xl font-bold text-secondary mb-6"><?php echo esc_html($s['v_title']??''); ?></h2><p class="text-muted-foreground leading-relaxed mb-6"><?php echo esc_html($s['v_desc']??''); ?></p><ul class="space-y-4"><?php foreach($vi as $it): ?><li class="flex items-start gap-3"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-5 h-5 text-primary flex-shrink-0 mt-1']); ?><span class="text-foreground"><?php echo esc_html($it['text']??''); ?></span></li><?php endforeach; ?></ul></div></div><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl p-8 shadow-card border border-border/50 h-full"><div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-6"><?php if(!empty($s['m_header_icon'])) Icons_Manager::render_icon($s['m_header_icon'],['class'=>'w-7 h-7 text-primary']); ?></div><h2 class="text-2xl md:text-3xl font-bold text-secondary mb-6"><?php echo esc_html($s['m_title']??''); ?></h2><ul class="space-y-4"><?php foreach($mi as $it): ?><li class="flex items-start gap-3"><?php if(!empty($s['m_item_icon'])) Icons_Manager::render_icon($s['m_item_icon'],['class'=>'w-5 h-5 text-primary flex-shrink-0 mt-0.5']); ?><span class="text-muted-foreground text-lg"><?php echo esc_html($it['text']??''); ?></span></li><?php endforeach; ?></ul></div></div></div></div></section>
    <?php }
}

