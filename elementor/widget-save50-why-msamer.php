<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_Save50_Why_Msamer_Widget extends Widget_Base {
    public function get_name(){return 'bina_save50_why_msamer';}
    public function get_title(){return __('Save50 Why Msamer (Static)','bina');}
    public function get_icon(){return 'eicon-info-box';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'المميزات']);
        $this->add_control('heading',['type'=>Controls_Manager::TEXT,'default'=>'لماذا مسامير؟']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'منصة مسامير توفر لك مواد بناء بأسعار تنافسية مع ضمان الجودة']);
        $r=new Repeater();
        $r->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-circle-dollar-to-slot','library'=>'fa-solid']]);
        $r->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'أسعار أقل مباشرة']);
        $r->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'نتعامل مع المصانع مباشرة بدون وسطاء لتوفير أفضل الأسعار']);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'title_field'=>'{{{ title }}}','default'=>[
            ['icon'=>['value'=>'fas fa-circle-dollar-to-slot','library'=>'fa-solid'],'title'=>'أسعار أقل مباشرة','text'=>'نتعامل مع المصانع مباشرة بدون وسطاء لتوفير أفضل الأسعار'],
            ['icon'=>['value'=>'fas fa-shield-halved','library'=>'fa-solid'],'title'=>'موردون معتمدون','text'=>'جميع الموردين معتمدون ومختبرون لضمان جودة المنتجات'],
            ['icon'=>['value'=>'fas fa-credit-card','library'=>'fa-solid'],'title'=>'تمويل بالأجل','text'=>'إمكانية التقسيط حتى 12 شهر لتسهيل عملية الشراء'],
            ['icon'=>['value'=>'fas fa-users','library'=>'fa-solid'],'title'=>'دعم مشاريع المقاولات','text'=>'خبرة واسعة في دعم مشاريع المقاولات بجميع أحجامها'],
            ['icon'=>['value'=>'fas fa-boxes-stacked','library'=>'fa-solid'],'title'=>'أكثر من 9,000 منتج','text'=>'تشكيلة واسعة من مواد البناء تغطي جميع احتياجات مشروعك'],
            ['icon'=>['value'=>'fas fa-wrench','library'=>'fa-solid'],'title'=>'خدمة التركيب','text'=>'فريق متخصص لتركيب المواد في موقع مشروعك'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; ?>
        <section class="section-padding bg-background"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center max-w-3xl mx-auto mb-12">
            <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground hover:bg-primary/80 mb-4"><?php echo esc_html($s['badge']??''); ?></div>
            <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html($s['heading']??''); ?></h2><p class="text-muted-foreground text-lg"><?php echo esc_html($s['desc']??''); ?></p>
        </div></div><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"><?php foreach($items as $it): ?><div class="" style="opacity: 1; transform: none;"><div class="rounded-lg border bg-card text-card-foreground shadow-sm h-full card-hover border-border"><div class="p-6"><div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4" style="transform: scale(1.04777);"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-6 h-6 text-primary']); ?></div><h3 class="text-lg font-bold text-foreground mb-2"><?php echo esc_html($it['title']??''); ?></h3><p class="text-muted-foreground text-sm"><?php echo esc_html($it['text']??''); ?></p></div></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

