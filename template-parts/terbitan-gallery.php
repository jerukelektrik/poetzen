<?php
/**
 * Terbitan Gallery Carousel / Single image renderer.
 *
 * @package Poetzen
 */

$post_id = get_the_ID();
$gallery_ids = sukusastra_get_meta( $post_id, '_ss_terbitan_gallery', '' );
$gallery_array = $gallery_ids ? explode( ',', $gallery_ids ) : array();

// If we have a gallery, we prepend the featured image or primary cover image (_ss_book_image_id) to it so that it is the first slide!
$primary_cover_id = sukusastra_get_meta( $post_id, '_ss_book_image_id', '' );
if ( ! $primary_cover_id && has_post_thumbnail( $post_id ) ) {
	$primary_cover_id = get_post_thumbnail_id( $post_id );
}

if ( $primary_cover_id ) {
	if ( ! in_array( $primary_cover_id, $gallery_array ) ) {
		array_unshift( $gallery_array, $primary_cover_id );
	}
}

if ( empty( $gallery_array ) ) {
	return;
}

$carousel_id = 'ss-terbitan-carousel-' . wp_generate_uuid4();
$is_multiple = count( $gallery_array ) > 1;
?>

<div class="relative w-full overflow-hidden group/carousel">
	<?php if ( $is_multiple ) : ?>
		<!-- Scrollable Slides Wrapper -->
		<div id="<?php echo esc_attr( $carousel_id ); ?>" class="flex w-full overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
			<?php foreach ( $gallery_array as $index => $img_id ) : ?>
				<div class="w-full shrink-0 snap-start snap-always flex justify-center items-center">
					<div class="w-full aspect-[2/3] relative rounded-2xl overflow-hidden bg-slate-100 dark:bg-zinc-900 border border-slate-200/50 dark:border-zinc-850 shadow-inner">
						<?php echo wp_get_attachment_image( (int) $img_id, 'large', false, array( 'class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-500 hover:scale-105' ) ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- Left/Right Arrow Navs -->
		<button type="button" class="absolute left-3 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-black/80 transition-colors opacity-0 group-hover/carousel:opacity-100 focus:opacity-100 z-10 cursor-pointer" onclick="document.getElementById('<?php echo esc_attr( $carousel_id ); ?>').scrollLeft -= document.getElementById('<?php echo esc_attr( $carousel_id ); ?>').offsetWidth">
			<svg class="h-4 w-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
		</button>
		<button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-full bg-black/60 text-white hover:bg-black/80 transition-colors opacity-0 group-hover/carousel:opacity-100 focus:opacity-100 z-10 cursor-pointer" onclick="document.getElementById('<?php echo esc_attr( $carousel_id ); ?>').scrollLeft += document.getElementById('<?php echo esc_attr( $carousel_id ); ?>').offsetWidth">
			<svg class="h-4 w-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
		</button>

		<!-- Indicator Dots -->
		<div class="flex justify-center gap-1.5 mt-3">
			<?php foreach ( $gallery_array as $index => $img_id ) : ?>
				<button type="button" class="h-1.5 w-1.5 rounded-full bg-slate-300 dark:bg-zinc-700 transition-all cursor-pointer ss-carousel-dot-<?php echo esc_attr( $carousel_id ); ?> <?php echo 0 === $index ? 'bg-red-700 dark:bg-red-500 w-3' : ''; ?>" onclick="document.getElementById('<?php echo esc_attr( $carousel_id ); ?>').scrollLeft = <?php echo $index; ?> * document.getElementById('<?php echo esc_attr( $carousel_id ); ?>').offsetWidth"></button>
			<?php endforeach; ?>
		</div>

		<!-- JS to update active dots on scroll -->
		<script>
		(function() {
			const container = document.getElementById('<?php echo esc_js( $carousel_id ); ?>');
			const dots = document.querySelectorAll('.ss-carousel-dot-<?php echo esc_js( $carousel_id ); ?>');
			if (!container || dots.length === 0) return;

			container.addEventListener('scroll', function() {
				const width = container.offsetWidth;
				const index = Math.round(container.scrollLeft / width);
				dots.forEach((dot, idx) => {
					if (idx === index) {
						dot.classList.add('bg-red-700', 'dark:bg-red-500', 'w-3');
						dot.classList.remove('bg-slate-300', 'dark:bg-zinc-700');
					} else {
						dot.classList.remove('bg-red-700', 'dark:bg-red-500', 'w-3');
						dot.classList.add('bg-slate-300', 'dark:bg-zinc-700');
					}
				});
			});
		})();
		</script>
	<?php else : ?>
		<!-- Single image fallback -->
		<div class="w-full shrink-0 flex justify-center items-center">
			<div class="w-full aspect-[2/3] relative rounded-2xl overflow-hidden bg-slate-100 dark:bg-zinc-900 border border-slate-200/50 dark:border-zinc-850 shadow-inner">
				<?php echo wp_get_attachment_image( (int) $gallery_array[0], 'large', false, array( 'class' => 'absolute inset-0 w-full h-full object-cover transition-transform duration-500 hover:scale-105' ) ); ?>
			</div>
		</div>
	<?php endif; ?>
</div>
