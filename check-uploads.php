<?php
/**
 * Diagnostic script to check uploads directory structure on staging.
 */

// Define ABSPATH and load WordPress bootstrap so we can use WordPress functions
// Since the theme is in wp-content/themes/poetzen/, go up 3 levels to reach public root
$wp_load_path = dirname( __DIR__, 3 ) . '/wp-load.php';

if ( file_exists( $wp_load_path ) ) {
	require_once $wp_load_path;
} else {
	echo "Could not find wp-load.php at: " . htmlspecialchars( $wp_load_path ) . "\n";
	exit;
}

header( 'Content-Type: text/plain; charset=utf-8' );

$upload_dir = wp_upload_dir();
echo "Uploads Base Directory (DB/WP Config): " . $upload_dir['basedir'] . "\n";
echo "Uploads Base URL: " . $upload_dir['baseurl'] . "\n";

$target_dir = $upload_dir['basedir'];

if ( ! is_dir( $target_dir ) ) {
	echo "Uploads directory does not exist: " . $target_dir . "\n";
	exit;
}

echo "\n--- Scanning Uploads Directory (up to 3 levels deep) ---\n";

function scan_dir_recursive( $dir, $prefix = '', $max_depth = 3, $current_depth = 0 ) {
	if ( $current_depth >= $max_depth ) {
		return;
	}

	$files = scandir( $dir );
	if ( ! is_array( $files ) ) {
		return;
	}

	foreach ( $files as $file ) {
		if ( $file === '.' || $file === '..' ) {
			continue;
		}

		$full_path = $dir . '/' . $file;
		if ( is_dir( $full_path ) ) {
			echo $prefix . "[DIR] " . $file . "\n";
			scan_dir_recursive( $full_path, $prefix . "  ", $max_depth, $current_depth + 1 );
		} else {
			$size = filesize( $full_path );
			echo $prefix . "[FILE] " . $file . " (" . number_format( $size ) . " bytes)\n";
		}
	}
}

scan_dir_recursive( $target_dir );
