<?php
/**
 * Wallet (escrow ledger) + withdrawal requests.
 *
 * This is a platform wallet: customer pays into platform, funds are allocated to provider
 * as pending/available and can be withdrawn via requests.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_wallet_ledger_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'bina_wallet_ledger';
}

function bina_wallet_withdrawals_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'bina_withdraw_requests';
}

function bina_wallet_maybe_install() {
	$ver = get_option( 'bina_wallet_db_version', '0' );
	if ( $ver === '1' ) {
		return;
	}

	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();

	$ledger = bina_wallet_ledger_table_name();
	$sql1   = "CREATE TABLE {$ledger} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL,
		project_id bigint(20) unsigned NOT NULL DEFAULT 0,
		proposal_id bigint(20) unsigned NOT NULL DEFAULT 0,
		plan_id bigint(20) unsigned NOT NULL DEFAULT 0,
		milestone_no int(11) NOT NULL DEFAULT 0,
		entry_type varchar(32) NOT NULL,
		amount decimal(12,2) NOT NULL DEFAULT 0.00,
		balance_bucket varchar(16) NOT NULL DEFAULT 'available',
		note varchar(190) NOT NULL DEFAULT '',
		meta longtext NULL,
		created_at datetime NOT NULL,
		PRIMARY KEY (id),
		KEY user_bucket (user_id, balance_bucket),
		KEY user_created (user_id, created_at),
		KEY project_created (project_id, created_at),
		KEY type_created (entry_type, created_at)
	) {$charset_collate};";

	$withdrawals = bina_wallet_withdrawals_table_name();
	$sql2        = "CREATE TABLE {$withdrawals} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL,
		amount decimal(12,2) NOT NULL DEFAULT 0.00,
		status varchar(16) NOT NULL DEFAULT 'pending',
		payout_method varchar(32) NOT NULL DEFAULT 'bank',
		payout_snapshot longtext NULL,
		admin_note varchar(190) NOT NULL DEFAULT '',
		created_at datetime NOT NULL,
		updated_at datetime NOT NULL,
		PRIMARY KEY (id),
		KEY user_status (user_id, status),
		KEY created_at (created_at)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql1 );
	dbDelta( $sql2 );

	update_option( 'bina_wallet_db_version', '1', false );
}
add_action( 'init', 'bina_wallet_maybe_install', 3 );

/**
 * Get wallet balances for a provider.
 *
 * @param int $user_id
 * @return array{available:float,pending:float}
 */
function bina_wallet_get_balances( $user_id ) {
	global $wpdb;
	$table   = bina_wallet_ledger_table_name();
	$user_id = (int) $user_id;
	if ( $user_id < 1 ) {
		return array( 'available' => 0.0, 'pending' => 0.0 );
	}

	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT balance_bucket, SUM(amount) AS s FROM {$table} WHERE user_id = %d GROUP BY balance_bucket",
			$user_id
		),
		ARRAY_A
	);
	$out = array( 'available' => 0.0, 'pending' => 0.0 );
	if ( is_array( $rows ) ) {
		foreach ( $rows as $r ) {
			$bucket = isset( $r['balance_bucket'] ) ? (string) $r['balance_bucket'] : '';
			$sum    = isset( $r['s'] ) ? (float) $r['s'] : 0.0;
			if ( $bucket === 'available' ) {
				$out['available'] = $sum;
			} elseif ( $bucket === 'pending' ) {
				$out['pending'] = $sum;
			}
		}
	}
	return $out;
}

/**
 * Create a ledger entry.
 *
 * Positive amount increases bucket. Negative decreases.
 */
function bina_wallet_ledger_add( $args ) {
	global $wpdb;
	$table = bina_wallet_ledger_table_name();

	$user_id = isset( $args['user_id'] ) ? (int) $args['user_id'] : 0;
	if ( $user_id < 1 ) {
		return new WP_Error( 'bina_wallet_bad', __( 'مستخدم غير صالح.', 'bina' ) );
	}

	$entry_type     = sanitize_text_field( (string) ( $args['entry_type'] ?? '' ) );
	$amount         = (float) ( $args['amount'] ?? 0 );
	$bucket         = sanitize_text_field( (string) ( $args['balance_bucket'] ?? 'available' ) );
	$project_id     = isset( $args['project_id'] ) ? (int) $args['project_id'] : 0;
	$proposal_id    = isset( $args['proposal_id'] ) ? (int) $args['proposal_id'] : 0;
	$plan_id        = isset( $args['plan_id'] ) ? (int) $args['plan_id'] : 0;
	$milestone_no   = isset( $args['milestone_no'] ) ? (int) $args['milestone_no'] : 0;
	$note           = sanitize_text_field( (string) ( $args['note'] ?? '' ) );
	$meta           = isset( $args['meta'] ) ? wp_json_encode( $args['meta'] ) : null;
	$now            = current_time( 'mysql' );

	if ( $entry_type === '' ) {
		return new WP_Error( 'bina_wallet_type', __( 'نوع الحركة مطلوب.', 'bina' ) );
	}
	if ( $bucket !== 'available' && $bucket !== 'pending' ) {
		$bucket = 'available';
	}
	if ( abs( $amount ) < 0.00001 ) {
		return new WP_Error( 'bina_wallet_amount', __( 'المبلغ غير صالح.', 'bina' ) );
	}

	$ok = $wpdb->insert(
		$table,
		array(
			'user_id'        => $user_id,
			'project_id'     => $project_id,
			'proposal_id'    => $proposal_id,
			'plan_id'        => $plan_id,
			'milestone_no'   => $milestone_no,
			'entry_type'     => $entry_type,
			'amount'         => $amount,
			'balance_bucket' => $bucket,
			'note'           => $note,
			'meta'           => $meta,
			'created_at'     => $now,
		),
		array( '%d', '%d', '%d', '%d', '%d', '%s', '%f', '%s', '%s', '%s', '%s' )
	);

	if ( ! $ok ) {
		return new WP_Error( 'bina_wallet_db', __( 'تعذر حفظ الحركة.', 'bina' ) );
	}
	return (int) $wpdb->insert_id;
}

