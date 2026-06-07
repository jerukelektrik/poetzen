<?php
/**
 * Native editor metaboxes.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'add_meta_boxes', 'sukusastra_register_metaboxes' );
function sukusastra_register_metaboxes(): void {
	$all_public = array( 'post', 'page', 'review_buku', 'berita', 'event' );

	add_meta_box( 'sukusastra_editorial_meta', __( 'Suku Sastra Editorial', 'sukusastra' ), 'sukusastra_render_editorial_metabox', $all_public, 'side', 'default' );
	add_meta_box( 'sukusastra_seo_meta', __( 'SEO Ringan', 'sukusastra' ), 'sukusastra_render_seo_metabox', $all_public, 'normal', 'default' );
	add_meta_box( 'sukusastra_review_meta', __( 'Metadata Buku', 'sukusastra' ), 'sukusastra_render_review_metabox', 'review_buku', 'normal', 'default' );
	add_meta_box( 'sukusastra_news_meta', __( 'Metadata Berita', 'sukusastra' ), 'sukusastra_render_news_metabox', 'berita', 'normal', 'default' );
	add_meta_box( 'sukusastra_event_meta', __( 'Metadata Event', 'sukusastra' ), 'sukusastra_render_event_metabox', 'event', 'normal', 'default' );
	
	add_meta_box( 'sukusastra_original_author_meta', __( 'Penulis Asli (Tokoh)', 'sukusastra' ), 'sukusastra_render_original_author_metabox', 'post', 'normal', 'default' );
	add_meta_box( 'sukusastra_pesan_moral_meta', __( 'Pesan Moral', 'sukusastra' ), 'sukusastra_render_pesan_moral_metabox', 'post', 'normal', 'default' );
	add_meta_box( 'sukusastra_penulis_meta', __( 'Metadata Penulis/Tokoh', 'sukusastra' ), 'sukusastra_render_penulis_metabox', 'penulis', 'normal', 'default' );
}

function sukusastra_render_editorial_metabox( WP_Post $post ): void {
	wp_nonce_field( 'sukusastra_save_meta', 'sukusastra_meta_nonce' );
	$show_home = sukusastra_get_meta( $post->ID, '_ss_show_home', '1' );
	$is_seo    = sukusastra_get_meta( $post->ID, '_ss_is_seo_article', '0' );
	?>
	<p>
		<label>
			<input type="checkbox" name="ss_show_home" value="1" <?php echo sukusastra_checked( $show_home, '1' ); ?>>
			<?php esc_html_e( 'Tampilkan di homepage/editorial feed', 'sukusastra' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="ss_is_seo_article" value="1" <?php echo sukusastra_checked( $is_seo, '1' ); ?>>
			<?php esc_html_e( 'Artikel SEO/Ruang Baca', 'sukusastra' ); ?>
		</label>
	</p>
	<?php
}

function sukusastra_render_seo_metabox( WP_Post $post ): void {
	$fields = array(
		'_ss_seo_title'       => __( 'SEO Title', 'sukusastra' ),
		'_ss_meta_desc'       => __( 'Meta Description', 'sukusastra' ),
		'_ss_canonical'       => __( 'Canonical URL', 'sukusastra' ),
		'_ss_redirect_target' => __( 'Redirect Target URL', 'sukusastra' ),
	);
	foreach ( $fields as $key => $label ) {
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" id="%1$s" name="%1$s" type="text" value="%3$s"></p>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( sukusastra_get_meta( $post->ID, $key ) )
		);
	}
	$robots       = sukusastra_get_meta( $post->ID, '_ss_robots', 'index,follow' );
	$redirect_type = sukusastra_get_meta( $post->ID, '_ss_redirect_type', '301' );
	?>
	<p>
		<label for="_ss_robots"><strong><?php esc_html_e( 'Robots', 'sukusastra' ); ?></strong></label><br>
		<select class="widefat" id="_ss_robots" name="_ss_robots">
			<option value="index,follow" <?php echo sukusastra_selected( $robots, 'index,follow' ); ?>>index, follow</option>
			<option value="noindex,follow" <?php echo sukusastra_selected( $robots, 'noindex,follow' ); ?>>noindex, follow</option>
		</select>
	</p>
	<p>
		<label for="_ss_redirect_type"><strong><?php esc_html_e( 'Redirect Type', 'sukusastra' ); ?></strong></label><br>
		<select class="widefat" id="_ss_redirect_type" name="_ss_redirect_type">
			<option value="301" <?php echo sukusastra_selected( $redirect_type, '301' ); ?>>301</option>
			<option value="302" <?php echo sukusastra_selected( $redirect_type, '302' ); ?>>302</option>
		</select>
	</p>
	<?php
}

function sukusastra_render_review_metabox( WP_Post $post ): void {
	$book_image_id = sukusastra_get_meta( $post->ID, '_ss_book_image_id', '' );
	$image_url     = '';
	if ( $book_image_id ) {
		$image_url = wp_get_attachment_image_url( (int) $book_image_id, 'medium' );
	}
	?>
	<div class="ss-book-image-uploader-wrapper" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
		<label><strong><?php esc_html_e( 'Foto/Sampul Buku (Sampul Sahaja)', 'sukusastra' ); ?></strong></label>
		<p class="description" style="margin-top: 4px;"><?php esc_html_e( 'Upload sampul buku di sini. Ini berbeda dengan Featured Image (Gambar Utama) yang digunakan sebagai banner artikel.', 'sukusastra' ); ?></p>
		<div style="margin-top: 10px;">
			<div id="ss-book-image-preview" style="max-width: 150px; min-height: 100px; border: 1px dashed #ccc; padding: 5px; background: #fafafa; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
				<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url ); ?>" style="max-width: 100%; height: auto; display: block;" />
				<?php else : ?>
					<span style="color: #999; font-size: 12px;"><?php esc_html_e( 'Belum ada gambar', 'sukusastra' ); ?></span>
				<?php endif; ?>
			</div>
			<input type="hidden" name="_ss_book_image_id" id="ss_book_image_id" value="<?php echo esc_attr( $book_image_id ); ?>">
			<button type="button" class="button" id="ss-upload-book-image-btn"><?php esc_html_e( 'Pilih Gambar', 'sukusastra' ); ?></button>
			<button type="button" class="button button-link-delete" id="ss-remove-book-image-btn" style="<?php echo $book_image_id ? '' : 'display: none;'; ?>"><?php esc_html_e( 'Hapus', 'sukusastra' ); ?></button>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var file_frame;
		$('#ss-upload-book-image-btn').on('click', function(e) {
			e.preventDefault();
			if (file_frame) {
				file_frame.open();
				return;
			}
			file_frame = wp.media.frames.file_frame = wp.media({
				title: '<?php echo esc_js( __( 'Pilih Sampul Buku', 'sukusastra' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'Gunakan Sampul ini', 'sukusastra' ) ); ?>'
				},
				multiple: false
			});
			file_frame.on('select', function() {
				var attachment = file_frame.state().get('selection').first().toJSON();
				$('#ss_book_image_id').val(attachment.id);
				
				var previewUrl = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
				$('#ss-book-image-preview').html('<img src="' + previewUrl + '" style="max-width: 100%; height: auto; display: block;" />');
				$('#ss-remove-book-image-btn').show();
			});
			file_frame.open();
		});

		$('#ss-remove-book-image-btn').on('click', function(e) {
			e.preventDefault();
			$('#ss_book_image_id').val('');
			$('#ss-book-image-preview').html('<span style="color: #999; font-size: 12px;"><?php echo esc_js( __( 'Belum ada gambar', 'sukusastra' ) ); ?></span>');
			$(this).hide();
		});
	});
	</script>
	<?php
	sukusastra_render_text_fields(
		$post->ID,
		array(
			'_ss_book_title'        => __( 'Judul Buku', 'sukusastra' ),
			'_ss_book_author'       => __( 'Penulis Buku', 'sukusastra' ),
			'_ss_book_publisher'    => __( 'Penerbit', 'sukusastra' ),
			'_ss_book_year'         => __( 'Tahun Terbit', 'sukusastra' ),
			'_ss_reviewer'          => __( 'Reviewer', 'sukusastra' ),
			'_ss_review_summary'    => __( 'Ringkasan Pendek', 'sukusastra' ),
			'_ss_shopee_url'        => array(
				'label'       => __( 'Shopee URL', 'sukusastra' ),
				'placeholder' => 'https://shopee.co.id/...',
			),
			'_ss_tokopedia_url'     => array(
				'label'       => __( 'Tokopedia URL', 'sukusastra' ),
				'placeholder' => 'https://tokopedia.com/...',
			),
			'_ss_whatsapp_url'      => array(
				'label'       => __( 'WhatsApp URL atau Nomor HP', 'sukusastra' ),
				'placeholder' => '6281234567890 atau https://wa.me/6281234567890',
			),
		)
	);
}

function sukusastra_render_news_metabox( WP_Post $post ): void {
	sukusastra_render_text_fields(
		$post->ID,
		array(
			'_ss_news_summary' => __( 'Ringkasan Berita', 'sukusastra' ),
			'_ss_location'     => __( 'Lokasi', 'sukusastra' ),
			'_ss_source_url'   => __( 'URL Sumber/Rujukan', 'sukusastra' ),
			'_ss_youtube_url'  => __( 'URL YouTube', 'sukusastra' ),
		)
	);
}

function sukusastra_render_event_metabox( WP_Post $post ): void {
	sukusastra_render_text_fields(
		$post->ID,
		array(
			'_ss_event_start'   => __( 'Tanggal Mulai (YYYY-MM-DD)', 'sukusastra' ),
			'_ss_event_end'     => __( 'Tanggal Selesai (YYYY-MM-DD)', 'sukusastra' ),
			'_ss_event_location'=> __( 'Lokasi/Format', 'sukusastra' ),
			'_ss_booking_label' => __( 'Label Booking', 'sukusastra' ),
			'_ss_booking_url'   => __( 'URL Booking', 'sukusastra' ),
			'_ss_contact_label' => __( 'Label Contact Sales/CP', 'sukusastra' ),
			'_ss_contact_url'   => __( 'URL Contact Sales/CP', 'sukusastra' ),
		)
	);
	$status = sukusastra_get_meta( $post->ID, '_ss_event_status', 'upcoming' );
	$tickets = sukusastra_get_meta( $post->ID, '_ss_ticket_availability', 'available' );
	$paid = sukusastra_get_meta( $post->ID, '_ss_paid_ticket', '0' );
	?>
	<p><label for="_ss_event_status"><strong><?php esc_html_e( 'Status', 'sukusastra' ); ?></strong></label><br>
	<select class="widefat" id="_ss_event_status" name="_ss_event_status">
		<option value="upcoming" <?php echo sukusastra_selected( $status, 'upcoming' ); ?>>upcoming</option>
		<option value="past" <?php echo sukusastra_selected( $status, 'past' ); ?>>past</option>
		<option value="cancelled" <?php echo sukusastra_selected( $status, 'cancelled' ); ?>>cancelled</option>
	</select></p>
	<p><label for="_ss_ticket_availability"><strong><?php esc_html_e( 'Ketersediaan Tiket', 'sukusastra' ); ?></strong></label><br>
	<select class="widefat" id="_ss_ticket_availability" name="_ss_ticket_availability">
		<option value="available" <?php echo sukusastra_selected( $tickets, 'available' ); ?>>available</option>
		<option value="sold_out" <?php echo sukusastra_selected( $tickets, 'sold_out' ); ?>>sold_out</option>
	</select></p>
	<p><label><input type="checkbox" name="_ss_paid_ticket" value="1" <?php echo sukusastra_checked( $paid, '1' ); ?>> <?php esc_html_e( 'Tiket berbayar', 'sukusastra' ); ?></label></p>
	<?php
}

function sukusastra_render_pesan_moral_metabox( WP_Post $post ): void {
	$pesan_moral = sukusastra_get_meta( $post->ID, '_ss_pesan_moral', '' );
	?>
	<p>
		<label for="_ss_pesan_moral"><strong><?php esc_html_e( 'Pesan Moral / Hikmah Cerita', 'sukusastra' ); ?></strong></label><br>
		<textarea class="widefat" id="_ss_pesan_moral" name="_ss_pesan_moral" rows="4" placeholder="<?php esc_attr_e( 'Tuliskan pesan moral atau hikmah dari karya/cerita ini...', 'sukusastra' ); ?>"><?php echo esc_textarea( $pesan_moral ); ?></textarea>
	</p>
	<p class="description"><?php esc_html_e( 'Pesan moral ini akan ditampilkan di akhir artikel dengan highlight kotak khusus.', 'sukusastra' ); ?></p>
	<?php
}

function sukusastra_render_original_author_metabox( WP_Post $post ): void {
	wp_nonce_field( 'sukusastra_save_author_meta', 'sukusastra_author_meta_nonce' );
	$selected_author_id = sukusastra_get_meta( $post->ID, '_ss_original_author_id', '' );

	$authors = get_posts(
		array(
			'post_type'      => 'penulis',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);
	?>
	<p>
		<label for="_ss_original_author_id"><strong><?php esc_html_e( 'Pilih Tokoh/Penulis Asli', 'sukusastra' ); ?></strong></label><br>
		<select class="widefat" id="_ss_original_author_id" name="_ss_original_author_id">
			<option value=""><?php esc_html_e( '-- Tanpa Penulis/Tokoh (Uploader Sahaja) --', 'sukusastra' ); ?></option>
			<?php foreach ( $authors as $author ) : ?>
				<option value="<?php echo esc_attr( (string) $author->ID ); ?>" <?php echo sukusastra_selected( $selected_author_id, (string) $author->ID ); ?>>
					<?php echo esc_html( $author->post_title ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<?php
}

function sukusastra_render_penulis_metabox( WP_Post $post ): void {
	wp_nonce_field( 'sukusastra_save_penulis_meta', 'sukusastra_penulis_meta_nonce' );

	sukusastra_render_text_fields(
		$post->ID,
		array(
			'_ss_penulis_tempat_lahir'  => __( 'Tempat Lahir', 'sukusastra' ),
			'_ss_penulis_tanggal_lahir' => __( 'Tanggal Lahir (misal: 26 Juli 1922)', 'sukusastra' ),
			'_ss_penulis_bio_summary'   => __( 'Bio Summary (Deskripsi Singkat)', 'sukusastra' ),
		)
	);
}

function sukusastra_render_text_fields( int $post_id, array $fields ): void {
	foreach ( $fields as $key => $data ) {
		$label = is_array( $data ) ? $data['label'] : $data;
		$placeholder = is_array( $data ) && isset( $data['placeholder'] ) ? $data['placeholder'] : '';
		printf(
			'<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" id="%1$s" name="%1$s" type="text" value="%3$s" placeholder="%4$s"></p>',
			esc_attr( $key ),
			esc_html( $label ),
			esc_attr( sukusastra_get_meta( $post_id, $key ) ),
			esc_attr( $placeholder )
		);
	}
}

add_action( 'save_post', 'sukusastra_save_metaboxes' );
function sukusastra_save_metaboxes( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// 1. Save general/editorial/SEO fields (if nonce exists)
	if ( isset( $_POST['sukusastra_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sukusastra_meta_nonce'] ) ), 'sukusastra_save_meta' ) ) {
		$checkboxes = array( 'ss_show_home' => '_ss_show_home', 'ss_is_seo_article' => '_ss_is_seo_article', '_ss_paid_ticket' => '_ss_paid_ticket' );
		foreach ( $checkboxes as $input => $meta_key ) {
			update_post_meta( $post_id, $meta_key, isset( $_POST[ $input ] ) ? '1' : '0' );
		}

		$text_fields = array(
			'_ss_seo_title',
			'_ss_meta_desc',
			'_ss_canonical',
			'_ss_redirect_target',
			'_ss_book_image_id',
			'_ss_book_title',
			'_ss_book_author',
			'_ss_book_publisher',
			'_ss_book_year',
			'_ss_reviewer',
			'_ss_review_summary',
			'_ss_shopee_url',
			'_ss_tokopedia_url',
			'_ss_whatsapp_url',
			'_ss_marketplace_label',
			'_ss_marketplace_url',
			'_ss_contact_label',
			'_ss_contact_url',
			'_ss_news_summary',
			'_ss_location',
			'_ss_source_url',
			'_ss_youtube_url',
			'_ss_event_start',
			'_ss_event_end',
			'_ss_event_location',
			'_ss_booking_label',
			'_ss_booking_url',
			'_ss_contact_url',
		);
		foreach ( $text_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}

		if ( isset( $_POST['_ss_pesan_moral'] ) ) {
			update_post_meta( $post_id, '_ss_pesan_moral', sanitize_textarea_field( wp_unslash( $_POST['_ss_pesan_moral'] ) ) );
		}

		$robots = isset( $_POST['_ss_robots'] ) ? sanitize_text_field( wp_unslash( $_POST['_ss_robots'] ) ) : 'index,follow';
		update_post_meta( $post_id, '_ss_robots', in_array( $robots, array( 'index,follow', 'noindex,follow' ), true ) ? $robots : 'index,follow' );

		$redirect_type = isset( $_POST['_ss_redirect_type'] ) ? sanitize_text_field( wp_unslash( $_POST['_ss_redirect_type'] ) ) : '301';
		update_post_meta( $post_id, '_ss_redirect_type', in_array( $redirect_type, array( '301', '302' ), true ) ? $redirect_type : '301' );

		$status = isset( $_POST['_ss_event_status'] ) ? sanitize_text_field( wp_unslash( $_POST['_ss_event_status'] ) ) : 'upcoming';
		update_post_meta( $post_id, '_ss_event_status', in_array( $status, array( 'upcoming', 'past', 'cancelled' ), true ) ? $status : 'upcoming' );

		$tickets = isset( $_POST['_ss_ticket_availability'] ) ? sanitize_text_field( wp_unslash( $_POST['_ss_ticket_availability'] ) ) : 'available';
		update_post_meta( $post_id, '_ss_ticket_availability', in_array( $tickets, array( 'available', 'sold_out' ), true ) ? $tickets : 'available' );
	}

	// 2. Save original author linkage (if nonce exists)
	if ( isset( $_POST['sukusastra_author_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sukusastra_author_meta_nonce'] ) ), 'sukusastra_save_author_meta' ) ) {
		$author_id = isset( $_POST['_ss_original_author_id'] ) ? sanitize_text_field( wp_unslash( $_POST['_ss_original_author_id'] ) ) : '';
		update_post_meta( $post_id, '_ss_original_author_id', $author_id );
	}

	// 3. Save penulis details (if nonce exists)
	if ( isset( $_POST['sukusastra_penulis_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sukusastra_penulis_meta_nonce'] ) ), 'sukusastra_save_penulis_meta' ) ) {
		$penulis_fields = array(
			'_ss_penulis_tempat_lahir',
			'_ss_penulis_tanggal_lahir',
			'_ss_penulis_bio_summary',
		);
		foreach ( $penulis_fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
			}
		}
	}
}
