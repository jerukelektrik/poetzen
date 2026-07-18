<?php
/**
 * Static page template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[minmax(0,760px)_320px]">
				<div class="min-w-0">
				<h1 class="ss-page-title"><?php the_title(); ?></h1>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mt-6 rounded overflow-hidden shadow-sm"><?php the_post_thumbnail( 'sukusastra-hero', array( 'class' => 'w-full object-cover' ) ); ?></div>
				<?php endif; ?>
				<div class="ss-reading mt-8"><?php the_content(); ?></div>
			</div>
			<aside class="grid content-start gap-6">
				<?php get_template_part( 'template-parts/cta-submit' ); ?>
			</aside>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
