<?php
/**
 * Custom post type: bina_project (customer-owned projects).
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registered project category labels (Arabic). Filter: bina_project_categories.
 *
 * @return string[]
 */
function bina_get_project_categories() {
	$defaults = array(
		__( 'الترميم العام', 'bina' ),
		__( 'أعمال الإضاءة', 'bina' ),
		__( 'أعمال العزل (حراري – مائي – صوتي)', 'bina' ),
		__( 'تنسيق وإنشاء الحدائق', 'bina' ),
		__( 'التشطيبات الداخلية والديكور', 'bina' ),
		__( 'تركيب الشاشات العملاقة والسينما المنزلية', 'bina' ),
		__( 'تجهيز غرف الاجتماعات وأنظمة الصوتيات', 'bina' ),
		__( 'بناء الملاحق', 'bina' ),
		__( 'خدمات عامة / غير مصنفة', 'bina' ),
		__( 'بناء المستودعات والمنشآت الصناعية', 'bina' ),
		__( 'الاستشارات الهندسية والتصميم والتخطيط', 'bina' ),
		__( 'الإشراف الهندسي والتنفيذ بالموقع', 'bina' ),
		__( 'بناء الفلل والمشاريع الفاخرة', 'bina' ),
		__( 'بناء العمائر', 'bina' ),
		__( 'الديكورات والتصميم الداخلي', 'bina' ),
		__( 'أعمال الكهرباء الكاملة', 'bina' ),
		__( 'أعمال السباكة', 'bina' ),
		__( 'أعمال الهدم', 'bina' ),
	);
	return apply_filters( 'bina_project_categories', $defaults );
}

/**
 * Reminder timeline options (stored in meta).
 *
 * @return string[]
 */
function bina_get_project_reminder_options() {
	$defaults = array(
		__( 'أقل من أسبوع', 'bina' ),
		__( 'خلال أسبوعين', 'bina' ),
		__( 'خلال شهر', 'bina' ),
		__( 'أكثر من شهر', 'bina' ),
	);
	return apply_filters( 'bina_project_reminder_options', $defaults );
}

/**
 * Internal project status for badges (not WP post_status).
 *
 * @return string[]
 */
function bina_get_project_status_labels() {
	return array(
		'open'        => __( 'مفتوح للعروض', 'bina' ),
		'selected'    => __( 'تم اختيار مزود', 'bina' ),
		'in_progress' => __( 'قيد التنفيذ', 'bina' ),
		'completed'   => __( 'مكتمل', 'bina' ),
	);
}

/**
 * Register CPT.
 *
 * @return void
 */
function bina_register_project_cpt() {
	register_post_type(
		'bina_project',
		array(
			'labels'              => array(
				'name'          => __( 'Projects', 'bina' ),
				'singular_name' => __( 'Project', 'bina' ),
				'menu_name'     => __( 'مشاريع العملاء', 'bina' ),
				'add_new'       => __( 'إضافة مشروع', 'bina' ),
				'add_new_item'  => __( 'إضافة مشروع جديد', 'bina' ),
				'edit_item'     => __( 'تعديل المشروع', 'bina' ),
			),
			'public'              => false,
			'show_ui'             => true,
			// Hide default CPT menu: we use a custom admin screen instead.
			'show_in_menu'        => false,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-hammer',
			'capability_type'     => array( 'bina_project', 'bina_projects' ),
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'author' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
		)
	);
}

add_action( 'init', 'bina_register_project_cpt', 10 );

/**
 * Grant CPT capabilities to administrator and customer roles.
 *
 * @return void
 */
function bina_register_project_caps_for_roles() {
	$post_type_object = get_post_type_object( 'bina_project' );
	if ( ! $post_type_object || empty( $post_type_object->cap ) ) {
		return;
	}

	$cap_obj = $post_type_object->cap;
	$all_caps = array_filter( array_unique( array_values( (array) $cap_obj ) ) );

	$admin = get_role( 'administrator' );
	if ( $admin ) {
		foreach ( $all_caps as $c ) {
			$admin->add_cap( $c );
		}
	}

	$customer = get_role( 'customer' );
	if ( $customer ) {
		$keys = array(
			'create_posts',
			'edit_posts',
			'edit_post',
			'read_post',
			'publish_posts',
			'delete_post',
			'edit_published_posts',
		);
		foreach ( $keys as $key ) {
			if ( isset( $cap_obj->$key ) && $cap_obj->$key ) {
				$customer->add_cap( $cap_obj->$key );
			}
		}
	}
}

