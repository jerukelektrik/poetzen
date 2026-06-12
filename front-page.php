<?php
/**
 * Homepage template.
 *
 * @package SukuSastra
 */
get_header();

// Fetch latest 5 posts for the Hero Grid
$hero_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 5,
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
<section id="hero" class="ss-section border-t-0 bg-transparent pt-0 md:pt-4 pb-10 md:pb-14">
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
		$show_news_ticker = sukusastra_get_option( 'toggle_news_ticker', '1' );
		if ( '1' === $show_news_ticker && $news_update_events->have_posts() ) :
		?>
		<!-- 1. News Update Ticker Bar -->
		<div class="mb-4 flex items-center border border-slate-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900/80 backdrop-blur rounded-full p-1 pr-6 shadow-sm overflow-hidden">
			<div class="flex items-center bg-amber-400 text-amber-950 px-4 py-1.5 rounded-full text-xs font-black shrink-0 shadow-sm">
				<!-- Bell SVG Icon -->
				<svg class="w-3.5 h-3.5 mr-1.5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
				</svg>
				<span>News Update:</span>
			</div>
				<div class="min-w-0 h-6 flex-1 overflow-hidden relative ml-2 mr-4 text-xs font-semibold text-slate-700 dark:text-zinc-300">
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
			<!-- Desktop Hero Grid (Visible on desktop, hidden on mobile) -->
			<div class="hidden lg:grid gap-8 grid-cols-1 lg:grid-cols-3">
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
						<article class="relative w-full aspect-[16/10] rounded-3xl overflow-hidden group shadow-md border border-slate-200/10 bg-slate-900 flex flex-col justify-between p-6 sm:p-8">
							<!-- Background Image -->
							<div class="absolute inset-0 z-0">
								<?php if ( $main_post['thumbnail'] ) : ?>
									<img src="<?php echo esc_url( $main_post['thumbnail'] ); ?>" alt="<?php echo esc_attr( $main_post['title'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-70">
								<?php else : ?>
									<div class="w-full h-full bg-gradient-to-br from-zinc-800 to-zinc-900 group-hover:scale-105 transition-transform duration-500 opacity-70"></div>
								<?php endif; ?>
								<!-- Dark gradient overlay for typography readability -->
								<div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/80"></div>
							</div>
							
							<!-- Top Row: Category Pill & Date/Author -->
							<div class="relative z-10 flex items-center justify-between w-full">
								<span class="bg-red-700/90 text-white text-[11px] font-black uppercase tracking-wider px-3.5 py-1.5 rounded-full shadow-sm backdrop-blur-sm">
									<?php echo esc_html( $main_post['category'] ); ?>
								</span>
								<span class="text-[11px] font-semibold text-white/95 drop-shadow flex items-center gap-1.5">
									<span><?php echo esc_html( $main_post['date'] ); ?></span>
									<span class="opacity-60">•</span>
									<span class="font-bold"><?php echo esc_html( $main_post['author_name'] ); ?></span>
								</span>
							</div>
							
							<!-- Bottom Row: Title & Circular Arrow -->
							<div class="relative z-10 flex items-end justify-between w-full gap-4 mt-auto">
								<h2 class="text-2xl sm:text-4xl font-black text-white leading-tight tracking-tight drop-shadow-md line-clamp-3 max-w-[85%]">
									<a class="no-underline text-white hover:text-red-200 transition-colors" href="<?php echo esc_url( $main_post['permalink'] ); ?>">
										<?php echo esc_html( $main_post['title'] ); ?>
									</a>
								</h2>
								<a href="<?php echo esc_url( $main_post['permalink'] ); ?>" class="w-12 h-12 rounded-full border border-white/80 hover:border-white hover:bg-white/10 flex items-center justify-center text-white shrink-0 shadow transition-all hover:scale-105" aria-hidden="true">
									<svg class="w-5 h-5 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
									</svg>
								</a>
							</div>
						</article>
					</div>
				<?php endif; ?>

				<!-- Column 2: Stack of 4 horizontal side items -->
				<div class="lg:col-span-1 flex flex-col gap-4 justify-center">
					<?php 
					for ( $i = 1; $i <= 4; $i++ ) : 
						if ( isset( $hero_posts[$i] ) ) :
							$p = $hero_posts[$i];
							?>
							<article class="ss-card rounded-3xl p-4 shadow-sm hover:shadow-md transition-all flex items-center justify-between gap-4">
								<!-- Left: Thumbnail image -->
								<?php if ( $p['thumbnail'] ) : ?>
									<a href="<?php echo esc_url( $p['permalink'] ); ?>" class="w-20 h-20 shrink-0 rounded-2xl overflow-hidden border border-slate-200/50 dark:border-zinc-800/50 shadow-inner">
										<img src="<?php echo esc_url( $p['thumbnail'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
									</a>
								<?php else : ?>
									<a href="<?php echo esc_url( $p['permalink'] ); ?>" class="w-20 h-20 shrink-0 rounded-2xl bg-gradient-to-br from-zinc-800 to-zinc-900 group-hover:scale-105 transition-transform duration-500 border border-slate-200/50 dark:border-zinc-800/50"></a>
								<?php endif; ?>

								<!-- Middle: Text Column -->
								<div class="flex flex-col gap-2 flex-1 min-w-0">
									<!-- Title -->
									<h3 class="text-sm font-black text-slate-900 dark:text-zinc-100 leading-snug line-clamp-2">
										<a class="no-underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( $p['permalink'] ); ?>">
											<?php echo esc_html( $p['title'] ); ?>
										</a>
									</h3>
									<!-- Badge & Date/Author Row -->
									<div class="flex flex-wrap items-center gap-2">
										<span class="bg-red-50 dark:bg-red-950/30 text-red-700 dark:text-red-400 text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full border border-red-200/50 dark:border-red-900/30 shrink-0">
											<?php echo esc_html( $p['category'] ); ?>
										</span>
										<span class="text-[9px] font-semibold text-slate-400 dark:text-zinc-500 flex items-center gap-1 min-w-0 truncate">
											<span><?php echo esc_html( $p['date'] ); ?></span>
											<span class="opacity-50">•</span>
											<span class="font-bold text-slate-600 dark:text-zinc-400 truncate"><?php echo esc_html( $p['author_name'] ); ?></span>
										</span>
									</div>
								</div>
								
								<!-- Right Circular Arrow Button -->
								<a href="<?php echo esc_url( $p['permalink'] ); ?>" class="w-8 h-8 rounded-full border border-slate-200 dark:border-zinc-700 hover:border-slate-400 dark:hover:border-zinc-500 hover:bg-slate-50 dark:hover:bg-zinc-800 flex items-center justify-center text-slate-700 dark:text-zinc-300 shrink-0 shadow-sm transition-all hover:scale-105" aria-hidden="true">
									<svg class="w-3.5 h-3.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
									</svg>
								</a>
							</article>
							<?php 
						endif;
					endfor; 
					?>
				</div>
			</div>

			<!-- Mobile Hero Slider (Visible on mobile, hidden on desktop) -->
			<div class="block lg:hidden w-full min-w-0 overflow-hidden relative">
				<div id="mobile-hero-carousel" class="flex max-w-full overflow-x-auto snap-x snap-mandatory no-scrollbar gap-3 -mx-4 px-4 pb-4">
					<?php foreach ( $hero_posts as $index => $post ) : ?>
						<div class="w-[78vw] sm:w-[64vw] shrink-0 snap-start">
							<article class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden group shadow border border-slate-200/10 bg-slate-900 flex flex-col justify-between p-5">
								<!-- Background Image -->
								<div class="absolute inset-0 z-0">
									<?php if ( $post['thumbnail'] ) : ?>
										<img src="<?php echo esc_url( $post['thumbnail'] ); ?>" alt="<?php echo esc_attr( $post['title'] ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-70">
									<?php else : ?>
										<div class="w-full h-full bg-gradient-to-br from-zinc-800 to-zinc-900 group-hover:scale-105 transition-transform duration-500 opacity-70"></div>
									<?php endif; ?>
									<!-- Dark gradient overlay for typography readability -->
									<div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/85"></div>
								</div>
								
								<!-- Top Row: Category Pill & Date/Author -->
								<div class="relative z-10 flex items-center justify-between w-full">
									<span class="bg-red-700/95 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-full shadow-sm backdrop-blur-sm">
										<?php echo esc_html( $post['category'] ); ?>
									</span>
									<span class="text-[10px] font-semibold text-white/90 drop-shadow flex items-center gap-1.5">
										<span><?php echo esc_html( $post['date'] ); ?></span>
										<span class="opacity-60">•</span>
										<span class="font-bold text-ellipsis overflow-hidden whitespace-nowrap max-w-[80px]"><?php echo esc_html( $post['author_name'] ); ?></span>
									</span>
								</div>
								
								<!-- Bottom Row: Title, Excerpt & Baca Selengkapnya -->
								<div class="relative z-10 flex flex-col gap-1.5 mt-auto w-full">
									<!-- Title -->
									<h2 class="text-base font-black text-white leading-tight tracking-tight drop-shadow-md line-clamp-2">
										<a class="no-underline text-white hover:text-red-200 transition-colors" href="<?php echo esc_url( $post['permalink'] ); ?>">
											<?php echo esc_html( $post['title'] ); ?>
										</a>
									</h2>
									<!-- Excerpt / Description -->
									<?php if ( ! empty( $post['excerpt'] ) ) : ?>
										<p class="text-[11px] leading-relaxed text-zinc-300 drop-shadow line-clamp-2">
											<?php echo esc_html( wp_trim_words( $post['excerpt'], 15, '...' ) ); ?>
										</p>
									<?php endif; ?>
									<!-- Baca Selengkapnya Link -->
									<div class="flex items-center justify-between w-full mt-0.5">
										<a href="<?php echo esc_url( $post['permalink'] ); ?>" class="text-[10px] font-black uppercase tracking-wider text-red-400 hover:text-red-300 transition-colors no-underline flex items-center gap-1 drop-shadow">
											<span>Baca Selengkapnya</span>
											<svg class="w-3 h-3 stroke-current fill-none stroke-[2.5]" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
											</svg>
										</a>
									</div>
								</div>
							</article>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Carousel Dots Indicator -->
				<div class="flex justify-center gap-1.5 mt-2">
					<?php foreach ( $hero_posts as $index => $post ) : ?>
						<span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-zinc-700 transition-all duration-300 mobile-hero-dot <?php echo 0 === $index ? 'bg-red-700 dark:bg-red-500 w-3' : ''; ?>"></span>
					<?php endforeach; ?>
				</div>
			</div>

			<script>
				document.addEventListener('DOMContentLoaded', function() {
					const carousel = document.getElementById('mobile-hero-carousel');
					const dots = document.querySelectorAll('.mobile-hero-dot');
					if (carousel && dots.length > 0) {
						carousel.addEventListener('scroll', function() {
							const scrollLeft = carousel.scrollLeft;
							const slideWidth = carousel.firstElementChild.offsetWidth + 12; // element width + gap
							const activeIndex = Math.round(scrollLeft / slideWidth);
							
							dots.forEach((dot, index) => {
								if (index === activeIndex) {
									dot.classList.add('bg-red-700', 'dark:bg-red-500', 'w-3');
									dot.classList.remove('bg-slate-300', 'dark:bg-zinc-700');
								} else {
									dot.classList.remove('bg-red-700', 'dark:bg-red-500', 'w-3');
									dot.classList.add('bg-slate-300', 'dark:bg-zinc-700');
								}
							});
						});
					}
				});
			</script>
		<?php else : ?>
			<p class="text-center py-12 text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Belum ada terbitan terbaru.', 'sukusastra' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<?php 
