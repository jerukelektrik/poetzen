<?php
/**
 * Search results page.
 *
 * @package SukuSastra
 */
get_header(); ?>
<?php
// Initialize the custom query parser based on URL search query parameter states
$filtered = new WP_Query( sukusastra_filter_args_from_request() );
?>
<section class="ss-section">
	<div class="ss-container">
		<?php sukusastra_breadcrumbs(); ?>
		<div class="grid gap-6">
		<header class="border-b border-slate-100 pb-4 dark:border-zinc-800/80">
			<p class="ss-eyebrow mb-1"><?php esc_html_e( 'Pencarian', 'sukusastra' ); ?></p>
			<h1 class="ss-page-title">
				<?php printf( esc_html__( 'Hasil untuk: "%s"', 'sukusastra' ), esc_html( get_search_query() ) ); ?>
			</h1>
		</header>
		
		<?php get_template_part( 'template-parts/filters' ); ?>
		
		<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-4">
			<?php if ( $filtered->have_posts() ) : while ( $filtered->have_posts() ) : $filtered->the_post(); ?>
				<?php
				$type = get_post_type();
				if ( 'review_buku' === $type ) {
					get_template_part( 'template-parts/cards/review-card' );
				} elseif ( 'berita' === $type ) {
					get_template_part( 'template-parts/cards/news-card' );
				} elseif ( 'event' === $type ) {
					get_template_part( 'template-parts/cards/event-card' );
				} else {
					get_template_part( 'template-parts/cards/post-card' );
				}
				?>
			<?php endwhile; wp_reset_postdata(); else : ?>
				<div class="col-span-full rounded-md border border-slate-200 p-8 text-center dark:border-zinc-800 bg-white dark:bg-zinc-900">
					<p class="text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Tidak ada hasil yang cocok. Coba reset filter atau gunakan kata kunci lain.', 'sukusastra' ); ?></p>
					<a class="ss-button mt-4" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Reset Filter', 'sukusastra' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
		<div class="mt-6 border-t border-slate-200/20 pt-6">
			<?php
			echo paginate_links(
				array(
					'total'   => $filtered->max_num_pages,
					'current' => max( 1, get_query_var( 'paged' ) ),
				)
			);
			?>
		</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
