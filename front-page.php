<?php
/**
 * Homepage template.
 *
 * @package SukuSastra
 */
get_header();

// Fetch latest 3 posts for the Hero Grid
$hero_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'meta_query'          => array(
			'relation' => 'OR',
			array(
				'key'     => '_ss_show_home',
				'value'   => '1',
				'compare' => '=',
			),
			array(
				'key'     => '_ss_show_home',
				'compare' => 'NOT EXISTS',
			),
		),
	)
);
?>

<!-- Editorial Hero Grid -->
<section class="ss-section border-t-0 bg-slate-55/30 dark:bg-black/20">
	<div class="ss-container">
		<?php if ( $hero_query->have_posts() ) : ?>
			<div class="grid gap-6 md:grid-cols-3">
				<?php 
				$count = 0;
				while ( $hero_query->have_posts() ) : $hero_query->the_post();
					$count++;
					$categories = get_the_category();
					$category_name = ! empty( $categories ) ? $categories[0]->name : esc_html__( 'Karya', 'sukusastra' );
					$orig_author = sukusastra_get_original_author( get_the_ID() );
					
					if ( 1 === $count ) : 
						// Main Feature Card (Spans 2 columns on desktop)
						?>
						<article <?php post_class( 'relative overflow-hidden rounded-2xl group aspect-[16/9] w-full md:col-span-2 shadow-md border border-slate-200/10' ); ?>>
							<a class="absolute inset-0 z-0 block" href="<?php the_permalink(); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'sukusastra-hero', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500' ) ); ?>
								<?php else : ?>
									<div class="w-full h-full bg-gradient-to-br from-zinc-800 to-zinc-900 group-hover:scale-105 transition-transform duration-500"></div>
								<?php endif; ?>
								<!-- Overlay Gradient -->
								<div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent z-1"></div>
							</a>
							<!-- Text Content -->
							<div class="absolute bottom-0 inset-x-0 p-6 z-10 pointer-events-none flex flex-col gap-2 justify-end">
								<div class="flex flex-wrap items-center gap-2 text-xs ss-hero-meta">
									<span class="ss-hero-category"><?php echo esc_html( $category_name ); ?></span>
									<span>&bull;</span>
									<span><?php echo get_the_date(); ?></span>
									<?php if ( $orig_author ) : ?>
										<span>&bull;</span>
										<a class="pointer-events-auto text-zinc-300 hover:text-red-300 transition-colors no-underline z-20 relative" href="<?php echo esc_url( get_permalink( $orig_author->ID ) ); ?>">
											<?php echo esc_html( $orig_author->post_title ); ?>
										</a>
									<?php else : ?>
										<span>&bull;</span>
										<span class="text-zinc-350"><?php echo esc_html( get_the_author() ); ?></span>
									<?php endif; ?>
								</div>
								<h2 class="text-xl md:text-3xl font-black text-white leading-tight tracking-tight line-clamp-2">
									<a class="pointer-events-auto text-white hover:text-red-300 transition-colors no-underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<p class="hidden sm:block text-xs md:text-sm text-zinc-300 line-clamp-2 mt-1 max-w-2xl"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
							</div>
						</article>
						
						<!-- Stack for Side Features -->
						<div class="grid gap-6 md:grid-rows-2">
						<?php 
					else : 
						// Side Features (Span 1 column)
						?>
						<article <?php post_class( 'relative overflow-hidden rounded-2xl group aspect-[16/9] md:aspect-auto md:h-full w-full shadow-md border border-slate-200/10' ); ?>>
							<a class="absolute inset-0 z-0 block" href="<?php the_permalink(); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'sukusastra-cover', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500' ) ); ?>
								<?php else : ?>
									<div class="w-full h-full bg-gradient-to-br from-zinc-800 to-zinc-900 group-hover:scale-105 transition-transform duration-500"></div>
								<?php endif; ?>
								<!-- Overlay Gradient -->
								<div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent z-1"></div>
							</a>
							<!-- Text Content -->
							<div class="absolute bottom-0 inset-x-0 p-5 z-10 pointer-events-none flex flex-col gap-1.5 justify-end">
								<div class="flex flex-wrap items-center gap-2 text-[11px] ss-hero-meta">
									<span class="ss-hero-category"><?php echo esc_html( $category_name ); ?></span>
									<span>&bull;</span>
									<span><?php echo get_the_date(); ?></span>
									<?php if ( $orig_author ) : ?>
										<span>&bull;</span>
										<a class="pointer-events-auto text-zinc-300 hover:text-red-300 transition-colors no-underline z-20 relative max-w-[120px] truncate" href="<?php echo esc_url( get_permalink( $orig_author->ID ) ); ?>" title="<?php echo esc_attr( $orig_author->post_title ); ?>">
											<?php echo esc_html( $orig_author->post_title ); ?>
										</a>
									<?php else : ?>
										<span>&bull;</span>
										<span class="text-zinc-350 max-w-[120px] truncate" title="<?php echo esc_attr( get_the_author() ); ?>"><?php echo esc_html( get_the_author() ); ?></span>
									<?php endif; ?>
								</div>
								<h3 class="text-base md:text-lg font-black text-white leading-snug tracking-tight line-clamp-2">
									<a class="pointer-events-auto text-white hover:text-red-300 transition-colors no-underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h3>
							</div>
						</article>
						<?php 
					endif;
				endwhile; 
				wp_reset_postdata();
				if ( $count > 1 ) {
					echo '</div>'; // Close side stack
				}
				?>
			</div>
		<?php else : ?>
			<p class="text-center py-12 text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Belum ada terbitan terbaru.', 'sukusastra' ); ?></p>
		<?php endif; ?>
	</div>