add_action( 'init', 'bina_register_project_caps_for_roles', 15 );

/**
 * Populate dashboard counters from real project counts.
 *
 * @param array<string,int> $stats Stats.
 * @param int               $user_id User ID.
 * @return array<string,int>
 */
function bina_customer_dashboard_stats_from_projects( $stats, $user_id ) {
	$user_id = (int) $user_id;
	if ( $user_id < 1 ) {
		return $stats;
	}

	$q = new WP_Query(
		array(
			'post_type'              => 'bina_project',
			'author'                 => $user_id,
			'post_status'            => array( 'publish', 'pending', 'draft' ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	$total = (int) $q->post_count;
	$stats['total_projects']    = $total;
	$stats['my_projects_badge'] = $total;

	$active = 0;
	foreach ( $q->posts as $pid ) {
		$st = bina_get_project_status_meta( (int) $pid );
		if ( $st === 'open' || $st === 'selected' || $st === 'in_progress' ) {
			++$active;
		}
	}
	$stats['active_projects'] = $active;

	return $stats;
}

add_filter( 'bina_customer_dashboard_stats', 'bina_customer_dashboard_stats_from_projects', 10, 2 );

/**
 * Normalize internal project status meta.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function bina_get_project_status_meta( $post_id ) {
	$st = (string) get_post_meta( (int) $post_id, '_bina_project_status', true );
	if ( $st === '' ) {
		return 'open';
	}

	// Backward compatibility: old keys -> new keys.
	switch ( $st ) {
		case 'pending':
			return 'open';
		case 'active':
			return 'in_progress';
		case 'done':
			return 'completed';
		case 'closed':
			return 'completed';
		default:
			return $st;
	}
}

/**
 * Count customer projects by internal status key.
 *
 * @param int $user_id User ID.
 * @return array<string,int>
 */
function bina_get_customer_project_status_counts( $user_id ) {
	$user_id = (int) $user_id;
	$labels  = bina_get_project_status_labels();
	$counts  = array_fill_keys( array_keys( $labels ), 0 );

	if ( $user_id < 1 ) {
		return $counts;
	}

	$q = new WP_Query(
		array(
			'post_type'              => 'bina_project',
			'author'                 => $user_id,
			'post_status'            => array( 'publish', 'pending', 'draft' ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'update_post_term_cache' => false,
		)
	);

	foreach ( $q->posts as $pid ) {
		$st = bina_get_project_status_meta( (int) $pid );
		if ( isset( $counts[ $st ] ) ) {
			++$counts[ $st ];
		}
	}

	return $counts;
}

/**
 * Customer's projects ordered by last modified (for dashboard activity).
 *
 * @param int $user_id User ID.
 * @param int $limit Max posts.
 * @return WP_Post[]
 */
function bina_get_customer_recent_projects( $user_id, $limit = 5 ) {
	$user_id = (int) $user_id;
	$limit   = max( 1, (int) $limit );
	if ( $user_id < 1 ) {
		return array();
	}

	return get_posts(
		array(
			'post_type'              => 'bina_project',
			'author'                 => $user_id,
			'post_status'            => array( 'publish', 'pending', 'draft' ),
			'posts_per_page'         => $limit,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
		)
	);
}

/**
 * Meta query: projects open for providers (not own, published, status open/unset).
 *
 * @param int $user_id Current user (excluded as author).
 * @return array<string,mixed>
 */
function bina_get_browseable_projects_meta_query_for_provider( $user_id ) {
	// Marketplace-visible projects:
	// - status open/unset (also supports legacy pending)
	// - not assigned to any provider yet
	// - not locked by accepted proposal
	return array(
		'relation' => 'AND',
		array(
			'relation' => 'OR',
			array(
				'key'     => '_bina_project_status',
				'value'   => 'open',
				'compare' => '=',
			),
			array(
				'key'     => '_bina_project_status',
				'value'   => 'pending',
				'compare' => '=',
			),
			array(
				'key'     => '_bina_project_status',
				'compare' => 'NOT EXISTS',
			),
		),
		array(
			'relation' => 'OR',
			array(
				'key'     => '_bina_assigned_provider_id',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => '_bina_assigned_provider_id',
				'value'   => 0,
				'compare' => '=',
				'type'    => 'NUMERIC',
			),
		),
		array(
			'relation' => 'OR',
			array(
				'key'     => '_bina_market_locked',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => '_bina_market_locked',
				'value'   => '0',
				'compare' => '=',
			),
		),
	);
}

/**
 * @param int $user_id Provider user ID.
 * @return int
 */
function bina_count_browseable_projects_for_provider( $user_id ) {
	$user_id = (int) $user_id;
	if ( $user_id < 1 ) {
		return 0;
	}

	$q = new WP_Query(
		array(
			'post_type'              => 'bina_project',
			'post_status'            => 'publish',
			'author__not_in'         => array( $user_id ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'meta_query'             => bina_get_browseable_projects_meta_query_for_provider( $user_id ),
		)
	);

	return (int) $q->post_count;
}

/**
 * Recent projects available on the marketplace for a provider.
 *
 * @param int $user_id Provider user ID.
 * @param int $limit Max posts.
 * @return WP_Post[]
 */
function bina_get_recent_browseable_projects_for_provider( $user_id, $limit = 5 ) {
	$user_id = (int) $user_id;
	$limit   = max( 1, (int) $limit );
	if ( $user_id < 1 ) {
		return array();
	}

	return get_posts(
		array(
			'post_type'              => 'bina_project',
			'post_status'            => 'publish',
			'author__not_in'         => array( $user_id ),
			'posts_per_page'         => $limit,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'meta_query'             => bina_get_browseable_projects_meta_query_for_provider( $user_id ),
			'update_post_meta_cache' => true,
		)
	);
}

/**
 * Paginated marketplace query for service provider "browse" screen.
 *
 * @param int $user_id   Provider user ID.
 * @param int $per_page  Posts per page.
 * @param int $paged     Page number.
 * @return WP_Query
 */
function bina_query_browseable_projects_for_provider( $user_id, $per_page = 12, $paged = 1 ) {
	$user_id   = (int) $user_id;
	$per_page  = max( 1, (int) $per_page );
	$paged     = max( 1, (int) $paged );
	if ( $user_id < 1 ) {
		return new WP_Query( array( 'post__in' => array( 0 ) ) );
	}

	return new WP_Query(
		array(
			'post_type'              => 'bina_project',
			'post_status'            => 'publish',
			'author__not_in'         => array( $user_id ),
			'posts_per_page'         => $per_page,
			'paged'                  => $paged,
			'orderby'                => 'modified',
			'order'                  => 'DESC',
			'meta_query'             => bina_get_browseable_projects_meta_query_for_provider( $user_id ),
			'update_post_meta_cache' => true,
		)
	);
}

/**
 * Status breakdown for browseable pool (provider dashboard).
 *
 * @param int $user_id Provider user ID.
 * @return array<string,int>
 */
function bina_get_browseable_project_status_counts_for_provider( $user_id ) {
	$labels = bina_get_project_status_labels();
	$counts = array_fill_keys( array_keys( $labels ), 0 );

	$user_id = (int) $user_id;
	if ( $user_id < 1 ) {
		return $counts;
	}

	$q = new WP_Query(
		array(
			'post_type'              => 'bina_project',
			'post_status'            => 'publish',
			'author__not_in'         => array( $user_id ),
			'posts_per_page'         => -1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true,
			'meta_query'             => bina_get_browseable_projects_meta_query_for_provider( $user_id ),
		)
	);

	foreach ( $q->posts as $pid ) {
		$st = bina_get_project_status_meta( (int) $pid );
		if ( isset( $counts[ $st ] ) ) {
			++$counts[ $st ];
		}
	}

	return $counts;
}

/**
 * Service provider dashboard: real browse count from CPT.
 *
 * @param array<string,int|float> $stats Stats.
 * @param int                     $user_id User ID.
 * @return array<string,int|float>
 */
function bina_service_provider_dashboard_stats_from_projects( $stats, $user_id ) {
	$user_id = (int) $user_id;
	if ( $user_id < 1 ) {
		return $stats;
	}

	$stats['browse_projects_count'] = bina_count_browseable_projects_for_provider( $user_id );

	return $stats;
}

add_filter( 'bina_service_provider_dashboard_stats', 'bina_service_provider_dashboard_stats_from_projects', 10, 2 );
