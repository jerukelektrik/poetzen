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
	$all_public = array( 'post', 'page', 'review_buku', 'berita', 'event', 'terbitan' );

	add_meta_box( 'sukusastra_editorial_meta', __( 'Suku Sastra Editorial', 'sukusastra' ), 'sukusastra_render_editorial_metabox', $all_public, 'side', 'default' );
	add_meta_box( 'sukusastra_seo_meta', __( 'SEO Ringan', 'sukusastra' ), 'sukusastra_render_seo_metabox', $all_public, 'normal', 'default' );
	add_meta_box( 'sukusastra_review_meta', __( 'Metadata Buku', 'sukusastra' ), 'sukusastra_render_review_metabox', 'review_buku', 'normal', 'default' );
	add_meta_box( 'sukusastra_news_meta', __( 'Metadata Berita', 'sukusastra' ), 'sukusastra_render_news_metabox', 'berita', 'normal', 'default' );
	add_meta_box( 'sukusastra_event_meta', __( 'Metadata Event', 'sukusastra' ), 'sukusastra_render_event_metabox', 'event', 'normal', 'default' );
	add_meta_box( 'sukusastra_terbitan_meta', __( 'Metadata Terbitan', 'sukusastra' ), 'sukusastra_render_terbitan_metabox', 'terbitan', 'normal', 'default' );
	
	add_meta_box( 'sukusastra_original_author_meta', __( 'Penulis Asli (Tokoh)', 'sukusastra' ), 'sukusastra_render_original_author_metabox', 'post', 'normal', 'default' );
	add_meta_box( 'sukusastra_pesan_moral_meta', __( 'Pesan Moral', 'sukusastra' ), 'sukusastra_render_pesan_moral_metabox', 'post', 'normal', 'default' );
	add_meta_box( 'sukusastra_penulis_meta', __( 'Metadata Penulis/Tokoh', 'sukusastra' ), 'sukusastra_render_penulis_metabox', 'penulis', 'normal', 'default' );
	add_meta_box( 'sukusastra_related_post_meta', __( 'Artikel Terkait', 'sukusastra' ), 'sukusastra_render_related_post_metabox', array( 'post', 'review_buku', 'berita' ), 'normal', 'default' );
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
		<p class="description" style="margin-top: 4px;"><?php esc_html_e( 'Upload sampul buku di sini. Ini berbeda dengan Featured Image (Gambar Utama) yang digunakan sebagai banner artikel. Best Practice: Portrait, rasio 3:4 (Rekomendasi: 600x800 px atau 900x1200 px) dengan ukuran maks. 1MB.', 'sukusastra' ); ?></p>
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
	$book_type = sukusastra_get_meta( $post->ID, '_ss_book_type', 'novel' );
	?>
	<p>
		<label for="_ss_book_type"><strong><?php esc_html_e( 'Jenis Buku (Kategori)', 'sukusastra' ); ?></strong></label><br>
		<select class="widefat" id="_ss_book_type" name="_ss_book_type">
			<option value="puisi" <?php echo sukusastra_selected( $book_type, 'puisi' ); ?>><?php esc_html_e( 'Kumpulan Puisi', 'sukusastra' ); ?></option>
			<option value="cerpen" <?php echo sukusastra_selected( $book_type, 'cerpen' ); ?>><?php esc_html_e( 'Kumpulan Cerpen', 'sukusastra' ); ?></option>
			<option value="novel" <?php echo sukusastra_selected( $book_type, 'novel' ); ?>><?php esc_html_e( 'Novel', 'sukusastra' ); ?></option>
			<option value="nonfiksi" <?php echo sukusastra_selected( $book_type, 'nonfiksi' ); ?>><?php esc_html_e( 'Nonfiksi', 'sukusastra' ); ?></option>
		</select>
	</p>
	<?php
	$current_reviewer = sukusastra_get_meta( $post->ID, '_ss_reviewer', '' );
	$current_book_author = sukusastra_get_meta( $post->ID, '_ss_book_author', '' );
	$penulis_posts = get_posts( array(
		'post_type'      => 'penulis',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );

	$is_numeric_reviewer = is_numeric( $current_reviewer );
	$is_numeric_author = is_numeric( $current_book_author );
	?>
	<!-- Reviewer Dual-Mode Dropdown -->
	<p>
		<label for="_ss_reviewer_select"><strong><?php esc_html_e( 'Reviewer (Resensator)', 'sukusastra' ); ?></strong></label><br>
		<select class="widefat" id="_ss_reviewer_select" name="_ss_reviewer_select" onchange="toggleReviewerInput(this.value)">
			<option value="Redaksi Suku Sastra" <?php selected( $current_reviewer, 'Redaksi Suku Sastra' ); ?>><?php esc_html_e( 'Redaksi Suku Sastra (Default)', 'sukusastra' ); ?></option>
			<option value="" <?php selected( ! empty( $current_reviewer ) && ! $is_numeric_reviewer && 'Redaksi Suku Sastra' !== $current_reviewer ); ?>><?php esc_html_e( '— Tulis Manual di Bawah —', 'sukusastra' ); ?></option>
			<optgroup label="<?php esc_attr_e( 'Pilih dari Penulis CPT', 'sukusastra' ); ?>">
				<?php foreach ( $penulis_posts as $penulis ) : ?>
					<option value="<?php echo esc_attr( $penulis->ID ); ?>" <?php selected( $current_reviewer, (string) $penulis->ID ); ?>>
						<?php echo esc_html( $penulis->post_title ); ?>
					</option>
				<?php endforeach; ?>
			</optgroup>
		</select>
	</p>
	<p id="ss-reviewer-text-wrapper" style="<?php echo ( $current_reviewer === 'Redaksi Suku Sastra' || $is_numeric_reviewer ) ? 'display:none;' : ''; ?>">
		<label for="_ss_reviewer"><strong><?php esc_html_e( 'Nama Reviewer (Manual)', 'sukusastra' ); ?></strong></label><br>
		<input type="text" class="widefat" id="_ss_reviewer" name="_ss_reviewer" value="<?php echo esc_attr( ( $current_reviewer === 'Redaksi Suku Sastra' || $is_numeric_reviewer ) ? '' : $current_reviewer ); ?>" />
	</p>

	<!-- Penulis Buku Dual-Mode Dropdown -->
	<p>
		<label for="_ss_book_author_select"><strong><?php esc_html_e( 'Penulis Buku', 'sukusastra' ); ?></strong></label><br>
		<select class="widefat" id="_ss_book_author_select" name="_ss_book_author_select" onchange="toggleBookAuthorInput(this.value)">
			<option value="" <?php selected( ! $is_numeric_author ); ?>><?php esc_html_e( '— Tulis Manual di Bawah —', 'sukusastra' ); ?></option>
			<optgroup label="<?php esc_attr_e( 'Pilih dari Penulis CPT', 'sukusastra' ); ?>">
				<?php foreach ( $penulis_posts as $penulis ) : ?>
					<option value="<?php echo esc_attr( $penulis->ID ); ?>" <?php selected( $current_book_author, (string) $penulis->ID ); ?>>
						<?php echo esc_html( $penulis->post_title ); ?>
					</option>
				<?php endforeach; ?>
			</optgroup>
		</select>
	</p>
	<p id="ss-book-author-text-wrapper" style="<?php echo $is_numeric_author ? 'display:none;' : ''; ?>">
		<label for="_ss_book_author"><strong><?php esc_html_e( 'Nama Penulis Buku (Manual)', 'sukusastra' ); ?></strong></label><br>
		<input type="text" class="widefat" id="_ss_book_author" name="_ss_book_author" value="<?php echo esc_attr( $is_numeric_author ? '' : $current_book_author ); ?>" />
	</p>

	<script>
	function toggleReviewerInput(val) {
		var wrapper = document.getElementById('ss-reviewer-text-wrapper');
		var textInput = document.getElementById('_ss_reviewer');
		if (val === '') {
			wrapper.style.display = 'block';
		} else {
			wrapper.style.display = 'none';
			textInput.value = val;
		}
	}
	function toggleBookAuthorInput(val) {
		var wrapper = document.getElementById('ss-book-author-text-wrapper');
		var textInput = document.getElementById('_ss_book_author');
		if (val === '') {
			wrapper.style.display = 'block';
		} else {
			wrapper.style.display = 'none';
			textInput.value = val;
		}
	}
	jQuery(document).ready(function($) {
		$('#post').on('submit', function() {
			var reviewerSelect = $('#_ss_reviewer_select').val();
			if (reviewerSelect !== '') {
				$('#_ss_reviewer').val(reviewerSelect);
			}
			var authorSelect = $('#_ss_book_author_select').val();
			if (authorSelect !== '') {
				$('#_ss_book_author').val(authorSelect);
			}
		});
	});
	</script>

	<?php
	sukusastra_render_text_fields(
		$post->ID,
		array(
			'_ss_book_title'        => __( 'Judul Buku', 'sukusastra' ),
			'_ss_book_publisher'    => __( 'Penerbit', 'sukusastra' ),
			'_ss_book_year'         => __( 'Tahun Terbit', 'sukusastra' ),
			'_ss_book_edition'      => __( 'Cetakan (misal: Cetakan I, Agustus 2017)', 'sukusastra' ),
			'_ss_book_pages'        => __( 'Halaman (Jumlah Halaman)', 'sukusastra' ),
			'_ss_book_cover_type'   => array(
				'label'       => __( 'Jenis Cover', 'sukusastra' ),
				'placeholder' => __( 'Softcover / Hardcover', 'sukusastra' ),
			),
			'_ss_book_dimensions'   => array(
				'label'       => __( 'Dimensi', 'sukusastra' ),
				'placeholder' => __( '13.5 x 20 cm', 'sukusastra' ),
			),
			'_ss_book_paper'        => array(
				'label'       => __( 'Jenis Kertas', 'sukusastra' ),
				'placeholder' => __( 'Bookpaper 72 gsm', 'sukusastra' ),
			),
			'_ss_book_isbn'         => array(
				'label'       => __( 'ISBN', 'sukusastra' ),
				'placeholder' => __( '978-602-xxxx-xx-x', 'sukusastra' ),
			),
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
	$gallery_ids = sukusastra_get_meta( $post->ID, '_ss_event_gallery', '' );
	$gallery_array = $gallery_ids ? explode( ',', $gallery_ids ) : array();
	?>
	<div class="ss-event-gallery-uploader-wrapper" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
		<label><strong><?php esc_html_e( 'Galeri Poster / Pamflet Acara (Multi-Gambar)', 'sukusastra' ); ?></strong></label>
		<p class="description" style="margin-top: 4px;"><?php esc_html_e( 'Pilih satu atau lebih gambar jika ingin menampilkan slider/carousel poster. Best Practice: Rasio 4:5 atau 1:1 (Rekomendasi: 800x1000 px atau 800x800 px) dengan ukuran maks. 1MB per berkas.', 'sukusastra' ); ?></p>
		<div style="margin-top: 10px;">
			<div id="ss-gallery-preview" style="min-height: 60px; border: 1px dashed #ccc; padding: 10px; background: #fafafa; margin-bottom: 10px;">
				<?php if ( ! empty( $gallery_array ) ) : ?>
					<?php foreach ( $gallery_array as $img_id ) : ?>
						<?php 
						$img_url = wp_get_attachment_image_url( (int) $img_id, 'thumbnail' ); 
						if ( $img_url ) :
							?>
							<div style="margin: 5px; border: 1px solid #ddd; padding: 2px; background: #fff; display: inline-block;">
								<img src="<?php echo esc_url( $img_url ); ?>" style="width: 50px; height: 50px; object-fit: cover; display: block;" />
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php else : ?>
					<span style="color: #999; font-size: 12px;"><?php esc_html_e( 'Belum ada gambar galeri', 'sukusastra' ); ?></span>
				<?php endif; ?>
			</div>
			<input type="hidden" name="_ss_event_gallery" id="ss_event_gallery" value="<?php echo esc_attr( $gallery_ids ); ?>">
			<button type="button" class="button" id="ss-upload-gallery-btn"><?php esc_html_e( 'Pilih Gambar', 'sukusastra' ); ?></button>
			<button type="button" class="button button-link-delete" id="ss-remove-gallery-btn" style="<?php echo $gallery_ids ? '' : 'display: none;'; ?>"><?php esc_html_e( 'Hapus', 'sukusastra' ); ?></button>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var gallery_frame;
		$('#ss-upload-gallery-btn').on('click', function(e) {
			e.preventDefault();
			if (gallery_frame) {
				gallery_frame.open();
				return;
			}
			gallery_frame = wp.media.frames.gallery_frame = wp.media({
				title: '<?php echo esc_js( __( 'Pilih Gambar Galeri', 'sukusastra' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'Gunakan Gambar ini', 'sukusastra' ) ); ?>'
				},
				multiple: true
			});
			gallery_frame.on('select', function() {
				var selection = gallery_frame.state().get('selection');
				var ids = [];
				var previewHtml = '';
				selection.map(function(attachment) {
					attachment = attachment.toJSON();
					ids.push(attachment.id);
					var previewUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
					previewHtml += '<div style="margin: 5px; border: 1px solid #ddd; padding: 2px; background: #fff; display: inline-block;"><img src="' + previewUrl + '" style="width: 50px; height: 50px; object-fit: cover; display: block;" /></div>';
				});
				$('#ss_event_gallery').val(ids.join(','));
				$('#ss-gallery-preview').html(previewHtml);
				$('#ss-remove-gallery-btn').show();
			});
			gallery_frame.open();
		});

		$('#ss-remove-gallery-btn').on('click', function(e) {
			e.preventDefault();
			$('#ss_event_gallery').val('');
			$('#ss-gallery-preview').html('<span style="color: #999; font-size: 12px;"><?php echo esc_js( __( 'Belum ada gambar galeri', 'sukusastra' ) ); ?></span>');
			$(this).hide();
		});
	});
	</script>
	<?php
	$event_type = sukusastra_get_meta( $post->ID, '_ss_event_type', 'acara' );
	?>
	<p>
		<label for="_ss_event_type"><strong><?php esc_html_e( 'Tipe Event', 'sukusastra' ); ?></strong></label><br>
		<select class="widefat" id="_ss_event_type" name="_ss_event_type" style="margin-bottom: 10px;">
			<option value="acara" <?php echo sukusastra_selected( $event_type, 'acara' ); ?>><?php esc_html_e( 'Acara / Agenda (Seminar, Workshop, Bookfair, dll.)', 'sukusastra' ); ?></option>
			<option value="sayembara" <?php echo sukusastra_selected( $event_type, 'sayembara' ); ?>><?php esc_html_e( 'Sayembara Menulis (Lomba)', 'sukusastra' ); ?></option>
		</select>
	</p>
	<?php
	sukusastra_render_text_fields(
		$post->ID,
		array(
			'_ss_event_start'      => __( 'Tanggal Mulai (YYYY-MM-DD)', 'sukusastra' ),
			'_ss_event_end'        => __( 'Tanggal Selesai / Deadline (YYYY-MM-DD)', 'sukusastra' ),
			'_ss_event_location'   => __( 'Lokasi / Format (untuk Acara)', 'sukusastra' ),
			'_ss_event_prize'      => __( 'Total Hadiah (untuk Sayembara, contoh: Rp 10.000.000)', 'sukusastra' ),
			'_ss_event_fee'        => __( 'Biaya Pendaftaran (untuk Sayembara, contoh: Gratis / Rp 50.000)', 'sukusastra' ),
			'_ss_booking_label'    => __( 'Label Button (Contoh: Beli Tiket / Kirim Naskah / Unduh Panduan)', 'sukusastra' ),
			'_ss_booking_url'      => __( 'URL Booking / Link Formulir Pendaftaran', 'sukusastra' ),
			'_ss_contact_url'      => __( 'URL Kontak CP/WhatsApp', 'sukusastra' ),
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

function sukusastra_render_related_post_metabox( WP_Post $post ): void {
	wp_nonce_field( 'sukusastra_save_related_meta', 'sukusastra_related_meta_nonce' );
	$selected_ids_str = sukusastra_get_meta( $post->ID, '_ss_related_post_id', '' );
	$selected_ids = array_filter( array_map( 'intval', explode( ',', $selected_ids_str ) ) );
	?>
	<div class="ss-related-search-wrapper" style="margin-bottom: 10px;">
		<label><strong><?php esc_html_e( 'Pilih Artikel Terkait (Manual - Bisa Lebih dari 1)', 'sukusastra' ); ?></strong></label>
		
		<!-- Show currently selected posts list -->
		<div id="ss-related-selected-list" style="margin-top: 8px; margin-bottom: 12px; display: grid; gap: 4px;">
			<?php 
			foreach ( $selected_ids as $id ) {
				$item_post = get_post( $id );
				if ( $item_post ) {
					$item_title = sprintf( '[%1$s] %2$s', esc_html( sukusastra_get_post_type_label( $item_post->ID ) ), esc_html( $item_post->post_title ) );
					?>
					<div class="ss-related-selected-item" data-id="<?php echo esc_attr( (string) $id ); ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border: 1px solid #ccc; background: #fafafa; border-radius: 4px;">
						<span style="font-weight: bold; font-size: 12px;"><?php echo esc_html( $item_title ); ?></span>
						<button type="button" class="button button-small ss-related-remove-item-btn" style="margin-left: 10px;"><?php esc_html_e( 'Hapus', 'sukusastra' ); ?></button>
					</div>
					<?php
				}
			}
			?>
		</div>

		<!-- Hidden field to store actual post IDs comma-separated -->
		<input type="hidden" name="_ss_related_post_id" id="ss_related_post_id" value="<?php echo esc_attr( $selected_ids_str ); ?>">

		<!-- Search Input -->
		<div id="ss-related-search-container" style="margin-top: 8px;">
			<input type="text" id="ss_related_search" placeholder="<?php esc_attr_e( 'Ketik judul artikel untuk mencari dan menambahkan...', 'sukusastra' ); ?>" class="widefat" autocomplete="off" style="height: 36px;">
			<div id="ss-related-search-results" style="border: 1px solid #ddd; border-top: none; max-height: 200px; overflow-y: auto; background: #fff; display: none; border-radius: 0 0 4px 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); z-index: 9999; position: relative;"></div>
		</div>
		
		<p class="description" style="margin-top: 6px;">
			<?php esc_html_e( 'Cari dan klik artikel di atas untuk menambahkan ke list artikel terkait. Jika daftar kosong, sistem akan otomatis menampilkan artikel terbaru dari kategori yang sama.', 'sukusastra' ); ?>
		</p>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var searchInput = $('#ss_related_search');
		var resultsBox = $('#ss-related-search-results');
		var selectedList = $('#ss-related-selected-list');
		var hiddenInput = $('#ss_related_post_id');
		var currentPostId = <?php echo (int) $post->ID; ?>;
		var timer;

		function getSelectedIds() {
			var val = hiddenInput.val().trim();
			if (!val) return [];
			return val.split(',').map(Number).filter(Boolean);
		}

		function updateHiddenInput(ids) {
			hiddenInput.val(ids.join(','));
		}

		searchInput.on('keyup input', function() {
			clearTimeout(timer);
			var query = $(this).val().trim();
			if (query.length < 2) {
				resultsBox.hide().empty();
				return;
			}

			timer = setTimeout(function() {
				resultsBox.html('<div style="padding: 10px; color: #666; font-size:12px;"><?php echo esc_js( __( 'Mencari...', 'sukusastra' ) ); ?></div>').show();
				
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'sukusastra_search_related_posts',
						q: query,
						exclude: currentPostId
					},
					success: function(response) {
						if (response.success && response.data.length > 0) {
							resultsBox.empty();
							$.each(response.data, function(i, item) {
								var row = $('<div style="padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; transition: background 0.2s; font-size:13px;" class="ss-search-item"></div>');
								row.html('<strong>[' + item.type + ']</strong> ' + item.title);
								row.hover(
									function() { $(this).css('background', '#f0f0f0'); },
									function() { $(this).css('background', '#fff'); }
								);
								row.on('click', function() {
									var ids = getSelectedIds();
									if (ids.indexOf(item.id) !== -1) {
										alert('<?php echo esc_js( __( 'Artikel ini sudah ada di dalam daftar.', 'sukusastra' ) ); ?>');
										return;
									}
									
									ids.push(item.id);
									updateHiddenInput(ids);

									var displayTitle = '[' + item.type + '] ' + item.title;
									var itemHtml = $('<div class="ss-related-selected-item" data-id="' + item.id + '" style="display: flex; align-items: center; justify-content: space-between; padding: 6px 10px; border: 1px solid #ccc; background: #fafafa; border-radius: 4px;">' +
										'<span style="font-weight: bold; font-size: 12px;">' + displayTitle + '</span>' +
										'<button type="button" class="button button-small ss-related-remove-item-btn" style="margin-left: 10px;"><?php echo esc_js( __( 'Hapus', 'sukusastra' ) ); ?></button>' +
									'</div>');
									
									selectedList.append(itemHtml);
									resultsBox.hide().empty();
									searchInput.val('');
								});
								resultsBox.append(row);
							});
						} else {
							resultsBox.html('<div style="padding: 10px; color: #999; font-size:12px;"><?php echo esc_js( __( 'Tidak ada hasil ditemukan', 'sukusastra' ) ); ?></div>');
						}
					},
					error: function() {
						resultsBox.html('<div style="padding: 10px; color: #a00; font-size:12px;"><?php echo esc_js( __( 'Error saat memuat data', 'sukusastra' ) ); ?></div>');
					}
				});
			}, 300);
		});

		// Remove button handler
		selectedList.on('click', '.ss-related-remove-item-btn', function(e) {
			e.preventDefault();
			var item = $(this).closest('.ss-related-selected-item');
			var itemId = Number(item.attr('data-id'));
			var ids = getSelectedIds();
			var index = ids.indexOf(itemId);
			
			if (index !== -1) {
				ids.splice(index, 1);
				updateHiddenInput(ids);
			}
			item.remove();
		});

		// Hide results list when clicking outside
		$(document).on('click', function(e) {
			if (!$(e.target).closest('.ss-related-search-wrapper').length) {
				resultsBox.hide();
			}
		});
	});
	</script>
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
			'_ss_book_edition',
			'_ss_book_pages',
			'_ss_book_type',
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
			'_ss_youtube_url',
			'_ss_event_start',
			'_ss_event_end',
			'_ss_event_location',
			'_ss_booking_label',
			'_ss_booking_url',
			'_ss_contact_url',
			'_ss_event_type',
			'_ss_event_prize',
			'_ss_event_fee',
			'_ss_event_rules_url',
			'_ss_event_gallery',
			'_ss_book_price',
			'_ss_book_whatsapp',
			'_ss_book_translator',
			'_ss_book_isbn',
			'_ss_book_dimensions',
			'_ss_book_cover_type',
			'_ss_terbitan_gallery',
			'_ss_book_original_title',
			'_ss_book_translator_en',
			'_ss_book_city',
			'_ss_book_paper',
			'_ss_book_editor',
			'_ss_book_proofreader',
			'_ss_book_layout',
			'_ss_book_cover_design',
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

	// 4. Save related post linkage (if nonce exists)
	if ( isset( $_POST['sukusastra_related_meta_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sukusastra_related_meta_nonce'] ) ), 'sukusastra_save_related_meta' ) ) {
		$related_id = isset( $_POST['_ss_related_post_id'] ) ? sanitize_text_field( wp_unslash( $_POST['_ss_related_post_id'] ) ) : '';
		update_post_meta( $post_id, '_ss_related_post_id', $related_id );
	}
}

