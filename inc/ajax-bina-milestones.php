<?php
/**
 * AJAX: milestones lifecycle (fund / submit / approve / release).
 *
 * MVP: funding is a "manual confirmation" (no gateway yet). Funds go to provider pending bucket,
 * then on approval they are released to available.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param int $milestone_id
 * @return array<string,mixed>|null
 */
function bina_milestone_get_by_id( $milestone_id ) {
	global $wpdb;
	$table        = function_exists( 'bina_milestones_db_table_name' ) ? bina_milestones_db_table_name() : '';
	$milestone_id = (int) $milestone_id;
	if ( $milestone_id < 1 || $table === '' ) {
		return null;
	}
	$row = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT id, project_id, proposal_id, provider_id, milestone_no, title, amount, status, due_at, submitted_at, approved_at, released_at
			 FROM {$table}
			 WHERE id = %d
			 LIMIT 1",
			$milestone_id
		),
		ARRAY_A
	);
	return is_array( $row ) ? $row : null;
}

/**
 * Customer requests milestone funding (MVP).
 * POST: milestone_id, nonce
 */
function bina_ajax_request_milestone_funding() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_milestones', 'nonce' );

	$user_id      = get_current_user_id();
	$milestone_id = isset( $_POST['milestone_id'] ) ? absint( $_POST['milestone_id'] ) : 0;
	$row          = bina_milestone_get_by_id( $milestone_id );
	if ( ! is_array( $row ) ) {
		wp_send_json_error( array( 'message' => __( 'الدفعة غير موجودة.', 'bina' ) ), 404 );
	}

	$project_id = (int) ( $row['project_id'] ?? 0 );
	$post       = get_post( $project_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		wp_send_json_error( array( 'message' => __( 'المشروع غير صالح.', 'bina' ) ), 400 );
	}
	if ( ! user_can( $user_id, 'manage_options' ) && (int) $post->post_author !== (int) $user_id ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح.', 'bina' ) ), 403 );
	}

	$status = (string) ( $row['status'] ?? '' );
	if ( $status !== 'scheduled' ) {
		wp_send_json_error( array( 'message' => __( 'لا يمكن طلب تمويل هذه الدفعة الآن.', 'bina' ) ), 400 );
	}

	$u = bina_milestone_update_status( $milestone_id, 'payment_requested' );
	if ( is_wp_error( $u ) ) {
		wp_send_json_error( array( 'message' => $u->get_error_message() ), 400 );
	}

	// Notify admins.
	$admins = get_users( array( 'role' => 'administrator', 'number' => 50, 'fields' => array( 'ID' ) ) );
	if ( is_array( $admins ) ) {
		foreach ( $admins as $a ) {
			$aid = is_object( $a ) && isset( $a->ID ) ? (int) $a->ID : (int) $a;
			if ( $aid < 1 ) {
				continue;
			}
			$title = __( 'طلب تمويل دفعة', 'bina' );
			$body  = __( 'هناك طلب جديد لتأكيد تمويل دفعة (Milestone).', 'bina' );
			if ( function_exists( 'bina_notifications_insert' ) ) {
				bina_notifications_insert( $aid, 'milestone_funding_requested', $project_id, $user_id, $title, $body );
			}
		}
	}

	wp_send_json_success( array( 'ok' => 1 ) );
}
add_action( 'wp_ajax_bina_request_milestone_funding', 'bina_ajax_request_milestone_funding' );

/**
 * Confirm milestone funding and credit provider pending bucket.
 *
 * @param int $milestone_id
 * @param int $admin_id
 * @return true|WP_Error
 */
