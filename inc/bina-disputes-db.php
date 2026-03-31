<?php
/**
 * DB: disputes table + helpers.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return string
 */
function bina_disputes_table() {
	global $wpdb;
	return $wpdb->prefix . 'bina_disputes';
}

/**
 * Install/upgrade disputes table.
 *
 * @return void
 */
function bina_disputes_maybe_install() {
	global $wpdb;

	$table           = bina_disputes_table();
	$charset_collate = $wpdb->get_charset_collate();

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$sql = "CREATE TABLE {$table} (
		id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		project_id BIGINT(20) UNSIGNED NOT NULL,
		customer_id BIGINT(20) UNSIGNED NOT NULL,
		provider_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
		created_by VARCHAR(20) NOT NULL DEFAULT 'customer',
		message TEXT NOT NULL,
		status VARCHAR(20) NOT NULL DEFAULT 'open',
		meta LONGTEXT NULL,
		created_at DATETIME NOT NULL,
		updated_at DATETIME NOT NULL,
		PRIMARY KEY  (id),
		KEY project_id (project_id),
		KEY customer_id (customer_id),
		KEY provider_id (provider_id),
		KEY status (status),
		KEY created_at (created_at)
	) {$charset_collate};";

	dbDelta( $sql );
}
add_action( 'init', 'bina_disputes_maybe_install', 9 );

/**
 * @param array<string,mixed> $row
 * @return array<string,mixed>
 */
function bina_dispute_normalize_row( $row ) {
	if ( ! is_array( $row ) ) {
		return array();
	}
	$row['id']          = isset( $row['id'] ) ? (int) $row['id'] : 0;
	$row['project_id']  = isset( $row['project_id'] ) ? (int) $row['project_id'] : 0;
	$row['customer_id'] = isset( $row['customer_id'] ) ? (int) $row['customer_id'] : 0;
	$row['provider_id'] = isset( $row['provider_id'] ) ? (int) $row['provider_id'] : 0;
	$row['created_by']  = isset( $row['created_by'] ) ? (string) $row['created_by'] : 'customer';
	$row['message']     = isset( $row['message'] ) ? (string) $row['message'] : '';
	$row['status']      = isset( $row['status'] ) ? (string) $row['status'] : 'open';
	$row['created_at']  = isset( $row['created_at'] ) ? (string) $row['created_at'] : '';
	$row['updated_at']  = isset( $row['updated_at'] ) ? (string) $row['updated_at'] : '';
	return $row;
}

/**
 * Create a new dispute.
 *
 * @param int    $project_id
 * @param int    $customer_id
 * @param int    $provider_id
 * @param string $created_by customer|provider
 * @param string $message
 * @param array<string,mixed> $meta
 * @return int|WP_Error
 */
function bina_dispute_create( $project_id, $customer_id, $provider_id, $created_by, $message, array $meta = array() ) {
	global $wpdb;

	$project_id  = (int) $project_id;
	$customer_id = (int) $customer_id;
	$provider_id = (int) $provider_id;
	$created_by  = (string) $created_by;
	$message     = trim( (string) $message );

	if ( $project_id < 1 || $customer_id < 1 ) {
		return new WP_Error( 'bina_dispute', __( 'بيانات غير صحيحة.', 'bina' ) );
	}
	if ( '' === $message ) {
		return new WP_Error( 'bina_dispute', __( 'اكتب رسالة الشكوى.', 'bina' ) );
	}
	if ( ! in_array( $created_by, array( 'customer', 'provider' ), true ) ) {
		$created_by = 'customer';
	}

	$now  = current_time( 'mysql' );
	$meta = wp_json_encode( $meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

	$table = bina_disputes_table();
	$ok    = $wpdb->insert(
		$table,
		array(
			'project_id'  => $project_id,
			'customer_id' => $customer_id,
			'provider_id' => $provider_id,
			'created_by'  => $created_by,
			'message'     => $message,
			'status'      => 'open',
			'meta'        => $meta,
			'created_at'  => $now,
			'updated_at'  => $now,
		),
		array( '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
	);

	if ( false === $ok ) {
		return new WP_Error( 'bina_dispute', __( 'تعذر إنشاء الشكوى.', 'bina' ) );
	}

	return (int) $wpdb->insert_id;
}

/**
 * Fetch disputes (for admin or per user/project).
 *
 * @param array<string,mixed> $args
 * @return array<int,array<string,mixed>>
 */
function bina_disputes_fetch( array $args = array() ) {
	global $wpdb;
	$table = bina_disputes_table();

	$where = array( '1=1' );
	$vals  = array();

	if ( ! empty( $args['project_id'] ) ) {
		$where[] = 'project_id = %d';
		$vals[]  = (int) $args['project_id'];
	}
	if ( ! empty( $args['customer_id'] ) ) {
		$where[] = 'customer_id = %d';
		$vals[]  = (int) $args['customer_id'];
	}
	if ( ! empty( $args['provider_id'] ) ) {
		$where[] = 'provider_id = %d';
		$vals[]  = (int) $args['provider_id'];
	}
	if ( ! empty( $args['status'] ) ) {
		$where[] = 'status = %s';
		$vals[]  = (string) $args['status'];
	}

	$limit = isset( $args['limit'] ) ? max( 1, min( 200, (int) $args['limit'] ) ) : 100;
	$off   = isset( $args['offset'] ) ? max( 0, (int) $args['offset'] ) : 0;

	$sql = "SELECT * FROM {$table} WHERE " . implode( ' AND ', $where ) . ' ORDER BY id DESC LIMIT %d OFFSET %d';
	$vals[] = $limit;
	$vals[] = $off;

	$rows = $wpdb->get_results( $wpdb->prepare( $sql, $vals ), ARRAY_A );
	if ( ! is_array( $rows ) ) {
		return array();
	}
	return array_values( array_filter( array_map( 'bina_dispute_normalize_row', $rows ) ) );
}

/**
 * Get single dispute.
 *
 * @param int $id
 * @return array<string,mixed>|null
 */
function bina_dispute_get( $id ) {
	global $wpdb;
	$id = (int) $id;
	if ( $id < 1 ) {
		return null;
	}
	$table = bina_disputes_table();
	$row   = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id=%d", $id ), ARRAY_A );
	if ( ! is_array( $row ) ) {
		return null;
	}
	return bina_dispute_normalize_row( $row );
}

/**
 * Update dispute status.
 *
 * @param int    $id
 * @param string $status open|closed
 * @return bool
 */
function bina_dispute_update_status( $id, $status ) {
	global $wpdb;
	$id     = (int) $id;
	$status = (string) $status;
	if ( $id < 1 ) {
		return false;
	}
	if ( ! in_array( $status, array( 'open', 'closed' ), true ) ) {
		return false;
	}
	$table = bina_disputes_table();
	$ok    = $wpdb->update(
		$table,
		array(
			'status'     => $status,
			'updated_at' => current_time( 'mysql' ),
		),
		array( 'id' => $id ),
		array( '%s', '%s' ),
		array( '%d' )
	);
	return false !== $ok;
}

