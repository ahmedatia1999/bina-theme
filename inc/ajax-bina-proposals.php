<?php
/**
 * AJAX: proposals (bids) for marketplace projects.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provider submits a proposal for a project.
 *
 * POST: project_id, price_total, duration_days, message, nonce
 */
function bina_ajax_submit_proposal() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_proposals', 'nonce' );

	$user_id = get_current_user_id();
	$u       = get_userdata( $user_id );
	if ( ! $u || ! bina_user_is_service_provider( $u ) ) {
		wp_send_json_error( array( 'message' => __( 'هذه العملية لمزود الخدمة فقط.', 'bina' ) ), 403 );
	}

	$project_id    = isset( $_POST['project_id'] ) ? absint( $_POST['project_id'] ) : 0;
	$price_total   = isset( $_POST['price_total'] ) ? (float) wp_unslash( $_POST['price_total'] ) : 0;
	$duration_days = isset( $_POST['duration_days'] ) ? absint( $_POST['duration_days'] ) : 0;
	$message       = isset( $_POST['message'] ) ? wp_unslash( $_POST['message'] ) : '';
	$plan_key      = isset( $_POST['plan_key'] ) ? sanitize_text_field( wp_unslash( $_POST['plan_key'] ) ) : 'pay_at_completion';
	$plan_meta     = isset( $_POST['plan_meta'] ) ? (string) wp_unslash( $_POST['plan_meta'] ) : '';
	if ( strlen( $plan_meta ) > 20000 ) {
		$plan_meta = substr( $plan_meta, 0, 20000 );
	}

	$plan_key_n = function_exists( 'bina_normalize_payment_plan_key' ) ? bina_normalize_payment_plan_key( $plan_key ) : $plan_key;
	if ( $plan_key_n !== 'pay_at_completion' ) {
		$decoded = json_decode( $plan_meta, true );
		if ( ! is_array( $decoded ) || empty( $decoded['items'] ) || ! is_array( $decoded['items'] ) ) {
			wp_send_json_error( array( 'message' => __( 'تفاصيل الدفعات مطلوبة لهذا النظام.', 'bina' ) ), 400 );
		}

		$expected_n = $plan_key_n === 'four_installments_equal' ? 4 : 11;
		if ( count( $decoded['items'] ) !== $expected_n ) {
			wp_send_json_error( array( 'message' => __( 'عدد الدفعات غير صحيح.', 'bina' ) ), 400 );
		}

		$sum = 0.0;
		foreach ( $decoded['items'] as $index => $it ) {
			if ( ! is_array( $it ) ) {
				wp_send_json_error( array( 'message' => __( 'بيانات الدفعات غير صالحة.', 'bina' ) ), 400 );
			}

			$title       = isset( $it['title'] ) ? sanitize_text_field( (string) $it['title'] ) : '';
			$amount      = isset( $it['amount'] ) ? round( (float) $it['amount'], 2 ) : -1;
			$description = isset( $it['description'] ) ? sanitize_textarea_field( (string) $it['description'] ) : '';
			if ( $title === '' ) {
				wp_send_json_error( array( 'message' => __( 'كل دفعة يجب أن تحتوي على عنوان.', 'bina' ) ), 400 );
			}
			if ( $amount < 0 ) {
				wp_send_json_error( array( 'message' => __( 'مبلغ الدفعة غير صحيح.', 'bina' ) ), 400 );
			}
			if ( $description === '' ) {
				wp_send_json_error( array( 'message' => __( 'اكتب وصفًا لكل دفعة.', 'bina' ) ), 400 );
			}

			$decoded['items'][ $index ] = array(
				'no'          => isset( $it['no'] ) ? absint( $it['no'] ) : ( $index + 1 ),
				'title'       => $title,
				'amount'      => $amount,
				'description' => $description,
			);
			$sum += $amount;
		}

		$sum = round( $sum, 2 );
		$pt  = round( (float) $price_total, 2 );
		if ( abs( $sum - $pt ) > 0.01 ) {
			wp_send_json_error( array( 'message' => __( 'إجمالي الدفعات يجب أن يساوي السعر الإجمالي.', 'bina' ) ), 400 );
		}

		$decoded['plan_key'] = $plan_key_n;
		$decoded['total']    = $pt;
		$plan_meta           = wp_json_encode( $decoded );
	}

	$existing = function_exists( 'bina_proposal_get_for_project_provider' ) ? bina_proposal_get_for_project_provider( $project_id, $user_id ) : null;
	$prop_id  = bina_proposal_upsert( $project_id, $user_id, $price_total, $duration_days, $message, $plan_key_n, $plan_meta );
	if ( is_wp_error( $prop_id ) ) {
		wp_send_json_error( array( 'message' => $prop_id->get_error_message() ), 400 );
	}
	$row = function_exists( 'bina_proposal_get_by_id' ) ? bina_proposal_get_by_id( (int) $prop_id ) : null;

	$post = get_post( $project_id );
	if ( empty( $existing ) && $post && $post->post_type === 'bina_project' ) {
		$recipient_id = (int) $post->post_author;
		if ( $recipient_id > 0 && $recipient_id !== $user_id ) {
			$sender_name = $u ? (string) $u->display_name : __( 'مزود خدمة', 'bina' );
			$title       = __( 'عرض جديد على مشروعك', 'bina' );
			$body_text   = sprintf(
				__( 'قام %1$s بتقديم عرض جديد على مشروعك: %2$s', 'bina' ),
				$sender_name,
				get_the_title( $project_id )
			);
			bina_notifications_insert( $recipient_id, 'proposal_new', $project_id, $user_id, $title, $body_text );
		}
	}

	wp_send_json_success(
		array(
			'proposal_id' => (int) $prop_id,
			'message'     => empty( $existing ) ? __( 'تم تقديم العرض بنجاح.', 'bina' ) : __( 'تم تحديث العرض بنجاح.', 'bina' ),
			'status'      => is_array( $row ) && isset( $row['status'] ) ? (string) $row['status'] : '',
			'plan_key'    => is_array( $row ) && isset( $row['plan_key'] ) ? (string) $row['plan_key'] : '',
			'was_created' => empty( $existing ),
		)
	);
}
add_action( 'wp_ajax_bina_submit_proposal', 'bina_ajax_submit_proposal' );

