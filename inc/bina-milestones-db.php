<?php
/**
 * Milestones DB (payment schedule) for projects.
 *
 * Creates a per-project schedule derived from the accepted proposal payment plan.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_milestones_db_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'bina_milestones';
}

function bina_milestones_maybe_install() {
	$ver = get_option( 'bina_milestones_db_version', '0' );
	if ( $ver === '1' ) {
		return;
	}

	global $wpdb;
	$table           = bina_milestones_db_table_name();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		project_id bigint(20) unsigned NOT NULL,
		proposal_id bigint(20) unsigned NOT NULL DEFAULT 0,
		provider_id bigint(20) unsigned NOT NULL DEFAULT 0,
		milestone_no int(11) NOT NULL DEFAULT 0,
		title varchar(190) NOT NULL DEFAULT '',
		amount decimal(12,2) NOT NULL DEFAULT 0.00,
		status varchar(16) NOT NULL DEFAULT 'scheduled',
		due_at datetime NULL,
		submitted_at datetime NULL,
		approved_at datetime NULL,
		released_at datetime NULL,
		meta longtext NULL,
		created_at datetime NOT NULL,
		updated_at datetime NOT NULL,
		PRIMARY KEY (id),
		UNIQUE KEY project_milestone (project_id, milestone_no),
		KEY project_status (project_id, status),
		KEY provider_status (provider_id, status),
		KEY due_at (due_at)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	update_option( 'bina_milestones_db_version', '1', false );
}
add_action( 'init', 'bina_milestones_maybe_install', 3 );

/**
 * @param int $project_id
 * @return int
 */
function bina_milestones_count_for_project( $project_id ) {
	global $wpdb;
	$table      = bina_milestones_db_table_name();
	$project_id = (int) $project_id;
	if ( $project_id < 1 ) {
		return 0;
	}
	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM {$table} WHERE project_id = %d",
			$project_id
		)
	);
}

/**
 * Fetch milestones for a project.
 *
 * @param int $project_id
 * @return array<int,array<string,mixed>>
 */
function bina_milestones_fetch_for_project( $project_id ) {
	global $wpdb;
	$table      = bina_milestones_db_table_name();
	$project_id = (int) $project_id;
	if ( $project_id < 1 ) {
		return array();
	}
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT id, project_id, proposal_id, provider_id, milestone_no, title, amount, status, due_at, submitted_at, approved_at, released_at, meta, created_at, updated_at
			 FROM {$table}
			 WHERE project_id = %d
			 ORDER BY milestone_no ASC",
			$project_id
		),
		ARRAY_A
	);
	return is_array( $rows ) ? $rows : array();
}

/**
 * Update milestone status with basic guard.
 *
 * @param int    $milestone_id
 * @param string $new_status
 * @param array<string,mixed> $extra_cols
 * @return true|WP_Error
 */
function bina_milestone_update_status( $milestone_id, $new_status, $extra_cols = array() ) {
	global $wpdb;
	$table        = bina_milestones_db_table_name();
	$milestone_id = (int) $milestone_id;
	$new_status   = sanitize_text_field( (string) $new_status );
	if ( $milestone_id < 1 || $new_status === '' ) {
		return new WP_Error( 'bina_ms_bad', __( 'بيانات غير صالحة.', 'bina' ) );
	}
	$now  = current_time( 'mysql' );
	$data = array_merge(
		array(
			'status'     => $new_status,
			'updated_at' => $now,
		),
		$extra_cols
	);
	$where = array( 'id' => $milestone_id );

	$ok = $wpdb->update( $table, $data, $where ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
	if ( $ok === false ) {
		return new WP_Error( 'bina_ms_db', __( 'تعذر تحديث الدفعة.', 'bina' ) );
	}
	return true;
}

/**
 * Fetch milestones waiting for admin funding confirmation.
 *
 * @param int $limit
 * @return array<int,array<string,mixed>>
 */
function bina_milestones_fetch_payment_requested( $limit = 200 ) {
	global $wpdb;
	$table = bina_milestones_db_table_name();
	$limit = max( 1, min( 500, (int) $limit ) );

	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT id, project_id, proposal_id, provider_id, milestone_no, title, amount, status, updated_at
			 FROM {$table}
			 WHERE status = %s
			 ORDER BY updated_at DESC
			 LIMIT %d",
			'payment_requested',
			$limit
		),
		ARRAY_A
	);
	return is_array( $rows ) ? $rows : array();
}

/**
 * Split total into N amounts with 2-dec rounding and exact sum.
 *
 * @param float $total
 * @param int   $n
 * @return float[]
 */
