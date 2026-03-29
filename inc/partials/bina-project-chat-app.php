<?php
/**
 * Project-thread chat UI (customer or service provider).
 *
 * @var string   $portal_role     customer|provider
 * @var string   $chat_base_url   Page URL without query (for list + back links).
 * @var int      $project_id      0 = list, else thread.
 * @var int[]    $inbox_project_ids Project IDs user can open.
 * @var bool     $thread_allowed  Whether current user may load thread for $project_id.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$portal_role       = isset( $portal_role ) ? (string) $portal_role : 'customer';
$chat_base_url     = isset( $chat_base_url ) ? (string) $chat_base_url : '';
$project_id        = isset( $project_id ) ? absint( $project_id ) : 0;
$inbox_project_ids = isset( $inbox_project_ids ) && is_array( $inbox_project_ids ) ? array_map( 'absint', $inbox_project_ids ) : array();
$thread_allowed    = ! empty( $thread_allowed );

$list_url = remove_query_arg( 'project_id', $chat_base_url );
$thread_url = $project_id > 0 ? add_query_arg( 'project_id', $project_id, $chat_base_url ) : $chat_base_url;
?>
<div
	class="w-full max-w-3xl mx-auto space-y-4"
	data-bina-project-chat
	data-bina-portal-role="<?php echo esc_attr( $portal_role ); ?>"
	data-thread-url="<?php echo esc_url( $thread_url ); ?>"
	data-project-id="<?php echo esc_attr( (string) $project_id ); ?>"
	data-thread-active="<?php echo $project_id > 0 && $thread_allowed ? '1' : '0'; ?>"
>
	<?php if ( $project_id > 0 && ! $thread_allowed ) : ?>
		<div class="rounded-xl border border-destructive/40 bg-destructive/5 p-6 text-center text-sm">
			<?php esc_html_e( 'لا يمكنك فتح هذه المحادثة.', 'bina' ); ?>
		</div>
		<p class="text-center">
			<a class="text-primary underline text-sm" href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'العودة للقائمة', 'bina' ); ?></a>
		</p>
	<?php elseif ( $project_id < 1 ) : ?>
		<div class="rounded-2xl border border-border/80 bg-card p-6 shadow-sm">
			<h1 class="text-xl font-semibold"><?php esc_html_e( 'المحادثات', 'bina' ); ?></h1>
			<p class="text-muted-foreground text-sm mt-2"><?php esc_html_e( 'اختر مشروعًا لفتح سلسلة الرسائل.', 'bina' ); ?></p>
		</div>
		<?php if ( empty( $inbox_project_ids ) ) : ?>
			<div class="rounded-xl border bg-muted/30 p-8 text-center text-muted-foreground text-sm">
				<?php if ( $portal_role === 'provider' ) : ?>
					<?php esc_html_e( 'لا توجد مشاريع مرتبطة بحسابك بعد. يظهر المشروع هنا عند تعيينك من لوحة التحكم.', 'bina' ); ?>
				<?php else : ?>
					<?php esc_html_e( 'لا توجد مشاريع بعد. أنشئ مشروعًا من لوحة التحكم.', 'bina' ); ?>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<ul class="space-y-2">
				<?php foreach ( $inbox_project_ids as $pid ) : ?>
					<?php
					$p = get_post( $pid );
					if ( ! $p || $p->post_type !== 'bina_project' ) {
						continue;
					}
					$link = add_query_arg( 'project_id', $pid, $chat_base_url );
					?>
					<li>
						<a class="flex items-center justify-between gap-3 rounded-xl border border-border/80 bg-card px-4 py-3 text-start shadow-sm hover:border-primary/40 transition-colors" href="<?php echo esc_url( $link ); ?>">
							<span class="font-medium line-clamp-2"><?php echo esc_html( get_the_title( $p ) ); ?></span>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 shrink-0 opacity-60" style="transform:rotate(180deg)"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	<?php else : ?>
		<?php
		$phead = get_post( $project_id );
		$ptitle = $phead ? get_the_title( $phead ) : '';
		$assigned = bina_get_project_assigned_provider_id( $project_id );
		?>
		<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
			<a class="inline-flex items-center gap-2 text-sm font-medium text-muted-foreground hover:text-foreground" href="<?php echo esc_url( $list_url ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" style="transform:rotate(180deg)"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
				<?php esc_html_e( 'كل المحادثات', 'bina' ); ?>
			</a>
		</div>
		<div class="rounded-2xl border border-border/80 bg-card overflow-hidden shadow-sm flex flex-col min-h-[420px] max-h-[min(70vh,560px)]">
			<div class="border-b px-4 py-3 bg-muted/20">
				<h2 class="font-semibold line-clamp-2"><?php echo esc_html( $ptitle ); ?></h2>
				<?php if ( $portal_role === 'customer' && $assigned < 1 ) : ?>
					<p class="text-xs text-muted-foreground mt-1"><?php esc_html_e( 'سيتم إخطار مقدم الخدمة عند تعيينه من الإدارة.', 'bina' ); ?></p>
				<?php elseif ( $portal_role === 'provider' && $assigned === (int) get_current_user_id() ) : ?>
					<p class="text-xs text-muted-foreground mt-1"><?php esc_html_e( 'محادثة مع صاحب المشروع.', 'bina' ); ?></p>
				<?php endif; ?>
			</div>
			<div id="bina-thread-scroll" class="flex-1 min-h-[240px] overflow-y-auto p-4 space-y-3 bg-background" data-bina-thread-scroll>
				<p class="text-center text-sm text-muted-foreground py-8" data-bina-thread-empty><?php esc_html_e( 'لا رسائل بعد. ابدأ المحادثة.', 'bina' ); ?></p>
				<div class="flex flex-col gap-3" data-bina-thread-messages hidden></div>
			</div>
			<form class="border-t p-3 bg-muted/10 flex gap-2 items-end" data-bina-thread-form>
				<label class="sr-only" for="bina-thread-input"><?php esc_html_e( 'رسالة', 'bina' ); ?></label>
				<textarea id="bina-thread-input" rows="2" class="flex-1 min-h-[44px] max-h-32 rounded-md border border-input bg-transparent px-3 py-2 text-sm resize-y" placeholder="<?php esc_attr_e( 'اكتب رسالتك…', 'bina' ); ?>" data-bina-thread-input></textarea>
				<button type="submit" class="inline-flex shrink-0 items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-4 text-sm font-medium disabled:opacity-50" data-bina-thread-send>
					<?php esc_html_e( 'إرسال', 'bina' ); ?>
				</button>
			</form>
		</div>
	<?php endif; ?>
</div>