/**
 * Provider creates a withdrawal request from available balance.
 *
 * @param int    $user_id
 * @param float  $amount
 * @param string $method bank|stc
 * @return int|WP_Error
 */
function bina_withdraw_request_create( $user_id, $amount, $method = 'bank' ) {
	global $wpdb;
	$table   = bina_wallet_withdrawals_table_name();
	$user_id = (int) $user_id;
	$amount  = round( (float) $amount, 2 );
	$method  = sanitize_text_field( (string) $method );

	if ( $user_id < 1 || $amount <= 0 ) {
		return new WP_Error( 'bina_withdraw_bad', __( 'بيانات غير صالحة.', 'bina' ) );
	}
	if ( $method !== 'bank' && $method !== 'stc' ) {
		$method = 'bank';
	}

	$balances  = bina_wallet_get_balances( $user_id );
	$available = round( (float) ( $balances['available'] ?? 0 ), 2 );
	if ( $amount - $available > 0.009 ) {
		return new WP_Error(
			'bina_withdraw_funds',
			sprintf(
				/* translators: 1: available amount, 2: requested amount */
				__( 'الرصيد المتاح غير كافٍ. المتاح: %1$s ر.س، المطلوب: %2$s ر.س.', 'bina' ),
				number_format_i18n( $available, 2 ),
				number_format_i18n( $amount, 2 )
			)
		);
	}

	// Snapshot payout method fields at time of request.
	$snapshot = array(
		'method' => $method,
		'bank'   => array(
			'holder' => (string) get_user_meta( $user_id, 'bina_payout_bank_holder', true ),
			'iban'   => (string) get_user_meta( $user_id, 'bina_payout_bank_iban', true ),
			'bank'   => (string) get_user_meta( $user_id, 'bina_payout_bank_name', true ),
		),
		'stc'    => array(
			'phone' => (string) get_user_meta( $user_id, 'bina_payout_stc_phone', true ),
		),
	);

	$now = current_time( 'mysql' );
	$ok  = $wpdb->insert(
		$table,
		array(
			'user_id'         => $user_id,
			'amount'          => $amount,
			'status'          => 'pending',
			'payout_method'   => $method,
			'payout_snapshot' => wp_json_encode( $snapshot ),
			'admin_note'      => '',
			'created_at'      => $now,
			'updated_at'      => $now,
		),
		array( '%d', '%f', '%s', '%s', '%s', '%s', '%s', '%s' )
	);

	if ( ! $ok ) {
		return new WP_Error( 'bina_withdraw_db', __( 'تعذر إنشاء طلب السحب.', 'bina' ) );
	}

	$withdraw_request_id = (int) $wpdb->insert_id;

	// Reserve funds by debiting available bucket immediately.
	$r = bina_wallet_ledger_add(
		array(
			'user_id'        => $user_id,
			'entry_type'     => 'withdraw_reserve',
			'amount'         => -1 * $amount,
			'balance_bucket' => 'available',
			'note'           => 'withdraw request reserve',
			'meta'           => array( 'withdraw_request_id' => $withdraw_request_id ),
		)
	);
	if ( is_wp_error( $r ) ) {
		// If reservation failed, rollback request.
		$wpdb->delete( $table, array( 'id' => $withdraw_request_id ), array( '%d' ) );
		return $r;
	}

	return $withdraw_request_id;
}

/**
 * Fetch withdraw requests for admin reporting.
 *
 * @param array<string,mixed> $args Optional filters: user_id, status, limit.
 * @return array<int,array<string,mixed>>
 */
function bina_withdraw_requests_fetch_for_admin( $args = array() ) {
	global $wpdb;

	$table   = bina_wallet_withdrawals_table_name();
	$user_id = isset( $args['user_id'] ) ? (int) $args['user_id'] : 0;
	$status  = isset( $args['status'] ) ? sanitize_key( (string) $args['status'] ) : '';
	$limit   = isset( $args['limit'] ) ? (int) $args['limit'] : 100;
	$limit   = max( 1, min( 500, $limit ) );

	$where  = array();
	$params = array();

	if ( $user_id > 0 ) {
		$where[]  = 'user_id = %d';
		$params[] = $user_id;
	}
	if ( $status !== '' ) {
		$where[]  = 'status = %s';
		$params[] = $status;
	}

	$sql = "SELECT id, user_id, amount, status, payout_method, created_at, updated_at
		FROM {$table}";
	if ( ! empty( $where ) ) {
		$sql .= ' WHERE ' . implode( ' AND ', $where );
	}
	$sql .= ' ORDER BY id DESC LIMIT %d';
	$params[] = $limit;

	$rows = $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return is_array( $rows ) ? $rows : array();
}