function bina_milestones_split_amounts_equal( $total, $n ) {
	$total = (float) $total;
	$n     = (int) $n;
	if ( $n < 1 ) {
		return array();
	}
	if ( $total < 0 ) {
		$total = 0;
	}
	$base = round( $total / $n, 2 );
	$out  = array_fill( 0, $n, $base );
	$sum  = round( array_sum( $out ), 2 );
	$diff = round( $total - $sum, 2 );
	$out[ $n - 1 ] = round( $out[ $n - 1 ] + $diff, 2 );
	return $out;
}

/**
 * Create milestones for a project based on an accepted proposal row.
 * Idempotent: if milestones exist for project, does nothing.
 *
 * @param array<string,mixed> $proposal Proposal row.
 * @return true|WP_Error
 */
function bina_milestones_create_from_accepted_proposal( $proposal ) {
	global $wpdb;
	$table = bina_milestones_db_table_name();

	$project_id   = (int) ( $proposal['project_id'] ?? 0 );
	$proposal_id  = (int) ( $proposal['id'] ?? 0 );
	$provider_id  = (int) ( $proposal['provider_id'] ?? 0 );
	$total_amount = (float) ( $proposal['price_total'] ?? 0 );
	$duration     = (int) ( $proposal['duration_days'] ?? 0 );
	$plan_key     = bina_normalize_payment_plan_key( (string) ( $proposal['plan_key'] ?? 'pay_at_completion' ) );

	if ( $project_id < 1 || $proposal_id < 1 || $provider_id < 1 ) {
		return new WP_Error( 'bina_ms_bad', __( 'بيانات غير صالحة لإنشاء الدفعات.', 'bina' ) );
	}

	if ( bina_milestones_count_for_project( $project_id ) > 0 ) {
		return true;
	}

	$now_mysql = current_time( 'mysql' );
	$now_ts    = current_time( 'timestamp' );

	$n      = 1;
	$titles = array();
	$due_ts = array();

	if ( $plan_key === 'four_installments_equal' ) {
		$n = 4;
		for ( $i = 1; $i <= 4; $i++ ) {
			$titles[] = sprintf( __( 'دفعة رقم %d', 'bina' ), $i );
		}
		$period_days = $duration > 0 ? max( 1, (int) ceil( $duration / 4 ) ) : 7;
		for ( $i = 1; $i <= 4; $i++ ) {
			$due_ts[] = $now_ts + ( $period_days * DAY_IN_SECONDS * $i );
		}
	} elseif ( $plan_key === 'eleven_months' ) {
		$n = 11;
		for ( $i = 1; $i <= 11; $i++ ) {
			$titles[] = sprintf( __( 'دفعة شهر %d', 'bina' ), $i );
		}
		for ( $i = 1; $i <= 11; $i++ ) {
			$due_ts[] = (int) strtotime( "+{$i} month", $now_ts );
		}
	} else {
		$n        = 1;
		$titles[] = __( 'الدفع بعد اكتمال المشروع', 'bina' );
		$due_ts[] = $duration > 0 ? ( $now_ts + ( $duration * DAY_IN_SECONDS ) ) : 0;
	}

	$amounts = bina_milestones_split_amounts_equal( $total_amount, $n );
	if ( count( $amounts ) !== $n ) {
		return new WP_Error( 'bina_ms_amounts', __( 'تعذر حساب مبالغ الدفعات.', 'bina' ) );
	}

	for ( $i = 1; $i <= $n; $i++ ) {
		$title = $titles[ $i - 1 ] ?? sprintf( __( 'دفعة رقم %d', 'bina' ), $i );
		$due   = $due_ts[ $i - 1 ] ?? 0;

		$ok = $wpdb->insert(
			$table,
			array(
				'project_id'   => $project_id,
				'proposal_id'  => $proposal_id,
				'provider_id'  => $provider_id,
				'milestone_no' => $i,
				'title'        => (string) $title,
				'amount'       => (float) $amounts[ $i - 1 ],
				'status'       => 'scheduled',
				// current_time('timestamp') is local, so store local mysql datetime for consistency with other tables.
				'due_at'       => $due ? date( 'Y-m-d H:i:s', $due ) : null,
				'meta'         => wp_json_encode(
					array(
						'plan_key'       => $plan_key,
						'duration_days'  => $duration,
						'total_amount'   => $total_amount,
						'created_reason' => 'proposal_accept',
					)
				),
				'created_at'   => $now_mysql,
				'updated_at'   => $now_mysql,
			),
			array( '%d', '%d', '%d', '%d', '%s', '%f', '%s', '%s', '%s', '%s', '%s' )
		);
		if ( ! $ok ) {
			return new WP_Error( 'bina_ms_db', __( 'تعذر إنشاء الدفعات.', 'bina' ) );
		}
	}

	return true;
}

