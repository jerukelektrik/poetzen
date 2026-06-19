<?php
/**
 * Site header.
 *
 * @package SukuSastra
 */
?><!doctype html>
<html <?php language_attributes(); ?> data-theme="light">
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
$show_mobile_capsules = false;
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
	<!-- Top Bar (Hidden on mobile) -->
	<div class="hidden md:flex items-center justify-end px-6 py-1.5 border-b border-slate-200/50 bg-slate-50/50 dark:bg-zinc-900/30 dark:border-zinc-800/50 rounded-t-2xl">
		<div class="flex items-center gap-1.5">
			<button class="p-1 rounded-md text-slate-500 hover:bg-slate-100 hover:text-slate-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-zinc-100 border-0 bg-transparent cursor-pointer transition-colors" type="button" data-theme-toggle aria-label="<?php esc_attr_e( 'Ubah mode warna', 'sukusastra' ); ?>">
				<!-- Sun Icon (visible in dark mode) -->
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 hidden dark:block">
					<circle cx="12" cy="12" r="4" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
				</svg>
				<!-- Moon Icon (visible in light mode) -->
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 block dark:hidden">
					<path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
				</svg>
			</button>
		</div>
	</div>

	<!-- Desktop Header Container (Hidden on mobile) -->
	<div class="ss-container hidden md:flex min-h-14 items-center justify-between gap-4 py-2">
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

		<!-- Primary Navigation Menu -->
		<nav id="primary-menu" class="flex-1 flex items-center justify-center gap-5" aria-label="<?php esc_attr_e( 'Menu utama', 'sukusastra' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex flex-row items-center gap-8 ss-nav-text',
					'fallback_cb'    => 'sukusastra_primary_menu_fallback',
				)
			);
			?>
		</nav>

		<!-- Action Buttons -->
		<div class="flex items-center gap-2">
			<a class="ss-button-kirim" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>">
				<svg class="w-4 h-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7Z" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M14 2v5h5" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6M9 14h6" />
				</svg>
				<span><?php esc_html_e( 'Kirim Karya', 'sukusastra' ); ?></span>
			</a>
		</div>
	</div>

	<!-- Mobile Header Container (Hidden on desktop) -->
	<div class="flex md:hidden flex-col w-full px-4 py-2.5">
		<!-- Top Row -->
		<div class="flex items-center justify-between gap-4 h-14">
			<!-- Logo left -->
			<a class="flex items-center no-underline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( $logo_light ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-[68px] w-auto object-contain logo-light relative z-10 -my-2">
				<img src="<?php echo esc_url( $logo_dark ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-[68px] w-auto object-contain logo-dark relative z-10 -my-2">
			</a>

			<!-- Circular Action Buttons Right -->
			<div class="flex items-center gap-2">
				<!-- Theme Toggle -->
				<button class="w-[42px] h-[42px] rounded-full border border-slate-200 bg-transparent dark:border-zinc-800 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-red-700 hover:border-red-200 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-red-400 dark:hover:border-red-900/50 cursor-pointer transition-all duration-200 shadow-sm" type="button" data-theme-toggle aria-label="<?php esc_attr_e( 'Ubah mode warna', 'sukusastra' ); ?>">
					<!-- Sun Icon (visible in dark mode) -->
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 hidden dark:block">
						<circle cx="12" cy="12" r="4" />
						<path stroke-linecap="round" stroke-linejoin="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
					</svg>
					<!-- Moon Icon (visible in light mode) -->
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 block dark:hidden">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
					</svg>
				</button>
				<!-- Hamburger Menu Button -->
				<button class="w-[42px] h-[42px] rounded-full border border-slate-200 bg-transparent dark:border-zinc-800 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-red-700 hover:border-red-200 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-red-400 dark:hover:border-red-900/50 cursor-pointer transition-all duration-200 shadow-sm aria-expanded:bg-red-700/10 aria-expanded:text-red-700 aria-expanded:border-red-500/30 dark:aria-expanded:bg-red-500/10 dark:aria-expanded:text-red-400 dark:aria-expanded:border-red-500/30" type="button" data-nav-toggle aria-controls="primary-menu-mobile" aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'sukusastra' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
						<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h11.25m-11.25 5.25h16.5" />
					</svg>
				</button>
			</div>
		</div>


		<?php if ( $show_mobile_capsules ) : ?>
		<!-- Bottom Row: Horizontal Scroll Categories Capsules (Homepage only) -->
		<div class="pt-2 pb-2.5 -mx-4 px-4 border-t border-slate-100/50 dark:border-zinc-800/50">
			<div id="mobile-capsules-bar" class="flex overflow-x-auto no-scrollbar gap-2">
				<?php
				$is_home = true;
				$base_url = '';
				
				$capsules = array(
					array(
						'label'  => __( 'Semua', 'sukusastra' ),
						'url'    => $base_url . '#hero',
						'hash'   => '#hero',
						'active' => $is_home
					),
					array(
						'label'  => __( 'Puisi', 'sukusastra' ),
						'url'    => $base_url . '#puisi',
						'hash'   => '#puisi',
						'active' => !$is_home && ( is_category( 'puisi' ) || ( is_single() && has_category( 'puisi' ) ) )
					),
					array(
						'label'  => __( 'Cerpen', 'sukusastra' ),
						'url'    => $base_url . '#cerpen',
						'hash'   => '#cerpen',
						'active' => !$is_home && ( is_category( 'cerpen' ) || ( is_single() && has_category( 'cerpen' ) ) )
					),
					array(
						'label'  => __( 'Esai', 'sukusastra' ),
						'url'    => $base_url . '#esai',
						'hash'   => '#esai',
						'active' => !$is_home && ( is_category( 'esai' ) || ( is_single() && has_category( 'esai' ) ) )
					),
					array(
						'label'  => __( 'Reviu Buku', 'sukusastra' ),
						'url'    => $base_url . '#review-buku',
						'hash'   => '#review-buku',
						'active' => !$is_home && ( is_post_type_archive( 'review_buku' ) || is_singular( 'review_buku' ) )
					),
					array(
						'label'  => __( 'Berita', 'sukusastra' ),
						'url'    => $base_url . '#berita',
						'hash'   => '#berita',
						'active' => !$is_home && ( is_post_type_archive( 'berita' ) || is_singular( 'berita' ) )
					),
					array(
						'label'  => __( 'Agenda/Event', 'sukusastra' ),
						'url'    => $base_url . '#event',
						'hash'   => '#event',
						'active' => !$is_home && ( is_post_type_archive( 'event' ) || is_singular( 'event' ) )
					)
				);

				foreach ( $capsules as $capsule ) {
					$active_class = $capsule['active'] 
						? 'bg-red-700 text-white border-red-700 dark:bg-red-500 dark:text-zinc-950 dark:border-red-500 font-bold shadow-sm' 
						: 'border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-red-700 hover:border-red-200 dark:border-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-800 dark:hover:text-red-400 dark:hover:border-red-900/50 font-medium';
					printf(
						'<a class="px-4 py-1.5 rounded-full text-xs no-underline shrink-0 transition-all duration-200 %s" href="%s">%s</a>',
						esc_attr( $active_class ),
						esc_url( $capsule['url'] ),
						esc_html( $capsule['label'] )
					);
				}
				?>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<?php if ( $show_mobile_capsules ) : ?>
	<!-- Mobile Category Navigation Script -->
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const headerOffset = 90; // offset for sticky header

			function smoothScrollTo(targetId) {
				const target = document.getElementById(targetId);
				if (!target) return;
				const elementPosition = target.getBoundingClientRect().top + window.scrollY;
				const offsetPosition = elementPosition - headerOffset;
				
				window.scrollTo({
					top: offsetPosition,
					behavior: 'smooth'
				});
			}

			// Smooth scroll capsule clicks
			const capsules = document.querySelectorAll('#mobile-capsules-bar a');
			capsules.forEach(capsule => {
				capsule.addEventListener('click', function(e) {
					const href = this.getAttribute('href');
					const hashIndex = href.indexOf('#');
					if (hashIndex !== -1) {
						const hash = href.substring(hashIndex + 1);
						const target = document.getElementById(hash);
						if (target) {
							e.preventDefault();
							smoothScrollTo(hash);
							updateActiveCapsule(hash);
						}
					}
				});
			});

			function updateActiveCapsule(activeId) {
				const activeClasses = ['bg-red-700', 'text-white', 'border-red-700', 'dark:bg-red-500', 'dark:text-zinc-950', 'dark:border-red-500', 'font-bold', 'shadow-sm'];
				const inactiveClasses = ['border', 'border-slate-200', 'text-slate-700', 'hover:bg-slate-100', 'dark:border-zinc-800', 'dark:text-zinc-300', 'dark:hover:bg-zinc-800', 'dark:hover:text-red-400', 'dark:hover:border-red-900/50', 'font-medium'];
				
				capsules.forEach(capsule => {
					const href = capsule.getAttribute('href');
					const hashIndex = href.indexOf('#');
					if (hashIndex !== -1) {
						const hash = href.substring(hashIndex + 1);
						if (hash === activeId) {
							capsule.classList.remove(...inactiveClasses);
							capsule.classList.add(...activeClasses);
							
							// Auto-scroll the capsules container horizontally to center the active tag
							const container = document.getElementById('mobile-capsules-bar');
							if (container) {
								const containerWidth = container.offsetWidth;
								const capsuleLeft = capsule.offsetLeft;
								const capsuleWidth = capsule.offsetWidth;
								const targetScroll = capsuleLeft - (containerWidth / 2) + (capsuleWidth / 2);
								container.scrollTo({
									left: targetScroll,
									behavior: 'smooth'
								});
							}
						} else {
							capsule.classList.remove(...activeClasses);
							capsule.classList.add(...inactiveClasses);
						}
					}
				});
			}

			// Intersection Observer for Scrollspy
			const isHome = window.location.pathname === '/' || window.location.pathname === '/index.php' || document.getElementById('hero');
			if (isHome) {
				const sectionIds = ['hero', 'puisi', 'cerpen', 'katalog-terbitan', 'esai', 'review-buku', 'berita', 'event'];
				const sections = sectionIds.map(id => document.getElementById(id)).filter(el => el !== null);
				
				const observerOptions = {
					root: null,
					rootMargin: '-20% 0px -60% 0px',
					threshold: 0
				};
				
				const observer = new IntersectionObserver((entries) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const id = entry.target.getAttribute('id');
							updateActiveCapsule(id);
						}
					});
				}, observerOptions);
				
				sections.forEach(section => observer.observe(section));
			}

			// Initial smooth scroll if hash present on page load
			if (window.location.hash) {
				const hash = window.location.hash.substring(1);
				setTimeout(() => {
					smoothScrollTo(hash);
				}, 400);
			}
		});
	</script>
	<?php endif; ?>
