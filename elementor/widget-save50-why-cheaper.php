<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_Save50_Why_Cheaper_Widget extends Widget_Base {
    public function get_name(){return 'bina_save50_why_cheaper';}
    public function get_title(){return __('Save50 Why Cheaper (Static)','bina');}
    public function get_icon(){return 'eicon-trends';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('badge_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-arrow-trend-down','library'=>'fa-solid']]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'أسعار أقل']);
        $this->add_control('heading',['type'=>Controls_Manager::TEXT,'default'=>'ليش الأسعار أقل من السوق؟']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'نوفر لك فرق سعر حقيقي من خلال آلية عمل ذكية تختلف عن الموردين التقليديين']);
        $r=new Repeater();
        $r->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-industry','library'=>'fa-solid']]);
        $r->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'شراء مباشر من المصنع']);
        $r->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'نشتري من المصانع مباشرة في الصين وتركيا وأوروبا بدون وسطاء أو موزعين محليين، مما يلغي هوامش الربح المتعددة']);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'title_field'=>'{{{ title }}}','default'=>[
            ['icon'=>['value'=>'fas fa-industry','library'=>'fa-solid'],'title'=>'شراء مباشر من المصنع','text'=>'نشتري من المصانع مباشرة في الصين وتركيا وأوروبا بدون وسطاء أو موزعين محليين، مما يلغي هوامش الربح المتعددة'],
            ['icon'=>['value'=>'fas fa-ship','library'=>'fa-solid'],'title'=>'شحن بالجملة','text'=>'نجمع طلبات عدة مشاريع في شحنة واحدة لتقليل تكلفة الشحن على كل عميل بشكل كبير'],
            ['icon'=>['value'=>'fas fa-handshake','library'=>'fa-solid'],'title'=>'علاقات طويلة مع الموردين','text'=>'شراكات مبنية على سنوات من التعامل تمنحنا أسعار تفضيلية لا تتوفر للمشتري الفردي'],
            ['icon'=>['value'=>'fas fa-percent','library'=>'fa-solid'],'title'=>'بدون تكاليف تشغيلية مرتفعة','text'=>'لا نتحمل تكاليف معارض ومخازن ضخمة مثل الموردين المحليين، فالتوريد مباشر لموقع مشروعك'],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; ?>
    <section class="section-padding bg-gradient-section"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center max-w-3xl mx-auto mb-12"><div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary text-primary-foreground hover:bg-primary/80 mb-4"><?php if(!empty($s['badge_icon'])) Icons_Manager::render_icon($s['badge_icon'],['class'=>'w-4 h-4 inline-block me-1']); ?><?php echo esc_html($s['badge']??''); ?></div><h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4"><?php echo esc_html($s['heading']??''); ?></h2><p class="text-muted-foreground text-lg"><?php echo esc_html($s['desc']??''); ?></p></div></div><div class="grid grid-cols-1 md:grid-cols-2 gap-6"><?php foreach($items as $it): ?><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl p-6 border border-border card-hover flex gap-4"><div class="w-14 h-14 shrink-0 rounded-xl bg-primary/10 flex items-center justify-center" style="transform: translateY(-4.61493px);"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-7 h-7 text-primary']); ?></div><div><h3 class="text-lg font-bold text-foreground mb-2"><?php echo esc_html($it['title']??''); ?></h3><p class="text-muted-foreground text-sm leading-relaxed"><?php echo esc_html($it['text']??''); ?></p></div></div></div><?php endforeach; ?></div></div></section>
    <?php }
}

