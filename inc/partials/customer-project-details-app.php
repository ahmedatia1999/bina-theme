<?php
/**
 * Single project detail (customer).
 *
 * @var WP_Post $post
 * @var string  $list_url
 * @var string  $category
 * @var string  $city
 * @var string  $reminder
 * @var string  $st_label
 * @var array   $extra
 * @var string  $edit_url Optional edit URL when user may edit.
 * @var int[]   $plans_attachment_ids
 * @var int[]   $site_photos_attachment_ids
 * @var array<int,array<string,mixed>> $proposals
 * @var string $proposal_nonce
 * @var string $ajaxurl
 * @var string $delete_nonce
 * @var string $my_projects_url
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$edit_url = isset( $edit_url ) ? $edit_url : '';

$plans_attachment_ids       = isset( $plans_attachment_ids ) && is_array( $plans_attachment_ids ) ? array_map( 'absint', $plans_attachment_ids ) : array();
$site_photos_attachment_ids = isset( $site_photos_attachment_ids ) && is_array( $site_photos_attachment_ids ) ? array_map( 'absint', $site_photos_attachment_ids ) : array();

$neighborhood = isset( $extra['neighborhood'] ) ? (string) $extra['neighborhood'] : '';
$street         = isset( $extra['street'] ) ? (string) $extra['street'] : '';
$start_timing   = isset( $extra['start_timing'] ) ? (string) $extra['start_timing'] : '';
$has_plans      = isset( $extra['has_plans'] ) ? (string) $extra['has_plans'] : '';
$has_photos     = isset( $extra['has_photos'] ) ? (string) $extra['has_photos'] : '';
$proposals      = isset( $proposals ) && is_array( $proposals ) ? $proposals : array();
$proposal_nonce = isset( $proposal_nonce ) ? (string) $proposal_nonce : '';
$ajaxurl        = isset( $ajaxurl ) ? (string) $ajaxurl : admin_url( 'admin-ajax.php' );
$delete_nonce   = isset( $delete_nonce ) ? (string) $delete_nonce : '';
$my_projects_url = isset( $my_projects_url ) ? (string) $my_projects_url : $list_url;
$plan_labels    = function_exists( 'bina_get_payment_plan_labels' ) ? bina_get_payment_plan_labels() : array();
$milestones_nonce = wp_create_nonce( 'bina_milestones' );
$milestones       = function_exists( 'bina_milestones_fetch_for_project' ) ? bina_milestones_fetch_for_project( (int) ( $post->ID ?? 0 ) ) : array();
$accepted_pid     = (int) get_post_meta( (int) ( $post->ID ?? 0 ), '_bina_accepted_proposal_id', true );
?>
<div class="page-container max-w-3xl space-y-6"
	data-bina-customer-proposals
	data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>"
	data-nonce="<?php echo esc_attr( $proposal_nonce ); ?>"
	data-delete-nonce="<?php echo esc_attr( $delete_nonce ); ?>"
	data-project-id="<?php echo esc_attr( (string) ( $post->ID ?? 0 ) ); ?>"
	data-my-projects-url="<?php echo esc_url( $my_projects_url ); ?>"
>
	<a class="inline-flex items-center gap-2 text-sm font-medium hover:bg-accent rounded-md px-1 py-2 mb-2" href="<?php echo esc_url( $list_url ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" style="transform:rotate(180deg)"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
		<?php esc_html_e( 'جميع المشاريع', 'bina' ); ?>
	</a>

	<div class="flex flex-wrap items-center gap-2">
		<span class="inline-flex rounded-md border px-2 py-0.5 text-xs font-medium bg-muted"><?php echo esc_html( $st_label ); ?></span>
		<?php if ( $category !== '' ) : ?>
			<span class="inline-flex rounded-md border px-2 py-0.5 text-xs font-medium"><?php echo esc_html( $category ); ?></span>
		<?php endif; ?>
	</div>

	<div class="flex flex-wrap items-center gap-3 justify-between">
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php echo esc_html( get_the_title( $post ) ); ?></h1>
		<div class="flex items-center gap-2">
			<?php if ( ! empty( $edit_url ) ) : ?>
				<a class="inline-flex items-center justify-center gap-2 rounded-md border bg-background px-4 py-2 text-sm font-medium hover:bg-accent" href="<?php echo esc_url( $edit_url ); ?>">
					<?php esc_html_e( 'تعديل المشروع', 'bina' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( current_user_can( 'delete_post', $post->ID ) ) : ?>
				<button type="button" class="inline-flex items-center justify-center gap-2 rounded-md border border-destructive/40 bg-background px-4 py-2 text-sm font-medium text-destructive hover:bg-destructive/10" data-bina-delete-project>
					<?php esc_html_e( 'حذف المشروع', 'bina' ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>

	<div class="prose prose-sm dark:prose-invert max-w-none text-muted-foreground">
		<?php echo apply_filters( 'the_content', $post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<div class="bg-card rounded-xl border p-6 space-y-4 text-sm">
		<h2 class="font-semibold text-base"><?php esc_html_e( 'تفاصيل إضافية', 'bina' ); ?></h2>
		<dl class="grid gap-3 sm:grid-cols-2">
			<?php if ( $reminder !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'موعد التذكير', 'bina' ); ?></dt><dd><?php echo esc_html( $reminder ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $city !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'المدينة', 'bina' ); ?></dt><dd><?php echo esc_html( $city ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $neighborhood !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'الحي', 'bina' ); ?></dt><dd><?php echo esc_html( $neighborhood ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $street !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'الشارع', 'bina' ); ?></dt><dd><?php echo esc_html( $street ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $start_timing !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'التوقيت المتوقع للبدء', 'bina' ); ?></dt><dd><?php echo esc_html( $start_timing ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $has_plans !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'مخططات هندسية', 'bina' ); ?></dt><dd><?php echo esc_html( $has_plans ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $has_photos !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'صور للموقع', 'bina' ); ?></dt><dd><?php echo esc_html( $has_photos ); ?></dd></div>
			<?php endif; ?>
		</dl>
		<?php if ( ! empty( $plans_attachment_ids ) ) : ?>
			<div class="mt-6 pt-4 border-t border-border/60">
				<h3 class="text-sm font-semibold mb-3"><?php esc_html_e( 'مرفقات المخططات', 'bina' ); ?></h3>
				<ul class="flex flex-wrap gap-3">
					<?php
					foreach ( $plans_attachment_ids as $aid ) {
						if ( $aid < 1 ) {
							continue;
						}
						$url = wp_get_attachment_url( $aid );
						if ( ! $url ) {
							continue;
						}
						$mime = get_post_mime_type( $aid );
						if ( $mime && strpos( $mime, 'image/' ) === 0 ) {
							echo '<li class="rounded-lg border overflow-hidden max-w-[140px]">' . wp_get_attachment_image( $aid, 'medium', false, array( 'class' => 'w-full h-auto object-cover' ) ) . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							$title = get_the_title( $aid ) ? get_the_title( $aid ) : __( 'تحميل', 'bina' );
							echo '<li><a class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm hover:bg-muted" href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $title ) . '</a></li>';
						}
					}
					?>
				</ul>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $site_photos_attachment_ids ) ) : ?>
			<div class="mt-6 pt-4 border-t border-border/60">
				<h3 class="text-sm font-semibold mb-3"><?php esc_html_e( 'صور الموقع المرفوعة', 'bina' ); ?></h3>
				<div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
					<?php
					foreach ( $site_photos_attachment_ids as $aid ) {
						if ( $aid < 1 ) {
							continue;
						}
						if ( ! wp_attachment_is_image( $aid ) ) {
							continue;
						}
						echo '<div class="rounded-xl border overflow-hidden aspect-video bg-muted">' . wp_get_attachment_image( $aid, 'large', false, array( 'class' => 'w-full h-full object-cover' ) ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<div class="bg-card rounded-xl border p-6 space-y-4 text-sm">
		<h2 class="font-semibold text-base"><?php esc_html_e( 'العروض المقدمة', 'bina' ); ?></h2>
		<?php if ( empty( $proposals ) ) : ?>
			<p class="text-muted-foreground"><?php esc_html_e( 'لا توجد عروض على هذا المشروع حتى الآن.', 'bina' ); ?></p>
		<?php else : ?>
			<ul class="space-y-3">
				<?php foreach ( $proposals as $row ) : ?>
					<?php
					$proposal_id = isset( $row['id'] ) ? (int) $row['id'] : 0;
					$provider_id = isset( $row['provider_id'] ) ? (int) $row['provider_id'] : 0;
					$provider    = $provider_id > 0 ? get_userdata( $provider_id ) : null;
					$provider_name = $provider ? (string) $provider->display_name : __( 'مزود خدمة', 'bina' );
					$status      = isset( $row['status'] ) ? (string) $row['status'] : 'pending';
					$status_label = $status === 'accepted' ? __( 'مقبول', 'bina' ) : ( $status === 'rejected' ? __( 'مرفوض', 'bina' ) : __( 'قيد المراجعة', 'bina' ) );
					$price       = isset( $row['price_total'] ) ? (float) $row['price_total'] : 0;
					$days        = isset( $row['duration_days'] ) ? (int) $row['duration_days'] : 0;
					$plan_key    = isset( $row['plan_key'] ) ? (string) $row['plan_key'] : 'pay_at_completion';
					$plan_label  = isset( $plan_labels[ $plan_key ] ) ? $plan_labels[ $plan_key ] : $plan_key;
					$plan_meta_raw = isset( $row['plan_meta'] ) ? (string) $row['plan_meta'] : '';
					$plan_meta     = json_decode( $plan_meta_raw, true );
					$plan_items    = ( is_array( $plan_meta ) && ! empty( $plan_meta['items'] ) && is_array( $plan_meta['items'] ) ) ? $plan_meta['items'] : array();
					$message     = isset( $row['message'] ) ? (string) $row['message'] : '';
					?>
					<li class="rounded-lg border border-border/70 p-4 space-y-2" data-bina-proposal-item data-proposal-id="<?php echo esc_attr( (string) $proposal_id ); ?>">
						<div class="flex items-center justify-between gap-3">
							<div class="font-medium"><?php echo esc_html( $provider_name ); ?></div>
							<span class="inline-flex rounded-md border px-2 py-0.5 text-xs"><?php echo esc_html( $status_label ); ?></span>
						</div>
						<div class="flex flex-wrap gap-2 text-xs text-muted-foreground">
							<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( number_format_i18n( $price, 2 ) ); ?> <?php esc_html_e( 'ر.س', 'bina' ); ?></span>
							<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( (string) $days ); ?> <?php esc_html_e( 'يوم', 'bina' ); ?></span>
							<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( $plan_label ); ?></span>
						</div>
						<?php if ( $message !== '' ) : ?>
							<p class="text-muted-foreground"><?php echo esc_html( $message ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $plan_items ) ) : ?>
							<div class="rounded-md border border-border/60 bg-background/60 p-3 space-y-2">
								<div class="text-xs font-medium"><?php esc_html_e( 'تفاصيل الدفعات', 'bina' ); ?></div>
								<ul class="space-y-2">
									<?php foreach ( $plan_items as $item ) : ?>
										<?php
										$it_title = isset( $item['title'] ) ? (string) $item['title'] : '';
										$it_amount = isset( $item['amount'] ) ? (float) $item['amount'] : 0;
										$it_desc = isset( $item['description'] ) ? (string) $item['description'] : '';
										?>
										<li class="rounded-md border border-border/50 bg-card p-2">
											<div class="flex flex-wrap items-center justify-between gap-2">
												<span class="text-xs font-medium"><?php echo esc_html( $it_title !== '' ? $it_title : __( 'دفعة', 'bina' ) ); ?></span>
												<span class="text-xs text-muted-foreground"><?php echo esc_html( number_format_i18n( $it_amount, 2 ) ); ?> <?php esc_html_e( 'ر.س', 'bina' ); ?></span>
											</div>
											<?php if ( $it_desc !== '' ) : ?>
												<p class="text-xs text-muted-foreground mt-1"><?php echo esc_html( $it_desc ); ?></p>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
						<?php if ( $status === 'pending' ) : ?>
							<div class="flex items-center gap-3">
								<button type="button" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium" data-bina-accept-proposal>
									<?php esc_html_e( 'قبول العرض', 'bina' ); ?>
								</button>
								<span class="text-xs text-muted-foreground" data-bina-proposal-action-msg></span>
							</div>
						<?php elseif ( $status === 'accepted' ) : ?>
							<div class="text-sm text-emerald-700 font-medium" data-bina-proposal-accepted-text>
								<?php esc_html_e( 'تم قبول العرض', 'bina' ); ?>
							</div>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<?php if ( $accepted_pid > 0 && ! empty( $milestones ) ) : ?>
		<div class="bg-card rounded-xl border p-6 space-y-4 text-sm" data-bina-milestones data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $milestones_nonce ); ?>">
			<h2 class="font-semibold text-base"><?php esc_html_e( 'الدفعات (Milestones)', 'bina' ); ?></h2>
			<p class="text-muted-foreground"><?php esc_html_e( 'قم بتمويل الدفعة، ثم بعد تسليمها يمكنك اعتمادها لتحويلها لمزود الخدمة.', 'bina' ); ?></p>

			<ul class="space-y-3">
				<?php foreach ( $milestones as $ms ) : ?>
					<?php
					$mid   = isset( $ms['id'] ) ? (int) $ms['id'] : 0;
					$no    = isset( $ms['milestone_no'] ) ? (int) $ms['milestone_no'] : 0;
					$title = isset( $ms['title'] ) ? (string) $ms['title'] : '';
					$amt   = isset( $ms['amount'] ) ? (float) $ms['amount'] : 0.0;
					$st    = isset( $ms['status'] ) ? (string) $ms['status'] : '';
					$ms_meta = isset( $ms['meta'] ) ? json_decode( (string) $ms['meta'], true ) : array();
					$financials = function_exists( 'bina_milestone_get_financials' ) ? bina_milestone_get_financials( $ms ) : array();
					$customer_fee = isset( $financials['customer_fee'] ) ? (float) $financials['customer_fee'] : 0.0;
					$customer_total = isset( $financials['customer_total'] ) ? (float) $financials['customer_total'] : $amt;
					$provider_net = isset( $financials['provider_net'] ) ? (float) $financials['provider_net'] : $amt;
					$ms_description = is_array( $ms_meta ) && ! empty( $ms_meta['description'] ) ? (string) $ms_meta['description'] : '';
					$st_l  = $st;
					if ( $st === 'scheduled' ) { $st_l = __( 'مستحقة', 'bina' ); }
					if ( $st === 'payment_requested' ) { $st_l = __( 'بانتظار تأكيد الأدمن', 'bina' ); }
					if ( $st === 'funded' ) { $st_l = __( 'مموّلة', 'bina' ); }
					if ( $st === 'submitted' ) { $st_l = __( 'تم التسليم', 'bina' ); }
					if ( $st === 'approved' ) { $st_l = __( 'تم الاعتماد', 'bina' ); }
					if ( $st === 'released' ) { $st_l = __( 'تم التحويل لمزود الخدمة', 'bina' ); }
					?>
					<li class="rounded-lg border border-border/70 p-4 space-y-2" data-bina-ms-row>
						<div class="flex items-start justify-between gap-3">
							<div class="min-w-0">
								<div class="font-medium"><?php echo esc_html( $title !== '' ? $title : sprintf( __( 'دفعة %d', 'bina' ), $no ) ); ?></div>
								<div class="mt-1 space-y-1 text-xs text-muted-foreground tabular-nums">
									<div><?php echo esc_html( number_format_i18n( $amt, 2 ) ); ?> <?php esc_html_e( 'ر.س', 'bina' ); ?></div>
									<div><?php esc_html_e( 'عمولة المنصة على العميل 1%', 'bina' ); ?>: <?php echo esc_html( number_format_i18n( $customer_fee, 2 ) ); ?> <?php esc_html_e( 'ر.س', 'bina' ); ?></div>
									<div class="font-medium text-foreground"><?php esc_html_e( 'إجمالي المطلوب دفعه', 'bina' ); ?>: <?php echo esc_html( number_format_i18n( $customer_total, 2 ) ); ?> <?php esc_html_e( 'ر.س', 'bina' ); ?></div>
									<div><?php esc_html_e( 'صافي استلام مزود الخدمة بعد عمولة 1%', 'bina' ); ?>: <?php echo esc_html( number_format_i18n( $provider_net, 2 ) ); ?> <?php esc_html_e( 'ر.س', 'bina' ); ?></div>
								</div>
								<?php if ( $ms_description !== '' ) : ?>
									<div class="text-xs text-muted-foreground mt-2"><?php echo esc_html( $ms_description ); ?></div>
								<?php endif; ?>
							</div>
							<span class="inline-flex rounded-md border px-2 py-0.5 text-xs"><?php echo esc_html( $st_l ); ?></span>
						</div>

						<div class="flex flex-wrap items-center gap-2">
							<?php if ( $st === 'scheduled' ) : ?>
								<button type="button" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium"
									data-bina-ms-action="request"
									data-milestone-id="<?php echo esc_attr( (string) $mid ); ?>"
									data-confirm="<?php echo esc_attr__( 'تأكيد: إرسال طلب تمويل هذه الدفعة؟', 'bina' ); ?>"
								><?php esc_html_e( 'طلب تمويل الدفعة', 'bina' ); ?></button>
							<?php elseif ( $st === 'payment_requested' ) : ?>
								<?php if ( current_user_can( 'manage_options' ) ) : ?>
									<button type="button" class="inline-flex items-center justify-center rounded-md border bg-background shadow-xs hover:bg-accent h-9 px-4 text-sm font-medium"
										data-bina-ms-action="fund"
										data-milestone-id="<?php echo esc_attr( (string) $mid ); ?>"
										data-confirm="<?php echo esc_attr__( 'تأكيد: تم استلام الدفعة من العميل وتأكيد التمويل؟', 'bina' ); ?>"
									><?php esc_html_e( 'تأكيد التمويل (أدمن)', 'bina' ); ?></button>
								<?php else : ?>
									<span class="text-xs text-muted-foreground"><?php esc_html_e( 'تم إرسال طلب التمويل، في انتظار تأكيد الأدمن.', 'bina' ); ?></span>
								<?php endif; ?>
							<?php elseif ( $st === 'submitted' ) : ?>
								<button type="button" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium"
									data-bina-ms-action="approve"
									data-milestone-id="<?php echo esc_attr( (string) $mid ); ?>"
									data-confirm="<?php echo esc_attr__( 'اعتماد هذه الدفعة وتحويلها لمزود الخدمة؟', 'bina' ); ?>"
								><?php esc_html_e( 'اعتماد الدفعة', 'bina' ); ?></button>
							<?php endif; ?>
							<span class="text-xs text-muted-foreground" data-bina-ms-msg></span>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>
