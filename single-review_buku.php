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
	?>
	<article class="ss-section ss-single-review">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[280px_minmax(0,760px)]">
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
				<?php endif; ?>
				
				<div class="ss-card grid gap-2 text-sm rounded">
					<?php 
					$book_type_val = sukusastra_get_meta( $post_id, '_ss_book_type', 'novel' );
					$book_types = array(
						'puisi'    => __( 'Kumpulan Puisi', 'sukusastra' ),
						'cerpen'   => __( 'Kumpulan Cerpen', 'sukusastra' ),
						'novel'    => __( 'Novel', 'sukusastra' ),
						'nonfiksi' => __( 'Nonfiksi', 'sukusastra' ),
					);
					$book_type_label = isset( $book_types[ $book_type_val ] ) ? $book_types[ $book_type_val ] : $book_types['novel'];
					?>
					<p class="flex items-center flex-wrap">
						<strong><?php esc_html_e( 'Jenis Buku:', 'sukusastra' ); ?></strong>
						<span class="inline-block px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-red-50 text-red-700 border border-red-200/60 dark:bg-red-950/20 dark:text-red-300 dark:border-red-900/50 ml-1.5"><?php echo esc_html( $book_type_label ); ?></span>
					</p>
					<p><strong><?php esc_html_e( 'Judul Buku:', 'sukusastra' ); ?></strong> <?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_title', get_the_title() ) ); ?></p>
					<?php
					$book_author_info = sukusastra_get_book_author_info( $post_id );
					if ( ! empty( $book_author_info['name'] ) ) :
						?>
						<p>
							<strong><?php esc_html_e( 'Penulis:', 'sukusastra' ); ?></strong> 
							<?php if ( ! empty( $book_author_info['url'] ) ) : ?>
								<a class="underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( $book_author_info['url'] ); ?>"><?php echo esc_html( $book_author_info['name'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $book_author_info['name'] ); ?>
							<?php endif; ?>
						</p>
					<?php endif; ?>
					<?php if ( sukusastra_get_meta( $post_id, '_ss_book_publisher' ) ) : ?>
						<p><strong><?php esc_html_e( 'Penerbit:', 'sukusastra' ); ?></strong> <?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_publisher' ) ); ?></p>
					<?php endif; ?>
					<?php if ( sukusastra_get_meta( $post_id, '_ss_book_year' ) ) : ?>
						<p><strong><?php esc_html_e( 'Tahun Terbit:', 'sukusastra' ); ?></strong> <?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_year' ) ); ?></p>
					<?php endif; ?>
					<?php if ( sukusastra_get_meta( $post_id, '_ss_book_edition' ) ) : ?>
						<p><strong><?php esc_html_e( 'Cetakan:', 'sukusastra' ); ?></strong> <?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_edition' ) ); ?></p>
					<?php endif; ?>
					<?php if ( sukusastra_get_meta( $post_id, '_ss_book_pages' ) ) : ?>
						<p><strong><?php esc_html_e( 'Halaman:', 'sukusastra' ); ?></strong> <?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_book_pages' ) ); ?></p>
					<?php endif; ?>
					<?php
					$reviewer_info = sukusastra_get_reviewer_info( $post_id );
					if ( ! empty( $reviewer_info['name'] ) ) :
						?>
						<p>
							<strong><?php esc_html_e( 'Resensator:', 'sukusastra' ); ?></strong> 
							<?php if ( ! empty( $reviewer_info['url'] ) ) : ?>
								<a class="underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( $reviewer_info['url'] ); ?>"><?php echo esc_html( $reviewer_info['name'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $reviewer_info['name'] ); ?>
							<?php endif; ?>
						</p>
					<?php elseif ( $orig_author ) : ?>
						<p><strong><?php esc_html_e( 'Resensator:', 'sukusastra' ); ?></strong> <a class="underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( get_permalink( $orig_author->ID ) ); ?>"><?php echo esc_html( $orig_author->post_title ); ?></a></p>
					<?php endif; ?>

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

					<div class="grid gap-2 mt-3">
						<?php if ( $shopee_url ) : ?>
							<a class="flex items-center justify-center gap-2 rounded bg-[#EE4D2D] hover:bg-[#d73f22] text-white py-2 px-3 text-xs font-bold no-underline transition-colors shadow-sm" href="<?php echo esc_url( $shopee_url ); ?>" target="_blank" rel="noopener">
								<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path d="M15.26 16.93c.19-1.55-.81-2.54-3.45-3.39-1.28-.44-1.88-1.01-1.87-1.8.05-.87.87-1.51 1.94-1.53.85 0 1.68.26 2.38.74.1.06.16.05.22-.03.07-.12.26-.41.32-.51.04-.07.05-.15-.06-.23-.15-.11-.58-.34-.81-.44-.66-.28-1.36-.42-2.08-.42-1.58 0-2.82 1-2.93 2.34-.07.96.41 1.74 1.43 2.34.22.13 1.39.59 1.85.74 1.47.46 2.23 1.27 2.05 2.23-.16.87-1.07 1.43-2.33 1.44-1-.04-1.89-.44-2.59-.98l-.12-.09c-.09-.07-.18-.06-.24.02-.04.06-.31.45-.38.55-.06.09-.03.14.04.19.29.24.68.51.94.64.73.37 1.52.57 2.34.6.59.03 1.17-.07 1.72-.29.91-.38 1.49-1.15 1.61-2.11ZM12 3.24c-1.71 0-3.1 1.61-3.17 3.63h6.34C15.1 4.85 13.71 3.24 12 3.24Zm6.49 18.68H5.37c-.89-.03-1.54-.75-1.63-1.65v-.16L3.15 7.27a.38.38 0 0 1 .35-.41h4.13c.1-2.67 2.02-4.79 4.36-4.79s4.26 2.12 4.36 4.79h4.11c.21 0 .38.17.38.38v.02l-.64 12.89v.11c-.08.9-.82 1.63-1.72 1.66Z"/>
								</svg>
								<?php esc_html_e( 'Beli di Shopee', 'sukusastra' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( $tokopedia_url ) : ?>
							<a class="flex items-center justify-center gap-2 rounded bg-[#03AC0E] hover:bg-[#028b0b] text-white py-2 px-3 text-xs font-bold no-underline transition-colors shadow-sm" href="<?php echo esc_url( $tokopedia_url ); ?>" target="_blank" rel="noopener">
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
							<a class="flex items-center justify-center gap-2 rounded bg-[#25D366] hover:bg-[#1ebd53] text-white py-2 px-3 text-xs font-bold no-underline transition-colors shadow-sm" href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener">
								<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
									<path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.248 8.477 3.517 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.453L0 24zm6.59-4.846c1.6-.95 3.167-1.465 5.05-1.464 5.378 0 9.754-4.373 9.757-9.751.002-2.578-1.002-5.001-2.83-6.832C16.747 1.277 14.326.275 11.75.275 6.376.275 2.003 4.646 2 10.027c-.001 2.012.527 3.979 1.53 5.707l-.99 3.62 3.71-.973zm10.74-5.263c-.266-.134-1.57-.775-1.81-.863-.242-.088-.419-.132-.596.132-.176.265-.682.863-.837 1.04-.155.176-.31.198-.577.065-.266-.134-1.127-.416-2.148-1.328-.793-.708-1.33-1.582-1.485-1.848-.155-.266-.017-.41.117-.542.12-.12.266-.31.4-.464.133-.155.177-.265.266-.442.088-.176.044-.331-.022-.464-.067-.132-.596-1.437-.816-1.967-.215-.518-.432-.447-.596-.455-.155-.008-.331-.008-.507-.008-.176 0-.463.066-.706.331-.242.265-.926.906-.926 2.21 0 1.303.948 2.562 1.08 2.738.132.176 1.865 2.848 4.518 3.993.63.272 1.122.435 1.506.557.633.201 1.21.173 1.666.105.508-.076 1.57-.64 1.792-1.258.22-.617.22-1.146.155-1.258-.066-.11-.242-.176-.508-.31z"/>
								</svg>
								<?php esc_html_e( 'Beli via WhatsApp', 'sukusastra' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( ! $shopee_url && ! $tokopedia_url && ! $whatsapp_url && '' !== $cta_label && '' !== $cta_url ) : ?>
							<a class="ss-button mt-1" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $cta_label ); ?></a>
						<?php endif; ?>
					</div>
				</div>
				<div class="hidden lg:block">
					<?php get_template_part( 'template-parts/sidebar-single' ); ?>
				</div>
			</aside>
			
			<!-- Review Details Content -->
			<div>
				<p class="ss-eyebrow mb-2">
					<?php 
					printf( '%s (%s)', esc_html__( 'Review Buku', 'sukusastra' ), esc_html( $book_type_label ) );
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
				
				<?php if ( $book_image_id && has_post_thumbnail() ) : ?>
					<div class="mt-8 rounded overflow-hidden shadow-sm">
						<?php the_post_thumbnail( 'sukusastra-hero', array( 'class' => 'w-full object-cover' ) ); ?>
					</div>
				<?php endif; ?>
				
				<div class="ss-reading mt-8"><?php the_content(); ?></div>

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
					</div>
				<?php endif; ?>

				<div class="mt-10 lg:hidden">
					<?php get_template_part( 'template-parts/sidebar-single' ); ?>
				</div>
			</div>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
