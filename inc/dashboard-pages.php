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
			'slug'  => 'service-provider-browse-projects',
			'title' => __( 'تصفح المشاريع — مقدم الخدمة', 'bina' ),
		),
		array(
			'slug'  => 'service-provider-my-projects',
			'title' => __( 'مشاريعي — مقدم الخدمة', 'bina' ),
		),
		array(
			'slug'  => 'service-provider-profile',
			'title' => __( 'الملف الشخصي — مقدم الخدمة', 'bina' ),
		),
		array(
			'slug'  => 'service-provider-subscription',
			'title' => __( 'الاشتراك — مقدم الخدمة', 'bina' ),
		),
		array(
			'slug'  => 'service-provider-offers',
			'title' => __( 'عروضي — مقدم الخدمة', 'bina' ),
		),
		array(
			'slug'  => 'service-provider-payments',
			'title' => __( 'المدفوعات — مقدم الخدمة', 'bina' ),
		),
		array(
			'slug'  => 'service-provider-notifications',
			'title' => __( 'الإشعارات — مقدم الخدمة', 'bina' ),
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
			'slug'  => 'service-provider-chat',
			'title' => __( 'محادثات مقدم الخدمة', 'bina' ),
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

function bina_service_provider_chat_page_slug() {
	return 'service-provider-chat';
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

/**
 * @return string
 */
function bina_get_service_provider_chat_url() {
	return bina_get_page_url_by_slug( bina_service_provider_chat_page_slug() );
}

function bina_service_provider_browse_projects_page_slug() {
	return 'service-provider-browse-projects';
}

function bina_get_service_provider_browse_projects_url() {
	return bina_get_page_url_by_slug( bina_service_provider_browse_projects_page_slug() );
}

function bina_service_provider_my_projects_page_slug() {
	return 'service-provider-my-projects';
}

function bina_get_service_provider_my_projects_url() {
	return bina_get_page_url_by_slug( bina_service_provider_my_projects_page_slug() );
}

function bina_service_provider_profile_page_slug() {
	return 'service-provider-profile';
}

function bina_get_service_provider_profile_url() {
	return bina_get_page_url_by_slug( bina_service_provider_profile_page_slug() );
}

function bina_service_provider_subscription_page_slug() {
	return 'service-provider-subscription';
}

function bina_get_service_provider_subscription_url() {
	return bina_get_page_url_by_slug( bina_service_provider_subscription_page_slug() );
}

function bina_service_provider_offers_page_slug() {
	return 'service-provider-offers';
}

function bina_get_service_provider_offers_url() {
	return bina_get_page_url_by_slug( bina_service_provider_offers_page_slug() );
}

function bina_service_provider_payments_page_slug() {
	return 'service-provider-payments';
}

function bina_get_service_provider_payments_url() {
	return bina_get_page_url_by_slug( bina_service_provider_payments_page_slug() );
}

function bina_service_provider_notifications_page_slug() {
	return 'service-provider-notifications';
}

function bina_get_service_provider_notifications_url() {
	return bina_get_page_url_by_slug( bina_service_provider_notifications_page_slug() );
}

/**
 * Service provider portal URLs (pages created by theme) with optional Elementor overrides.
 *
 * @param array<string,mixed>|null $settings Elementor widget settings (url_* controls).
 * @return array<string,string>
 */
function bina_get_service_provider_portal_urls( $settings = null ) {
	$urls = array(
		'dashboard'       => bina_get_service_provider_dashboard_url(),
		'browse_projects' => bina_get_service_provider_browse_projects_url(),
		'my_projects'     => bina_get_service_provider_my_projects_url(),
		'profile'         => bina_get_service_provider_profile_url(),
		'subscription'    => bina_get_service_provider_subscription_url(),
		'offers'          => bina_get_service_provider_offers_url(),
		'chat'            => bina_get_service_provider_chat_url(),
		'payments'        => bina_get_service_provider_payments_url(),
		'notifications'   => bina_get_service_provider_notifications_url(),
		'verification'    => bina_get_service_provider_profile_url() . '#verification',
	);

	if ( is_array( $settings ) ) {
		$dash = isset( $settings['url_dashboard']['url'] ) ? trim( (string) $settings['url_dashboard']['url'] ) : '';
		if ( $dash !== '' ) {
			$urls['dashboard'] = bina_dashboard_resolve_url( $dash );
		}
		$map = array(
			'url_browse_projects' => 'browse_projects',
			'url_my_projects'     => 'my_projects',
			'url_profile'         => 'profile',
			'url_subscription'    => 'subscription',
			'url_offers'          => 'offers',
			'url_chat'            => 'chat',
			'url_payments'        => 'payments',
			'url_notifications'   => 'notifications',
		);
		foreach ( $map as $ctrl => $key ) {
			$u = isset( $settings[ $ctrl ]['url'] ) ? trim( (string) $settings[ $ctrl ]['url'] ) : '';
			if ( $u !== '' ) {
				$urls[ $key ] = bina_dashboard_resolve_url( $u );
			}
		}
		$ver = isset( $settings['url_verification']['url'] ) ? trim( (string) $settings['url_verification']['url'] ) : '';
		if ( $ver !== '' ) {
			$urls['verification'] = bina_dashboard_resolve_url( $ver );
		}
	}

	return apply_filters( 'bina_service_provider_portal_urls', $urls );
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

/**
 * Render a fallback app for service-provider-profile page if content is empty.
 * This prevents blank pages when Elementor content is not configured.
 *
 * @param string $content Page content.
 * @return string
 */
function bina_service_provider_profile_page_fallback_content( $content ) {
	if ( ! is_singular( 'page' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$post = get_post();
	if ( ! $post instanceof WP_Post || $post->post_name !== bina_service_provider_profile_page_slug() ) {
		return $content;
	}

	ob_start();

	if ( ! is_user_logged_in() ) {
		echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'يجب تسجيل الدخول لعرض الملف الشخصي.', 'bina' ) . '</p>';
		return (string) ob_get_clean();
	}

	$user = wp_get_current_user();
	if ( ! bina_user_is_service_provider( $user ) ) {
		echo '<p class="p-6 text-center text-muted-foreground">' . esc_html__( 'هذه الصفحة مخصصة لمقدمي الخدمة.', 'bina' ) . '</p>';
		return (string) ob_get_clean();
	}

	if ( function_exists( 'bina_customer_portal_enqueue_shell_assets' ) ) {
		bina_customer_portal_enqueue_shell_assets();
	}

	$urls     = bina_get_service_provider_portal_urls();
	$stats    = bina_get_service_provider_dashboard_stats( $user->ID );
	$logo_url = '';
	$help_url = bina_dashboard_resolve_url( 'https://wa.me/966590000474' );

	require_once get_template_directory() . '/inc/partials/service-provider-chat-layout.php';

	bina_render_service_provider_chat_layout_start(
		array(
			'user'       => $user,
			'urls'       => $urls,
			'stats'      => $stats,
			'logo_url'   => $logo_url,
			'help_url'   => $help_url,
			'active_nav' => 'profile',
		)
	);

	include get_template_directory() . '/inc/partials/service-provider-profile-app.php';

	bina_render_service_provider_chat_layout_end();

	return (string) ob_get_clean();
}
add_filter( 'the_content', 'bina_service_provider_profile_page_fallback_content', 20 );
