<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_About_Page_Services_Widget extends Widget_Base {
    public function get_name(){return 'bina_about_page_services';}
    public function get_title(){return __('About Services Split (Static)','bina');}
    public function get_icon(){return 'eicon-columns';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('h',['label'=>__('Header','bina')]);
        $this->add_control('badge',['type'=>Controls_Manager::TEXT,'default'=>'خدماتنا']);
        $this->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'ما الذي نقدمه؟']);
        $this->add_control('desc',['type'=>Controls_Manager::TEXTAREA,'default'=>'بناء سنتر لا تكتفي بربطك بمقاول فقط… بل تقدّم حلولًا متكاملة تجعل تجربتك أكثر ذكاءً واطمئنانًا']);
        $this->end_controls_section();
        $this->start_controls_section('c',['label'=>__('Columns','bina')]);
        $this->add_control('left_title',['type'=>Controls_Manager::TEXT,'default'=>'للعميل']);
        $this->add_control('left_header_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-users','library'=>'fa-solid']]);
        $rl=new Repeater(); $rl->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'تصفّح قائمة شركات المقاولات حسب الخدمة والمنطقة والتقييم، مع إمكانية رؤية معلومات موثقة لكل مقاول.']); $rl->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-magnifying-glass','library'=>'fa-solid']]);
        $this->add_control('left_items',['type'=>Controls_Manager::REPEATER,'fields'=>$rl->get_controls(),'default'=>[
            ['text'=>'تصفّح قائمة شركات المقاولات حسب الخدمة والمنطقة والتقييم، مع إمكانية رؤية معلومات موثقة لكل مقاول.','icon'=>['value'=>'fas fa-magnifying-glass','library'=>'fa-solid']],
            ['text'=>'تعبئة نموذج مشروعك بسهولة واستقبال عروض متعددة من مقاولين متخصصين في البناء أو الترميم أو الديكور.','icon'=>['value'=>'fas fa-file-lines','library'=>'fa-solid']],
            ['text'=>'مقارنة العروض بوضوح من حيث الجودة، السعر، مدة التنفيذ، والأعمال السابقة، مع إمكانية إضافة التمويل وخيارات الدفع.','icon'=>['value'=>'fas fa-chart-line','library'=>'fa-solid']],
            ['text'=>'الوصول لخدمات مساندة كاملة تشمل مواد البناء، التشطيبات، التصميم، والاحتياجات الأساسية التي قد يتطلبها مشروعك.','icon'=>['value'=>'fas fa-wrench','library'=>'fa-solid']],
        ]]);
        $this->add_control('right_title',['type'=>Controls_Manager::TEXT,'default'=>'للمقاول']);
        $this->add_control('right_header_icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-hard-hat','library'=>'fa-solid']]);
        $rr=new Repeater(); $rr->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'الحصول على مشاريع جاهزة من عملاء يبحثون عن مقاولين متخصصين في البناء والترميم، أو الديكور داخل منطقتك.']); $rr->add_control('icon',['type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-building','library'=>'fa-solid']]);
        $this->add_control('right_items',['type'=>Controls_Manager::REPEATER,'fields'=>$rr->get_controls(),'default'=>[
            ['text'=>'الحصول على مشاريع جاهزة من عملاء يبحثون عن مقاولين متخصصين في البناء والترميم، أو الديكور داخل منطقتك.','icon'=>['value'=>'fas fa-building','library'=>'fa-solid']],
            ['text'=>'استقبال الطلبات مباشرة عبر منصة واضحة تساعدك على تسعير المشروع وإرسال عرضك بسهولة وبجودة عالية.','icon'=>['value'=>'fas fa-file-lines','library'=>'fa-solid']],
            ['text'=>'عرض أعمالك وتقييماتك بشكل موثّق لزيادة فرصك في الفوز بالمشاريع وتعزيز ثقة العملاء بك.','icon'=>['value'=>'fas fa-star','library'=>'fa-solid']],
            ['text'=>'الاستفادة من خدمات مساندة تشمل مواد البناء، التمويل، الدعم الفني، وإدارة الأعمال.','icon'=>['value'=>'fas fa-headphones','library'=>'fa-solid']],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $li=$s['left_items']??[]; $ri=$s['right_items']??[]; ?>
    <section class="py-20 bg-background"><div class="container-custom"><div class="" style="opacity: 1; transform: none;"><div class="text-center mb-12"><span class="inline-block px-4 py-1.5 bg-primary/10 text-primary rounded-full text-sm font-medium mb-4"><?php echo esc_html($s['badge']??''); ?></span><h2 class="text-3xl md:text-4xl font-bold text-secondary mb-4"><?php echo esc_html($s['title']??''); ?></h2><p class="text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html($s['desc']??''); ?></p></div></div><div class="grid lg:grid-cols-2 gap-8"><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl p-8 shadow-card border border-border/50 h-full" style="transform: none;"><div class="flex items-center gap-3 mb-6"><div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center"><?php if(!empty($s['left_header_icon'])) Icons_Manager::render_icon($s['left_header_icon'],['class'=>'w-6 h-6 text-white']); ?></div><h3 class="text-2xl font-bold text-secondary"><?php echo esc_html($s['left_title']??''); ?></h3></div><ul class="space-y-5"><?php foreach($li as $it): ?><li class="flex items-start gap-4"><div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-5 h-5 text-primary']); ?></div><span class="text-muted-foreground leading-relaxed"><?php echo esc_html($it['text']??''); ?></span></li><?php endforeach; ?></ul></div></div><div class="" style="opacity: 1; transform: none;"><div class="bg-card rounded-2xl p-8 shadow-card border border-border/50 h-full"><div class="flex items-center gap-3 mb-6"><div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center"><?php if(!empty($s['right_header_icon'])) Icons_Manager::render_icon($s['right_header_icon'],['class'=>'w-6 h-6 text-white']); ?></div><h3 class="text-2xl font-bold text-secondary"><?php echo esc_html($s['right_title']??''); ?></h3></div><ul class="space-y-5"><?php foreach($ri as $it): ?><li class="flex items-start gap-4"><div class="w-10 h-10 rounded-lg bg-secondary/10 flex items-center justify-center flex-shrink-0"><?php if(!empty($it['icon'])) Icons_Manager::render_icon($it['icon'],['class'=>'w-5 h-5 text-secondary']); ?></div><span class="text-muted-foreground leading-relaxed"><?php echo esc_html($it['text']??''); ?></span></li><?php endforeach; ?></ul></div></div></div></div></section>
    <?php }
}

