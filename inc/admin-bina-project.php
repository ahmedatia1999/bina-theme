<?php
/**
 * Admin: custom project detail view for bina_project CPT.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Save assigned service provider from project detail screen.
 *
 * @return void
 */
function bina_admin_save_assigned_provider_from_detail() {
	if ( empty( $_POST['bina_save_assigned_provider'] ) || empty( $_POST['post_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	$post_id = absint( $_POST['post_id'] );
	if ( $post_id < 1 ) {
		return;
	}
	if ( ! isset( $_POST['bina_assigned_provider_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bina_assigned_provider_nonce'] ) ), 'bina_assigned_provider_' . $post_id ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$uid = isset( $_POST['bina_assigned_provider_id'] ) ? absint( $_POST['bina_assigned_provider_id'] ) : 0;
	if ( $uid > 0 ) {
		$u = get_userdata( $uid );
		if ( ! $u || ! bina_user_is_service_provider( $u ) ) {
			$uid = 0;
		}
	}
	bina_set_project_assigned_provider_id( $post_id, $uid );
	wp_safe_redirect( admin_url( 'admin.php?page=bina-project-detail&post_id=' . $post_id . '&assigned_saved=1' ) );
	exit;
}
add_action( 'admin_init', 'bina_admin_save_assigned_provider_from_detail' );

/**
 * Save project status from admin detail screen.
 *
 * @return void
 */
function bina_admin_save_project_status_from_detail() {
	if ( empty( $_POST['bina_save_project_status'] ) || empty( $_POST['post_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	$post_id = absint( $_POST['post_id'] );
	if ( $post_id < 1 ) {
		return;
	}
	$nonce_key = 'bina_project_status_nonce_' . $post_id;
	if ( ! isset( $_POST[ $nonce_key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_key ] ) ), 'bina_project_status_' . $post_id ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$new_status = isset( $_POST['bina_project_status'] ) ? sanitize_text_field( wp_unslash( $_POST['bina_project_status'] ) ) : '';
	$allowed    = array_keys( bina_get_project_status_labels() );
	if ( ! in_array( $new_status, $allowed, true ) ) {
		return;
	}

	update_post_meta( $post_id, '_bina_project_status', $new_status );
	wp_safe_redirect( admin_url( 'admin.php?page=bina-project-detail&post_id=' . $post_id . '&status_saved=1' ) );
	exit;
}
add_action( 'admin_init', 'bina_admin_save_project_status_from_detail' );

/**
 * Hidden submenu page — opened when editing a project (see redirect).
 *
 * @return void
 */
function bina_register_admin_project_detail_page() {
	$pto = get_post_type_object( 'bina_project' );
	$cap = ( $pto && isset( $pto->cap->edit_posts ) ) ? $pto->cap->edit_posts : 'edit_posts';
	add_submenu_page(
		null,
		__( 'تفاصيل المشروع', 'bina' ),
		'',
		$cap,
		'bina-project-detail',
		'bina_render_admin_project_detail_page'
	);
}
add_action( 'admin_menu', 'bina_register_admin_project_detail_page' );

/**
 * Redirect default block/classic editor to the custom summary screen.
 *
 * @return void
 */
function bina_redirect_bina_project_edit_to_summary() {
	if ( ! is_admin() ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only routing.
	if ( empty( $_GET['post'] ) || empty( $_GET['action'] ) || $_GET['action'] !== 'edit' ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! empty( $_GET['bina_raw_editor'] ) ) {
		return;
	}
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	if ( $post_id < 1 ) {
		return;
	}
	$post = get_post( $post_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	wp_safe_redirect( admin_url( 'admin.php?page=bina-project-detail&post_id=' . $post_id ) );
	exit;
}
add_action( 'load-post.php', 'bina_redirect_bina_project_edit_to_summary' );

/**
 * Enqueue minimal layout styles on the project detail admin page.
 *
 * @param string $hook_suffix Current admin page hook.
 * @return void
 */
function bina_admin_project_detail_assets( $hook_suffix ) {
	if ( $hook_suffix !== 'admin_page_bina-project-detail' ) {
		return;
	}
	wp_enqueue_style(
		'bina-admin-project-detail',
		get_template_directory_uri() . '/assets/css/admin-bina-project.css',
		array(),
		file_exists( get_template_directory() . '/assets/css/admin-bina-project.css' )
			? filemtime( get_template_directory() . '/assets/css/admin-bina-project.css' )
			: null
	);
}
add_action( 'admin_enqueue_scripts', 'bina_admin_project_detail_assets' );

/**
 * Render admin project detail.
 *
 * @return void
 */
function bina_render_admin_project_detail_page() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$post_id = isset( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
	if ( $post_id < 1 ) {
		wp_die( esc_html__( 'معرّف غير صالح.', 'bina' ) );
	}
	$post = get_post( $post_id );
	if ( ! $post || $post->post_type !== 'bina_project' ) {
		wp_die( esc_html__( 'المشروع غير موجود.', 'bina' ) );
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( esc_html__( 'غير مصرح.', 'bina' ) );
	}

	$extra_raw = get_post_meta( $post_id, '_bina_extra', true );
	$extra     = bina_project_extra_from_meta( is_string( $extra_raw ) ? $extra_raw : '' );
	$category  = (string) get_post_meta( $post_id, '_bina_category', true );
	$reminder  = (string) get_post_meta( $post_id, '_bina_reminder', true );
	$city      = (string) get_post_meta( $post_id, '_bina_city', true );
	$status    = (string) get_post_meta( $post_id, '_bina_project_status', true );
	$labels    = bina_get_project_status_labels();
	$status_l  = isset( $labels[ $status ] ) ? $labels[ $status ] : $status;

	$author      = get_userdata( (int) $post->post_author );
	$author_name = $author ? $author->display_name : '';
	$author_mail = $author ? $author->user_email : '';

	$plans_ids = bina_get_project_attachment_ids( $post_id, 'plans' );
	$photo_ids = bina_get_project_attachment_ids( $post_id, 'site_photos' );

	$assigned_provider_id = bina_get_project_assigned_provider_id( $post_id );
	$service_providers    = get_users(
		array(
			'role'    => 'service_provider',
			'number'  => 400,
			'orderby' => 'display_name',
			'order'   => 'ASC',
		)
	);

	$raw_editor = add_query_arg(
		array(
			'post'             => $post_id,
			'action'           => 'edit',
			'bina_raw_editor'  => '1',
		),
		admin_url( 'post.php' )
	);

	echo '<div class="wrap bina-admin-project-wrap">';
	echo '<h1 class="wp-heading-inline">' . esc_html( get_the_title( $post ) ) . '</h1>';
	echo ' <a href="' . esc_url( $raw_editor ) . '" class="page-title-action">' . esc_html__( 'المحرر الافتراضي', 'bina' ) . '</a>';
	echo '<hr class="wp-header-end">';

	if ( isset( $_GET['assigned_saved'] ) && (string) $_GET['assigned_saved'] === '1' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم حفظ مقدم الخدمة المعيّن.', 'bina' ) . '</p></div>';
	}

	if ( isset( $_GET['status_saved'] ) && (string) $_GET['status_saved'] === '1' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم تحديث حالة المشروع.', 'bina' ) . '</p></div>';
	}

	echo '<div class="bina-admin-project-grid">';

	echo '<div class="bina-admin-card">';
	echo '<h2>' . esc_html__( 'تعيين مقدم خدمة (المحادثات)', 'bina' ) . '</h2>';
	echo '<p class="description">' . esc_html__( 'يستطيع هذا المستخدم رؤية المشروع والرد في صفحة محادثات مقدم الخدمة.', 'bina' ) . '</p>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin.php' ) ) . '">';
	echo '<input type="hidden" name="page" value="bina-project-detail" />';
	echo '<input type="hidden" name="post_id" value="' . esc_attr( (string) $post_id ) . '" />';
	wp_nonce_field( 'bina_assigned_provider_' . $post_id, 'bina_assigned_provider_nonce' );
	echo '<p><label for="bina_assigned_provider_id">' . esc_html__( 'مستخدم مقدم الخدمة', 'bina' ) . '</label></p>';
	echo '<select name="bina_assigned_provider_id" id="bina_assigned_provider_id" class="regular-text" style="max-width:100%;">';
	echo '<option value="0">' . esc_html__( '— بدون —', 'bina' ) . '</option>';
	foreach ( $service_providers as $sp_user ) {
		echo '<option value="' . esc_attr( (string) $sp_user->ID ) . '"' . selected( $assigned_provider_id, $sp_user->ID, false ) . '>' . esc_html( $sp_user->display_name . ' (' . $sp_user->user_email . ')' ) . '</option>';
	}
	echo '</select>';
	echo '<p class="submit"><button type="submit" name="bina_save_assigned_provider" value="1" class="button button-primary">' . esc_html__( 'حفظ التعيين', 'bina' ) . '</button></p>';
	echo '</form>';
	echo '</div>';

	echo '<div class="bina-admin-card">';
	echo '<h2>' . esc_html__( 'العميل', 'bina' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th>' . esc_html__( 'الاسم', 'bina' ) . '</th><td>' . esc_html( $author_name ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'البريد', 'bina' ) . '</th><td><a href="mailto:' . esc_attr( $author_mail ) . '">' . esc_html( $author_mail ) . '</a></td></tr>';
	echo '</tbody></table>';
	echo '</div>';

	echo '<div class="bina-admin-card">';
	echo '<h2>' . esc_html__( 'حالة المشروع', 'bina' ) . '</h2>';
	echo '<p><span class="bina-admin-badge">' . esc_html( $status_l ) . '</span></p>';
	echo '<p class="description">' . esc_html__( 'غيّر الحالة مباشرة من هنا.', 'bina' ) . '</p>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin.php' ) ) . '">';
	echo '<input type="hidden" name="page" value="bina-project-detail" />';
	echo '<input type="hidden" name="post_id" value="' . esc_attr( (string) $post_id ) . '" />';
	echo '<input type="hidden" name="bina_save_project_status" value="1" />';

	$nonce_key = 'bina_project_status_nonce_' . $post_id;
	wp_nonce_field( 'bina_project_status_' . $post_id, $nonce_key );

	echo '<p><label for="bina_project_status_select">' . esc_html__( 'الحالة', 'bina' ) . '</label></p>';
	echo '<select name="bina_project_status" id="bina_project_status_select" class="regular-text" style="max-width:100%;">';
	foreach ( $labels as $k => $lab ) {
		echo '<option value="' . esc_attr( (string) $k ) . '"' . selected( $status, $k, false ) . '>' . esc_html( $lab ) . '</option>';
	}
	echo '</select>';
	echo '<p class="submit"><button type="submit" class="button button-primary">' . esc_html__( 'حفظ الحالة', 'bina' ) . '</button></p>';
	echo '</form>';
	echo '</div>';

	echo '<div class="bina-admin-card bina-admin-card--wide">';
	echo '<h2>' . esc_html__( 'وصف المشروع', 'bina' ) . '</h2>';
	echo '<div class="bina-admin-prose">' . wp_kses_post( wpautop( $post->post_content ) ) . '</div>';
	echo '</div>';

	echo '<div class="bina-admin-card bina-admin-card--wide">';
	echo '<h2>' . esc_html__( 'البيانات الإضافية', 'bina' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th>' . esc_html__( 'الفئة', 'bina' ) . '</th><td>' . esc_html( $category ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'موعد التذكير', 'bina' ) . '</th><td>' . esc_html( $reminder ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'المدينة', 'bina' ) . '</th><td>' . esc_html( $city ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'الحي', 'bina' ) . '</th><td>' . esc_html( isset( $extra['neighborhood'] ) ? (string) $extra['neighborhood'] : '' ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'الشارع', 'bina' ) . '</th><td>' . esc_html( isset( $extra['street'] ) ? (string) $extra['street'] : '' ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'التوقيت المتوقع للبدء', 'bina' ) . '</th><td>' . esc_html( isset( $extra['start_timing'] ) ? (string) $extra['start_timing'] : '' ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'مخططات هندسية', 'bina' ) . '</th><td>' . esc_html( isset( $extra['has_plans'] ) ? (string) $extra['has_plans'] : '—' ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'صور للموقع', 'bina' ) . '</th><td>' . esc_html( isset( $extra['has_photos'] ) ? (string) $extra['has_photos'] : '—' ) . '</td></tr>';
	echo '</tbody></table>';
	echo '</div>';

	echo '<div class="bina-admin-card bina-admin-card--wide">';
	echo '<h2>' . esc_html__( 'مرفقات المخططات', 'bina' ) . '</h2>';
	if ( empty( $plans_ids ) ) {
		echo '<p class="description">' . esc_html__( 'لا توجد مرفقات.', 'bina' ) . '</p>';
	} else {
		echo '<ul class="bina-admin-attachments">';
		foreach ( $plans_ids as $aid ) {
			$url  = wp_get_attachment_url( $aid );
			$mime = get_post_mime_type( $aid );
			$ttl  = get_the_title( $aid );
			if ( ! $url ) {
				continue;
			}
			echo '<li>';
			if ( $mime && strpos( $mime, 'image/' ) === 0 ) {
				echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">';
				echo wp_get_attachment_image( $aid, 'medium', false, array( 'class' => 'bina-admin-thumb' ) );
				echo '</a>';
			} else {
				echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html( $ttl ? $ttl : __( 'تحميل', 'bina' ) ) . '</a>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
	echo '</div>';

	echo '<div class="bina-admin-card bina-admin-card--wide">';
	echo '<h2>' . esc_html__( 'صور الموقع', 'bina' ) . '</h2>';
	if ( empty( $photo_ids ) ) {
		echo '<p class="description">' . esc_html__( 'لا توجد صور.', 'bina' ) . '</p>';
	} else {
		echo '<div class="bina-admin-photo-grid">';
		foreach ( $photo_ids as $aid ) {
			$url = wp_get_attachment_url( $aid );
			if ( ! $url ) {
				continue;
			}
			echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="bina-admin-photo-item">';
			echo wp_get_attachment_image( $aid, 'medium_large', false, array( 'class' => 'bina-admin-photo-img' ) );
			echo '</a>';
		}
		echo '</div>';
	}
	echo '</div>';

	echo '</div>';
	echo '</div>';
}
