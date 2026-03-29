<?php
/**
 * Service provider: browse marketplace projects (main column).
 *
 * @var WP_Query $projects_query
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$projects_query = isset( $projects_query ) && $projects_query instanceof WP_Query ? $projects_query : null;
if ( ! $projects_query ) {
	return;
}

$st_labels = bina_get_project_status_labels();
?>
<div class="space-y-6">
	<div>
		<h1 class="text-2xl sm:text-3xl font-bold tracking-tight"><?php esc_html_e( 'تصفح المشاريع', 'bina' ); ?></h1>
		<p class="text-muted-foreground mt-1"><?php esc_html_e( 'مشاريع متاحة للتصفح (حسب حالة المشروع في المنصة).', 'bina' ); ?></p>
	</div>

	<?php if ( ! $projects_query->have_posts() ) : ?>
		<div class="rounded-xl border bg-card p-10 text-center text-muted-foreground text-sm">
			<?php esc_html_e( 'لا توجد مشاريع متاحة حاليًا.', 'bina' ); ?>
		</div>
	<?php else : ?>
		<ul class="grid gap-4 sm:grid-cols-2">
			<?php
			while ( $projects_query->have_posts() ) :
				$projects_query->the_post();
				$pid      = get_the_ID();
				$city_raw = (string) get_post_meta( $pid, '_bina_city', true );
				$city_l   = $city_raw;
				foreach ( bina_get_cities_for_select() as $c ) {
					if ( isset( $c['value'] ) && $c['value'] === $city_raw ) {
						$city_l = isset( $c['label'] ) ? $c['label'] : $city_raw;
						break;
					}
				}
				$sk = bina_get_project_status_meta( $pid );
				$sl = isset( $st_labels[ $sk ] ) ? $st_labels[ $sk ] : $sk;
				$mod = get_post_modified_time( 'U', true, $pid );
				$ago = $mod ? human_time_diff( (int) $mod, (int) current_time( 'timestamp' ) ) : '';
				?>
				<li class="rounded-xl border border-border/80 bg-card p-4 shadow-sm">
					<div class="font-semibold line-clamp-2"><?php the_title(); ?></div>
					<div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-muted-foreground">
						<span class="inline-flex rounded-md border px-1.5 py-0.5"><?php echo esc_html( $sl ); ?></span>
						<?php if ( $city_l !== '' ) : ?>
							<span><?php echo esc_html( $city_l ); ?></span>
						<?php endif; ?>
						<?php if ( $ago !== '' ) : ?>
							<span><?php echo esc_html( sprintf( __( 'تحديث منذ %s', 'bina' ), $ago ) ); ?></span>
						<?php endif; ?>
					</div>
					<?php if ( has_excerpt() || get_the_content() ) : ?>
						<p class="text-sm text-muted-foreground mt-2 line-clamp-3"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30 ) ) ); ?></p>
					<?php endif; ?>
				</li>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</ul>

		<?php
		$cur_paged = max( 1, (int) get_query_var( 'paged' ) );
		if ( $cur_paged < 1 ) {
			$cur_paged = max( 1, (int) get_query_var( 'page' ) );
		}

		$big        = 999999999;
		$link       = str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) );
		$pagination = paginate_links(
			array(
				'base'      => $link,
				'format'    => '?paged=%#%',
				'current'   => $cur_paged,
				'total'     => (int) $projects_query->max_num_pages,
				'type'      => 'list',
				'prev_text' => __( 'السابق', 'bina' ),
				'next_text' => __( 'التالي', 'bina' ),
			)
		);
		if ( $pagination ) {
			echo '<nav class="bina-pagination mt-8 flex justify-center" aria-label="' . esc_attr__( 'ترقيم الصفحات', 'bina' ) . '">' . wp_kses_post( $pagination ) . '</nav>';
		}
		?>
	<?php endif; ?>
</div>
