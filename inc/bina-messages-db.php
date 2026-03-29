<?php
/**
 * DB table for per-project messages (bina_project threads).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return string Table name with prefix.
 */
function bina_messages_db_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'bina_messages';
}

/**
 * Create or upgrade messages table.
 *
 * @return void
 */
function bina_messages_maybe_install() {
	$ver = get_option( 'bina_messages_db_version', '0' );
	if ( $ver === '1' ) {
		return;
	}
	global $wpdb;
	$table           = bina_messages_db_table_name();
	$charset_collate = $wpdb->get_charset_collate();
	$sql             = "CREATE TABLE {$table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		project_id bigint(20) unsigned NOT NULL,
		sender_id bigint(20) unsigned NOT NULL,
		body longtext NOT NULL,
		created_at datetime NOT NULL,
		PRIMARY KEY  (id),
		KEY project_created (project_id, created_at),
		KEY sender (sender_id)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	update_option( 'bina_messages_db_version', '1', false );
}
add_action( 'init', 'bina_messages_maybe_install', 3 );