$show_penulis_stories = sukusastra_get_option( 'toggle_penulis_stories', '1' );
if ( '1' === $show_penulis_stories ) : 
?>
<!-- Penulis Stories Section -->
<section class="bg-transparent py-8 relative">
	<div class="ss-container">
		<div class="flex items-center justify-between mb-6">
			<h2 class="ss-section-title ss-author-section-title flex items-center gap-2">
				<span>Penulis</span>
			</h2>
			<a class="text-xs font-bold uppercase tracking-wider text-red-700 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 no-underline flex items-center gap-1.5" href="<?php echo esc_url( get_post_type_archive_link( 'penulis' ) ); ?>">
				<span>Lihat Semua</span>
				<svg class="w-3.5 h-3.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
				</svg>
			</a>
		</div>

		<!-- Scroll wrapper with slide buttons -->
		<div class="relative group/scroll">
			<!-- Left Slide Button -->
			<button id="slide-left-btn" class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-white dark:bg-[#262B4E] shadow-md border border-slate-200 dark:border-zinc-800/80 flex items-center justify-center text-slate-600 dark:text-zinc-300 opacity-0 group-hover/scroll:opacity-100 transition-opacity duration-300 hover:text-red-700 dark:hover:text-red-400" aria-label="Geser Kiri">
				<svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
				</svg>
			</button>
			
			<!-- Horizontally scrollable stories container -->
			<div id="stories-scroll-container" class="flex items-start gap-6 overflow-x-auto pb-2 no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0 scroll-smooth snap-x">
				<?php
				$penulis_query = new WP_Query(
					array(
						'post_type'      => 'penulis',
						'posts_per_page' => 15,
						'orderby'        => 'title',
						'order'          => 'ASC',
					)
				);

				if ( $penulis_query->have_posts() ) :
					while ( $penulis_query->have_posts() ) :
						$penulis_query->the_post();
						$author_name = get_the_title();
						
						// Smart handle generator: lowercase, dots, strip single letters
						$clean_name = strtolower( preg_replace( '/[^a-zA-Z0-9\s]/', '', $author_name ) );
						$parts = array_filter( explode( ' ', $clean_name ), function( $val ) {
							return strlen( $val ) > 1;
						} );
						if ( empty( $parts ) ) {
							$parts = array_filter( explode( ' ', $clean_name ) );
						}
						if ( count( $parts ) >= 2 ) {
							$handle = implode( '.', array_slice( $parts, 0, 2 ) );
						} else {
							$handle = implode( '', $parts );
						}
						$handle = '@' . $handle;
						
						$avatar_url = '';
						if ( has_post_thumbnail() ) {
							$avatar_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
						}
						if ( ! $avatar_url ) {
							$avatar_url = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=120&h=120&fit=crop';
						}
						
						$bio_summary = get_post_meta( get_the_ID(), '_ss_penulis_bio_summary', true );
						$birth_place = get_post_meta( get_the_ID(), '_ss_penulis_tempat_lahir', true );
						$birth_date = get_post_meta( get_the_ID(), '_ss_penulis_tanggal_lahir', true );
						$birth_info = '';
						if ( $birth_place || $birth_date ) {
							$birth_info = trim( implode( ', ', array_filter( array( $birth_place, $birth_date ) ) ) );
						}
						
						// Check if author has posts in the last 30 days
						$recent_posts = get_posts(
							array(
								'post_type'      => array( 'post', 'review_buku' ),
								'posts_per_page' => 1,
								'meta_query'     => array(
									array(
										'key'     => '_ss_original_author_id',
										'value'   => get_the_ID(),
										'compare' => '=',
									),
								),
								'date_query'     => array(
									array(
										'after' => '30 days ago',
									),
								),
							)
						);
						$is_active = ! empty( $recent_posts );
						
						$ring_class = $is_active 
							? 'bg-gradient-to-tr from-red-700 via-amber-500 to-yellow-500' 
							: 'bg-slate-200 dark:bg-zinc-800/80';
						?>
						<div class="flex flex-col items-center shrink-0 w-20 sm:w-24 snap-start group">
							<a href="<?php the_permalink(); ?>" 
							   class="author-story-trigger relative p-[3px] rounded-full <?php echo esc_attr( $ring_class ); ?> transition-transform duration-300 group-hover:scale-105 shadow-sm"
							   data-name="<?php echo esc_attr( $author_name ); ?>"
							   data-handle="<?php echo esc_attr( $handle ); ?>"
							   data-bio="<?php echo esc_attr( $bio_summary ); ?>"
							   data-birth="<?php echo esc_attr( $birth_info ); ?>"
							   data-avatar="<?php echo esc_url( $avatar_url ); ?>"
							   data-link="<?php the_permalink(); ?>">
								<div class="bg-slate-50 dark:bg-[#343B6A] p-[2px] rounded-full">
									<img src="<?php echo esc_url( $avatar_url ); ?>" alt="<?php echo esc_attr( $author_name ); ?>" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover">
								</div>
							</a>
							<a href="<?php the_permalink(); ?>" class="mt-2 text-[11px] font-semibold text-slate-700 dark:text-zinc-300 hover:text-red-700 dark:hover:text-red-300 no-underline text-center block w-full whitespace-normal break-words">
								<?php echo esc_html( $author_name ); ?>
							</a>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<p class="text-xs text-slate-500 dark:text-zinc-400 py-4"><?php esc_html_e( 'Belum ada penulis terdaftar.', 'sukusastra' ); ?></p>
				<?php
				endif;
				?>
			</div>
			
			<!-- Right Slide Button -->
			<button id="slide-right-btn" class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-white dark:bg-[#262B4E] shadow-md border border-slate-200 dark:border-zinc-800/80 flex items-center justify-center text-slate-600 dark:text-zinc-300 opacity-0 group-hover/scroll:opacity-100 transition-opacity duration-300 hover:text-red-700 dark:hover:text-red-400" aria-label="Geser Kanan">
				<svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
				</svg>
			</button>
		</div>
	</div>

	<!-- Author Biography Modal -->
	<div id="author-bio-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300" aria-hidden="true" role="dialog">
		<!-- Backdrop -->
		<div id="author-bio-modal-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
		
		<!-- Modal Card -->
		<div class="relative w-full max-w-md bg-white dark:bg-[#262B4E] rounded-3xl overflow-hidden shadow-2xl border border-slate-200/50 dark:border-zinc-800/80 transform scale-95 transition-transform duration-300 flex flex-col items-center p-6 text-center z-10">
			<!-- Close Button -->
			<button id="author-bio-modal-close" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 flex items-center justify-center text-slate-500 dark:text-zinc-400 transition-colors" aria-label="Tutup">
				<svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
				</svg>
			</button>
			
			<!-- Avatar Ring -->
			<div class="relative p-[4px] rounded-full bg-gradient-to-tr from-red-700 via-amber-500 to-yellow-500 shadow-md mb-4 mt-2">
				<div class="bg-white dark:bg-[#262B4E] p-[3px] rounded-full">
					<img id="modal-author-avatar" src="" alt="" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover">
				</div>
			</div>
			
			<!-- Author Name -->
			<h3 id="modal-author-name" class="text-2xl font-black text-slate-900 dark:text-zinc-50 leading-tight"></h3>
			
			<!-- Username Handle -->
			<span id="modal-author-handle" class="text-xs font-bold text-red-700 dark:text-red-400 mt-1 uppercase tracking-wider"></span>
			
			<!-- Birth Info -->
			<div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-zinc-500 mt-2.5 font-semibold">
				<svg class="w-3.5 h-3.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
					<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
				</svg>
				<span id="modal-author-birth"></span>
			</div>
			
			<!-- Bio Summary -->
			<p id="modal-author-bio" class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-zinc-300 font-serif italic max-w-sm"></p>
			
			<!-- Action Link -->
			<a id="modal-author-link" href="" class="mt-6 w-full ss-button text-center no-underline flex items-center justify-center gap-2">
				<span>Kunjungi Profil & Semua Karya</span>
				<svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
				</svg>
			</a>
		</div>
	</div>

	<!-- Slider & Modal Javascript -->
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// 1. Slider controls
		const container = document.getElementById('stories-scroll-container');
		const leftBtn = document.getElementById('slide-left-btn');
		const rightBtn = document.getElementById('slide-right-btn');
		
		if (container && leftBtn && rightBtn) {
			leftBtn.addEventListener('click', () => {
				container.scrollBy({ left: -240, behavior: 'smooth' });
			});
			rightBtn.addEventListener('click', () => {
				container.scrollBy({ left: 240, behavior: 'smooth' });
			});
		}

		// 2. Modal Popup
		const modal = document.getElementById('author-bio-modal');
		const card = modal ? modal.querySelector('.transform') : null;
		const backdrop = document.getElementById('author-bio-modal-backdrop');
		const closeBtn = document.getElementById('author-bio-modal-close');
		
		const modalAvatar = document.getElementById('modal-author-avatar');
		const modalName = document.getElementById('modal-author-name');
		const modalHandle = document.getElementById('modal-author-handle');
		const modalBirth = document.getElementById('modal-author-birth');
		const modalBio = document.getElementById('modal-author-bio');
		const modalLink = document.getElementById('modal-author-link');
		
		if (modal && card && backdrop && closeBtn) {
			document.querySelectorAll('.author-story-trigger').forEach(trigger => {
				trigger.addEventListener('click', function(e) {
					e.preventDefault();
					
					const name = this.getAttribute('data-name');
					const handle = this.getAttribute('data-handle');
					const bio = this.getAttribute('data-bio');
					const birth = this.getAttribute('data-birth');
					const avatar = this.getAttribute('data-avatar');
					const link = this.getAttribute('data-link');
					
					if (modalAvatar) {
						modalAvatar.src = avatar || '';
						modalAvatar.alt = name || '';
					}
					if (modalName) modalName.textContent = name || '';
					if (modalHandle) modalHandle.textContent = handle || '';
					if (modalBirth) modalBirth.textContent = birth || 'Data kelahiran tidak dicatat';
					if (modalBio) modalBio.textContent = bio || 'Sastrawan terkemuka yang berkontribusi aktif di media Suku Sastra.';
					if (modalLink) modalLink.href = link || '#';
					
					// Open modal
					modal.classList.remove('opacity-0', 'pointer-events-none');
					modal.setAttribute('aria-hidden', 'false');
					card.classList.remove('scale-95');
					card.classList.add('scale-100');
					document.body.style.overflow = 'hidden';
				});
			});
			
			function closeModal() {
				modal.classList.add('opacity-0', 'pointer-events-none');
				modal.setAttribute('aria-hidden', 'true');
				card.classList.remove('scale-100');
				card.classList.add('scale-95');
				document.body.style.overflow = '';
			}
			
			closeBtn.addEventListener('click', closeModal);
			backdrop.addEventListener('click', closeModal);
			
			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' && !modal.classList.contains('opacity-0')) {
					closeModal();
				}
			});
		}
	});
	</script>
