<?php
/**
 * Admin overview: Projects / Customers / Service Providers.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin action: trash a project from overview list.
 *
 * @return void
 */
function bina_admin_trash_project_from_overview() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- handled below.
	if ( empty( $_POST['bina_trash_project'] ) || empty( $_POST['post_id'] ) ) {
		return;
	}
	$post_id = absint( $_POST['post_id'] );
	if ( $post_id < 1 ) {
		return;
	}
	$nonce = isset( $_POST['bina_trash_project_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['bina_trash_project_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'bina_trash_project_' . $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'delete_post', $post_id ) ) {
		return;
	}
	wp_trash_post( $post_id );
	wp_safe_redirect( admin_url( 'admin.php?page=bina-project-admin&tab=projects&trashed=1' ) );
	exit;
}
add_action( 'admin_init', 'bina_admin_trash_project_from_overview' );

/**
 * Admin action: confirm milestone funding from payments tab.
 *
 * @return void
 */
function bina_admin_confirm_milestone_funding_from_overview() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- handled below.
	if ( empty( $_POST['bina_confirm_milestone_funding'] ) || empty( $_POST['milestone_id'] ) ) {
		return;
	}

	$milestone_id = absint( $_POST['milestone_id'] );
	if ( $milestone_id < 1 ) {
		return;
	}
	$nonce = isset( $_POST['bina_confirm_milestone_funding_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['bina_confirm_milestone_funding_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'bina_confirm_milestone_funding_' . $milestone_id ) ) {
		return;
	}

	$r = function_exists( 'bina_milestone_confirm_funding' )
		? bina_milestone_confirm_funding( $milestone_id, get_current_user_id() )
		: null;
	if ( is_wp_error( $r ) ) {
		wp_safe_redirect( admin_url( 'admin.php?page=bina-project-admin&tab=payments&funded=0' ) );
		exit;
	}
	wp_safe_redirect( admin_url( 'admin.php?page=bina-project-admin&tab=payments&funded=1' ) );
	exit;
}
add_action( 'admin_init', 'bina_admin_confirm_milestone_funding_from_overview' );

/**
 * Admin action: make funded milestone withdrawable (move pending -> available).
 *
 * @return void
 */
function bina_admin_make_milestone_withdrawable_from_overview() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- handled below.
	if ( empty( $_POST['bina_make_milestone_withdrawable'] ) || empty( $_POST['milestone_id'] ) ) {
		return;
	}

	$milestone_id = absint( $_POST['milestone_id'] );
	if ( $milestone_id < 1 ) {
		return;
	}
	$nonce = isset( $_POST['bina_make_milestone_withdrawable_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['bina_make_milestone_withdrawable_nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'bina_make_milestone_withdrawable_' . $milestone_id ) ) {
		return;
	}

	$r = function_exists( 'bina_milestone_release_to_available' )
		? bina_milestone_release_to_available( $milestone_id, get_current_user_id() )
		: new WP_Error( 'bina_ms_missing', __( 'ميزة إتاحة السحب غير متاحة.', 'bina' ) );
	if ( is_wp_error( $r ) ) {
		wp_safe_redirect( admin_url( 'admin.php?page=bina-project-admin&tab=payments&withdrawable=0' ) );
		exit;
	}

	wp_safe_redirect( admin_url( 'admin.php?page=bina-project-admin&tab=payments&withdrawable=1' ) );
	exit;
}
add_action( 'admin_init', 'bina_admin_make_milestone_withdrawable_from_overview' );

/**
 * Render admin overview page.
 *
 * @return void
 */
function bina_render_admin_project_overview_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'غير مصرح.', 'bina' ) );
	}

	$css_path = get_template_directory() . '/assets/css/admin-bina-project.css';
	if ( file_exists( $css_path ) ) {
		wp_enqueue_style(
			'bina-admin-project-detail',
			get_template_directory_uri() . '/assets/css/admin-bina-project.css',
			array(),
			filemtime( $css_path )
		);
	}

	$tab     = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'projects';
	$user_id = isset( $_GET['user_id'] ) ? absint( $_GET['user_id'] ) : 0;

	$st_labels = bina_get_project_status_labels();

	$project_detail_url = static function ( $pid ) {
		return admin_url( 'admin.php?page=bina-project-detail&post_id=' . absint( $pid ) );
	};
	$customer_view_url = static function ( $uid ) {
		return admin_url( 'admin.php?page=bina-project-admin&tab=customer&user_id=' . absint( $uid ) );
	};
	$provider_view_url = static function ( $uid ) {
		return admin_url( 'admin.php?page=bina-project-admin&tab=provider&user_id=' . absint( $uid ) );
	};

	echo '<div class="wrap bina-project-admin-wrap">';
	echo '<h1>' . esc_html__( 'إدارة مشاريع العملاء', 'bina' ) . '</h1>';

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['trashed'] ) && (string) $_GET['trashed'] === '1' ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم نقل المشروع إلى سلة المهملات.', 'bina' ) . '</p></div>';
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['funded'] ) && (string) $_GET['funded'] === '1' ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم تأكيد تمويل الدفعة.', 'bina' ) . '</p></div>';
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['withdrawable'] ) && (string) $_GET['withdrawable'] === '1' ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم إتاحة الدفعة للسحب لمزود الخدمة.', 'bina' ) . '</p></div>';
	}

	// Tabs.
	$tabs = array(
		'projects' => __( 'المشاريع', 'bina' ),
		'customers' => __( 'العملاء', 'bina' ),
		'providers' => __( 'مقدمو الخدمة', 'bina' ),
		'payments' => __( 'المدفوعات', 'bina' ),
		'messages' => __( 'رسائل المشروع', 'bina' ),
		'disputes' => __( 'النزاعات', 'bina' ),
	);

	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $tabs as $key => $label ) {
		$is_active = ( $tab === $key );
		$url       = admin_url( 'admin.php?page=bina-project-admin&tab=' . esc_attr( $key ) );
		echo '<a href="' . esc_url( $url ) . '" class="nav-tab ' . ( $is_active ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
	}
	echo '</h2>';

	// Detail pages.
	if ( $tab === 'customer' && $user_id > 0 ) {
		bina_render_admin_customer_projects( $user_id, $st_labels, $project_detail_url );
		echo '</div>';
		return;
	}
	if ( $tab === 'provider' && $user_id > 0 ) {
		bina_render_admin_provider_projects( $user_id, $st_labels, $project_detail_url );
		echo '</div>';
		return;
	}

	if ( $tab === 'projects' ) {
		bina_render_admin_projects_list( $st_labels, $project_detail_url, $customer_view_url, $provider_view_url );
		echo '</div>';
		return;
	}

	if ( $tab === 'customers' ) {
		bina_render_admin_customers_list( $customer_view_url );
		echo '</div>';
		return;
	}

	if ( $tab === 'providers' ) {
		bina_render_admin_providers_list( $provider_view_url );
		echo '</div>';
		return;
	}

	if ( $tab === 'payments' ) {
		bina_render_admin_payments_list( $project_detail_url );
		echo '</div>';
		return;
	}

	if ( $tab === 'messages' ) {
		bina_render_admin_project_messages_tab( $project_detail_url );
		echo '</div>';
		return;
	}
	if ( $tab === 'disputes' ) {
		bina_render_admin_disputes_tab();
		echo '</div>';
		return;
	}

	echo '<p>' . esc_html__( 'تبويب غير صالح.', 'bina' ) . '</p>';
	echo '</div>';
}

