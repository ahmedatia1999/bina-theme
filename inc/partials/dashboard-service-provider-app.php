<?php
/**
 * Service provider dashboard shell + home (contractor-style).
 *
 * @var WP_User $user
 * @var array   $stats
 * @var array   $urls
 * @var string  $logo_url
 * @var string  $help_url
 * @var array   $browse_status_counts Status breakdown for open marketplace projects.
 * @var WP_Post[] $recent_market_projects Recently updated open projects (not authored by user).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$browse_status_counts   = isset( $browse_status_counts ) && is_array( $browse_status_counts ) ? $browse_status_counts : array();
$recent_market_projects = isset( $recent_market_projects ) && is_array( $recent_market_projects ) ? $recent_market_projects : array();
$st_labels_sp           = bina_get_project_status_labels();
$logout_url_sp          = wp_logout_url( $urls['dashboard'] );
$verification_url       = add_query_arg( 'section', 'verification', isset( $urls['profile'] ) ? $urls['profile'] : '' ) . '#verification';

$u_name  = bina_dashboard_user_display_name( $user );
$u_email = $user->user_email;
$u_init  = esc_html( bina_dashboard_user_initial( $user ) );

$rate = isset( $stats['acceptance_rate'] ) ? (float) $stats['acceptance_rate'] : 0;

$verification_status_raw = strtolower( trim( (string) get_user_meta( $user->ID, 'bina_verification_status', true ) ) );
$verification_state      = 'unverified';
$verification_title      = __( 'غير موثق', 'bina' );
$verification_message    = __( 'أكمل توثيق حسابك لزيادة الثقة', 'bina' );
$verification_badge      = 'bg-amber-100 text-amber-800';

if ( in_array( $verification_status_raw, array( 'verified', 'approved', 'active' ), true ) ) {
	$verification_state   = 'verified';
	$verification_title   = __( 'موثق', 'bina' );
	$verification_message = __( 'حسابك موثق ويمكن للعملاء رؤية حالة التوثيق.', 'bina' );
	$verification_badge   = 'bg-emerald-100 text-emerald-800';
} elseif ( in_array( $verification_status_raw, array( 'pending', 'in_review', 'under_review', 'submitted' ), true ) ) {
	$verification_state   = 'pending';
	$verification_title   = __( 'قيد المراجعة', 'bina' );
	$verification_message = __( 'تم استلام طلب التوثيق وجارٍ مراجعته.', 'bina' );
	$verification_badge   = 'bg-blue-100 text-blue-800';
} elseif ( in_array( $verification_status_raw, array( 'rejected', 'declined' ), true ) ) {
	$verification_state   = 'rejected';
	$verification_title   = __( 'مرفوض', 'bina' );
	$verification_message = __( 'تم رفض طلب التوثيق. راجع البيانات ثم أعد الإرسال.', 'bina' );
	$verification_badge   = 'bg-rose-100 text-rose-800';
}
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
							<a class="flex w-full items-center gap-2 rounded-md p-2 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground h-8 text-sm" href="<?php echo esc_url( $urls['dashboard'] ); ?>">
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
						<li>
							<a class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm bg-sidebar-accent font-medium text-sidebar-accent-foreground" href="<?php echo esc_url( $urls['dashboard'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M12 13m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path><path d="M13.45 11.55l2.05 -2.05"></path><path d="M6.4 20a9 9 0 1 1 11.2 0z"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'لوحة التحكم', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="<?php echo esc_url( $urls['browse_projects'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M10.5 4a6.5 6.5 0 1 0 3.5 11.5L21 21"></path><path d="M10.5 4a6.5 6.5 0 0 1 0 13"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'تصفح المشاريع', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="<?php echo esc_url( $urls['my_projects'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M5 4h4l3 3h7a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-11a2 2 0 0 1 2 -2"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'مشاريعي', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="<?php echo esc_url( $urls['profile'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'الملف الشخصي', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="<?php echo esc_url( $urls['offers'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M20.59 13.41 17 17H7l-4 4V7l4-4h10l3 3z"></path><path d="M8 8h8v8H8z"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'عروضي', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="<?php echo esc_url( $urls['chat'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10"></path><path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'المحادثات', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="<?php echo esc_url( $urls['payments'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M2 8h20v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8z"></path><path d="M2 8l3-5h14l3 5"></path><path d="M12 12v4"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'المدفوعات', 'bina' ); ?></span>
							</a>
						</li>
						<li>
							<a class="peer/menu-button flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-start ring-sidebar-ring transition-[width,height,padding] focus-visible:ring-2 h-8 text-sm hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="<?php echo esc_url( $urls['notifications'] ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="shrink-0 size-4 opacity-90"><path d="M10.268 21a2 2 0 0 0 3.464 0"></path><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path></svg>
								<span class="truncate leading-none"><?php esc_html_e( 'الإشعارات', 'bina' ); ?></span>
							</a>
						</li>
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
				<div>
					<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'لوحة التحكم', 'bina' ); ?></h1>
					<p class="text-muted-foreground mt-1"><?php esc_html_e( 'نظرة عامة على عروضك ومشاريعك.', 'bina' ); ?></p>
				</div>

				<div class="grid gap-4 md:grid-cols-2">
					<a href="<?php echo esc_url( $urls['browse_projects'] ); ?>" class="block bg-card text-card-foreground rounded-xl border py-6 px-6 shadow-sm transition-all hover:shadow-md hover:border-primary/50">
						<div class="flex items-start justify-between gap-3">
							<div>
								<div class="font-semibold text-base"><?php esc_html_e( 'المشاريع', 'bina' ); ?></div>
								<p class="text-muted-foreground text-sm mt-1"><?php esc_html_e( 'تصفح المشاريع المتاحة', 'bina' ); ?></p>
							</div>
							<span class="text-2xl font-bold tabular-nums"><?php echo (int) $stats['browse_projects_count']; ?></span>
						</div>
					</a>
					<a href="<?php echo esc_url( $urls['payments'] ?? '#' ); ?>" class="block bg-card text-card-foreground rounded-xl border py-6 px-6 shadow-sm transition-all hover:shadow-md hover:border-primary/50">
						<div class="font-semibold text-base"><?php esc_html_e( 'المدفوعات', 'bina' ); ?></div>
						<p class="text-muted-foreground text-sm mt-1"><?php esc_html_e( 'الرصيد وطلبات السحب', 'bina' ); ?></p>
					</a>
				</div>

				<div class="grid gap-4 md:grid-cols-2">
					<a href="<?php echo esc_url( $urls['chat'] ); ?>" class="block bg-card rounded-xl border py-6 px-6 shadow-sm hover:border-primary/50">
						<div class="font-semibold"><?php esc_html_e( 'الرسائل', 'bina' ); ?></div>
						<p class="text-muted-foreground text-sm mt-1"><?php esc_html_e( 'تواصل مع العملاء', 'bina' ); ?></p>
					</a>
					<a href="<?php echo esc_url( $urls['notifications'] ); ?>" class="block bg-card rounded-xl border py-6 px-6 shadow-sm hover:border-primary/50">
						<div class="flex items-center justify-between">
							<div>
								<div class="font-semibold"><?php esc_html_e( 'الإشعارات', 'bina' ); ?></div>
								<p class="text-muted-foreground text-sm mt-1"><?php esc_html_e( 'متابعة التنبيهات', 'bina' ); ?></p>
							</div>
							<?php if ( ! empty( $stats['unread_notifications'] ) ) : ?>
								<span class="inline-flex min-w-[1.5rem] justify-center rounded-full bg-primary px-2 py-0.5 text-xs text-primary-foreground"><?php echo (int) $stats['unread_notifications']; ?></span>
							<?php endif; ?>
						</div>
					</a>
				</div>

				<div class="grid gap-4 md:grid-cols-2">
					<a href="<?php echo esc_url( $urls['conflicts'] ?? '#' ); ?>" class="block bg-card rounded-xl border py-6 px-6 shadow-sm hover:border-primary/50">
						<div class="font-semibold"><?php esc_html_e( 'النزاعات', 'bina' ); ?></div>
						<p class="text-muted-foreground text-sm mt-1"><?php esc_html_e( 'تقديم شكوى على مشروع', 'bina' ); ?></p>
					</a>
					<a href="<?php echo esc_url( $urls['my_projects'] ?? '#' ); ?>" class="block bg-card rounded-xl border py-6 px-6 shadow-sm hover:border-primary/50">
						<div class="font-semibold"><?php esc_html_e( 'مشاريعي', 'bina' ); ?></div>
						<p class="text-muted-foreground text-sm mt-1"><?php esc_html_e( 'عرض مشاريعك المسندة', 'bina' ); ?></p>
					</a>
				</div>

				<div class="grid gap-4 md:grid-cols-3">
					<div class="bg-card rounded-xl border py-6 px-6 shadow-sm">
						<div class="text-sm text-muted-foreground"><?php esc_html_e( 'إجمالي العروض', 'bina' ); ?></div>
						<div class="text-2xl font-bold mt-2 tabular-nums"><?php echo (int) $stats['total_offers']; ?></div>
					</div>
					<div class="bg-card rounded-xl border py-6 px-6 shadow-sm">
						<div class="text-sm text-muted-foreground"><?php esc_html_e( 'العروض المقبولة', 'bina' ); ?></div>
						<div class="text-2xl font-bold mt-2 tabular-nums"><?php echo (int) $stats['accepted_offers']; ?></div>
					</div>
					<div class="bg-card rounded-xl border py-6 px-6 shadow-sm">
						<div class="text-sm text-muted-foreground"><?php esc_html_e( 'المشاريع النشطة', 'bina' ); ?></div>
						<div class="text-2xl font-bold mt-2 tabular-nums"><?php echo (int) $stats['active_projects']; ?></div>
					</div>
				</div>

				<div class="grid gap-4 md:grid-cols-3">
					<div class="bg-card rounded-xl border py-6 px-6 shadow-sm">
						<div class="text-sm text-muted-foreground"><?php esc_html_e( 'معدل القبول', 'bina' ); ?></div>
						<div class="text-2xl font-bold mt-2 tabular-nums"><?php echo esc_html( (string) round( $rate, 1 ) ); ?>%</div>
						<p class="text-xs text-muted-foreground mt-1"><?php esc_html_e( 'نسبة العروض المقبولة', 'bina' ); ?></p>
					</div>
					<div class="bg-card rounded-xl border py-6 px-6 shadow-sm">
						<div class="text-sm text-muted-foreground"><?php esc_html_e( 'الرسائل غير المقروءة', 'bina' ); ?></div>
						<div class="text-2xl font-bold mt-2 tabular-nums"><?php echo (int) $stats['unread_messages']; ?></div>
					</div>
					<div class="bg-card rounded-xl border py-6 px-6 shadow-sm">
						<div class="text-sm text-muted-foreground"><?php esc_html_e( 'الإشعارات غير المقروءة', 'bina' ); ?></div>
						<div class="text-2xl font-bold mt-2 tabular-nums" data-bina-unread-notifications-card><?php echo (int) $stats['unread_notifications']; ?></div>
					</div>
				</div>

				<div class="grid gap-4 md:grid-cols-2">
					<div class="bg-card rounded-xl border py-6 shadow-sm">
						<div class="px-6 font-semibold"><?php esc_html_e( 'حالات المشاريع المتاحة', 'bina' ); ?></div>
						<p class="px-6 text-xs text-muted-foreground mt-1"><?php esc_html_e( 'توزيع الحالات ضمن المشاريع المفتوحة للتصفح (غير مشاريعك).', 'bina' ); ?></p>
						<div class="px-6 pt-4">
							<?php
							$sum_br = (int) array_sum( $browse_status_counts );
							if ( $sum_br < 1 ) :
								?>
								<p class="text-sm text-muted-foreground text-center py-6"><?php esc_html_e( 'لا توجد مشاريع مفتوحة للتصفح حالياً.', 'bina' ); ?></p>
							<?php else : ?>
								<ul class="space-y-3">
									<?php
									foreach ( $browse_status_counts as $key => $n ) {
										if ( $n < 1 || ! isset( $st_labels_sp[ $key ] ) ) {
											continue;
										}
										$pct = (int) round( 100 * (int) $n / max( 1, $sum_br ) );
										?>
										<li class="space-y-1">
											<div class="flex justify-between text-sm gap-2">
												<span class="text-muted-foreground"><?php echo esc_html( $st_labels_sp[ $key ] ); ?></span>
												<span class="font-medium tabular-nums"><?php echo (int) $n; ?></span>
											</div>
											<div class="h-2 w-full rounded-full bg-muted overflow-hidden">
												<div class="h-full rounded-full bg-primary transition-all" style="width: <?php echo esc_attr( (string) $pct ); ?>%;"></div>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							<?php endif; ?>
						</div>
					</div>
					<div class="bg-card rounded-xl border py-6 shadow-sm">
						<div class="px-6 font-semibold"><?php esc_html_e( 'أحدث المشاريع في السوق', 'bina' ); ?></div>
						<p class="px-6 text-xs text-muted-foreground mt-1"><?php esc_html_e( 'آخر التحديثات على المشاريع المتاحة.', 'bina' ); ?></p>
						<div class="px-6 pt-4">
							<?php if ( empty( $recent_market_projects ) ) : ?>
								<p class="text-sm text-muted-foreground text-center py-6"><?php esc_html_e( 'لا يوجد نشاط حديث', 'bina' ); ?></p>
							<?php else : ?>
								<ul class="space-y-3">
									<?php
									foreach ( $recent_market_projects as $mp ) {
										if ( ! $mp instanceof WP_Post ) {
											continue;
										}
										$city_raw = get_post_meta( $mp->ID, '_bina_city', true );
										$city_l   = $city_raw;
										foreach ( bina_get_cities_for_select() as $c ) {
											if ( isset( $c['value'] ) && $c['value'] === $city_raw ) {
												$city_l = isset( $c['label'] ) ? $c['label'] : $city_raw;
												break;
											}
										}
										$sk  = bina_get_project_status_meta( $mp->ID );
										$sl  = isset( $st_labels_sp[ $sk ] ) ? $st_labels_sp[ $sk ] : $sk;
										$mod = get_post_modified_time( 'U', true, $mp );
										$ago = $mod ? human_time_diff( (int) $mod, (int) current_time( 'timestamp' ) ) : '';
										?>
										<li class="border-b border-border/60 pb-3 last:border-0 last:pb-0">
											<div class="font-medium line-clamp-2"><?php echo esc_html( get_the_title( $mp ) ); ?></div>
											<div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-muted-foreground">
												<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( $sl ); ?></span>
												<?php if ( $city_l !== '' ) : ?>
													<span><?php echo esc_html( $city_l ); ?></span>
												<?php endif; ?>
												<?php if ( $ago !== '' ) : ?>
													<span><?php echo esc_html( sprintf( __( 'تحديث منذ %s', 'bina' ), $ago ) ); ?></span>
												<?php endif; ?>
											</div>
										</li>
										<?php
									}
									?>
								</ul>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</div>
