<?php
/**
 * Theme bootstrap for Poetzen.
 *
 * @package Poetzen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SUKUSASTRA_VERSION', '0.1.0' );
define( 'SUKUSASTRA_DIR', get_template_directory() );
define( 'SUKUSASTRA_URI', get_template_directory_uri() );

$sukusastra_files = array(
	'inc/helpers.php',
	'inc/event-state.php',
	'inc/setup.php',
	'inc/assets.php',
	'inc/post-types.php',
	'inc/metaboxes.php',
	'inc/seo.php',
	'inc/redirects.php',
	'inc/queries.php',
	'inc/sample-content.php',
	'inc/options.php',
	'inc/banners.php',
	'inc/importer.php',
	'inc/webp-uploads.php',
	'inc/sitemap.php',
);

foreach ( $sukusastra_files as $sukusastra_file ) {
	require_once SUKUSASTRA_DIR . '/' . $sukusastra_file;
}
