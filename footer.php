<?php
/**
 * Site footer.
 *
 * @package SukuSastra
 */

// Query 5 CPT penulis for orbit banner
$orbit_avatars = array();
$penulis_query = new WP_Query( array(
	'post_type'      => 'penulis',
	'posts_per_page' => 5,
	'post_status'    => 'publish',
) );

if ( $penulis_query->have_posts() ) {
	while ( $penulis_query->have_posts() ) {
		$penulis_query->the_post();
		$title = get_the_title();
		$letter = substr( $title, 0, 1 );
		if ( has_post_thumbnail() ) {
			$orbit_avatars[] = array(
				'type' => 'image',
				'data' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
				'link' => get_permalink(),
			);
		} else {
			$orbit_avatars[] = array(
				'type' => 'letter',
				'data' => $letter,
				'link' => get_permalink(),
			);
		}
	}
	wp_reset_postdata();
}

$fallback_names = array( 'Sapardi', 'Chairil', 'Leila', 'Goenawan', 'Sutardji' );
$fallback_colors = array( 'bg-red-700', 'bg-blue-700', 'bg-emerald-700', 'bg-amber-700', 'bg-purple-700' );
for ( $i = count( $orbit_avatars ); $i < 5; $i++ ) {
	$orbit_avatars[] = array(
		'type'  => 'letter',
		'data'  => substr( $fallback_names[ $i ], 0, 1 ),
		'link'  => home_url( '/penulis/' ),
		'color' => $fallback_colors[ $i ],
	);
}

if ( ! function_exists( 'sukusastra_render_orbit_avatar' ) ) {
	function sukusastra_render_orbit_avatar( $avatar ) {
		if ( 'image' === $avatar['type'] ) {
			return sprintf(
				'<a href="%1$s" class="block w-full h-full"><img src="%2$s" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"></a>',
				esc_url( $avatar['link'] ),
				esc_url( $avatar['data'] )
			);
		} else {
			$bg = isset( $avatar['color'] ) ? $avatar['color'] : 'bg-red-700';
			return sprintf(
				'<a href="%1$s" class="flex w-full h-full items-center justify-center %2$s text-white text-[10px] font-black hover:scale-110 transition-transform duration-300">%3$s</a>',
				esc_url( $avatar['link'] ),
				esc_attr( $bg ),
				esc_html( $avatar['data'] )
			);
		}
	}
}
?>
</main>

