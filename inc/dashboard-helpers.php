<?php
/**
 * Dashboard helpers: role checks, stats (extensible via filters), URL helpers.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * City options (value + Arabic label). Used by registration AJAX and project forms.
 *
 * @return array<int,array{value:string,label:string}>
 */
function bina_get_cities_for_select() {
	return array(
		array( 'value' => 'riyadh', 'label' => 'الرياض' ),
		array( 'value' => 'jeddah', 'label' => 'جدة' ),
		array( 'value' => 'makkah', 'label' => 'مكة المكرمة' ),
		array( 'value' => 'madinah', 'label' => 'المدينة المنورة' ),
		array( 'value' => 'dammam', 'label' => 'الدمام' ),
		array( 'value' => 'khobar', 'label' => 'الخبر' ),
		array( 'value' => 'taif', 'label' => 'الطائف' ),
		array( 'value' => 'abha', 'label' => 'أبها' ),
		array( 'value' => 'tabuk', 'label' => 'تبوك' ),
		array( 'value' => 'buraydah', 'label' => 'بريدة' ),
	);
}

/**
 * Normalize a path or full URL to a full URL.
 *
 * @param string $url Path like /customer/dashboard or full URL.
 * @return string
 */
function bina_dashboard_resolve_url( $url ) {
	$url = trim( (string) $url );
	if ( $url === '' ) {
		return '';
	}
	if ( preg_match( '#^https?://#i', $url ) ) {
		return $url;
	}
	return home_url( $url );
}

/**
 * Default portal URLs for customer app (pages created by theme).
 *
 * @return array<string,string>
 */
function bina_get_customer_portal_default_urls() {
	return array(
		'dashboard'          => bina_get_customer_dashboard_url(),
		'profile'            => bina_get_customer_profile_url(),
		'my_projects'        => bina_get_customer_my_projects_url(),
		'my_projects_create' => bina_get_customer_create_project_url(),
		'chat'               => bina_get_customer_chat_url(),
		'notifications'      => bina_get_customer_notifications_url(),
		'disputes'           => function_exists( 'bina_get_customer_disputes_url' ) ? bina_get_customer_disputes_url() : home_url( '/customer-disputes/' ),
	);
}

/**
 * Merge optional Elementor URL controls (from Customer Dashboard widget) into defaults.
 *
 * @param array<string,mixed>|null $settings get_settings_for_display() or null.
 * @return array<string,string>
 */
function bina_get_customer_portal_urls( $settings = null ) {
	$urls = bina_get_customer_portal_default_urls();

	if ( is_array( $settings ) ) {
		$dash = isset( $settings['url_dashboard']['url'] ) ? trim( (string) $settings['url_dashboard']['url'] ) : '';
		if ( $dash !== '' ) {
			$urls['dashboard'] = bina_dashboard_resolve_url( $dash );
		}
		$map = array(
			'url_profile'            => 'profile',
			'url_my_projects'        => 'my_projects',
			'url_my_projects_create' => 'my_projects_create',
			'url_chat'               => 'chat',
			'url_notifications'      => 'notifications',
			'url_disputes'           => 'disputes',
		);
		foreach ( $map as $ctrl => $key ) {
			$u = isset( $settings[ $ctrl ]['url'] ) ? trim( (string) $settings[ $ctrl ]['url'] ) : '';
			if ( $u !== '' ) {
				$urls[ $key ] = bina_dashboard_resolve_url( $u );
			}
		}
	}

	return apply_filters( 'bina_customer_portal_urls', $urls );
}

/**
 * Enqueue JS for customer portal shell (mobile sidebar). Idempotent.
 *
 * @return void
 */
