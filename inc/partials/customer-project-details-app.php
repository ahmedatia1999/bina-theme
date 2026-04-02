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
		<?php esc_html_e( 'Ø¬Ù…ÙŠØ¹ Ø§Ù„Ù…Ø´Ø§Ø±ÙŠØ¹', 'bina' ); ?>
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
					<?php esc_html_e( 'ØªØ¹Ø¯ÙŠÙ„ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹', 'bina' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( current_user_can( 'delete_post', $post->ID ) ) : ?>
				<button type="button" class="inline-flex items-center justify-center gap-2 rounded-md border border-destructive/40 bg-background px-4 py-2 text-sm font-medium text-destructive hover:bg-destructive/10" data-bina-delete-project>
					<?php esc_html_e( 'Ø­Ø°Ù Ø§Ù„Ù…Ø´Ø±ÙˆØ¹', 'bina' ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>

	<div class="prose prose-sm dark:prose-invert max-w-none text-muted-foreground">
		<?php echo apply_filters( 'the_content', $post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<div class="bg-card rounded-xl border p-6 space-y-4 text-sm">
		<h2 class="font-semibold text-base"><?php esc_html_e( 'ØªÙØ§ØµÙŠÙ„ Ø¥Ø¶Ø§ÙÙŠØ©', 'bina' ); ?></h2>
		<dl class="grid gap-3 sm:grid-cols-2">
			<?php if ( $reminder !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'Ù…ÙˆØ¹Ø¯ Ø§Ù„ØªØ°ÙƒÙŠØ±', 'bina' ); ?></dt><dd><?php echo esc_html( $reminder ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $city !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'Ø§Ù„Ù…Ø¯ÙŠÙ†Ø©', 'bina' ); ?></dt><dd><?php echo esc_html( $city ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $neighborhood !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'Ø§Ù„Ø­ÙŠ', 'bina' ); ?></dt><dd><?php echo esc_html( $neighborhood ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $street !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'Ø§Ù„Ø´Ø§Ø±Ø¹', 'bina' ); ?></dt><dd><?php echo esc_html( $street ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $start_timing !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'Ø§Ù„ØªÙˆÙ‚ÙŠØª Ø§Ù„Ù…ØªÙˆÙ‚Ø¹ Ù„Ù„Ø¨Ø¯Ø¡', 'bina' ); ?></dt><dd><?php echo esc_html( $start_timing ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $has_plans !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'Ù…Ø®Ø·Ø·Ø§Øª Ù‡Ù†Ø¯Ø³ÙŠØ©', 'bina' ); ?></dt><dd><?php echo esc_html( $has_plans ); ?></dd></div>
			<?php endif; ?>
			<?php if ( $has_photos !== '' ) : ?>
				<div><dt class="text-muted-foreground"><?php esc_html_e( 'ØµÙˆØ± Ù„Ù„Ù…ÙˆÙ‚Ø¹', 'bina' ); ?></dt><dd><?php echo esc_html( $has_photos ); ?></dd></div>
			<?php endif; ?>
		</dl>
		<?php if ( ! empty( $plans_attachment_ids ) ) : ?>
			<div class="mt-6 pt-4 border-t border-border/60">
				<h3 class="text-sm font-semibold mb-3"><?php esc_html_e( 'Ù…Ø±ÙÙ‚Ø§Øª Ø§Ù„Ù…Ø®Ø·Ø·Ø§Øª', 'bina' ); ?></h3>
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
							$title = get_the_title( $aid ) ? get_the_title( $aid ) : __( 'ØªØ­Ù…ÙŠÙ„', 'bina' );
							echo '<li><a class="inline-flex items-center gap-2 rounded-md border px-3 py-2 text-sm hover:bg-muted" href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $title ) . '</a></li>';
						}
					}
					?>
				</ul>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $site_photos_attachment_ids ) ) : ?>
			<div class="mt-6 pt-4 border-t border-border/60">
				<h3 class="text-sm font-semibold mb-3"><?php esc_html_e( 'ØµÙˆØ± Ø§Ù„Ù…ÙˆÙ‚Ø¹ Ø§Ù„Ù…Ø±ÙÙˆØ¹Ø©', 'bina' ); ?></h3>
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
		<h2 class="font-semibold text-base"><?php esc_html_e( 'Ø§Ù„Ø¹Ø±ÙˆØ¶ Ø§Ù„Ù…Ù‚Ø¯Ù…Ø©', 'bina' ); ?></h2>
		<?php if ( empty( $proposals ) ) : ?>
			<p class="text-muted-foreground"><?php esc_html_e( 'Ù„Ø§ ØªÙˆØ¬Ø¯ Ø¹Ø±ÙˆØ¶ Ø¹Ù„Ù‰ Ù‡Ø°Ø§ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ Ø­ØªÙ‰ Ø§Ù„Ø¢Ù†.', 'bina' ); ?></p>
		<?php else : ?>
			<ul class="space-y-3">
				<?php foreach ( $proposals as $row ) : ?>
					<?php
					$proposal_id = isset( $row['id'] ) ? (int) $row['id'] : 0;
					$provider_id = isset( $row['provider_id'] ) ? (int) $row['provider_id'] : 0;
					$provider    = $provider_id > 0 ? get_userdata( $provider_id ) : null;
					$provider_name = $provider ? (string) $provider->display_name : __( 'Ù…Ø²ÙˆØ¯ Ø®Ø¯Ù…Ø©', 'bina' );
					$status      = isset( $row['status'] ) ? (string) $row['status'] : 'pending';
					$status_label = $status === 'accepted' ? __( 'Ù…Ù‚Ø¨ÙˆÙ„', 'bina' ) : ( $status === 'rejected' ? __( 'Ù…Ø±ÙÙˆØ¶', 'bina' ) : __( 'Ù‚ÙŠØ¯ Ø§Ù„Ù…Ø±Ø§Ø¬Ø¹Ø©', 'bina' ) );
					$price       = isset( $row['price_total'] ) ? (float) $row['price_total'] : 0;
					$days        = isset( $row['duration_days'] ) ? (int) $row['duration_days'] : 0;
					$plan_key    = isset( $row['plan_key'] ) ? (string) $row['plan_key'] : 'pay_at_completion';
					$plan_label  = isset( $plan_labels[ $plan_key ] ) ? $plan_labels[ $plan_key ] : $plan_key;
					$message     = isset( $row['message'] ) ? (string) $row['message'] : '';
					?>
					<li class="rounded-lg border border-border/70 p-4 space-y-2" data-bina-proposal-item data-proposal-id="<?php echo esc_attr( (string) $proposal_id ); ?>">
						<div class="flex items-center justify-between gap-3">
							<div class="font-medium"><?php echo esc_html( $provider_name ); ?></div>
							<span class="inline-flex rounded-md border px-2 py-0.5 text-xs"><?php echo esc_html( $status_label ); ?></span>
						</div>
						<div class="flex flex-wrap gap-2 text-xs text-muted-foreground">
							<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( number_format_i18n( $price, 2 ) ); ?> <?php esc_html_e( 'Ø±.Ø³', 'bina' ); ?></span>
							<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( (string) $days ); ?> <?php esc_html_e( 'ÙŠÙˆÙ…', 'bina' ); ?></span>
							<span class="inline-flex rounded-md border px-0\.5 py-0.5"><?php echo esc_html( $plan_label ); ?></span>
						</div>
						<?php if ( $message !== '' ) : ?>
							<p class="text-muted-foreground"><?php echo esc_html( $message ); ?></p>
						<?php endif; ?>
						<?php if ( $status === 'pending' ) : ?>
							<div class="flex items-center gap-3">
								<button type="button" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium" data-bina-accept-proposal>
									<?php esc_html_e( 'Ù‚Ø¨ÙˆÙ„ Ø§Ù„Ø¹Ø±Ø¶', 'bina' ); ?>
								</button>
								<span class="text-xs text-muted-foreground" data-bina-proposal-action-msg></span>
							</div>
						<?php elseif ( $status === 'accepted' ) : ?>
							<div class="text-sm text-emerald-700 font-medium" data-bina-proposal-accepted-text>
								<?php esc_html_e( 'ØªÙ… Ù‚Ø¨ÙˆÙ„ Ø§Ù„Ø¹Ø±Ø¶', 'bina' ); ?>
							</div>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<?php if ( $accepted_pid > 0 && ! empty( $milestones ) ) : ?>
		<div class="bg-card rounded-xl border p-6 space-y-4 text-sm" data-bina-milestones data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $milestones_nonce ); ?>">
			<h2 class="font-semibold text-base"><?php esc_html_e( 'Ø§Ù„Ø¯ÙØ¹Ø§Øª (Milestones)', 'bina' ); ?></h2>
			<p class="text-muted-foreground"><?php esc_html_e( 'Ù‚Ù… Ø¨ØªÙ…ÙˆÙŠÙ„ Ø§Ù„Ø¯ÙØ¹Ø©ØŒ Ø«Ù… Ø¨Ø¹Ø¯ ØªØ³Ù„ÙŠÙ…Ù‡Ø§ ÙŠÙ…ÙƒÙ†Ùƒ Ø§Ø¹ØªÙ…Ø§Ø¯Ù‡Ø§ Ù„ØªØ­ÙˆÙŠÙ„Ù‡Ø§ Ù„Ù…Ø²ÙˆØ¯ Ø§Ù„Ø®Ø¯Ù…Ø©.', 'bina' ); ?></p>

			<ul class="space-y-3">
				<?php foreach ( $milestones as $ms ) : ?>
					<?php
					$mid   = isset( $ms['id'] ) ? (int) $ms['id'] : 0;
					$no    = isset( $ms['milestone_no'] ) ? (int) $ms['milestone_no'] : 0;
					$title = isset( $ms['title'] ) ? (string) $ms['title'] : '';
					$amt   = isset( $ms['amount'] ) ? (float) $ms['amount'] : 0.0;
					$st    = isset( $ms['status'] ) ? (string) $ms['status'] : '';
					$ms_meta = isset( $ms['meta'] ) ? json_decode( (string) $ms['meta'], true ) : array();
					$ms_description = is_array( $ms_meta ) && ! empty( $ms_meta['description'] ) ? (string) $ms_meta['description'] : '';
					$st_l  = $st;
					if ( $st === 'scheduled' ) { $st_l = __( 'Ù…Ø³ØªØ­Ù‚Ø©', 'bina' ); }
					if ( $st === 'payment_requested' ) { $st_l = __( 'Ø¨Ø§Ù†ØªØ¸Ø§Ø± ØªØ£ÙƒÙŠØ¯ Ø§Ù„Ø£Ø¯Ù…Ù†', 'bina' ); }
					if ( $st === 'funded' ) { $st_l = __( 'Ù…ÙÙ…ÙˆÙ‘Ù„Ø©', 'bina' ); }
					if ( $st === 'submitted' ) { $st_l = __( 'ØªÙ… Ø§Ù„ØªØ³Ù„ÙŠÙ…', 'bina' ); }
					if ( $st === 'approved' ) { $st_l = __( 'ØªÙ… Ø§Ù„Ø§Ø¹ØªÙ…Ø§Ø¯', 'bina' ); }
					if ( $st === 'released' ) { $st_l = __( 'ØªÙ… Ø§Ù„ØªØ­ÙˆÙŠÙ„ Ù„Ù…Ø²ÙˆØ¯ Ø§Ù„Ø®Ø¯Ù…Ø©', 'bina' ); }
					?>
					<li class="rounded-lg border border-border/70 p-4 space-y-2" data-bina-ms-row>
						<div class="flex items-start justify-between gap-3">
							<div class="min-w-0">
								<div class="font-medium"><?php echo esc_html( $title !== '' ? $title : sprintf( __( 'Ø¯ÙØ¹Ø© %d', 'bina' ), $no ) ); ?></div>
								<div class="text-xs text-muted-foreground tabular-nums mt-1"><?php echo esc_html( number_format_i18n( $amt, 2 ) ); ?> <?php esc_html_e( 'Ø±.Ø³', 'bina' ); ?></div>
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
									data-confirm="<?php echo esc_attr__( 'ØªØ£ÙƒÙŠØ¯: Ø¥Ø±Ø³Ø§Ù„ Ø·Ù„Ø¨ ØªÙ…ÙˆÙŠÙ„ Ù‡Ø°Ù‡ Ø§Ù„Ø¯ÙØ¹Ø©ØŸ', 'bina' ); ?>"
								><?php esc_html_e( 'Ø·Ù„Ø¨ ØªÙ…ÙˆÙŠÙ„ Ø§Ù„Ø¯ÙØ¹Ø©', 'bina' ); ?></button>
							<?php elseif ( $st === 'payment_requested' ) : ?>
								<?php if ( current_user_can( 'manage_options' ) ) : ?>
									<button type="button" class="inline-flex items-center justify-center rounded-md border bg-background shadow-xs hover:bg-accent h-9 px-4 text-sm font-medium"
										data-bina-ms-action="fund"
										data-milestone-id="<?php echo esc_attr( (string) $mid ); ?>"
										data-confirm="<?php echo esc_attr__( 'ØªØ£ÙƒÙŠØ¯: ØªÙ… Ø§Ø³ØªÙ„Ø§Ù… Ø§Ù„Ø¯ÙØ¹Ø© Ù…Ù† Ø§Ù„Ø¹Ù…ÙŠÙ„ ÙˆØªØ£ÙƒÙŠØ¯ Ø§Ù„ØªÙ…ÙˆÙŠÙ„ØŸ', 'bina' ); ?>"
									><?php esc_html_e( 'ØªØ£ÙƒÙŠØ¯ Ø§Ù„ØªÙ…ÙˆÙŠÙ„ (Ø£Ø¯Ù…Ù†)', 'bina' ); ?></button>
								<?php else : ?>
									<span class="text-xs text-muted-foreground"><?php esc_html_e( 'ØªÙ… Ø¥Ø±Ø³Ø§Ù„ Ø·Ù„Ø¨ Ø§Ù„ØªÙ…ÙˆÙŠÙ„ØŒ ÙÙŠ Ø§Ù†ØªØ¸Ø§Ø± ØªØ£ÙƒÙŠØ¯ Ø§Ù„Ø£Ø¯Ù…Ù†.', 'bina' ); ?></span>
								<?php endif; ?>
							<?php elseif ( $st === 'submitted' ) : ?>
								<button type="button" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium"
									data-bina-ms-action="approve"
									data-milestone-id="<?php echo esc_attr( (string) $mid ); ?>"
									data-confirm="<?php echo esc_attr__( 'Ø§Ø¹ØªÙ…Ø§Ø¯ Ù‡Ø°Ù‡ Ø§Ù„Ø¯ÙØ¹Ø© ÙˆØªØ­ÙˆÙŠÙ„Ù‡Ø§ Ù„Ù…Ø²ÙˆØ¯ Ø§Ù„Ø®Ø¯Ù…Ø©ØŸ', 'bina' ); ?>"
								><?php esc_html_e( 'Ø§Ø¹ØªÙ…Ø§Ø¯ Ø§Ù„Ø¯ÙØ¹Ø©', 'bina' ); ?></button>
							<?php endif; ?>
							<span class="text-xs text-muted-foreground" data-bina-ms-msg></span>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>

