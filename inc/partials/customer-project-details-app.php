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
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$edit_url = isset( $edit_url ) ? $edit_url : '';

$neighborhood = isset( $extra['neighborhood'] ) ? (string) $extra['neighborhood'] : '';
$street         = isset( $extra['street'] ) ? (string) $extra['street'] : '';
$start_timing   = isset( $extra['start_timing'] ) ? (string) $extra['start_timing'] : '';
$has_plans      = isset( $extra['has_plans'] ) ? (string) $extra['has_plans'] : '';
$has_photos     = isset( $extra['has_photos'] ) ? (string) $extra['has_photos'] : '';
?>
<div class="page-container max-w-3xl space-y-6">
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
		<?php if ( ! empty( $edit_url ) ) : ?>
			<a class="inline-flex items-center justify-center gap-2 rounded-md border bg-background px-4 py-2 text-sm font-medium hover:bg-accent" href="<?php echo esc_url( $edit_url ); ?>">
				<?php esc_html_e( 'تعديل المشروع', 'bina' ); ?>
			</a>
		<?php endif; ?>
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
	</div>
</div>