function bina_milestone_confirm_funding( $milestone_id, $admin_id ) {
	$milestone_id = (int) $milestone_id;
	$admin_id     = (int) $admin_id;
	if ( $milestone_id < 1 || $admin_id < 1 ) {
		return new WP_Error( 'bina_ms_bad', __( 'بيانات غير صالحة.', 'bina' ) );
	}
	if ( ! user_can( $admin_id, 'manage_options' ) ) {
		return new WP_Error( 'bina_ms_forbidden', __( 'هذه العملية للأدمن فقط.', 'bina' ) );
	}

	$row = bina_milestone_get_by_id( $milestone_id );
	if ( ! is_array( $row ) ) {
		return new WP_Error( 'bina_ms_nf', __( 'الدفعة غير موجودة.', 'bina' ) );
	}

	$project_id = (int) ( $row['project_id'] ?? 0 );
	$post       = get_post( $project_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		return new WP_Error( 'bina_ms_project', __( 'المشروع غير صالح.', 'bina' ) );
	}

	$status = (string) ( $row['status'] ?? '' );
	if ( $status !== 'payment_requested' ) {
		return new WP_Error( 'bina_ms_state', __( 'يجب أن يطلب العميل التمويل أولاً.', 'bina' ) );
	}

	$provider_id = (int) ( $row['provider_id'] ?? 0 );
	$amount      = (float) ( $row['amount'] ?? 0 );
	$proposal_id = (int) ( $row['proposal_id'] ?? 0 );
	$no          = (int) ( $row['milestone_no'] ?? 0 );
	if ( $provider_id < 1 || $amount <= 0 ) {
		return new WP_Error( 'bina_ms_bad_row', __( 'بيانات الدفعة غير صالحة.', 'bina' ) );
	}

	$u = bina_milestone_update_status( $milestone_id, 'funded' );
	if ( is_wp_error( $u ) ) {
		return $u;
	}

	$led = bina_wallet_ledger_add(
		array(
			'user_id'        => $provider_id,
			'project_id'     => $project_id,
			'proposal_id'    => $proposal_id,
			'milestone_no'   => $no,
			'entry_type'     => 'escrow_funded',
			'amount'         => (float) $amount,
			'balance_bucket' => 'pending',
			'note'           => 'milestone funded (admin confirm)',
			'meta'           => array( 'milestone_id' => $milestone_id, 'admin_id' => $admin_id ),
		)
	);
	if ( is_wp_error( $led ) ) {
		// Rollback status if ledger failed.
		bina_milestone_update_status( $milestone_id, 'payment_requested' );
		return $led;
	}

	return true;
}

/**
 * Admin confirms milestone funding (MVP manual funding).
 * POST: milestone_id, nonce
 */
function bina_ajax_fund_milestone() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_milestones', 'nonce' );

	$user_id      = get_current_user_id();
	$milestone_id = isset( $_POST['milestone_id'] ) ? absint( $_POST['milestone_id'] ) : 0;
	$row          = bina_milestone_get_by_id( $milestone_id );
	if ( ! is_array( $row ) ) {
		wp_send_json_error( array( 'message' => __( 'الدفعة غير موجودة.', 'bina' ) ), 404 );
	}

	$project_id = (int) ( $row['project_id'] ?? 0 );
	$post       = get_post( $project_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		wp_send_json_error( array( 'message' => __( 'المشروع غير صالح.', 'bina' ) ), 400 );
	}
	// Admin-only confirmation.
	if ( ! user_can( $user_id, 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'هذه العملية للأدمن فقط.', 'bina' ) ), 403 );
	}

	$status = (string) ( $row['status'] ?? '' );
	if ( $status !== 'payment_requested' ) {
		wp_send_json_error( array( 'message' => __( 'يجب أن يطلب العميل التمويل أولاً.', 'bina' ) ), 400 );
	}

	$r = bina_milestone_confirm_funding( $milestone_id, $user_id );
	if ( is_wp_error( $r ) ) {
		wp_send_json_error( array( 'message' => $r->get_error_message() ), 400 );
	}

	wp_send_json_success( array( 'ok' => 1 ) );
}
add_action( 'wp_ajax_bina_fund_milestone', 'bina_ajax_fund_milestone' );

/**
 * Provider submits a funded milestone.
 * POST: milestone_id, nonce
 */
function bina_ajax_submit_milestone() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_milestones', 'nonce' );

	$user_id      = get_current_user_id();
	$milestone_id = isset( $_POST['milestone_id'] ) ? absint( $_POST['milestone_id'] ) : 0;
	$row          = bina_milestone_get_by_id( $milestone_id );
	if ( ! is_array( $row ) ) {
		wp_send_json_error( array( 'message' => __( 'الدفعة غير موجودة.', 'bina' ) ), 404 );
	}

	$provider_id = (int) ( $row['provider_id'] ?? 0 );
	if ( (int) $provider_id !== (int) $user_id && ! user_can( $user_id, 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح.', 'bina' ) ), 403 );
	}

	$status = (string) ( $row['status'] ?? '' );
	if ( $status !== 'funded' ) {
		wp_send_json_error( array( 'message' => __( 'يجب تمويل الدفعة أولاً.', 'bina' ) ), 400 );
	}

	$u = bina_milestone_update_status(
		$milestone_id,
		'submitted',
		array(
			'submitted_at' => current_time( 'mysql' ),
		)
	);
	if ( is_wp_error( $u ) ) {
		wp_send_json_error( array( 'message' => $u->get_error_message() ), 400 );
	}
	wp_send_json_success( array( 'ok' => 1 ) );
}
add_action( 'wp_ajax_bina_submit_milestone', 'bina_ajax_submit_milestone' );

