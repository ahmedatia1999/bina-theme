<?php
/**
 * Service provider: offers (proposals) list.
 *
 * @var array<int,array<string,mixed>> $offers
 * @var WP_User $user
 * @var array $urls
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$offers = isset( $offers ) && is_array( $offers ) ? $offers : array();

$status_labels = array(
	'pending'  => __( 'قيد المراجعة', 'bina' ),
	'accepted' => __( 'مقبول', 'bina' ),
	'rejected' => __( 'مرفوض', 'bina' ),
);

$plan_labels = function_exists( 'bina_get_payment_plan_labels' ) ? bina_get_payment_plan_labels() : array();

?>
<div class="space-y-6">
	<div>
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'عروضي', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1"><?php esc_html_e( 'كل العروض التي قدمتها على مشاريع العملاء.', 'bina' ); ?></p>
	</div>

	<?php if ( empty( $offers ) ) : ?>
		<div class="rounded-xl border bg-card p-10 text-center text-muted-foreground text-sm">
			<?php esc_html_e( 'لا يوجد عروض حتى الآن.', 'bina' ); ?>
		</div>
	<?php else : ?>
		<ul class="grid gap-4 sm:grid-cols-2">
			<?php
			foreach ( $offers as $o ) :
				$pid   = isset( $o['project_id'] ) ? (int) $o['project_id'] : 0;
				$proj  = $pid > 0 ? get_post( $pid ) : null;
				$title = $proj ? get_the_title( $proj ) : __( 'مشروع غير متاح', 'bina' );

				$st     = isset( $o['status'] ) ? (string) $o['status'] : 'pending';
				$st_l   = isset( $status_labels[ $st ] ) ? $status_labels[ $st ] : $st;
				$amount = isset( $o['price_total'] ) ? (float) $o['price_total'] : 0;
				$days   = isset( $o['duration_days'] ) ? (int) $o['duration_days'] : 0;
				$msg    = isset( $o['message'] ) ? (string) $o['message'] : '';
				$plan_k = isset( $o['plan_key'] ) ? (string) $o['plan_key'] : 'pay_at_completion';
				$plan_l = isset( $plan_labels[ $plan_k ] ) ? $plan_labels[ $plan_k ] : $plan_k;

				$chat_url = '';
				if ( $st === 'accepted' && function_exists( 'bina_get_service_provider_chat_url' ) && $pid > 0 ) {
					$chat_url = add_query_arg( 'project_id', $pid, bina_get_service_provider_chat_url() );
				}
				?>
				<li class="rounded-xl border border-border/80 bg-card p-4 shadow-sm">
					<div class="flex items-start justify-between gap-3">
						<div class="font-semibold line-clamp-2"><?php echo esc_html( (string) $title ); ?></div>
						<span class="inline-flex rounded-md border px-2 py-0.5 text-xs <?php echo $st === 'accepted' ? 'border-emerald-300/60 text-emerald-700 bg-emerald-50' : ( $st === 'rejected' ? 'border-rose-300/60 text-rose-700 bg-rose-50' : 'border-border text-muted-foreground bg-muted/30' ); ?>">
							<?php echo esc_html( (string) $st_l ); ?>
						</span>
					</div>

					<div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-muted-foreground">
						<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( number_format_i18n( $amount, 2 ) ); ?> <?php esc_html_e( 'ر.س', 'bina' ); ?></span>
						<?php if ( $days > 0 ) : ?>
							<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( (string) $days ); ?> <?php esc_html_e( 'يوم', 'bina' ); ?></span>
						<?php endif; ?>
						<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( (string) $plan_l ); ?></span>
					</div>

					<?php if ( $msg !== '' ) : ?>
						<p class="text-sm text-muted-foreground mt-2 line-clamp-3"><?php echo esc_html( $msg ); ?></p>
					<?php endif; ?>

					<?php if ( $chat_url ) : ?>
						<div class="mt-3">
							<a class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium" href="<?php echo esc_url( $chat_url ); ?>">
								<?php esc_html_e( 'فتح المحادثة', 'bina' ); ?>
							</a>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>

