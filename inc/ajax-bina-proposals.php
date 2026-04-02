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
		wp_send_json_error( array( 'message' => __( 'ÙŠØ¬Ø¨ ØªØ³Ø¬ÙŠÙ„ Ø§Ù„Ø¯Ø®ÙˆÙ„.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_proposals', 'nonce' );

	$user_id = get_current_user_id();
	$u       = get_userdata( $user_id );
	if ( ! $u || ! bina_user_is_service_provider( $u ) ) {
		wp_send_json_error( array( 'message' => __( 'Ù‡Ø°Ù‡ Ø§Ù„Ø¹Ù…Ù„ÙŠØ© Ù„Ù…Ø²ÙˆØ¯ÙŠ Ø§Ù„Ø®Ø¯Ù…Ø© ÙÙ‚Ø·.', 'bina' ) ), 403 );
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
			wp_send_json_error( array( 'message' => __( 'ØªÙØ§ØµÙŠÙ„ Ø§Ù„Ø¯ÙØ¹Ø§Øª Ù…Ø·Ù„ÙˆØ¨Ø© Ù„Ù‡Ø°Ø§ Ø§Ù„Ù†Ø¸Ø§Ù….', 'bina' ) ), 400 );
		}

		$expected_n = $plan_key_n === 'four_installments_equal' ? 4 : 11;
		if ( count( $decoded['items'] ) !== $expected_n ) {
			wp_send_json_error( array( 'message' => __( 'Ø¹Ø¯Ø¯ Ø§Ù„Ø¯ÙØ¹Ø§Øª ØºÙŠØ± ØµØ­ÙŠØ­.', 'bina' ) ), 400 );
		}

		$sum = 0.0;
		foreach ( $decoded['items'] as $index => $it ) {
			if ( ! is_array( $it ) ) {
				wp_send_json_error( array( 'message' => __( 'Ø¨ÙŠØ§Ù†Ø§Øª Ø§Ù„Ø¯ÙØ¹Ø§Øª ØºÙŠØ± ØµØ§Ù„Ø­Ø©.', 'bina' ) ), 400 );
			}

			$title       = isset( $it['title'] ) ? sanitize_text_field( (string) $it['title'] ) : '';
			$amount      = isset( $it['amount'] ) ? round( (float) $it['amount'], 2 ) : -1;
			$description = isset( $it['description'] ) ? sanitize_textarea_field( (string) $it['description'] ) : '';
			if ( $title === '' ) {
				wp_send_json_error( array( 'message' => __( 'ÙƒÙ„ Ø¯ÙØ¹Ø© ÙŠØ¬Ø¨ Ø£Ù† ØªØ­ØªÙˆÙŠ Ø¹Ù„Ù‰ Ø¹Ù†ÙˆØ§Ù†.', 'bina' ) ), 400 );
			}
			if ( $amount < 0 ) {
				wp_send_json_error( array( 'message' => __( 'Ù…Ø¨Ù„Øº Ø§Ù„Ø¯ÙØ¹Ø© ØºÙŠØ± ØµØ­ÙŠØ­.', 'bina' ) ), 400 );
			}
			if ( $description === '' ) {
				wp_send_json_error( array( 'message' => __( 'Ø§ÙƒØªØ¨ ÙˆØµÙÙ‹Ø§ Ù„ÙƒÙ„ Ø¯ÙØ¹Ø©.', 'bina' ) ), 400 );
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
			wp_send_json_error( array( 'message' => __( 'Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ø¯ÙØ¹Ø§Øª ÙŠØ¬Ø¨ Ø£Ù† ÙŠØ³Ø§ÙˆÙŠ Ø§Ù„Ø³Ø¹Ø± Ø§Ù„Ø¥Ø¬Ù…Ø§Ù„ÙŠ.', 'bina' ) ), 400 );
		}

		$decoded['plan_key'] = $plan_key_n;
		$decoded['total']    = $pt;
		$plan_meta           = wp_json_encode( $decoded );
	}

	$prop_id = bina_proposal_upsert( $project_id, $user_id, $price_total, $duration_days, $message, $plan_key, $plan_meta );
	if ( is_wp_error( $prop_id ) ) {
		wp_send_json_error( array( 'message' => $prop_id->get_error_message() ), 400 );
	}
	$row = function_exists( 'bina_proposal_get_by_id' ) ? bina_proposal_get_by_id( (int) $prop_id ) : null;

	$post = get_post( $project_id );
	if ( $post && $post->post_type === 'bina_project' ) {
		$recipient_id = (int) $post->post_author;
		if ( $recipient_id > 0 && $recipient_id !== $user_id ) {
			$sender_name = $u ? (string) $u->display_name : __( 'Ù…Ø²ÙˆØ¯ Ø®Ø¯Ù…Ø©', 'bina' );
			$title       = __( 'Ø¹Ø±Ø¶ Ø¬Ø¯ÙŠØ¯ Ø¹Ù„Ù‰ Ù…Ø´Ø±ÙˆØ¹Ùƒ', 'bina' );
			$body_text   = sprintf(
				__( 'Ù‚Ø§Ù… %1$s Ø¨ØªÙ‚Ø¯ÙŠÙ… Ø¹Ø±Ø¶ Ø¬Ø¯ÙŠØ¯ Ø¹Ù„Ù‰ Ù…Ø´Ø±ÙˆØ¹Ùƒ: %2$s', 'bina' ),
				$sender_name,
				get_the_title( $project_id )
			);
			bina_notifications_insert( $recipient_id, 'proposal_new', $project_id, $user_id, $title, $body_text );
		}
	}

	wp_send_json_success(
		array(
			'proposal_id' => (int) $prop_id,
			'message'     => __( 'ØªÙ… ØªÙ‚Ø¯ÙŠÙ… Ø§Ù„Ø¹Ø±Ø¶ Ø¨Ù†Ø¬Ø§Ø­.', 'bina' ),
			'status'      => is_array( $row ) && isset( $row['status'] ) ? (string) $row['status'] : '',
			'plan_key'    => is_array( $row ) && isset( $row['plan_key'] ) ? (string) $row['plan_key'] : '',
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
		wp_send_json_error( array( 'message' => __( 'ÙŠØ¬Ø¨ ØªØ³Ø¬ÙŠÙ„ Ø§Ù„Ø¯Ø®ÙˆÙ„.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_proposals', 'nonce' );

	$user_id     = get_current_user_id();
	$proposal_id = isset( $_POST['proposal_id'] ) ? absint( $_POST['proposal_id'] ) : 0;
	if ( $proposal_id < 1 ) {
		wp_send_json_error( array( 'message' => __( 'Ø¨ÙŠØ§Ù†Ø§Øª ØºÙŠØ± ØµØ§Ù„Ø­Ø©.', 'bina' ) ), 400 );
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
			$title = __( 'ØªÙ… Ù‚Ø¨ÙˆÙ„ Ø¹Ø±Ø¶Ùƒ', 'bina' );
			$body  = sprintf(
				__( 'ØªÙ‡Ø§Ù†ÙŠÙ†Ø§! ØªÙ… Ù‚Ø¨ÙˆÙ„ Ø¹Ø±Ø¶Ùƒ Ø¹Ù„Ù‰ Ø§Ù„Ù…Ø´Ø±ÙˆØ¹: %s', 'bina' ),
				get_the_title( $project_id )
			);
			bina_notifications_insert( $provider_id, 'proposal_accepted', $project_id, $user_id, $title, $body );
		}
	}

	wp_send_json_success(
		array(
			'ok'      => 1,
			'message' => __( 'ØªÙ… Ù‚Ø¨ÙˆÙ„ Ø§Ù„Ø¹Ø±Ø¶ Ø¨Ù†Ø¬Ø§Ø­.', 'bina' ),
			'status'  => is_array( $row ) && isset( $row['status'] ) ? (string) $row['status'] : '',
		)
	);
}
add_action( 'wp_ajax_bina_accept_proposal', 'bina_ajax_accept_proposal' );
