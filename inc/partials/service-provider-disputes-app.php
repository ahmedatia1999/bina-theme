<?php
/**
 * Service provider disputes page body.
 *
 * @var WP_User $user
 * @var array<int,WP_Post> $projects
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user     = isset( $user ) && $user instanceof WP_User ? $user : wp_get_current_user();
$nonce    = wp_create_nonce( 'bina_disputes' );
$ajaxurl  = admin_url( 'admin-ajax.php' );
$projects = isset( $projects ) && is_array( $projects ) ? $projects : array();
?>

<div class="space-y-6 w-full min-w-0" data-bina-disputes data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
	<div class="w-full min-w-0">
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight wrap-break-word"><?php esc_html_e( 'النزاعات', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1 wrap-break-word"><?php esc_html_e( 'اختر مشروعًا ثم اكتب شكواك ليتم مراجعتها من الإدارة.', 'bina' ); ?></p>
	</div>

	<div class="rounded-2xl border border-border/80 bg-card shadow-sm overflow-hidden">
		<div class="px-4 py-3 border-b border-border/80 bg-muted/20 text-sm font-medium">
			<?php esc_html_e( 'تقديم شكوى', 'bina' ); ?>
		</div>
		<div class="p-4">
			<form class="space-y-4" data-bina-dispute-form>
				<div class="space-y-2">
					<label class="text-sm font-medium"><?php esc_html_e( 'المشروع', 'bina' ); ?></label>
					<select class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="project_id" required>
						<option value=""><?php esc_html_e( 'اختر مشروع', 'bina' ); ?></option>
						<?php foreach ( $projects as $p ) : ?>
							<option value="<?php echo esc_attr( (string) $p->ID ); ?>"><?php echo esc_html( $p->post_title ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="space-y-2">
					<label class="text-sm font-medium"><?php esc_html_e( 'الشكوى', 'bina' ); ?></label>
					<textarea class="w-full min-h-[140px] rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="message" required></textarea>
				</div>

				<div class="flex items-center gap-3">
					<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-6 text-sm font-medium">
						<?php esc_html_e( 'إرسال', 'bina' ); ?>
					</button>
					<span class="text-sm text-muted-foreground" data-bina-dispute-msg></span>
				</div>
			</form>
		</div>
	</div>
</div>

