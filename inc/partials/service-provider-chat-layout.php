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
 *   @type string  $active_nav dashboard|browse|my_projects|profile|subscription|offers|chat|conflicts|payments|notifications
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
		$cls  = $is
			? 'flex items-center gap-2 rounded-md p-2 bg-sidebar-accent font-medium text-sidebar-accent-foreground'
			: 'flex items-center gap-2 rounded-md p-2 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground';
		echo '<li><a class="' . esc_attr( $cls ) . '" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
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
		<div data-slot="sidebar-gap" class="relative hidden md:block shrink-0 bg-transparent transition-[width] duration-200 ease-linear" style="width: var(--sidebar-width);"></div>
		<div data-bina-sidebar-container data-slot="sidebar-container" class="pointer-events-auto fixed inset-y-0 z-[40] hidden h-svh transition-[left,right,width] duration-200 ease-linear md:flex right-0 w-[min(100vw,17rem)] max-w-[85vw] md:max-w-none md:w-[17rem] p-2 border-l border-white/10 shadow-xl" style="background: linear-gradient(180deg, #14181F 0%, #0d1015 100%); width: 100%;">
			<div data-sidebar="sidebar" class="flex h-full w-full flex-col">
				<div class="flex flex-col gap-2 p-2">
					<ul class="flex w-full min-w-0 flex-col gap-1">
						<li class="relative">
							<a class="flex w-full items-center gap-2 rounded-md p-2 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground h-8 text-sm" href="<?php echo esc_url( $urls['dashboard'] ?? '' ); ?>">
								<?php if ( $logo_url ) : ?>
									<img alt="" class="h-9 w-9 shrink-0 rounded-sm object-cover" src="<?php echo esc_url( $logo_url ); ?>" width="36" height="36" loading="lazy" />
								<?php endif; ?>
								<span class="min-w-0 flex-1 truncate text-lg font-semibold"><?php esc_html_e( 'بناء', 'bina' ); ?></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="flex min-h-0 flex-1 flex-col gap-2 overflow-auto p-2">
					<ul class="flex w-full min-w-0 flex-col gap-1 text-sm">
						<?php
						$nav_item( 'dashboard', $active, $urls['dashboard'] ?? '#', __( 'لوحة التحكم', 'bina' ) );
						$nav_item( 'browse', $active, $urls['browse_projects'] ?? '#', __( 'تصفح المشاريع', 'bina' ) );
						$nav_item( 'my_projects', $active, $urls['my_projects'] ?? '#', __( 'مشاريعي', 'bina' ) );
						$nav_item( 'profile', $active, $urls['profile'] ?? '#', __( 'الملف الشخصي', 'bina' ) );
						$nav_item( 'subscription', $active, $urls['subscription'] ?? '#', __( 'الاشتراك', 'bina' ) );
						$nav_item( 'offers', $active, $urls['offers'] ?? '#', __( 'عروضي', 'bina' ) );
						$nav_item( 'chat', $active, $urls['chat'] ?? '#', __( 'المحادثات', 'bina' ) );
						$nav_item( 'conflicts', $active, $urls['conflicts'] ?? '#', __( 'النزاعات', 'bina' ) );
						$nav_item( 'payments', $active, $urls['payments'] ?? '#', __( 'المدفوعات', 'bina' ) );
						$nav_item( 'notifications', $active, $urls['notifications'] ?? '#', __( 'الإشعارات', 'bina' ) );
						?>
					</ul>
					<div class="mt-auto pt-2">
						<a href="<?php echo esc_url( $help_url ); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 rounded-md p-2 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"><?php esc_html_e( 'الحصول على المساعدة', 'bina' ); ?></a>
					</div>
				</div>
				<div class="flex flex-col gap-2 p-2 overflow-hidden border-t border-white/10">
					<div class="flex w-full items-center gap-2 rounded-md p-2 text-left text-sm">
						<span class="flex size-8 shrink-0 items-center justify-center rounded-lg bg-muted"><?php echo $u_init; ?></span>
						<div class="grid min-w-0 flex-1 text-left leading-tight">
							<span class="truncate font-medium"><?php echo esc_html( $u_name ); ?></span>
							<span class="text-muted-foreground truncate text-xs"><?php echo esc_html( $u_email ); ?></span>
						</div>
					</div>
					<a class="rounded-md p-2 text-sm text-muted-foreground hover:text-foreground hover:bg-sidebar-accent" href="<?php echo esc_url( $logout_url_sp ); ?>"><?php esc_html_e( 'تسجيل الخروج', 'bina' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<main class="bg-background relative z-0 flex flex-1 min-h-0 min-w-0 w-full flex-col overflow-hidden border border-border/60 md:rounded-s-2xl md:shadow-sm">
		<header class="sticky top-0 z-20 flex h-14 sm:h-16 shrink-0 items-center justify-between gap-2 border-b border-border/70 bg-background/90 px-3 sm:px-4 backdrop-blur-md supports-[backdrop-filter]:bg-background/75">
			<div class="flex items-center gap-2">
				<button type="button" data-bina-dashboard-sidebar-trigger class="inline-flex md:hidden items-center justify-center rounded-md text-sm font-medium hover:bg-accent size-7" aria-label="<?php esc_attr_e( 'القائمة', 'bina' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-4"><rect width="18" height="18" x="3" y="3" rx="2"></rect><path d="M9 3v18"></path></svg>
				</button>
			</div>
			<div class="flex items-center gap-3">
				<a class="relative inline-flex items-center justify-center rounded-md border bg-background shadow-xs hover:bg-accent size-9" href="<?php echo esc_url( $urls['notifications'] ?? '#' ); ?>" aria-label="<?php esc_attr_e( 'الإشعارات', 'bina' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5"><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path></svg>
					<?php if ( ! empty( $stats['notifications_bell'] ) ) : ?>
						<span class="absolute top-1 end-1 min-w-[1rem] h-4 px-1 rounded-full bg-destructive text-[10px] text-white flex items-center justify-center"><?php echo (int) $stats['notifications_bell']; ?></span>
					<?php endif; ?>
				</a>
			</div>
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
