<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_Save50_Supply_Widget extends Widget_Base {
    public function get_name(){return 'bina_save50_supply';}
    public function get_title(){return __('Save50 Supply Steps (Static)','bina');}
    public function get_icon(){return 'eicon-flow';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-ship','library'=>'fa-solid']]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'آلية التوريد']);
        $this->add_control('heading',['type'=>Controls_Manager::TEXT,'default'=>'كيف نوصل المواد لمشروعك؟']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'رحلة المواد من المصنع إلى موقع مشروعك بخطوات واضحة ومضمونة']);
        $r=new Repeater();
        $r->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-circle-check','library'=>'fa-solid']]);
        $r->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'تحديد المواصفات']);
        $r->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'نحدد معك المواصفات الدقيقة والكميات المطلوبة ونتأكد من مطابقتها لمعايير البناء السعودية']);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'title_field'=>'{{{ title }}}','default'=>[
            ['icon'=>['value'=>'fas fa-circle-check','library'=>'fa-solid'],'title'=>'تحديد المواصفات','text'=>'نحدد معك المواصفات الدقيقة والكميات المطلوبة ونتأكد من مطابقتها لمعايير البناء السعودية'],
            ['icon'=>['value'=>'fas fa-globe','library'=>'fa-solid'],'title'=>'التوريد من المصدر','text'=>'نتواصل مع المصانع المعتمدة في الصين وتركيا وأوروبا للحصول على أفضل عرض مع ضمان الجودة'],
            ['icon'=>['value'=>'fas fa-shield-halved','library'=>'fa-solid'],'title'=>'فحص الجودة','text'=>'يتم فحص المنتجات في بلد المنشأ قبل الشحن للتأكد من مطابقتها للمواصفات المطلوبة'],
            ['icon'=>['value'=>'fas fa-ship','library'=>'fa-solid'],'title'=>'الشحن البحري','text'=>'يتم شحن المواد بحرياً إلى الموانئ السعودية مع التخليص الجمركي الكامل'],
            ['icon'=>['value'=>'fas fa-truck','library'=>'fa-solid'],'title'=>'التوصيل لموقعك','text'=>'نقل المواد من الميناء مباشرة إلى موقع مشروعك مع التفريغ والترتيب'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; $last=count($items)-1; ?>
    <section class="section-padding bg-gradient-section"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center max-w-3xl mx-auto mb-12"><div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground hover:bg-primary/80 mb-4"><?php if(!empty($s['badge_icon'])) Icons_Manager::render_icon($s['badge_icon'],['class'=>'w-4 h-4 inline-block me-1']); ?><?php echo esc_html($s['badge']??''); ?></div><h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html($s['heading']??''); ?></h2><p class="text-muted-foreground text-lg"><?php echo esc_html($s['desc']??''); ?></p></div></div><div class="max-w-3xl mx-auto space-y-6"><?php foreach($items as $i=>$it): ?><div class="" style="opacity: 1; transform: none;"><div class="flex gap-4 items-start"><div class="relative flex flex-col items-center"><div class="w-12 h-12 shrink-0 rounded-full bg-primary text-primary-foreground flex items-center justify-center" style="transform: scale(1.03107);"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-6 h-6']); ?></div><?php if($i!==$last): ?><div class="w-0.5 h-12 bg-primary/20 mt-2"></div><?php endif; ?></div><div class="pb-6"><h3 class="text-lg font-bold text-foreground mb-1"><?php echo esc_html($it['title']??''); ?></h3><p class="text-muted-foreground text-sm leading-relaxed"><?php echo esc_html($it['text']??''); ?></p></div></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

