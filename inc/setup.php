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

	add_image_size( 'sukusastra-card', 720, 480, true );
	add_image_size( 'sukusastra-cover', 520, 780, true );
	add_image_size( 'sukusastra-hero', 1280, 720, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'sukusastra' ),
			'footer'  => __( 'Footer Menu', 'sukusastra' ),
		)
	);
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