add_action( 'wp_ajax_sukusastra_search_related_posts', 'wp_ajax_sukusastra_search_related_posts_callback' );
function wp_ajax_sukusastra_search_related_posts_callback(): void {
	// Verify user permissions
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( 'Unauthorized' );
	}

	$query = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';
	$exclude = isset( $_POST['exclude'] ) ? (int) $_POST['exclude'] : 0;

	if ( strlen( $query ) < 2 ) {
		wp_send_json_success( array() );
	}

	$args = array(
		'post_type'      => array( 'post', 'review_buku', 'berita' ),
		'posts_per_page' => 10,
		's'              => $query,
		'post_status'    => 'publish',
		'orderby'        => 'relevance',
	);

	if ( $exclude > 0 ) {
		$args['post__not_in'] = array( $exclude );
	}

	$posts = get_posts( $args );
	$data = array();

	foreach ( $posts as $p ) {
		$data[] = array(
			'id'    => $p->ID,
			'title' => esc_html( $p->post_title ),
			'type'  => esc_html( sukusastra_get_post_type_label( $p->ID ) ),
		);
	}

	wp_send_json_success( $data );
}

function sukusastra_render_terbitan_metabox( WP_Post $post ): void {
	$book_image_id = sukusastra_get_meta( $post->ID, '_ss_book_image_id', '' );
	$image_url     = '';
	if ( $book_image_id ) {
		$image_url = wp_get_attachment_image_url( (int) $book_image_id, 'medium' );
	}
	?>
	<div class="ss-book-image-uploader-wrapper" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
		<label><strong><?php esc_html_e( 'Foto/Sampul Buku (Portrait)', 'sukusastra' ); ?></strong></label>
		<p class="description" style="margin-top: 4px;"><?php esc_html_e( 'Upload sampul depan buku di sini. Best Practice: Portrait, rasio 3:4 (Rekomendasi: 600x800 px atau 900x1200 px) dengan ukuran maks. 1MB.', 'sukusastra' ); ?></p>
		<div style="margin-top: 10px;">
			<div id="ss-terbitan-cover-preview" style="max-width: 150px; min-height: 100px; border: 1px dashed #ccc; padding: 5px; background: #fafafa; display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
				<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url ); ?>" style="max-width: 100%; height: auto; display: block;" />
				<?php else : ?>
					<span style="color: #999; font-size: 12px;"><?php esc_html_e( 'Belum ada gambar', 'sukusastra' ); ?></span>
				<?php endif; ?>
			</div>
			<input type="hidden" name="_ss_book_image_id" id="ss_terbitan_cover_id" value="<?php echo esc_attr( $book_image_id ); ?>">
			<button type="button" class="button" id="ss-upload-terbitan-cover-btn"><?php esc_html_e( 'Pilih Sampul', 'sukusastra' ); ?></button>
			<button type="button" class="button button-link-delete" id="ss-remove-terbitan-cover-btn" style="<?php echo $book_image_id ? '' : 'display: none;'; ?>"><?php esc_html_e( 'Hapus', 'sukusastra' ); ?></button>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var cover_frame;
		$('#ss-upload-terbitan-cover-btn').on('click', function(e) {
			e.preventDefault();
			if (cover_frame) {
				cover_frame.open();
				return;
			}
			cover_frame = wp.media.frames.cover_frame = wp.media({
				title: '<?php echo esc_js( __( 'Pilih Sampul Buku', 'sukusastra' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'Gunakan Sampul ini', 'sukusastra' ) ); ?>'
				},
				multiple: false
			});
			cover_frame.on('select', function() {
				var attachment = cover_frame.state().get('selection').first().toJSON();
				$('#ss_terbitan_cover_id').val(attachment.id);
				
				var previewUrl = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
				$('#ss-terbitan-cover-preview').html('<img src="' + previewUrl + '" style="max-width: 100%; height: auto; display: block;" />');
				$('#ss-remove-terbitan-cover-btn').show();
			});
			cover_frame.open();
		});

		$('#ss-remove-terbitan-cover-btn').on('click', function(e) {
			e.preventDefault();
			$('#ss_terbitan_cover_id').val('');
			$('#ss-terbitan-cover-preview').html('<span style="color: #999; font-size: 12px;"><?php echo esc_js( __( 'Belum ada gambar', 'sukusastra' ) ); ?></span>');
			$(this).hide();
		});
	});
	</script>

	<!-- Multi-image gallery uploader for terbitan details -->
	<?php
	$gallery_ids = sukusastra_get_meta( $post->ID, '_ss_terbitan_gallery', '' );
	$gallery_array = $gallery_ids ? explode( ',', $gallery_ids ) : array();
	?>
	<div class="ss-terbitan-gallery-uploader-wrapper" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
		<label><strong><?php esc_html_e( 'Galeri Buku / Foto Tambahan (Multi-Gambar)', 'sukusastra' ); ?></strong></label>
		<p class="description" style="margin-top: 4px;"><?php esc_html_e( 'Pilih satu atau lebih gambar tambahan untuk ditampilkan di slider halaman produk. Best Practice: Rasio 3:4 atau 1:1 (Rekomendasi: 600x800 px atau 800x800 px) dengan ukuran maks. 1MB per berkas.', 'sukusastra' ); ?></p>
		<div style="margin-top: 10px;">
			<div id="ss-terbitan-gallery-preview" style="min-height: 60px; border: 1px dashed #ccc; padding: 10px; background: #fafafa; margin-bottom: 10px;">
				<?php if ( ! empty( $gallery_array ) ) : ?>
					<?php foreach ( $gallery_array as $img_id ) : ?>
						<?php 
						$img_url = wp_get_attachment_image_url( (int) $img_id, 'thumbnail' ); 
						if ( $img_url ) :
							?>
							<div style="margin: 5px; border: 1px solid #ddd; padding: 2px; background: #fff; display: inline-block;">
								<img src="<?php echo esc_url( $img_url ); ?>" style="width: 50px; height: 50px; object-fit: cover; display: block;" />
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php else : ?>
					<span style="color: #999; font-size: 12px;"><?php esc_html_e( 'Belum ada gambar galeri', 'sukusastra' ); ?></span>
				<?php endif; ?>
			</div>
			<input type="hidden" name="_ss_terbitan_gallery" id="ss_terbitan_gallery" value="<?php echo esc_attr( $gallery_ids ); ?>">
			<button type="button" class="button" id="ss-upload-terbitan-gallery-btn"><?php esc_html_e( 'Pilih Gambar', 'sukusastra' ); ?></button>
			<button type="button" class="button button-link-delete" id="ss-remove-terbitan-gallery-btn" style="<?php echo $gallery_ids ? '' : 'display: none;'; ?>"><?php esc_html_e( 'Hapus', 'sukusastra' ); ?></button>
		</div>
	</div>

	<script>
	jQuery(document).ready(function($) {
		var terbitan_gallery_frame;
		$('#ss-upload-terbitan-gallery-btn').on('click', function(e) {
			e.preventDefault();
			if (terbitan_gallery_frame) {
				terbitan_gallery_frame.open();
				return;
			}
			terbitan_gallery_frame = wp.media.frames.terbitan_gallery_frame = wp.media({
				title: '<?php echo esc_js( __( 'Pilih Gambar Galeri Terbitan', 'sukusastra' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'Gunakan Gambar ini', 'sukusastra' ) ); ?>'
				},
				multiple: true
			});
			terbitan_gallery_frame.on('select', function() {
				var selection = terbitan_gallery_frame.state().get('selection');
				var ids = [];
				var previewHtml = '';
				selection.map(function(attachment) {
					attachment = attachment.toJSON();
					ids.push(attachment.id);
					var previewUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
					previewHtml += '<div style="margin: 5px; border: 1px solid #ddd; padding: 2px; background: #fff; display: inline-block;"><img src="' + previewUrl + '" style="width: 50px; height: 50px; object-fit: cover; display: block;" /></div>';
				});
				$('#ss_terbitan_gallery').val(ids.join(','));
				$('#ss-terbitan-gallery-preview').html(previewHtml);
				$('#ss-remove-terbitan-gallery-btn').show();
			});
			terbitan_gallery_frame.open();
		});

		$('#ss-remove-terbitan-gallery-btn').on('click', function(e) {
			e.preventDefault();
			$('#ss_terbitan_gallery').val('');
			$('#ss-terbitan-gallery-preview').html('<span style="color: #999; font-size: 12px;"><?php echo esc_js( __( 'Belum ada gambar galeri', 'sukusastra' ) ); ?></span>');
			$(this).hide();
		});
	});
	</script>

	<?php
	// Text fields mapping
	sukusastra_render_text_fields(
		$post->ID,
		array(
			'_ss_book_price'       => __( 'Harga Buku (contoh: Rp 55.000)', 'sukusastra' ),
			'_ss_book_whatsapp'    => array(
				'label'       => __( 'WhatsApp Order (Nomor HP/Link)', 'sukusastra' ),
				'placeholder' => __( '628388966273 atau link wa.me', 'sukusastra' ),
			),
			'_ss_book_author'      => __( 'Penulis Buku', 'sukusastra' ),
			'_ss_book_original_title' => __( 'Judul Asli (Original Title)', 'sukusastra' ),
			'_ss_book_translator'  => __( 'Penerjemah', 'sukusastra' ),
			'_ss_book_translator_en' => __( 'Penerjemah Edisi Inggris (jika ada)', 'sukusastra' ),
			'_ss_book_publisher'   => array(
				'label'       => __( 'Penerbit', 'sukusastra' ),
				'placeholder' => 'Yayasan Komunitas Sastra Suku Sastra',
			),
			'_ss_book_city'        => __( 'Kota Terbit (contoh: Yogyakarta, Indonesia)', 'sukusastra' ),
			'_ss_book_year'        => __( 'Tahun Terbit', 'sukusastra' ),
			'_ss_book_edition'     => __( 'Cetakan (contoh: Pertama, Februari 2026)', 'sukusastra' ),
			'_ss_book_pages'       => __( 'Jumlah Halaman', 'sukusastra' ),
			'_ss_book_isbn'        => __( 'ISBN / Nomor Terbit', 'sukusastra' ),
			'_ss_book_dimensions'  => __( 'Dimensi (contoh: 13,5 x 19,5 cm)', 'sukusastra' ),
			'_ss_book_cover_type'  => __( 'Jenis Jilid (contoh: Softcover / Hardcover)', 'sukusastra' ),
			'_ss_book_paper'       => __( 'Kertas (contoh: Bookpaper 72 gsm)', 'sukusastra' ),
			'_ss_book_editor'      => __( 'Penyunting', 'sukusastra' ),
			'_ss_book_proofreader' => __( 'Proofreader', 'sukusastra' ),
			'_ss_book_layout'      => __( 'Penata Letak', 'sukusastra' ),
			'_ss_book_cover_design'=> __( 'Desain Sampul', 'sukusastra' ),
		)
	);
}


