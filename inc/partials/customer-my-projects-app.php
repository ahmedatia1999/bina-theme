<?php
/**
 * Customer my-projects list.
 *
 * @var WP_Query $q
 * @var int      $count
 * @var array    $statuses
 * @var string   $create_url
 * @var string[] $categories
 * @var array    $cities City rows value/label
 * @var string   $filter_status
 * @var string   $filter_category
 * @var string   $filter_city
 * @var string   $filter_action_url Form action (current page permalink).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="space-y-6 w-full min-w-0">
	<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
		<div>
			<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'مشاريعي', 'bina' ); ?></h1>
			<p class="text-sm sm:text-base text-muted-foreground">
				<?php
				printf(
					/* translators: %d: project count */
					esc_html( _n( 'إدارة ومتابعة %d مشروع', 'إدارة ومتابعة %d مشاريع', $count, 'bina' ) ),
					(int) $count
				);
				?>
			</p>
		</div>
		<a class="inline-flex items-center justify-center gap-2 rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 py-2 text-sm font-medium" href="<?php echo esc_url( $create_url ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg>
			<?php esc_html_e( 'إنشاء مشروع', 'bina' ); ?>
		</a>
	</div>

	<form method="get" action="<?php echo esc_url( $filter_action_url ); ?>" class="flex flex-col gap-3 rounded-xl border bg-card p-4 sm:flex-row sm:flex-wrap sm:items-end">
		<div class="space-y-1 flex-1 min-w-[140px]">
			<label class="text-xs font-medium text-muted-foreground" for="bina-filter-status"><?php esc_html_e( 'الحالة', 'bina' ); ?></label>
			<select class="border-input h-9 w-full rounded-md border bg-transparent px-3 text-sm" id="bina-filter-status" name="project_status">
				<option value=""><?php esc_html_e( 'الكل', 'bina' ); ?></option>
				<?php foreach ( $statuses as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $filter_status, $key ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="space-y-1 flex-1 min-w-[160px]">
			<label class="text-xs font-medium text-muted-foreground" for="bina-filter-cat"><?php esc_html_e( 'الفئة', 'bina' ); ?></label>
			<select class="border-input h-9 w-full rounded-md border bg-transparent px-3 text-sm" id="bina-filter-cat" name="project_category">
				<option value=""><?php esc_html_e( 'الكل', 'bina' ); ?></option>
				<?php foreach ( $categories as $cat_label ) : ?>
					<option value="<?php echo esc_attr( $cat_label ); ?>" <?php selected( $filter_category, $cat_label ); ?>><?php echo esc_html( $cat_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="space-y-1 flex-1 min-w-[140px]">
			<label class="text-xs font-medium text-muted-foreground" for="bina-filter-city"><?php esc_html_e( 'المدينة', 'bina' ); ?></label>
			<select class="border-input h-9 w-full rounded-md border bg-transparent px-3 text-sm" id="bina-filter-city" name="project_city">
				<option value=""><?php esc_html_e( 'الكل', 'bina' ); ?></option>
				<?php foreach ( $cities as $c ) : ?>
					<option value="<?php echo esc_attr( $c['value'] ); ?>" <?php selected( $filter_city, $c['value'] ); ?>><?php echo esc_html( $c['label'] ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="flex gap-2">
			<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-9 px-4 text-sm font-medium"><?php esc_html_e( 'تصفية', 'bina' ); ?></button>
			<a class="inline-flex items-center justify-center rounded-md border bg-background h-9 px-4 text-sm font-medium hover:bg-accent" href="<?php echo esc_url( $filter_action_url ); ?>"><?php esc_html_e( 'إعادة تعيين', 'bina' ); ?></a>
		</div>
	</form>

	<?php if ( ! $q->have_posts() ) : ?>
		<div class="rounded-xl border bg-card p-10 text-center text-muted-foreground">
			<?php esc_html_e( 'لا توجد مشاريع تطابق التصفية، أو لا توجد مشاريع بعد.', 'bina' ); ?>
		</div>
	<?php else : ?>
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( $q->have_posts() ) {
				$q->the_post();
				$pid    = get_the_ID();
				$st_key = get_post_meta( $pid, '_bina_project_status', true );
				if ( $st_key === '' ) {
					$st_key = 'pending';
				}
				$st_label = isset( $statuses[ $st_key ] ) ? $statuses[ $st_key ] : $statuses['pending'];
				$cat      = get_post_meta( $pid, '_bina_category', true );
				$rem      = get_post_meta( $pid, '_bina_reminder', true );
				$link     = bina_get_customer_project_detail_url( $pid );
				$content  = get_post_field( 'post_content', $pid );
				$excerpt  = wp_trim_words( wp_strip_all_tags( (string) $content ), 24, '…' );
				?>
				<a href="<?php echo esc_url( $link ); ?>" class="block bg-card text-card-foreground rounded-xl border py-6 shadow-sm transition-all hover:shadow-lg">
					<div class="px-6 space-y-3">
						<div class="flex flex-wrap items-center gap-2">
							<span class="inline-flex rounded-md border px-2 py-0.5 text-xs font-medium bg-muted"><?php echo esc_html( $st_label ); ?></span>
							<?php if ( $cat !== '' ) : ?>
								<span class="inline-flex rounded-md border px-2 py-0.5 text-xs font-medium"><?php echo esc_html( $cat ); ?></span>
							<?php endif; ?>
						</div>
						<div class="font-semibold line-clamp-2"><?php the_title(); ?></div>
						<div class="text-muted-foreground text-sm line-clamp-2"><?php echo esc_html( $excerpt ); ?></div>
						<?php if ( $rem !== '' ) : ?>
							<div class="flex items-center gap-2 text-sm text-muted-foreground pt-2">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 shrink-0"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
								<span><?php echo esc_html( $rem ); ?></span>
							</div>
						<?php endif; ?>
					</div>
				</a>
				<?php
			}
			?>
		</div>
	<?php endif; ?>
</div>
