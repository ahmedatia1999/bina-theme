<?php
/**
 * Small top header controls: language + theme toggle.
 *
 * Used on auth pages and dashboards.
 *
 * @package bina-theme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bina-dashboard-top-mini inline-flex items-center gap-2">
	<?php if ( ! empty( $urls['notifications'] ) ) : ?>
		<a class="relative inline-flex items-center justify-center rounded-lg text-sm font-medium bg-card shadow-sm hover:bg-accent hover:text-accent-foreground size-9 transition-colors" href="<?php echo esc_url( $urls['notifications'] ); ?>" aria-label="<?php esc_attr_e( 'الإشعارات', 'bina' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-5"><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path></svg>
			<?php
			$initial_unread = 0;
			if ( isset( $stats['notifications_unread'] ) ) $initial_unread = (int) $stats['notifications_unread'];
			elseif ( isset( $stats['notifications_bell'] ) ) $initial_unread = (int) $stats['notifications_bell'];
			?>
			<span
				data-bina-unread-notifications-bell
				class="absolute bottom-0.5 end-0.5 min-w-[1rem] h-4 px-1 rounded-full bg-destructive text-[10px] text-white flex items-center justify-center font-medium <?php echo $initial_unread > 0 ? '' : 'hidden'; ?>"
			><?php echo (int) $initial_unread; ?></span>
		</a>
	<?php endif; ?>
	<a href="<?php echo esc_url( function_exists('bina_trp_toggle_url') ? bina_trp_toggle_url() : home_url('/') ); ?>" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 h-9 px-3 py-2">
		<span class="text-xs font-semibold"><?php echo esc_html( function_exists('bina_trp_toggle_label') ? bina_trp_toggle_label() : 'EN' ); ?></span>
	</a>
	<button data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive border bg-background shadow-xs hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 size-9 relative overflow-hidden transition-all" aria-label="Switch to light theme" type="button" style="padding: 5px !important;">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon h-[1.2rem] w-[1.2rem]"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path></svg>
	</button>
</div>

