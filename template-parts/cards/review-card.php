<?php
/**
 * Review book card.
 *
 * @package SukuSastra
 */
$post_id = get_the_ID();
$book_title = sukusastra_get_meta( $post_id, '_ss_book_title', get_the_title() );
$book_author = sukusastra_get_meta( $post_id, '_ss_book_author' );
$tokopedia_url = sukusastra_get_meta( $post_id, '_ss_tokopedia_url' );
$shopee_url = sukusastra_get_meta( $post_id, '_ss_shopee_url' );
$whatsapp_val = sukusastra_get_meta( $post_id, '_ss_whatsapp_url' );

$cta_label = '';
$cta_url = '';

if ( $tokopedia_url ) {
	$cta_url = $tokopedia_url;
	$cta_label = __( 'Beli di Tokopedia', 'sukusastra' );
} elseif ( $shopee_url ) {
	$cta_url = $shopee_url;
	$cta_label = __( 'Beli di Shopee', 'sukusastra' );
} elseif ( $whatsapp_val ) {
	if ( str_starts_with( $whatsapp_val, 'http' ) ) {
		$cta_url = $whatsapp_val;
	} else {
		$clean_num = preg_replace( '/[^0-9]/', '', $whatsapp_val );
		if ( str_starts_with( $clean_num, '0' ) ) {
			$clean_num = '62' . substr( $clean_num, 1 );
		}
		$cta_url = 'https://api.whatsapp.com/send?phone=' . $clean_num;
	}
	$cta_label = __( 'Beli via WhatsApp', 'sukusastra' );
} else {
	$cta_label = sukusastra_cta_label( sukusastra_get_meta( $post_id, '_ss_marketplace_label' ), sukusastra_get_meta( $post_id, '_ss_contact_label' ) );
	$cta_url = sukusastra_get_meta( $post_id, '_ss_marketplace_url', sukusastra_get_meta( $post_id, '_ss_contact_url' ) );
}
?>
<article <?php post_class( 'ss-card grid gap-4 md:grid-cols-[120px_1fr]' ); ?>>
	<a class="block no-underline" href="<?php the_permalink(); ?>">
		<?php 
		$book_image_id = sukusastra_get_meta( $post_id, '_ss_book_image_id' );
		if ( $book_image_id ) : 
			?>
			<?php echo wp_get_attachment_image( $book_image_id, 'sukusastra-cover', false, array( 'class' => 'aspect-[2/3] w-full object-cover rounded shadow-sm' ) ); ?>
		<?php elseif ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'sukusastra-cover', array( 'class' => 'aspect-[2/3] w-full object-cover rounded shadow-sm' ) ); ?>
		<?php else : ?>
			<div class="flex aspect-[2/3] items-center justify-center bg-slate-100 p-4 text-center font-serif text-lg text-slate-500 rounded dark:bg-zinc-800 dark:text-zinc-400">
				<?php echo esc_html( $book_title ); ?>
			</div>
		<?php endif; ?>
	</a>
	<div class="grid content-start gap-2">
		<p class="ss-eyebrow">
			<?php 
			esc_html_e( 'Review Buku', 'sukusastra' ); 
			$orig_author = sukusastra_get_original_author( $post_id );
			if ( $orig_author ) {
				printf(
					' · <a class="hover:text-red-700 dark:hover:text-red-300" href="%1$s">%2$s</a>',
					esc_url( get_permalink( $orig_author->ID ) ),
					esc_html( $orig_author->post_title )
				);
			} elseif ( sukusastra_get_meta( $post_id, '_ss_reviewer' ) ) {
				printf( ' · %s', esc_html( sukusastra_get_meta( $post_id, '_ss_reviewer' ) ) );
			}
			?>
		</p>
		<h3 class="ss-card-title"><a class="ss-card-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="ss-body"><?php echo esc_html( $book_title ); ?><?php echo $book_author ? ' · ' . esc_html( $book_author ) : ''; ?></p>
		<p class="ss-body"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
	</div>
</article>
