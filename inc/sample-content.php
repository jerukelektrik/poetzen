<?php
/**
 * Sample Content Generator for Suku Sastra.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_notices', 'sukusastra_sample_content_notice' );
function sukusastra_sample_content_notice(): void {
	if ( get_option( 'sukusastra_samples_generated' ) ) {
		return;
	}

	if ( isset( $_GET['generate_suku_samples'] ) && '1' === $_GET['generate_suku_samples'] ) {
		sukusastra_generate_samples();
		update_option( 'sukusastra_samples_generated', 1 );
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Konten sampel Suku Sastra berhasil dibuat!', 'sukusastra' ) . '</p></div>';
		return;
	}

	$url = add_query_arg( 'generate_suku_samples', '1' );
	?>
	<div class="notice notice-info">
		<p>
			<strong><?php esc_html_e( 'Revamp Suku Sastra', 'sukusastra' ); ?></strong>: 
			<?php esc_html_e( 'Klik tombol di bawah ini untuk membuat konten sampel (Puisi, Cerpen, Esai, Review Buku, Berita, Event) beserta kategori penulis secara otomatis.', 'sukusastra' ); ?>
		</p>
		<p>
			<a href="<?php echo esc_url( $url ); ?>" class="button button-primary"><?php esc_html_e( 'Buat Konten Sampel', 'sukusastra' ); ?></a>
		</p>
	</div>
	<?php
}

function sukusastra_generate_samples(): void {
	// Create base categories
	$categories = array(
		'Puisi'      => 'puisi',
		'Cerpen'     => 'cerpen',
		'Esai'       => 'esai',
		'Ruang Baca' => 'ruang-baca',
	);
	$cat_ids = array();
	foreach ( $categories as $name => $slug ) {
		$term = get_category_by_slug( $slug );
		if ( ! $term ) {
			$inserted = wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
			$cat_ids[ $slug ] = is_array( $inserted ) ? $inserted['term_id'] : $inserted;
		} else {
			$cat_ids[ $slug ] = $term->term_id;
		}
	}

	// Create original authors as 'penulis' CPT posts
	$authors_data = array(
		'sapardi' => array(
			'title'       => 'Sapardi Djoko Damono',
			'birth_place' => 'Surakarta',
			'birth_date'  => '20 Maret 1940',
			'bio_summary' => 'Sastrawan terkemuka Indonesia yang dikenal lewat puisi-puisinya yang bersahaja namun sarat makna mendalam seperti Hujan Bulan Juni.',
			'content'     => '<p>Prof. Dr. Sapardi Djoko Damono adalah seorang pujangga berkebangsaan Indonesia yang kerap dijuluki sebagai pelopor puisi lirik di Indonesia. Karya-karyanya sangat populer dan digemari lintas generasi.</p>',
		),
		'chairil' => array(
			'title'       => 'Chairil Anwar',
			'birth_place' => 'Medan',
			'birth_date'  => '26 Juli 1922',
			'bio_summary' => 'Penyair legendaris pelopor Angkatan \'45 dalam kesusastraan Indonesia yang terkenal dengan julukan "Si Binatang Jalang".',
			'content'     => '<p>Chairil Anwar adalah penyair terkemuka Indonesia. Bersama Asrul Sani dan Rivai Apin, ia memelopori era puisi modern Indonesia. Chairil meninggal di usia muda namun mewariskan puisi-puisi abadi seperti Aku, Karawang-Bekasi, dan Deru Campur Debu.</p>',
		),
		'leila' => array(
			'title'       => 'Leila S. Chudori',
			'birth_place' => 'Jakarta',
			'birth_date'  => '12 Desember 1962',
			'bio_summary' => 'Penulis novel, cerpen, dan skenario drama Indonesia yang dikenal lewat karya monumental seperti Laut Bercerita dan Pulang.',
			'content'     => '<p>Leila Salikha Chudori adalah penulis dan wartawan berkebangsaan Indonesia. Novelnya, Laut Bercerita, memenangkan Penghargaan Sastra Asia Tenggara (S.E.A. Write Award) dan diadaptasi menjadi film pendek.</p>',
		)
	);

	$author_ids = array();
	foreach ( $authors_data as $key => $data ) {
		$existing = get_posts(
			array(
				'post_type'   => 'penulis',
				'title'       => $data['title'],
				'post_status' => 'publish',
				'numberposts' => 1,
			)
		);
		if ( ! empty( $existing ) ) {
			$author_ids[ $key ] = $existing[0]->ID;
		} else {
			$id = wp_insert_post(
				array(
					'post_title'   => $data['title'],
					'post_content' => $data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'penulis',
				)
			);
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_ss_penulis_tempat_lahir', $data['birth_place'] );
				update_post_meta( $id, '_ss_penulis_tanggal_lahir', $data['birth_date'] );
				update_post_meta( $id, '_ss_penulis_bio_summary', $data['bio_summary'] );
				$author_ids[ $key ] = $id;
			}
		}
	}

	// Create Tags
	$tags = array( 'Menulis', 'Diskusi', 'Sastra Nusantara' );
	foreach ( $tags as $tag ) {
		if ( ! term_exists( $tag, 'post_tag' ) ) {
			wp_insert_term( $tag, 'post_tag' );
		}
	}

	// 1. Sample Puisi by Sapardi Djoko Damono
	$puisi_id = wp_insert_post(
		array(
			'post_title'    => 'Melihat Hujan di Bulan Juni',
			'post_content'  => '<p>Hujan di bulan Juni jatuh perlahan di atas daun-daun kering. Tak ada yang lebih tabah selain rintik yang menolak reda, menyimpan rahasia rindu yang paling dalam kepada bumi yang gersang.</p><p>Kita berdiri di bawah atap seng yang sama, mendengarkan simfoni alam yang bernada sendu. Air mengalir membasahi ingatan-ingatan yang coba kita kubur dalam-dalam.</p>',
			'post_excerpt'  => 'Hujan di bulan Juni jatuh perlahan di atas daun-daun kering. Tak ada yang lebih tabah selain rintik yang menolak reda.',
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_category' => array( $cat_ids['puisi'] ),
		)
	);
	if ( $puisi_id && ! is_wp_error( $puisi_id ) ) {
		update_post_meta( $puisi_id, '_ss_show_home', '1' );
		update_post_meta( $puisi_id, '_ss_is_seo_article', '0' );
		update_post_meta( $puisi_id, '_ss_seo_title', 'Puisi: Melihat Hujan di Bulan Juni | Suku Sastra' );
		update_post_meta( $puisi_id, '_ss_meta_desc', 'Puisi tentang ketabahan hujan di bulan Juni oleh Sapardi Djoko Damono.' );
		update_post_meta( $puisi_id, '_ss_post_views', 420 );
		if ( isset( $author_ids['sapardi'] ) ) {
			update_post_meta( $puisi_id, '_ss_original_author_id', $author_ids['sapardi'] );
		}
	}

	// 2. Sample Cerpen by Chairil Anwar (for demonstration)
	$cerpen_id = wp_insert_post(
		array(
			'post_title'    => 'Lelaki yang Menggambar Angin',
			'post_content'  => '<p>Sejak kecil, ia tidak pernah menggambar gunung, pohon, atau rumah seperti anak-anak lainnya. Di atas kertas gambarnya yang putih, ia hanya menarik garis-garis meliuk dan berputar menggunakan krayon kelabu.</p><p>"Ini apa?" tanya gurunya suatu hari.</p><p>"Ini angin, Bu. Ia sedang menari di atas atap sekolah kita," jawabnya polos.</p><p>Ketika dewasa, ia memutuskan menjadi pelukis angin profesional. Orang-orang menganggapnya gila sampai suatu ketika lukisannya terjual dengan harga miliaran rupiah kepada seorang kolektor asing yang mengklaim bisa merasakan sejuknya embusan udara saat menatap kanvasnya.</p>',
			'post_excerpt'  => 'Sejak kecil, ia tidak menggambar rumah or gunung. Di atas kertas putihnya, ia hanya menarik garis-garis meliuk kelabu menggambar angin.',
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_category' => array( $cat_ids['cerpen'] ),
		)
	);
	if ( $cerpen_id && ! is_wp_error( $cerpen_id ) ) {
		update_post_meta( $cerpen_id, '_ss_show_home', '1' );
		update_post_meta( $cerpen_id, '_ss_is_seo_article', '0' );
		update_post_meta( $cerpen_id, '_ss_post_views', 150 );
		update_post_meta( $cerpen_id, '_ss_pesan_moral', 'Jangan pernah membatasi impian dan kreativitasmu hanya karena orang lain tidak memahaminya. Terkadang, hal paling tak terlihat seperti angin adalah hal yang paling berharga jika diekspresikan dengan keyakinan penuh.' );
		if ( isset( $author_ids['chairil'] ) ) {
			update_post_meta( $cerpen_id, '_ss_original_author_id', $author_ids['chairil'] );
		}
	}

	// 3. Sample Esai (Uploader only, no original author assigned to test fallbacks)
	$esai_id = wp_insert_post(
		array(
			'post_title'    => 'Estetika Sunyi dalam Sastra Modern',
			'post_content'  => '<p>Sastra hari ini seringkali terjebak dalam hiruk-pikuk kecepatan informasi. Narasi-narasi dibangun di atas kegaduhan opini dan tren media sosial. Namun, di sudut yang sunyi, karya sastra yang mengendap perlahan justru menawarkan kekuatan refleksi yang lebih mendalam.</p>',
			'post_excerpt'  => 'Melihat bagaimana estetika kesunyian kembali menemukan relevansinya di tengah kegaduhan informasi era digital.',
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_category' => array( $cat_ids['esai'] ),
		)
	);
	if ( $esai_id && ! is_wp_error( $esai_id ) ) {
		update_post_meta( $esai_id, '_ss_show_home', '1' );
		update_post_meta( $esai_id, '_ss_is_seo_article', '0' );
		update_post_meta( $esai_id, '_ss_post_views', 80 );
	}

	// 4. Sample Ruang Baca (SEO article)
	$seo_id = wp_insert_post(
		array(
			'post_title'    => 'Panduan Menulis Puisi untuk Pemula',
			'post_content'  => '<p>Menulis puisi tidaklah sesulit yang dibayangkan. Langkah pertama adalah melatih kepekaan pancaindra kita terhadap sekitar. Tulislah apa yang Anda lihat, dengar, dan rasakan tanpa terbebani oleh rima terlebih dahulu.</p>',
			'post_excerpt'  => 'Panduan praktis langkah demi langkah menulis puisi bagi pemula agar menghasilkan diksi yang segar.',
			'post_status'   => 'publish',
			'post_type'     => 'post',
			'post_category' => array( $cat_ids['ruang-baca'] ),
			'tags_input'    => array( 'Menulis' ),
		)
	);
	if ( $seo_id && ! is_wp_error( $seo_id ) ) {
		update_post_meta( $seo_id, '_ss_show_home', '0' );
		update_post_meta( $seo_id, '_ss_is_seo_article', '1' );
		update_post_meta( $seo_id, '_ss_post_views', 5 );
	}

	// 5. Sample Review Buku CPT linked to Leila S. Chudori
	$review_id = wp_insert_post(
		array(
			'post_title'   => 'Mengurai Luka Sejarah dalam Laut Bercerita',
			'post_content' => '<p>Novel Laut Bercerita karya Leila S. Chudori bukan sekadar kisah fiksi, melainkan sebuah rekaman emosional atas tragedi penculikan aktivis 1998.</p>',
			'post_excerpt' => 'Review novel sejarah Laut Bercerita karya Leila S. Chudori.',
			'post_status'  => 'publish',
			'post_type'    => 'review_buku',
		)
	);
	if ( $review_id && ! is_wp_error( $review_id ) ) {
		update_post_meta( $review_id, '_ss_book_title', 'Laut Bercerita' );
		update_post_meta( $review_id, '_ss_book_author', 'Leila S. Chudori' );
		update_post_meta( $review_id, '_ss_book_publisher', 'Gramedia Pustaka Utama' );
		update_post_meta( $review_id, '_ss_book_year', '2017' );
		update_post_meta( $review_id, '_ss_reviewer', 'Redaksi Suku Sastra' );
		update_post_meta( $review_id, '_ss_shopee_url', 'https://shopee.co.id' );
		update_post_meta( $review_id, '_ss_tokopedia_url', 'https://tokopedia.com' );
		update_post_meta( $review_id, '_ss_whatsapp_url', '6281234567890' );
		update_post_meta( $review_id, '_ss_post_views', 230 );
		if ( isset( $author_ids['leila'] ) ) {
			update_post_meta( $review_id, '_ss_original_author_id', $author_ids['leila'] );
		}
	}

	// 6. Sample Berita CPT
	$berita_id = wp_insert_post(
		array(
			'post_title'   => 'Suku Sastra Meluncurkan Revamp Website Wajah Baru',
			'post_content' => '<p>Redaksi Suku Sastra resmi meluncurkan revamp website utama hari ini.</p>',
			'post_excerpt' => 'Redaksi Suku Sastra merilis tampilan website baru dengan fitur dark mode.',
			'post_status'  => 'publish',
			'post_type'    => 'berita',
		)
	);
	if ( $berita_id && ! is_wp_error( $berita_id ) ) {
		update_post_meta( $berita_id, '_ss_news_summary', 'Revamp website utama diluncurkan.' );
		update_post_meta( $berita_id, '_ss_location', 'Yogyakarta' );
		update_post_meta( $berita_id, '_ss_youtube_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' );
		update_post_meta( $berita_id, '_ss_post_views', 12 );
	}

	// 7. Sample Event CPT
	$event_active_id = wp_insert_post(
		array(
			'post_title'   => 'Workshop Kepenulisan Puisi & Cerpen 2026',
			'post_content' => '<p>Daftarkan dirimu dalam Workshop Kepenulisan Kreatif intensif selama dua hari.</p>',
			'post_excerpt' => 'Workshop kepenulisan kreatif bersama editor Suku Sastra.',
			'post_status'  => 'publish',
			'post_type'    => 'event',
		)
	);
	if ( $event_active_id && ! is_wp_error( $event_active_id ) ) {
		$next_year = (int) date( 'Y' ) + 1;
		update_post_meta( $event_active_id, '_ss_event_start', "{$next_year}-08-15" );
		update_post_meta( $event_active_id, '_ss_event_end', "{$next_year}-08-16" );
		update_post_meta( $event_active_id, '_ss_event_location', 'Zoom Cloud Meetings' );
		update_post_meta( $event_active_id, '_ss_event_status', 'upcoming' );
		update_post_meta( $event_active_id, '_ss_ticket_availability', 'available' );
		update_post_meta( $event_active_id, '_ss_booking_label', 'Daftar Sekarang' );
		update_post_meta( $event_active_id, '_ss_booking_url', 'https://forms.gle/sample-event' );
		update_post_meta( $event_active_id, '_ss_paid_ticket', '0' );
		update_post_meta( $event_active_id, '_ss_post_views', 55 );
	}

	// Flush rewrite rules to activate CPT URLs immediately
	flush_rewrite_rules();
}
