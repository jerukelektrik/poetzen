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

$film_term = get_category_by_slug( 'film' );
$esai_term = get_category_by_slug( 'esai' );

if ( ! $film_term || ! $esai_term ) {
	wp_die( 'Kategori "film" atau "esai" tidak ditemukan.' );
}

$posts = get_posts( array(
	'category'       => $film_term->term_id,
	'post_type'      => 'post',
	'posts_per_page' => -1,
	'post_status'    => 'any',
) );

if ( empty( $posts ) ) {
	wp_die( 'Tidak ada postingan di kategori "film". Migrasi mungkin sudah selesai sebelumnya.' );
}

$migrated = array();
foreach ( $posts as $p ) {
	$current_cats = wp_get_post_categories( $p->ID );
	
	// Remove film and add esai
	$new_cats = array_diff( $current_cats, array( $film_term->term_id ) );
	$new_cats[] = $esai_term->term_id;
	$new_cats = array_unique( $new_cats );
	
	wp_set_post_categories( $p->ID, $new_cats );
	
	$migrated[] = sprintf( '- ID: %d | Title: <strong>%s</strong>', $p->ID, esc_html( $p->post_title ) );
}

$output = '<h2>Migrasi Kategori Film Berhasil!</h2>';
$output .= '<p>Berhasil memindahkan ' . count( $migrated ) . ' postingan dari kategori "film" ke "esai":</p>';
$output .= '<div style="background: #f5f5f5; border: 1px solid #ddd; padding: 15px; border-radius: 6px; font-family: monospace; max-height: 300px; overflow-y: auto;">' . implode( '<br>', $migrated ) . '</div>';

wp_die( $output );
