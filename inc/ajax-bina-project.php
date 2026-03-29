<?php
/**
 * AJAX handlers for bina_project (customer-facing).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Upload files from $_FILES[ $field_name ] (multiple). Returns new attachment IDs.
 *
 * @param int    $post_id    Parent project ID.
 * @param string $field_name e.g. bina_plans or bina_site_photos.
 * @param array  $allowed_ext File extensions without dot.
 * @return int[]
 */
function bina_project_process_upload_field( $post_id, $field_name, array $allowed_ext ) {
	$post_id = (int) $post_id;
	if ( $post_id < 1 || empty( $_FILES[ $field_name ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return array();
	}

	$files = $_FILES[ $field_name ]; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( empty( $files['name'] ) || ! is_array( $files['name'] ) ) {
		return array();
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$max_bytes = min( 5 * 1024 * 1024, (int) wp_max_upload_size() );
	$max_files = 15;
	$ids       = array();

	$count = count( $files['name'] );
	for ( $i = 0; $i < $count && count( $ids ) < $max_files; $i++ ) {
		if ( ! isset( $files['error'][ $i ] ) || UPLOAD_ERR_OK !== (int) $files['error'][ $i ] ) {
			continue;
		}
		if ( isset( $files['size'][ $i ] ) && (int) $files['size'][ $i ] > $max_bytes ) {
			continue;
		}

		$name = isset( $files['name'][ $i ] ) ? sanitize_file_name( $files['name'][ $i ] ) : '';
		$ext  = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );
		if ( $ext === '' || ! in_array( $ext, $allowed_ext, true ) ) {
			continue;
		}

		$file_array = array(
			'name'     => $files['name'][ $i ],
			'type'     => $files['type'][ $i ],
			'tmp_name' => $files['tmp_name'][ $i ],
			'error'    => $files['error'][ $i ],
			'size'     => $files['size'][ $i ],
		);

		$overrides = array( 'test_form' => false );
		$moved     = wp_handle_upload( $file_array, $overrides );
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
			'post_content'   => '',
			'post_status'    => 'inherit',
			'post_parent'    => $post_id,
		);

		$attach_id = wp_insert_attachment( $attachment, $moved['file'], $post_id );
		if ( is_wp_error( $attach_id ) || ! $attach_id ) {
			@unlink( $moved['file'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			continue;
		}

		$meta = wp_generate_attachment_metadata( $attach_id, $moved['file'] );
		wp_update_attachment_metadata( $attach_id, $meta );

		$ids[] = (int) $attach_id;
	}

	return $ids;
}

/**
 * Sanitize and validate project fields from POST.
 *
 * @return array<string,mixed>|WP_Error
 */
function bina_project_get_sanitized_fields_from_request() {
	$title        = isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
	$description  = isset( $_POST['description'] ) ? wp_kses_post( wp_unslash( $_POST['description'] ) ) : '';
	$category     = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';
	$reminder     = isset( $_POST['reminder'] ) ? sanitize_text_field( wp_unslash( $_POST['reminder'] ) ) : '';
	$city         = isset( $_POST['city'] ) ? sanitize_text_field( wp_unslash( $_POST['city'] ) ) : '';
	$neighborhood = isset( $_POST['neighborhood'] ) ? sanitize_text_field( wp_unslash( $_POST['neighborhood'] ) ) : '';
	$street       = isset( $_POST['street'] ) ? sanitize_text_field( wp_unslash( $_POST['street'] ) ) : '';
	$start_timing = isset( $_POST['start_timing'] ) ? sanitize_text_field( wp_unslash( $_POST['start_timing'] ) ) : '';
	$has_plans    = isset( $_POST['has_plans'] ) ? sanitize_text_field( wp_unslash( $_POST['has_plans'] ) ) : '';
	$has_photos   = isset( $_POST['has_photos'] ) ? sanitize_text_field( wp_unslash( $_POST['has_photos'] ) ) : '';

	if ( $title === '' ) {
		return new WP_Error( 'bina_fields', __( 'اسم المشروع مطلوب.', 'bina' ) );
	}
	if ( $description === '' ) {
		return new WP_Error( 'bina_fields', __( 'وصف المشروع مطلوب.', 'bina' ) );
	}
	if ( $category === '' ) {
		return new WP_Error( 'bina_fields', __( 'اختر فئة المشروع.', 'bina' ) );
	}

	$allowed_cats = bina_get_project_categories();
	if ( ! in_array( $category, $allowed_cats, true ) ) {
		return new WP_Error( 'bina_fields', __( 'فئة غير صالحة.', 'bina' ) );
	}

	$extra = array(
		'neighborhood' => $neighborhood,
		'street'       => $street,
		'start_timing' => $start_timing,
		'has_plans'    => $has_plans,
		'has_photos'   => $has_photos,
	);

	return array(
		'title'       => $title,
		'description' => $description,
		'category'    => $category,
		'reminder'    => $reminder,
		'city'        => $city,
		'extra'       => $extra,
	);
}

/**
 * @return void
 */
function bina_ajax_save_project() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}

	$user = wp_get_current_user();
	if ( ! bina_user_is_customer( $user ) ) {
		wp_send_json_error( array( 'message' => __( 'هذا الإجراء متاح للعملاء فقط.', 'bina' ) ), 403 );
	}

	check_ajax_referer( 'bina_project', 'nonce' );

	$fields = bina_project_get_sanitized_fields_from_request();
	if ( is_wp_error( $fields ) ) {
		wp_send_json_error( array( 'message' => $fields->get_error_message() ), 400 );
	}

	$extra = $fields['extra'];

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'bina_project',
			'post_status'  => 'publish',
			'post_title'   => $fields['title'],
			'post_content' => $fields['description'],
			'post_author'  => get_current_user_id(),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( array( 'message' => $post_id->get_error_message() ), 500 );
	}

	update_post_meta( $post_id, '_bina_category', $fields['category'] );
	update_post_meta( $post_id, '_bina_reminder', $fields['reminder'] );
	update_post_meta( $post_id, '_bina_city', $fields['city'] );
	update_post_meta( $post_id, '_bina_project_status', 'pending' );
	update_post_meta( $post_id, '_bina_extra', bina_project_extra_to_json( $extra ) );

	$new_plans  = bina_project_process_upload_field( $post_id, 'bina_plans', array( 'pdf', 'jpg', 'jpeg', 'png', 'webp' ) );
	$new_photos = bina_project_process_upload_field( $post_id, 'bina_site_photos', array( 'jpg', 'jpeg', 'png', 'webp', 'gif' ) );
	if ( ! empty( $new_plans ) ) {
		bina_set_project_attachment_ids( $post_id, array_merge( bina_get_project_attachment_ids( $post_id, 'plans' ), $new_plans ), 'plans' );
	}
	if ( ! empty( $new_photos ) ) {
		bina_set_project_attachment_ids( $post_id, array_merge( bina_get_project_attachment_ids( $post_id, 'site_photos' ), $new_photos ), 'site_photos' );
	}

	$redirect = function_exists( 'bina_get_customer_project_detail_url' )
		? bina_get_customer_project_detail_url( $post_id )
		: home_url( '/' );

	wp_send_json_success(
		array(
			'post_id'       => $post_id,
			'redirect_url' => $redirect,
		)
	);
}

