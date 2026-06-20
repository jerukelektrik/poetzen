<?php
/**
 * Site footer.
 *
 * @package SukuSastra
 */
?>

</main>

<footer class="border-t border-slate-800 bg-slate-950 pt-12 pb-10 text-slate-400 dark:border-zinc-850 dark:bg-zinc-950 dark:text-zinc-500">
	<div class="ss-container">
		<!-- 1. Centered Logo & Bio -->
		<div class="flex flex-col items-center pb-8">
			<a class="flex items-center no-underline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<!-- Logo in footer (uses dark-mode/white logo or light-mode logo fallback) -->
				<?php 
				$footer_logo = sukusastra_get_option( 'logo_dark' );
				if ( ! $footer_logo ) {
					$footer_logo = sukusastra_get_option( 'logo_light', get_template_directory_uri() . '/assets/images/logo-white.svg' );
				}
				?>
				<img src="<?php echo esc_url( $footer_logo ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="w-[120px] h-[120px] object-contain logo-light">
				<img src="<?php echo esc_url( $footer_logo ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="w-[120px] h-[120px] object-contain logo-dark">
			</a>
			<?php 
			$footer_bio = sukusastra_get_option( 'footer_bio' );
			if ( $footer_bio ) : ?>
				<p class="text-sm text-center text-slate-400 dark:text-zinc-500 mt-4 max-w-2xl font-sans leading-relaxed">
					<?php echo wp_kses_post( $footer_bio ); ?>
				</p>
			<?php endif; ?>
		</div>

		<!-- 2. Main Link Grid -->
		<div class="border-t border-slate-800 dark:border-zinc-800 py-10 grid gap-8 sm:grid-cols-2 md:grid-cols-4">
			<!-- Column 1: Rubrik (Sections) -->
			<div>
				<h4 class="ss-footer-label text-slate-500 dark:text-zinc-550 mb-4"><?php esc_html_e( 'Rubrik', 'sukusastra' ); ?></h4>
				<ul class="grid gap-2 text-sm text-slate-300 dark:text-zinc-400">
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/category/puisi/' ) ); ?>"><?php esc_html_e( 'Puisi', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/category/cerpen/' ) ); ?>"><?php esc_html_e( 'Cerpen', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/category/esai/' ) ); ?>"><?php esc_html_e( 'Esai', 'sukusastra' ); ?></a></li>
				</ul>
			</div>

			<!-- Column 2: Rubrik Continued -->
			<div>
				<div class="hidden md:block h-[34px]"></div> <!-- alignment spacer -->
				<ul class="grid gap-2 text-sm text-slate-300 dark:text-zinc-400">
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/review-buku/' ) ); ?>"><?php esc_html_e( 'Reviu Buku', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/berita/' ) ); ?>"><?php esc_html_e( 'Berita', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/event/' ) ); ?>"><?php esc_html_e( 'Event & Agenda', 'sukusastra' ); ?></a></li>
				</ul>
			</div>

			<!-- Column 3: Lainnya (More) -->
			<div>
				<h4 class="ss-footer-label text-slate-500 dark:text-zinc-550 mb-4"><?php esc_html_e( 'Lainnya', 'sukusastra' ); ?></h4>
				<ul class="grid gap-2 text-sm text-slate-300 dark:text-zinc-400">
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/tentang-kami/' ) ); ?>"><?php esc_html_e( 'Tentang Kami', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/redaksi/' ) ); ?>"><?php esc_html_e( 'Redaksi', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/hubungi-kami/' ) ); ?>"><?php esc_html_e( 'Hubungi Kami', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'F.A.Q.', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>"><?php esc_html_e( 'Ketentuan Kirim Karya', 'sukusastra' ); ?></a></li>
					<li><a class="no-underline hover:text-red-400 dark:hover:text-red-400 transition-colors" href="<?php echo esc_url( home_url( '/kebijakan-privasi/' ) ); ?>"><?php esc_html_e( 'Kebijakan Privasi', 'sukusastra' ); ?></a></li>
				</ul>
			</div>

			<!-- Column 4: Newsletter -->
			<div>
				<h4 class="ss-footer-label text-slate-500 dark:text-zinc-550 mb-4"><?php esc_html_e( 'Newsletter', 'sukusastra' ); ?></h4>
				<p class="text-sm leading-5 text-slate-400 dark:text-zinc-500 mb-3 font-sans">
					<?php esc_html_e( 'Dapatkan kurasi karya sastra terbaik langsung di emailmu.', 'sukusastra' ); ?>
				</p>
				<form class="flex items-center bg-slate-900 border border-slate-800 p-1 pl-3.5 rounded-full w-full max-w-[260px] shadow-inner" action="#" method="post">
					<span class="text-slate-500 text-[10px] font-bold shrink-0">@</span>
					<input type="email" class="bg-transparent border-0 outline-none text-sm flex-1 text-slate-100 placeholder-slate-500 py-1.5 px-2 font-medium" placeholder="<?php esc_attr_e( 'Alamat email...', 'sukusastra' ); ?>" required />
					<button type="submit" class="w-8 h-8 rounded-full bg-zinc-100 text-zinc-950 flex items-center justify-center hover:bg-red-700 hover:text-white transition cursor-pointer shadow-sm shrink-0">
						<svg class="w-3.5 h-3.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
						</svg>
					</button>
				</form>
			</div>
		</div>


		<!-- 4. Bottom Copyright & Social Icons -->
		<div class="border-t border-slate-800 dark:border-zinc-800 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500 dark:text-zinc-500">
			<div>
				<?php 
				$copyright = sukusastra_get_option( 'copyright', sprintf( '&copy; %s Suku Sastra. Hak Cipta Dilindungi.', date( 'Y' ) ) );
				echo wp_kses_post( $copyright ); 
				?>
			</div>
			
			<div class="flex items-center gap-4 text-slate-400 dark:text-zinc-400">
				<?php
				$socials = array(
					'youtube' => array(
						'label'   => 'YouTube',
						'default' => 'https://youtube.com/@sukusastra',
						'svg'     => '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.163a3.003 3.003 0 0 0-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.518 0-9.388.508a3.003 3.003 0 0 0-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 0 0 2.11 2.11c1.87.508 9.388.508 9.388.508s7.518 0 9.388-.508a3.003 3.003 0 0 0 2.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'
					),
					'instagram' => array(
						'label'   => 'Instagram',
						'default' => 'https://instagram.com/sukusastra',
						'svg'     => '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051C.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>'
					),
					'tiktok' => array(
						'label'   => 'TikTok',
						'default' => 'https://tiktok.com/@sukusastra',
						'svg'     => '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.02 1.77 4.05 1.13.97 2.61 1.43 4.1 1.47v3.82c-1.87-.02-3.68-.72-5.02-2.02-.02 3.42-.01 6.85-.02 10.27-.01 1.77-.52 3.56-1.58 4.96-1.53 1.95-4.14 2.87-6.57 2.39-2.73-.54-4.97-2.75-5.38-5.51-.55-3.69 1.96-7.39 5.62-8.03.88-.15 1.79-.11 2.68.08v3.9c-.83-.23-1.74-.2-2.51.22-.96.53-1.54 1.57-1.47 2.67.06 1.48 1.34 2.69 2.83 2.62 1.55-.07 2.76-1.39 2.72-2.95.02-5.46.01-10.92.02-16.38-.01-.31-.02-.62-.02-.93z"/></svg>'
					),
					'linkedin' => array(
						'label'   => 'LinkedIn',
						'default' => 'https://linkedin.com/company/sukusastra',
						'svg'     => '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>'
					),
					'twitter' => array(
						'label'   => 'Twitter / X',
						'default' => 'https://twitter.com/sukusastra',
						'svg'     => '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'
					),
					'whatsapp' => array(
						'label'   => 'WhatsApp',
						'default' => 'https://wa.me/6281234567890',
						'svg'     => '<svg class="w-4 h-4 fill-current" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16.01 3.2c-7.04 0-12.77 5.72-12.77 12.76 0 2.25.59 4.44 1.72 6.37L3.12 29l6.83-1.79a12.7 12.7 0 0 0 6.06 1.54c7.04 0 12.77-5.72 12.77-12.76S23.05 3.2 16.01 3.2Zm0 23.39c-1.91 0-3.79-.51-5.43-1.49l-.39-.23-4.05 1.06 1.08-3.95-.25-.41a10.56 10.56 0 0 1-1.62-5.61c0-5.86 4.78-10.63 10.65-10.63 5.86 0 10.64 4.77 10.64 10.63 0 5.86-4.78 10.63-10.64 10.63Zm5.83-7.96c-.32-.16-1.89-.93-2.18-1.04-.29-.11-.5-.16-.71.16-.21.32-.82 1.04-1.01 1.25-.19.21-.37.24-.69.08-.32-.16-1.35-.5-2.58-1.59-.95-.85-1.6-1.9-1.79-2.22-.19-.32-.02-.49.14-.65.15-.15.32-.37.48-.56.16-.19.21-.32.32-.53.11-.21.05-.4-.03-.56-.08-.16-.71-1.71-.98-2.34-.26-.62-.52-.53-.71-.54h-.61c-.21 0-.56.08-.85.4-.29.32-1.12 1.09-1.12 2.66s1.15 3.09 1.31 3.3c.16.21 2.27 3.46 5.5 4.85.77.33 1.37.53 1.84.68.77.24 1.48.21 2.04.13.62-.09 1.89-.77 2.16-1.52.27-.75.27-1.39.19-1.52-.08-.13-.29-.21-.61-.37Z"/></svg>'
					),
					'facebook' => array(
						'label'   => 'Facebook',
						'default' => 'https://facebook.com/sukusastra',
						'svg'     => '<svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>'
					)
				);

				foreach ( $socials as $key => $social ) {
					$url = sukusastra_get_option( $key, $social['default'] );
					if ( $url ) {
						printf(
							'<a class="hover:text-red-400 transition-colors" href="%1$s" target="_blank" rel="noopener" aria-label="%2$s">%3$s</a>',
							esc_url( $url ),
							esc_attr( $social['label'] ),
							$social['svg']
						);
					}
				}
				?>
			</div>
		</div>
	</div>
</footer>

	<!-- Floating Sticky Kirim Karya Button -->
	<div class="hidden md:block fixed bottom-6 right-6 z-50">
		<a class="block hover:scale-105 active:scale-95 transition-all duration-300" style="width: 200px; height: 200px;" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>" aria-label="<?php esc_attr_e( 'Kirim Karya', 'sukusastra' ); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/kirim.png' ); ?>" alt="<?php esc_attr_e( 'Kirim Karya', 'sukusastra' ); ?>" class="w-full h-full object-contain">
		</a>
	</div>

	<div class="h-24 md:hidden" aria-hidden="true"></div>

	<!-- Mobile Sticky Kirim Karya Bar -->
	<div class="ss-mobile-submit-bar fixed inset-x-0 bottom-0 z-50 md:hidden">
		<a class="ss-mobile-submit-link relative flex min-h-[72px] items-center gap-3 overflow-hidden rounded-t-2xl px-5 py-3 text-white shadow-[0_-10px_30px_rgba(15,23,42,0.18)] no-underline" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>" aria-label="<?php esc_attr_e( 'Kirim Karya', 'sukusastra' ); ?>">
			<span class="flex-1 text-sm font-black leading-tight">
				<?php esc_html_e( 'Mau kirim karya? Yuk baca ketentuan redaksi.', 'sukusastra' ); ?>
			</span>
			<span class="ss-mobile-submit-icon grid h-11 w-11 shrink-0 place-items-center rounded-full bg-white shadow-sm dark:bg-zinc-950">
				<svg class="h-5 w-5 fill-none stroke-current stroke-[2.5]" viewBox="0 0 24 24" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
				</svg>
			</span>
		</a>
	</div>

	<?php poetzen_render_popup_banners(); ?>

<?php wp_footer(); ?>

<?php 
$footer_scripts = sukusastra_get_option( 'footer_scripts' );
if ( $footer_scripts ) {
	echo $footer_scripts . "\n";
}
?>
</body>
</html>
