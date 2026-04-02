<?php
/**
 * AJAX: customer payment method preferences.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_ajax_save_customer_payment_method() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_customer_payments', 'nonce' );

	$user_id = get_current_user_id();
	$u       = get_userdata( $user_id );
	if ( ! $u || ! bina_user_is_customer( $u ) ) {
		wp_send_json_error( array( 'message' => __( 'هذه العملية للعملاء فقط.', 'bina' ) ), 403 );
	}

	$method = isset( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : '';
	$method = in_array( $method, array( 'bank', 'stc' ), true ) ? $method : '';

	if ( '' === $method ) {
		wp_send_json_error( array( 'message' => __( 'اختر طريقة دفع.', 'bina' ) ), 400 );
	}

	if ( 'bank' === $method ) {
		$bank_holder = isset( $_POST['bank_holder'] ) ? sanitize_text_field( wp_unslash( $_POST['bank_holder'] ) ) : '';
		$bank_name   = isset( $_POST['bank_name'] ) ? sanitize_text_field( wp_unslash( $_POST['bank_name'] ) ) : '';
		$bank_iban   = isset( $_POST['bank_iban'] ) ? preg_replace( '/\s+/', '', sanitize_text_field( wp_unslash( $_POST['bank_iban'] ) ) ) : '';

		if ( '' === trim( $bank_holder ) || '' === trim( $bank_name ) || '' === trim( $bank_iban ) ) {
			wp_send_json_error( array( 'message' => __( 'أكمل بيانات التحويل البنكي.', 'bina' ) ), 400 );
		}
		if ( preg_match( '/^SA/i', $bank_iban ) && ! preg_match( '/^SA\d{22}$/i', $bank_iban ) ) {
			wp_send_json_error( array( 'message' => __( 'رقم IBAN غير صحيح.', 'bina' ) ), 400 );
		}

		update_user_meta( $user_id, 'bina_customer_pay_method', 'bank' );
		update_user_meta( $user_id, 'bina_customer_pay_bank_holder', $bank_holder );
		update_user_meta( $user_id, 'bina_customer_pay_bank_name', $bank_name );
		update_user_meta( $user_id, 'bina_customer_pay_bank_iban', $bank_iban );
		delete_user_meta( $user_id, 'bina_customer_pay_stc_phone' );

		wp_send_json_success(
			array(
				'ok'    => 1,
				'saved' => array(
					'method'      => 'bank',
					'bank_holder' => $bank_holder,
					'bank_name'   => $bank_name,
					'bank_iban'   => $bank_iban,
					'stc_phone'   => '',
				),
			)
		);
	}

	if ( 'stc' === $method ) {
		$stc_phone = isset( $_POST['stc_phone'] ) ? preg_replace( '/\s+/', '', sanitize_text_field( wp_unslash( $_POST['stc_phone'] ) ) ) : '';
		$digits    = preg_replace( '/\D+/', '', $stc_phone );

		if ( '' === trim( $digits ) ) {
			wp_send_json_error( array( 'message' => __( 'أدخل رقم STC Pay.', 'bina' ) ), 400 );
		}
		if ( strlen( $digits ) < 9 ) {
			wp_send_json_error( array( 'message' => __( 'رقم STC Pay غير صحيح.', 'bina' ) ), 400 );
		}

		update_user_meta( $user_id, 'bina_customer_pay_method', 'stc' );
		update_user_meta( $user_id, 'bina_customer_pay_stc_phone', $digits );
		delete_user_meta( $user_id, 'bina_customer_pay_bank_holder' );
		delete_user_meta( $user_id, 'bina_customer_pay_bank_name' );
		delete_user_meta( $user_id, 'bina_customer_pay_bank_iban' );

		wp_send_json_success(
			array(
				'ok'    => 1,
				'saved' => array(
					'method'      => 'stc',
					'bank_holder' => '',
					'bank_name'   => '',
					'bank_iban'   => '',
					'stc_phone'   => $digits,
				),
			)
		);
	}
}
add_action( 'wp_ajax_bina_save_customer_payment_method', 'bina_ajax_save_customer_payment_method' );
