<?php
/**
 * Related posts layout.
 *
 * @package SukuSastra
 */
$related = sukusastra_related_posts( get_the_ID(), 4 );
if ( ! $related->have_posts() ) {
	return;
}
?>
<section class="grid gap-4">
	<h2 class="text-lg font-black"><?php esc_html_e( 'Bacaan Terkait', 'sukusastra' ); ?></h2>
	<div class="grid gap-3">
		<?php while ( $related->have_posts() ) : $related->the_post(); ?>
			<a class="block rounded-md border border-slate-200 p-3 no-underline hover:border-slate-300 dark:border-zinc-800 dark:hover:border-zinc-700" href="<?php the_permalink(); ?>">
				<span class="font-bold text-sm text-slate-900 dark:text-zinc-50"><?php the_title(); ?></span>
			</a>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>
</section>
