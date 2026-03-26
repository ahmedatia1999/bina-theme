<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_About_Page_Values_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_values';}
    public function get_title(){return __('About Values (Static)','bina');}
    public function get_icon(){return 'eicon-star';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'مبادئنا']);
        $this->add_control('heading',['type'=>Controls_Manager::TEXT,'default'=>'قيمنا']);
        $r=new Repeater(); $r->add_control('icon',['type'=>Controls_Manager::ICONS]); $r->add_control('title',['type'=>Controls_Manager::TEXT]); $r->add_control('text',['type'=>Controls_Manager::TEXTAREA]);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'default'=>[
            ['icon'=>['value'=>'fas fa-eye','library'=>'fa-solid'],'title'=>'الشفافية','text'=>'كل شيء واضح، من السعر إلى التقييم إلى تاريخ المقاول'],
            ['icon'=>['value'=>'fas fa-headphones','library'=>'fa-solid'],'title'=>'دعم فني متكامل','text'=>'توفير دعم فني متواصل للعملاء والمقاولين، وحل جميع الاستفسارات والمشاكل بسرعة واحترافية'],
            ['icon'=>['value'=>'fas fa-rotate-left','library'=>'fa-solid'],'title'=>'التقييم المستمر والتحسين','text'=>'الاعتماد على تقييمات العملاء والمراجعات الدورية لتحسين الخدمات وتطوير أداء المقاولين باستمرار'],
            ['icon'=>['value'=>'fas fa-bullseye','library'=>'fa-solid'],'title'=>'التخصص','text'=>'نركّز فقط على الترميم والبناء لنقدم خدمة قوية في هذا المجال'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; ?>
    <section class="py-20 bg-background"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center mb-12"><span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge']??''); ?></span><h2 class="text-3xl md:text-4xl font-bold text-secondary"><?php echo esc_html($s['heading']??''); ?></h2></div></div><div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6"><?php foreach($items as $it): ?><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl p-6 shadow-card border border-border/50 text-center h-full"><div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mx-auto mb-4"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-7 h-7 text-primary']); ?></div><h3 class="text-lg font-bold text-secondary mb-3"><?php echo esc_html($it['title']??''); ?></h3><p class="text-sm text-muted-foreground leading-relaxed"><?php echo esc_html($it['text']??''); ?></p></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

