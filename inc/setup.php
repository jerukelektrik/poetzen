<?php
/**
 * Theme setup.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'sukusastra_setup' );
function sukusastra_setup(): void {
	load_theme_textdomain( 'sukusastra', SUKUSASTRA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'automatic-feed-links' );

	// Custom Image Sizes for Cards, Covers, and Hero (3:2, 2:3, and 16:9 aspect ratios)
	add_image_size( 'sukusastra-card-xs', 240, 160, true ); // 3:2 aspect ratio (mobile/xs cards)
	add_image_size( 'article-card', 360, 240, true );        // 3:2 aspect ratio (standard article card)
	add_image_size( 'sukusastra-card-sm', 360, 240, true ); // 3:2 aspect ratio alias
	add_image_size( 'sukusastra-card-md', 480, 320, true ); // 3:2 aspect ratio (medium card)
	add_image_size( 'sukusastra-card', 720, 480, true );    // 3:2 aspect ratio (large/retina card)

	add_image_size( 'sukusastra-cover-sm', 260, 390, true ); // 2:3 aspect ratio (book cover small)
	add_image_size( 'sukusastra-cover', 520, 780, true );    // 2:3 aspect ratio (book cover standard)
	add_image_size( 'sukusastra-hero', 1280, 720, true );   // 16:9 aspect ratio (hero banner)

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'sukusastra' ),
			'footer'  => __( 'Footer Menu', 'sukusastra' ),
		)
	);
}

/**
 * Optimize default sizes attribute for sukusastra image sizes.
 */
add_filter( 'wp_calculate_image_sizes', 'sukusastra_adjust_image_sizes_attr', 10, 5 );
function sukusastra_adjust_image_sizes_attr( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
	if ( is_admin() ) {
		return $sizes;
	}

	$size_name = is_array( $size ) ? '' : (string) $size;

	if ( in_array( $size_name, array( 'article-card', 'sukusastra-card-sm', 'sukusastra-card-xs', 'sukusastra-card-md', 'sukusastra-card' ), true ) ) {
		return '(max-width: 640px) 75vw, (max-width: 768px) 45vw, (max-width: 1024px) 30vw, 360px';
	}

	if ( in_array( $size_name, array( 'sukusastra-cover', 'sukusastra-cover-sm' ), true ) ) {
		return '(max-width: 640px) 72vw, (max-width: 1024px) 25vw, 260px';
	}

	return $sizes;
}

/**
 * Filter default image attributes to enforce decoding="async".
 */
add_filter( 'wp_get_attachment_image_attributes', 'sukusastra_optimize_image_attributes', 10, 3 );
function sukusastra_optimize_image_attributes( $attr, $attachment, $size ) {
	if ( ! isset( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	return $attr;
}

add_filter( 'excerpt_length', 'sukusastra_excerpt_length' );
function sukusastra_excerpt_length(): int {
	return 28;
}

add_filter( 'excerpt_more', 'sukusastra_excerpt_more' );
function sukusastra_excerpt_more(): string {
	return '&hellip;';
}

add_action( 'wp_head', 'sukusastra_track_post_views' );
function sukusastra_track_post_views(): void {
	if ( is_singular( array( 'post', 'review_buku', 'berita', 'event' ) ) ) {
		$post_id = get_the_ID();
		$views   = get_post_meta( $post_id, '_ss_post_views', true );
		if ( '' === $views ) {
			update_post_meta( $post_id, '_ss_post_views', 1 );
		} else {
			update_post_meta( $post_id, '_ss_post_views', (int) $views + 1 );
		}
	}
}

add_action( 'save_post', 'sukusastra_init_post_views', 10, 2 );
function sukusastra_init_post_views( $post_id, $post ): void {
	if ( ! $post || is_wp_error( $post ) ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( in_array( $post->post_type, array( 'post', 'review_buku', 'berita', 'event' ), true ) ) {
		$views = get_post_meta( $post_id, '_ss_post_views', true );
		if ( '' === $views ) {
			update_post_meta( $post_id, '_ss_post_views', 0 );
		}
	}
}

