<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class bina_Forgot_Password_Form_Widget extends Widget_Base {
    public function get_name() { return 'bina_forgot_password_form'; }
    public function get_title() { return __('Forgot Password Form (Static)', 'bina'); }
    public function get_icon() { return 'eicon-lock-user'; }
    public function get_categories() { return ['general']; }

    protected function _register_controls() {
        $this->start_controls_section('sec_content', ['label' => __('Content', 'bina')]);
        $this->add_control('title', ['label' => __('Title', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'استعادة كلمة المرور']);
        $this->add_control('desc', ['label' => __('Description', 'bina'), 'type' => Controls_Manager::TEXTAREA, 'default' => 'أدخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور']);
        $this->add_control('email_label', ['label' => __('Email Label', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'البريد الإلكتروني']);
        $this->add_control('email_placeholder', ['label' => __('Email Placeholder', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'name@example.com']);
        $this->add_control('email_help', ['label' => __('Email Help Text', 'bina'), 'type' => Controls_Manager::TEXTAREA, 'default' => 'سنرسل رابط إعادة تعيين كلمة المرور إذا كان هناك حساب مرتبط بهذا البريد الإلكتروني.']);
        $this->add_control('button_text', ['label' => __('Button Text', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'إرسال رابط إعادة التعيين']);
        $this->add_control('login_text', ['label' => __('Login Link Text', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'تسجيل الدخول']);
        $this->add_control('login_url', ['label' => __('Login URL', 'bina'), 'type' => Controls_Manager::URL, 'default' => ['url' => '/login']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        wp_enqueue_script('bina-forgot-password-js', get_template_directory_uri() . '/assets/js/forgot-password.js', array(), filemtime(get_template_directory() . '/assets/js/forgot-password.js'), true);
        $nonce = wp_create_nonce('bina_forgot_password_nonce');
        ?>
        <div class="relative min-h-svh">
            <header class="absolute top-0 left-0 right-0 z-10 flex h-16 shrink-0 items-center justify-end px-4 md:px-6">
                <div class="flex items-center gap-3">
                    <button data-slot="dropdown-menu-trigger" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 h-9 px-4 py-2 has-[&gt;svg]:px-3" type="button" aria-haspopup="menu" aria-expanded="false" data-state="closed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-languages h-4 w-4"><path d="m5 8 6 6"></path><path d="m4 14 6-6 2-3"></path><path d="M2 5h12"></path><path d="M7 2h1"></path><path d="m22 22-5-10-5 10"></path><path d="M14 18h6"></path></svg>
                    </button>
                    <button data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 size-9 relative overflow-hidden transition-all" aria-label="Switch to light theme" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon h-[1.2rem] w-[1.2rem]"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></svg>
                    </button>
                </div>
            </header>
            <main class="min-h-svh">
                <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
                    <div class="w-full max-w-sm">
                        <div data-slot="card" class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border py-6 shadow-sm w-full max-w-md">
                            <div data-slot="card-header" class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-2 px-6 has-data-[slot=card-action]:grid-cols-[1fr_auto] [.border-b]:pb-6 text-center">
                                <h1 class="text-2xl font-bold"><?php echo esc_html($s['title'] ?? ''); ?></h1>
                                <p class="text-muted-foreground text-balance"><?php echo esc_html($s['desc'] ?? ''); ?></p>
                            </div>
                            <div data-slot="card-content" class="px-4 md:px-6">
                                <form class="flex flex-col gap-6" method="post" action="#" data-bina-forgot-form data-forgot-nonce="<?php echo esc_attr($nonce); ?>">
                                    <div data-slot="field-group" class="group/field-group @container/field-group flex w-full flex-col gap-7 data-[slot=checkbox-group]:gap-3 [&amp;&gt;[data-slot=field-group]]:gap-4">
                                        <div data-slot="form-item" class="grid gap-2">
                                            <label data-slot="form-label" class="flex items-center gap-2 text-sm leading-none font-medium select-none"><?php echo esc_html($s['email_label'] ?? ''); ?></label>
                                            <input type="text" data-slot="form-control" class="file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive" inputmode="email" autocapitalize="none" placeholder="<?php echo esc_attr($s['email_placeholder'] ?? ''); ?>" name="user_login" value="">
                                            <p data-slot="form-description" class="text-muted-foreground text-sm"><?php echo esc_html($s['email_help'] ?? ''); ?></p>
                                            <p class="text-sm text-red-500 mt-1" data-error-for="email" style="display:none"></p>
                                        </div>
                                        <div role="group" data-slot="field" data-orientation="vertical" class="group/field flex w-full gap-3 data-[invalid=true]:text-destructive flex-col [&amp;&gt;*]:w-full [&amp;&gt;.sr-only]:w-auto">
                                            <button data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 py-2 has-[&gt;svg]:px-3 w-full" type="submit"><?php echo esc_html($s['button_text'] ?? ''); ?></button>
                                        </div>
                                        <p data-slot="field-description" class="text-muted-foreground text-sm leading-normal font-normal [&amp;&gt;a:hover]:text-primary [&amp;&gt;a]:underline [&amp;&gt;a]:underline-offset-4 px-6 text-center">
                                            تذكرت كلمة المرور؟
                                            <a href="<?php echo esc_url($s['login_url']['url'] ?? '#'); ?>"><?php echo esc_html($s['login_text'] ?? ''); ?></a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php
    }
}

