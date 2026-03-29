<?php
/**
 * Helpers: project extra JSON, display normalization, attachment meta keys.
 *
 * @package bina-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Encode extra array for post meta (UTF-8 Arabic, no \uXXXX escapes).
 *
 * @param array<string,mixed> $extra Extra fields.
 * @return string
 */
function bina_project_extra_to_json( array $extra ) {
	return wp_json_encode( $extra, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
}

/**
 * Decode _bina_extra meta and normalize string values (fixes legacy corrupted unicode).
 *
 * @param string|null $raw Meta string.
 * @return array<string,mixed>
 */
function bina_project_extra_from_meta( $raw ) {
	$extra = array();
	if ( ! is_string( $raw ) || $raw === '' ) {
		return $extra;
	}
	$decoded = json_decode( $raw, true );
	if ( ! is_array( $decoded ) ) {
		return $extra;
	}
	return bina_project_normalize_extra_array( $decoded );
}

/**
 * @param array<string,mixed> $arr Input.
 * @return array<string,mixed>
 */
function bina_project_normalize_extra_array( array $arr ) {
	foreach ( $arr as $k => $v ) {
		if ( is_string( $v ) ) {
			$arr[ $k ] = bina_normalize_corrupted_unicode_string( $v );
		}
	}
	return $arr;
}

/**
 * Fix display when DB contains literal "u0644u0627" instead of Arabic (legacy / double encoding).
 *
 * @param string $str Raw string.
 * @return string
 */
function bina_normalize_corrupted_unicode_string( $str ) {
	if ( ! is_string( $str ) || $str === '' ) {
		return $str;
	}
	// Already contains Arabic letters etc.
	if ( preg_match( '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}]/u', $str ) ) {
		return $str;
	}
	$compact = preg_replace( '/\s+/u', '', $str );
	if ( preg_match( '/^u[0-9a-fA-F]{4}(u[0-9a-fA-F]{4})*$/', $compact ) ) {
		$json = '"' . preg_replace( '/u([0-9a-fA-F]{4})/', '\\u$1', $compact ) . '"';
		$decoded = json_decode( $json );
		if ( is_string( $decoded ) && $decoded !== '' ) {
			return $decoded;
		}
	}
	return $str;
}

/**
 * Attachment IDs stored for engineering plans / site photos.
 *
 * @param int    $post_id Project post ID.
 * @param string $key     plans|site_photos.
 * @return int[]
 */
function bina_get_project_attachment_ids( $post_id, $key ) {
	$post_id = (int) $post_id;
	$meta_key = ( $key === 'site_photos' ) ? '_bina_site_photos_attachment_ids' : '_bina_plans_attachment_ids';
	$raw      = get_post_meta( $post_id, $meta_key, true );
	if ( $raw === '' || $raw === false ) {
		return array();
	}
	if ( is_array( $raw ) ) {
		$ids = array_map( 'absint', $raw );
	} elseif ( is_string( $raw ) ) {
		$decoded = json_decode( $raw, true );
		$ids     = is_array( $decoded ) ? array_map( 'absint', $decoded ) : array();
	} else {
		$ids = array();
	}
	return array_values( array_filter( array_unique( $ids ) ) );
}

/**
 * @param int   $post_id Post ID.
 * @param int[] $ids     Attachment IDs.
 * @param string $key    plans|site_photos.
 * @return void
 */
function bina_set_project_attachment_ids( $post_id, array $ids, $key ) {
	$post_id = (int) $post_id;
	$ids     = array_values( array_filter( array_map( 'absint', $ids ) ) );
	$meta_key = ( $key === 'site_photos' ) ? '_bina_site_photos_attachment_ids' : '_bina_plans_attachment_ids';
	update_post_meta( $post_id, $meta_key, $ids );
}

/**
 * Service provider user ID assigned to this project (for messaging / workflow).
 *
 * @param int $post_id Project post ID.
 * @return int 0 if none.
 */
function bina_get_project_assigned_provider_id( $post_id ) {
	$post_id = (int) $post_id;
	if ( $post_id < 1 ) {
		return 0;
	}
	$v = get_post_meta( $post_id, '_bina_assigned_provider_id', true );
	if ( $v === '' || $v === false ) {
		return 0;
	}
	return absint( $v );
}

/**
 * @param int $post_id Project post ID.
 * @param int $user_id WordPress user ID (0 clears).
 * @return void
 */
function bina_set_project_assigned_provider_id( $post_id, $user_id ) {
	$post_id = (int) $post_id;
	$user_id = (int) $user_id;
	if ( $post_id < 1 ) {
		return;
	}
	if ( $user_id < 1 ) {
		delete_post_meta( $post_id, '_bina_assigned_provider_id' );
		return;
	}
	update_post_meta( $post_id, '_bina_assigned_provider_id', $user_id );
}