</section>
<?php endif; ?>




<!-- Category Feeds (Puisi, Cerpen, Esai) -->
<?php
if ( ! function_exists( 'sukusastra_home_feed_actions' ) ) {
	function sukusastra_home_feed_actions( string $archive_url ): void {
		$actions = array(
			array(
				'label' => __( 'Terbaru', 'sukusastra' ),
				'sort'  => 'terbaru',
				'icon'  => '<svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>',
			),
			array(
				'label' => __( 'Terpopuler', 'sukusastra' ),
				'sort'  => 'terpopuler',
				'icon'  => '<svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m3 17 6-6 4 4 7-8" /><path stroke-linecap="round" stroke-linejoin="round" d="M14 7h6v6" /></svg>',
			),
		);
		?>
		<nav class="ss-section-actions" aria-label="<?php esc_attr_e( 'Navigasi feed', 'sukusastra' ); ?>">
			<?php foreach ( $actions as $action ) : ?>
				<button class="ss-section-action" type="button" data-feed-action="<?php echo esc_attr( $action['sort'] ); ?>" aria-pressed="<?php echo 'terbaru' === $action['sort'] ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( $action['label'] ); ?>" title="<?php echo esc_attr( $action['label'] ); ?>">
					<?php echo $action['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="sr-only"><?php echo esc_html( $action['label'] ); ?></span>
				</button>
			<?php endforeach; ?>
			<a class="ss-section-action" href="<?php echo esc_url( $archive_url ); ?>" aria-label="<?php esc_attr_e( 'Lihat Semua', 'sukusastra' ); ?>" title="<?php esc_attr_e( 'Lihat Semua', 'sukusastra' ); ?>">
				<svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h10M4 12h10M4 18h10" /><path stroke-linecap="round" stroke-linejoin="round" d="m17 8 4 4-4 4" /></svg>
				<span class="sr-only"><?php esc_html_e( 'Lihat Semua', 'sukusastra' ); ?></span>
			</a>
		</nav>
		<a class="ss-eyebrow hidden md:inline-flex md:items-center md:gap-1" href="<?php echo esc_url( $archive_url ); ?>">
			<?php esc_html_e( 'Lihat Semua', 'sukusastra' ); ?> <span aria-hidden="true">&rarr;</span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'sukusastra_home_feed_panel' ) ) {
	function sukusastra_home_feed_panel( WP_Query $query, string $sort_by, bool $is_hidden = false ): void {
		?>
			<div class="ss-feed-panel <?php echo $is_hidden ? 'hidden' : ''; ?>" data-feed-panel="<?php echo esc_attr( $sort_by ); ?>">
				<div class="ss-category-swipe-row flex gap-3 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-2 md:grid md:grid-cols-3" data-drag-scroll>
				<?php $feed_index = 0; ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php $feed_index++; ?>
					<div class="ss-category-swipe-card w-[40vw] shrink-0 snap-start md:w-auto <?php echo $feed_index > 3 ? 'md:hidden' : ''; ?>">
						<?php get_template_part( 'template-parts/cards/post-card' ); ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
		<?php
	}
}
?>
<?php $query_puisi = sukusastra_home_posts( 'puisi', 10 ); ?>
<?php $popular_puisi = sukusastra_home_posts( 'puisi', 10, 'terpopuler' ); ?>
<?php if ( $query_puisi->have_posts() ) : ?>
	<section id="puisi" class="ss-section bg-transparent" data-feed-section>
		<div class="ss-container grid gap-6">
			<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
				<h2 class="ss-section-title ss-feed-section-title"><?php esc_html_e( 'Puisi Terbaru', 'sukusastra' ); ?></h2>
				<?php sukusastra_home_feed_actions( get_category_link( get_category_by_slug( 'puisi' ) ) ); ?>
			</div>
			<?php sukusastra_home_feed_panel( $query_puisi, 'terbaru' ); ?>
			<?php sukusastra_home_feed_panel( $popular_puisi, 'terpopuler', true ); ?>
		</div>
	</section>
<?php endif; ?>

<?php $query_cerpen = sukusastra_home_posts( 'cerpen', 10 ); ?>
<?php $popular_cerpen = sukusastra_home_posts( 'cerpen', 10, 'terpopuler' ); ?>
<?php if ( $query_cerpen->have_posts() ) : ?>
	<section id="cerpen" class="ss-section bg-transparent" data-feed-section>
		<div class="ss-container grid gap-6">
			<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
				<h2 class="ss-section-title ss-feed-section-title"><?php esc_html_e( 'Cerpen Terbaru', 'sukusastra' ); ?></h2>
				<?php sukusastra_home_feed_actions( get_category_link( get_category_by_slug( 'cerpen' ) ) ); ?>
			</div>
			<?php sukusastra_home_feed_panel( $query_cerpen, 'terbaru' ); ?>
			<?php sukusastra_home_feed_panel( $popular_cerpen, 'terpopuler', true ); ?>
		</div>
	</section>
<?php endif; ?>

<!-- Katalog Terbitan Section -->
<?php $terbitan_query = sukusastra_latest_cpt( 'terbitan', 5 ); ?>
<?php if ( $terbitan_query->have_posts() ) : ?>
	<section id="katalog-terbitan" class="ss-section bg-transparent">
		<div class="ss-container grid gap-6">
			<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
				<h2 class="ss-section-title ss-feed-section-title"><?php esc_html_e( 'Katalog Terbitan', 'sukusastra' ); ?></h2>
				<a class="ss-eyebrow" href="<?php echo esc_url( get_post_type_archive_link( 'terbitan' ) ); ?>">
					<?php esc_html_e( 'Lihat Semua', 'sukusastra' ); ?> &rarr;
				</a>
			</div>
			
			<!-- Monetization Banner -->
			<?php 
			$banner_toggle = sukusastra_get_option( 'monetization_banner_toggle', '0' );
			$banner_image = sukusastra_get_option( 'monetization_banner_image' );
			$banner_link = sukusastra_get_option( 'monetization_banner_link' );
			if ( '1' === $banner_toggle && $banner_image ) : 
				?>
				<div class="ss-terbitan-banner w-full md:w-full">
					<?php if ( $banner_link ) : ?>
						<a href="<?php echo esc_url( $banner_link ); ?>" target="_blank" rel="noopener" class="block w-full overflow-hidden rounded-2xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm transition hover:opacity-95 duration-200">
					<?php else : ?>
						<div class="w-full overflow-hidden rounded-2xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm">
					<?php endif; ?>
						<img src="<?php echo esc_url( $banner_image ); ?>" alt="<?php esc_attr_e( 'Monetization Banner', 'sukusastra' ); ?>" class="ss-terbitan-banner-img w-full h-auto object-cover block">
					<?php if ( $banner_link ) : ?>
						</a>
					<?php else : ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<!-- Mobile carousel, desktop grid for Kumparan-style portrait cards -->
			<div class="ss-terbitan-carousel -mx-4 flex gap-3 overflow-x-auto snap-x snap-mandatory no-scrollbar px-4 pb-2 sm:-mx-6 sm:px-6 md:mx-0 md:grid md:gap-5 md:grid-cols-4 md:px-0 lg:grid-cols-5 md:overflow-visible md:snap-none">
				<?php while ( $terbitan_query->have_posts() ) : $terbitan_query->the_post(); ?>
					<div class="ss-terbitan-carousel-item w-[calc((100vw-2rem-1.5rem)/2.5)] max-w-[9.5rem] shrink-0 snap-start md:w-auto md:max-w-none md:shrink">
						<?php get_template_part( 'template-parts/cards/terbitan-home-card' ); ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php $query_esai = sukusastra_home_posts( 'esai', 10 ); ?>
<?php $popular_esai = sukusastra_home_posts( 'esai', 10, 'terpopuler' ); ?>
<?php if ( $query_esai->have_posts() ) : ?>
	<section id="esai" class="ss-section bg-transparent" data-feed-section>
		<div class="ss-container grid gap-6">
			<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
				<h2 class="ss-section-title ss-feed-section-title"><?php esc_html_e( 'Esai Terbaru', 'sukusastra' ); ?></h2>
				<?php sukusastra_home_feed_actions( get_category_link( get_category_by_slug( 'esai' ) ) ); ?>
			</div>
			<?php sukusastra_home_feed_panel( $query_esai, 'terbaru' ); ?>
			<?php sukusastra_home_feed_panel( $popular_esai, 'terpopuler', true ); ?>
		</div>
	</section>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('[data-feed-section]').forEach(function(section) {
		const actions = section.querySelectorAll('[data-feed-action]');
		const panels = section.querySelectorAll('[data-feed-panel]');

		actions.forEach(function(action) {
			action.addEventListener('click', function() {
				const target = action.getAttribute('data-feed-action');

				actions.forEach(function(item) {
					item.setAttribute('aria-pressed', item === action ? 'true' : 'false');
				});

				panels.forEach(function(panel) {
					const isActive = panel.getAttribute('data-feed-panel') === target;
					panel.classList.toggle('hidden', !isActive);

					const row = panel.querySelector('.ss-category-swipe-row');
					if (row && isActive) {
						row.scrollLeft = 0;
					}
				});
			});
		});
	});

	document.querySelectorAll('[data-drag-scroll]').forEach(function(row) {
		let isDown = false;
		let startX = 0;
		let scrollLeft = 0;

		row.addEventListener('pointerdown', function(event) {
			if (window.matchMedia('(min-width: 48rem)').matches) {
				return;
			}

			isDown = true;
			startX = event.clientX;
			scrollLeft = row.scrollLeft;
			row.classList.add('is-dragging');
			row.setPointerCapture(event.pointerId);
		});

		row.addEventListener('pointermove', function(event) {
			if (!isDown) {
				return;
			}

			event.preventDefault();
			row.scrollLeft = scrollLeft - (event.clientX - startX);
		});

		function stopDrag(event) {
			if (!isDown) {
				return;
			}

			isDown = false;
			row.classList.remove('is-dragging');
			if (row.hasPointerCapture(event.pointerId)) {
				row.releasePointerCapture(event.pointerId);
			}
		}

		row.addEventListener('pointerup', stopDrag);
		row.addEventListener('pointercancel', stopDrag);
		row.addEventListener('lostpointercapture', function() {
			isDown = false;
			row.classList.remove('is-dragging');
		});
	});
});
</script>

