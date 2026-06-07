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
	$font_family = sukusastra_get_option( 'font_family', 'default' );
	$font_url = 'https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..700;1,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@300;400;500;600;700;800;900&display=swap';
	
	if ( 'modern' === $font_family ) {
		$font_url = 'https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&display=swap';
	} elseif ( 'elegant' === $font_family ) {
		$font_url = 'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700&family=Inter:wght@300;400;500;600;700;800;900&display=swap';
	} elseif ( 'system' === $font_family ) {
		$font_url = ''; // Do not load Google Fonts
	}

	if ( $font_url ) {
		wp_enqueue_style(
			'sukusastra-fonts',
			$font_url,
			array(),
			null
		);
	}

	wp_enqueue_style(
		'sukusastra-theme',
		SUKUSASTRA_URI . '/assets/css/theme.css',
		$font_url ? array( 'sukusastra-fonts' ) : array(),
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

add_action( 'wp_head', 'sukusastra_render_dynamic_styles', 100 );
function sukusastra_render_dynamic_styles(): void {
	$font_family = sukusastra_get_option( 'font_family', 'default' );
	$font_size = sukusastra_get_option( 'font_size', 'medium' );
	$color_scheme = sukusastra_get_option( 'color_scheme', 'crimson' );
	$header_bg_light = sukusastra_get_option( 'header_bg_light', '#ffffff' );
	$header_bg_dark = sukusastra_get_option( 'header_bg_dark', '#262B4E' );
	$footer_bg = sukusastra_get_option( 'footer_bg', '#090d16' );

	echo "<style id='sukusastra-dynamic-styles'>\n";
	
	// Font Family Styles
	if ( 'modern' === $font_family ) {
		echo "  :root {\n";
		echo "    --font-display: 'Outfit', Inter, system-ui, sans-serif !important;\n";
		echo "    --font-serif: 'Inter', system-ui, sans-serif !important;\n";
		echo "    --font-sans: 'Inter', system-ui, sans-serif !important;\n";
		echo "  }\n";
	} elseif ( 'elegant' === $font_family ) {
		echo "  :root {\n";
		echo "    --font-display: 'Merriweather', Georgia, Cambria, serif !important;\n";
		echo "    --font-serif: 'Lora', Georgia, Cambria, serif !important;\n";
		echo "  }\n";
	} elseif ( 'system' === $font_family ) {
		echo "  :root {\n";
		echo "    --font-display: ui-serif, Georgia, Cambria, serif !important;\n";
		echo "    --font-serif: ui-serif, Georgia, Cambria, serif !important;\n";
		echo "    --font-sans: system-ui, -apple-system, sans-serif !important;\n";
		echo "  }\n";
	}

	// Font Size Styles
	if ( 'small' === $font_size ) {
		echo "  html { font-size: 15px !important; }\n";
	} elseif ( 'large' === $font_size ) {
		echo "  html { font-size: 18px !important; }\n";
	} elseif ( 'xlarge' === $font_size ) {
		echo "  html { font-size: 20px !important; }\n";
	}

	// Color Scheme Styles
	$accent = '';
	$accent_dark = '';
	if ( 'emerald' === $color_scheme ) {
		$accent = '#059669';
		$accent_dark = '#34d399';
	} elseif ( 'sapphire' === $color_scheme ) {
		$accent = '#2563eb';
		$accent_dark = '#60a5fa';
	} elseif ( 'amber' === $color_scheme ) {
		$accent = '#d97706';
		$accent_dark = '#fbbf24';
	} elseif ( 'amethyst' === $color_scheme ) {
		$accent = '#7c3aed';
		$accent_dark = '#a78bfa';
	}

	if ( $accent && $accent_dark ) {
		echo "  :root {\n";
		echo "    --color-accent: {$accent} !important;\n";
		echo "    --color-accent-dark: {$accent_dark} !important;\n";
		echo "  }\n";
		echo "  .text-red-750, .text-red-700, .hover\:text-red-700:hover, .group-hover\:text-red-700:hover, .hover\:text-red-800:hover, .text-red-400, .dark\:text-red-400, .text-red-300, .dark\:text-red-300, a:hover, .ss-eyebrow, .ss-info-title, .ss-card-title-link:hover {\n";
		echo "    color: {$accent} !important;\n";
		echo "  }\n";
		echo "  .bg-red-700, .hover\:bg-red-700:hover, .hover\:bg-red-800:hover, .bg-red-500, .dark\:bg-red-500, .dark\:hover:bg-red-400, .ss-button, .ss-button:hover, .author-story-trigger, .bg-amber-400, .text-amber-950 {\n";
		echo "    background-color: {$accent} !important;\n";
		echo "  }\n";
		echo "  .bg-amber-400 {\n";
		echo "    background-color: {$accent} !important;\n";
		echo "  }\n";
		echo "  .text-amber-950 {\n";
		echo "    color: #ffffff !important;\n";
		echo "  }\n";
		echo "  .border-red-750, .border-red-700, .border-red-200, .border-red-200\/50, .border-red-900\/30, :focus-visible {\n";
		echo "    border-color: {$accent} !important;\n";
		echo "  }\n";
		echo "  :focus-visible {\n";
		echo "    outline-color: {$accent} !important;\n";
		echo "  }\n";
		
		// Dark mode overrides
		echo "  .dark .text-red-750, .dark .text-red-700, .dark .hover\:text-red-700:hover, .dark .group-hover\:text-red-700:hover, .dark .hover\:text-red-800:hover, .dark .text-red-400, .dark .dark\:text-red-400, .dark .text-red-300, .dark .dark\:text-red-300, .dark a:hover, .dark .ss-eyebrow, .dark .ss-info-title, .dark .ss-card-title-link:hover {\n";
		echo "    color: {$accent_dark} !important;\n";
		echo "  }\n";
		echo "  .dark .bg-red-700, .dark .hover\:bg-red-700:hover, .dark .hover\:bg-red-800:hover, .dark .bg-red-500, .dark .dark\:bg-red-500, .dark .dark\:hover:bg-red-400, .dark .ss-button, .dark .ss-button:hover, .dark .bg-amber-400 {\n";
		echo "    background-color: {$accent_dark} !important;\n";
		echo "  }\n";
		echo "  .dark .text-amber-950 {\n";
		echo "    color: #0f172a !important;\n";
		echo "  }\n";
		echo "  .dark .border-red-750, .dark .border-red-700, .dark .border-red-200, .dark .border-red-200\/50, .dark .border-red-900\/30, .dark :focus-visible {\n";
		echo "    border-color: {$accent_dark} !important;\n";
		echo "  }\n";
		echo "  .dark :focus-visible {\n";
		echo "    outline-color: {$accent_dark} !important;\n";
		echo "  }\n";
	}

	// Header background light/dark overrides
	if ( $header_bg_light ) {
		echo "  header.sticky {\n";
		echo "    background-color: {$header_bg_light}e6 !important;\n";
		echo "  }\n";
	}
	if ( $header_bg_dark ) {
		echo "  .dark header.sticky {\n";
		echo "    background-color: {$header_bg_dark}e6 !important;\n";
		echo "  }\n";
	}

	// Footer background override
	if ( $footer_bg ) {
		echo "  footer {\n";
		echo "    background-color: {$footer_bg} !important;\n";
		echo "  }\n";
	}

	echo "</style>\n";
}

