<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

class bina_Save50_Alert_Widget extends Widget_Base {
    public function get_name(){ return 'bina_save50_alert'; }
    public function get_title(){ return __('Save50 Alert (Static)','bina'); }
    public function get_icon(){ return 'eicon-alert'; }
    public function get_categories(){ return ['general']; }

    protected function _register_controls(){
        $this->start_controls_section('s',['label'=>__('Content','bina')]);
        $this->add_control('title',['type'=>Controls_Manager::TEXT,'default'=>'تنبيه مهم: مدة التوريد 60 يوم تقريباً']);
        $this->add_control('text',['type'=>Controls_Manager::TEXTAREA,'default'=>'المنتجات تُشحن من الصين وتركيا وأوروبا مباشرة إلى موقع مشروعك في المملكة — خطط مبكراً لضمان وصول المواد في الوقت المناسب']);

        $this->add_control('icon_upload_1', [
            'label' => __('Left Icon 1 (Upload)', 'bina'),
            'type' => Controls_Manager::MEDIA,
        ]);
        $this->add_control('icon_upload_2', [
            'label' => __('Left Icon 2 (Upload)', 'bina'),
            'type' => Controls_Manager::MEDIA,
        ]);
        $this->add_control('icon_upload_3', [
            'label' => __('Right Icon 1 (Upload)', 'bina'),
            'type' => Controls_Manager::MEDIA,
        ]);
        $this->add_control('icon_upload_4', [
            'label' => __('Right Icon 2 (Upload)', 'bina'),
            'type' => Controls_Manager::MEDIA,
        ]);

        $this->end_controls_section();
    }

    protected function render(){
        $s = $this->get_settings_for_display();
        $icon1 = $s['icon_upload_1']['url'] ?? '';
        $icon2 = $s['icon_upload_2']['url'] ?? '';
        $icon3 = $s['icon_upload_3']['url'] ?? '';
        $icon4 = $s['icon_upload_4']['url'] ?? '';
        ?>
        <section class="border-t border-border bg-muted">
            <div class="container-custom py-6">
                <div class="" style="opacity: 1; transform: none;">
                    <div class="flex flex-col md:flex-row items-center justify-center gap-4 text-center">
                        <div class="flex items-center gap-2 text-destructive">
                            <div style="transform: scale(1.07923);">
                                <?php if ($icon1): ?>
                                    <img src="<?php echo esc_url($icon1); ?>" alt="" class="w-6 h-6 shrink-0 object-contain">
                                <?php else: ?>
                                    <?php Icons_Manager::render_icon(['value'=>'fas fa-triangle-exclamation','library'=>'fa-solid'], ['class'=>'w-6 h-6 shrink-0']); ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($icon2): ?>
                                <img src="<?php echo esc_url($icon2); ?>" alt="" class="w-6 h-6 shrink-0 object-contain">
                            <?php else: ?>
                                <?php Icons_Manager::render_icon(['value'=>'fas fa-clock','library'=>'fa-solid'], ['class'=>'w-6 h-6 shrink-0']); ?>
                            <?php endif; ?>
                        </div>

                        <div>
                            <p class="font-bold text-foreground text-lg"><?php echo esc_html($s['title'] ?? ''); ?></p>
                            <p class="text-muted-foreground text-sm"><?php echo esc_html($s['text'] ?? ''); ?></p>
                        </div>

                        <div class="flex items-center gap-2">
                            <?php if ($icon3): ?>
                                <img src="<?php echo esc_url($icon3); ?>" alt="" class="w-5 h-5 text-muted-foreground object-contain">
                            <?php else: ?>
                                <?php Icons_Manager::render_icon(['value'=>'fas fa-globe','library'=>'fa-solid'], ['class'=>'w-5 h-5 text-muted-foreground']); ?>
                            <?php endif; ?>

                            <?php if ($icon4): ?>
                                <img src="<?php echo esc_url($icon4); ?>" alt="" class="w-5 h-5 text-muted-foreground object-contain">
                            <?php else: ?>
                                <?php Icons_Manager::render_icon(['value'=>'fas fa-truck','library'=>'fa-solid'], ['class'=>'w-5 h-5 text-muted-foreground']); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

