<?php
/**
 * AJAX: project thread messages.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return void
 */
function bina_ajax_get_thread_messages() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_project_messages', 'nonce' );

	$project_id = isset( $_POST['project_id'] ) ? absint( $_POST['project_id'] ) : 0;
	$since_id   = isset( $_POST['since_id'] ) ? absint( $_POST['since_id'] ) : 0;
	$user_id    = get_current_user_id();

	if ( $project_id < 1 || ! bina_user_can_access_project_messages( $user_id, $project_id ) ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح.', 'bina' ) ), 403 );
	}

	$rows = bina_messages_fetch_for_project( $project_id, $since_id );
	$out  = array();
	foreach ( $rows as $row ) {
		$sid = (int) $row['sender_id'];
		$out[] = array(
			'id'         => (int) $row['id'],
			'sender_id'  => $sid,
			'body'       => (string) $row['body'],
			'created_at' => (string) $row['created_at'],
			'is_mine'    => $sid === $user_id,
		);
	}

	wp_send_json_success( array( 'messages' => $out ) );
}
add_action( 'wp_ajax_bina_get_thread_messages', 'bina_ajax_get_thread_messages' );

/**
 * @return void
 */
function bina_ajax_send_thread_message() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_project_messages', 'nonce' );

	$project_id = isset( $_POST['project_id'] ) ? absint( $_POST['project_id'] ) : 0;
	$body       = isset( $_POST['body'] ) ? wp_unslash( $_POST['body'] ) : '';
	$user_id    = get_current_user_id();

	if ( $project_id < 1 || ! bina_user_can_access_project_messages( $user_id, $project_id ) ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح.', 'bina' ) ), 403 );
	}

	$result = bina_messages_insert( $project_id, $user_id, $body );
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
	}

	wp_send_json_success(
		array(
			'message' => array(
				'id'         => (int) $result['id'],
				'sender_id'  => (int) $result['sender_id'],
				'body'       => (string) $result['body'],
				'created_at' => (string) $result['created_at'],
				'is_mine'    => true,
			),
		)
	);
}
add_action( 'wp_ajax_bina_send_thread_message', 'bina_ajax_send_thread_message' );
