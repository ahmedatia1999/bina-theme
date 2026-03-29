<?php
/**
 * Project-scoped messages (helpers).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether user may read/write messages on this project.
 *
 * @param int $user_id    User ID.
 * @param int $project_id bina_project post ID.
 * @return bool
 */
function bina_user_can_access_project_messages( $user_id, $project_id ) {
	$user_id    = (int) $user_id;
	$project_id = (int) $project_id;
	if ( $user_id < 1 || $project_id < 1 ) {
		return false;
	}
	$post = get_post( $project_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		return false;
	}
	if ( (int) $post->post_author === $user_id ) {
		return true;
	}
	$assigned = bina_get_project_assigned_provider_id( $project_id );
	if ( $assigned > 0 && $assigned === $user_id ) {
		return true;
	}
	if ( user_can( $user_id, 'manage_options' ) ) {
		return true;
	}
	if ( user_can( $user_id, 'edit_post', $project_id ) && user_can( $user_id, 'edit_others_bina_projects' ) ) {
		return true;
	}
	return false;
}

/**
 * Project IDs for inbox (customer: authored; provider: assigned).
 *
 * @param int $user_id User ID.
 * @return int[]
 */
function bina_get_project_ids_for_messages_inbox( $user_id ) {
	$user_id = (int) $user_id;
	if ( $user_id < 1 ) {
		return array();
	}
	$user = get_userdata( $user_id );
	if ( ! $user ) {
		return array();
	}
	$statuses = array( 'publish', 'pending', 'draft' );
	if ( bina_user_is_customer( $user ) ) {
		$q = new WP_Query(
			array(
				'post_type'              => 'bina_project',
				'author'                 => $user_id,
				'post_status'            => $statuses,
				'posts_per_page'         => 100,
				'fields'                 => 'ids',
				'orderby'                => 'modified',
				'order'                  => 'DESC',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
			)
		);
		return array_map( 'intval', $q->posts );
	}
	if ( bina_user_is_service_provider( $user ) ) {
		$q = new WP_Query(
			array(
				'post_type'              => 'bina_project',
				'post_status'            => $statuses,
				'posts_per_page'         => 100,
				'fields'                 => 'ids',
				'orderby'                => 'modified',
				'order'                  => 'DESC',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'meta_query'             => array(
					array(
						'key'     => '_bina_assigned_provider_id',
						'value'   => $user_id,
						'compare' => '=',
						'type'    => 'NUMERIC',
					),
				),
			)
		);
		return array_map( 'intval', $q->posts );
	}
	return array();
}

/**
 * @param int $project_id Project ID.
 * @param int $since_id   Only rows with id > this (0 = all).
 * @return array<int,array<string,mixed>>
 */
function bina_messages_fetch_for_project( $project_id, $since_id = 0 ) {
	global $wpdb;
	$table      = bina_messages_db_table_name();
	$project_id = (int) $project_id;
	$since_id   = (int) $since_id;
	if ( $project_id < 1 ) {
		return array();
	}
	if ( $since_id > 0 ) {
		$sql = $wpdb->prepare(
			"SELECT id, project_id, sender_id, body, created_at FROM {$table} WHERE project_id = %d AND id > %d ORDER BY id ASC LIMIT 500",
			$project_id,
			$since_id
		);
	} else {
		$sql = $wpdb->prepare(
			"SELECT id, project_id, sender_id, body, created_at FROM {$table} WHERE project_id = %d ORDER BY id ASC LIMIT 500",
			$project_id
		);
	}
	$rows = $wpdb->get_results( $sql, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	return is_array( $rows ) ? $rows : array();
}

/**
 * @param int    $project_id Project ID.
 * @param int    $sender_id  Sender user ID.
 * @param string $body       Message body.
 * @return array<string,mixed>|WP_Error
 */
function bina_messages_insert( $project_id, $sender_id, $body ) {
	global $wpdb;
	$table      = bina_messages_db_table_name();
	$project_id = (int) $project_id;
	$sender_id  = (int) $sender_id;
	$body       = sanitize_textarea_field( (string) $body );
	if ( $body === '' ) {
		return new WP_Error( 'bina_msg_empty', __( 'الرسالة فارغة.', 'bina' ) );
	}
	if ( strlen( $body ) > 8000 ) {
		$body = substr( $body, 0, 8000 );
	}
	$now = current_time( 'mysql' );
	$r   = $wpdb->insert(
		$table,
		array(
			'project_id' => $project_id,
			'sender_id'  => $sender_id,
			'body'       => $body,
			'created_at' => $now,
		),
		array( '%d', '%d', '%s', '%s' )
	);
	if ( ! $r ) {
		return new WP_Error( 'bina_msg_db', __( 'تعذر حفظ الرسالة.', 'bina' ) );
	}
	$id = (int) $wpdb->insert_id;
	return array(
		'id'         => $id,
		'project_id' => $project_id,
		'sender_id'  => $sender_id,
		'body'       => $body,
		'created_at' => $now,
	);
}
