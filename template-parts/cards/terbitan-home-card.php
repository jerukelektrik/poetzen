<?php
/**
 * Terbitan catalog item card for homepage.
 *
 * @package SukuSastra
 */
$post_id = get_the_ID();
$book_title = get_the_title();
$book_author = sukusastra_get_meta( $post_id, '_ss_book_author' );
$price = sukusastra_get_meta( $post_id, '_ss_book_price' );
$book_image_id = sukusastra_get_meta( $post_id, '_ss_book_image_id' );

$img_class = 'absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105';
?>
<article class="ss-terbitan-home-card flex flex-col group gap-2.5">
	<a class="ss-terbitan-home-cover block no-underline overflow-hidden rounded-2xl aspect-[2/3] relative bg-slate-100 dark:bg-zinc-900 border border-slate-200/50 dark:border-zinc-800 shadow-sm" href="<?php the_permalink(); ?>">
		<?php if ( $book_image_id ) : ?>
			<?php echo wp_get_attachment_image( $book_image_id, 'large', false, array( 'class' => $img_class ) ); ?>
		<?php elseif ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', array( 'class' => $img_class ) ); ?>
		<?php else : ?>
			<div class="absolute inset-0 flex items-center justify-center p-4 text-center">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php echo esc_attr( $book_title ); ?>" class="max-h-24 max-w-full opacity-60 dark:opacity-30 object-contain" />
			</div>
		<?php endif; ?>
		
		<!-- Dark overlay at bottom for author readability -->
		<div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80 z-10"></div>
		
		<!-- Author Name overlay at the bottom center (like Kumparan) -->
		<div class="ss-terbitan-home-author absolute bottom-4 left-0 right-0 text-center z-20 px-3">
			<span class="text-xs font-bold uppercase tracking-wide text-white drop-shadow-md">
				<?php echo esc_html( $book_author ? $book_author : 'Suku Sastra' ); ?>
			</span>
		</div>
		
		<!-- Light hover overlay -->
		<div class="absolute inset-0 bg-black/5 dark:bg-black/10 group-hover:bg-black/0 transition-colors duration-300 z-15"></div>
	</a>

	<div class="ss-terbitan-home-meta flex flex-col mt-2">
		<div class="flex items-start justify-between gap-2">
			<h3 class="ss-terbitan-home-title text-lg font-black leading-snug text-slate-900 dark:text-zinc-50 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors line-clamp-2">
				<a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			<svg class="ss-terbitan-home-arrow w-5 h-5 text-slate-400 dark:text-zinc-500 shrink-0 transform transition-transform duration-300 group-hover:translate-x-1 group-hover:-translate-y-1 stroke-current fill-none stroke-2 mt-0.5" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
			</svg>
		</div>
		<p class="ss-terbitan-home-price mt-1.5 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-zinc-400">
			<?php echo esc_html( $price ? $price : __( 'Tanya WA', 'sukusastra' ) ); ?>
		</p>
	</div>
</article>