function bina_customer_portal_enqueue_shell_assets() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$script_path = get_template_directory() . '/assets/js/dashboard-shell.js';
	if ( ! file_exists( $script_path ) ) {
		return;
	}
	wp_enqueue_script(
		'bina-dashboard-shell',
		get_template_directory_uri() . '/assets/js/dashboard-shell.js',
		array(),
		filemtime( $script_path ),
		true
	);

	// Notifications bell (unread counter) — optional if JS file exists.
	$bjs_path = get_template_directory() . '/assets/js/bina-notifications-bell.js';
	if ( file_exists( $bjs_path ) ) {
		wp_enqueue_script(
			'bina-notifications-bell',
			get_template_directory_uri() . '/assets/js/bina-notifications-bell.js',
			array(),
			filemtime( $bjs_path ),
			true
		);
		wp_localize_script(
			'bina-notifications-bell',
			'binaNotificationsBell',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'bina_notifications' ),
				'pollMs'  => 8000,
			)
		);
	}

	$done = true;
}

/**
 * Enqueue AJAX thread messaging script (customer / service provider chat pages).
 *
 * @return void
 */
function bina_enqueue_project_messages_script() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$path = get_template_directory() . '/assets/js/bina-project-messages.js';
	if ( ! file_exists( $path ) ) {
		return;
	}
	wp_enqueue_script(
		'bina-project-messages',
		get_template_directory_uri() . '/assets/js/bina-project-messages.js',
		array(),
		filemtime( $path ),
		true
	);
	wp_localize_script(
		'bina-project-messages',
		'binaProjectMessages',
		array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'bina_project_messages' ),
			'currentUserId' => get_current_user_id(),
		)
	);
	$done = true;
}

/**
 * @param WP_User $user User object.
 * @return bool
 */
function bina_user_is_customer( $user ) {
	if ( ! $user || ! $user->ID ) {
		return false;
	}
	$type = get_user_meta( $user->ID, 'bina_account_type', true );
	if ( $type === 'customer' ) {
		return true;
	}
	return in_array( 'customer', (array) $user->roles, true );
}

/**
 * @param WP_User $user User object.
 * @return bool
 */
function bina_user_is_service_provider( $user ) {
	if ( ! $user || ! $user->ID ) {
		return false;
	}
	$type = get_user_meta( $user->ID, 'bina_account_type', true );
	if ( $type === 'service_provider' ) {
		return true;
	}
	return in_array( 'service_provider', (array) $user->roles, true );
}

/**
 * Display name for dashboard (prefer first + last from meta if you add them later).
 *
 * @param WP_User $user User object.
 * @return string
 */
function bina_dashboard_user_display_name( $user ) {
	if ( ! $user || ! $user->ID ) {
		return '';
	}
	$name = $user->display_name;
	if ( $name !== '' && $name !== $user->user_login ) {
		return $name;
	}
	return $user->user_email ? $user->user_email : $user->user_login;
}

/**
 * Single letter avatar fallback.
 *
 * @param WP_User $user User object.
 * @return string
 */
function bina_dashboard_user_initial( $user ) {
	$name = bina_dashboard_user_display_name( $user );
	if ( $name === '' ) {
		return '?';
	}
	return function_exists( 'mb_substr' )
		? mb_strtoupper( mb_substr( $name, 0, 1 ) )
		: strtoupper( substr( $name, 0, 1 ) );
}

/**
 * Stats for customer dashboard home. Hook `bina_customer_dashboard_stats` to fill from CPT / custom tables.
 *
 * @param int $user_id User ID.
 * @return array<string,int>
 */
function bina_get_customer_dashboard_stats( $user_id ) {
	$defaults = array(
		'total_projects'       => 0,
		'active_projects'      => 0,
		'pending_offers'       => 0,
		'unread_messages'      => 0,
		'my_projects_badge'    => 0,
		'notifications_unread' => 0,
	);
	return apply_filters( 'bina_customer_dashboard_stats', $defaults, (int) $user_id );
}

/**
 * Stats for service provider dashboard home.
 *
 * @param int $user_id User ID.
 * @return array<string,int|float>
 */
function bina_get_service_provider_dashboard_stats( $user_id ) {
	$defaults = array(
		'browse_projects_count'  => 0,
		'total_offers'           => 0,
		'accepted_offers'        => 0,
		'active_projects'        => 0,
		'acceptance_rate'        => 0,
		'unread_messages'        => 0,
		'unread_notifications'   => 0,
		'notifications_bell'     => 0,
	);
	return apply_filters( 'bina_service_provider_dashboard_stats', $defaults, (int) $user_id );
}
