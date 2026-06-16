<?php
/**
 * Standard post card.
 *
 * @package SukuSastra
 */
?>
<article <?php post_class( 'group flex flex-col min-w-0' ); ?>>
	<!-- Image wrapper with scale hover effect -->
	<a class="block no-underline overflow-hidden rounded-2xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'sukusastra-card', array( 'class' => 'aspect-[3/2] w-full object-cover group-hover:scale-105 transition-transform duration-500' ) ); ?>
		<?php else : ?>
			<div class="ss-post-card-placeholder flex aspect-[3/2] w-full items-center justify-center bg-slate-50 dark:bg-zinc-900/40 p-6 group-hover:scale-105 transition-transform duration-500">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php the_title_attribute(); ?>" class="max-h-12 max-w-full opacity-50 dark:opacity-20 object-contain" />
			</div>
		<?php endif; ?>
	</a>
	
		<!-- Metadata: Rubric / Author / Date -->
		<div class="ss-post-card-meta mt-4 flex flex-col items-start gap-1 text-xs font-semibold tracking-wide uppercase md:flex-row md:flex-wrap md:items-center md:gap-1.5">
			<span class="ss-post-card-category block text-red-700 dark:text-red-400 font-black md:inline">
				<?php
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
				echo esc_html( $categories[0]->name );
			} else {
				echo esc_html__( 'Karya', 'sukusastra' );
				}
				?>
			</span>
			<span class="hidden text-slate-350 dark:text-zinc-650 font-normal md:inline">·</span>
			<span class="ss-post-card-author block text-slate-800 dark:text-zinc-200 font-bold md:inline">
				<?php
				$orig_author = sukusastra_get_original_author( get_the_ID() );
				if ( $orig_author ) {
				echo esc_html( $orig_author->post_title );
			} else {
				echo esc_html( get_the_author() );
				}
				?>
			</span>
			<span class="hidden text-slate-355 dark:text-zinc-655 font-normal md:inline">·</span>
			<span class="ss-post-card-date block text-slate-500 dark:text-zinc-400 font-semibold normal-case md:inline"><?php echo esc_html( get_the_date() ); ?></span>
		</div>

	<!-- Title & Diagonal Arrow Icon -->
	<div class="ss-post-card-heading flex items-start justify-between gap-2 mt-2">
		<h3 class="ss-post-card-title text-lg font-black leading-snug text-slate-900 dark:text-zinc-50 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">
			<a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h3>
		<svg class="ss-post-card-arrow w-5 h-5 text-slate-400 dark:text-zinc-500 shrink-0 transform transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1 stroke-current fill-none stroke-2 mt-0.5" viewBox="0 0 24 24">
			<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
		</svg>
	</div>

	<!-- Excerpt -->
	<p class="ss-post-card-excerpt mt-2 text-sm leading-relaxed text-slate-600 dark:text-zinc-400 line-clamp-2">
		<?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?>
	</p>
</article>