</section>



<!-- Category Feeds (Puisi, Cerpen, Esai) -->
<?php foreach ( array( 'puisi' => 'Puisi Terbaru', 'cerpen' => 'Cerpen Terbaru', 'esai' => 'Esai Terbaru' ) as $slug => $title ) : ?>
	<?php $query = sukusastra_home_posts( $slug, 3 ); ?>
	<?php if ( $query->have_posts() ) : ?>
		<section class="ss-section bg-slate-55/50 dark:bg-black/20">
			<div class="ss-container grid gap-6">
				<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
					<h2 class="ss-section-title"><?php echo esc_html( $title ); ?></h2>
					<a class="ss-eyebrow" href="<?php echo esc_url( get_category_link( get_category_by_slug( $slug ) ) ); ?>">
						<?php esc_html_e( 'Lihat Semua', 'sukusastra' ); ?> &rarr;
					</a>
				</div>
				<div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php get_template_part( 'template-parts/cards/post-card' ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
<?php endforeach; ?>

<!-- Book Reviews Section -->
<?php $reviews = sukusastra_latest_cpt( 'review_buku', 4 ); ?>
<?php if ( $reviews->have_posts() ) : ?>
	<section class="ss-section">
		<div class="ss-container grid gap-6">
			<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
				<h2 class="ss-section-title"><?php esc_html_e( 'Review Buku', 'sukusastra' ); ?></h2>
				<a class="ss-eyebrow" href="<?php echo esc_url( get_post_type_archive_link( 'review_buku' ) ); ?>">
					<?php esc_html_e( 'Semua Review', 'sukusastra' ); ?> &rarr;
				</a>
			</div>
			<div class="grid gap-5 md:grid-cols-2">
				<?php while ( $reviews->have_posts() ) : $reviews->the_post(); ?>
					<?php get_template_part( 'template-parts/cards/review-card' ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- News and Events Section -->
<section class="ss-section border-b border-slate-200 dark:border-zinc-800">
	<div class="ss-container grid gap-10 lg:grid-cols-2">
		<!-- News CPT Feed -->
		<div class="grid content-start gap-6">
			<?php $news = sukusastra_latest_cpt( 'berita', 3 ); ?>
			<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
				<h2 class="ss-section-title"><?php esc_html_e( 'Berita Suku Sastra', 'sukusastra' ); ?></h2>
				<a class="ss-eyebrow" href="<?php echo esc_url( get_post_type_archive_link( 'berita' ) ); ?>">
					<?php esc_html_e( 'Semua Berita', 'sukusastra' ); ?> &rarr;
				</a>
			</div>
			<?php if ( $news->have_posts() ) : ?>
				<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
					<?php while ( $news->have_posts() ) : $news->the_post(); ?>
						<?php get_template_part( 'template-parts/cards/news-card' ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<p class="text-sm text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Belum ada berita terbaru.', 'sukusastra' ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Upcoming Events CPT Feed -->
		<div class="grid content-start gap-6">
			<?php $events = sukusastra_upcoming_events( 3 ); ?>
			<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
				<h2 class="ss-section-title"><?php esc_html_e( 'Agenda & Event Sastra', 'sukusastra' ); ?></h2>
				<a class="ss-eyebrow" href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>">
					<?php esc_html_e( 'Semua Event', 'sukusastra' ); ?> &rarr;
				</a>
			</div>
			<?php if ( $events->have_posts() ) : ?>
				<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
					<?php while ( $events->have_posts() ) : $events->the_post(); ?>
						<?php get_template_part( 'template-parts/cards/event-card' ); ?>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<p class="text-sm text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Belum ada agenda terdekat.', 'sukusastra' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php get_footer(); ?>