/**
 * Customer accepts a proposal.
 *
 * POST: proposal_id, nonce
 */
function bina_ajax_accept_proposal() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_proposals', 'nonce' );

	$user_id     = get_current_user_id();
	$proposal_id = isset( $_POST['proposal_id'] ) ? absint( $_POST['proposal_id'] ) : 0;
	if ( $proposal_id < 1 ) {
		wp_send_json_error( array( 'message' => __( 'بيانات غير صالحة.', 'bina' ) ), 400 );
	}

	$r = bina_proposal_accept( $proposal_id, $user_id );
	if ( is_wp_error( $r ) ) {
		wp_send_json_error( array( 'message' => $r->get_error_message() ), 400 );
	}

	$row = function_exists( 'bina_proposal_get_by_id' ) ? bina_proposal_get_by_id( $proposal_id ) : null;
	if ( is_array( $row ) ) {
		$provider_id = isset( $row['provider_id'] ) ? (int) $row['provider_id'] : 0;
		$project_id  = isset( $row['project_id'] ) ? (int) $row['project_id'] : 0;
		if ( $provider_id > 0 ) {
			$title = __( 'تم قبول عرضك', 'bina' );
			$body  = sprintf(
				__( 'تهانينا! تم قبول عرضك على المشروع: %s', 'bina' ),
				get_the_title( $project_id )
			);
			bina_notifications_insert( $provider_id, 'proposal_accepted', $project_id, $user_id, $title, $body );
		}
	}

	wp_send_json_success(
		array(
			'ok'      => 1,
			'message' => __( 'تم قبول العرض بنجاح.', 'bina' ),
			'status'  => is_array( $row ) && isset( $row['status'] ) ? (string) $row['status'] : '',
		)
	);
}
add_action( 'wp_ajax_bina_accept_proposal', 'bina_ajax_accept_proposal' );
