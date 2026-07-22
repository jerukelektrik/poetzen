<?php
/**
 * News single template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php 
	$youtube = sukusastra_get_meta( get_the_ID(), '_ss_youtube_url' ); 
	$orig_author = sukusastra_get_original_author( get_the_ID() );
	?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[minmax(0,760px)_320px]">
				<div class="min-w-0">
				
				<!-- Mobile Sidebar Banner (Tampil hanya di Mobile) -->
				<div class="block lg:hidden mb-6">
					<?php poetzen_render_sidebar_banners(); ?>
				</div>

				<p class="ss-eyebrow mb-2"><?php esc_html_e( 'Peristiwa', 'sukusastra' ); ?></p>
				<h1 class="ss-page-title"><?php the_title(); ?></h1>
				<p class="mt-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-zinc-400">
					<?php
					echo esc_html( get_the_date() );
					if ( $orig_author ) {
						printf(
							' · <a class="underline hover:text-red-700 dark:hover:text-red-300" href="%1$s">%2$s</a>',
							esc_url( get_permalink( $orig_author->ID ) ),
							esc_html( $orig_author->post_title )
						);
					}
					?>
				</p>
				
				<!-- Media Embed Area -->
				<?php if ( $youtube ) : ?>
					<div class="mt-8 aspect-video w-full overflow-hidden rounded bg-slate-200 dark:bg-zinc-800 shadow-sm">
						<?php echo wp_oembed_get( esc_url( $youtube ) ); ?>
					</div>
				<?php elseif ( has_post_thumbnail() ) : ?>
					<div class="mt-8 rounded overflow-hidden shadow-sm">
						<?php 
						the_post_thumbnail( 
							'sukusastra-hero', 
							array( 
								'class'         => 'w-full object-cover',
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'decoding'      => 'async',
								'sizes'         => '(max-width: 768px) 100vw, 1280px',
							) 
						); 
						?>
					</div>
				<?php else : ?>
					<div class="mt-8 rounded overflow-hidden shadow-sm flex items-center justify-center bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800 p-8 h-64 md:h-[320px]">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php the_title_attribute(); ?>" class="h-32 w-32 md:h-40 md:w-40 opacity-60 dark:opacity-30 object-contain" />
					</div>
				<?php endif; ?>
				
				<div class="ss-reading mt-8"><?php the_content(); ?></div>


				<?php poetzen_render_article_banner(); ?>

				<?php if ( $orig_author ) : ?>
					<!-- Premium Original Author Box -->
					<div class="mt-10 flex flex-col sm:flex-row gap-5 rounded-2xl border border-slate-200 bg-slate-50/50 p-6 dark:border-zinc-800 dark:bg-zinc-900/30">
						<?php if ( has_post_thumbnail( $orig_author->ID ) ) : ?>
							<div class="h-20 w-20 shrink-0 self-center sm:self-start overflow-hidden rounded-full border border-slate-100 dark:border-zinc-800 shadow-sm">
								<?php echo get_the_post_thumbnail( $orig_author->ID, 'thumbnail', array( 'class' => 'h-full w-full object-cover' ) ); ?>
							</div>
						<?php else : ?>
							<div class="flex h-20 w-20 shrink-0 self-center sm:self-start items-center justify-center rounded-full bg-red-700 font-serif text-3xl font-black text-white dark:bg-zinc-800 shadow-sm">
								<?php echo esc_html( substr( $orig_author->post_title, 0, 1 ) ); ?>
							</div>
						<?php endif; ?>
						<div class="grid gap-2 text-center sm:text-left">
							<div>
								<span class="ss-eyebrow"><?php esc_html_e( 'Tentang Penulis', 'sukusastra' ); ?></span>
								<h3 class="text-xl tracking-tight ss-author-name mt-0.5">
									<a class="ss-card-title-link" href="<?php echo esc_url( get_permalink( $orig_author->ID ) ); ?>">
										<?php echo esc_html( $orig_author->post_title ); ?>
									</a>
								</h3>
								<?php 
								$tempat_lahir = sukusastra_get_meta( $orig_author->ID, '_ss_penulis_tempat_lahir' );
								$tanggal_lahir = sukusastra_get_meta( $orig_author->ID, '_ss_penulis_tanggal_lahir' );
								if ( $tempat_lahir || $tanggal_lahir ) : 
									?>
									<p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-medium">
										<?php 
										if ( $tempat_lahir && $tanggal_lahir ) {
											printf( esc_html__( 'Lahir: %1$s, %2$s', 'sukusastra' ), esc_html( $tempat_lahir ), esc_html( $tanggal_lahir ) );
										} elseif ( $tempat_lahir ) {
											printf( esc_html__( 'Lahir: %s', 'sukusastra' ), esc_html( $tempat_lahir ) );
										} else {
											printf( esc_html__( 'Lahir: %s', 'sukusastra' ), esc_html( $tanggal_lahir ) );
										}
										?>
									</p>
								<?php endif; ?>
							</div>
							<?php 
							$bio = sukusastra_get_meta( $orig_author->ID, '_ss_penulis_bio_summary' );
							if ( $bio ) : 
								?>
								<p class="ss-body-serif italic text-sm text-slate-600 dark:text-zinc-400 leading-relaxed"><?php echo esc_html( $bio ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>


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
				
				<?php if ( sukusastra_get_meta( get_the_ID(), '_ss_source_url' ) ) : ?>
					<div class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800">
						<a class="ss-button-secondary" href="<?php echo esc_url( sukusastra_get_meta( get_the_ID(), '_ss_source_url' ) ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'Sumber / Rujukan', 'sukusastra' ); ?> &rarr;
						</a>
				<?php endif; ?>

				<?php sukusastra_display_related_posts(); ?>
			</div>
			<?php get_template_part( 'template-parts/sidebar-single' ); ?>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
