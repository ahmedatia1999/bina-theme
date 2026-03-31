<?php
/**
 * Customer profile page body (inside portal shell).
 *
 * @var WP_User $user
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user    = isset( $user ) && $user instanceof WP_User ? $user : wp_get_current_user();
$nonce   = wp_create_nonce( 'bina_customer_profile' );
$ajaxurl = admin_url( 'admin-ajax.php' );

$phone = (string) get_user_meta( $user->ID, 'bina_phone', true );
?>

<div class="space-y-6 w-full min-w-0" data-bina-customer-profile data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
	<div class="w-full min-w-0">
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight wrap-break-word"><?php esc_html_e( 'الملف الشخصي', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1 wrap-break-word"><?php esc_html_e( 'حدّث بيانات حسابك وطرق الدفع.', 'bina' ); ?></p>
	</div>

	<div class="rounded-2xl border border-border/80 bg-card shadow-sm overflow-hidden">
		<div class="px-4 py-3 border-b border-border/80 bg-muted/20 text-sm font-medium">
			<?php esc_html_e( 'بيانات الحساب', 'bina' ); ?>
		</div>
		<div class="p-4">
			<form class="space-y-4" data-bina-customer-profile-form>
				<div class="grid gap-3 sm:grid-cols-2">
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'الاسم', 'bina' ); ?></label>
						<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
					</div>
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'البريد الإلكتروني', 'bina' ); ?></label>
						<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" value="<?php echo esc_attr( $user->user_email ); ?>" disabled />
					</div>
				</div>
				<div class="space-y-2">
					<label class="text-sm font-medium"><?php esc_html_e( 'رقم الجوال', 'bina' ); ?></label>
					<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="phone" value="<?php echo esc_attr( $phone ); ?>" />
				</div>
				<div class="flex items-center gap-3">
					<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-6 text-sm font-medium">
						<?php esc_html_e( 'حفظ', 'bina' ); ?>
					</button>
					<span class="text-sm text-muted-foreground" data-bina-customer-profile-msg></span>
				</div>
			</form>
		</div>
	</div>

	<?php include get_template_directory() . '/inc/partials/customer-payment-methods-app.php'; ?>
</div>

