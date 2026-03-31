<?php
/**
 * AJAX: wallet actions for providers (payout methods, withdrawals).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_ajax_save_payout_methods() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_wallet', 'nonce' );

	$user_id = get_current_user_id();
	$u       = get_userdata( $user_id );
	if ( ! $u || ! bina_user_is_service_provider( $u ) ) {
		wp_send_json_error( array( 'message' => __( 'هذه العملية لمزودي الخدمة فقط.', 'bina' ) ), 403 );
	}

	$has_bank = isset( $_POST['bank_holder'] ) || isset( $_POST['bank_iban'] ) || isset( $_POST['bank_name'] );
	$has_stc  = isset( $_POST['stc_phone'] );

	$bank_holder = isset( $_POST['bank_holder'] ) ? sanitize_text_field( wp_unslash( $_POST['bank_holder'] ) ) : '';
	$bank_iban   = isset( $_POST['bank_iban'] ) ? preg_replace( '/\s+/', '', sanitize_text_field( wp_unslash( $_POST['bank_iban'] ) ) ) : '';
	$bank_name   = isset( $_POST['bank_name'] ) ? sanitize_text_field( wp_unslash( $_POST['bank_name'] ) ) : '';
	$stc_phone   = isset( $_POST['stc_phone'] ) ? preg_replace( '/\s+/', '', sanitize_text_field( wp_unslash( $_POST['stc_phone'] ) ) ) : '';

	if ( ! $has_bank && ! $has_stc ) {
		wp_send_json_error( array( 'message' => __( 'لا توجد بيانات للحفظ.', 'bina' ) ), 400 );
	}

	// Validate bank payload when it is being saved.
	if ( $has_bank ) {
		if ( '' === trim( $bank_holder ) || '' === trim( $bank_name ) || '' === trim( $bank_iban ) ) {
			wp_send_json_error( array( 'message' => __( 'أكمل بيانات البنك (صاحب الحساب/اسم البنك/IBAN).', 'bina' ) ), 400 );
		}
		// Basic IBAN sanity (SA + 22 digits) if user entered SA iban; otherwise allow as-is.
		if ( preg_match( '/^SA/i', $bank_iban ) && ! preg_match( '/^SA\d{22}$/i', $bank_iban ) ) {
			wp_send_json_error( array( 'message' => __( 'رقم IBAN غير صحيح.', 'bina' ) ), 400 );
		}
	}

	// Validate STC payload when it is being saved.
	if ( $has_stc ) {
		$digits = preg_replace( '/\D+/', '', $stc_phone );
		if ( '' === trim( $digits ) ) {
			wp_send_json_error( array( 'message' => __( 'أدخل رقم STC Pay.', 'bina' ) ), 400 );
		}
		if ( strlen( $digits ) < 9 ) {
			wp_send_json_error( array( 'message' => __( 'رقم STC Pay غير صحيح.', 'bina' ) ), 400 );
		}
		$stc_phone = $digits;
	}

	// Only update fields that were actually submitted to avoid overwriting saved data with empty values.
	if ( $has_bank && isset( $_POST['bank_holder'] ) ) {
		update_user_meta( $user_id, 'bina_payout_bank_holder', $bank_holder );
	}
	if ( $has_bank && isset( $_POST['bank_iban'] ) ) {
		update_user_meta( $user_id, 'bina_payout_bank_iban', $bank_iban );
	}
	if ( $has_bank && isset( $_POST['bank_name'] ) ) {
		update_user_meta( $user_id, 'bina_payout_bank_name', $bank_name );
	}
	if ( $has_stc && isset( $_POST['stc_phone'] ) ) {
		update_user_meta( $user_id, 'bina_payout_stc_phone', $stc_phone );
	}

	// Return confirmed saved values (avoid UI showing stale/empty after refresh).
	$saved = array(
		'bank_holder' => (string) get_user_meta( $user_id, 'bina_payout_bank_holder', true ),
		'bank_name'   => (string) get_user_meta( $user_id, 'bina_payout_bank_name', true ),
		'bank_iban'   => (string) get_user_meta( $user_id, 'bina_payout_bank_iban', true ),
		'stc_phone'   => (string) get_user_meta( $user_id, 'bina_payout_stc_phone', true ),
	);

	wp_send_json_success(
		array(
			'ok'    => 1,
			'saved' => $saved,
		)
	);
}
add_action( 'wp_ajax_bina_save_payout_methods', 'bina_ajax_save_payout_methods' );

function bina_ajax_create_withdraw_request() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_wallet', 'nonce' );

	$user_id = get_current_user_id();
	$u       = get_userdata( $user_id );
	if ( ! $u || ! bina_user_is_service_provider( $u ) ) {
		wp_send_json_error( array( 'message' => __( 'هذه العملية لمزودي الخدمة فقط.', 'bina' ) ), 403 );
	}

	$amount = isset( $_POST['amount'] ) ? (float) wp_unslash( $_POST['amount'] ) : 0;
	$method = isset( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : 'bank';

	$r = bina_withdraw_request_create( $user_id, $amount, $method );
	if ( is_wp_error( $r ) ) {
		wp_send_json_error( array( 'message' => $r->get_error_message() ), 400 );
	}

	wp_send_json_success(
		array(
			'withdraw_request_id' => (int) $r,
		)
	);
}
add_action( 'wp_ajax_bina_create_withdraw_request', 'bina_ajax_create_withdraw_request' );

