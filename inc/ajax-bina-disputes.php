<?php
/**
 * AJAX: disputes create (customer/provider).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Notify all admins about dispute.
 *
 * @param int $dispute_id
 * @param int $project_id
 * @return void
 */
function bina_disputes_notify_admins_new( $dispute_id, $project_id ) {
	if ( ! function_exists( 'bina_notifications_insert' ) ) {
		return;
	}
	$admins = get_users( array( 'role' => 'administrator', 'fields' => array( 'ID' ) ) );
	if ( ! is_array( $admins ) || empty( $admins ) ) {
		return;
	}
	// Admin can see details from إدارة مشاريع العملاء -> النزاعات.
	$admin_url = admin_url( 'admin.php?page=bina-project-admin&tab=disputes&dispute_id=' . (int) $dispute_id );
	foreach ( $admins as $a ) {
		$aid = is_object( $a ) && isset( $a->ID ) ? (int) $a->ID : 0;
		if ( $aid < 1 ) {
			continue;
		}
		bina_notifications_insert(
			$aid,
			'dispute_created',
			(int) $project_id,
			0,
			__( 'نزاع جديد', 'bina' ),
			sprintf( __( 'تم إرسال شكوى جديدة. رقم النزاع: %d. %s', 'bina' ), (int) $dispute_id, $admin_url )
		);
	}
}

function bina_ajax_create_dispute() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}
	check_ajax_referer( 'bina_disputes', 'nonce' );

	$user = wp_get_current_user();
	if ( ! $user || ! ( function_exists( 'bina_user_is_customer' ) && function_exists( 'bina_user_is_service_provider' ) ) ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح.', 'bina' ) ), 403 );
	}

	$project_id = isset( $_POST['project_id'] ) ? absint( wp_unslash( $_POST['project_id'] ) ) : 0;
	$message    = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$message    = trim( $message );

	if ( $project_id < 1 ) {
		wp_send_json_error( array( 'message' => __( 'اختر مشروعًا.', 'bina' ) ), 400 );
	}
	if ( '' === $message ) {
		wp_send_json_error( array( 'message' => __( 'اكتب الشكوى.', 'bina' ) ), 400 );
	}

	$post = get_post( $project_id );
	if ( ! $post || 'bina_project' !== $post->post_type ) {
		wp_send_json_error( array( 'message' => __( 'المشروع غير موجود.', 'bina' ) ), 404 );
	}

	$created_by = '';
	$customer_id = 0;
	$provider_id = function_exists( 'bina_get_project_assigned_provider_id' ) ? (int) bina_get_project_assigned_provider_id( $project_id ) : 0;

	if ( bina_user_is_customer( $user ) ) {
		$created_by  = 'customer';
		$customer_id = (int) $user->ID;
		if ( (int) $post->post_author !== $customer_id ) {
			wp_send_json_error( array( 'message' => __( 'لا يمكنك تقديم شكوى على هذا المشروع.', 'bina' ) ), 403 );
		}
	} elseif ( bina_user_is_service_provider( $user ) ) {
		$created_by = 'provider';
		$provider_id_current = (int) $user->ID;
		if ( $provider_id_current < 1 || $provider_id_current !== $provider_id ) {
			wp_send_json_error( array( 'message' => __( 'لا يمكنك تقديم شكوى على هذا المشروع.', 'bina' ) ), 403 );
		}
		$customer_id = (int) $post->post_author;
	} else {
		wp_send_json_error( array( 'message' => __( 'غير مصرح.', 'bina' ) ), 403 );
	}

	if ( ! function_exists( 'bina_dispute_create' ) ) {
		wp_send_json_error( array( 'message' => __( 'نظام النزاعات غير جاهز.', 'bina' ) ), 500 );
	}

	$did = bina_dispute_create(
		$project_id,
		$customer_id,
		$provider_id,
		$created_by,
		$message,
		array(
			'project_title' => get_the_title( $project_id ),
		)
	);
	if ( is_wp_error( $did ) ) {
		wp_send_json_error( array( 'message' => $did->get_error_message() ), 400 );
	}

	bina_disputes_notify_admins_new( (int) $did, $project_id );

	wp_send_json_success(
		array(
			'dispute_id' => (int) $did,
			'status'     => 'open',
		)
	);
}
add_action( 'wp_ajax_bina_create_dispute', 'bina_ajax_create_dispute' );

