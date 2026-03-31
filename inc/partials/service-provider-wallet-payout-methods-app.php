<?php
/**
 * Service provider profile: payout methods form.
 *
 * @var WP_User $user
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user   = isset( $user ) && $user instanceof WP_User ? $user : wp_get_current_user();
$nonce  = wp_create_nonce( 'bina_wallet' );
$ajaxurl = admin_url( 'admin-ajax.php' );
$embedded = isset( $bina_payout_embedded ) && true === $bina_payout_embedded;

$bank_holder = (string) get_user_meta( $user->ID, 'bina_payout_bank_holder', true );
$bank_iban   = (string) get_user_meta( $user->ID, 'bina_payout_bank_iban', true );
$bank_name   = (string) get_user_meta( $user->ID, 'bina_payout_bank_name', true );
$stc_phone   = (string) get_user_meta( $user->ID, 'bina_payout_stc_phone', true );
?>

<div class="<?php echo $embedded ? 'space-y-4 w-full min-w-0' : 'space-y-6 w-full max-w-3xl mx-auto'; ?>" data-bina-wallet data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
	<?php if ( ! $embedded ) : ?>
		<div>
			<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'طرق الاستلام', 'bina' ); ?></h1>
			<p class="text-muted-foreground mt-1"><?php esc_html_e( 'أضف طرق استلام أموالك عند طلب السحب.', 'bina' ); ?></p>
		</div>
	<?php endif; ?>

	<div class="rounded-2xl border border-border/80 bg-card shadow-sm overflow-hidden">
		<div class="px-4 py-3 border-b border-border/80 bg-muted/20 text-sm font-medium">
			<?php esc_html_e( 'طرق الاستلام', 'bina' ); ?>
		</div>
		<div class="p-4 space-y-4">
			<div class="rounded-xl border border-border/70 bg-background p-4">
				<div class="font-medium mb-3"><?php esc_html_e( 'بيانات الحساب', 'bina' ); ?></div>
				<div class="grid gap-3 sm:grid-cols-2">
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'الاسم', 'bina' ); ?></label>
						<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" value="<?php echo esc_attr( $user->display_name ); ?>" disabled />
					</div>
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'البريد الإلكتروني', 'bina' ); ?></label>
						<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" value="<?php echo esc_attr( $user->user_email ); ?>" disabled />
					</div>
				</div>
			</div>

			<div class="space-y-4">
				<div class="rounded-xl border border-border/70 bg-background p-4">
					<div class="font-medium mb-3"><?php esc_html_e( 'تحويل بنكي', 'bina' ); ?></div>
					<form class="space-y-3" data-bina-payout-form data-bina-payout-kind="bank">
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
						<div class="space-y-2">
							<label class="text-sm font-medium"><?php esc_html_e( 'IBAN', 'bina' ); ?></label>
							<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="bank_iban" value="<?php echo esc_attr( $bank_iban ); ?>" />
						</div>
						<div class="flex items-center gap-3">
							<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-6 text-sm font-medium">
								<?php esc_html_e( 'حفظ', 'bina' ); ?>
							</button>
							<span class="text-sm text-muted-foreground" data-bina-payout-msg></span>
						</div>
					</form>
				</div>

				<div class="rounded-xl border border-border/70 bg-background p-4">
					<div class="font-medium mb-3"><?php esc_html_e( 'STC Pay', 'bina' ); ?></div>
					<form class="space-y-3" data-bina-payout-form data-bina-payout-kind="stc">
						<div class="space-y-2">
							<label class="text-sm font-medium"><?php esc_html_e( 'رقم الجوال', 'bina' ); ?></label>
							<input class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="stc_phone" value="<?php echo esc_attr( $stc_phone ); ?>" />
						</div>
						<div class="flex items-center gap-3">
							<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-6 text-sm font-medium">
								<?php esc_html_e( 'حفظ', 'bina' ); ?>
							</button>
							<span class="text-sm text-muted-foreground" data-bina-payout-msg></span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

