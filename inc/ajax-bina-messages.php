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
		$su  = $sid > 0 ? get_userdata( $sid ) : null;
		$role = 'user';
		if ( $su ) {
			if ( bina_user_is_customer( $su ) ) {
				$role = 'customer';
			} elseif ( bina_user_is_service_provider( $su ) ) {
				$role = 'service_provider';
			} elseif ( user_can( $sid, 'manage_options' ) ) {
				$role = 'admin';
			}
		}
		$out[] = array(
			'id'         => (int) $row['id'],
			'sender_id'  => $sid,
			'sender_name' => $su ? (string) $su->display_name : '',
			'sender_role' => $role,
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

	// If a service provider is initiating chat on an unassigned project, auto-assign it.
	$assigned = bina_get_project_assigned_provider_id( $project_id );
	if ( $assigned < 1 ) {
		$u = get_userdata( $user_id );
		if ( $u && bina_user_is_service_provider( $u ) ) {
			bina_set_project_assigned_provider_id( $project_id, $user_id );
		}
	}

	$result = bina_messages_insert( $project_id, $user_id, $body );
	if ( is_wp_error( $result ) ) {
		wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
	}

	// Create notification for recipient.
	$post = get_post( $project_id );
	$su   = get_userdata( $user_id );
	if ( $post && $su ) {
		$recipient_id = 0;
		if ( bina_user_is_service_provider( $su ) ) {
			// Provider -> notify customer (project author).
			$recipient_id = (int) $post->post_author;
		} elseif ( bina_user_is_customer( $su ) ) {
			// Customer -> notify assigned provider.
			$recipient_id = bina_get_project_assigned_provider_id( $project_id );
		}

		if ( $recipient_id > 0 && $recipient_id !== (int) $user_id ) {
			$title = '';
			if ( bina_user_is_service_provider( $su ) ) {
				$title = __( 'رسالة جديدة من مزود الخدمة', 'bina' );
			} else {
				$title = __( 'رسالة جديدة من العميل', 'bina' );
			}
			bina_notifications_insert(
				$recipient_id,
				'message_new',
				$project_id,
				$user_id,
				$title,
				$body
			);
		}
	}

	$su   = get_userdata( $user_id );
	$role = 'user';
	if ( $su ) {
		if ( bina_user_is_customer( $su ) ) {
			$role = 'customer';
		} elseif ( bina_user_is_service_provider( $su ) ) {
			$role = 'service_provider';
		} elseif ( user_can( $user_id, 'manage_options' ) ) {
			$role = 'admin';
		}
	}

	wp_send_json_success(
		array(
			'message' => array(
				'id'         => (int) $result['id'],
				'sender_id'  => (int) $result['sender_id'],
				'sender_name' => $su ? (string) $su->display_name : '',
				'sender_role' => $role,
				'body'       => (string) $result['body'],
				'created_at' => (string) $result['created_at'],
				'is_mine'    => true,
			),
		)
	);
}
add_action( 'wp_ajax_bina_send_thread_message', 'bina_ajax_send_thread_message' );
