<?php
/**
 * Generic fallback template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<section class="ss-section">
	<div class="ss-container grid gap-6">
		<h1 class="ss-page-title"><?php bloginfo( 'name' ); ?></h1>
		<div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/cards/post-card' ); ?>
			<?php endwhile; ?>
		</div>
		<div class="mt-6 border-t border-slate-250/20 pt-6">
			<?php the_posts_pagination(); ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>
