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

	// Google Search Console Verification
	$gsc_id = sukusastra_get_option( 'gsc_id' );
	if ( $gsc_id ) {
		printf( '<meta name="google-site-verification" content="%s">' . "\n", esc_attr( $gsc_id ) );
	}

	// Google Analytics
	$ga_id = sukusastra_get_option( 'ga_id' );
	if ( $ga_id ) {
		?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', '<?php echo esc_attr( $ga_id ); ?>');
		</script>
		<?php
	}

	// Google Tag Manager (Head)
	$gtm_id = sukusastra_get_option( 'gtm_id' );
	if ( $gtm_id ) {
		?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo esc_attr( $gtm_id ); ?>');</script>
		<!-- End Google Tag Manager -->
		<?php
	}

	// Meta Pixel
	$meta_pixel_id = sukusastra_get_option( 'meta_pixel_id' );
	if ( $meta_pixel_id ) {
		?>
		<!-- Meta Pixel Code -->
		<script>
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '<?php echo esc_attr( $meta_pixel_id ); ?>');
		fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
		src="https://www.facebook.com/tr?id=<?php echo esc_attr( $meta_pixel_id ); ?>&ev=PageView&noscript=1"
		/></noscript>
		<!-- End Meta Pixel Code -->
		<?php
	}
	?>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'min-h-screen bg-slate-50 text-slate-950 dark:bg-[#343B6A] dark:text-zinc-50' ); ?>>
<?php wp_body_open(); ?>
<?php
$gtm_id = sukusastra_get_option( 'gtm_id' );
if ( $gtm_id ) : ?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>
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
			<?php 
			$logo_light = sukusastra_get_option( 'logo_light', get_template_directory_uri() . '/assets/images/logo.svg' );
			?>
			<img src="<?php echo esc_url( $logo_light ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-[120px] w-[120px] -my-6 relative z-10 object-contain logo-light">
			<!-- Logo for Dark Mode -->
			<?php 
			$logo_dark = sukusastra_get_option( 'logo_dark', get_template_directory_uri() . '/assets/images/logo-white.svg' );
			?>
			<img src="<?php echo esc_url( $logo_dark ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-[120px] w-[120px] -my-6 relative z-10 object-contain logo-dark">
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
			<a class="ss-button-kirim" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>">
				<svg class="w-4 h-4 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h3M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h7M14 3l5 5" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M14 3v5h5" />
					<!-- Sparkle 1 -->
					<path d="M19 13.5c0 .8.6 1.5 1.5 1.5c-.9 0-1.5.7-1.5 1.5c0-.8-.6-1.5-1.5-1.5c.9 0 1.5-.7 1.5-1.5Z" fill="currentColor" stroke="none" />
					<!-- Sparkle 2 -->
					<path d="M21.5 17.5c0 .5.4 1 1 1c-.6 0-1 .5-1 1c0-.5-.4-1-1-1c.6 0 1-.5 1-1Z" fill="currentColor" stroke="none" />
				</svg>
				<span><?php esc_html_e( 'Kirim Karya', 'sukusastra' ); ?></span>
			</a>
		</div>


	</div>
</header>

<main id="main-content">