<!-- Book Reviews Section -->
<?php $reviews = sukusastra_latest_cpt( 'review_buku', 4 ); ?>
<?php if ( $reviews->have_posts() ) : ?>
	<section id="review-buku" class="ss-section">
		<div class="ss-container grid gap-6">
			<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
				<h2 class="ss-section-title ss-feed-section-title"><?php esc_html_e( 'Review Buku', 'sukusastra' ); ?></h2>
				<a class="ss-eyebrow" href="<?php echo esc_url( get_post_type_archive_link( 'review_buku' ) ); ?>">
					<?php esc_html_e( 'Semua Review', 'sukusastra' ); ?> &rarr;
				</a>
			</div>
			<div class="ss-review-carousel flex gap-4 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-2 md:grid md:gap-5 md:grid-cols-2 lg:grid-cols-3 md:overflow-visible md:snap-none">
				<?php while ( $reviews->have_posts() ) : $reviews->the_post(); ?>
					<div class="ss-review-carousel-item w-[68vw] shrink-0 snap-start sm:w-[46vw] md:w-auto md:shrink">
						<?php get_template_part( 'template-parts/cards/review-card', null, array( 'layout' => 'vertical' ) ); ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- News Section -->
<section id="berita" class="ss-section">
	<div class="ss-container grid gap-6">
		<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
			<h2 class="ss-section-title ss-feed-section-title"><?php esc_html_e( 'Berita Suku Sastra', 'sukusastra' ); ?></h2>
			<a class="ss-eyebrow" href="<?php echo esc_url( get_post_type_archive_link( 'berita' ) ); ?>">
				<?php esc_html_e( 'Semua Berita', 'sukusastra' ); ?> &rarr;
			</a>
		</div>
		<?php $news = sukusastra_latest_cpt( 'berita', 3 ); ?>
		<?php if ( $news->have_posts() ) : ?>
			<div class="ss-news-carousel flex gap-4 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-2 md:grid md:gap-6 md:grid-cols-3 md:overflow-visible md:snap-none" data-drag-scroll>
				<?php while ( $news->have_posts() ) : $news->the_post(); ?>
					<div class="ss-news-carousel-item w-[82vw] shrink-0 snap-start md:w-auto md:shrink">
						<?php get_template_part( 'template-parts/cards/news-card' ); ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p class="text-sm text-slate-500 dark:text-zinc-400 py-6 text-center"><?php esc_html_e( 'Belum ada berita terbaru.', 'sukusastra' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<!-- Events Section -->
<section id="event" class="ss-section bg-slate-100 dark:bg-black/40 border-b border-slate-200 dark:border-[#4d568c]/25">
	<div class="ss-container grid gap-6">
		<div class="flex items-center justify-between border-b border-slate-100 pb-2 dark:border-zinc-800/80">
			<h2 class="ss-section-title ss-feed-section-title"><?php esc_html_e( 'Agenda & Event Sastra', 'sukusastra' ); ?></h2>
			<a class="ss-eyebrow" href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>">
				<?php esc_html_e( 'Semua Event', 'sukusastra' ); ?> &rarr;
			</a>
		</div>
		<?php $events = sukusastra_upcoming_events( 4 ); ?>
		<?php if ( $events->have_posts() ) : ?>
			<div class="ss-event-carousel flex gap-4 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-2 md:grid md:gap-6 md:grid-cols-2 md:overflow-visible md:snap-none lg:grid-cols-4" data-drag-scroll>
				<?php while ( $events->have_posts() ) : $events->the_post(); ?>
					<div class="ss-event-carousel-item w-[82vw] shrink-0 snap-start md:w-auto md:shrink">
						<?php get_template_part( 'template-parts/cards/event-card' ); ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		<?php else : ?>
			<p class="text-sm text-slate-500 dark:text-zinc-400 py-6 text-center"><?php esc_html_e( 'Belum ada agenda terdekat.', 'sukusastra' ); ?></p>
		<?php endif; ?>
	</div>
</section>


<?php get_footer(); ?>
