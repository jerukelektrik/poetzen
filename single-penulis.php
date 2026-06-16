<?php
/**
 * Single Author/Tokoh Biography Page.
 *
 * @package SukuSastra
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php
	$author_id = get_the_ID();
	$tempat_lahir = sukusastra_get_meta( $author_id, '_ss_penulis_tempat_lahir' );
	$tanggal_lahir = sukusastra_get_meta( $author_id, '_ss_penulis_tanggal_lahir' );
	$bio_summary = sukusastra_get_meta( $author_id, '_ss_penulis_bio_summary' );
	?>
	<section class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[280px_minmax(0,760px)]">
			<!-- Author Sidebar Profile info -->
			<aside class="grid content-start gap-6">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="rounded-2xl overflow-hidden shadow-md border border-slate-100 dark:border-zinc-800">
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full object-cover aspect-square sm:aspect-[3/4]' ) ); ?>
					</div>
				<?php else : ?>
					<div class="flex aspect-square sm:aspect-[3/4] w-full items-center justify-center rounded-2xl bg-red-700 font-serif text-6xl font-black text-white dark:bg-zinc-800 shadow-md">
						<?php echo esc_html( substr( get_the_title(), 0, 1 ) ); ?>
					</div>
				<?php endif; ?>

				<div class="ss-card grid gap-3 text-sm rounded-2xl p-6">
					<h2 class="ss-info-title m-0"><?php esc_html_e( 'Informasi Tokoh', 'sukusastra' ); ?></h2>
					<hr class="border-slate-100 dark:border-zinc-800 my-1">
					<?php if ( $tempat_lahir ) : ?>
						<p class="mb-2"><strong><?php esc_html_e( 'Tempat Lahir:', 'sukusastra' ); ?></strong><br><span class="text-slate-600 dark:text-zinc-400"><?php echo esc_html( $tempat_lahir ); ?></span></p>
					<?php endif; ?>
					<?php if ( $tanggal_lahir ) : ?>
						<p class="mb-0"><strong><?php esc_html_e( 'Tanggal Lahir:', 'sukusastra' ); ?></strong><br><span class="text-slate-600 dark:text-zinc-400"><?php echo esc_html( $tanggal_lahir ); ?></span></p>
					<?php endif; ?>
				</div>
			</aside>

			<!-- Biography and Works list -->
			<div class="grid gap-10">
				<!-- Full Bio details -->
				<div>
					<p class="ss-eyebrow mb-1"><?php esc_html_e( 'Profil Tokoh & Penulis', 'sukusastra' ); ?></p>
					<h1 class="ss-page-title"><?php the_title(); ?></h1>
					
					<?php if ( $bio_summary ) : ?>
						<div class="mt-4 border-l-4 border-red-700 pl-4 italic text-slate-700 dark:text-zinc-300 text-lg leading-7">
							<?php echo esc_html( $bio_summary ); ?>
						</div>
					<?php endif; ?>

					<div class="ss-reading mt-8">
						<?php the_content(); ?>
					</div>
				</div>

				<!-- Custom Query: Works by this author -->
				<?php
				$paged = max( 1, get_query_var( 'paged' ) );
				$selected_filter = isset( $_GET['filter_karya'] ) ? sanitize_key( wp_unslash( $_GET['filter_karya'] ) ) : 'semua';

				$query_args = array(
					'posts_per_page'      => 6,
					'paged'               => $paged,
					'ignore_sticky_posts' => true,
					'meta_query'          => array(
						'relation' => 'OR',
						array(
							'key'     => '_ss_original_author_id',
							'value'   => $author_id,
							'compare' => '=',
						),
						array(
							'key'     => '_ss_book_author',
							'value'   => $author_id,
							'compare' => '=',
						),
					),
				);

				if ( 'puisi' === $selected_filter ) {
					$query_args['post_type'] = 'post';
					$query_args['category_name'] = 'puisi';
				} elseif ( 'cerpen' === $selected_filter ) {
					$query_args['post_type'] = 'post';
					$query_args['category_name'] = 'cerpen';
				} elseif ( 'esai' === $selected_filter ) {
					$query_args['post_type'] = 'post';
					$query_args['category_name'] = 'esai';
				} elseif ( 'review_buku' === $selected_filter ) {
					$query_args['post_type'] = 'review_buku';
				} else {
					$query_args['post_type'] = array( 'post', 'review_buku' );
				}

				$works_query = new WP_Query( $query_args );
				?>
				<div class="border-t border-slate-200/40 pt-8 dark:border-zinc-800/80">
					<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
						<h2 class="ss-section-title m-0">
							<?php printf( esc_html__( 'Karya oleh %s', 'sukusastra' ), esc_html( get_the_title() ) ); ?>
						</h2>
						<!-- Rubric/CPT Filter Dropdown -->
						<form id="author-filter-form" method="get" action="<?php echo esc_url( get_permalink( $author_id ) ); ?>" class="flex items-center gap-2">
							<label for="filter_karya" class="sr-only"><?php esc_html_e( 'Filter Karya', 'sukusastra' ); ?></label>
							<select id="filter_karya" name="filter_karya" class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-sm font-bold dark:border-zinc-700 dark:bg-zinc-950 text-slate-700 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-red-700">
								<option value="semua" <?php selected( $selected_filter, 'semua' ); ?>><?php esc_html_e( 'Semua Karya', 'sukusastra' ); ?></option>
								<option value="puisi" <?php selected( $selected_filter, 'puisi' ); ?>><?php esc_html_e( 'Puisi', 'sukusastra' ); ?></option>
								<option value="cerpen" <?php selected( $selected_filter, 'cerpen' ); ?>><?php esc_html_e( 'Cerpen', 'sukusastra' ); ?></option>
								<option value="esai" <?php selected( $selected_filter, 'esai' ); ?>><?php esc_html_e( 'Esai', 'sukusastra' ); ?></option>
								<option value="review_buku" <?php selected( $selected_filter, 'review_buku' ); ?>><?php esc_html_e( 'Reviu Buku', 'sukusastra' ); ?></option>
							</select>
						</form>
					</div>

					<!-- AJAX Wrapper -->
					<div id="author-works-container" class="relative transition-opacity duration-200">
						<?php if ( $works_query->have_posts() ) : ?>
							<div class="grid gap-5 sm:grid-cols-2">
								<?php while ( $works_query->have_posts() ) : $works_query->the_post(); ?>
									<?php 
									if ( 'review_buku' === get_post_type() ) {
										get_template_part( 'template-parts/cards/review-card' );
									} else {
										get_template_part( 'template-parts/cards/post-card' );
									}
									?>
								<?php endwhile; ?>
							</div>

							<div class="mt-8 border-t border-slate-200/20 pt-6 pagination">
								<?php
								echo paginate_links(
									array(
										'total'     => $works_query->max_num_pages,
										'current'   => $paged,
										'format'    => '?paged=%#%',
										'add_args'  => array( 'filter_karya' => $selected_filter ),
										'prev_text' => '<span class="sr-only">' . esc_html__( 'Sebelumnya', 'sukusastra' ) . '</span><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>',
										'next_text' => '<span class="sr-only">' . esc_html__( 'Berikutnya', 'sukusastra' ) . '</span><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>',
									)
								);
								?>
							</div>
						<?php else : ?>
							<p class="text-slate-500 dark:text-zinc-400 italic">
								<?php esc_html_e( 'Belum ada karya yang sesuai dengan filter ini.', 'sukusastra' ); ?>
							</p>
						<?php endif; wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
			</div>
		</div>
	</section>
<?php endwhile; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const filterSelect = document.getElementById('filter_karya');
	const container = document.getElementById('author-works-container');
	const form = document.getElementById('author-filter-form');

	if (!filterSelect || !container) return;

	// Helper to fetch and swap contents
	async function updateWorks(url) {
		container.classList.add('opacity-40');
		container.style.pointerEvents = 'none';

		try {
			const response = await fetch(url);
			if (!response.ok) throw new Error('Network response was not ok');
			const htmlText = await response.text();

			const parser = new DOMParser();
			const doc = parser.parseFromString(htmlText, 'text/html');
			const newContent = doc.getElementById('author-works-container');

			if (newContent) {
				container.innerHTML = newContent.innerHTML;
				history.pushState(null, '', url);
			}
		} catch (error) {
			console.error('Fetch error:', error);
			window.location.href = url;
		} finally {
			container.classList.remove('opacity-40');
			container.style.pointerEvents = 'auto';
		}
	}

	// 1. Listen for filter select change
	filterSelect.addEventListener('change', function() {
		const filterVal = this.value;
		const baseUrl = form.getAttribute('action');
		const url = new URL(baseUrl);
		url.searchParams.set('filter_karya', filterVal);
		updateWorks(url.toString());
	});

	// 2. Listen for pagination clicks
	container.addEventListener('click', function(e) {
		const link = e.target.closest('a');
		if (link && link.href) {
			// Only intercept if the link is a pagination number link
			if (link.classList.contains('page-numbers') || link.closest('.page-numbers') || link.href.includes('paged=')) {
				e.preventDefault();
				updateWorks(link.href);
			}
		}
	});
});
</script>

<?php get_footer(); ?>
