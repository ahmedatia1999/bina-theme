<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;

class bina_Add_Project_Hero_Widget extends Widget_Base {
    public function get_name() { return 'bina_add_project_hero'; }
    public function get_title() { return __('Add Project Hero (Static)', 'bina'); }
    public function get_icon() { return 'eicon-plus-circle-o'; }
    public function get_categories() { return ['general']; }

    protected function _register_controls() {
        $this->start_controls_section('section_main', ['label' => __('Main Content', 'bina')]);

        $this->add_control('badge_text', [
            'label' => __('Badge Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('ابدأ مشروعك', 'bina'),
        ]);
        $this->add_control('badge_icon', [
            'label' => __('Badge Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-building', 'library' => 'fa-solid'],
        ]);
        $this->add_control('title', [
            'label' => __('Title', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 2,
            'default' => __('أضف مشروعك الآن', 'bina'),
        ]);
        $this->add_control('description', [
            'label' => __('Description', 'bina'),
            'type' => Controls_Manager::TEXTAREA,
            'rows' => 3,
            'default' => __('ابدأ رحلتك مع بناء سنتر واحصل على أفضل العروض من المقاولين المعتمدين', 'bina'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_card_register', ['label' => __('Register Card', 'bina')]);

        $this->add_control('register_url', [
            'label' => __('Register URL', 'bina'),
            'type' => Controls_Manager::URL,
            'default' => [
                'url' => 'https://app.binacenter.com/ar/register',
                'is_external' => true,
                'nofollow' => true,
            ],
        ]);
        $this->add_control('register_icon', [
            'label' => __('Register Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-user-plus', 'library' => 'fa-solid'],
        ]);
        $this->add_control('register_title', [
            'label' => __('Register Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مستخدم جديد؟', 'bina'),
        ]);
        $this->add_control('register_text', [
            'label' => __('Register Description', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('سجل الآن وأضف مشروعك الأول', 'bina'),
        ]);
        $this->add_control('register_button_text', [
            'label' => __('Register Button Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('التسجيل', 'bina'),
        ]);
        $this->add_control('register_button_icon', [
            'label' => __('Register Button Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-arrow-left', 'library' => 'fa-solid'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_card_login', ['label' => __('Login Card', 'bina')]);

        $this->add_control('login_url', [
            'label' => __('Login URL', 'bina'),
            'type' => Controls_Manager::URL,
            'default' => [
                'url' => 'https://app.binacenter.com/ar/login',
                'is_external' => true,
                'nofollow' => true,
            ],
        ]);
        $this->add_control('login_icon', [
            'label' => __('Login Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-right-to-bracket', 'library' => 'fa-solid'],
        ]);
        $this->add_control('login_title', [
            'label' => __('Login Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('مسجل بالفعل؟', 'bina'),
        ]);
        $this->add_control('login_text', [
            'label' => __('Login Description', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('سجل دخولك وأكمل إضافة مشروعك', 'bina'),
        ]);
        $this->add_control('login_button_text', [
            'label' => __('Login Button Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('تسجيل الدخول', 'bina'),
        ]);
        $this->add_control('login_button_icon', [
            'label' => __('Login Button Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-arrow-left', 'library' => 'fa-solid'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('section_why', ['label' => __('Why Block', 'bina')]);

        $this->add_control('why_icon', [
            'label' => __('Why Title Icon', 'bina'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-sparkles', 'library' => 'fa-solid'],
        ]);
        $this->add_control('why_title', [
            'label' => __('Why Title', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('لماذا تختار بناء سنتر؟', 'bina'),
        ]);

        $repeater = new Repeater();
        $repeater->add_control('text', [
            'label' => __('Feature Text', 'bina'),
            'type' => Controls_Manager::TEXT,
            'default' => __('ميزة', 'bina'),
        ]);

        $this->add_control('why_features', [
            'label' => __('Features', 'bina'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['text' => 'عروض متعددة من مقاولين معتمدين'],
                ['text' => 'مقارنة شاملة للأسعار والجودة'],
                ['text' => 'دعم فني متواصل'],
                ['text' => 'ضمان الجودة والتنفيذ'],
            ],
            'title_field' => '{{{ text }}}',
        ]);

        $this->end_controls_section();
    }

    private function render_link_attrs($url_setting) {
        $href = $url_setting['url'] ?? '#';
        $target = !empty($url_setting['is_external']) ? ' target="_blank"' : '';
        $rel = ' rel="noopener';
        if (!empty($url_setting['nofollow'])) {
            $rel .= ' nofollow';
        }
        $rel .= '"';
        return [esc_url($href), $target, $rel];
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        [$register_href, $register_target, $register_rel] = $this->render_link_attrs($s['register_url'] ?? []);
        [$login_href, $login_target, $login_rel] = $this->render_link_attrs($s['login_url'] ?? []);
        ?>
        <section class="py-16 md:py-24 bg-background relative overflow-hidden min-h-[80vh] flex items-center">
            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(hsl(var(--border)) 1px, transparent 1px), linear-gradient(90deg, hsl(var(--border)) 1px, transparent 1px); background-size: 60px 60px;"></div>
            </div>
            <div class="absolute top-20 right-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-10 w-96 h-96 bg-accent/10 rounded-full blur-3xl"></div>
            <div class="container-custom relative z-10">
                <div class="text-center mb-12">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-medium mb-6">
                        <?php if (!empty($s['badge_icon']['value'])) { Icons_Manager::render_icon($s['badge_icon'], ['aria-hidden' => 'true', 'class' => 'w-4 h-4']); } ?>
                        <?php echo esc_html($s['badge_text'] ?? ''); ?>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-secondary mb-4"><?php echo esc_html($s['title'] ?? ''); ?></h1>
                    <p class="text-lg text-muted-foreground max-w-2xl mx-auto"><?php echo esc_html($s['description'] ?? ''); ?></p>
                </div>

                <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto mb-12">
                    <div>
                        <a href="<?php echo $register_href; ?>"<?php echo $register_target; ?><?php echo $register_rel; ?> class="block bg-card rounded-2xl p-8 border border-border shadow-card hover:shadow-xl hover:border-primary/30 transition-all duration-300 group relative overflow-hidden cursor-pointer" tabindex="0">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary to-blue-600 opacity-0 group-hover:opacity-[0.03] transition-opacity duration-300"></div>
                            <div class="flex flex-col items-center text-center gap-4 relative z-10">
                                <div class="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-all duration-300">
                                    <?php if (!empty($s['register_icon']['value'])) { Icons_Manager::render_icon($s['register_icon'], ['aria-hidden' => 'true', 'class' => 'w-10 h-10 text-primary transition-colors duration-300']); } ?>
                                </div>
                                <h2 class="text-xl font-bold text-secondary"><?php echo esc_html($s['register_title'] ?? ''); ?></h2>
                                <p class="text-muted-foreground"><?php echo esc_html($s['register_text'] ?? ''); ?></p>
                                <div class="w-full mt-4 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium h-11 px-8 transition-all duration-300 bg-primary text-primary-foreground group-hover:shadow-md">
                                    <?php echo esc_html($s['register_button_text'] ?? ''); ?>
                                    <?php if (!empty($s['register_button_icon']['value'])) { Icons_Manager::render_icon($s['register_button_icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5 group-hover:-translate-x-1 transition-transform']); } ?>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div>
                        <a href="<?php echo $login_href; ?>"<?php echo $login_target; ?><?php echo $login_rel; ?> class="block bg-card rounded-2xl p-8 border border-border shadow-card hover:shadow-xl hover:border-primary/30 transition-all duration-300 group relative overflow-hidden cursor-pointer" tabindex="0">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-cyan-500 opacity-0 group-hover:opacity-[0.03] transition-opacity duration-300"></div>
                            <div class="flex flex-col items-center text-center gap-4 relative z-10">
                                <div class="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-all duration-300">
                                    <?php if (!empty($s['login_icon']['value'])) { Icons_Manager::render_icon($s['login_icon'], ['aria-hidden' => 'true', 'class' => 'w-10 h-10 text-primary transition-colors duration-300']); } ?>
                                </div>
                                <h2 class="text-xl font-bold text-secondary"><?php echo esc_html($s['login_title'] ?? ''); ?></h2>
                                <p class="text-muted-foreground"><?php echo esc_html($s['login_text'] ?? ''); ?></p>
                                <div class="w-full mt-4 inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium h-11 px-8 transition-all duration-300 border border-input bg-background group-hover:border-primary/50 group-hover:bg-primary/5">
                                    <?php echo esc_html($s['login_button_text'] ?? ''); ?>
                                    <?php if (!empty($s['login_button_icon']['value'])) { Icons_Manager::render_icon($s['login_button_icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5 group-hover:-translate-x-1 transition-transform']); } ?>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="bg-muted/50 rounded-2xl p-6 md:p-8 max-w-3xl mx-auto">
                        <div class="flex items-center gap-2 justify-center mb-6">
                            <?php if (!empty($s['why_icon']['value'])) { Icons_Manager::render_icon($s['why_icon'], ['aria-hidden' => 'true', 'class' => 'w-5 h-5 text-primary']); } ?>
                            <h3 class="text-lg font-semibold text-secondary"><?php echo esc_html($s['why_title'] ?? ''); ?></h3>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <?php foreach (($s['why_features'] ?? []) as $item): ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <div class="w-2 h-2 rounded-full bg-primary"></div>
                                    </div>
                                    <span class="text-muted-foreground text-sm"><?php echo esc_html($item['text'] ?? ''); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

