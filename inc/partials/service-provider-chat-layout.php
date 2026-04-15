<?php
/**
 * Service provider subpage shell (chat): sidebar + main chrome only.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param array<string,mixed> $args {
 *   @type WP_User $user
 *   @type array   $urls
 *   @type array   $stats
 *   @type string  $logo_url
 *   @type string  $help_url
 *   @type string  $active_nav dashboard|browse|my_projects|profile|offers|chat|payments|notifications
 * }
 * @return void
 */
function bina_render_service_provider_chat_layout_start( $args ) {
	$user     = isset( $args['user'] ) ? $args['user'] : null;
	$urls     = isset( $args['urls'] ) && is_array( $args['urls'] ) ? $args['urls'] : array();
	$stats    = isset( $args['stats'] ) && is_array( $args['stats'] ) ? $args['stats'] : array();
	$logo_url = isset( $args['logo_url'] ) ? (string) $args['logo_url'] : '';
	$help_url = isset( $args['help_url'] ) ? (string) $args['help_url'] : bina_dashboard_resolve_url( 'https://wa.me/966590000474' );
	$active   = isset( $args['active_nav'] ) ? (string) $args['active_nav'] : 'chat';

	if ( ! $user instanceof WP_User ) {
		return;
	}

	$logout_url_sp = wp_logout_url( isset( $urls['dashboard'] ) ? $urls['dashboard'] : home_url( '/' ) );
	$u_name        = bina_dashboard_user_display_name( $user );
	$u_email       = $user->user_email;
	$u_init        = esc_html( bina_dashboard_user_initial( $user ) );

	$nav_item = static function ( $key, $current, $url, $label ) {
		$is   = ( $current === $key );
		$base = 'peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm';
		$cls  = $is
			? $base . ' bg-sidebar-accent font-medium text-sidebar-accent-foreground'
			: $base . ' hover:bg-sidebar-accent hover:text-sidebar-accent-foreground';

		$icon = '';
		switch ( (string) $key ) {
			case 'dashboard':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path><path d="M13.45 11.55l2.05 -2.05"></path><path d="M6.4 20a9 9 0 1 1 11.2 0z"></path></svg>';
				break;
			case 'browse':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M10.5 4a6.5 6.5 0 1 0 3.5 11.5L21 21"></path><path d="M10.5 4a6.5 6.5 0 0 1 0 13"></path></svg>';
				break;
			case 'my_projects':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>';
				break;
			case 'profile':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
				break;
			case 'offers':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M20.59 13.41 17 17H7l-4 4V7l4-4h10l3 3z"></path><path d="M8 8h8v8H8z"></path></svg>';
				break;
			case 'chat':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10"></path><path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2"></path></svg>';
				break;
			case 'conflicts':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M12 3v6"></path><path d="M12 15v6"></path><path d="M5 12h14"></path><path d="M7 7l10 10"></path><path d="M7 17l10-10"></path></svg>';
				break;
			case 'payments':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M2 8h20v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"></path><path d="M2 8l3-5h14l3 5"></path><path d="M12 12v4"></path></svg>';
				break;
			case 'notifications':
				$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M10.268 21a2 2 0 0 0 3.464 0"></path><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path></svg>';
				break;
			default:
				$icon = '';
				break;
		}

		echo '<li><a class="' . esc_attr( $cls ) . '" href="' . esc_url( $url ) . '">' . $icon . '<span class="truncate leading-none">' . esc_html( $label ) . '</span></a></li>';
	};
	?>
<style>
@media (max-width: 767px) {
	[data-bina-dashboard-shell].bina-dashboard-sidebar-open [data-bina-sidebar-container] {
		display: flex !important;
		z-index: 40;
	}
	[data-bina-dashboard-shell].bina-dashboard-sidebar-open [data-bina-portal-backdrop],
	[data-bina-dashboard-shell].bina-dashboard-sidebar-open [data-bina-sp-backdrop] {
		display: block !important;
	}
}
</style>
<div data-bina-dashboard-shell class="bina-service-provider-portal group/sidebar-wrapper flex flex-row min-h-svh w-full max-w-[100vw] bg-muted/30 overflow-x-hidden" style="--sidebar-width: 17rem;">
	<div data-bina-sp-backdrop class="fixed inset-0 z-[35] hidden bg-black/50 md:hidden" aria-hidden="true"></div>
	<div class="group peer text-sidebar-foreground pointer-events-none md:pointer-events-auto" data-state="expanded" data-variant="inset" data-side="right" data-slot="sidebar">
		<div data-slot="sidebar-gap" class="relative hidden md:block shrink-0 bg-transparent transition-[width] duration-500 ease-out" style="width: var(--sidebar-width);"></div>
		<div data-bina-sidebar-container data-slot="sidebar-container" class="pointer-events-auto fixed inset-y-0 z-[40] hidden h-svh transition-[left,right,width] duration-500 ease-out md:flex right-0 w-[min(100vw,17rem)] max-w-[85vw] md:max-w-none md:w-[17rem] p-2 border-l border-white/10 shadow-xl" style="background: linear-gradient(180deg, #14181F 0%, #0d1015 100%); width: 100%;">
			<div data-sidebar="sidebar" class="flex h-full w-full flex-col">
				<div class="flex flex-col gap-2 p-2">
					<ul class="flex w-full min-w-0 flex-col gap-1">
						<li class="relative">
							<a class="flex w-full items-center gap-2 rounded-md p-2 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground h-8 text-sm" href="<?php echo esc_url( $urls['dashboard'] ?? '' ); ?>">
								<?php if ( $logo_url ) : ?>
									<img alt="" class="h-9 w-9 shrink-0 rounded-sm object-cover" src="<?php echo esc_url( $logo_url ); ?>" width="36" height="36" loading="lazy" />
								<?php else : ?>
									<span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-primary/20 text-primary font-bold text-sm ring-1 ring-primary/30"><?php esc_html_e( 'ب', 'bina' ); ?></span>
								<?php endif; ?>
								<span class="min-w-0 flex-1 truncate text-lg font-semibold"><?php esc_html_e( 'بناء', 'bina' ); ?></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="flex min-h-0 flex-1 flex-col gap-1 overflow-auto py-2 px-2">
					<ul class="flex w-full min-w-0 flex-col gap-0.5 text-sm">
						<?php
						$nav_item( 'dashboard', $active, $urls['dashboard'] ?? '#', __( 'لوحة التحكم', 'bina' ) );
						$nav_item( 'browse', $active, $urls['browse_projects'] ?? '#', __( 'تصفح المشاريع', 'bina' ) );
						$nav_item( 'my_projects', $active, $urls['my_projects'] ?? '#', __( 'مشاريعي', 'bina' ) );
						$nav_item( 'profile', $active, $urls['profile'] ?? '#', __( 'الملف الشخصي', 'bina' ) );
						$nav_item( 'conflicts', $active, $urls['conflicts'] ?? '#', __( 'النزاعات', 'bina' ) );
						$nav_item( 'offers', $active, $urls['offers'] ?? '#', __( 'عروضي', 'bina' ) );
						$nav_item( 'chat', $active, $urls['chat'] ?? '#', __( 'المحادثات', 'bina' ) );
						$nav_item( 'payments', $active, $urls['payments'] ?? '#', __( 'المدفوعات', 'bina' ) );
						$nav_item( 'notifications', $active, $urls['notifications'] ?? '#', __( 'الإشعارات', 'bina' ) );
						?>
					</ul>
					<div class="mt-auto pt-2">
						<a href="<?php echo esc_url( $help_url ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 rounded-md p-2 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"><?php esc_html_e( 'الحصول على المساعدة', 'bina' ); ?></a>
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
					<a class="block rounded-md py-2 px-2 text-sm text-white/70 hover:text-white hover:bg-white/10 text-start" href="<?php echo esc_url( $logout_url_sp ); ?>"><?php esc_html_e( 'تسجيل الخروج', 'bina' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<main class="bg-background relative z-0 flex flex-1 min-h-0 min-w-0 w-full flex-col overflow-hidden border border-border/60 md:rounded-s-2xl md:shadow-sm">
		<header class="sticky top-0 z-20 relative flex h-14 sm:h-16 shrink-0 items-center justify-between gap-2 border-b border-border/70 bg-background/90 px-3 sm:px-4 backdrop-blur-md supports-[backdrop-filter]:bg-background/75">
			<div class="flex items-center gap-2">
				<button type="button" data-bina-dashboard-sidebar-trigger class="inline-flex md:hidden items-center justify-center rounded-md text-sm font-medium hover:bg-accent size-7" aria-label="<?php esc_attr_e( 'القائمة', 'bina' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M9 3v18"></path></svg>
				</button>
			</div>
			<?php include get_template_directory() . '/inc/partials/dashboard-top-mini-header.php'; ?>
		</header>
		<div class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden px-3 sm:px-6 pb-8 pt-2">
			<div class="mx-auto w-full max-w-6xl min-w-0 space-y-6 sm:space-y-8">
	<?php
}

/**
 * @return void
 */
function bina_render_service_provider_chat_layout_end() {
	?>
			</div>
		</div>
	</main>
</div>
	<?php
}
