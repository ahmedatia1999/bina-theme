<?php
/**
 * Customer dashboard home body (inside portal shell).
 *
 * @var array     $urls
 * @var array     $stats
 * @var array     $status_counts
 * @var array     $st_labels Optional; filled from dashboard if missing.
 * @var WP_Post[] $recent_projects
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $st_labels ) || ! is_array( $st_labels ) ) {
	$st_labels = bina_get_project_status_labels();
}
?>
<div class="space-y-6 sm:space-y-8 w-full min-w-0">
	<div class="w-full min-w-0">
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight wrap-break-word"><?php esc_html_e( 'لوحة التحكم', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1 wrap-break-word"><?php esc_html_e( 'مرحباً بك في لوحة التحكم. إليك نظرة عامة على مشاريعك وأنشطتك.', 'bina' ); ?></p>
	</div>
	<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 w-full min-w-0">
		<a class="block group" href="<?php echo esc_url( $urls['my_projects_create'] ); ?>">
			<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/30 h-full transition-all hover:shadow-md hover:border-primary/40 hover:ring-primary/20">
				<div class="px-6 pb-3">
					<div class="flex items-start justify-between">
						<div class="flex items-center gap-3">
							<div class="rounded-lg bg-primary/10 p-2 ring-1 ring-primary/20 group-hover:bg-primary/15">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-primary"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
							</div>
							<div class="flex-1">
								<div class="font-semibold text-base"><?php esc_html_e( 'إنشاء مشروع', 'bina' ); ?></div>
								<p class="text-muted-foreground mt-1 text-sm"><?php esc_html_e( 'ابدأ مشروعاً جديداً', 'bina' ); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</a>
		<a class="block group" href="<?php echo esc_url( $urls['my_projects'] ); ?>">
			<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/30 h-full transition-all hover:shadow-md hover:border-primary/40 hover:ring-primary/20">
				<div class="px-6 pb-3">
					<div class="flex items-start justify-between">
						<div class="flex items-center gap-3">
							<div class="rounded-lg bg-primary/10 p-2 ring-1 ring-primary/20 group-hover:bg-primary/15">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-primary"><path d="m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"></path></svg>
							</div>
							<div class="flex-1">
								<div class="font-semibold text-base"><?php esc_html_e( 'مشاريعي', 'bina' ); ?></div>
								<p class="text-muted-foreground mt-1 text-sm"><?php esc_html_e( 'عرض جميع مشاريعك', 'bina' ); ?></p>
							</div>
						</div>
						<span class="inline-flex items-center justify-center rounded-md border px-2 py-0.5 text-xs font-medium border-transparent bg-secondary text-secondary-foreground"><?php echo (int) $stats['my_projects_badge']; ?></span>
					</div>
				</div>
			</div>
		</a>
		<a class="block group" href="<?php echo esc_url( $urls['chat'] ); ?>">
			<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/30 h-full transition-all hover:shadow-md hover:border-primary/40 hover:ring-primary/20">
				<div class="px-6 pb-3">
					<div class="flex items-center gap-3">
						<div class="rounded-lg bg-primary/10 p-2 ring-1 ring-primary/20 group-hover:bg-primary/15">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-primary"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
						</div>
						<div class="flex-1">
							<div class="font-semibold text-base"><?php esc_html_e( 'الرسائل', 'bina' ); ?></div>
							<p class="text-muted-foreground mt-1 text-sm"><?php esc_html_e( 'التواصل مع المقاولين', 'bina' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</a>
		<a class="block group" href="<?php echo esc_url( $urls['notifications'] ); ?>">
			<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/30 h-full transition-all hover:shadow-md hover:border-primary/40 hover:ring-primary/20">
				<div class="px-6 pb-3">
					<div class="flex items-center gap-3">
						<div class="rounded-lg bg-primary/10 p-2 ring-1 ring-primary/20 group-hover:bg-primary/15">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5 text-primary"><path d="M10.268 21a2 2 0 0 0 3.464 0"></path><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path></svg>
						</div>
						<div class="flex-1">
							<div class="font-semibold text-base"><?php esc_html_e( 'الإشعارات', 'bina' ); ?></div>
							<p class="text-muted-foreground mt-1 text-sm"><?php esc_html_e( 'عرض إشعاراتك', 'bina' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 w-full min-w-0">
		<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/25 h-full">
			<div class="flex flex-row items-center justify-between pb-2 px-6">
				<div class="text-sm font-medium text-muted-foreground"><?php esc_html_e( 'إجمالي المشاريع', 'bina' ); ?></div>
				<div class="rounded-lg bg-primary/10 p-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-primary"><path d="m6 14 1.5-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.54 6a2 2 0 0 1-1.95 1.5H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3.9a2 2 0 0 1 1.69.9l.81 1.2a2 2 0 0 0 1.67.9H18a2 2 0 0 1 2 2v2"></path></svg>
				</div>
			</div>
			<div class="px-4 md:px-6"><div class="text-2xl font-bold tabular-nums"><?php echo (int) $stats['total_projects']; ?></div></div>
		</div>
		<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/25 h-full">
			<div class="flex flex-row items-center justify-between pb-2 px-6">
				<div class="text-sm font-medium text-muted-foreground"><?php esc_html_e( 'المشاريع النشطة', 'bina' ); ?></div>
				<div class="rounded-lg bg-primary/10 p-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-primary"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path></svg>
				</div>
			</div>
			<div class="px-4 md:px-6"><div class="text-2xl font-bold tabular-nums"><?php echo (int) $stats['active_projects']; ?></div></div>
		</div>
		<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/25 h-full">
			<div class="flex flex-row items-center justify-between pb-2 px-6">
				<div class="text-sm font-medium text-muted-foreground"><?php esc_html_e( 'العروض المعلقة', 'bina' ); ?></div>
				<div class="rounded-lg bg-primary/10 p-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-primary"><path d="M10.268 21a2 2 0 0 0 3.464 0"></path><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path></svg>
				</div>
			</div>
			<div class="px-4 md:px-6"><div class="text-2xl font-bold tabular-nums"><?php echo (int) $stats['pending_offers']; ?></div></div>
		</div>
		<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/25 h-full">
			<div class="flex flex-row items-center justify-between pb-2 px-6">
				<div class="text-sm font-medium text-muted-foreground"><?php esc_html_e( 'الرسائل غير المقروءة', 'bina' ); ?></div>
				<div class="rounded-lg bg-primary/10 p-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-primary"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
				</div>
			</div>
			<div class="px-4 md:px-6"><div class="text-2xl font-bold tabular-nums"><?php echo (int) $stats['unread_messages']; ?></div></div>
		</div>
	</div>
	<div class="grid gap-4 md:grid-cols-2 w-full min-w-0">
		<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/25 h-full">
			<div class="px-6"><div class="leading-none font-semibold"><?php esc_html_e( 'نظرة عامة على حالة المشروع', 'bina' ); ?></div></div>
			<div class="px-4 md:px-6 space-y-4 min-w-0">
				<?php
				$sum_status = (int) array_sum( $status_counts );
				if ( $sum_status < 1 ) :
					?>
					<p class="text-sm text-muted-foreground text-center py-4"><?php esc_html_e( 'لا توجد مشاريع بعد', 'bina' ); ?></p>
				<?php else : ?>
					<ul class="space-y-3">
						<?php
						foreach ( $status_counts as $key => $n ) {
							if ( $n < 1 || ! isset( $st_labels[ $key ] ) ) {
								continue;
							}
							$pct = (int) round( 100 * (int) $n / $sum_status );
							?>
							<li class="space-y-1">
								<div class="flex justify-between text-sm gap-2">
									<span class="text-muted-foreground"><?php echo esc_html( $st_labels[ $key ] ); ?></span>
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
		<div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border border-border/80 py-6 shadow-sm ring-1 ring-border/25 h-full w-full min-w-0">
			<div class="px-6"><div class="leading-none font-semibold"><?php esc_html_e( 'النشاط الأخير', 'bina' ); ?></div></div>
			<div class="px-4 md:px-6 min-w-0">
				<?php if ( empty( $recent_projects ) ) : ?>
					<p class="text-sm text-muted-foreground text-center py-4"><?php esc_html_e( 'لا يوجد نشاط حديث', 'bina' ); ?></p>
				<?php else : ?>
					<ul class="space-y-3">
						<?php
						foreach ( $recent_projects as $rp ) {
							if ( ! $rp instanceof WP_Post ) {
								continue;
							}
							$pid      = (int) $rp->ID;
							$detail   = bina_get_customer_project_detail_url( $pid );
							$sk       = bina_get_project_status_meta( $pid );
							$sl       = isset( $st_labels[ $sk ] ) ? $st_labels[ $sk ] : $sk;
							$modified = get_post_modified_time( 'U', true, $rp );
							$ago      = $modified ? human_time_diff( (int) $modified, (int) current_time( 'timestamp' ) ) : '';
							?>
							<li class="border-b border-border/60 pb-3 last:border-0 last:pb-0">
								<a class="font-medium hover:underline line-clamp-2" href="<?php echo esc_url( $detail ); ?>"><?php echo esc_html( get_the_title( $rp ) ); ?></a>
								<div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-muted-foreground">
									<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( $sl ); ?></span>
									<?php if ( $ago !== '' ) : ?>
										<span><?php echo esc_html( sprintf( /* translators: %s: human time */ __( 'آخر تحديث منذ %s', 'bina' ), $ago ) ); ?></span>
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
