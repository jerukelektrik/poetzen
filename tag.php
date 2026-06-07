<?php
/**
 * Tag hub archive.
 *
 * @package SukuSastra
 */
get_header(); ?>
<section class="ss-section">
	<div class="ss-container">
		<?php sukusastra_breadcrumbs(); ?>
		<div class="grid gap-6">
		<header class="border-b border-slate-100 pb-4 dark:border-zinc-800/80 max-w-3xl">
			<p class="ss-eyebrow mb-1"><?php esc_html_e( 'Topik', 'sukusastra' ); ?></p>
			<h1 class="ss-page-title"><?php single_tag_title(); ?></h1>
			<?php if ( tag_description() ) : ?>
				<div class="mt-3 ss-body"><?php echo tag_description(); ?></div>
			<?php endif; ?>
		</header>
		<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/cards/post-card' ); ?>
			<?php endwhile; else : ?>
				<p class="col-span-full text-center py-12 text-slate-500 dark:text-zinc-400">
					<?php esc_html_e( 'Topik ini belum memiliki artikel.', 'sukusastra' ); ?>
				</p>
			<?php endif; ?>
		</div>
		<div class="mt-6 border-t border-slate-200/20 pt-6">
			<?php the_posts_pagination(); ?>
		</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
