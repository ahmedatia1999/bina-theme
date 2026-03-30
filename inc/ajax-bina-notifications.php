<?php
/**
 * AJAX: user notifications.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bina_ajax_get_unread_notifications_count() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_notifications', 'nonce' );

	$user_id = get_current_user_id();
	$count   = bina_notifications_count_unread( $user_id );

	wp_send_json_success(
		array(
			'unread_count' => (int) $count,
		)
	);
}
add_action( 'wp_ajax_bina_get_unread_notifications_count', 'bina_ajax_get_unread_notifications_count' );

function bina_ajax_get_notifications_list() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_notifications', 'nonce' );

	$user_id    = get_current_user_id();
	$only_unread = isset( $_POST['only_unread'] ) ? (bool) (int) $_POST['only_unread'] : false; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$limit      = isset( $_POST['limit'] ) ? absint( $_POST['limit'] ) : 20;

	$list         = bina_notifications_fetch_list( $user_id, $limit, $only_unread );
	$unread_count = bina_notifications_count_unread( $user_id );

	// Decorate list with role/name for messages.
	foreach ( $list as &$row ) {
		$sender_id = isset( $row['sender_id'] ) ? (int) $row['sender_id'] : 0;
		$su         = $sender_id > 0 ? get_userdata( $sender_id ) : null;
		$role       = 'user';
		if ( $su ) {
			if ( bina_user_is_customer( $su ) ) {
				$role = 'customer';
			} elseif ( bina_user_is_service_provider( $su ) ) {
				$role = 'service_provider';
			} elseif ( user_can( $sender_id, 'manage_options' ) ) {
				$role = 'admin';
			}
		}
		$row['sender_name'] = $su ? (string) $su->display_name : '';
		$row['sender_role'] = $role;
	}
	unset( $row );

	wp_send_json_success(
		array(
			'unread_count'   => (int) $unread_count,
			'notifications'  => $list,
		)
	);
}
add_action( 'wp_ajax_bina_get_notifications_list', 'bina_ajax_get_notifications_list' );

function bina_ajax_mark_notification_read() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_notifications', 'nonce' );

	$user_id = get_current_user_id();
	$nid     = isset( $_POST['notification_id'] ) ? absint( $_POST['notification_id'] ) : 0;
	if ( $nid < 1 ) {
		wp_send_json_error( array( 'message' => __( 'معرّف غير صالح.', 'bina' ) ), 400 );
	}
	bina_notifications_mark_read( $user_id, $nid );
	wp_send_json_success( array( 'done' => true ) );
}
add_action( 'wp_ajax_bina_mark_notification_read', 'bina_ajax_mark_notification_read' );

function bina_ajax_mark_all_notifications_read() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_notifications', 'nonce' );

	$user_id = get_current_user_id();
	bina_notifications_mark_all_read( $user_id );
	wp_send_json_success( array( 'done' => true ) );
}
add_action( 'wp_ajax_bina_mark_all_notifications_read', 'bina_ajax_mark_all_notifications_read' );

