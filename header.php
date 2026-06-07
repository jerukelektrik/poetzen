<?php
/**
 * Site header.
 *
 * @package SukuSastra
 */
?><!doctype html>
<html <?php language_attributes(); ?> data-theme="system">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php 
	$header_scripts = sukusastra_get_option( 'header_scripts' );
	if ( $header_scripts ) {
		echo $header_scripts . "\n";
	}
	?>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'min-h-screen bg-slate-50 text-slate-950 dark:bg-[#343B6A] dark:text-zinc-50' ); ?>>
<?php wp_body_open(); ?>
<a class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:bg-white focus:p-3 focus:text-slate-950 dark:focus:bg-zinc-900 dark:focus:text-white" href="#main-content">
	<?php esc_html_e( 'Lewati ke konten', 'sukusastra' ); ?>
</a>

<header class="sticky top-4 z-40 mx-4 mt-4 mb-6 max-w-7xl lg:mx-auto border border-slate-200 bg-white/90 backdrop-blur dark:border-zinc-800/80 dark:bg-[#262B4E]/90 rounded-2xl shadow-sm transition-all">
	<!-- Top Bar -->
	<div class="flex items-center justify-end px-6 py-1.5 border-b border-slate-200/50 bg-slate-50/50 dark:bg-zinc-900/30 dark:border-zinc-800/50 rounded-t-2xl">
		<div class="flex items-center gap-1.5">
			<button class="p-1 rounded-md text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 border-0 bg-transparent cursor-pointer transition-colors" type="button" data-theme-toggle aria-label="<?php esc_attr_e( 'Ubah mode warna', 'sukusastra' ); ?>">
				<!-- Sun Icon (visible in dark mode) -->
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 hidden dark:block">
					<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M9.75 12l-1.5-1.5m10.5 1.5l-1.5-1.5M12 7.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9ZM4.75 12h2.25m10.5 0h2.25M6.25 6.25l1.5 1.5m8.5 8.5l1.5 1.5" />
				</svg>
				<!-- Moon Icon (visible in light mode) -->
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 block dark:hidden">
					<path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
				</svg>
			</button>
		</div>
	</div>

	<div class="ss-container flex min-h-14 items-center justify-between gap-4 py-2">
		<a class="flex items-center no-underline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<!-- Logo for Light Mode -->
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-[120px] w-[120px] -my-6 relative z-10 object-contain logo-light">
			<!-- Logo for Dark Mode -->
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-white.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-[120px] w-[120px] -my-6 relative z-10 object-contain logo-dark">
		</a>

		<!-- Mobile Navigation Toggle -->
		<button class="ss-button-secondary md:hidden" type="button" data-nav-toggle aria-controls="primary-menu" aria-expanded="false">
			<?php esc_html_e( 'Menu', 'sukusastra' ); ?>
		</button>

		<!-- Primary Navigation Menu -->
		<nav id="primary-menu" class="hidden flex-1 items-center justify-center gap-5 md:flex" aria-label="<?php esc_attr_e( 'Menu utama', 'sukusastra' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex flex-col gap-4 ss-nav-text md:flex-row md:items-center md:gap-8',
					'fallback_cb'    => 'sukusastra_primary_menu_fallback',
				)
			);
			?>
		</nav>

		<!-- Action Buttons -->
		<div class="hidden items-center gap-2 md:flex">
			<a class="ss-button" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>">
				<?php esc_html_e( 'Kirim Karya', 'sukusastra' ); ?>
			</a>
		</div>
	</div>
</header>

<main id="main-content">
