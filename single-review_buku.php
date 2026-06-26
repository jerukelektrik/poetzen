<?php
/**
 * Book review single template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php
	$post_id = get_the_ID();
	$cta_label = sukusastra_cta_label( sukusastra_get_meta( $post_id, '_ss_marketplace_label' ), sukusastra_get_meta( $post_id, '_ss_contact_label' ) );
	$cta_url = sukusastra_get_meta( $post_id, '_ss_marketplace_url', sukusastra_get_meta( $post_id, '_ss_contact_url' ) );
	$book_image_id = sukusastra_get_meta( $post_id, '_ss_book_image_id' );
	$orig_author = sukusastra_get_original_author( $post_id );
	$book_type_val = sukusastra_get_meta( $post_id, '_ss_book_type', 'novel' );
	$book_types = array(
		'puisi'    => __( 'Kumpulan Puisi', 'sukusastra' ),
		'cerpen'   => __( 'Kumpulan Cerpen', 'sukusastra' ),
		'novel'    => __( 'Novel', 'sukusastra' ),
		'nonfiksi' => __( 'Nonfiksi', 'sukusastra' ),
	);
	$book_type_label = isset( $book_types[ $book_type_val ] ) ? $book_types[ $book_type_val ] : $book_types['novel'];
	?>
	<article class="ss-section ss-single-review">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[280px_minmax(0,760px)]">
			<div class="grid gap-2 lg:hidden">
				
				<!-- Mobile Sidebar Banner (Tampil hanya di Mobile) -->
				<div class="block lg:hidden mb-4">
					<?php poetzen_render_sidebar_banners(); ?>
				</div>

				<p class="ss-eyebrow">
					<?php 
					printf( '%s (%s)', esc_html__( 'Reviu Buku', 'sukusastra' ), esc_html( $book_type_label ) );
					if ( $orig_author ) {
						printf(
							' · <a class="underline hover:text-red-700 dark:hover:text-red-300" href="%1$s">%2$s</a>',
							esc_url( get_permalink( $orig_author->ID ) ),
							esc_html( $orig_author->post_title )
						);
					}
					?>
				</p>
				<h1 class="ss-page-title"><?php the_title(); ?></h1>
			</div>

			<!-- Book Meta Sidebar -->
			<aside class="grid content-start gap-4 lg:sticky lg:top-24 self-start">
				<?php if ( $book_image_id ) : ?>
					<div class="rounded overflow-hidden shadow-md border border-slate-100 dark:border-zinc-800 bg-white dark:bg-[#262B4E] p-2">
						<p class="ss-meta mb-2 text-center"><?php esc_html_e( 'Sampul Buku', 'sukusastra' ); ?></p>
						<?php echo wp_get_attachment_image( $book_image_id, 'sukusastra-cover', false, array( 'class' => 'w-full object-cover rounded shadow-sm' ) ); ?>
					</div>
				<?php elseif ( has_post_thumbnail() ) : ?>
					<div class="rounded overflow-hidden shadow-md">
						<?php the_post_thumbnail( 'sukusastra-cover', array( 'class' => 'w-full object-cover' ) ); ?>
					</div>
				<?php else : ?>
					<div class="rounded overflow-hidden shadow-md border border-slate-200/60 dark:border-zinc-800 bg-white dark:bg-[#262B4E]/40 p-6 flex items-center justify-center aspect-[2/3] w-full">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php the_title_attribute(); ?>" class="max-h-36 max-w-full opacity-60 dark:opacity-30 object-contain" />
					</div>
				<?php endif; ?>
				
				<div class="ss-card rounded-3xl p-6 grid gap-5 bg-white dark:bg-[#262B4E]/40 shadow-sm border border-slate-200/60 dark:border-zinc-800/80 font-sans text-sm">
					<h3 class="ss-info-title m-0 text-slate-900 dark:text-zinc-50 text-base font-black uppercase tracking-wide"><?php esc_html_e( 'Identitas Buku', 'sukusastra' ); ?></h3>
					
					<div class="grid gap-4.5 text-sm">
						<!-- Jenis Buku -->
						<div>
							<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Jenis Buku', 'sukusastra' ); ?></span>
							<span class="inline-block px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-red-50 text-red-700 border border-red-200/60 dark:bg-red-950/20 dark:text-red-300 dark:border-red-900/50 mt-0.5"><?php echo esc_html( $book_type_label ); ?></span>
						</div>

						<!-- Judul Buku -->
						<div>
							<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Judul Buku', 'sukusastra' ); ?></span>
							<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_title', get_the_title() ) ); ?></span>
						</div>

						<!-- Penulis -->
						<?php
						$book_author_info = sukusastra_get_book_author_info( $post_id );
						if ( ! empty( $book_author_info['name'] ) ) :
							?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Penulis', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold">
									<?php if ( ! empty( $book_author_info['url'] ) ) : ?>
										<a class="underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( $book_author_info['url'] ); ?>"><?php echo esc_html( $book_author_info['name'] ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $book_author_info['name'] ); ?>
									<?php endif; ?>
								</span>
							</div>
						<?php endif; ?>

						<!-- Penerbit -->
						<?php if ( sukusastra_get_meta( $post_id, '_ss_book_publisher' ) ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Penerbit', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_publisher' ) ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Tahun Terbit -->
						<?php if ( sukusastra_get_meta( $post_id, '_ss_book_year' ) ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Tahun Terbit', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_year' ) ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Cetakan -->
						<?php if ( sukusastra_get_meta( $post_id, '_ss_book_edition' ) ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Cetakan', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_edition' ) ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Halaman -->
						<?php if ( sukusastra_get_meta( $post_id, '_ss_book_pages' ) ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Halaman', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_pages' ) ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Jenis Cover -->
						<?php if ( sukusastra_get_meta( $post_id, '_ss_book_cover_type' ) ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Jenis Cover', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_cover_type' ) ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Dimensi -->
						<?php if ( sukusastra_get_meta( $post_id, '_ss_book_dimensions' ) ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Dimensi', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_dimensions' ) ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Jenis Kertas -->
						<?php if ( sukusastra_get_meta( $post_id, '_ss_book_paper' ) ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Jenis Kertas', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_paper' ) ); ?></span>
							</div>
						<?php endif; ?>

						<!-- ISBN -->
						<?php if ( sukusastra_get_meta( $post_id, '_ss_book_isbn' ) ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'ISBN', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_isbn' ) ); ?></span>
							</div>
						<?php endif; ?>

						<!-- Resensator -->
						<?php
						$reviewer_info = sukusastra_get_reviewer_info( $post_id );
						if ( ! empty( $reviewer_info['name'] ) ) :
							?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Resensator', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold">
									<?php if ( ! empty( $reviewer_info['url'] ) ) : ?>
										<a class="underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( $reviewer_info['url'] ); ?>"><?php echo esc_html( $reviewer_info['name'] ); ?></a>
									<?php else : ?>
										<?php echo esc_html( $reviewer_info['name'] ); ?>
									<?php endif; ?>
								</span>
							</div>
						<?php elseif ( $orig_author ) : ?>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-550 mb-0.5"><?php esc_html_e( 'Resensator', 'sukusastra' ); ?></span>
								<span class="text-slate-700 dark:text-zinc-200 font-bold">
									<a class="underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( get_permalink( $orig_author->ID ) ); ?>"><?php echo esc_html( $orig_author->post_title ); ?></a>
								</span>
							</div>
						<?php endif; ?>
					</div>

					<?php
					$shopee_url   = sukusastra_get_meta( $post_id, '_ss_shopee_url' );
					$tokopedia_url = sukusastra_get_meta( $post_id, '_ss_tokopedia_url' );
					$whatsapp_val  = sukusastra_get_meta( $post_id, '_ss_whatsapp_url' );
					
					$whatsapp_url = '';
					if ( $whatsapp_val ) {
						if ( str_starts_with( $whatsapp_val, 'http' ) ) {
							$whatsapp_url = $whatsapp_val;
						} else {
							$clean_num = preg_replace( '/[^0-9]/', '', $whatsapp_val );
							if ( str_starts_with( $clean_num, '0' ) ) {
								$clean_num = '62' . substr( $clean_num, 1 );
							}
							$whatsapp_url = 'https://api.whatsapp.com/send?phone=' . $clean_num;
						}
					}
					?>

					<?php if ( $shopee_url || $tokopedia_url || $whatsapp_url || ( ! $shopee_url && ! $tokopedia_url && ! $whatsapp_url && '' !== $cta_label && '' !== $cta_url ) ) : ?>
						<div class="border-t border-slate-100 dark:border-zinc-800/80 pt-4 grid gap-2">
							<?php if ( $shopee_url ) : ?>
								<a class="flex items-center justify-center gap-2 rounded-xl bg-[#EE4D2D] hover:bg-[#d73f22] text-white py-2.5 px-3 text-xs font-bold no-underline transition-colors shadow-sm" href="<?php echo esc_url( $shopee_url ); ?>" target="_blank" rel="noopener">
									<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
										<path d="M15.26 16.93c.19-1.55-.81-2.54-3.45-3.39-1.28-.44-1.88-1.01-1.87-1.8.05-.87.87-1.51 1.94-1.53.85 0 1.68.26 2.38.74.1.06.16.05.22-.03.07-.12.26-.41.32-.51.04-.07.05-.15-.06-.23-.15-.11-.58-.34-.81-.44-.66-.28-1.36-.42-2.08-.42-1.58 0-2.82 1-2.93 2.34-.07.96.41 1.74 1.43 2.34.22.13 1.39.59 1.85.74 1.47.46 2.23 1.27 2.05 2.23-.16.87-1.07 1.43-2.33 1.44-1-.04-1.89-.44-2.59-.98l-.12-.09c-.09-.07-.18-.06-.24.02-.04.06-.31.45-.38.55-.06.09-.03.14.04.19.29.24.68.51.94.64.73.37 1.52.57 2.34.6.59.03 1.17-.07 1.72-.29.91-.38 1.49-1.15 1.61-2.11ZM12 3.24c-1.71 0-3.1 1.61-3.17 3.63h6.34C15.1 4.85 13.71 3.24 12 3.24Zm6.49 18.68H5.37c-.89-.03-1.54-.75-1.63-1.65v-.16L3.15 7.27a.38.38 0 0 1 .35-.41h4.13c.1-2.67 2.02-4.79 4.36-4.79s4.26 2.12 4.36 4.79h4.11c.21 0 .38.17.38.38v.02l-.64 12.89v.11c-.08.9-.82 1.63-1.72 1.66Z"/>
									</svg>
									<?php esc_html_e( 'Beli di Shopee', 'sukusastra' ); ?>
								</a>
							<?php endif; ?>

							<?php if ( $tokopedia_url ) : ?>
								<a class="flex items-center justify-center gap-2 rounded-xl bg-[#03AC0E] hover:bg-[#028b0b] text-white py-2.5 px-3 text-xs font-bold no-underline transition-colors shadow-sm" href="<?php echo esc_url( $tokopedia_url ); ?>" target="_blank" rel="noopener">
									<svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M8 8a4 4 0 0 1 8 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
										<rect x="4" y="8" width="16" height="13" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
										<circle cx="8.5" cy="13.5" r="1.8" stroke="currentColor" stroke-width="1.2"/>
										<circle cx="15.5" cy="13.5" r="1.8" stroke="currentColor" stroke-width="1.2"/>
										<circle cx="8.5" cy="13.5" r="0.8" fill="currentColor"/>
										<circle cx="15.5" cy="13.5" r="0.8" fill="currentColor"/>
										<path d="M11.2 14.8h1.6l-.8 1.2-0.8-1.2z" fill="currentColor"/>
									</svg>
									<?php esc_html_e( 'Beli di Tokopedia', 'sukusastra' ); ?>
								</a>
							<?php endif; ?>

							<?php if ( $whatsapp_url ) : ?>
								<a class="flex items-center justify-center gap-2 rounded-xl bg-[#25D366] hover:bg-[#1ebd53] text-white py-2.5 px-3 text-xs font-bold no-underline transition-colors shadow-sm" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener">
									<svg class="h-4 w-4 shrink-0 overflow-visible" viewBox="0 0 32 32" aria-hidden="true" focusable="false" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill="currentColor" d="M16 5.25c-5.88 0-10.65 4.67-10.65 10.43 0 1.98.57 3.84 1.57 5.42l-1.02 5.65 5.82-1.33c1.3.45 2.74.7 4.28.7 5.88 0 10.65-4.67 10.65-10.44S21.88 5.25 16 5.25Zm0 18.92c-1.39 0-2.69-.28-3.84-.8l-.38-.17-3.22.74.57-3.13-.24-.4a8.47 8.47 0 0 1-1.35-4.73c0-4.69 3.8-8.49 8.46-8.49s8.46 3.8 8.46 8.49c0 4.68-3.8 8.49-8.46 8.49Z"/>
										<path fill="currentColor" d="M20.75 18.44c-.25-.13-1.47-.73-1.7-.81-.23-.09-.39-.13-.56.13-.16.25-.64.81-.79.97-.14.17-.29.19-.54.07-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.48-1.38-1.73-.15-.25-.02-.39.11-.52.12-.11.25-.29.38-.44.13-.15.17-.25.25-.42.08-.16.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.22.25-.86.85-.86 2.07s.88 2.4 1.01 2.56c.12.17 1.74 2.69 4.23 3.77.59.25 1.05.4 1.41.51.59.19 1.13.16 1.55.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.08.15-1.18-.06-.11-.23-.17-.48-.29Z"/>
									</svg>
									<?php esc_html_e( 'Beli via WhatsApp', 'sukusastra' ); ?>
								</a>
							<?php endif; ?>

							<?php if ( ! $shopee_url && ! $tokopedia_url && ! $whatsapp_url && '' !== $cta_label && '' !== $cta_url ) : ?>
								<a class="ss-button mt-1 rounded-xl py-2.5" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="hidden lg:block">
					<?php get_template_part( 'template-parts/sidebar-single' ); ?>
				</div>
			</aside>
			
			<!-- Review Details Content -->
			<div>
				<p class="ss-eyebrow mb-2 hidden lg:block">
					<?php 
					printf( '%s (%s)', esc_html__( 'Reviu Buku', 'sukusastra' ), esc_html( $book_type_label ) );
					if ( $orig_author ) {
						printf(
							' · <a class="underline hover:text-red-700 dark:hover:text-red-300" href="%1$s">%2$s</a>',
							esc_url( get_permalink( $orig_author->ID ) ),
							esc_html( $orig_author->post_title )
						);
					}
					?>
				</p>
				<h1 class="ss-page-title hidden lg:block"><?php the_title(); ?></h1>
				
				<?php if ( $book_image_id && has_post_thumbnail() ) : ?>
					<div class="mt-8 rounded overflow-hidden shadow-sm">
						<?php the_post_thumbnail( 'sukusastra-hero', array( 'class' => 'w-full object-cover' ) ); ?>
					</div>
				<?php endif; ?>
				
				<div class="ss-reading mt-8"><?php the_content(); ?></div>

				<?php poetzen_render_article_banner(); ?>

				<?php 
				$tags = get_the_tags();
				if ( $tags ) : 
					?>
					<div class="mt-8 flex flex-wrap gap-2 items-center">
						<span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mr-1"><?php esc_html_e( 'Topik:', 'sukusastra' ); ?></span>
						<?php foreach ( $tags as $tag ) : ?>
							<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="px-3 py-1 text-xs font-semibold rounded-full border border-slate-200 hover:border-red-700 hover:text-red-700 dark:border-zinc-800 dark:hover:border-red-400 dark:hover:text-red-400 text-slate-650 dark:text-zinc-400 transition-colors no-underline">
								#<?php echo esc_html( $tag->name ); ?>
							</a>
						<?php endforeach; ?>
				<?php endif; ?>

				<?php sukusastra_display_related_posts(); ?>

				<div class="mt-10 lg:hidden">
					<?php get_template_part( 'template-parts/sidebar-single' ); ?>
				</div>
			</div>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