</header>

<!-- Mobile Navigation Backdrop overlay -->
<div id="mobile-menu-backdrop" class="fixed inset-0 bg-black/55 z-40 hidden transition-all duration-300" data-nav-toggle aria-controls="primary-menu-mobile"></div>

<!-- Mobile Navigation Drawer Menu (Slide-in) -->
<nav id="primary-menu-mobile" class="fixed top-0 right-0 h-full w-[290px] max-w-[85vw] bg-white dark:bg-zinc-950 z-50 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col justify-between hidden border-l border-slate-100 dark:border-zinc-900" aria-label="<?php esc_attr_e( 'Menu utama mobile', 'sukusastra' ); ?>">
	<!-- Drawer Header -->
	<div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-slate-100/80 dark:border-zinc-900/80">
		<a class="flex items-center gap-2 no-underline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php 
			$logo_light = sukusastra_get_option( 'logo_light', get_template_directory_uri() . '/assets/images/logo.svg' );
			$logo_dark = sukusastra_get_option( 'logo_dark', get_template_directory_uri() . '/assets/images/logo-white.svg' );
			?>
			<img src="<?php echo esc_url( $logo_light ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-9 w-auto object-contain logo-light">
			<img src="<?php echo esc_url( $logo_dark ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="h-9 w-auto object-contain logo-dark">
			<span class="text-sm font-extrabold text-slate-800 dark:text-zinc-50 tracking-tight ss-logo-font">Suku Sastra</span>
		</a>
		<button class="w-[32px] h-[32px] rounded-full border border-slate-200 dark:border-zinc-800 flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-zinc-900 cursor-pointer shadow-sm" type="button" data-nav-toggle aria-controls="primary-menu-mobile">
			<!-- Chevron Left Icon -->
			<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
				<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
			</svg>
		</button>
	</div>

	<!-- Drawer Scrollable Content -->
	<div class="flex-1 overflow-y-auto px-4 py-5 flex flex-col gap-6">
		<!-- Search Form -->
		<div>
			<form role="search" method="get" class="flex items-center bg-slate-100 dark:bg-zinc-900 border border-slate-200/60 dark:border-zinc-800 p-1 pl-4 rounded-xl w-full shadow-inner" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" class="bg-transparent border-0 outline-none text-xs flex-1 text-slate-900 dark:text-zinc-50 placeholder-slate-400 py-1.5 px-1 w-full" placeholder="<?php esc_attr_e( 'Cari tulisan...', 'sukusastra' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
				<button type="submit" class="w-8 h-8 rounded-lg bg-red-700 text-white flex items-center justify-center hover:bg-red-800 transition cursor-pointer shrink-0">
					<svg class="w-3.5 h-3.5 fill-none stroke-current stroke-[2.5]" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
				</button>
			</form>
		</div>

		<!-- Vertical Menu Items -->
		<div class="flex flex-col gap-1.5">
			<?php
			$locations = get_nav_menu_locations();
			$menu = isset( $locations['primary'] ) ? wp_get_nav_menu_object( $locations['primary'] ) : null;
			$menu_items = $menu ? wp_get_nav_menu_items( $menu->term_id ) : array();

			if ( ! empty( $menu_items ) ) {
				foreach ( $menu_items as $item ) {
					$active_class = ( (string) $item->object_id === (string) get_queried_object_id() || ( is_front_page() && strtolower(trim($item->title)) === 'depan' ) )
						? 'bg-slate-100 dark:bg-zinc-900 text-red-700 dark:text-red-400 font-bold shadow-sm border border-slate-200/40 dark:border-zinc-800/40'
						: 'text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 hover:text-red-700 dark:hover:text-red-400 font-medium border border-transparent';
					?>
					<a href="<?php echo esc_url( $item->url ); ?>" class="flex items-center justify-between px-3.5 py-3 rounded-xl transition-all duration-200 no-underline <?php echo esc_attr( $active_class ); ?>">
						<div class="flex items-center gap-3.5">
							<span class="text-slate-400 dark:text-zinc-500 shrink-0"><?php echo sukusastra_get_menu_icon( $item->title ); ?></span>
							<span class="text-[11px] tracking-wide uppercase font-bold ss-nav-text leading-none"><?php echo esc_html( $item->title ); ?></span>
						</div>
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-300 dark:text-zinc-700 shrink-0">
							<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
						</svg>
					</a>
					<?php
				}
			} else {
				// Fallback menu
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center justify-between px-3.5 py-3 rounded-xl text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 font-medium no-underline">
					<div class="flex items-center gap-3.5">
						<span class="text-slate-400 shrink-0"><?php echo sukusastra_get_menu_icon( 'depan' ); ?></span>
						<span class="text-[11px] tracking-wide uppercase font-bold ss-nav-text leading-none">DEPAN</span>
					</div>
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-300 shrink-0">
						<path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
					</svg>
				</a>
				<?php
			}
			?>
		</div>

		<!-- Kirim Karya Button (Call to Action) -->
		<div>
			<a class="flex items-center justify-center gap-2.5 rounded-xl border border-red-700 bg-white dark:bg-zinc-900 dark:border-red-500/30 px-4 py-3 text-red-700 dark:text-red-400 no-underline shadow-sm hover:bg-red-50 dark:hover:bg-red-950/20 transition-all w-full cursor-pointer h-11" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>">
				<svg class="w-4 h-4 shrink-0 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7Z" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M14 2v5h5" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6M9 14h6" />
				</svg>
				<span class="text-[11px] uppercase font-black tracking-wider leading-none">Kirim Karya</span>
			</a>
		</div>
	</div>

	<!-- Drawer Footer -->
	<div class="px-5 py-4 border-t border-slate-200 dark:border-zinc-900 bg-slate-50 dark:bg-zinc-900/60">
		<?php if ( is_user_logged_in() ) : 
			$current_user = wp_get_current_user();
			$avatar_url = get_avatar_url( $current_user->ID, array( 'size' => 64 ) );
			?>
			<div class="flex items-center justify-between w-full">
				<div class="flex items-center gap-3">
					<img src="<?php echo esc_url( $avatar_url ); ?>" alt="<?php echo esc_attr( $current_user->display_name ); ?>" class="w-9 h-9 rounded-full object-cover ring-2 ring-red-700/10 dark:ring-red-500/20 shrink-0">
					<div class="flex flex-col text-left leading-normal">
						<span class="text-[11px] font-bold text-slate-800 dark:text-zinc-100 leading-none truncate max-w-[140px]"><?php echo esc_html( $current_user->display_name ); ?></span>
						<span class="text-[9px] text-slate-400 dark:text-zinc-500 mt-0.5 capitalize"><?php echo esc_html( reset( $current_user->roles ) ); ?></span>
					</div>
				</div>
				<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="text-slate-400 dark:text-zinc-500 hover:text-red-700 dark:hover:text-red-400 transition" title="<?php esc_attr_e( 'Keluar', 'sukusastra' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4.5 h-4.5">
						<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
					</svg>
				</a>
			</div>
		<?php else : ?>
			<div class="flex items-center justify-between w-full">
				<div class="flex items-center gap-3">
					<div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-zinc-800 flex items-center justify-center text-slate-400 dark:text-zinc-500 shrink-0">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4.5 h-4.5">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
						</svg>
					</div>
					<div class="flex flex-col text-left leading-normal">
						<span class="text-[11px] font-bold text-slate-800 dark:text-zinc-200 leading-none">Ruang Sastra & Seni</span>
						<span class="text-[9px] text-slate-400 dark:text-zinc-500 mt-0.5">Suku Sastra</span>
					</div>
				</div>
				<a href="<?php echo esc_url( wp_login_url() ); ?>" class="text-[10px] font-bold text-red-700 dark:text-red-400 hover:underline no-underline" title="<?php esc_attr_e( 'Masuk Admin', 'sukusastra' ); ?>">
					Masuk
				</a>
			</div>
		<?php endif; ?>
	</div>
</nav>

<main id="main-content">
