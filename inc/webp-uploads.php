<?php
/**
 * Auto convert uploaded images to WebP natively.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wp_handle_upload', 'sukusastra_convert_upload_to_webp' );
function sukusastra_convert_upload_to_webp( array $upload ): array {
	if ( 'image/jpeg' === $upload['type'] || 'image/png' === $upload['type'] ) {
		$file_path = $upload['file'];

		// Check if PHP extensions for image editing are available
		$image_editor = wp_get_image_editor( $file_path );
		if ( ! is_wp_error( $image_editor ) ) {
			$path_info = pathinfo( $file_path );
			$new_file_path = $path_info['dirname'] . '/' . $path_info['filename'] . '.webp';

			// Save the image as WebP with 82% quality (good balance of quality/size)
			$image_editor->set_quality( 82 );
			$saved = $image_editor->save( $new_file_path, 'image/webp' );

			if ( ! is_wp_error( $saved ) ) {
				// Delete the original JPG/PNG file to save disk space
				unlink( $file_path );

				// Update upload data so WordPress handles the WebP version
				$upload['file'] = $new_file_path;
				$upload['url']  = str_replace( '.' . $path_info['extension'], '.webp', $upload['url'] );
				$upload['type'] = 'image/webp';
			}
		}
	}
	return $upload;
}