/**
 * Customer approves a submitted milestone, releasing funds to provider available.
 * POST: milestone_id, nonce
 */
function bina_ajax_approve_milestone() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_milestones', 'nonce' );

	$user_id      = get_current_user_id();
	$milestone_id = isset( $_POST['milestone_id'] ) ? absint( $_POST['milestone_id'] ) : 0;
	$row          = bina_milestone_get_by_id( $milestone_id );
	if ( ! is_array( $row ) ) {
		wp_send_json_error( array( 'message' => __( 'الدفعة غير موجودة.', 'bina' ) ), 404 );
	}

	$project_id = (int) ( $row['project_id'] ?? 0 );
	$post       = get_post( $project_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		wp_send_json_error( array( 'message' => __( 'المشروع غير صالح.', 'bina' ) ), 400 );
	}
	if ( ! user_can( $user_id, 'manage_options' ) && (int) $post->post_author !== (int) $user_id ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح.', 'bina' ) ), 403 );
	}

	$status = (string) ( $row['status'] ?? '' );
	if ( $status !== 'submitted' ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسليم الدفعة أولاً.', 'bina' ) ), 400 );
	}

	$provider_id = (int) ( $row['provider_id'] ?? 0 );
	$amount      = (float) ( $row['amount'] ?? 0 );
	$proposal_id = (int) ( $row['proposal_id'] ?? 0 );
	$no          = (int) ( $row['milestone_no'] ?? 0 );
	if ( $provider_id < 1 || $amount <= 0 ) {
		wp_send_json_error( array( 'message' => __( 'بيانات الدفعة غير صالحة.', 'bina' ) ), 400 );
	}

	// Mark approved.
	$u = bina_milestone_update_status(
		$milestone_id,
		'approved',
		array(
			'approved_at' => current_time( 'mysql' ),
		)
	);
	if ( is_wp_error( $u ) ) {
		wp_send_json_error( array( 'message' => $u->get_error_message() ), 400 );
	}

	// Release funds: pending -amount, available +amount.
	$debit = bina_wallet_ledger_add(
		array(
			'user_id'        => $provider_id,
			'project_id'     => $project_id,
			'proposal_id'    => $proposal_id,
			'milestone_no'   => $no,
			'entry_type'     => 'escrow_release_debit',
			'amount'         => -1 * (float) $amount,
			'balance_bucket' => 'pending',
			'note'           => 'milestone approved release (debit pending)',
			'meta'           => array( 'milestone_id' => $milestone_id ),
		)
	);
	if ( is_wp_error( $debit ) ) {
		wp_send_json_error( array( 'message' => $debit->get_error_message() ), 400 );
	}
	$credit = bina_wallet_ledger_add(
		array(
			'user_id'        => $provider_id,
			'project_id'     => $project_id,
			'proposal_id'    => $proposal_id,
			'milestone_no'   => $no,
			'entry_type'     => 'escrow_release_credit',
			'amount'         => (float) $amount,
			'balance_bucket' => 'available',
			'note'           => 'milestone approved release (credit available)',
			'meta'           => array( 'milestone_id' => $milestone_id ),
		)
	);
	if ( is_wp_error( $credit ) ) {
		// Best-effort rollback debit.
		bina_wallet_ledger_add(
			array(
				'user_id'        => $provider_id,
				'project_id'     => $project_id,
				'proposal_id'    => $proposal_id,
				'milestone_no'   => $no,
				'entry_type'     => 'escrow_release_rollback',
				'amount'         => (float) $amount,
				'balance_bucket' => 'pending',
				'note'           => 'rollback release debit',
				'meta'           => array( 'milestone_id' => $milestone_id ),
			)
		);
		wp_send_json_error( array( 'message' => $credit->get_error_message() ), 400 );
	}

	// Mark released.
	bina_milestone_update_status(
		$milestone_id,
		'released',
		array(
			'released_at' => current_time( 'mysql' ),
		)
	);

	wp_send_json_success( array( 'ok' => 1 ) );
}
add_action( 'wp_ajax_bina_approve_milestone', 'bina_ajax_approve_milestone' );

