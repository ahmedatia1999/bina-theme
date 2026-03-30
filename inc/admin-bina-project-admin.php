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

	// Tabs.
	$tabs = array(
		'projects' => __( 'المشاريع', 'bina' ),
		'customers' => __( 'العملاء', 'bina' ),
		'providers' => __( 'مقدمو الخدمة', 'bina' ),
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

	echo '<p>' . esc_html__( 'تبويب غير صالح.', 'bina' ) . '</p>';
	echo '</div>';
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
		echo '<td><a class="button button-small" href="' . esc_url( $project_detail_url( $pid ) ) . '">' . esc_html__( 'فتح', 'bina' ) . '</a></td>';
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

