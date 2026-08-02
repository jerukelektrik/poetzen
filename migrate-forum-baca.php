<?php
/**
 * Standalone Category Migration script to bypass caching and redirects.
 */

// Bootstrap WordPress
$wp_load_path = __DIR__ . '/../../../wp-load.php';
if ( ! file_exists( $wp_load_path ) ) {
	die( 'wp-load.php not found.' );
}
define( 'WP_USE_THEMES', false );
require_once $wp_load_path;

// Basic safety token
if ( ! isset( $_GET['token'] ) || 'sukusastra123' !== $_GET['token'] ) {
	wp_die( 'Access denied. Please provide the correct token.' );
}

$forum_term = get_category_by_slug( 'forum-baca' );

if ( ! $forum_term ) {
	wp_die( 'Kategori "forum-baca" tidak ditemukan.' );
}

$posts = get_posts( array(
	'category'       => $forum_term->term_id,
	'post_type'      => 'post',
	'posts_per_page' => -1,
	'post_status'    => 'any',
) );

if ( empty( $posts ) ) {
	wp_die( 'Tidak ada postingan di kategori "forum-baca". Migrasi mungkin sudah selesai sebelumnya.' );
}

$migrated = array();
foreach ( $posts as $p ) {
	// 1. Change post type to CPT 'berita' (Peristiwa)
	set_post_type( $p->ID, 'berita' );
	
	// 2. Remove term relationships for categories so they don't stay associated with standard categories
	wp_set_object_terms( $p->ID, null, 'category' );
	
	$migrated[] = sprintf( '- ID: %d | Title: <strong>%s</strong> (%s)', $p->ID, esc_html( $p->post_title ), esc_html( $p->post_status ) );
}

$output = '<h2>Migrasi Kategori Forum Baca ke Peristiwa Berhasil!</h2>';
$output .= '<p>Berhasil memindahkan ' . count( $migrated ) . ' postingan dari kategori "forum-baca" ke CPT <strong>Peristiwa</strong> (berita):</p>';
$output .= '<div style="background: #f5f5f5; border: 1px solid #ddd; padding: 15px; border-radius: 6px; font-family: monospace; max-height: 300px; overflow-y: auto;">' . implode( '<br>', $migrated ) . '</div>';

wp_die( $output );
