<?php
/**
 * AJAX: customer profile save.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_ajax_save_customer_profile() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_customer_profile', 'nonce' );

	$user_id = get_current_user_id();
	$u       = get_userdata( $user_id );
	if ( ! $u || ! bina_user_is_customer( $u ) ) {
		wp_send_json_error( array( 'message' => __( 'هذه العملية للعملاء فقط.', 'bina' ) ), 403 );
	}

	$display_name = isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';

	$display_name = trim( $display_name );
	if ( '' === $display_name ) {
		wp_send_json_error( array( 'message' => __( 'اكتب الاسم.', 'bina' ) ), 400 );
	}

	$digits = preg_replace( '/\D+/', '', $phone );
	if ( '' !== trim( $phone ) && strlen( $digits ) < 9 ) {
		wp_send_json_error( array( 'message' => __( 'رقم الجوال غير صحيح.', 'bina' ) ), 400 );
	}

	$upd = wp_update_user(
		array(
			'ID'           => $user_id,
			'display_name' => $display_name,
		)
	);
	if ( is_wp_error( $upd ) ) {
		wp_send_json_error( array( 'message' => $upd->get_error_message() ), 400 );
	}

	if ( isset( $_POST['phone'] ) ) {
		update_user_meta( $user_id, 'bina_phone', $digits );
	}

	wp_send_json_success( array( 'ok' => 1 ) );
}
add_action( 'wp_ajax_bina_save_customer_profile', 'bina_ajax_save_customer_profile' );

