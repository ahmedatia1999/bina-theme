<?php
/**
 * Service provider: browse marketplace projects (main column).
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
$nonce     = wp_create_nonce( 'bina_proposals' );
$me_id     = (int) get_current_user_id();
?>
<div class="space-y-6" data-bina-proposals data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
	<div>
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'تصفح المشاريع', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1"><?php esc_html_e( 'قدّم عرضك على المشاريع المتاحة. سيتم فتح المحادثة فقط بعد قبول عرضك.', 'bina' ); ?></p>
	</div>

	<?php if ( ! $projects_query->have_posts() ) : ?>
		<div class="rounded-xl border bg-card p-10 text-center text-muted-foreground text-sm">
			<?php esc_html_e( 'لا توجد مشاريع متاحة حاليًا.', 'bina' ); ?>
		</div>
	<?php else : ?>
		<ul class="grid gap-4 sm:grid-cols-2">
			<?php
			while ( $projects_query->have_posts() ) :
				$projects_query->the_post();
				$pid      = get_the_ID();
				$my_prop  = function_exists( 'bina_proposal_get_for_project_provider' ) && $me_id > 0
					? bina_proposal_get_for_project_provider( (int) $pid, (int) $me_id )
					: null;
				$has_prop = is_array( $my_prop ) && ! empty( $my_prop['id'] );
				$prop_st  = $has_prop && isset( $my_prop['status'] ) ? (string) $my_prop['status'] : '';
				$city_raw = (string) get_post_meta( $pid, '_bina_city', true );
				$city_l   = $city_raw;
				foreach ( bina_get_cities_for_select() as $c ) {
					if ( isset( $c['value'] ) && $c['value'] === $city_raw ) {
						$city_l = isset( $c['label'] ) ? $c['label'] : $city_raw;
						break;
					}
				}
				$sk       = bina_get_project_status_meta( $pid );
				$sl       = isset( $st_labels[ $sk ] ) ? $st_labels[ $sk ] : $sk;
				$mod      = get_post_modified_time( 'U', true, $pid );
				$ago      = $mod ? human_time_diff( (int) $mod, (int) current_time( 'timestamp' ) ) : '';
				$sel_plan = $has_prop ? bina_normalize_payment_plan_key( (string) ( $my_prop['plan_key'] ?? 'pay_at_completion' ) ) : 'pay_at_completion';
				?>
				<li class="rounded-xl border border-border/80 bg-card p-4 shadow-sm">
					<div class="font-semibold line-clamp-2"><?php the_title(); ?></div>
					<div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-muted-foreground">
						<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( $sl ); ?></span>
						<?php if ( $city_l !== '' ) : ?>
							<span><?php echo esc_html( $city_l ); ?></span>
						<?php endif; ?>
						<?php if ( $ago !== '' ) : ?>
							<span><?php echo esc_html( sprintf( __( 'تحديث منذ %s', 'bina' ), $ago ) ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( has_excerpt() || get_the_content() ) : ?>
						<p class="text-sm text-muted-foreground mt-2 line-clamp-3"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30 ) ) ); ?></p>
					<?php endif; ?>
					<div class="mt-3 space-y-3" data-bina-proposal-card data-project-id="<?php echo (int) $pid; ?>">
						<?php if ( $has_prop && $prop_st !== 'rejected' ) : ?>
							<div class="inline-flex items-center justify-center rounded-md border bg-background shadow-xs h-9 px-4 text-sm font-medium text-muted-foreground" data-bina-proposal-sent>
								<?php esc_html_e( 'تم إرسال العرض', 'bina' ); ?>
							</div>
						<?php else : ?>
							<button type="button" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium" data-bina-proposal-open>
								<?php echo $prop_st === 'rejected' ? esc_html__( 'إعادة تقديم العرض', 'bina' ) : esc_html__( 'تقديم عرض', 'bina' ); ?>
							</button>
							<div class="hidden inline-flex items-center justify-center rounded-md border bg-background shadow-xs h-9 px-4 text-sm font-medium text-muted-foreground" data-bina-proposal-sent>
								<?php esc_html_e( 'تم إرسال العرض', 'bina' ); ?>
							</div>
						<?php endif; ?>

						<form class="hidden rounded-lg border border-border/70 bg-background p-3 space-y-3" data-bina-proposal-form>
							<div class="grid gap-3 sm:grid-cols-2">
								<div class="space-y-1.5">
									<label class="text-xs font-medium"><?php esc_html_e( 'السعر الإجمالي (ر.س)', 'bina' ); ?></label>
									<input name="price_total" inputmode="decimal" class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" placeholder="0" value="<?php echo $has_prop ? esc_attr( (string) ( $my_prop['price_total'] ?? '' ) ) : ''; ?>" required />
								</div>
								<div class="space-y-1.5" data-bina-duration-row>
									<label class="text-xs font-medium"><?php esc_html_e( 'المدة الإجمالية (بالأيام)', 'bina' ); ?></label>
									<input name="duration_days" inputmode="numeric" class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" placeholder="0" value="<?php echo $has_prop ? esc_attr( (string) ( $my_prop['duration_days'] ?? '' ) ) : ''; ?>" required />
									<p class="text-[11px] text-muted-foreground" data-bina-duration-hint><?php esc_html_e( 'اكتب المدة الإجمالية للمشروع، وعدد الدفعات يتم اختياره بشكل منفصل.', 'bina' ); ?></p>
								</div>
							</div>
							<div class="space-y-1.5">
								<label class="text-xs font-medium"><?php esc_html_e( 'عدد الدفعات', 'bina' ); ?></label>
								<select name="plan_key" class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm">
									<option value="pay_at_completion" <?php selected( $sel_plan, 'pay_at_completion' ); ?>><?php esc_html_e( 'دفعة واحدة بعد اكتمال المشروع', 'bina' ); ?></option>
									<option value="four_installments_equal" <?php selected( $sel_plan, 'four_installments_equal' ); ?>><?php esc_html_e( '4 دفعات', 'bina' ); ?></option>
									<option value="eleven_installments_equal" <?php selected( $sel_plan, 'eleven_installments_equal' ); ?>><?php esc_html_e( '11 دفعة', 'bina' ); ?></option>
								</select>
								<p class="text-xs text-muted-foreground" data-bina-plan-hint><?php esc_html_e( 'اختر فقط عدد مرات الدفع، ثم وزّع المبلغ الإجمالي على الدفعات بالأسفل.', 'bina' ); ?></p>
							</div>

							<div class="rounded-lg border border-border/70 bg-muted/10 p-3 space-y-2 hidden" data-bina-plan-breakdown-root>
								<div class="flex items-center justify-between gap-2">
									<div class="text-xs font-medium"><?php esc_html_e( 'تفاصيل الدفعات', 'bina' ); ?></div>
									<div class="text-[11px] text-muted-foreground" data-bina-plan-total-hint></div>
								</div>
								<div class="space-y-2" data-bina-plan-breakdown></div>
								<input type="hidden" name="plan_meta" value="<?php echo $has_prop ? esc_attr( (string) ( $my_prop['plan_meta'] ?? '' ) ) : ''; ?>" />
								<p class="text-[11px] text-muted-foreground"><?php esc_html_e( 'اكتب المطلوب تنفيذه في كل دفعة. سيتم تثبيت هذه التفاصيل عند قبول العرض.', 'bina' ); ?></p>
							</div>

							<div class="space-y-1.5">
								<label class="text-xs font-medium"><?php esc_html_e( 'رسالة للعميل', 'bina' ); ?></label>
								<textarea name="message" rows="3" class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" placeholder="<?php esc_attr_e( 'اكتب تفاصيل عرضك...', 'bina' ); ?>"><?php echo $has_prop ? esc_textarea( (string) ( $my_prop['message'] ?? '' ) ) : ''; ?></textarea>
							</div>
							<div class="flex items-center gap-3">
								<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium" data-bina-proposal-submit>
									<?php esc_html_e( 'إرسال العرض', 'bina' ); ?>
								</button>
								<button type="button" class="inline-flex items-center justify-center rounded-md border bg-background shadow-xs hover:bg-accent h-9 px-4 text-sm font-medium" data-bina-proposal-cancel>
									<?php esc_html_e( 'إلغاء', 'bina' ); ?>
								</button>
							</div>
							<span class="text-xs text-muted-foreground" data-bina-proposal-msg></span>
						</form>
					</div>
				</li>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</ul>

		<?php
		$cur_paged = max( 1, (int) get_query_var( 'paged' ) );
		if ( $cur_paged < 1 ) {
			$cur_paged = max( 1, (int) get_query_var( 'page' ) );
		}

		$big        = 999999999;
		$link       = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
		$pagination = paginate_links(
			array(
				'base'      => $link,
				'format'    => '?paged=%#%',
				'current'   => $cur_paged,
				'total'     => (int) $projects_query->max_num_pages,
				'type'      => 'list',
				'prev_text' => __( 'السابق', 'bina' ),
				'next_text' => __( 'التالي', 'bina' ),
			)
		);
		if ( $pagination ) {
			echo '<nav class="bina-pagination mt-8 flex justify-center" aria-label="' . esc_attr__( 'ترقيم الصفحات', 'bina' ) . '">' . wp_kses_post( $pagination ) . '</nav>';
		}
		?>
	<?php endif; ?>
</div>
