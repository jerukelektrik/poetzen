<?php
/**
 * Homepage template.
 *
 * @package SukuSastra
 */
get_header();

// Fetch latest 4 posts for the Hero Grid
$hero_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 4,
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

<!-- Editorial Hero Grid & News Ticker -->
<section class="ss-section border-t-0 bg-slate-55/30 dark:bg-black/20">
	<div class="ss-container">
		<?php 
		// Fetch ongoing/upcoming events for the News Update ticker
		$news_update_events = new WP_Query( array(
			'post_type'      => 'event',
			'posts_per_page' => 10,
			'meta_key'       => '_ss_event_start',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_ss_event_status',
					'value'   => 'upcoming',
					'compare' => '=',
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => '_ss_event_end',
						'value'   => date( 'Y-m-d' ),
						'compare' => '>=',
						'type'    => 'DATE',
					),
					array(
						'key'     => '_ss_event_end',
						'compare' => 'NOT EXISTS',
					),
				),
			),
		) );
		
		if ( ! $news_update_events->have_posts() ) {
			// Fallback: Latest events regardless of status
			$news_update_events = new WP_Query( array(
				'post_type'      => 'event',
				'posts_per_page' => 10,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post_status'    => 'publish'
			) );
		}
		if ( ! $news_update_events->have_posts() ) {
			// Fallback: Latest posts
			$news_update_events = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => 10,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post_status'    => 'publish'
			) );
		}

		if ( $news_update_events->have_posts() ) :
		?>
		<!-- 1. News Update Ticker Bar -->
		<div class="mb-8 flex items-center border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900/80 backdrop-blur rounded-full p-1 pr-6 shadow-sm overflow-hidden">
			<div class="flex items-center bg-amber-400 text-amber-950 px-4 py-1.5 rounded-full text-xs font-black shrink-0 shadow-sm">
				<!-- Bell SVG Icon -->
				<svg class="w-3.5 h-3.5 mr-1.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
				</svg>
				<span>News Update:</span>
			</div>
			<div class="flex-1 overflow-hidden relative mx-4 text-xs font-semibold text-slate-700 dark:text-zinc-300">
				<div class="animate-marquee whitespace-nowrap flex items-center">
					<?php 
					$ticker_items = array();
					while ( $news_update_events->have_posts() ) : $news_update_events->the_post();
						$ticker_items[] = sprintf(
							'<a href="%1$s" class="hover:text-red-700 dark:hover:text-red-400 transition-colors">%2$s</a>',
							esc_url( get_permalink() ),
							esc_html( get_the_title() )
						);
					endwhile;
					wp_reset_postdata();
					
					$ticker_html = implode( ' <span class="mx-4 text-slate-350 dark:text-zinc-650">•</span> ', $ticker_items );
					echo $ticker_html . ' <span class="mx-4 text-slate-350 dark:text-zinc-650">•</span> ' . $ticker_html;
					?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<!-- 2. Hero 2-Column Layout (Widescreen Card on Left + 3 Stacked List Items on Right) -->
		<?php if ( $hero_query->have_posts() ) : ?>
			<div class="grid gap-8 grid-cols-1 lg:grid-cols-3">
				<?php 
				$hero_posts = array();
				while ( $hero_query->have_posts() ) {
					$hero_query->the_post();
					$categories = get_the_category();
					$orig_author = sukusastra_get_original_author( get_the_ID() );
					
					$author_name = esc_html__( 'Suku Sastra', 'sukusastra' );
					$author_avatar = '';
					if ( $orig_author ) {
						$author_name = $orig_author->post_title;
						if ( has_post_thumbnail( $orig_author->ID ) ) {
							$author_avatar = get_the_post_thumbnail_url( $orig_author->ID, 'thumbnail' );
						}
					}
					if ( ! $author_avatar ) {
						$author_avatar = get_avatar_url( get_the_author_meta( 'ID' ) );
					}

					$hero_posts[] = array(
						'id'          => get_the_ID(),
						'title'       => get_the_title(),
						'permalink'   => get_permalink(),
						'date'        => get_the_date(),
						'time_ago'    => human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . esc_html__( 'lalu', 'sukusastra' ),
						'excerpt'     => wp_strip_all_tags( get_the_excerpt() ),
						'thumbnail'   => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : '',
						'category'    => ! empty( $categories ) ? $categories[0]->name : esc_html__( 'Karya', 'sukusastra' ),
						'author_name' => $author_name,
						'avatar'      => $author_avatar
					);
				}
				wp_reset_postdata();
				
				// Render Column 1: Main Feature (First Post - Spans 2 Columns)
				if ( isset( $hero_posts[0] ) ) :
					$main_post = $hero_posts[0];
					?>
					<div class="lg:col-span-2">
						<article class="flex flex-col">
							<!-- 1. Large Widescreen Image -->
							<div class="relative w-full aspect-[16/10] rounded-3xl overflow-hidden group shadow-sm bg-slate-900 border border-slate-200/10">
								<a class="absolute inset-0 z-0 block" href="<?php echo esc_url( $main_post['permalink'] ); ?>">
									<?php if ( $main_post['thumbnail'] ) : ?>
										<img src="<?php echo esc_url( $main_post['thumbnail'] ); ?>" alt="<?php echo esc_attr( $main_post['title'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-95">
									<?php else : ?>
										<div class="w-full h-full bg-gradient-to-br from-zinc-800 to-zinc-900 group-hover:scale-105 transition-transform duration-500 opacity-95"></div>
									<?php endif; ?>
								</a>
							</div>
							
							<!-- 2. Title printed underneath -->
							<h2 class="text-xl md:text-3xl font-black text-slate-900 dark:text-zinc-50 leading-tight mt-4 line-clamp-2">
								<a class="no-underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( $main_post['permalink'] ); ?>">
									<?php echo esc_html( $main_post['title'] ); ?>
								</a>
							</h2>
							
							<!-- 3. Author Row underneath -->
							<div class="flex items-center gap-3 mt-3">
								<img class="w-8 h-8 rounded-full object-cover border border-slate-100 dark:border-zinc-800 shadow-sm" src="<?php echo esc_url( $main_post['avatar'] ); ?>" alt="<?php echo esc_attr( $main_post['author_name'] ); ?>">
								<div class="grid gap-0.5">
									<span class="text-xs font-bold text-slate-900 dark:text-zinc-100 leading-none"><?php echo esc_html( $main_post['author_name'] ); ?></span>
									<span class="text-[10px] text-slate-400 dark:text-zinc-500 font-semibold leading-none mt-0.5"><?php echo esc_html( $main_post['time_ago'] ); ?></span>
								</div>
							</div>
						</article>
					</div>
				<?php endif; ?>

				<!-- Column 2: Stack of 3 horizontal borderless side items -->
				<div class="lg:col-span-1 flex flex-col gap-6">
					<?php 
					for ( $i = 1; $i <= 3; $i++ ) : 
						if ( isset( $hero_posts[$i] ) ) :
							$p = $hero_posts[$i];
							?>
							<article class="flex gap-4 items-center">
								<!-- Left: Thumbnail image -->
								<?php if ( $p['thumbnail'] ) : ?>
									<a href="<?php echo esc_url( $p['permalink'] ); ?>" class="w-24 h-18 lg:w-28 lg:h-20 shrink-0 rounded-2xl overflow-hidden border border-slate-200/50 dark:border-zinc-800/50 shadow-inner">
										<img src="<?php echo esc_url( $p['thumbnail'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
									</a>
								<?php else : ?>
									<a href="<?php echo esc_url( $p['permalink'] ); ?>" class="w-24 h-18 lg:w-28 lg:h-20 shrink-0 rounded-2xl bg-gradient-to-br from-zinc-800 to-zinc-900 group-hover:scale-105 transition-transform duration-500 border border-slate-200/50 dark:border-zinc-800/50"></a>
								<?php endif; ?>
								
								<!-- Right: Text column -->
								<div class="flex flex-col justify-center flex-1 min-w-0">
									<h3 class="text-sm font-bold text-slate-900 dark:text-zinc-100 leading-snug line-clamp-3">
										<a class="no-underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( $p['permalink'] ); ?>">
											<?php echo esc_html( $p['title'] ); ?>
										</a>
									</h3>
									
									<!-- Author Row below title -->
									<div class="flex items-center gap-2 mt-2">
										<img class="w-6 h-6 rounded-full object-cover border border-slate-100 dark:border-zinc-800 shadow-sm" src="<?php echo esc_url( $p['avatar'] ); ?>" alt="<?php echo esc_attr( $p['author_name'] ); ?>">
										<div class="grid gap-0.5">
											<span class="text-[10px] font-bold text-slate-900 dark:text-zinc-100 leading-none"><?php echo esc_html( $p['author_name'] ); ?></span>
											<span class="text-[9px] text-slate-400 dark:text-zinc-500 font-semibold leading-none mt-0.5"><?php echo esc_html( $p['time_ago'] ); ?></span>
										</div>
									</div>
								</div>
							</article>
							<?php 
						endif;
					endfor; 
					?>
				</div>
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
