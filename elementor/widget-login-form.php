<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

class bina_Login_Form_Widget extends Widget_Base {
    public function get_name() { return 'bina_login_form'; }
    public function get_title() { return __('Login Form (Static)', 'bina'); }
    public function get_icon() { return 'eicon-sign-out'; }
    public function get_categories() { return ['general']; }

    protected function _register_controls() {
        $this->start_controls_section('sec_content', ['label' => __('Content', 'bina')]);
        $this->add_control('title', ['label' => __('Title', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'مرحباً بعودتك']);
        $this->add_control('desc', ['label' => __('Description', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'قم بتسجيل الدخول إلى حسابك في بناء سنتر']);
        $this->add_control('forgot_url', ['label' => __('Forgot Password URL', 'bina'), 'type' => Controls_Manager::URL, 'default' => ['url' => '/forgot-password']]);
        $this->add_control('register_url', ['label' => __('Register URL', 'bina'), 'type' => Controls_Manager::URL, 'default' => ['url' => '/register']]);
        $this->add_control('terms_url', ['label' => __('Terms URL', 'bina'), 'type' => Controls_Manager::URL, 'default' => ['url' => '/terms-and-condition/']]);
        $this->add_control('privacy_url', ['label' => __('Privacy URL', 'bina'), 'type' => Controls_Manager::URL, 'default' => ['url' => '/privacy-policy/']]);
        $this->add_control('hero_image', [
            'label' => __('Side Image', 'bina'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $hero = !empty($s['hero_image']['url']) ? $s['hero_image']['url'] : Utils::get_placeholder_image_src();
        wp_enqueue_script('bina-login-js', get_template_directory_uri() . '/assets/js/login.js', array(), filemtime(get_template_directory() . '/assets/js/login.js'), true);
        ?>
        <div class="relative min-h-svh bina-login-widget">
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
                <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
                    <div class="w-full max-w-sm md:max-w-4xl" style="max-width:52rem;">
                        <div class="flex flex-col gap-6">
                            <div data-slot="card" class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border shadow-sm overflow-hidden p-0">
                                <div data-slot="card-content" class="grid px-0 md:px-0 md:grid-cols-2">
                                    <form class="p-6 md:p-8" data-bina-login-form>
                                        <div data-slot="field-group" class="group/field-group @container/field-group flex w-full flex-col gap-8">
                                            <div class="flex flex-col items-center gap-2 text-center">
                                                <h1 class="text-2xl font-bold"><?php echo esc_html($s['title'] ?? ''); ?></h1>
                                                <p class="text-muted-foreground text-balance"><?php echo esc_html($s['desc'] ?? ''); ?></p>
                                            </div>
                                            <div role="group" data-slot="field" class="group/field flex w-full gap-3 flex-col">
                                                <label class="items-center text-sm font-medium flex w-fit gap-2 leading-snug">البريد الإلكتروني أو رقم الهاتف</label>
                                                <input type="text" data-slot="input" class="file:text-foreground placeholder:text-muted-foreground border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none md:text-sm" inputmode="email" autocapitalize="none" autocomplete="username" placeholder="name@example.com أو username أو 05xxxxxxxx" name="identifier">
                                                <p class="text-sm text-red-500 mt-1" data-error-for="identifier" style="display:none"></p>
                                            </div>
                                            <div role="group" data-slot="field" class="group/field flex w-full gap-3 flex-col">
                                                <div class="flex items-center justify-between">
                                                    <label class="items-center text-sm font-medium flex w-fit gap-2 leading-snug">كلمة المرور</label>
                                                    <a href="<?php echo esc_url($s['forgot_url']['url'] ?? '#'); ?>" class="ms-auto text-sm underline-offset-2 hover:underline">نسيت كلمة المرور؟</a>
                                                </div>
                                                <div class="relative">
                                                    <input type="password" data-slot="input" class="file:text-foreground placeholder:text-muted-foreground border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none md:text-sm pr-10" placeholder="أدخل كلمة المرور" autocomplete="current-password" name="password">
                                                    <button data-password-toggle data-slot="button" class="inline-flex items-center justify-center size-9 text-muted-foreground absolute inset-y-0 right-0 rounded-l-none hover:bg-transparent" type="button" tabindex="-1"><span class="sr-only">Show password</span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
                                                </div>
                                                <p class="text-sm text-red-500 mt-1" data-error-for="password" style="display:none"></p>
                                            </div>
                                            <div role="group" data-slot="field" class="group/field flex w-full gap-3 flex-col">
                                                <button data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 py-2 w-full" type="submit">تسجيل الدخول</button>
                                            </div>
                                            <p class="text-muted-foreground text-sm text-center">ليس لديك حساب؟ <a href="<?php echo esc_url($s['register_url']['url'] ?? '#'); ?>">سجل الآن</a></p>
                                        </div>
                                    </form>
                                    <div class="bg-muted relative hidden md:block">
                                        <img src="<?php echo esc_url($hero); ?>" alt="Image" class="absolute inset-0 h-full w-full object-cover dark:brightness-[0.5] dark:grayscale">
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted-foreground text-sm px-6 text-center">
                                بالنقر على متابعة، أنت توافق على
                                <a href="<?php echo esc_url($s['terms_url']['url'] ?? '#'); ?>">شروط الخدمة</a>
                                و
                                <a href="<?php echo esc_url($s['privacy_url']['url'] ?? '#'); ?>">سياسة الخصوصية</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php
    }
}

