<?php
/**
 * Service provider: my projects list (assigned to provider).
 *
 * @var WP_Query $projects_query
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$projects_query = isset( $projects_query ) && $projects_query instanceof WP_Query ? $projects_query : null;
if ( ! $projects_query ) {
	return;
}

$st_labels = bina_get_project_status_labels();
$ajaxurl   = admin_url( 'admin-ajax.php' );
$ms_nonce  = wp_create_nonce( 'bina_milestones' );
?>
<div class="space-y-6" data-bina-milestones data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $ms_nonce ); ?>">
	<div>
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'مشاريعي', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1"><?php esc_html_e( 'المشاريع المرتبطة بك بعد قبول عرضك/تعيينك.', 'bina' ); ?></p>
	</div>

	<?php if ( ! $projects_query->have_posts() ) : ?>
		<div class="rounded-xl border bg-card p-10 text-center text-muted-foreground text-sm">
			<?php esc_html_e( 'لا توجد مشاريع حالياً.', 'bina' ); ?>
		</div>
	<?php else : ?>
		<ul class="grid gap-4 sm:grid-cols-2">
			<?php
			while ( $projects_query->have_posts() ) :
				$projects_query->the_post();
				$pid  = get_the_ID();
				$sk   = bina_get_project_status_meta( $pid );
				$sl   = isset( $st_labels[ $sk ] ) ? $st_labels[ $sk ] : $sk;
				$city_raw = (string) get_post_meta( $pid, '_bina_city', true );
				$city     = $city_raw;
				foreach ( bina_get_cities_for_select() as $c ) {
					if ( isset( $c['value'] ) && $c['value'] === $city_raw ) {
						$city = isset( $c['label'] ) ? $c['label'] : $city_raw;
						break;
					}
				}
				$mod  = get_post_modified_time( 'U', true, $pid );
				$ago  = $mod ? human_time_diff( (int) $mod, (int) current_time( 'timestamp' ) ) : '';

				$chat_url = function_exists( 'bina_get_service_provider_chat_url' )
					? add_query_arg( 'project_id', (int) $pid, bina_get_service_provider_chat_url() )
					: '';

				$milestones = function_exists( 'bina_milestones_fetch_for_project' ) ? bina_milestones_fetch_for_project( (int) $pid ) : array();
				?>
				<li class="rounded-xl border border-border/80 bg-card p-4 shadow-sm">
					<div class="font-semibold line-clamp-2"><?php the_title(); ?></div>
					<div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-muted-foreground">
						<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( $sl ); ?></span>
						<?php if ( $city !== '' ) : ?>
							<span><?php echo esc_html( $city ); ?></span>
						<?php endif; ?>
						<?php if ( $ago !== '' ) : ?>
							<span><?php echo esc_html( sprintf( __( 'تحديث منذ %s', 'bina' ), $ago ) ); ?></span>
						<?php endif; ?>
					</div>

					<?php if ( $chat_url ) : ?>
						<div class="mt-3">
							<a class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium" href="<?php echo esc_url( $chat_url ); ?>">
								<?php esc_html_e( 'فتح المحادثة', 'bina' ); ?>
							</a>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $milestones ) ) : ?>
						<div class="mt-4 rounded-lg border border-border/70 bg-background p-3 space-y-2 text-sm">
							<div class="font-medium"><?php esc_html_e( 'الدفعات', 'bina' ); ?></div>
							<ul class="space-y-2">
								<?php foreach ( $milestones as $ms ) : ?>
									<?php
									$mid   = isset( $ms['id'] ) ? (int) $ms['id'] : 0;
									$title = isset( $ms['title'] ) ? (string) $ms['title'] : '';
									$amt   = isset( $ms['amount'] ) ? (float) $ms['amount'] : 0.0;
									$st    = isset( $ms['status'] ) ? (string) $ms['status'] : '';
									$ms_meta = isset( $ms['meta'] ) ? json_decode( (string) $ms['meta'], true ) : array();
									$ms_description = is_array( $ms_meta ) && ! empty( $ms_meta['description'] ) ? (string) $ms_meta['description'] : '';
									$st_l  = $st;
									if ( $st === 'scheduled' ) { $st_l = __( 'مستحقة', 'bina' ); }
									if ( $st === 'funded' ) { $st_l = __( 'مموّلة', 'bina' ); }
									if ( $st === 'submitted' ) { $st_l = __( 'تم التسليم', 'bina' ); }
									if ( $st === 'approved' ) { $st_l = __( 'تم الاعتماد', 'bina' ); }
									if ( $st === 'released' ) { $st_l = __( 'تم التحويل للمزوّد', 'bina' ); }
									?>
									<li class="rounded-md border border-border/60 p-2 space-y-2" data-bina-ms-row>
										<div class="flex items-center justify-between gap-2">
											<div class="text-xs font-medium"><?php echo esc_html( $title ); ?></div>
											<div class="text-xs text-muted-foreground tabular-nums"><?php echo esc_html( number_format_i18n( $amt, 2 ) ); ?> <?php esc_html_e( 'ر.س', 'bina' ); ?></div>
                                    <?php if ( $ms_description !== '' ) : ?>
                                        <div class="text-xs text-muted-foreground"><?php echo esc_html( $ms_description ); ?></div>
                                    <?php endif; ?>
										</div>
										<div class="flex items-center justify-between gap-2">
											<span class="inline-flex rounded-md border px-2 py-0.5 text-xs"><?php echo esc_html( $st_l ); ?></span>
											<?php if ( $st === 'funded' ) : ?>
												<button type="button" class="inline-flex items-center justify-center rounded-md border bg-background shadow-xs hover:bg-accent h-8 px-3 text-xs font-medium"
													data-bina-ms-action="submit"
													data-milestone-id="<?php echo esc_attr( (string) $mid ); ?>"
													data-confirm="<?php echo esc_attr__( 'تأكيد: تم تسليم هذه الدفعة؟', 'bina' ); ?>"
												><?php esc_html_e( 'تسليم الدفعة', 'bina' ); ?></button>
											<?php endif; ?>
										</div>
										<span class="text-xs text-muted-foreground" data-bina-ms-msg></span>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</li>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</ul>
	<?php endif; ?>
</div>


