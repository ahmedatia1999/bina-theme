<?php
/**
 * Notifications DB (per-user).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_notifications_db_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'bina_notifications';
}

/**
 * Create/upgrade notifications table.
 *
 * @return void
 */
function bina_notifications_maybe_install() {
	$ver = get_option( 'bina_notifications_db_version', '0' );
	if ( $ver === '1' ) {
		return;
	}

	global $wpdb;
	$table           = bina_notifications_db_table_name();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL,
		type varchar(64) NOT NULL DEFAULT 'info',
		project_id bigint(20) unsigned NOT NULL DEFAULT 0,
		sender_id bigint(20) unsigned NOT NULL DEFAULT 0,
		title varchar(190) NOT NULL DEFAULT '',
		body longtext NOT NULL,
		is_read tinyint(1) NOT NULL DEFAULT 0,
		created_at datetime NOT NULL,
		PRIMARY KEY (id),
		KEY user_read_created (user_id, is_read, created_at),
		KEY project (project_id),
		KEY sender (sender_id)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
	update_option( 'bina_notifications_db_version', '1', false );
}
add_action( 'init', 'bina_notifications_maybe_install', 3 );

/**
 * Fill dashboard notification counters.
 *
 * @param array<string,int|float> $stats
 * @param int $user_id
 * @return array<string,int|float>
 */
function bina_dashboard_stats_notifications_count_customer( $stats, $user_id ) {
	$stats['notifications_unread'] = bina_notifications_count_unread( (int) $user_id );
	return $stats;
}
add_filter( 'bina_customer_dashboard_stats', 'bina_dashboard_stats_notifications_count_customer', 20, 2 );

/**
 * Service provider dashboard counters.
 *
 * @param array<string,int|float> $stats
 * @param int $user_id
 * @return array<string,int|float>
 */
function bina_dashboard_stats_notifications_count_provider( $stats, $user_id ) {
	$count = bina_notifications_count_unread( (int) $user_id );
	$stats['unread_notifications'] = $count;
	$stats['notifications_bell']   = $count;
	return $stats;
}
add_filter( 'bina_service_provider_dashboard_stats', 'bina_dashboard_stats_notifications_count_provider', 20, 2 );

/**
 * Insert a notification row.
 *
 * @param int    $user_id Recipient user id.
 * @param string $type Notification type key.
 * @param int    $project_id Project id.
 * @param int    $sender_id Sender user id.
 * @param string $title Title.
 * @param string $body Body.
 * @return int Inserted notification id.
 */
function bina_notifications_insert( $user_id, $type, $project_id, $sender_id, $title, $body ) {
	global $wpdb;
	$table = bina_notifications_db_table_name();

	$user_id    = (int) $user_id;
	$project_id = (int) $project_id;
	$sender_id  = (int) $sender_id;
	$type       = sanitize_key( (string) $type );
	$title      = sanitize_text_field( (string) $title );
	$body       = sanitize_textarea_field( (string) $body );
	if ( $user_id < 1 || $body === '' ) {
		return 0;
	}

	$now = current_time( 'mysql' );
	$wpdb->insert(
		$table,
		array(
			'user_id'    => $user_id,
			'type'       => $type,
			'project_id' => $project_id,
			'sender_id'  => $sender_id,
			'title'      => $title,
			'body'       => $body,
			'is_read'    => 0,
			'created_at' => $now,
		),
		array( '%d', '%s', '%d', '%d', '%s', '%s', '%d', '%s' )
	);

	return (int) $wpdb->insert_id;
}

/**
 * Count unread notifications for user.
 *
 * @param int $user_id
 * @return int
 */
function bina_notifications_count_unread( $user_id ) {
	global $wpdb;
	$table   = bina_notifications_db_table_name();
	$user_id = (int) $user_id;
	if ( $user_id < 1 ) {
		return 0;
	}

	$sql = $wpdb->prepare(
		"SELECT COUNT(*) FROM {$table} WHERE user_id = %d AND is_read = 0",
		$user_id
	);
	return (int) $wpdb->get_var( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
}

/**
 * Fetch notifications list.
 *
 * @param int  $user_id
 * @param int  $limit
 * @param bool $only_unread
 * @return array<int,array<string,mixed>>
 */
function bina_notifications_fetch_list( $user_id, $limit = 20, $only_unread = false ) {
	global $wpdb;
	$table   = bina_notifications_db_table_name();
	$user_id = (int) $user_id;
	$limit   = max( 1, (int) $limit );
	if ( $user_id < 1 ) {
		return array();
	}

	$sql = "SELECT id, type, project_id, sender_id, title, body, is_read, created_at
		FROM {$table}
		WHERE user_id = %d";

	if ( $only_unread ) {
		$sql .= ' AND is_read = 0';
	}

	$sql .= ' ORDER BY id DESC LIMIT %d';

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$rows = $wpdb->get_results(
		$wpdb->prepare( $sql, $user_id, $limit ),
		ARRAY_A
	);
	return is_array( $rows ) ? $rows : array();
}

/**
 * Mark a notification as read.
 *
 * @param int $user_id
 * @param int $notification_id
 * @return void
 */
function bina_notifications_mark_read( $user_id, $notification_id ) {
	global $wpdb;
	$table   = bina_notifications_db_table_name();
	$user_id = (int) $user_id;
	$nid     = (int) $notification_id;
	if ( $user_id < 1 || $nid < 1 ) {
		return;
	}
	$wpdb->update(
		$table,
		array( 'is_read' => 1 ),
		array( 'id' => $nid, 'user_id' => $user_id ),
		array( '%d' ),
		array( '%d', '%d' )
	);
}

/**
 * Mark all user notifications as read.
 *
 * @param int $user_id
 * @return void
 */
function bina_notifications_mark_all_read( $user_id ) {
	global $wpdb;
	$table   = bina_notifications_db_table_name();
	$user_id = (int) $user_id;
	if ( $user_id < 1 ) {
		return;
	}
	$wpdb->update(
		$table,
		array( 'is_read' => 1 ),
		array( 'user_id' => $user_id, 'is_read' => 0 ),
		array( '%d' ),
		array( '%d', '%d' )
	);
}

