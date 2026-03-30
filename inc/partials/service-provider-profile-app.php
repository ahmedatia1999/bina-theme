<?php
/**
 * Service provider profile app (fallback content for profile page).
 *
 * Expects `$user` from parent scope.
 *
 * @var WP_User $user
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$full_name = bina_dashboard_user_display_name( $user );
$email     = (string) $user->user_email;
$phone     = (string) get_user_meta( $user->ID, 'bina_phone', true );
$city      = (string) get_user_meta( $user->ID, 'bina_city', true );

$city_label = $city;
if ( $city !== '' ) {
	foreach ( bina_get_cities_for_select() as $row ) {
		if ( isset( $row['value'] ) && (string) $row['value'] === $city ) {
			$city_label = isset( $row['label'] ) ? (string) $row['label'] : $city;
			break;
		}
	}
}

$verification_status_raw = strtolower( trim( (string) get_user_meta( $user->ID, 'bina_verification_status', true ) ) );
$requested_section       = isset( $_GET['section'] ) ? sanitize_key( wp_unslash( $_GET['section'] ) ) : '';
$verification_title      = __( 'غير موثق', 'bina' );
$verification_message    = __( 'لم يتم إرسال طلب توثيق مكتمل حتى الآن.', 'bina' );
$verification_badge      = 'bg-amber-100 text-amber-800';

if ( in_array( $verification_status_raw, array( 'verified', 'approved', 'active' ), true ) ) {
	$verification_title   = __( 'موثق', 'bina' );
	$verification_message = __( 'حسابك موثق وحالتك ظاهرة للعملاء.', 'bina' );
	$verification_badge   = 'bg-emerald-100 text-emerald-800';
} elseif ( in_array( $verification_status_raw, array( 'pending', 'in_review', 'under_review', 'submitted' ), true ) ) {
	$verification_title   = __( 'قيد المراجعة', 'bina' );
	$verification_message = __( 'تم استلام طلبك وجارٍ مراجعته من الإدارة.', 'bina' );
	$verification_badge   = 'bg-blue-100 text-blue-800';
} elseif ( in_array( $verification_status_raw, array( 'rejected', 'declined' ), true ) ) {
	$verification_title   = __( 'مرفوض', 'bina' );
	$verification_message = __( 'تم رفض الطلب السابق. راجع البيانات وأعد الإرسال.', 'bina' );
	$verification_badge   = 'bg-rose-100 text-rose-800';
}
?>
<div class="space-y-6">
	<div>
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'الملف الشخصي', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1"><?php esc_html_e( 'بيانات حسابك وحالة التوثيق.', 'bina' ); ?></p>
	</div>

	<div class="grid gap-4 md:grid-cols-2">
		<div class="bg-card rounded-xl border p-6 shadow-sm">
			<h2 class="font-semibold text-base"><?php esc_html_e( 'بيانات الحساب', 'bina' ); ?></h2>
			<div class="mt-4 space-y-3 text-sm">
				<div class="flex items-center justify-between gap-3">
					<span class="text-muted-foreground"><?php esc_html_e( 'الاسم', 'bina' ); ?></span>
					<span class="font-medium"><?php echo esc_html( $full_name !== '' ? $full_name : '-' ); ?></span>
				</div>
				<div class="flex items-center justify-between gap-3">
					<span class="text-muted-foreground"><?php esc_html_e( 'البريد الإلكتروني', 'bina' ); ?></span>
					<span class="font-medium"><?php echo esc_html( $email !== '' ? $email : '-' ); ?></span>
				</div>
				<div class="flex items-center justify-between gap-3">
					<span class="text-muted-foreground"><?php esc_html_e( 'رقم الجوال', 'bina' ); ?></span>
					<span class="font-medium"><?php echo esc_html( $phone !== '' ? $phone : '-' ); ?></span>
				</div>
				<div class="flex items-center justify-between gap-3">
					<span class="text-muted-foreground"><?php esc_html_e( 'المدينة', 'bina' ); ?></span>
					<span class="font-medium"><?php echo esc_html( $city_label !== '' ? $city_label : '-' ); ?></span>
				</div>
			</div>
		</div>

		<div id="verification" class="bg-card rounded-xl border p-6 shadow-sm scroll-mt-24">
			<div class="flex items-center justify-between gap-2">
				<h2 class="font-semibold text-base"><?php esc_html_e( 'التوثيق', 'bina' ); ?></h2>
				<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium <?php echo esc_attr( $verification_badge ); ?>"><?php echo esc_html( $verification_title ); ?></span>
			</div>
			<p class="text-sm text-muted-foreground mt-2"><?php echo esc_html( $verification_message ); ?></p>
			<div class="mt-4 rounded-lg border border-dashed border-border/80 bg-muted/30 p-3 text-xs text-muted-foreground">
				<?php esc_html_e( 'إذا أردت تعديل مستندات التوثيق أو إعادة الإرسال، استخدم نموذج التوثيق داخل صفحة الملف الشخصي عند تفعيله.', 'bina' ); ?>
			</div>
		</div>
	</div>
</div>
<?php if ( 'verification' === $requested_section ) : ?>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var section = document.getElementById('verification');
			if (!section) return;
			section.scrollIntoView({ behavior: 'smooth', block: 'start' });
		});
	</script>
<?php endif; ?>
