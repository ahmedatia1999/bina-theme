<?php
/**
 * Auto-created WordPress pages for dashboards + customer portal + URL helpers.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @return array<int,array{slug:string,title:string}>
 */
function bina_get_theme_auto_pages() {
	return array(
		array(
			'slug'  => 'customer-dashboard',
			'title' => __( 'لوحة تحكم العميل', 'bina' ),
		),
		array(
			'slug'  => 'service-provider-dashboard',
			'title' => __( 'لوحة تحكم مقدم الخدمة', 'bina' ),
		),
		array(
			'slug'  => 'customer-my-projects',
			'title' => __( 'مشاريعي', 'bina' ),
		),
		array(
			'slug'  => 'customer-create-project',
			'title' => __( 'إنشاء مشروع', 'bina' ),
		),
		array(
			'slug'  => 'customer-project-detail',
			'title' => __( 'تفاصيل المشروع', 'bina' ),
		),
		array(
			'slug'  => 'customer-chat',
			'title' => __( 'المحادثات', 'bina' ),
		),
		array(
			'slug'  => 'customer-conflicts',
			'title' => __( 'النزاعات', 'bina' ),
		),
		array(
			'slug'  => 'customer-notifications',
			'title' => __( 'الإشعارات', 'bina' ),
		),
	);
}

function bina_customer_dashboard_page_slug() {
	return 'customer-dashboard';
}

function bina_service_provider_dashboard_page_slug() {
	return 'service-provider-dashboard';
}

function bina_customer_my_projects_page_slug() {
	return 'customer-my-projects';
}

function bina_customer_create_project_page_slug() {
	return 'customer-create-project';
}

function bina_customer_project_detail_page_slug() {
	return 'customer-project-detail';
}

function bina_customer_chat_page_slug() {
	return 'customer-chat';
}

function bina_customer_conflicts_page_slug() {
	return 'customer-conflicts';
}

function bina_customer_notifications_page_slug() {
	return 'customer-notifications';
}

/**
 * @param string $slug Page slug.
 * @return string Full URL.
 */
function bina_get_page_url_by_slug( $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}
	return home_url( '/' . $slug . '/' );
}

function bina_get_customer_dashboard_url() {
	return bina_get_page_url_by_slug( bina_customer_dashboard_page_slug() );
}

function bina_get_service_provider_dashboard_url() {
	return bina_get_page_url_by_slug( bina_service_provider_dashboard_page_slug() );
}

function bina_get_customer_my_projects_url() {
	return bina_get_page_url_by_slug( bina_customer_my_projects_page_slug() );
}

function bina_get_customer_create_project_url() {
	return bina_get_page_url_by_slug( bina_customer_create_project_page_slug() );
}

/**
 * Base URL for the project detail template (pass project_id in query string).
 *
 * @return string
 */
function bina_get_customer_project_detail_page_url() {
	return bina_get_page_url_by_slug( bina_customer_project_detail_page_slug() );
}

/**
 * Link to view one project (customer must own it — enforced in widget).
 *
 * @param int $post_id Project post ID.
 * @return string
 */
function bina_get_customer_project_detail_url( $post_id ) {
	return add_query_arg( 'project_id', absint( $post_id ), bina_get_customer_project_detail_page_url() );
}

function bina_get_customer_chat_url() {
	return bina_get_page_url_by_slug( bina_customer_chat_page_slug() );
}

function bina_get_customer_conflicts_url() {
	return bina_get_page_url_by_slug( bina_customer_conflicts_page_slug() );
}

function bina_get_customer_notifications_url() {
	return bina_get_page_url_by_slug( bina_customer_notifications_page_slug() );
}

/**
 * Open "create project" screen in edit mode for an existing project.
 *
 * @param int $post_id Project post ID.
 * @return string
 */
function bina_get_customer_edit_project_url( $post_id ) {
	return add_query_arg( 'project_id', absint( $post_id ), bina_get_customer_create_project_url() );
}

/**
 * Create theme pages if missing.
 *
 * @return void
 */
function bina_ensure_dashboard_pages() {
	foreach ( bina_get_theme_auto_pages() as $row ) {
		$slug = $row['slug'];
		if ( get_page_by_path( $slug, OBJECT, 'page' ) ) {
			continue;
		}
		$result = wp_insert_post(
			array(
				'post_title'   => $row['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			),
			true
		);
		if ( is_wp_error( $result ) ) {
			return;
		}
	}

	update_option( 'bina_dashboard_pages_ready', '1', false );
}

function bina_maybe_ensure_dashboard_pages() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	foreach ( bina_get_theme_auto_pages() as $row ) {
		if ( ! get_page_by_path( $row['slug'], OBJECT, 'page' ) ) {
			bina_ensure_dashboard_pages();
			return;
		}
	}
}

add_action( 'after_switch_theme', 'bina_ensure_dashboard_pages' );
add_action( 'admin_init', 'bina_maybe_ensure_dashboard_pages', 5 );

function bina_ensure_dashboard_pages_on_init_for_admins() {
	if ( ! is_user_logged_in() || ! current_user_can( 'publish_pages' ) ) {
		return;
	}
	foreach ( bina_get_theme_auto_pages() as $row ) {
		if ( ! get_page_by_path( $row['slug'], OBJECT, 'page' ) ) {
			bina_ensure_dashboard_pages();
			return;
		}
	}
}

add_action( 'init', 'bina_ensure_dashboard_pages_on_init_for_admins', 30 );

require_once get_template_directory() . '/inc/partials/dashboard-customer-shell.php';
