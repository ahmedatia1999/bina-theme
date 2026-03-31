<?php
/**
 * Service provider profile page body (inside portal shell).
 *
 * @var WP_User $user
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user    = isset( $user ) && $user instanceof WP_User ? $user : wp_get_current_user();
$nonce   = wp_create_nonce( 'bina_service_provider_profile' );
$ajaxurl = admin_url( 'admin-ajax.php' );

$phone = (string) get_user_meta( $user->ID, 'bina_phone', true );
$city  = (string) get_user_meta( $user->ID, 'bina_city', true );
?>
<div class="space-y-6 w-full min-w-0" data-bina-service-provider-profile data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
	<div class="w-full min-w-0">
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight wrap-break-word"><?php esc_html_e( 'الملف الشخصي', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1 wrap-break-word"><?php esc_html_e( 'حدّث بيانات حسابك وطرق الاستلام.', 'bina' ); ?></p>
	</div>

	<div class="rounded-2xl border border-border/80 bg-card shadow-sm overflow-hidden">
		<div class="px-4 py-3 border-b border-border/80 bg-muted/20 text-sm font-medium">
			<?php esc_html_e( 'بيانات الحساب', 'bina' ); ?>
		</div>
		<div class="p-4">
			<form class="space-y-4" data-bina-service-provider-profile-form>
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
				<div class="grid gap-3 sm:grid-cols-2">
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'رقم الجوال', 'bina' ); ?></label>
						<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="phone" value="<?php echo esc_attr( $phone ); ?>" />
					</div>
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'المدينة', 'bina' ); ?></label>
						<select class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="city">
							<option value=""><?php esc_html_e( 'اختر', 'bina' ); ?></option>
							<?php if ( function_exists( 'bina_get_cities_for_select' ) ) : ?>
								<?php foreach ( bina_get_cities_for_select() as $row ) : ?>
									<?php
									$val   = isset( $row['value'] ) ? (string) $row['value'] : '';
									$label = isset( $row['label'] ) ? (string) $row['label'] : $val;
									if ( '' === $val ) {
										continue;
									}
									?>
									<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $city, $val ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
				</div>
				<div class="flex items-center gap-3">
					<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-6 text-sm font-medium">
						<?php esc_html_e( 'حفظ', 'bina' ); ?>
					</button>
					<span class="text-sm text-muted-foreground" data-bina-service-provider-profile-msg></span>
				</div>
			</form>
		</div>
	</div>

	<?php
	// Render payout methods embedded inside profile page card layout.
	$bina_payout_embedded = true;
	include get_template_directory() . '/inc/partials/service-provider-wallet-payout-methods-app.php';
	?>
</div>
