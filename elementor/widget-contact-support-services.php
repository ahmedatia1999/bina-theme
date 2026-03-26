<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_Contact_Support_Services_Widget extends Widget_Base {
    public function get_name(){return 'bina_contact_support_services';}
    public function get_title(){return __('Contact Support Services (Static)','bina');}
    public function get_icon(){return 'eicon-support';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'خدمات الدعم']);
        $this->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'كيف يمكننا مساعدتك؟']);
        $r=new Repeater(); $r->add_control('icon_upload',['type'=>Controls_Manager::MEDIA]); $r->add_control('icon',['type'=>Controls_Manager::ICONS]); $r->add_control('title',['type'=>Controls_Manager::TEXT]); $r->add_control('text',['type'=>Controls_Manager::TEXTAREA]);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'default'=>[
            ['title'=>'الدعم الفني','text'=>'مساعدة في استخدام المنصة والمشاكل التقنية','icon'=>['value'=>'fas fa-headphones','library'=>'fa-solid']],
            ['title'=>'استشارات المشاريع','text'=>'نصائح حول مشروعك واختيار المقاول المناسب','icon'=>['value'=>'fas fa-building','library'=>'fa-solid']],
            ['title'=>'الشراكات','text'=>'فرص التعاون والشراكات الاستراتيجية','icon'=>['value'=>'fas fa-users','library'=>'fa-solid']],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; ?>
    <section class="py-20 bg-muted/30"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center max-w-3xl mx-auto mb-16"><span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge']??''); ?></span><h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html($s['title']??''); ?></h2></div></div><div class="grid md:grid-cols-3 gap-8"><?php foreach($items as $it): $img=$it['icon_upload']['url']??''; ?><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl p-8 border border-border text-center h-full"><div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-6"><?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt="" class="w-8 h-8 object-contain"><?php else: ?><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-8 h-8 text-primary']); ?><?php endif; ?></div><h3 class="text-xl font-bold text-foreground mb-3"><?php echo esc_html($it['title']??''); ?></h3><p class="text-muted-foreground"><?php echo esc_html($it['text']??''); ?></p></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