add_action( 'wp_ajax_bina_save_project', 'bina_ajax_save_project' );

/**
 * Update an existing project (owner or admin).
 *
 * @return void
 */
function bina_ajax_update_project() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}

	$user = wp_get_current_user();
	if ( ! bina_user_is_customer( $user ) ) {
		wp_send_json_error( array( 'message' => __( 'هذا الإجراء متاح للعملاء فقط.', 'bina' ) ), 403 );
	}

	check_ajax_referer( 'bina_project', 'nonce' );

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	if ( $post_id < 1 ) {
		wp_send_json_error( array( 'message' => __( 'معرّف المشروع غير صالح.', 'bina' ) ), 400 );
	}

	$post = get_post( $post_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		wp_send_json_error( array( 'message' => __( 'المشروع غير موجود.', 'bina' ) ), 404 );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'لا يمكنك تعديل هذا المشروع.', 'bina' ) ), 403 );
	}

	$fields = bina_project_get_sanitized_fields_from_request();
	if ( is_wp_error( $fields ) ) {
		wp_send_json_error( array( 'message' => $fields->get_error_message() ), 400 );
	}

	$updated = wp_update_post(
		array(
			'ID'           => $post_id,
			'post_title'   => $fields['title'],
			'post_content' => $fields['description'],
		),
		true
	);

	if ( is_wp_error( $updated ) ) {
		wp_send_json_error( array( 'message' => $updated->get_error_message() ), 500 );
	}

	update_post_meta( $post_id, '_bina_category', $fields['category'] );
	update_post_meta( $post_id, '_bina_reminder', $fields['reminder'] );
	update_post_meta( $post_id, '_bina_city', $fields['city'] );
	update_post_meta( $post_id, '_bina_extra', bina_project_extra_to_json( $fields['extra'] ) );

	$new_plans  = bina_project_process_upload_field( $post_id, 'bina_plans', array( 'pdf', 'jpg', 'jpeg', 'png', 'webp' ) );
	$new_photos = bina_project_process_upload_field( $post_id, 'bina_site_photos', array( 'jpg', 'jpeg', 'png', 'webp', 'gif' ) );
	if ( ! empty( $new_plans ) ) {
		bina_set_project_attachment_ids( $post_id, array_merge( bina_get_project_attachment_ids( $post_id, 'plans' ), $new_plans ), 'plans' );
	}
	if ( ! empty( $new_photos ) ) {
		bina_set_project_attachment_ids( $post_id, array_merge( bina_get_project_attachment_ids( $post_id, 'site_photos' ), $new_photos ), 'site_photos' );
	}

	$redirect = function_exists( 'bina_get_customer_project_detail_url' )
		? bina_get_customer_project_detail_url( $post_id )
		: home_url( '/' );

	wp_send_json_success(
		array(
			'post_id'      => $post_id,
			'redirect_url' => $redirect,
		)
	);
}

add_action( 'wp_ajax_bina_update_project', 'bina_ajax_update_project' );

/**
 * @return void
 */
function bina_ajax_delete_project() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول.', 'bina' ) ), 401 );
	}

	$user = wp_get_current_user();
	if ( ! bina_user_is_customer( $user ) ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح.', 'bina' ) ), 403 );
	}

	check_ajax_referer( 'bina_project', 'nonce' );

	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	if ( $post_id < 1 ) {
		wp_send_json_error( array( 'message' => __( 'معرّف غير صالح.', 'bina' ) ), 400 );
	}

	$post = get_post( $post_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		wp_send_json_error( array( 'message' => __( 'المشروع غير موجود.', 'bina' ) ), 404 );
	}

	if ( ! current_user_can( 'delete_post', $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'لا يمكن حذف هذا المشروع.', 'bina' ) ), 403 );
	}

	$r = wp_trash_post( $post_id );
	if ( ! $r ) {
		wp_send_json_error( array( 'message' => __( 'تعذر الحذف.', 'bina' ) ), 500 );
	}

	wp_send_json_success( array( 'deleted' => true ) );
}

add_action( 'wp_ajax_bina_delete_project', 'bina_ajax_delete_project' );
