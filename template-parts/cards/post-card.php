<?php
/**
 * Standard post card.
 *
 * @package SukuSastra
 */
?>
<article <?php post_class( 'ss-card grid gap-4' ); ?>>
	<a class="block no-underline" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'sukusastra-card', array( 'class' => 'aspect-[3/2] w-full object-cover rounded' ) ); ?>
		<?php else : ?>
			<div class="flex aspect-[3/2] items-center justify-center bg-slate-100 p-6 text-center font-serif text-2xl text-slate-500 rounded dark:bg-zinc-800 dark:text-zinc-400">
				<?php echo esc_html( get_the_title() ); ?>
			</div>
		<?php endif; ?>
	</a>
	<div class="grid gap-2">
		<p class="ss-eyebrow">
			<?php 
			echo get_the_category_list( ', ' ); 
			$orig_author = sukusastra_get_original_author( get_the_ID() );
			if ( $orig_author ) {
				printf(
					' · <a class="hover:text-red-700 dark:hover:text-red-300" href="%1$s">%2$s</a>',
					esc_url( get_permalink( $orig_author->ID ) ),
					esc_html( $orig_author->post_title )
				);
			} else {
				printf( ' · %s', get_the_author() );
			}
			?>
		</p>
		<h3 class="ss-card-title"><a class="ss-card-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="ss-body"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
	</div>
</article>
