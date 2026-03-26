<?php
if (!defined('ABSPATH')) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

class bina_Register_Form_Widget extends Widget_Base {
    public function get_name() { return 'bina_register_form'; }
    public function get_title() { return __('Register Form (Static)', 'bina'); }
    public function get_icon() { return 'eicon-lock-user'; }
    public function get_categories() { return ['general']; }

    protected function _register_controls() {
        $this->start_controls_section('sec_content', ['label' => __('Content', 'bina')]);
        $this->add_control('title', ['label' => __('Title', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'إنشاء حسابك']);
        $this->add_control('desc', ['label' => __('Description', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'املأ النموذج أدناه لإنشاء حسابك']);
        $this->add_control('login_text', ['label' => __('Login Link Text', 'bina'), 'type' => Controls_Manager::TEXT, 'default' => 'لديك حساب بالفعل؟ تسجيل الدخول']);
        $this->add_control('login_url', ['label' => __('Login URL', 'bina'), 'type' => Controls_Manager::URL, 'default' => ['url' => '/login']]);
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
        wp_enqueue_script('bina-register-js', get_template_directory_uri() . '/assets/js/register.js', array(), filemtime(get_template_directory() . '/assets/js/register.js'), true);
        ?>
        <div class="relative min-h-svh bina-register-widget">
            <main class="min-h-svh">
                <div class="grid min-h-svh lg:grid-cols-2">
                    <div class="flex flex-col gap-4 p-6 md:p-10 overflow-y-auto">
                        <div class="flex flex-1 items-start justify-center py-8">
                            <div class="w-full max-w-md">
                                <form class="flex flex-col gap-6" data-bina-register-form>
                                    <div data-slot="field-group" class="group/field-group @container/field-group flex w-full flex-col gap-6">
                                        <div class="flex flex-col items-center gap-1 text-center">
                                            <h1 class="text-2xl font-bold"><?php echo esc_html($s['title'] ?? ''); ?></h1>
                                            <p class="text-muted-foreground text-sm text-balance"><?php echo esc_html($s['desc'] ?? ''); ?></p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div data-slot="form-item" class="gap-2 flex flex-col">
                                                <label class="flex items-center gap-2 text-sm leading-none font-medium">الاسم الأول</label>
                                                <input class="file:text-foreground placeholder:text-muted-foreground border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none md:text-sm" placeholder="الاسم الأول" name="firstName">
                                                <p class="text-destructive text-sm min-h-[1.2rem]" data-error-for="firstName"></p>
                                            </div>
                                            <div data-slot="form-item" class="gap-2 flex flex-col">
                                                <label class="flex items-center gap-2 text-sm leading-none font-medium">الاسم الأخير</label>
                                                <input class="file:text-foreground placeholder:text-muted-foreground border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none md:text-sm" placeholder="الاسم الأخير" name="lastName">
                                                <div class="min-h-[1.2rem]"></div>
                                            </div>
                                        </div>
                                        <div data-slot="form-item" class="grid gap-2">
                                            <label class="flex items-center gap-2 text-sm leading-none font-medium">عنوان البريد الإلكتروني</label>
                                            <input type="text" class="file:text-foreground placeholder:text-muted-foreground border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none md:text-sm" inputmode="email" autocapitalize="none" placeholder="m@example.com" name="email">
                                            <p class="text-muted-foreground text-sm">سنستخدم هذا للتواصل معك. لن نشارك بريدك الإلكتروني مع أي شخص آخر.</p>
                                            <p class="text-destructive text-sm min-h-[1.2rem]" data-error-for="email"></p>
                                        </div>
                                        <div data-slot="form-item" class="grid gap-2">
                                            <label class="flex items-center gap-2 text-sm leading-none font-medium">رقم الهاتف</label>
                                            <input type="tel" class="file:text-foreground placeholder:text-muted-foreground border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none md:text-sm" placeholder="05xxxxxxxx" name="phone">
                                            <p class="text-muted-foreground text-sm">سنستخدم هذا للتواصل معك حول مشاريعك.</p>
                                            <p class="text-destructive text-sm min-h-[1.2rem]" data-error-for="phone"></p>
                                        </div>
                                        <div data-slot="form-item" class="grid gap-2">
                                            <label class="flex items-center gap-2 text-sm leading-none font-medium">المدينة<span class="text-destructive ms-1">*</span></label>
                                            <button data-city-trigger class="inline-flex items-center gap-2 whitespace-nowrap rounded-md text-sm font-medium border bg-background shadow-xs h-9 px-4 py-2 justify-between w-full" type="button">اختر المدينة</button>
                                            <input type="hidden" name="city" data-city-value value="">
                                            <p class="text-destructive text-sm min-h-[1.2rem]" data-error-for="city"></p>
                                        </div>
                                        <div data-slot="form-item" class="grid gap-2">
                                            <label class="flex items-center gap-2 text-sm leading-none font-medium">كلمة المرور</label>
                                            <div class="relative">
                                                <input type="password" class="file:text-foreground placeholder:text-muted-foreground border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none md:text-sm pr-10" placeholder="أدخل كلمة المرور" name="password">
                                                <button data-password-toggle class="inline-flex items-center justify-center size-9 text-muted-foreground absolute inset-y-0 right-0 rounded-l-none hover:bg-transparent" type="button" tabindex="-1"><span class="sr-only">Show password</span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
                                            </div>
                                            <p class="text-muted-foreground text-sm">يجب أن تكون 8 أحرف على الأقل مع أحرف كبيرة وصغيرة وأرقام.</p>
                                            <p class="text-destructive text-sm min-h-[1.2rem]" data-error-for="password"></p>
                                        </div>
                                        <div data-slot="form-item" class="grid gap-2">
                                            <label class="flex items-center gap-2 text-sm leading-none font-medium">تأكيد كلمة المرور</label>
                                            <div class="relative">
                                                <input type="password" class="file:text-foreground placeholder:text-muted-foreground border-input h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs outline-none md:text-sm pr-10" placeholder="أكد كلمة المرور" name="confirmPassword">
                                                <button data-password-toggle class="inline-flex items-center justify-center size-9 text-muted-foreground absolute inset-y-0 right-0 rounded-l-none hover:bg-transparent" type="button" tabindex="-1"><span class="sr-only">Show password</span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
                                            </div>
                                            <p class="text-muted-foreground text-sm">يرجى تأكيد كلمة المرور.</p>
                                            <p class="text-destructive text-sm min-h-[1.2rem]" data-error-for="confirmPassword"></p>
                                        </div>
                                        <div data-slot="form-item" class="grid gap-2">
                                            <label class="flex items-center gap-2 text-sm leading-none font-medium">نوع الحساب</label>
                                            <div role="radiogroup" dir="rtl" class="gap-3 flex flex-col space-y-1 mt-2">
                                                <label class="flex items-start gap-2 rounded-lg border bg-background p-4 rtl:flex-row-reverse cursor-pointer hover:bg-accent transition-colors">
                                                    <button type="button" role="radio" aria-checked="false" data-state="unchecked" value="customer" data-slot="radio-group-item" class="border-input text-primary aspect-square size-4 rounded-full border shadow-xs mt-0.5 shrink-0"></button>
                                                    <span class="text-sm font-medium leading-normal">صاحب عمل</span>
                                                </label>
                                                <label class="flex items-start gap-2 rounded-lg border bg-background p-4 rtl:flex-row-reverse cursor-pointer hover:bg-accent transition-colors">
                                                    <button type="button" role="radio" aria-checked="false" data-state="unchecked" value="service_provider" data-slot="radio-group-item" class="border-input text-primary aspect-square size-4 rounded-full border shadow-xs mt-0.5 shrink-0"></button>
                                                    <span class="text-sm font-medium leading-normal">مزود خدمة (مقاول - مهندس - مورد - استشاري)</span>
                                                </label>
                                            </div>
                                            <input type="hidden" name="accountType" data-account-type-value value="">
                                            <p class="text-muted-foreground text-sm">اختر ما إذا كنت تريد الحصول على الخدمات أو تقديم الخدمات.</p>
                                            <p class="text-destructive text-sm min-h-[1.2rem]" data-error-for="accountType"></p>
                                        </div>
                                        <div role="group" class="group/field flex w-full gap-3 flex-col">
                                            <button data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 py-2" type="submit">إنشاء حساب</button>
                                        </div>
                                        <p class="text-muted-foreground text-sm px-6 text-center">
                                            بإنشاء حساب، أنت توافق على
                                            <a href="<?php echo esc_url($s['terms_url']['url'] ?? '#'); ?>">شروط الخدمة</a>
                                            و
                                            <a href="<?php echo esc_url($s['privacy_url']['url'] ?? '#'); ?>">سياسة الخصوصية</a>.
                                        </p>
                                        <p class="text-muted-foreground text-sm px-6 text-center">
                                            <a href="<?php echo esc_url($s['login_url']['url'] ?? '#'); ?>"><?php echo esc_html($s['login_text'] ?? ''); ?></a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-muted relative hidden lg:block">
                        <div class="absolute inset-0 h-full w-full">
                            <img src="<?php echo esc_url($hero); ?>" alt="Image" class="h-full w-full object-cover dark:brightness-[0.6] dark:grayscale" style="object-position:20% 50%">
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php
    }
}

