<?php
/**
 * Komunitas single template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php
	$post_id     = get_the_ID();
	$comm_name   = sukusastra_get_meta( $post_id, '_ss_comm_name', get_the_title() );
	$comm_desc   = sukusastra_get_meta( $post_id, '_ss_comm_desc', '' );
	$comm_year   = sukusastra_get_meta( $post_id, '_ss_comm_year', '' );
	$comm_address= sukusastra_get_meta( $post_id, '_ss_comm_address', '' );
	$comm_city   = sukusastra_get_meta( $post_id, '_ss_comm_city', '' );
	$comm_prov   = sukusastra_get_meta( $post_id, '_ss_comm_province', '' );
	$website     = sukusastra_get_meta( $post_id, '_ss_comm_website', '' );
	$instagram   = sukusastra_get_meta( $post_id, '_ss_comm_instagram', '' );
	$tiktok      = sukusastra_get_meta( $post_id, '_ss_comm_tiktok', '' );
	$youtube     = sukusastra_get_meta( $post_id, '_ss_comm_youtube', '' );
	$contact     = sukusastra_get_meta( $post_id, '_ss_comm_contact', '' );
	$activities  = sukusastra_get_meta( $post_id, '_ss_comm_activities', '' );
	$publications= sukusastra_get_meta( $post_id, '_ss_comm_publications', '' );
	$gallery_ids = sukusastra_get_meta( $post_id, '_ss_comm_gallery', '' );
	$gallery_array = $gallery_ids ? explode( ',', $gallery_ids ) : array();
	?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[minmax(0,760px)_320px]">
				<!-- Main Column -->
				<div>
					<!-- Mobile Sidebar Banner (Tampil hanya di Mobile) -->
					<div class="block lg:hidden mb-6">
						<?php poetzen_render_sidebar_banners(); ?>
					</div>

					<p class="ss-eyebrow mb-2"><?php esc_html_e( 'Profil Komunitas', 'sukusastra' ); ?></p>
					<h1 class="ss-page-title"><?php echo esc_html( $comm_name ); ?></h1>

					<!-- Featured Image / Logo -->
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="mt-6 rounded-2xl overflow-hidden shadow-sm">
							<?php the_post_thumbnail( 'sukusastra-hero', array( 'class' => 'w-full object-cover max-h-[400px]' ) ); ?>
						</div>
					<?php endif; ?>

					<!-- Identitas Komunitas Card -->
					<div class="ss-card mt-6 rounded-3xl p-6 grid gap-5 bg-white dark:bg-[#262B4E]/40 shadow-sm border border-slate-200/60 dark:border-zinc-800/80 font-sans">
						<h3 class="ss-info-title m-0 text-slate-900 dark:text-zinc-50 text-base font-black uppercase tracking-wide"><?php esc_html_e( 'Identitas Komunitas', 'sukusastra' ); ?></h3>
						
						<div class="grid gap-4.5 text-sm">
							<!-- Tahun Berdiri -->
							<?php if ( $comm_year ) : ?>
								<div class="flex items-start gap-3">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 dark:text-zinc-500 shrink-0 mt-0.5">
										<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
									</svg>
									<div>
										<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Tahun Berdiri', 'sukusastra' ); ?></span>
										<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( $comm_year ); ?></span>
									</div>
								</div>
							<?php endif; ?>

							<!-- Wilayah (Kota & Provinsi) -->
							<?php if ( $comm_city || $comm_prov ) : ?>
								<div class="flex items-start gap-3">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 dark:text-zinc-500 shrink-0 mt-0.5">
										<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
										<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
									</svg>
									<div>
										<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Wilayah', 'sukusastra' ); ?></span>
										<span class="text-slate-700 dark:text-zinc-200 font-bold">
											<?php 
											$loc_parts = array_filter( array( $comm_city, $comm_prov ) );
											echo esc_html( implode( ', ', $loc_parts ) ); 
											?>
										</span>
									</div>
								</div>
							<?php endif; ?>

							<!-- Alamat Sekretariat -->
							<?php if ( $comm_address ) : ?>
								<div class="flex items-start gap-3">
									<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 dark:text-zinc-500 shrink-0 mt-0.5">
										<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
									</svg>
									<div>
										<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Alamat Sekretariat', 'sukusastra' ); ?></span>
										<span class="text-slate-700 dark:text-zinc-300 font-medium leading-relaxed block"><?php echo esc_html( $comm_address ); ?></span>
									</div>
								</div>
							<?php endif; ?>

							<!-- Media Sosial -->
							<?php if ( $website || $instagram || $tiktok || $youtube ) : ?>
								<div class="border-t border-slate-100 dark:border-zinc-800/80 pt-4">
									<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-2.5"><?php esc_html_e( 'Media Sosial', 'sukusastra' ); ?></span>
									<div class="flex flex-wrap gap-2">
										<?php if ( $website ) : ?>
											<a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-550 hover:text-red-700 dark:bg-zinc-900/60 dark:hover:bg-zinc-900 dark:text-zinc-400 dark:hover:text-red-400 border border-slate-200/50 dark:border-zinc-800/60 transition" title="Website">
												<svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.778.099-1.533.284-2.253" /></svg>
											</a>
										<?php endif; ?>

										<?php if ( $instagram ) : 
											$insta_url = str_starts_with( $instagram, 'http' ) ? $instagram : 'https://instagram.com/' . trim( $instagram, '@' );
											?>
											<a href="<?php echo esc_url( $insta_url ); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-550 hover:text-red-700 dark:bg-zinc-900/60 dark:hover:bg-zinc-900 dark:text-zinc-400 dark:hover:text-red-400 border border-slate-200/50 dark:border-zinc-800/60 transition" title="Instagram">
												<svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 2.25 20.25h-15A2.25 2.25 0 0 0 2.25 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" /></svg>
											</a>
										<?php endif; ?>

										<?php if ( $tiktok ) : 
											$tiktok_url = str_starts_with( $tiktok, 'http' ) ? $tiktok : 'https://tiktok.com/@' . trim( $tiktok, '@' );
											?>
											<a href="<?php echo esc_url( $tiktok_url ); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-550 hover:text-red-700 dark:bg-zinc-900/60 dark:hover:bg-zinc-900 dark:text-zinc-400 dark:hover:text-red-400 border border-slate-200/50 dark:border-zinc-800/60 transition" title="TikTok">
												<svg class="h-4 w-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.06-2.89-.52-4.06-1.39-.77-.57-1.39-1.35-1.77-2.24-.06.72-.03 1.44-.04 2.16v8.44c.05 1.55-.4 3.16-1.37 4.38-1.4 1.83-3.87 2.76-6.11 2.44-2.58-.29-4.88-2.22-5.46-4.79-.76-3.14.97-6.52 4.02-7.39 1.11-.34 2.3-.32 3.42.02v4.12c-.79-.37-1.72-.44-2.53-.12-1.12.41-1.89 1.56-1.85 2.77.06 1.48 1.39 2.71 2.87 2.58 1.41-.05 2.53-1.25 2.53-2.67V.02h.1z"/></svg>
											</a>
										<?php endif; ?>

										<?php if ( $youtube ) : ?>
											<a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-550 hover:text-red-700 dark:bg-zinc-900/60 dark:hover:bg-zinc-900 dark:text-zinc-400 dark:hover:text-red-400 border border-slate-200/50 dark:border-zinc-800/60 transition" title="YouTube">
												<svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.487 11.278a.75.75 0 0 1 0 1.444l-4.73 1.892A.75.75 0 0 1 9.75 13.91V10.09a.75.75 0 0 1 1.007-.704l4.73 1.892Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 12c0 5.385-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12 6.615 2.25 12 2.25s9.75 4.365 9.75 9.75Z" /></svg>
											</a>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>

							<!-- Whatsapp Button CTA / Contact -->
							<?php if ( $contact ) : ?>
								<div class="border-t border-slate-100 dark:border-zinc-800/80 pt-4">
									<?php if ( preg_match( '/^[0-9+]+$/', str_replace( array(' ', '-'), '', $contact ) ) ) : ?>
										<!-- If number, convert to WA clean link -->
										<?php
										$clean_phone = preg_replace( '/[^0-9]/', '', $contact );
										if ( str_starts_with( $clean_phone, '0' ) ) {
											$clean_phone = '62' . substr( $clean_phone, 1 );
										}
										?>
										<a href="https://wa.me/<?php echo esc_attr( $clean_phone ); ?>" target="_blank" rel="noopener" class="no-underline text-slate-700 dark:text-zinc-300 hover:text-red-700 dark:hover:text-red-400 font-bold inline-flex items-center gap-1.5 bg-green-50/60 dark:bg-green-950/20 border border-green-200/50 dark:border-green-800/40 px-3 py-2.5 rounded-xl text-green-700 dark:text-green-400 transition-colors w-full justify-center">
											<svg class="w-4 h-4 fill-current" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16.01 3.2c-7.04 0-12.77 5.72-12.77 12.76 0 2.25.59 4.44 1.72 6.37L3.12 29l6.83-1.79a12.7 12.7 0 0 0 6.06 1.54c7.04 0 12.77-5.72 12.77-12.76S23.05 3.2 16.01 3.2Zm0 23.39c-1.91 0-3.79-.51-5.43-1.49l-.39-.23-4.05 1.06 1.08-3.95-.25-.41a10.56 10.56 0 0 1-1.62-5.61c0-5.86 4.78-10.63 10.65-10.63 5.86 0 10.64 4.77 10.64 10.63 0 5.86-4.78 10.63-10.64 10.63Zm5.83-7.96c-.32-.16-1.89-.93-2.18-1.04-.29-.11-.5-.16-.71.16-.21.32-.82 1.04-1.01 1.25-.19.21-.37.24-.69.08-.32-.16-1.35-.5-2.58-1.59-.95-.85-1.9-1.9-1.79-2.22-.19-.32-.02-.49.14-.65.15-.15.32-.37.48-.56.16-.19.21-.32.32-.53.11-.21.05-.4-.03-.56-.08-.16-.71-1.71-.98-2.34-.26-.62-.52-.53-.71-.54h-.61c-.21 0-.56.08-.85.4-.29.32-1.12 1.09-1.12 2.66s1.15 3.09 1.31 3.3c.16.21 2.27 3.46 5.5 4.85.77.33 1.37.53 1.84.68.77.24 1.48.21 2.04.13.62-.09 1.89-.77 2.16-1.52.27-.75.27-1.39.19-1.52-.08-.13-.29-.21-.61-.37Z"/></svg>
											<?php esc_html_e( 'Hubungi WhatsApp', 'sukusastra' ); ?>
										</a>
									<?php elseif ( filter_var( $contact, FILTER_VALIDATE_EMAIL ) ) : ?>
										<a href="mailto:<?php echo esc_attr( $contact ); ?>" class="no-underline text-slate-700 dark:text-zinc-300 hover:text-red-700 dark:hover:text-red-400 font-bold inline-flex items-center gap-1.5 bg-slate-50 dark:bg-zinc-900 border border-slate-200/60 dark:border-zinc-800 px-3 py-2.5 rounded-xl transition-colors w-full justify-center">
											<svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
											<?php echo esc_html( $contact ); ?>
										</a>
									<?php else : ?>
										<span class="text-slate-700 dark:text-zinc-300 font-bold leading-normal block text-center"><?php echo esc_html( $contact ); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Full Description Content -->
					<div class="ss-reading mt-8">
						<h2 class="text-xl font-black mb-4"><?php esc_html_e( 'Tentang Komunitas', 'sukusastra' ); ?></h2>
						<?php the_content(); ?>
					</div>

					<!-- Kegiatan & Publikasi sections -->
					<?php if ( $activities || $publications ) : ?>
						<div class="mt-10 grid gap-8 border-t border-slate-100 pt-8 dark:border-zinc-800/80">
							<?php if ( $activities ) : ?>
								<div>
									<h3 class="text-lg font-black mb-3 text-slate-900 dark:text-zinc-50"><?php esc_html_e( 'Kegiatan Utama', 'sukusastra' ); ?></h3>
									<div class="text-sm leading-relaxed text-slate-700 dark:text-zinc-300 bg-slate-50 dark:bg-zinc-900/30 border border-slate-200/50 dark:border-zinc-800/60 rounded-2xl p-5">
										<?php echo wpautop( esc_html( $activities ) ); ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if ( $publications ) : ?>
								<div>
									<h3 class="text-lg font-black mb-3 text-slate-900 dark:text-zinc-50"><?php esc_html_e( 'Publikasi Karya', 'sukusastra' ); ?></h3>
									<div class="text-sm leading-relaxed text-slate-700 dark:text-zinc-300 bg-slate-50 dark:bg-zinc-900/30 border border-slate-200/50 dark:border-zinc-800/60 rounded-2xl p-5">
										<?php echo wpautop( esc_html( $publications ) ); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<!-- Galeri Aktivitas -->
					<?php if ( ! empty( $gallery_array ) ) : ?>
						<div class="mt-10 border-t border-slate-100 pt-8 dark:border-zinc-800/80">
							<h3 class="text-lg font-black mb-4 text-slate-900 dark:text-zinc-50"><?php esc_html_e( 'Galeri Aktivitas', 'sukusastra' ); ?></h3>
							<!-- Responsive Photo Grid -->
							<div class="grid gap-4 grid-cols-2 sm:grid-cols-3">
								<?php foreach ( $gallery_array as $img_id ) : ?>
									<?php 
									$thumb_url = wp_get_attachment_image_url( (int) $img_id, 'medium' );
									$full_url  = wp_get_attachment_image_url( (int) $img_id, 'full' );
									if ( $thumb_url ) :
										?>
										<a href="<?php echo esc_url( $full_url ); ?>" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl border border-slate-200/60 dark:border-zinc-800 shadow-sm hover:scale-102 transition-transform duration-300 bg-slate-100 dark:bg-zinc-900 aspect-square">
											<img src="<?php echo esc_url( $thumb_url ); ?>" class="w-full h-full object-cover" alt="<?php esc_attr_e( 'Foto Aktivitas Komunitas', 'sukusastra' ); ?>" />
										</a>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

					<!-- Related Posts -->
					<?php sukusastra_display_related_posts(); ?>
				</div>

				<!-- Sidebar Column -->
				<aside class="grid content-start gap-6">
					<!-- Sidebar Banners (Desktop only) -->
					<div class="hidden lg:block">
						<?php poetzen_render_sidebar_banners(); ?>
					</div>

				</aside>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