function bina_admin_disputes_handle_actions() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( empty( $_POST['bina_dispute_action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return;
	}
	$action = sanitize_text_field( wp_unslash( $_POST['bina_dispute_action'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$did    = isset( $_POST['dispute_id'] ) ? absint( wp_unslash( $_POST['dispute_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$nonce  = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

	if ( $did < 1 || ! wp_verify_nonce( $nonce, 'bina_dispute_action_' . $did ) ) {
		return;
	}
	if ( ! function_exists( 'bina_dispute_update_status' ) ) {
		return;
	}
	if ( $action === 'close' ) {
		bina_dispute_update_status( $did, 'closed' );
	} elseif ( $action === 'reopen' ) {
		bina_dispute_update_status( $did, 'open' );
	}
	wp_safe_redirect( admin_url( 'admin.php?page=bina-project-admin&tab=disputes&updated=1' ) );
	exit;
}
add_action( 'admin_init', 'bina_admin_disputes_handle_actions', 9 );

function bina_render_admin_disputes_tab() {
	if ( ! current_user_can( 'manage_options' ) ) {
		echo '<p>' . esc_html__( 'غير مصرح.', 'bina' ) . '</p>';
		return;
	}
	echo '<h2>' . esc_html__( 'النزاعات', 'bina' ) . '</h2>';

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['updated'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم التحديث.', 'bina' ) . '</p></div>';
	}

	$status = isset( $_GET['status'] ) ? sanitize_key( wp_unslash( $_GET['status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( ! in_array( $status, array( '', 'open', 'closed' ), true ) ) {
		$status = '';
	}
	$dispute_id = isset( $_GET['dispute_id'] ) ? absint( wp_unslash( $_GET['dispute_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	echo '<p class="description">' . esc_html__( 'قائمة الشكاوى المرسلة من العملاء أو مقدمي الخدمة على المشاريع.', 'bina' ) . '</p>';

	$base = admin_url( 'admin.php?page=bina-project-admin&tab=disputes' );
	echo '<p style="margin:12px 0;">';
	echo '<a class="button ' . ( $status === '' ? 'button-primary' : '' ) . '" href="' . esc_url( $base ) . '">' . esc_html__( 'الكل', 'bina' ) . '</a> ';
	echo '<a class="button ' . ( $status === 'open' ? 'button-primary' : '' ) . '" href="' . esc_url( add_query_arg( 'status', 'open', $base ) ) . '">' . esc_html__( 'مفتوح', 'bina' ) . '</a> ';
	echo '<a class="button ' . ( $status === 'closed' ? 'button-primary' : '' ) . '" href="' . esc_url( add_query_arg( 'status', 'closed', $base ) ) . '">' . esc_html__( 'مغلق', 'bina' ) . '</a> ';
	echo '</p>';

	if ( $dispute_id > 0 && function_exists( 'bina_dispute_get' ) ) {
		$d = bina_dispute_get( $dispute_id );
		if ( $d ) {
			$project_id = (int) $d['project_id'];
			$project    = get_post( $project_id );
			$customer   = get_userdata( (int) $d['customer_id'] );
			$provider   = (int) $d['provider_id'] ? get_userdata( (int) $d['provider_id'] ) : null;

			echo '<div class="postbox" style="max-width:900px;"><div class="postbox-header"><h3 class="hndle">';
			echo esc_html__( 'تفاصيل النزاع', 'bina' );
			echo '</h3></div><div class="inside">';

			echo '<p><strong>' . esc_html__( 'المشروع:', 'bina' ) . '</strong> ';
			echo $project ? esc_html( $project->post_title ) : '#' . esc_html( (string) $project_id );
			echo '</p>';
			echo '<p><strong>' . esc_html__( 'العميل:', 'bina' ) . '</strong> ' . esc_html( $customer ? $customer->display_name . ' (' . $customer->user_email . ')' : '-' ) . '</p>';
			echo '<p><strong>' . esc_html__( 'مقدم الخدمة:', 'bina' ) . '</strong> ' . esc_html( $provider ? $provider->display_name . ' (' . $provider->user_email . ')' : '-' ) . '</p>';
			echo '<p><strong>' . esc_html__( 'من:', 'bina' ) . '</strong> ' . esc_html( $d['created_by'] ) . '</p>';
			echo '<p><strong>' . esc_html__( 'الحالة:', 'bina' ) . '</strong> ' . esc_html( $d['status'] ) . '</p>';
			echo '<p><strong>' . esc_html__( 'التاريخ:', 'bina' ) . '</strong> ' . esc_html( $d['created_at'] ) . '</p>';
			echo '<hr />';
			echo '<div style="white-space:pre-wrap; border:1px solid #e5e7eb; background:#fff; padding:12px; border-radius:8px;">' . esc_html( $d['message'] ) . '</div>';

			echo '<p style="margin-top:14px;"><a class="button" href="' . esc_url( $base ) . '">' . esc_html__( 'رجوع للقائمة', 'bina' ) . '</a></p>';
			echo '</div></div>';
			return;
		}
	}

	$args = array( 'limit' => 100 );
	if ( $status !== '' ) {
		$args['status'] = $status;
	}
	$rows = function_exists( 'bina_disputes_fetch' ) ? bina_disputes_fetch( $args ) : array();

	echo '<table class="widefat striped" style="max-width:1200px;">';
	echo '<thead><tr>';
	echo '<th>' . esc_html__( '#', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'المشروع', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'العميل', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'مقدم الخدمة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'من', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'الحالة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'الرسالة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'إجراءات', 'bina' ) . '</th>';
	echo '</tr></thead><tbody>';

	if ( empty( $rows ) ) {
		echo '<tr><td colspan="8">' . esc_html__( 'لا توجد نزاعات.', 'bina' ) . '</td></tr>';
	} else {
		foreach ( $rows as $d ) {
			$project = get_post( (int) $d['project_id'] );
			$c       = get_userdata( (int) $d['customer_id'] );
			$p       = (int) $d['provider_id'] ? get_userdata( (int) $d['provider_id'] ) : null;
			$excerpt = mb_substr( (string) $d['message'], 0, 80 );

			$view = add_query_arg( 'dispute_id', (int) $d['id'], $base );

			echo '<tr>';
			echo '<td>' . (int) $d['id'] . '</td>';
			echo '<td>' . esc_html( $project ? $project->post_title : ( '#' . (int) $d['project_id'] ) ) . '</td>';
			echo '<td>' . esc_html( $c ? $c->display_name : '-' ) . '</td>';
			echo '<td>' . esc_html( $p ? $p->display_name : '-' ) . '</td>';
			echo '<td>' . esc_html( (string) $d['created_by'] ) . '</td>';
			echo '<td>' . esc_html( (string) $d['status'] ) . '</td>';
			echo '<td>' . esc_html( $excerpt ) . '</td>';
			echo '<td>';
			echo '<a class="button button-small" href="' . esc_url( $view ) . '">' . esc_html__( 'عرض', 'bina' ) . '</a> ';
			echo '<form method="post" style="display:inline-block; margin:0 0 0 6px;">';
			wp_nonce_field( 'bina_dispute_action_' . (int) $d['id'] );
			echo '<input type="hidden" name="dispute_id" value="' . esc_attr( (string) (int) $d['id'] ) . '" />';
			if ( (string) $d['status'] === 'open' ) {
				echo '<input type="hidden" name="bina_dispute_action" value="close" />';
				echo '<button type="submit" class="button button-small">' . esc_html__( 'إغلاق', 'bina' ) . '</button>';
			} else {
				echo '<input type="hidden" name="bina_dispute_action" value="reopen" />';
				echo '<button type="submit" class="button button-small">' . esc_html__( 'إعادة فتح', 'bina' ) . '</button>';
			}
			echo '</form>';
			echo '</td>';
			echo '</tr>';
		}
	}

	echo '</tbody></table>';
}

/**
 * Messages tab: view per-project thread messages (customer <-> provider).
 *
 * @param callable $project_detail_url
 * @return void
 */
function bina_render_admin_project_messages_tab( $project_detail_url ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		echo '<p>' . esc_html__( 'غير مصرح.', 'bina' ) . '</p>';
		return;
	}

	$project_id = isset( $_GET['project_id'] ) ? absint( $_GET['project_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	echo '<h2>' . esc_html__( 'رسائل المشروع', 'bina' ) . '</h2>';
	echo '<p class="description">' . esc_html__( 'عرض رسائل المحادثة بين العميل ومزود الخدمة لكل مشروع.', 'bina' ) . '</p>';

	if ( $project_id > 0 ) {
		$post = get_post( $project_id );
		if ( $post && $post->post_type === 'bina_project' ) {
			$customer_u = get_userdata( (int) $post->post_author );
			$provider_id = function_exists( 'bina_get_project_assigned_provider_id' ) ? (int) bina_get_project_assigned_provider_id( $project_id ) : 0;
			$provider_u  = $provider_id > 0 ? get_userdata( $provider_id ) : null;

			echo '<div class="bina-admin-card" style="max-width: 980px; margin: 12px 0 16px;">';
			echo '<h3 style="margin-top:0;">' . esc_html( get_the_title( $project_id ) ) . '</h3>';
			echo '<p class="description" style="margin: 6px 0 0;">' . esc_html__( 'العميل:', 'bina' ) . ' ' . esc_html( $customer_u ? $customer_u->display_name : '—' ) . ' — ' . esc_html__( 'مزود الخدمة:', 'bina' ) . ' ' . esc_html( $provider_u ? $provider_u->display_name : '—' ) . '</p>';
			echo '<p style="margin:10px 0 0;"><a class="button button-small" href="' . esc_url( $project_detail_url( $project_id ) ) . '">' . esc_html__( 'فتح صفحة المشروع (أدمن)', 'bina' ) . '</a></p>';
			echo '</div>';

			$rows = function_exists( 'bina_messages_fetch_for_project' ) ? bina_messages_fetch_for_project( $project_id, 0 ) : array();
			if ( empty( $rows ) ) {
				echo '<p>' . esc_html__( 'لا توجد رسائل لهذا المشروع.', 'bina' ) . '</p>';
			} else {
				echo '<div class="bina-admin-card" style="max-width: 980px;">';
				echo '<div style="max-height: 520px; overflow:auto; padding: 12px;">';
				foreach ( $rows as $m ) {
					$sender_id = isset( $m['sender_id'] ) ? (int) $m['sender_id'] : 0;
					$sender_u  = $sender_id > 0 ? get_userdata( $sender_id ) : null;
					$sender    = $sender_u ? (string) $sender_u->display_name : ( $sender_id ? ( 'User#' . $sender_id ) : '—' );
					$body      = isset( $m['body'] ) ? (string) $m['body'] : '';
					$created   = isset( $m['created_at'] ) ? (string) $m['created_at'] : '';
					echo '<div style="border:1px solid rgba(0,0,0,.08); border-radius:10px; padding:10px 12px; margin-bottom:10px; background:#fff;">';
					echo '<div style="display:flex; gap:10px; justify-content:space-between; align-items:flex-start;">';
					echo '<strong>' . esc_html( $sender ) . '</strong>';
					echo '<span style="color:#6b7280; font-size:12px;">' . esc_html( $created ) . '</span>';
					echo '</div>';
					echo '<div style="margin-top:6px; white-space:pre-wrap;">' . esc_html( $body ) . '</div>';
					echo '</div>';
				}
				echo '</div></div>';
			}
		} else {
			echo '<p>' . esc_html__( 'المشروع غير موجود.', 'bina' ) . '</p>';
		}

		echo '<p style="margin-top:14px;"><a class="button" href="' . esc_url( admin_url( 'admin.php?page=bina-project-admin&tab=messages' ) ) . '">' . esc_html__( 'رجوع لقائمة الرسائل', 'bina' ) . '</a></p>';
		return;
	}

	// List projects that have messages.
	global $wpdb;
	$table = function_exists( 'bina_messages_db_table_name' ) ? bina_messages_db_table_name() : '';
	if ( $table === '' ) {
		echo '<p>' . esc_html__( 'جدول الرسائل غير متاح.', 'bina' ) . '</p>';
		return;
	}

	$pids = $wpdb->get_col( "SELECT DISTINCT project_id FROM {$table} ORDER BY project_id DESC LIMIT 300" ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	$pids = is_array( $pids ) ? array_map( 'intval', $pids ) : array();
	if ( empty( $pids ) ) {
		echo '<p>' . esc_html__( 'لا توجد رسائل حتى الآن.', 'bina' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped">';
	echo '<thead><tr>';
	echo '<th>' . esc_html__( 'المشروع', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'العميل', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'مزود الخدمة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'إجراء', 'bina' ) . '</th>';
	echo '</tr></thead><tbody>';

	foreach ( $pids as $pid ) {
		$p = get_post( $pid );
		if ( ! $p || $p->post_type !== 'bina_project' ) {
			continue;
		}
		$customer_u = get_userdata( (int) $p->post_author );
		$provider_id = function_exists( 'bina_get_project_assigned_provider_id' ) ? (int) bina_get_project_assigned_provider_id( $pid ) : 0;
		$provider_u  = $provider_id > 0 ? get_userdata( $provider_id ) : null;

		$url = admin_url( 'admin.php?page=bina-project-admin&tab=messages&project_id=' . absint( $pid ) );

		echo '<tr>';
		echo '<td>' . esc_html( get_the_title( $pid ) ) . '</td>';
		echo '<td>' . esc_html( $customer_u ? $customer_u->display_name : '—' ) . '</td>';
		echo '<td>' . esc_html( $provider_u ? $provider_u->display_name : '—' ) . '</td>';
		echo '<td><a class="button button-small" href="' . esc_url( $url ) . '">' . esc_html__( 'عرض الرسائل', 'bina' ) . '</a></td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
}

/**
 * Payments tab: show milestones pending admin confirmation.
 *
 * @param callable $project_detail_url
 * @return void
 */
function bina_render_admin_payments_list( $project_detail_url ) {
	echo '<h2>' . esc_html__( 'طلبات تمويل الدفعات', 'bina' ) . '</h2>';
	echo '<p class="description">' . esc_html__( 'هذه القائمة تعرض الدفعات التي طلب العميل تمويلها وتحتاج تأكيد الأدمن.', 'bina' ) . '</p>';

	$rows = function_exists( 'bina_milestones_fetch_payment_requested' ) ? bina_milestones_fetch_payment_requested( 300 ) : array();
	if ( empty( $rows ) ) {
		echo '<p>' . esc_html__( 'لا توجد طلبات حالياً.', 'bina' ) . '</p>';
	} else {
		echo '<table class="widefat striped">';
		echo '<thead><tr>';
		echo '<th>' . esc_html__( 'المشروع', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'العميل', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'مقدم الخدمة', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'الدفعة', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'المبلغ', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'آخر تحديث', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'إجراء', 'bina' ) . '</th>';
		echo '</tr></thead><tbody>';

		foreach ( $rows as $r ) {
			$mid        = isset( $r['id'] ) ? (int) $r['id'] : 0;
			$project_id = isset( $r['project_id'] ) ? (int) $r['project_id'] : 0;
			$provider_id = isset( $r['provider_id'] ) ? (int) $r['provider_id'] : 0;
			$no         = isset( $r['milestone_no'] ) ? (int) $r['milestone_no'] : 0;
			$title      = isset( $r['title'] ) ? (string) $r['title'] : '';
			$amount     = isset( $r['amount'] ) ? (float) $r['amount'] : 0.0;
			$updated_at = isset( $r['updated_at'] ) ? (string) $r['updated_at'] : '';

			$p = $project_id > 0 ? get_post( $project_id ) : null;
			$customer_u = $p ? get_userdata( (int) $p->post_author ) : null;
			$provider_u = $provider_id > 0 ? get_userdata( $provider_id ) : null;

			$nonce = wp_create_nonce( 'bina_confirm_milestone_funding_' . $mid );

			echo '<tr>';
			echo '<td><a href="' . esc_url( $project_detail_url( $project_id ) ) . '">' . esc_html( $p ? get_the_title( $project_id ) : '—' ) . '</a></td>';
			echo '<td>' . esc_html( $customer_u ? $customer_u->display_name : '—' ) . '</td>';
			echo '<td>' . esc_html( $provider_u ? $provider_u->display_name : '—' ) . '</td>';
			echo '<td>' . esc_html( $title !== '' ? $title : sprintf( __( 'دفعة %d', 'bina' ), $no ) ) . '</td>';
			echo '<td>' . esc_html( number_format_i18n( $amount, 2 ) ) . ' ' . esc_html__( 'ر.س', 'bina' ) . '</td>';
			echo '<td>' . esc_html( $updated_at !== '' ? $updated_at : '—' ) . '</td>';
			echo '<td>';
			echo '<form method="post" action="' . esc_url( admin_url( 'admin.php?page=bina-project-admin&tab=payments' ) ) . '" style="display:inline-block; margin:0; padding:0;" onsubmit="return confirm(\'' . esc_js( __( 'تأكيد: تم استلام دفعة العميل وتأكيد تمويلها؟', 'bina' ) ) . '\');">';
			echo '<input type="hidden" name="milestone_id" value="' . esc_attr( (string) $mid ) . '" />';
			echo '<input type="hidden" name="bina_confirm_milestone_funding" value="1" />';
			echo '<input type="hidden" name="bina_confirm_milestone_funding_nonce" value="' . esc_attr( $nonce ) . '" />';
			echo '<button type="submit" class="button button-small button-primary">' . esc_html__( 'تأكيد التمويل', 'bina' ) . '</button>';
			echo '</form>';
			echo '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}

	echo '<hr style="margin:20px 0;" />';
	echo '<h2>' . esc_html__( 'معاملات الدفع الناجحة', 'bina' ) . '</h2>';
	echo '<p class="description">' . esc_html__( 'تعرض عمليات التمويل التي تمت بنجاح عبر البوابة (Mock/Production).', 'bina' ) . '</p>';
	$tx_rows = function_exists( 'bina_payment_tx_fetch_for_admin' )
		? bina_payment_tx_fetch_for_admin(
			array(
				'flow_type'   => 'payin',
				'object_type' => 'milestone',
				'status'      => 'completed',
				'limit'       => 300,
			)
		)
		: array();
	if ( empty( $tx_rows ) ) {
		echo '<p>' . esc_html__( 'لا توجد معاملات مكتملة حتى الآن.', 'bina' ) . '</p>';
	} else {
		echo '<table class="widefat striped">';
		echo '<thead><tr>';
		echo '<th>' . esc_html__( '#TX', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'المشروع', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'الدفعة', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'العميل الدافع', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'المبلغ', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'Gateway Ref', 'bina' ) . '</th>';
		echo '<th>' . esc_html__( 'التاريخ', 'bina' ) . '</th>';
		echo '</tr></thead><tbody>';
		foreach ( $tx_rows as $tx ) {
			$mid      = isset( $tx['object_id'] ) ? (int) $tx['object_id'] : 0;
			$tx_id    = isset( $tx['id'] ) ? (int) $tx['id'] : 0;
			$payer_id = isset( $tx['user_id'] ) ? (int) $tx['user_id'] : 0;
			$amount   = isset( $tx['amount'] ) ? (float) $tx['amount'] : 0.0;
			$ref      = isset( $tx['gateway_ref'] ) ? (string) $tx['gateway_ref'] : '';
			$created  = isset( $tx['created_at'] ) ? (string) $tx['created_at'] : '';
			$milestone = function_exists( 'bina_milestone_get_by_id' ) ? bina_milestone_get_by_id( $mid ) : null;
			$project_id = is_array( $milestone ) && isset( $milestone['project_id'] ) ? (int) $milestone['project_id'] : 0;
			$project = $project_id > 0 ? get_post( $project_id ) : null;
			$payer = $payer_id > 0 ? get_userdata( $payer_id ) : null;
			$ms_label = is_array( $milestone )
				? ( ! empty( $milestone['title'] ) ? (string) $milestone['title'] : sprintf( __( 'دفعة %d', 'bina' ), (int) ( $milestone['milestone_no'] ?? 0 ) ) )
				: '—';

			echo '<tr>';
			echo '<td>' . esc_html( (string) $tx_id ) . '</td>';
			echo '<td>' . ( $project_id > 0 ? '<a href="' . esc_url( $project_detail_url( $project_id ) ) . '">' . esc_html( $project ? get_the_title( $project_id ) : ( '#' . $project_id ) ) . '</a>' : '—' ) . '</td>';
			echo '<td>' . esc_html( $ms_label ) . '</td>';
			echo '<td>' . esc_html( $payer ? $payer->display_name : '—' ) . '</td>';
			echo '<td>' . esc_html( number_format_i18n( $amount, 2 ) ) . ' ' . esc_html__( 'ر.س', 'bina' ) . '</td>';
			echo '<td>' . esc_html( $ref !== '' ? $ref : '—' ) . '</td>';
			echo '<td>' . esc_html( $created !== '' ? $created : '—' ) . '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}

	echo '<hr style="margin:20px 0;" />';
	echo '<h2>' . esc_html__( 'الدفعات الممولة وإتاحة السحب', 'bina' ) . '</h2>';
	echo '<p class="description">' . esc_html__( 'من هنا يمكنك التحكم في جعل الدفعة متاحة للسحب لمزود الخدمة.', 'bina' ) . '</p>';
	$funded_rows = function_exists( 'bina_milestones_fetch_by_statuses' )
		? bina_milestones_fetch_by_statuses( array( 'funded', 'submitted', 'approved', 'released' ), 300 )
		: array();
	if ( empty( $funded_rows ) ) {
		echo '<p>' . esc_html__( 'لا توجد دفعات ممولة حتى الآن.', 'bina' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped">';
	echo '<thead><tr>';
	echo '<th>' . esc_html__( 'المشروع', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'مقدم الخدمة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'الدفعة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'المبلغ', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'الحالة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'إجراء', 'bina' ) . '</th>';
	echo '</tr></thead><tbody>';
	$provider_paid_withdraw_map = array();
	foreach ( $funded_rows as $r ) {
		$mid         = isset( $r['id'] ) ? (int) $r['id'] : 0;
		$project_id  = isset( $r['project_id'] ) ? (int) $r['project_id'] : 0;
		$provider_id = isset( $r['provider_id'] ) ? (int) $r['provider_id'] : 0;
		$title       = isset( $r['title'] ) ? (string) $r['title'] : '';
		$no          = isset( $r['milestone_no'] ) ? (int) $r['milestone_no'] : 0;
		$amount      = isset( $r['amount'] ) ? (float) $r['amount'] : 0.0;
		$status      = isset( $r['status'] ) ? (string) $r['status'] : '';
		$project     = $project_id > 0 ? get_post( $project_id ) : null;
		$provider_u  = $provider_id > 0 ? get_userdata( $provider_id ) : null;

		echo '<tr>';
		echo '<td>' . ( $project_id > 0 ? '<a href="' . esc_url( $project_detail_url( $project_id ) ) . '">' . esc_html( $project ? get_the_title( $project_id ) : ( '#' . $project_id ) ) . '</a>' : '—' ) . '</td>';
		echo '<td>' . esc_html( $provider_u ? $provider_u->display_name : '—' ) . '</td>';
		echo '<td>' . esc_html( $title !== '' ? $title : sprintf( __( 'دفعة %d', 'bina' ), $no ) ) . '</td>';
		echo '<td>' . esc_html( number_format_i18n( $amount, 2 ) ) . ' ' . esc_html__( 'ر.س', 'bina' ) . '</td>';
		echo '<td>' . esc_html( $status ) . '</td>';
		echo '<td>';
		if ( $status === 'released' ) {
			$has_paid_withdraw = false;
			if ( $provider_id > 0 ) {
				if ( ! array_key_exists( $provider_id, $provider_paid_withdraw_map ) ) {
					$paid_rows = function_exists( 'bina_withdraw_requests_fetch_for_admin' )
						? bina_withdraw_requests_fetch_for_admin(
							array(
								'user_id' => $provider_id,
								'status'  => 'paid',
								'limit'   => 1,
							)
						)
						: array();
					$provider_paid_withdraw_map[ $provider_id ] = ! empty( $paid_rows );
				}
				$has_paid_withdraw = ! empty( $provider_paid_withdraw_map[ $provider_id ] );
			}
			if ( $has_paid_withdraw ) {
				echo '<span class="button button-small" style="opacity:.85;cursor:default;background:#16a34a;border-color:#16a34a;color:#fff;">' . esc_html__( 'تم السحب', 'bina' ) . '</span>';
			} else {
				echo '<span class="button button-small" style="opacity:.7;cursor:default;">' . esc_html__( 'متاح للسحب', 'bina' ) . '</span>';
			}
		} else {
			$nonce = wp_create_nonce( 'bina_make_milestone_withdrawable_' . $mid );
			echo '<form method="post" action="' . esc_url( admin_url( 'admin.php?page=bina-project-admin&tab=payments' ) ) . '" style="display:inline-block; margin:0; padding:0;" onsubmit="return confirm(\'' . esc_js( __( 'تأكيد: جعل هذه الدفعة متاحة للسحب لمزود الخدمة؟', 'bina' ) ) . '\');">';
			echo '<input type="hidden" name="milestone_id" value="' . esc_attr( (string) $mid ) . '" />';
			echo '<input type="hidden" name="bina_make_milestone_withdrawable" value="1" />';
			echo '<input type="hidden" name="bina_make_milestone_withdrawable_nonce" value="' . esc_attr( $nonce ) . '" />';
			echo '<button type="submit" class="button button-small">' . esc_html__( 'إتاحة السحب', 'bina' ) . '</button>';
			echo '</form>';
		}
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
}

/**
 * @param int      $user_id
 * @param string[] $st_labels
 * @param callable $project_detail_url
 * @return void
 */
function bina_render_admin_customer_projects( $user_id, $st_labels, $project_detail_url ) {
	$u = get_userdata( (int) $user_id );
	if ( ! $u ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'العميل غير موجود.', 'bina' ) . '</p></div>';
		return;
	}

	$phone = get_user_meta( (int) $user_id, 'bina_phone', true );
	$city  = get_user_meta( (int) $user_id, 'bina_city', true );

	echo '<h2>' . esc_html( $u->display_name ) . '</h2>';
	echo '<p class="description">' . esc_html( $u->user_email ) . '</p>';
	echo '<div class="bina-admin-card" style="margin-bottom:16px; max-width: 880px;">';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th>' . esc_html__( 'الهاتف', 'bina' ) . '</th><td>' . esc_html( $phone ? (string) $phone : '—' ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'المدينة', 'bina' ) . '</th><td>' . esc_html( $city ? (string) $city : '—' ) . '</td></tr>';
	echo '</tbody></table>';
	echo '</div>';

	$q = new WP_Query(
		array(
			'post_type'              => 'bina_project',
			'post_status'            => array( 'publish', 'pending', 'draft' ),
			'author'                 => (int) $user_id,
			'posts_per_page'         => -1,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	echo '<h3>' . esc_html__( 'مشاريع العميل', 'bina' ) . '</h3>';
	if ( ! $q->have_posts() ) {
		echo '<p>' . esc_html__( 'لا توجد مشاريع.', 'bina' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped">';
	echo '<thead><tr>';
	echo '<th>' . esc_html__( 'المشروع', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'الحالة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'مقدم الخدمة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'آخر تحديث', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'إجراءات', 'bina' ) . '</th>';
	echo '</tr></thead><tbody>';

	foreach ( $q->posts as $p ) {
		$pid     = (int) $p->ID;
		$status  = bina_get_project_status_meta( $pid );
		$statusl = isset( $st_labels[ $status ] ) ? $st_labels[ $status ] : $status;

		$assigned_id = bina_get_project_assigned_provider_id( $pid );
		$assigned_name = '';
		if ( $assigned_id > 0 ) {
			$sp_user = get_userdata( $assigned_id );
			$assigned_name = $sp_user ? $sp_user->display_name : '';
		}

		$updated_ts = get_post_modified_time( 'U', true, $pid );
		$updated_ago = $updated_ts ? human_time_diff( (int) $updated_ts, (int) current_time( 'timestamp' ) ) : '';

		echo '<tr>';
		echo '<td>' . esc_html( get_the_title( $pid ) ) . '</td>';
		echo '<td><span class="bina-admin-badge">' . esc_html( $statusl ) . '</span></td>';
		echo '<td>' . esc_html( $assigned_name !== '' ? $assigned_name : '—' ) . '</td>';
		echo '<td>' . esc_html( $updated_ago !== '' ? $updated_ago : '—' ) . '</td>';
		echo '<td><a class="button button-small" href="' . esc_url( $project_detail_url( $pid ) ) . '">' . esc_html__( 'فتح', 'bina' ) . '</a></td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
}

/**
 * @param int      $user_id
 * @param string[] $st_labels
 * @param callable $project_detail_url
 * @return void
 */
function bina_render_admin_provider_projects( $user_id, $st_labels, $project_detail_url ) {
	$u = get_userdata( (int) $user_id );
	if ( ! $u ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'مقدم الخدمة غير موجود.', 'bina' ) . '</p></div>';
		return;
	}

	$phone = get_user_meta( (int) $user_id, 'bina_phone', true );
	$city  = get_user_meta( (int) $user_id, 'bina_city', true );

	echo '<h2>' . esc_html( $u->display_name ) . '</h2>';
	echo '<p class="description">' . esc_html( $u->user_email ) . '</p>';
	echo '<div class="bina-admin-card" style="margin-bottom:16px; max-width: 880px;">';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th>' . esc_html__( 'الهاتف', 'bina' ) . '</th><td>' . esc_html( $phone ? (string) $phone : '—' ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'المدينة', 'bina' ) . '</th><td>' . esc_html( $city ? (string) $city : '—' ) . '</td></tr>';
	echo '</tbody></table>';
	echo '</div>';

	$q = new WP_Query(
		array(
			'post_type'              => 'bina_project',
			'post_status'            => array( 'publish', 'pending', 'draft' ),
			'posts_per_page'         => -1,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
			'meta_query'             => array(
				array(
					'key'     => '_bina_assigned_provider_id',
					'value'   => (int) $user_id,
					'compare' => '=',
					'type'    => 'NUMERIC',
				),
			),
		)
	);

	echo '<h3>' . esc_html__( 'مشاريع مقدم الخدمة', 'bina' ) . '</h3>';
	if ( ! $q->have_posts() ) {
		echo '<p>' . esc_html__( 'لا توجد مشاريع.', 'bina' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped">';
	echo '<thead><tr>';
	echo '<th>' . esc_html__( 'المشروع', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'العميل', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'الحالة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'آخر تحديث', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'إجراءات', 'bina' ) . '</th>';
	echo '</tr></thead><tbody>';

	foreach ( $q->posts as $p ) {
		$pid     = (int) $p->ID;
		$status  = bina_get_project_status_meta( $pid );
		$statusl = isset( $st_labels[ $status ] ) ? $st_labels[ $status ] : $status;

		$customer_id   = (int) $p->post_author;
		$customer_user = get_userdata( $customer_id );

		$updated_ts = get_post_modified_time( 'U', true, $pid );
		$updated_ago = $updated_ts ? human_time_diff( (int) $updated_ts, (int) current_time( 'timestamp' ) ) : '';

		echo '<tr>';
		echo '<td>' . esc_html( get_the_title( $pid ) ) . '</td>';
		echo '<td>' . esc_html( $customer_user ? $customer_user->display_name : '—' ) . '</td>';
		echo '<td><span class="bina-admin-badge">' . esc_html( $statusl ) . '</span></td>';
		echo '<td>' . esc_html( $updated_ago !== '' ? $updated_ago : '—' ) . '</td>';
		echo '<td><a class="button button-small" href="' . esc_url( $project_detail_url( $pid ) ) . '">' . esc_html__( 'فتح', 'bina' ) . '</a></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
}

/**
 * @param array<string,string> $st_labels
 * @param callable             $project_detail_url
 * @param callable             $customer_view_url
 * @param callable             $provider_view_url
 * @return void
 */
function bina_render_admin_projects_list( $st_labels, $project_detail_url, $customer_view_url, $provider_view_url ) {
	$q = new WP_Query(
		array(
			'post_type'              => 'bina_project',
			'post_status'            => array( 'publish', 'pending', 'draft' ),
			'posts_per_page'         => 30,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	echo '<h2 class="screen-reader-text">' . esc_html__( 'قائمة المشاريع', 'bina' ) . '</h2>';

	if ( ! $q->have_posts() ) {
		echo '<p>' . esc_html__( 'لا توجد مشاريع.', 'bina' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped">';
	echo '<thead><tr>';
	echo '<th>' . esc_html__( 'المشروع', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'العميل', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'مقدم الخدمة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'الحالة', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'آخر تحديث', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'إجراءات', 'bina' ) . '</th>';
	echo '</tr></thead><tbody>';

	foreach ( $q->posts as $p ) {
		$pid       = (int) $p->ID;
		$customer  = (int) $p->post_author;
		$customer_u = get_userdata( $customer );

		$assigned_id = bina_get_project_assigned_provider_id( $pid );
		$assigned_user = $assigned_id > 0 ? get_userdata( $assigned_id ) : null;

		$status  = bina_get_project_status_meta( $pid );
		$statusl = isset( $st_labels[ $status ] ) ? $st_labels[ $status ] : $status;

		$updated_ts = get_post_modified_time( 'U', true, $pid );
		$updated_ago = $updated_ts ? human_time_diff( (int) $updated_ts, (int) current_time( 'timestamp' ) ) : '';

		echo '<tr>';
		echo '<td>' . esc_html( get_the_title( $pid ) ) . '</td>';

		if ( $customer_u ) {
			echo '<td><a href="' . esc_url( $customer_view_url( $customer_u->ID ) ) . '">' . esc_html( $customer_u->display_name ) . '</a></td>';
		} else {
			echo '<td>—</td>';
		}

		if ( $assigned_user ) {
			echo '<td><a href="' . esc_url( $provider_view_url( $assigned_user->ID ) ) . '">' . esc_html( $assigned_user->display_name ) . '</a></td>';
		} else {
			echo '<td>—</td>';
		}

		echo '<td><span class="bina-admin-badge">' . esc_html( $statusl ) . '</span></td>';
		echo '<td>' . esc_html( $updated_ago !== '' ? $updated_ago : '—' ) . '</td>';
		echo '<td>';
		echo '<a class="button button-small" href="' . esc_url( $project_detail_url( $pid ) ) . '">' . esc_html__( 'فتح', 'bina' ) . '</a> ';
		echo '<form method="post" action="' . esc_url( admin_url( 'admin.php?page=bina-project-admin&tab=projects' ) ) . '" style="display:inline-block; margin:0; padding:0;" onsubmit="return confirm(\'' . esc_js( __( 'هل تريد حذف هذا المشروع؟ سيتم نقله لسلة المهملات.', 'bina' ) ) . '\');">';
		echo '<input type="hidden" name="post_id" value="' . esc_attr( (string) $pid ) . '" />';
		echo '<input type="hidden" name="bina_trash_project" value="1" />';
		echo '<input type="hidden" name="bina_trash_project_nonce" value="' . esc_attr( wp_create_nonce( 'bina_trash_project_' . $pid ) ) . '" />';
		echo '<button type="submit" class="button button-small" style="color:#b91c1c; border-color: rgba(185,28,28,.35);">' . esc_html__( 'حذف', 'bina' ) . '</button>';
		echo '</form>';
		echo '</td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
}

/**
 * @param callable $customer_view_url
 * @return void
 */
function bina_render_admin_customers_list( $customer_view_url ) {
	$users = get_users(
		array(
			'role'    => 'customer',
			'number'  => 400,
			'orderby' => 'display_name',
			'order'   => 'ASC',
		)
	);

	echo '<h2>' . esc_html__( 'العملاء', 'bina' ) . '</h2>';
	if ( empty( $users ) ) {
		echo '<p>' . esc_html__( 'لا يوجد عملاء.', 'bina' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped">';
	echo '<thead><tr>';
	echo '<th>' . esc_html__( 'الاسم', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'البريد', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'المشاريع', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'إجراءات', 'bina' ) . '</th>';
	echo '</tr></thead><tbody>';

	foreach ( $users as $u ) {
		$count = (int) ( new WP_Query( array( 'post_type' => 'bina_project', 'post_status' => array( 'publish', 'pending', 'draft' ), 'author' => (int) $u->ID, 'posts_per_page' => -1, 'fields' => 'ids', 'no_found_rows' => true ) ) )->post_count;

		echo '<tr>';
		echo '<td>' . esc_html( $u->display_name ) . '</td>';
		echo '<td>' . esc_html( $u->user_email ) . '</td>';
		echo '<td>' . (int) $count . '</td>';
		echo '<td><a class="button button-small" href="' . esc_url( $customer_view_url( $u->ID ) ) . '">' . esc_html__( 'عرض', 'bina' ) . '</a></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
}

/**
 * @param callable $provider_view_url
 * @return void
 */
function bina_render_admin_providers_list( $provider_view_url ) {
	$users = get_users(
		array(
			'role'    => 'service_provider',
			'number'  => 400,
			'orderby' => 'display_name',
			'order'   => 'ASC',
		)
	);

	echo '<h2>' . esc_html__( 'مقدمو الخدمة', 'bina' ) . '</h2>';
	if ( empty( $users ) ) {
		echo '<p>' . esc_html__( 'لا يوجد مقدمو خدمة.', 'bina' ) . '</p>';
		return;
	}

	echo '<table class="widefat striped">';
	echo '<thead><tr>';
	echo '<th>' . esc_html__( 'الاسم', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'البريد', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'المشاريع', 'bina' ) . '</th>';
	echo '<th>' . esc_html__( 'إجراءات', 'bina' ) . '</th>';
	echo '</tr></thead><tbody>';

	foreach ( $users as $u ) {
		$count_q = new WP_Query(
			array(
				'post_type'              => 'bina_project',
				'post_status'            => array( 'publish', 'pending', 'draft' ),
				'posts_per_page'         => -1,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'meta_query'             => array(
					array(
						'key'     => '_bina_assigned_provider_id',
						'value'   => (int) $u->ID,
						'compare' => '=',
						'type'    => 'NUMERIC',
					),
				),
			)
		);
		$count = (int) $count_q->post_count;

		echo '<tr>';
		echo '<td>' . esc_html( $u->display_name ) . '</td>';
		echo '<td>' . esc_html( $u->user_email ) . '</td>';
		echo '<td>' . (int) $count . '</td>';
		echo '<td><a class="button button-small" href="' . esc_url( $provider_view_url( $u->ID ) ) . '">' . esc_html__( 'عرض', 'bina' ) . '</a></td>';
		echo '</tr>';
	}

	echo '</tbody></table>';
}

/**
 * Redirect CPT list/add to our overview page.
 *
 * @return void
 */
function bina_project_admin_redirect_cpt_list() {
	if ( ! is_admin() ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Keep our own pages.
	if ( isset( $_GET['page'] ) && in_array( sanitize_text_field( wp_unslash( $_GET['page'] ) ), array( 'bina-project-admin', 'bina-project-detail' ), true ) ) {
		return;
	}

	$pt     = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';
	$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';

	// Redirect list/add screens for the CPT.
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';

	// edit list screen / edit actions.
	if ( $pt === 'bina_project' && ( $action === '' || $action === 'edit' ) && strpos( $request_uri, 'post-new.php' ) === false ) {
		wp_safe_redirect( admin_url( 'admin.php?page=bina-project-admin&tab=projects' ) );
		exit;
	}

	// add screen.
	if ( $pt === 'bina_project' && ( $action === 'add' || strpos( $request_uri, 'post-new.php' ) !== false ) ) {
		wp_safe_redirect( admin_url( 'admin.php?page=bina-project-admin&tab=projects' ) );
		exit;
	}
}
add_action( 'admin_init', 'bina_project_admin_redirect_cpt_list', 1 );

/**
 * Register admin menu.
 *
 * @return void
 */
function bina_register_admin_project_admin_menu() {
	$cap = 'manage_options';
	add_menu_page(
		__( 'إدارة مشاريع العملاء', 'bina' ),
		__( 'إدارة مشاريع العملاء', 'bina' ),
		$cap,
		'bina-project-admin',
		'bina_render_admin_project_overview_page',
		'dashicons-hammer',
		26
	);
}
add_action( 'admin_menu', 'bina_register_admin_project_admin_menu' );

