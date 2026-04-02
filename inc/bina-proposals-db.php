<?php
/**
 * Proposals (bids) DB for marketplace projects (Upwork-style).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_proposals_db_table_name() {
	global $wpdb;
	return $wpdb->prefix . 'bina_proposals';
}

/**
 * Ensure proposals table has required columns (safe to call repeatedly).
 *
 * @return void
 */
function bina_proposals_ensure_schema() {
	global $wpdb;
	$table = bina_proposals_db_table_name();

	// Table might not exist yet.
	$exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
	if ( (string) $exists !== (string) $table ) {
		return;
	}

	$cols = $wpdb->get_col( "SHOW COLUMNS FROM {$table}", 0 ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	$cols = is_array( $cols ) ? $cols : array();

	if ( ! in_array( 'plan_key', $cols, true ) ) {
		$wpdb->query( "ALTER TABLE {$table} ADD COLUMN plan_key varchar(32) NOT NULL DEFAULT 'pay_at_completion' AFTER message" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}
	if ( ! in_array( 'plan_meta', $cols, true ) ) {
		$wpdb->query( "ALTER TABLE {$table} ADD COLUMN plan_meta longtext NULL AFTER plan_key" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}
}

function bina_proposals_maybe_install() {
	$ver = get_option( 'bina_proposals_db_version', '0' );
	// Always ensure schema; dbDelta/ALTER are safe to re-run.

	global $wpdb;
	$table           = bina_proposals_db_table_name();
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		project_id bigint(20) unsigned NOT NULL,
		provider_id bigint(20) unsigned NOT NULL,
		price_total decimal(12,2) NOT NULL DEFAULT 0.00,
		duration_days int(11) NOT NULL DEFAULT 0,
		message longtext NOT NULL,
		plan_key varchar(32) NOT NULL DEFAULT 'pay_at_completion',
		plan_meta longtext NULL,
		status varchar(16) NOT NULL DEFAULT 'pending',
		created_at datetime NOT NULL,
		updated_at datetime NOT NULL,
		PRIMARY KEY (id),
		UNIQUE KEY project_provider (project_id, provider_id),
		KEY project_status (project_id, status),
		KEY provider_status (provider_id, status),
		KEY project_created (project_id, created_at)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	// Make sure newly added columns exist even if dbDelta didn't add them (MySQL edge cases).
	bina_proposals_ensure_schema();

	update_option( 'bina_proposals_db_version', '2', false );
}
add_action( 'init', 'bina_proposals_maybe_install', 3 );

/**
 * Payment plan labels for proposals.
 *
 * @return array<string,string>
 */
function bina_get_payment_plan_labels() {
	return array(
		'pay_at_completion'         => __( 'الدفع بعد اكتمال المشروع', 'bina' ),
		'four_installments_equal'   => __( 'الدفع على 4 دفعات', 'bina' ),
		'eleven_installments_equal' => __( 'الدفع على 11 دفعة', 'bina' ),
	);
}

/**
 * Normalize a payment plan key to known values.
 *
 * @param string $key
 * @return string
 */
function bina_normalize_payment_plan_key( $key ) {
	$key = (string) $key;
	if ( 'eleven_months' === $key ) {
		$key = 'eleven_installments_equal';
	}
	$allowed = array_keys( bina_get_payment_plan_labels() );
	if ( in_array( $key, $allowed, true ) ) {
		return $key;
	}
	return 'pay_at_completion';
}

/**
 * Whether the project is locked (offer accepted / assigned).
 *
 * @param int $project_id
 * @return bool
 */
function bina_project_is_market_locked( $project_id ) {
	$project_id = (int) $project_id;
	if ( $project_id < 1 ) {
		return false;
	}
	$assigned = (int) bina_get_project_assigned_provider_id( $project_id );
	if ( $assigned > 0 ) {
		return true;
	}
	$locked = get_post_meta( $project_id, '_bina_market_locked', true );
	return (string) $locked === '1';
}

/**
 * Insert or update a proposal from provider to a project.
 *
 * @param int    $project_id
 * @param int    $provider_id
 * @param float  $price_total
 * @param int    $duration_days
 * @param string $message
 * @return int|WP_Error Proposal ID.
 */
function bina_proposal_upsert( $project_id, $provider_id, $price_total, $duration_days, $message, $plan_key = 'pay_at_completion', $plan_meta = '' ) {
	global $wpdb;
	$table = bina_proposals_db_table_name();

	$project_id   = (int) $project_id;
	$provider_id  = (int) $provider_id;
	$duration_days = (int) $duration_days;
	$price_total  = (float) $price_total;
	$message      = sanitize_textarea_field( (string) $message );
	$plan_key     = bina_normalize_payment_plan_key( (string) $plan_key );
	$plan_meta    = is_string( $plan_meta ) ? $plan_meta : '';
	if ( strlen( $plan_meta ) > 20000 ) {
		$plan_meta = substr( $plan_meta, 0, 20000 );
	}

	if ( $project_id < 1 || $provider_id < 1 ) {
		return new WP_Error( 'bina_prop_bad', __( 'Ø¨ÙŠØ§Ù†Ø§Øª ØºÙŠØ± ØµØ§Ù„Ø­Ø©.', 'bina' ) );
	}
	if ( $message === '' ) {
		return new WP_Error( 'bina_prop_empty', __( 'Ø±Ø³Ø§Ù„Ø© Ø§Ù„Ø¹Ø±Ø¶ Ù…Ø·Ù„ÙˆØ¨Ø©.', 'bina' ) );
	}
	if ( $duration_days < 1 ) {
		return new WP_Error( 'bina_prop_duration', __( 'Ù…Ø¯Ø© Ø§Ù„ØªÙ†ÙÙŠØ° Ù…Ø·Ù„ÙˆØ¨Ø©.', 'bina' ) );
	}
	if ( $price_total < 0 ) {
		$price_total = 0;
	}

	$post = get_post( $project_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		return new WP_Error( 'bina_prop_project', __( 'Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ ØºÙŠØ± ØµØ§Ù„Ø­.', 'bina' ) );
	}

	if ( bina_project_is_market_locked( $project_id ) ) {
		return new WP_Error( 'bina_prop_locked', __( 'Ù‡Ø°Ø§ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ ØºÙŠØ± Ù…ØªØ§Ø­ Ù„Ù„ØªÙ‚Ø¯ÙŠÙ… Ø­Ø§Ù„ÙŠØ§Ù‹.', 'bina' ) );
	}

	$u = get_userdata( $provider_id );
	if ( ! $u || ! bina_user_is_service_provider( $u ) ) {
		return new WP_Error( 'bina_prop_role', __( 'Ù‡Ø°Ø§ Ø§Ù„Ù…Ø³ØªØ®Ø¯Ù… Ù„ÙŠØ³ Ù…Ø²ÙˆØ¯ Ø®Ø¯Ù…Ø©.', 'bina' ) );
	}

	// Provider cannot bid on own project.
	if ( (int) $post->post_author === $provider_id ) {
		return new WP_Error( 'bina_prop_own', __( 'Ù„Ø§ ÙŠÙ…ÙƒÙ†Ùƒ Ø§Ù„ØªÙ‚Ø¯ÙŠÙ… Ø¹Ù„Ù‰ Ù…Ø´Ø±ÙˆØ¹Ùƒ.', 'bina' ) );
	}

	$now = current_time( 'mysql' );

	$existing_id = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT id FROM {$table} WHERE project_id = %d AND provider_id = %d LIMIT 1",
			$project_id,
			$provider_id
		)
	);

	if ( $existing_id > 0 ) {
		$wpdb->update(
			$table,
			array(
				'price_total'   => $price_total,
				'duration_days' => $duration_days,
				'message'       => $message,
				'plan_key'      => $plan_key,
				'plan_meta'     => $plan_meta,
				'status'        => 'pending',
				'updated_at'    => $now,
			),
			array(
				'id' => $existing_id,
			),
			array( '%f', '%d', '%s', '%s', '%s', '%s', '%s' ),
			array( '%d' )
		);
		return $existing_id;
	}

	$ok = $wpdb->insert(
		$table,
		array(
			'project_id'    => $project_id,
			'provider_id'   => $provider_id,
			'price_total'   => $price_total,
			'duration_days' => $duration_days,
			'message'       => $message,
			'plan_key'      => $plan_key,
			'plan_meta'     => $plan_meta,
			'status'        => 'pending',
			'created_at'    => $now,
			'updated_at'    => $now,
		),
		array( '%d', '%d', '%f', '%d', '%s', '%s', '%s', '%s', '%s', '%s' )
	);
	if ( ! $ok ) {
		return new WP_Error( 'bina_prop_db', __( 'ØªØ¹Ø°Ø± Ø­ÙØ¸ Ø§Ù„Ø¹Ø±Ø¶.', 'bina' ) );
	}

	return (int) $wpdb->insert_id;
}

/**
 * @param int $project_id
 * @return array<int,array<string,mixed>>
 */
function bina_proposals_fetch_for_project( $project_id ) {
	global $wpdb;
	$table      = bina_proposals_db_table_name();
	$project_id = (int) $project_id;
	if ( $project_id < 1 ) {
		return array();
	}

	$sql  = $wpdb->prepare(
	"SELECT id, project_id, provider_id, price_total, duration_days, message, plan_key, plan_meta, status, created_at, updated_at
		FROM {$table}
		WHERE project_id = %d
		ORDER BY created_at DESC
		LIMIT 500",
		$project_id
	);
	$rows = $wpdb->get_results( $sql, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return is_array( $rows ) ? $rows : array();
}

/**
 * Get provider's proposal for a project (if any).
 *
 * @param int $project_id
 * @param int $provider_id
 * @return array<string,mixed>|null
 */
function bina_proposal_get_for_project_provider( $project_id, $provider_id ) {
	global $wpdb;
	$table       = bina_proposals_db_table_name();
	$project_id  = (int) $project_id;
	$provider_id = (int) $provider_id;
	if ( $project_id < 1 || $provider_id < 1 ) {
		return null;
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, project_id, provider_id, price_total, duration_days, message, plan_key, plan_meta, status, created_at, updated_at
			FROM {$table}
			WHERE project_id = %d AND provider_id = %d
			LIMIT 1",
			$project_id,
			$provider_id
		),
		ARRAY_A
	);

	return is_array( $row ) ? $row : null;
}

/**
 * @param int $provider_id
 * @return array<int,array<string,mixed>>
 */
function bina_proposals_fetch_for_provider( $provider_id ) {
	global $wpdb;
	$table       = bina_proposals_db_table_name();
	$provider_id = (int) $provider_id;
	if ( $provider_id < 1 ) {
		return array();
	}

	$sql  = $wpdb->prepare(
	"SELECT id, project_id, provider_id, price_total, duration_days, message, plan_key, plan_meta, status, created_at, updated_at
		FROM {$table}
		WHERE provider_id = %d
		ORDER BY updated_at DESC
		LIMIT 500",
		$provider_id
	);
	$rows = $wpdb->get_results( $sql, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return is_array( $rows ) ? $rows : array();
}

/**
 * Get a single proposal row by id.
 *
 * @param int $proposal_id
 * @return array<string,mixed>|null
 */
function bina_proposal_get_by_id( $proposal_id ) {
	global $wpdb;
	$table       = bina_proposals_db_table_name();
	$proposal_id = (int) $proposal_id;
	if ( $proposal_id < 1 ) {
		return null;
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, project_id, provider_id, price_total, duration_days, message, plan_key, plan_meta, status, created_at, updated_at
			FROM {$table}
			WHERE id = %d
			LIMIT 1",
			$proposal_id
		),
		ARRAY_A
	);

	return is_array( $row ) ? $row : null;
}

/**
 * Accept a proposal (locks project and assigns provider).
 *
 * @param int $proposal_id
 * @param int $actor_id Customer/admin performing action.
 * @return true|WP_Error
 */
function bina_proposal_accept( $proposal_id, $actor_id ) {
	global $wpdb;
	$table       = bina_proposals_db_table_name();
	$proposal_id = (int) $proposal_id;
	$actor_id    = (int) $actor_id;
	if ( $proposal_id < 1 || $actor_id < 1 ) {
		return new WP_Error( 'bina_prop_bad', __( 'Ø¨ÙŠØ§Ù†Ø§Øª ØºÙŠØ± ØµØ§Ù„Ø­Ø©.', 'bina' ) );
	}

	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, project_id, provider_id, price_total, duration_days, plan_key, plan_meta, status FROM {$table} WHERE id = %d LIMIT 1",
			$proposal_id
		),
		ARRAY_A
	);
	if ( ! is_array( $row ) ) {
		return new WP_Error( 'bina_prop_nf', __( 'Ø§Ù„Ø¹Ø±Ø¶ ØºÙŠØ± Ù…ÙˆØ¬ÙˆØ¯.', 'bina' ) );
	}

	$project_id  = (int) $row['project_id'];
	$provider_id = (int) $row['provider_id'];
	$plan_key    = isset( $row['plan_key'] ) ? bina_normalize_payment_plan_key( (string) $row['plan_key'] ) : 'pay_at_completion';
	$plan_meta   = isset( $row['plan_meta'] ) ? (string) $row['plan_meta'] : '';
	$post        = get_post( $project_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		return new WP_Error( 'bina_prop_project', __( 'Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ ØºÙŠØ± ØµØ§Ù„Ø­.', 'bina' ) );
	}

	// Only customer (project author) or admin can accept.
	if ( ! user_can( $actor_id, 'manage_options' ) && (int) $post->post_author !== $actor_id ) {
		return new WP_Error( 'bina_prop_forbidden', __( 'ØºÙŠØ± Ù…ØµØ±Ø­.', 'bina' ) );
	}

	if ( bina_project_is_market_locked( $project_id ) ) {
		return new WP_Error( 'bina_prop_locked', __( 'Ø§Ù„Ù…Ø´Ø±ÙˆØ¹ Ù…Ù‚ÙÙˆÙ„ Ø¨Ø§Ù„ÙØ¹Ù„.', 'bina' ) );
	}

	$now = current_time( 'mysql' );

	// Mark selected as accepted.
	$updated = $wpdb->update(
		$table,
		array(
			'status'     => 'accepted',
			'updated_at' => $now,
		),
		array(
			'id' => $proposal_id,
		),
		array( '%s', '%s' ),
		array( '%d' )
	);
	if ( $updated === false ) {
		return new WP_Error( 'bina_prop_db', __( 'ØªØ¹Ø°Ø± ØªØ­Ø¯ÙŠØ« Ø­Ø§Ù„Ø© Ø§Ù„Ø¹Ø±Ø¶.', 'bina' ) );
	}

	// Verify it actually became accepted.
	$st_after = (string) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT status FROM {$table} WHERE id = %d LIMIT 1",
			$proposal_id
		)
	);
	if ( $st_after !== 'accepted' ) {
		return new WP_Error( 'bina_prop_db', __( 'ØªØ¹Ø°Ø± ØªØ«Ø¨ÙŠØª Ù‚Ø¨ÙˆÙ„ Ø§Ù„Ø¹Ø±Ø¶.', 'bina' ) );
	}

	// Reject all other proposals for this project.
	$rej = $wpdb->query(
		$wpdb->prepare(
			"UPDATE {$table} SET status = %s, updated_at = %s WHERE project_id = %d AND id <> %d",
			'rejected',
			$now,
			$project_id,
			$proposal_id
		)
	);
	if ( $rej === false ) {
		return new WP_Error( 'bina_prop_db', __( 'ØªØ¹Ø°Ø± Ø±ÙØ¶ Ø§Ù„Ø¹Ø±ÙˆØ¶ Ø§Ù„Ø£Ø®Ø±Ù‰.', 'bina' ) );
	}

	// Lock project and assign provider.
	update_post_meta( $project_id, '_bina_market_locked', '1' );
	update_post_meta( $project_id, '_bina_accepted_proposal_id', (string) $proposal_id );
	bina_set_project_assigned_provider_id( $project_id, $provider_id );
	update_post_meta( $project_id, '_bina_payment_plan', $plan_key );
	if ( $plan_meta !== '' ) {
		update_post_meta( $project_id, '_bina_payment_plan_meta', $plan_meta );
	}

	// Optional: move status to active.
	update_post_meta( $project_id, '_bina_project_status', 'selected' );

	// Create milestones schedule based on accepted proposal (idempotent).
	if ( function_exists( 'bina_milestones_create_from_accepted_proposal' ) ) {
		$ms = bina_milestones_create_from_accepted_proposal(
			array(
				'id'            => (int) $proposal_id,
				'project_id'     => (int) $project_id,
				'provider_id'    => (int) $provider_id,
				'price_total'    => (float) ( $row['price_total'] ?? 0 ),
				'duration_days'  => (int) ( $row['duration_days'] ?? 0 ),
				'plan_key'       => (string) $plan_key,
				'plan_meta'      => (string) $plan_meta,
			)
		);
		if ( is_wp_error( $ms ) ) {
			return $ms;
		}
	}

	return true;
}