<footer class="border-t border-slate-200 bg-white pt-16 pb-10 dark:border-zinc-800 dark:bg-[#262B4E]">
	<!-- SquareUi style Concentric Orbit CTA Banner -->
	<div class="ss-container mb-12">
		<div class="bg-slate-50 dark:bg-[#343B6A]/30 border border-slate-200/60 dark:border-zinc-800/80 rounded-3xl p-8 md:p-12 shadow-sm grid md:grid-cols-[1.2fr_1fr] gap-8 items-center overflow-hidden">
			<!-- Left Column: Content -->
			<div>
				<p class="ss-eyebrow mb-2">
					<?php esc_html_e( 'Kirim Karya', 'sukusastra' ); ?>
				</p>
				<h2 class="ss-section-title md:text-3.5xl font-serif mb-3 leading-tight">
					<?php esc_html_e( 'Punya Karya Sastra Terbaikmu?', 'sukusastra' ); ?>
				</h2>
				<p class="ss-body-serif max-w-md mb-6">
					<?php esc_html_e( 'Kirimkan puisi, cerpen, esai, atau review bukumu. Kami menerbitkan karya sastra pilihan setiap minggunya untuk dinikmati pembaca seluruh Indonesia.', 'sukusastra' ); ?>
				</p>
				<a href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-full bg-slate-950 px-6 py-3 text-sm font-bold text-white hover:bg-red-700 transition dark:bg-zinc-100 dark:text-zinc-950 dark:hover:bg-red-400 shadow-sm w-fit group no-underline">
					<?php esc_html_e( 'Kirim Karya Sekarang', 'sukusastra' ); ?>
					<svg class="w-4 h-4 fill-none stroke-current stroke-2 transform group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform duration-300" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>

			<!-- Right Column: concentric orbit rings -->
			<div class="hidden md:flex justify-center items-center relative h-[280px]">
				<!-- Center Logo/Icon -->
				<div class="z-10 w-12 h-12 rounded-full bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center shadow-md">
					<svg class="w-6 h-6 text-red-700 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.582.477 5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
					</svg>
				</div>

				<!-- Ring 1 (Inner, 110px) -->
				<div class="absolute w-[110px] h-[110px] rounded-full border border-dashed border-slate-200/80 dark:border-zinc-800/80 animate-[spin_40s_linear_infinite]">
					<!-- Avatar 1 -->
					<div class="absolute -top-3 left-[43px] w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-zinc-700 shadow-sm bg-white">
						<?php echo sukusastra_render_orbit_avatar( $orbit_avatars[0] ); ?>
					</div>
				</div>

				<!-- Ring 2 (Middle, 190px) -->
				<div class="absolute w-[190px] h-[190px] rounded-full border border-dashed border-slate-200/80 dark:border-zinc-800/80 animate-[spin_60s_linear_infinite_reverse]">
					<!-- Avatar 2 -->
					<div class="absolute top-[83px] -left-3 w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-zinc-700 shadow-sm bg-white">
						<?php echo sukusastra_render_orbit_avatar( $orbit_avatars[1] ); ?>
					</div>
					<!-- Avatar 3 -->
					<div class="absolute top-[83px] -right-3 w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-zinc-700 shadow-sm bg-white">
						<?php echo sukusastra_render_orbit_avatar( $orbit_avatars[2] ); ?>
					</div>
				</div>

				<!-- Ring 3 (Outer, 270px) -->
				<div class="absolute w-[270px] h-[270px] rounded-full border border-dashed border-slate-200/80 dark:border-zinc-800/80 animate-[spin_80s_linear_infinite]">
					<!-- Avatar 4 -->
					<div class="absolute -top-3 left-[123px] w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-zinc-700 shadow-sm bg-white">
						<?php echo sukusastra_render_orbit_avatar( $orbit_avatars[3] ); ?>
					</div>
					<!-- Avatar 5 -->
					<div class="absolute bottom-6 left-[39px] w-6 h-6 rounded-full overflow-hidden border border-slate-200 dark:border-zinc-700 shadow-sm bg-white">
						<?php echo sukusastra_render_orbit_avatar( $orbit_avatars[4] ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Main Footer Columns -->
	<div class="ss-container grid gap-10 sm:grid-cols-2 md:grid-cols-4 border-b border-slate-100 dark:border-zinc-800/80 pb-12">
		<!-- Column 1: About -->
		<div class="grid gap-3 content-start">
			<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 font-serif tracking-tight flex items-center gap-2">
				<svg class="w-5 h-5 text-red-700 dark:text-red-400 fill-none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.582.477 5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
				</svg>
				<?php esc_html_e( 'Suku Sastra', 'sukusastra' ); ?>
			</h3>
			<p class="text-xs leading-6 text-slate-500 dark:text-zinc-400 max-w-xs font-serif">
				<?php esc_html_e( 'Media sastra independen untuk membaca, menulis, menerbitkan, dan mendiskusikan karya sastra terbaik Indonesia.', 'sukusastra' ); ?>
			</p>
		</div>

		<!-- Column 2: Karya & Rubrik -->
		<div class="grid gap-3 content-start">
			<h4 class="ss-footer-label">
				<?php esc_html_e( 'Karya & Rubrik', 'sukusastra' ); ?>
			</h4>
			<ul class="grid gap-2 ss-footer-link">
				<li><a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/category/puisi/' ) ); ?>"><?php esc_html_e( 'Puisi', 'sukusastra' ); ?></a></li>
				<li><a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/category/cerpen/' ) ); ?>"><?php esc_html_e( 'Cerpen', 'sukusastra' ); ?></a></li>
				<li><a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/category/esai/' ) ); ?>"><?php esc_html_e( 'Esai', 'sukusastra' ); ?></a></li>
				<li><a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/review-buku/' ) ); ?>"><?php esc_html_e( 'Review Buku', 'sukusastra' ); ?></a></li>
				<li><a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/event/' ) ); ?>"><?php esc_html_e( 'Event & Agenda', 'sukusastra' ); ?></a></li>
			</ul>
		</div>

		<!-- Column 3: Media Sosial -->
		<div class="grid gap-3 content-start">
			<h4 class="ss-footer-label">
				<?php esc_html_e( 'Media Sosial', 'sukusastra' ); ?>
			</h4>
			<ul class="grid gap-2 ss-footer-link">
				<?php
				$social_keys = array(
					'instagram' => 'Instagram',
					'twitter'   => 'Twitter / X',
					'facebook'  => 'Facebook',
					'youtube'   => 'YouTube',
				);
				foreach ( $social_keys as $key => $label ) {
					$url = sukusastra_get_option( $key );
					if ( $url ) {
						printf(
							'<li><a class="inline-flex items-center gap-1 no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="%1$s" target="_blank" rel="noopener">%2$s <span class="text-[9px] text-slate-400 dark:text-zinc-600 font-normal">↗</span></a></li>',
							esc_url( $url ),
							esc_html( $label )
						);
					}
				}
				?>
			</ul>
		</div>

		<!-- Column 4: Newsletter -->
		<div class="grid gap-3 content-start">
			<h4 class="ss-footer-label">
				<?php esc_html_e( 'Newsletter', 'sukusastra' ); ?>
			</h4>
			<p class="ss-body-serif text-xs leading-5">
				<?php esc_html_e( 'Dapatkan kurasi karya sastra terbaik langsung di emailmu.', 'sukusastra' ); ?>
			</p>
			<form class="flex items-center bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-1 pl-3.5 rounded-full mt-2 w-full max-w-[260px] shadow-inner" action="#" method="post">
				<span class="text-slate-400 dark:text-zinc-600 text-[10px] font-bold shrink-0">@</span>
				<input type="email" class="bg-transparent border-0 outline-none text-xs flex-1 text-slate-800 dark:text-zinc-100 placeholder-slate-400 py-1.5 px-2 font-medium" placeholder="<?php esc_attr_e( 'Alamat email...', 'sukusastra' ); ?>" required />
				<button type="submit" class="w-8 h-8 rounded-full bg-slate-950 text-white flex items-center justify-center hover:bg-red-700 transition dark:bg-zinc-100 dark:text-zinc-950 dark:hover:bg-red-400 cursor-pointer shadow-sm shrink-0">
					<svg class="w-3.5 h-3.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</button>
			</form>
		</div>
	</div>

	<!-- Bottom Row: Copyright & Framer-like icons -->
	<div class="ss-container flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 ss-copyright">
		<div>
			<?php 
			$copyright = sukusastra_get_option( 'copyright', sprintf( '&copy; %s poetzen. Hak Cipta Dilindungi.', date( 'Y' ) ) );
			echo wp_kses_post( $copyright ); 
			?>
		</div>
		
		<div class="flex items-center gap-3">
			<span><?php esc_html_e( 'Jelajahi ekosistem kami', 'sukusastra' ); ?></span>
			<span>·</span>
			<a class="hover:text-slate-700 dark:hover:text-zinc-300 transition-colors" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Website', 'sukusastra' ); ?>">
				<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
			</a>
			<a class="hover:text-slate-700 dark:hover:text-zinc-300 transition-colors" href="<?php echo esc_url( sukusastra_get_option( 'twitter' ) ); ?>" target="_blank" rel="noopener" aria-label="Twitter / X">
				<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
			</a>
		</div>
	</div>
</footer>

<!-- Floating Sticky Kirim Karya Button -->
<div class="fixed bottom-6 right-6 z-50">
	<a class="block hover:scale-105 active:scale-95 transition-all duration-300" style="width: 200px; height: 200px;" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>" aria-label="<?php esc_attr_e( 'Kirim Karya', 'sukusastra' ); ?>">
		<!-- Light Mode badge -->
		<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/kirim.png' ); ?>" alt="<?php esc_attr_e( 'Kirim Karya', 'sukusastra' ); ?>" class="w-full h-full object-contain kirim-light">
		<!-- Dark Mode badge -->
		<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/kirim-white.png' ); ?>" alt="<?php esc_attr_e( 'Kirim Karya', 'sukusastra' ); ?>" class="w-full h-full object-contain kirim-dark">
	</a>
</div>

<?php wp_footer(); ?>

<?php 
$footer_scripts = sukusastra_get_option( 'footer_scripts' );
if ( $footer_scripts ) {
	echo $footer_scripts . "\n";
}
?>
</body>
</html>
