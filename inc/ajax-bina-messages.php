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
		$attachments = array();
		$meta_raw    = isset( $row['meta'] ) ? (string) $row['meta'] : '';
		if ( $meta_raw !== '' ) {
			$decoded = json_decode( $meta_raw, true );
			if ( is_array( $decoded ) && ! empty( $decoded['attachments'] ) && is_array( $decoded['attachments'] ) ) {
				foreach ( $decoded['attachments'] as $aid ) {
					$aid = absint( $aid );
					if ( $aid < 1 ) {
						continue;
					}
					$url = wp_get_attachment_url( $aid );
					if ( ! $url ) {
						continue;
					}
					$mime     = (string) get_post_mime_type( $aid );
					$is_image = $mime && strpos( $mime, 'image/' ) === 0;
					$thumb    = $is_image ? wp_get_attachment_image_url( $aid, 'thumbnail' ) : '';
					$view     = $is_image ? wp_get_attachment_image_url( $aid, 'medium' ) : '';
					$attachments[] = array(
						'id'       => $aid,
						'url'      => $url,
						'mime'     => $mime,
						'title'    => (string) get_the_title( $aid ),
						'is_image' => $is_image,
						'thumb'    => $thumb ? $thumb : '',
						'view'     => $view ? $view : '',
					);
				}
			}
		}
		$out[] = array(
			'id'         => (int) $row['id'],
			'sender_id'  => $sid,
			'sender_name' => $su ? (string) $su->display_name : '',
			'sender_role' => $role,
			'body'       => (string) $row['body'],
			'attachments' => $attachments,
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

	// Handle optional attachments.
	$attachment_ids = array();
	if ( ! empty( $_FILES['attachments'] ) && is_array( $_FILES['attachments'] ) && ! empty( $_FILES['attachments']['name'] ) && is_array( $_FILES['attachments']['name'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$max_bytes = min( 5 * 1024 * 1024, (int) wp_max_upload_size() );
		$max_files = 6;
		$count     = count( $_FILES['attachments']['name'] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
		for ( $i = 0; $i < $count && count( $attachment_ids ) < $max_files; $i++ ) {
			$err = isset( $_FILES['attachments']['error'][ $i ] ) ? (int) $_FILES['attachments']['error'][ $i ] : UPLOAD_ERR_NO_FILE; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( UPLOAD_ERR_OK !== $err ) {
				continue;
			}
			$size = isset( $_FILES['attachments']['size'][ $i ] ) ? (int) $_FILES['attachments']['size'][ $i ] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			if ( $size < 1 || $size > $max_bytes ) {
				continue;
			}

			$file_array = array(
				'name'     => $_FILES['attachments']['name'][ $i ], // phpcs:ignore WordPress.Security.NonceVerification.Missing
				'type'     => $_FILES['attachments']['type'][ $i ], // phpcs:ignore WordPress.Security.NonceVerification.Missing
				'tmp_name' => $_FILES['attachments']['tmp_name'][ $i ], // phpcs:ignore WordPress.Security.NonceVerification.Missing
				'error'    => $_FILES['attachments']['error'][ $i ], // phpcs:ignore WordPress.Security.NonceVerification.Missing
				'size'     => $_FILES['attachments']['size'][ $i ], // phpcs:ignore WordPress.Security.NonceVerification.Missing
			);

			$moved = wp_handle_upload( $file_array, array( 'test_form' => false ) );
			if ( isset( $moved['error'] ) || empty( $moved['file'] ) ) {
				continue;
			}
			$filetype = wp_check_filetype( basename( $moved['file'] ), null );
			if ( empty( $filetype['type'] ) ) {
				@unlink( $moved['file'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				continue;
			}
			$attachment = array(
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $moved['file'] ) ),
				'post_status'    => 'inherit',
			);
			$attach_id = wp_insert_attachment( $attachment, $moved['file'], $project_id );
			if ( is_wp_error( $attach_id ) || ! $attach_id ) {
				@unlink( $moved['file'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				continue;
			}
			$meta = wp_generate_attachment_metadata( $attach_id, $moved['file'] );
			wp_update_attachment_metadata( $attach_id, $meta );
			$attachment_ids[] = (int) $attach_id;
		}
	}

	$result = bina_messages_insert(
		$project_id,
		$user_id,
		$body,
		array(
			'attachments' => $attachment_ids,
		)
	);
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

	// Decorate attachments for client.
	$attachments_out = array();
	foreach ( $attachment_ids as $aid ) {
		$url = wp_get_attachment_url( $aid );
		if ( ! $url ) {
			continue;
		}
		$mime     = (string) get_post_mime_type( $aid );
		$is_image = $mime && strpos( $mime, 'image/' ) === 0;
		$thumb    = $is_image ? wp_get_attachment_image_url( $aid, 'thumbnail' ) : '';
		$view     = $is_image ? wp_get_attachment_image_url( $aid, 'medium' ) : '';
		$attachments_out[] = array(
			'id'       => (int) $aid,
			'url'      => $url,
			'mime'     => $mime,
			'title'    => (string) get_the_title( $aid ),
			'is_image' => $is_image,
			'thumb'    => $thumb ? $thumb : '',
			'view'     => $view ? $view : '',
		);
	}

	wp_send_json_success(
		array(
			'message' => array(
				'id'         => (int) $result['id'],
				'sender_id'  => (int) $result['sender_id'],
				'sender_name' => $su ? (string) $su->display_name : '',
				'sender_role' => $role,
				'body'       => (string) $result['body'],
				'attachments' => $attachments_out,
				'created_at' => (string) $result['created_at'],
				'is_mine'    => true,
			),
		)
	);
}
add_action( 'wp_ajax_bina_send_thread_message', 'bina_ajax_send_thread_message' );
