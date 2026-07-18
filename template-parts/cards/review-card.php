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
<?php
$layout = isset( $args['layout'] ) ? $args['layout'] : 'horizontal';
$article_classes = 'ss-card ss-review-card h-full ';
$content_classes = 'ss-review-card-content ';

if ( 'vertical' === $layout ) {
	$article_classes .= 'flex flex-col justify-between gap-3 group !rounded-2xl';
	$content_classes .= 'flex flex-col justify-between flex-grow gap-3';
} else {
	$article_classes .= 'grid gap-4 md:grid-cols-[120px_1fr]';
	$content_classes .= 'grid content-start gap-2';
}
?>
<article <?php post_class( $article_classes ); ?>>
	<a class="ss-review-card-media block no-underline overflow-hidden rounded-xl" href="<?php the_permalink(); ?>">
		<?php 
		$book_image_id = sukusastra_get_meta( $post_id, '_ss_book_image_id' );
		$img_class = 'aspect-[2/3] w-full object-cover rounded-xl shadow-sm transition-transform duration-500';
		if ( 'vertical' === $layout ) {
			$img_class .= ' group-hover:scale-105';
		}
		if ( $book_image_id ) : 
			?>
			<?php echo wp_get_attachment_image( $book_image_id, 'sukusastra-cover', false, array( 'class' => $img_class ) ); ?>
		<?php elseif ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'sukusastra-cover', array( 'class' => $img_class ) ); ?>
		<?php else : ?>
			<div class="ss-review-card-placeholder flex aspect-[2/3] w-full items-center justify-center bg-slate-50 dark:bg-zinc-900/40 p-4 dark:text-zinc-400">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php echo esc_attr( $book_title ); ?>" class="max-h-14 max-w-full opacity-50 dark:opacity-20 object-contain" />
			</div>
		<?php endif; ?>
	</a>
	<div class="<?php echo esc_attr( $content_classes ); ?>">
		<?php if ( 'vertical' === $layout ) : ?>
			<div class="grid gap-2">
				<p class="ss-eyebrow">
					<?php 
					$book_type_val = sukusastra_get_meta( $post_id, '_ss_book_type', 'novel' );
					$book_types = array(
						'puisi'    => __( 'Kumpulan Puisi', 'sukusastra' ),
						'cerpen'   => __( 'Kumpulan Cerpen', 'sukusastra' ),
						'novel'    => __( 'Novel', 'sukusastra' ),
						'nonfiksi' => __( 'Nonfiksi', 'sukusastra' ),
					);
					$book_type_label = isset( $book_types[ $book_type_val ] ) ? $book_types[ $book_type_val ] : $book_types['novel'];
					echo esc_html( $book_type_label );
					?>
				</p>
				<h3 class="ss-review-card-title text-base font-black leading-snug text-slate-900 dark:text-zinc-50 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">
					<a class="ss-card-title-link line-clamp-2" href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a>
				</h3>
			</div>
			
			<p class="ss-review-card-author text-[11px] text-slate-500 dark:text-zinc-400 font-bold uppercase tracking-wide mt-auto">
				<?php 
				$reviewer_info = sukusastra_get_reviewer_info( $post_id );
				if ( ! empty( $reviewer_info['name'] ) ) {
					echo esc_html( $reviewer_info['name'] );
				} elseif ( $orig_author ) {
					echo esc_html( $orig_author->post_title );
				} else {
					$book_author_info = sukusastra_get_book_author_info( $post_id );
					if ( ! empty( $book_author_info['name'] ) ) {
						echo esc_html( $book_author_info['name'] );
					}
				}
				?>
			</p>
		<?php else : ?>
			<p class="ss-eyebrow">
				<?php 
				$book_type_val = sukusastra_get_meta( $post_id, '_ss_book_type', 'novel' );
				$book_types = array(
					'puisi'    => __( 'Kumpulan Puisi', 'sukusastra' ),
					'cerpen'   => __( 'Kumpulan Cerpen', 'sukusastra' ),
					'novel'    => __( 'Novel', 'sukusastra' ),
					'nonfiksi' => __( 'Nonfiksi', 'sukusastra' ),
				);
				$book_type_label = isset( $book_types[ $book_type_val ] ) ? $book_types[ $book_type_val ] : $book_types['novel'];
				printf( '%s (%s)', esc_html__( 'Reviu Buku', 'sukusastra' ), esc_html( $book_type_label ) );

				$reviewer_info = sukusastra_get_reviewer_info( $post_id );
				if ( ! empty( $reviewer_info['name'] ) ) {
					if ( ! empty( $reviewer_info['url'] ) ) {
						printf(
							' · <a class="hover:text-red-700 dark:hover:text-red-300" href="%1$s">%2$s</a>',
							esc_url( $reviewer_info['url'] ),
							esc_html( $reviewer_info['name'] )
						);
					} else {
						printf( ' · %s', esc_html( $reviewer_info['name'] ) );
					}
				} elseif ( $orig_author ) {
					printf(
						' · <a class="hover:text-red-700 dark:hover:text-red-300" href="%1$s">%2$s</a>',
						esc_url( get_permalink( $orig_author->ID ) ),
						esc_html( $orig_author->post_title )
					);
				}
				?>
			</p>
			<h3 class="ss-card-title ss-review-card-title">
				<a class="ss-card-title-link line-clamp-2" href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			<?php
			$book_author_info = sukusastra_get_book_author_info( $post_id );
			$author_text = ! empty( $book_author_info['name'] ) ? ' · ' . $book_author_info['name'] : '';
			?>
			<p class="ss-body"><?php echo esc_html( $book_title ); ?><?php echo esc_html( $author_text ); ?></p>
			<p class="ss-body line-clamp-2"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<?php endif; ?>
	</div>
</article>
