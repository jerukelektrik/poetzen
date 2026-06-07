<?php
/**
 * Generic archive fallback.
 *
 * @package SukuSastra
 */
get_header(); ?>
<section class="ss-section">
	<div class="ss-container">
		<?php sukusastra_breadcrumbs(); ?>
		<div class="grid gap-6">
		<header class="border-b border-slate-100 pb-4 dark:border-zinc-800/80">
			<p class="ss-eyebrow mb-1"><?php esc_html_e( 'Arsip', 'sukusastra' ); ?></p>
			<h1 class="ss-page-title"><?php the_archive_title(); ?></h1>
			<?php if ( get_the_archive_description() ) : ?>
				<div class="mt-3 max-w-2xl ss-body"><?php the_archive_description(); ?></div>
			<?php endif; ?>
		</header>
		<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
			<?php while ( have_posts() ) : the_post(); ?>
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
			<?php endwhile; ?>
		</div>
		<div class="mt-6 border-t border-slate-200/20 pt-6">
			<?php sukusastra_pagination(); ?>
		</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
