<?php
/**
 * Terbitan catalog item card.
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
<article <?php post_class( 'ss-card flex flex-col gap-4 group h-full' ); ?>>
	<a class="block no-underline overflow-hidden rounded-2xl aspect-[2/3] relative bg-slate-100 dark:bg-zinc-900 border border-slate-200/50 dark:border-zinc-800 shadow-sm" href="<?php the_permalink(); ?>">
		<?php if ( $book_image_id ) : ?>
			<?php echo wp_get_attachment_image( $book_image_id, 'sukusastra-cover-sm', false, array( 'class' => $img_class, 'sizes' => '(max-width: 640px) 45vw, (max-width: 1024px) 25vw, 260px', 'decoding' => 'async' ) ); ?>
		<?php elseif ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'sukusastra-cover-sm', array( 'class' => $img_class, 'sizes' => '(max-width: 640px) 45vw, (max-width: 1024px) 25vw, 260px', 'decoding' => 'async' ) ); ?>
		<?php else : ?>
			<div class="absolute inset-0 flex items-center justify-center p-4 text-center">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php echo esc_attr( $book_title ); ?>" class="max-h-14 max-w-full opacity-50 dark:opacity-20 object-contain" />
			</div>
		<?php endif; ?>
		<!-- Overlay overlay hover -->
		<div class="absolute inset-0 bg-black/5 dark:bg-black/10 group-hover:bg-black/0 transition-colors duration-300"></div>
	</a>

	<div class="flex flex-col flex-1 gap-2.5">
		<div class="flex flex-col gap-1 flex-1">
			<p class="text-[10px] text-slate-400 dark:text-zinc-500 font-bold uppercase tracking-wider">
				<?php 
				if ( $book_author ) {
					printf( esc_html__( 'Karya %s', 'sukusastra' ), esc_html( $book_author ) );
				} else {
					esc_html_e( 'Terbitan Suku Sastra', 'sukusastra' );
				}
				?>
			</p>
			<h3 class="ss-card-title text-base line-clamp-2">
				<a class="ss-card-title-link" href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
		</div>
		
		<div class="flex items-center justify-between gap-3 pt-3 border-t border-slate-100/60 dark:border-zinc-800/40">
			<span class="text-sm font-black text-red-700 dark:text-red-400">
				<?php echo esc_html( $price ? $price : __( 'Tanya WA', 'sukusastra' ) ); ?>
			</span>
			<a href="<?php the_permalink(); ?>" class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-zinc-400 hover:text-red-700 dark:hover:text-red-400 no-underline inline-flex items-center gap-1 transition-colors">
				<span><?php esc_html_e( 'Detail', 'sukusastra' ); ?></span>
				<svg class="w-3.5 h-3.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
				</svg>
			</a>
		</div>
	</div>
</article>
