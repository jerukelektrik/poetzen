<?php
/**
 * Asset loading.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'sukusastra_enqueue_assets' );
function sukusastra_enqueue_assets(): void {
	wp_enqueue_style(
		'sukusastra-fonts',
		'https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..700;1,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@300;400;500;600;700;800;900&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'sukusastra-theme',
		SUKUSASTRA_URI . '/assets/css/theme.css',
		array( 'sukusastra-fonts' ),
		SUKUSASTRA_VERSION
	);

	wp_enqueue_script(
		'sukusastra-theme-toggle',
		SUKUSASTRA_URI . '/assets/js/theme-toggle.js',
		array(),
		SUKUSASTRA_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => false )
	);

	wp_enqueue_script(
		'sukusastra-navigation',
		SUKUSASTRA_URI . '/assets/js/navigation.js',
		array(),
		SUKUSASTRA_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
}

add_action( 'admin_enqueue_scripts', 'sukusastra_admin_assets' );
function sukusastra_admin_assets(): void {
	global $pagenow;
	if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
		wp_enqueue_media();
	}
}

