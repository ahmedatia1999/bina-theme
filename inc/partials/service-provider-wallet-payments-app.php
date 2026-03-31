<?php
/**
 * Service provider payments page: wallet balances + withdraw request.
 *
 * @var WP_User $user
 * @var array   $urls
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user = isset( $user ) && $user instanceof WP_User ? $user : wp_get_current_user();
$urls = isset( $urls ) && is_array( $urls ) ? $urls : array();

$balances = function_exists( 'bina_wallet_get_balances' ) ? bina_wallet_get_balances( (int) $user->ID ) : array( 'available' => 0.0, 'pending' => 0.0 );
$nonce    = wp_create_nonce( 'bina_wallet' );
$ajaxurl  = admin_url( 'admin-ajax.php' );

$bank_holder = (string) get_user_meta( $user->ID, 'bina_payout_bank_holder', true );
$bank_iban   = (string) get_user_meta( $user->ID, 'bina_payout_bank_iban', true );
$bank_name   = (string) get_user_meta( $user->ID, 'bina_payout_bank_name', true );
$stc_phone   = (string) get_user_meta( $user->ID, 'bina_payout_stc_phone', true );
?>

<div class="space-y-6 w-full max-w-3xl mx-auto" data-bina-wallet data-ajaxurl="<?php echo esc_url( $ajaxurl ); ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>">
	<div>
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'المدفوعات', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1"><?php esc_html_e( 'رصيدك داخل المنصة وطلبات السحب.', 'bina' ); ?></p>
	</div>

	<div class="grid gap-4 sm:grid-cols-2">
		<div class="rounded-xl border border-border/80 bg-card p-5 shadow-sm ring-1 ring-border/20">
			<div class="text-sm text-muted-foreground"><?php esc_html_e( 'الرصيد المتاح للسحب', 'bina' ); ?></div>
			<div class="text-2xl font-bold mt-2 tabular-nums" data-bina-wallet-available><?php echo esc_html( number_format_i18n( (float) ( $balances['available'] ?? 0 ), 2 ) ); ?></div>
			<p class="text-xs text-muted-foreground mt-1"><?php esc_html_e( 'يمكنك طلب سحب من هذا الرصيد.', 'bina' ); ?></p>
		</div>
		<div class="rounded-xl border border-border/80 bg-card p-5 shadow-sm ring-1 ring-border/20">
			<div class="text-sm text-muted-foreground"><?php esc_html_e( 'رصيد معلّق (Escrow)', 'bina' ); ?></div>
			<div class="text-2xl font-bold mt-2 tabular-nums"><?php echo esc_html( number_format_i18n( (float) ( $balances['pending'] ?? 0 ), 2 ) ); ?></div>
			<p class="text-xs text-muted-foreground mt-1"><?php esc_html_e( 'يتحول للمتاح بعد اعتماد المرحلة/الإكمال.', 'bina' ); ?></p>
		</div>
	</div>

	<div class="rounded-2xl border border-border/80 bg-card shadow-sm overflow-hidden">
		<div class="px-4 py-3 border-b border-border/80 bg-muted/20 text-sm font-medium">
			<?php esc_html_e( 'طرق الاستلام (من البروفايل)', 'bina' ); ?>
		</div>
		<div class="p-4 text-sm text-muted-foreground space-y-2">
			<div class="rounded-lg border border-border/70 bg-background p-3">
				<div class="font-medium text-foreground"><?php esc_html_e( 'تحويل بنكي', 'bina' ); ?></div>
				<div class="mt-1"><?php echo esc_html( $bank_holder !== '' ? $bank_holder : '—' ); ?></div>
				<div class="mt-1"><?php echo esc_html( $bank_iban !== '' ? $bank_iban : '—' ); ?></div>
				<div class="mt-1"><?php echo esc_html( $bank_name !== '' ? $bank_name : '—' ); ?></div>
			</div>
			<div class="rounded-lg border border-border/70 bg-background p-3">
				<div class="font-medium text-foreground"><?php esc_html_e( 'STC Pay', 'bina' ); ?></div>
				<div class="mt-1"><?php echo esc_html( $stc_phone !== '' ? $stc_phone : '—' ); ?></div>
			</div>
			<?php if ( ! empty( $urls['profile'] ) ) : ?>
				<a class="inline-flex items-center justify-center rounded-md border px-4 h-9 text-sm font-medium hover:bg-accent" href="<?php echo esc_url( $urls['profile'] ); ?>">
					<?php esc_html_e( 'تعديل طرق الاستلام', 'bina' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="rounded-2xl border border-border/80 bg-card shadow-sm overflow-hidden">
		<div class="px-4 py-3 border-b border-border/80 bg-muted/20 text-sm font-medium">
			<?php esc_html_e( 'طلب سحب', 'bina' ); ?>
		</div>
		<div class="p-4">
			<form class="space-y-3" data-bina-withdraw-form>
				<div class="grid gap-3 sm:grid-cols-2">
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'المبلغ', 'bina' ); ?></label>
						<input type="number" step="0.01" min="1" class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="amount" required />
					</div>
					<div class="space-y-2">
						<label class="text-sm font-medium"><?php esc_html_e( 'طريقة السحب', 'bina' ); ?></label>
						<select class="w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm" name="method">
							<option value="bank"><?php esc_html_e( 'تحويل بنكي', 'bina' ); ?></option>
							<option value="stc"><?php esc_html_e( 'STC Pay', 'bina' ); ?></option>
						</select>
					</div>
				</div>

				<div class="flex items-center gap-3">
					<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-6 text-sm font-medium">
						<?php esc_html_e( 'إرسال طلب السحب', 'bina' ); ?>
					</button>
					<span class="text-sm text-muted-foreground" data-bina-withdraw-msg></span>
				</div>
			</form>
		</div>
	</div>
</div>

