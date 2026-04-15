<?php
/**
 * Shared customer portal layout: sidebar + main chrome (used on all customer app pages).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sidebar nav link classes (active vs hover).
 *
 * @param string $nav_key   Nav id: dashboard, profile, my_projects, chat, notifications.
 * @param string $active_nav Current page nav id.
 * @return string
 */
function bina_customer_portal_sidebar_nav_class( $nav_key, $active_nav ) {
	$base = 'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm items-center gap-2';
	if ( $active_nav === $nav_key ) {
		return $base . ' bg-sidebar-accent font-medium text-sidebar-accent-foreground';
	}
	return $base . ' hover:bg-sidebar-accent hover:text-sidebar-accent-foreground';
}

/**
 * @param array<string,mixed> $args {
 *   @type WP_User $user
 *   @type array   $urls
 *   @type string  $logo_url
 *   @type string  $help_url
 *   @type array   $stats
 *   @type string  $active_nav dashboard|profile|my_projects|chat|disputes|notifications
 * }
 * @return void
 */
function bina_render_customer_portal_shell_start( $args ) {
	$user       = isset( $args['user'] ) ? $args['user'] : null;
	$urls       = isset( $args['urls'] ) && is_array( $args['urls'] ) ? $args['urls'] : bina_get_customer_portal_default_urls();
	$logo_url   = isset( $args['logo_url'] ) ? (string) $args['logo_url'] : '';
	$help_url   = isset( $args['help_url'] ) ? (string) $args['help_url'] : bina_dashboard_resolve_url( 'https://wa.me/966590000474' );
	$stats      = isset( $args['stats'] ) && is_array( $args['stats'] ) ? $args['stats'] : array();
	$active_nav = isset( $args['active_nav'] ) ? (string) $args['active_nav'] : 'dashboard';

	if ( ! $user instanceof WP_User ) {
		return;
	}

	bina_customer_portal_enqueue_shell_assets();

	$u_name   = bina_dashboard_user_display_name( $user );
	$u_email  = $user->user_email;
	$u_init   = esc_html( bina_dashboard_user_initial( $user ) );
	$logout   = wp_logout_url( $urls['dashboard'] );
	$dash_url = isset( $urls['dashboard'] ) ? $urls['dashboard'] : bina_get_customer_dashboard_url();
	?>
<style>
@media (max-width: 767px) {
	[data-bina-dashboard-shell] [data-bina-sidebar-container] {
		display: flex !important;
		opacity: 0;
		visibility: hidden;
		pointer-events: none;
		transform: translate3d(100%, 0, 0);
		transition: opacity .42s cubic-bezier(.22,.61,.36,1), transform .42s cubic-bezier(.22,.61,.36,1), visibility 0s linear .42s;
	}
	[data-bina-dashboard-shell].bina-dashboard-sidebar-open [data-bina-sidebar-container] {
		opacity: 1;
		visibility: visible;
		pointer-events: auto;
		transform: translate3d(0, 0, 0);
		transition-delay: 0s;
		z-index: 40;
	}
	[data-bina-dashboard-shell] [data-bina-portal-backdrop] {
		display: block !important;
		opacity: 0;
		visibility: hidden;
		pointer-events: none;
		transition: opacity .32s cubic-bezier(.22,.61,.36,1), visibility 0s linear .32s;
	}
	[data-bina-dashboard-shell].bina-dashboard-sidebar-open [data-bina-portal-backdrop] {
		opacity: 1;
		visibility: visible;
		pointer-events: auto;
		transition-delay: 0s;
	}
}
</style>
<div data-bina-dashboard-shell class="bina-customer-portal group/sidebar-wrapper flex flex-row min-h-svh w-full max-w-[100vw] bg-muted/30 overflow-x-hidden" style="--sidebar-width: 17rem; --sidebar-width-icon: 3rem;">
	<div data-bina-portal-backdrop class="fixed inset-0 z-[35] hidden bg-black/50 md:hidden" aria-hidden="true"></div>
	<div class="group peer text-sidebar-foreground pointer-events-none md:pointer-events-auto" data-state="expanded" data-variant="inset" data-side="right" data-slot="sidebar">
		<div data-slot="sidebar-gap" class="relative hidden md:block w-[--sidebar-width] bg-transparent transition-[width] duration-500 ease-out shrink-0"></div>
		<div data-bina-sidebar-container data-slot="sidebar-container" class="pointer-events-auto fixed inset-y-0 z-[40] hidden h-svh w-[min(100vw,17rem)] max-w-[85vw] transition-[left,right,width] duration-500 ease-out md:flex right-0 p-2 md:w-[--sidebar-width] md:max-w-none border-l border-white/10 shadow-2xl md:shadow-none" style="background: linear-gradient(180deg, #14181F 0%, #0d1015 100%); width: 100%;">
			<div data-sidebar="sidebar" class="flex h-full w-full flex-col rounded-xl md:rounded-lg border border-white/10 overflow-hidden">
				<div class="flex flex-col gap-2 p-3 border-b border-white/10">
					<ul class="flex w-full min-w-0 flex-col gap-1">
						<li class="relative">
							<a class="flex w-full items-center gap-2 rounded-md p-2 text-start hover:bg-white/5 transition-colors" href="<?php echo esc_url( $dash_url ); ?>">
								<?php if ( $logo_url ) : ?>
									<img alt="" class="h-9 w-9 shrink-0 rounded-md object-cover ring-1 ring-white/10" src="<?php echo esc_url( $logo_url ); ?>" width="36" height="36" loading="lazy" />
								<?php else : ?>
									<span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-primary/20 text-primary font-bold text-sm ring-1 ring-primary/30"><?php esc_html_e( 'ب', 'bina' ); ?></span>
								<?php endif; ?>
								<span class="min-w-0 flex-1 truncate text-lg font-semibold text-white"><?php esc_html_e( 'بناء', 'bina' ); ?></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="flex min-h-0 flex-1 flex-col gap-1 overflow-auto py-2 px-2">
					<ul class="flex w-full min-w-0 flex-col gap-0.5 text-sm">
						<li>
							<a class="<?php echo esc_attr( bina_customer_portal_sidebar_nav_class( 'dashboard', $active_nav ) ); ?>" href="<?php echo esc_url( $dash_url ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path><path d="M13.45 11.55l2.05 -2.05"></path><path d="M6.4 20a9 9 0 1 1 11.2 0z"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'لوحة التحكم', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="<?php echo esc_attr( bina_customer_portal_sidebar_nav_class( 'profile', $active_nav ) ); ?>" href="<?php echo esc_url( $urls['profile'] ?? bina_get_customer_profile_url() ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M8 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'الملف الشخصي', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="<?php echo esc_attr( bina_customer_portal_sidebar_nav_class( 'disputes', $active_nav ) ); ?>" href="<?php echo esc_url( $urls['disputes'] ?? '#' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M12 3v6"></path><path d="M12 15v6"></path><path d="M5 12h14"></path><path d="M7 7l10 10"></path><path d="M7 17l10-10"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'النزاعات', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="<?php echo esc_attr( bina_customer_portal_sidebar_nav_class( 'my_projects', $active_nav ) ); ?>" href="<?php echo esc_url( $urls['my_projects'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'المشاريع الخاصة بي', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="<?php echo esc_attr( bina_customer_portal_sidebar_nav_class( 'chat', $active_nav ) ); ?>" href="<?php echo esc_url( $urls['chat'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10"></path><path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'المحادثات', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="<?php echo esc_attr( bina_customer_portal_sidebar_nav_class( 'notifications', $active_nav ) ); ?>" href="<?php echo esc_url( $urls['notifications'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M10.268 21a2 2 0 0 0 3.464 0"></path><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'الإشعارات', 'bina' ); ?></span>
							</a>
						</li>
					</ul>
					<div class="mt-auto pt-4 border-t border-white/10">
						<a href="<?php echo esc_url( $help_url ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 rounded-md p-2 text-sm text-white/80 hover:bg-white/10 hover:text-white">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4"><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M12 17l0 .01"></path><path d="M12 13.5a1.5 1.5 0 0 1 1 -1.5a2.6 2.6 0 1 0 -3 -4"></path></svg>
							<span><?php esc_html_e( 'الحصول على المساعدة', 'bina' ); ?></span>
						</a>
					</div>
				</div>
				<div class="flex flex-col gap-2 p-3 border-t border-white/10 bg-black/20">
					<div class="flex w-full items-center gap-2 rounded-md text-sm min-w-0">
						<span class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-white/10 text-white font-medium"><?php echo $u_init; ?></span>
						<div class="grid min-w-0 flex-1 text-start leading-tight">
							<span class="truncate font-medium text-white"><?php echo esc_html( $u_name ); ?></span>
							<span class="text-white/60 truncate text-xs"><?php echo esc_html( $u_email ); ?></span>
						</div>
					</div>
					<a class="block rounded-md py-2 px-2 text-sm text-white/70 hover:text-white hover:bg-white/10 text-start" href="<?php echo esc_url( $logout ); ?>"><?php esc_html_e( 'تسجيل الخروج', 'bina' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<main class="relative flex flex-1 min-h-0 min-w-0 w-full flex-col bg-background md:rounded-s-2xl md:ms-0 md:shadow-sm border border-border/60 overflow-hidden">
		<header class="sticky top-0 z-20 relative flex h-14 sm:h-16 shrink-0 items-center justify-between gap-2 border-b border-border/70 bg-background/90 px-3 sm:px-4 backdrop-blur-md supports-[backdrop-filter]:bg-background/75">
			<div class="flex items-center gap-2 min-w-0">
				<button type="button" data-bina-dashboard-sidebar-trigger class="inline-flex md:hidden items-center justify-center rounded-lg text-sm font-medium transition-colors hover:bg-muted size-9 shrink-0 border border-border/80" aria-label="<?php esc_attr_e( 'القائمة', 'bina' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M9 3v18"></path></svg>
				</button>
				<span class="text-sm font-medium text-muted-foreground truncate hidden sm:inline"><?php esc_html_e( 'بوابة العميل', 'bina' ); ?></span>
			</div>
			<?php include get_template_directory() . '/inc/partials/dashboard-top-mini-header.php'; ?>
		</header>
		<div class="bina-portal-main-scroll flex-1 overflow-y-auto overflow-x-hidden">
			<div class="mx-auto w-full max-w-6xl min-w-0 px-3 sm:px-6 py-6 sm:py-8 space-y-6">
	<?php
}

/**
 * @return void
 */
function bina_render_customer_portal_shell_end() {
	?>
			</div>
		</div>
	</main>
</div>
	<?php
}
