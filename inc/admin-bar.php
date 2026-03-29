<?php
/**
 * Hide the front-end (and when applicable) WordPress admin bar for everyone except Administrators.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param bool $show Whether the admin bar should be shown.
 * @return bool
 */
function bina_show_admin_bar_for_admins_only( $show ) {
	if ( ! is_user_logged_in() ) {
		return $show;
	}

	// Multisite: network super admins should keep the bar.
	if ( is_multisite() && is_super_admin() ) {
		return $show;
	}

	$user = wp_get_current_user();
	if ( $user && in_array( 'administrator', (array) $user->roles, true ) ) {
		return $show;
	}

	return false;
}

add_filter( 'show_admin_bar', 'bina_show_admin_bar_for_admins_only', 99 );
