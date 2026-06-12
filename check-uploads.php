<?php
/**
 * Diagnostic script to check uploads directory structure and attachment records on staging.
 */

$wp_load_path = dirname( __DIR__, 3 ) . '/wp-load.php';

if ( file_exists( $wp_load_path ) ) {
	require_once $wp_load_path;
} else {
	echo "Could not find wp-load.php at: " . htmlspecialchars( $wp_load_path ) . "\n";
	exit;
}

header( 'Content-Type: text/plain; charset=utf-8' );

echo "=== WordPress Environment ===\n";
echo "Site URL: " . get_site_url() . "\n";
echo "Uploads Base Directory: " . wp_upload_dir()['basedir'] . "\n";

echo "\n=== Database Attachment Diagnostics ===\n";
global $wpdb;

// Count total attachments
$total_attachments = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'attachment'" );
echo "Total Attachments in Database: " . $total_attachments . "\n";

// Count attachments with _wp_attached_file meta
$total_attached_files = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file'" );
echo "Total Attachments with _wp_attached_file meta: " . $total_attached_files . "\n";

// List last 10 attachments
echo "\nLast 10 Attachments:\n";
$attachments = $wpdb->get_results( "
	SELECT p.ID, p.post_title, p.post_mime_type, pm.meta_value as attached_file 
	FROM $wpdb->posts p
	LEFT JOIN $wpdb->postmeta pm ON p.ID = pm.post_id AND pm.meta_key = '_wp_attached_file'
	WHERE p.post_type = 'attachment'
	ORDER BY p.ID DESC
	LIMIT 10
" );

if ( ! empty( $attachments ) ) {
	foreach ( $attachments as $att ) {
		echo "ID: {$att->ID} | Title: {$att->post_title} | Mime: {$att->post_mime_type} | File: {$att->attached_file}\n";
	}
} else {
	echo "No attachments found.\n";
}

// Check some specific image paths from JSON to see if they exist on disk and if they are in DB
echo "\n=== Specific Path Checks ===\n";
$paths_to_check = array(
	"2025/09/Sehari-di-Parade-Sastra-JBS-scaled-1.jpg",
	"2026/06/diva-pantura-1.jpg",
	"2026/01/Kitab-Jalan-Teh-Utuh-ISBN.png",
	"2025/03/Resensi-Buku-Keluarga-dan-Silsilah-Suka-Duka-Ismail-Basbeth.jpg",
	"2026/06/screenshot.png"
);

$basedir = wp_upload_dir()['basedir'];
foreach ( $paths_to_check as $rel_path ) {
	$full_path = $basedir . '/' . $rel_path;
	$exists = file_exists( $full_path ) ? "YES" : "NO";
	
	// Query if registered
	$db_id = $wpdb->get_var( $wpdb->prepare(
		"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
		$rel_path
	) );
	$registered = $db_id ? "YES (ID: $db_id)" : "NO";
	
	echo "Path: $rel_path\n";
	echo "  File Exists on Disk: $exists\n";
	echo "  Registered in DB:    $registered\n";
}
