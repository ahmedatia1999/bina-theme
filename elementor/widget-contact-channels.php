<?php
if (!defined('ABSPATH')) exit;
use Elementor\Widget_Base; use Elementor\Controls_Manager; use Elementor\Repeater; use Elementor\Icons_Manager;
class bina_Contact_Channels_Widget extends Widget_Base {
    public function get_name(){return 'bina_contact_channels';}
    public function get_title(){return __('Contact Channels (Static)','bina');}
    public function get_icon(){return 'eicon-call-to-action';}
    public function get_categories(){return ['general'];}
    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Cards','bina')]);
        $r=new Repeater();
        $r->add_control('url',['label'=>__('URL','bina'),'type'=>Controls_Manager::URL,'default'=>['url'=>'#']]);
        $r->add_control('open_new',['label'=>__('Open in new tab','bina'),'type'=>Controls_Manager::SWITCHER,'default'=>'']);
        $r->add_control('icon_upload',['label'=>__('Icon Upload','bina'),'type'=>Controls_Manager::MEDIA]);
        $r->add_control('fallback_icon',['label'=>__('Fallback Icon','bina'),'type'=>Controls_Manager::ICONS,'default'=>['value'=>'fas fa-phone','library'=>'fa-solid']]);
        $r->add_control('icon_bg_class',['label'=>__('Icon Background Class','bina'),'type'=>Controls_Manager::TEXT,'default'=>'bg-gradient-to-br from-blue-500 to-blue-600']);
        $r->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'اتصل بنا']);
        $r->add_control('desc',['type'=>Controls_Manager::TEXT,'default'=>'متاحون للرد على استفساراتكم']);
        $r->add_control('cta',['type'=>Controls_Manager::TEXT,'default'=>'+966 57 357 2442']);
        $r->add_control('cta_extra_class',['type'=>Controls_Manager::TEXT,'default'=>'text-right']);
        $r->add_control('cta_dir',['type'=>Controls_Manager::TEXT,'default'=>'ltr']);
        $this->add_control('items',['type'=>Controls_Manager::REPEATER,'fields'=>$r->get_controls(),'title_field'=>'{{{ title }}}','default'=>[
            ['url'=>['url'=>'tel:+966573572442'],'icon_bg_class'=>'bg-gradient-to-br from-blue-500 to-blue-600','title'=>'اتصل بنا','desc'=>'متاحون للرد على استفساراتكم','cta'=>'+966 57 357 2442','cta_extra_class'=>'text-right','cta_dir'=>'ltr','fallback_icon'=>['value'=>'fas fa-phone','library'=>'fa-solid']],
            ['url'=>['url'=>'https://api.whatsapp.com/send?phone=966573572442'],'open_new'=>'yes','icon_bg_class'=>'bg-gradient-to-br from-green-500 to-green-600','title'=>'واتساب','desc'=>'تواصل معنا مباشرة','cta'=>'راسلنا الآن','cta_extra_class'=>'','cta_dir'=>'','fallback_icon'=>['value'=>'fas fa-message','library'=>'fa-solid']],
            ['url'=>['url'=>'mailto:info@binacenter.com'],'icon_bg_class'=>'bg-gradient-to-br from-purple-500 to-purple-600','title'=>'البريد الإلكتروني','desc'=>'للاستفسارات الرسمية','cta'=>'info@binacenter.com','cta_extra_class'=>'text-right','cta_dir'=>'ltr','fallback_icon'=>['value'=>'fas fa-envelope','library'=>'fa-solid']],
        ]]);
        $this->end_controls_section();
    }
    protected function render(){ $s=$this->get_settings_for_display(); $items=$s['items']??[]; ?>
    <section class="py-16 bg-muted/30"><div class="container-custom"><div class="grid md:grid-cols-3 gap-6"><?php foreach($items as $it): $u=$it['url']['url']??'#'; $new=!empty($it['open_new']); $icon=$it['icon_upload']['url']??''; ?><div class="" style="opacity: 1; transform: none;"><a href="<?php echo esc_url($u); ?>" <?php if($new): ?>target="_blank" rel="noopener noreferrer"<?php endif; ?> class="block bg-card rounded-2xl p-8 border border-border shadow-card hover:shadow-card-hover transition-all group h-full" tabindex="0" style="transform: none;"><div class="w-16 h-16 rounded-2xl <?php echo esc_attr($it['icon_bg_class']??''); ?> flex items-center justify-center mb-6 group-hover:scale-110 transition-transform"><?php if($icon): ?><img src="<?php echo esc_url($icon); ?>" alt="" class="w-8 h-8 object-contain"><?php else: ?><?php if(!empty($it['fallback_icon'])) Icons_Manager::render_icon($it['fallback_icon'],['class'=>'w-8 h-8 text-white']); ?><?php endif; ?></div><h3 class="text-xl font-bold text-foreground mb-2"><?php echo esc_html($it['title']??''); ?></h3><p class="text-muted-foreground mb-4"><?php echo esc_html($it['desc']??''); ?></p><p class="font-semibold text-primary text-lg group-hover:underline <?php echo esc_attr($it['cta_extra_class']??''); ?>" <?php if(!empty($it['cta_dir'])): ?>dir="<?php echo esc_attr($it['cta_dir']); ?>"<?php endif; ?>><?php echo esc_html($it['cta']??''); ?></p></a></div><?php endforeach; ?></div></div></section>
    <?php }
}

