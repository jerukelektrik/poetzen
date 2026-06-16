<?php
/**
 * Standard post single template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[minmax(0,760px)_320px]">
				<div>
				<?php $orig_author = sukusastra_get_original_author( get_the_ID() ); ?>
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
				<?php if ( has_post_thumbnail() ) : ?>
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

				<?php 
				$pesan_moral = sukusastra_get_meta( get_the_ID(), '_ss_pesan_moral' );
				if ( $pesan_moral ) : 
					?>
					<div class="mt-8 p-5 border-l-4 border-red-700 bg-red-50/30 dark:bg-red-950/10 dark:border-red-500 rounded-r-xl">
						<span class="block text-xs font-bold uppercase tracking-wider text-red-700 dark:text-red-400 mb-1.5"><?php esc_html_e( 'Pesan Moral / Hikmah Cerita', 'sukusastra' ); ?></span>
						<p class="ss-body-serif italic text-slate-800 dark:text-zinc-200"><?php echo nl2br( esc_html( $pesan_moral ) ); ?></p>
					</div>
				<?php endif; ?>

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
											printf( esc_html__( 'Lahir di %s', 'sukusastra' ), esc_html( $tempat_lahir ) );
										} else {
											printf( esc_html__( 'Lahir: %s', 'sukusastra' ), esc_html( $tanggal_lahir ) );
										}
										?>
									</p>
								<?php endif; ?>
							</div>
							<?php 
							$bio_summary = sukusastra_get_meta( $orig_author->ID, '_ss_penulis_bio_summary' );
							if ( $bio_summary ) : 
								?>
								<p class="ss-body"><?php echo esc_html( $bio_summary ); ?></p>
							<?php endif; ?>
							<a class="text-xs font-bold text-red-700 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 w-fit self-center sm:self-start mt-1 no-underline border-b border-transparent hover:border-red-700 dark:hover:border-red-400" href="<?php echo esc_url( get_permalink( $orig_author->ID ) ); ?>">
								<?php esc_html_e( 'Baca Biografi Selengkapnya', 'sukusastra' ); ?> &rarr;
							</a>
						</div>
					</div>
				<?php endif; ?>
				
				<?php sukusastra_display_related_posts(); ?>
			</div>
			<?php get_template_part( 'template-parts/sidebar-single' ); ?>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
