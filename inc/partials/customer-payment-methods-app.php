<?php
/**
 * Customer dashboard: payment methods form.
 *
 * @var WP_User $user
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user    = isset( $user ) && $user instanceof WP_User ? $user : wp_get_current_user();
$nonce   = wp_create_nonce( 'bina_customer_payments' );
$ajaxurl = admin_url( 'admin-ajax.php' );

$method      = (string) get_user_meta( $user->ID, 'bina_customer_pay_method', true );
$bank_holder = (string) get_user_meta( $user->ID, 'bina_customer_pay_bank_holder', true );
$bank_name   = (string) get_user_meta( $user->ID, 'bina_customer_pay_bank_name', true );
$bank_iban   = (string) get_user_meta( $user->ID, 'bina_customer_pay_bank_iban', true );
$stc_phone   = (string) get_user_meta( $user->ID, 'bina_customer_pay_stc_phone', true );

if ( ! in_array( $method, array( 'bank', 'stc' ), true ) ) {
	$method = '';
}
?>

<div class="rounded-2xl border border-border/80 bg-card shadow-sm overflow-hidden" data-bina-customer-payments data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
	<div class="px-4 py-3 border-b border-border/80 bg-muted/20 text-sm font-medium">
		<?php esc_html_e( 'طرق الدفع', 'bina' ); ?>
	</div>
	<div class="p-4">
		<form class="space-y-4" data-bina-customer-payments-form>
			<div class="space-y-2">
				<label class="text-sm font-medium"><?php esc_html_e( 'طريقة الدفع المفضلة', 'bina' ); ?></label>
				<select class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="method">
					<option value=""><?php esc_html_e( 'اختر...', 'bina' ); ?></option>
					<option value="bank" <?php selected( $method, 'bank' ); ?>><?php esc_html_e( 'تحويل بنكي', 'bina' ); ?></option>
					<option value="stc" <?php selected( $method, 'stc' ); ?>><?php esc_html_e( 'STC Pay', 'bina' ); ?></option>
				</select>
			</div>

			<div class="rounded-xl border border-border/70 bg-background p-4 <?php echo $method === 'bank' ? '' : 'hidden'; ?>" data-bina-method-block="bank">
				<div class="font-medium mb-3"><?php esc_html_e( 'بيانات التحويل البنكي', 'bina' ); ?></div>
				<div class="grid gap-3 sm:grid-cols-2">
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'اسم صاحب الحساب', 'bina' ); ?></label>
						<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="bank_holder" value="<?php echo esc_attr( $bank_holder ); ?>" />
					</div>
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'اسم البنك', 'bina' ); ?></label>
						<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="bank_name" value="<?php echo esc_attr( $bank_name ); ?>" />
					</div>
				</div>
				<div class="space-y-2 mt-3">
					<label class="text-sm font-medium"><?php esc_html_e( 'IBAN', 'bina' ); ?></label>
					<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="bank_iban" value="<?php echo esc_attr( $bank_iban ); ?>" />
				</div>
			</div>

			<div class="rounded-xl border border-border/70 bg-background p-4 <?php echo $method === 'stc' ? '' : 'hidden'; ?>" data-bina-method-block="stc">
				<div class="font-medium mb-3"><?php esc_html_e( 'بيانات STC Pay', 'bina' ); ?></div>
				<div class="space-y-2">
					<label class="text-sm font-medium"><?php esc_html_e( 'رقم الجوال', 'bina' ); ?></label>
					<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="stc_phone" value="<?php echo esc_attr( $stc_phone ); ?>" />
				</div>
			</div>

			<div class="flex items-center gap-3">
				<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-6 text-sm font-medium">
					<?php esc_html_e( 'حفظ', 'bina' ); ?>
				</button>
				<span class="text-sm text-muted-foreground" data-bina-customer-payments-msg></span>
			</div>
		</form>
	</div>
</div>

