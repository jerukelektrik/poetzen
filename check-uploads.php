<?php
/**
 * Diagnostic script to check uploads directory structure, attachment records, and test image registration on staging.
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

// Require migration file to get access to registration function
require_once __DIR__ . '/inc/migration.php';

// Test image registration manually on diva-pantura-1.jpg
echo "\n=== Testing Image Registration Directly ===\n";
$test_rel_path = "2026/06/diva-pantura-1.jpg";
$basedir = wp_upload_dir()['basedir'];
$test_file_path = $basedir . '/' . $test_rel_path;

echo "Test File Path: $test_file_path\n";
if ( ! file_exists( $test_file_path ) ) {
	echo "Error: Test file does not exist on disk!\n";
} else {
	echo "Test file exists on disk. Proceeding with registration...\n";
	
	$filename = basename( $test_file_path );
	$wp_filetype = wp_check_filetype( $filename, null );
	echo "Mime Type: " . $wp_filetype['type'] . "\n";
	
	$attachment  = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $test_file_path, 0 );

	if ( is_wp_error( $attach_id ) ) {
		echo "Registration Failed with WP_Error: " . $attach_id->get_error_message() . "\n";
	} elseif ( $attach_id === 0 || empty( $attach_id ) ) {
		echo "Registration Failed: wp_insert_attachment returned 0 or empty.\n";
	} else {
		echo "wp_insert_attachment succeeded! ID: $attach_id\n";
		
		// Let's test generating metadata safely
		require_once ABSPATH . 'wp-admin/includes/image.php';
		echo "image.php loaded. Checking if image libraries exist...\n";
		if ( ! function_exists('gd_info') ) {
			echo "GD library is NOT enabled!\n";
		} else {
			echo "GD library is enabled.\n";
		}
		if ( ! class_exists('Imagick') ) {
			echo "Imagick extension is NOT enabled!\n";
		} else {
			echo "Imagick extension is enabled.\n";
		}

		try {
			echo "Attempting to generate metadata...\n";
			$attach_data = wp_generate_attachment_metadata( $attach_id, $test_file_path );
			if ( empty( $attach_data ) ) {
				echo "Warning: wp_generate_attachment_metadata returned empty data.\n";
			} else {
				echo "wp_generate_attachment_metadata succeeded!\n";
				print_r( $attach_data );
			}
		} catch (Throwable $e) {
			echo "Fatal Error caught during metadata generation: " . $e->getMessage() . "\n";
			$attach_data = array();
		}
		
		wp_update_attachment_metadata( $attach_id, $attach_data );
		update_post_meta( $attach_id, '_wp_attached_file', $test_rel_path );
		echo "Registration test completed successfully!\n";
	}
}
