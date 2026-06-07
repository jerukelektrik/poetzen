<?php
/**
 * Redirect handling.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'template_redirect', 'sukusastra_maybe_redirect_singular', 0 );
function sukusastra_maybe_redirect_singular(): void {
	if ( ! is_singular() ) {
		return;
	}

	$post_id = get_queried_object_id();
	$target = sukusastra_get_meta( $post_id, '_ss_redirect_target' );
	if ( '' === $target ) {
		return;
	}

	$validated = wp_validate_redirect( esc_url_raw( $target ), '' );
	if ( '' === $validated ) {
		return;
	}

	$type = sukusastra_get_meta( $post_id, '_ss_redirect_type', '301' );
	$status = '302' === $type ? 302 : 301;
	wp_safe_redirect( $validated, $status );
	exit;
}
