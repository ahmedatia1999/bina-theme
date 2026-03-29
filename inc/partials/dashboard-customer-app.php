<?php
/**
 * Customer dashboard home — uses shared portal shell.
 *
 * @var WP_User $user
 * @var array   $stats
 * @var array   $urls
 * @var string  $logo_url
 * @var string  $help_url
 * @var array   $status_counts
 * @var WP_Post[] $recent_projects
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$status_counts   = isset( $status_counts ) && is_array( $status_counts ) ? $status_counts : array();
$recent_projects = isset( $recent_projects ) && is_array( $recent_projects ) ? $recent_projects : array();
$st_labels       = bina_get_project_status_labels();

bina_render_customer_portal_shell_start(
	array(
		'user'       => $user,
		'urls'       => $urls,
		'logo_url'   => isset( $logo_url ) ? (string) $logo_url : '',
		'help_url'   => isset( $help_url ) ? (string) $help_url : bina_dashboard_resolve_url( 'https://wa.me/966590000474' ),
		'stats'      => $stats,
		'active_nav' => 'dashboard',
	)
);

include get_template_directory() . '/inc/partials/dashboard-customer-home.php';

bina_render_customer_portal_shell_end();
