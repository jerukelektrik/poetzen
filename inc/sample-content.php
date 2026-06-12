<?php
/**
 * Sample Content Generator for Suku Sastra.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'sukusastra_init_sample_trigger' );
function sukusastra_init_sample_trigger(): void {
	if ( isset( $_GET['generate_suku_samples'] ) && '1' === $_GET['generate_suku_samples'] ) {
		sukusastra_generate_samples();
		update_option( 'sukusastra_samples_generated', 1 );
		if ( ! is_admin() ) {
			wp_safe_redirect( home_url( '/' ) );
			exit;
		}
	}
}

add_action( 'admin_notices', 'sukusastra_sample_content_notice' );
function sukusastra_sample_content_notice(): void {
	if ( get_option( 'sukusastra_samples_generated' ) ) {
		return;
	}

	$url = add_query_arg( 'generate_suku_samples', '1' );
	?>
	<div class="notice notice-info">
		<p>
			<strong><?php esc_html_e( 'Revamp Poetzen', 'sukusastra' ); ?></strong>: 
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
			'image'       => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&h=200&fit=crop', // man portrait
		),
		'chairil' => array(
			'title'       => 'Chairil Anwar',
			'birth_place' => 'Medan',
			'birth_date'  => '26 Juli 1922',
			'bio_summary' => 'Penyair legendaris pelopor Angkatan \'45 dalam kesusastraan Indonesia yang terkenal dengan julukan "Si Binatang Jalang".',
			'content'     => '<p>Chairil Anwar adalah penyair terkemuka Indonesia. Bersama Asrul Sani dan Rivai Apin, ia memelopori era puisi modern Indonesia. Chairil meninggal di usia muda namun mewariskan puisi-puisi abadi seperti Aku, Karawang-Bekasi, dan Deru Campur Debu.</p>',
			'image'       => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=200&fit=crop', // man portrait
		),
		'leila' => array(
			'title'       => 'Leila S. Chudori',
			'birth_place' => 'Jakarta',
			'birth_date'  => '12 Desember 1962',
			'bio_summary' => 'Penulis novel, cerpen, dan skenario drama Indonesia yang dikenal lewat karya monumental seperti Laut Bercerita dan Pulang.',
			'content'     => '<p>Leila Salikha Chudori adalah penulis dan wartawan berkebangsaan Indonesia. Novelnya, Laut Bercerita, memenangkan Penghargaan Sastra Asia Tenggara (S.E.A. Write Award) dan diadaptasi menjadi film pendek.</p>',
			'image'       => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=200&fit=crop', // woman portrait
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
			$id = $existing[0]->ID;
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
		if ( $id && ! is_wp_error( $id ) && ! has_post_thumbnail( $id ) ) {
			sukusastra_attach_image_from_url( $id, $data['image'] );
		}
	}

	// Create Tags
	$tags = array( 'Menulis', 'Diskusi', 'Sastra Nusantara' );
	foreach ( $tags as $tag ) {
		if ( ! term_exists( $tag, 'post_tag' ) ) {
			wp_insert_term( $tag, 'post_tag' );
		}
	}

	// 1. Sample Puisi: Melihat Hujan di Bulan Juni by Sapardi
	$existing = get_posts( array(
		'post_type'   => 'post',
		'title'       => 'Melihat Hujan di Bulan Juni',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$puisi_id = $existing[0]->ID;
	} else {
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
	}
	if ( $puisi_id && ! is_wp_error( $puisi_id ) ) {
		update_post_meta( $puisi_id, '_ss_show_home', '1' );
		update_post_meta( $puisi_id, '_ss_is_seo_article', '0' );
		update_post_meta( $puisi_id, '_ss_post_views', 420 );
		if ( isset( $author_ids['sapardi'] ) ) {
			update_post_meta( $puisi_id, '_ss_original_author_id', $author_ids['sapardi'] );
		}
		if ( ! has_post_thumbnail( $puisi_id ) ) {
			sukusastra_attach_image_from_url( $puisi_id, 'https://images.unsplash.com/photo-1473186578172-c141e6798cf4?w=800' );
		}
	}

	// 2. Sample Cerpen: Lelaki yang Menggambar Angin by Chairil
	$existing = get_posts( array(
		'post_type'   => 'post',
		'title'       => 'Lelaki yang Menggambar Angin',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$cerpen_id = $existing[0]->ID;
	} else {
		$cerpen_id = wp_insert_post(
			array(
				'post_title'    => 'Lelaki yang Menggambar Angin',
				'post_content'  => '<p>Sejak kecil, ia tidak pernah menggambar gunung, pohon, atau rumah seperti anak-anak lainnya. Di atas kertas gambarnya yang putih, ia hanya menarik garis-garis meliuk dan berputar menggunakan krayon kelabu.</p><p>"Ini apa?" tanya gurunya suatu hari.</p><p>"Ini angin, Bu. Ia sedang menari di atas atap sekolah kita," jawabnya polos.</p>',
				'post_excerpt'  => 'Sejak kecil, ia tidak menggambar rumah or gunung. Di atas kertas putihnya, ia hanya menarik garis-garis meliuk kelabu menggambar angin.',
				'post_status'   => 'publish',
				'post_type'     => 'post',
				'post_category' => array( $cat_ids['cerpen'] ),
			)
		);
	}
	if ( $cerpen_id && ! is_wp_error( $cerpen_id ) ) {
		update_post_meta( $cerpen_id, '_ss_show_home', '1' );
		update_post_meta( $cerpen_id, '_ss_is_seo_article', '0' );
		update_post_meta( $cerpen_id, '_ss_post_views', 150 );
		if ( isset( $author_ids['chairil'] ) ) {
			update_post_meta( $cerpen_id, '_ss_original_author_id', $author_ids['chairil'] );
		}
		if ( ! has_post_thumbnail( $cerpen_id ) ) {
			sukusastra_attach_image_from_url( $cerpen_id, 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=800' );
		}
	}

	// 3. Sample Esai: Estetika Sunyi dalam Sastra Modern (Uploader fallback)
	$existing = get_posts( array(
		'post_type'   => 'post',
		'title'       => 'Estetika Sunyi dalam Sastra Modern',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$esai_id = $existing[0]->ID;
	} else {
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
	}
	if ( $esai_id && ! is_wp_error( $esai_id ) ) {
		update_post_meta( $esai_id, '_ss_show_home', '1' );
		update_post_meta( $esai_id, '_ss_is_seo_article', '0' );
		update_post_meta( $esai_id, '_ss_post_views', 80 );
		if ( ! has_post_thumbnail( $esai_id ) ) {
			sukusastra_attach_image_from_url( $esai_id, 'https://images.unsplash.com/photo-1455390582262-044cdead277a?w=800' );
		}
	}

	// 4. New Sample Puisi: Aku by Chairil Anwar
	$existing = get_posts( array(
		'post_type'   => 'post',
		'title'       => 'Aku',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$aku_id = $existing[0]->ID;
	} else {
		$aku_id = wp_insert_post(
			array(
				'post_title'    => 'Aku',
				'post_content'  => '<p>Kalau sampai waktuku<br/>\'Ku mau tak seorang kan merayu<br/>Tidak juga kau<br/><br/>Tak perlu sedu sedan itu<br/>Aku ini binatang jalang<br/>Dari kumpulannya terbuang</p>',
				'post_excerpt'  => 'Puisi monumental pelopor Angkatan 45 yang sarat akan semangat individualisme dan kebebasan diri.',
				'post_status'   => 'publish',
				'post_type'     => 'post',
				'post_category' => array( $cat_ids['puisi'] ),
			)
		);
	}
	if ( $aku_id && ! is_wp_error( $aku_id ) ) {
		update_post_meta( $aku_id, '_ss_show_home', '1' );
		update_post_meta( $aku_id, '_ss_is_seo_article', '0' );
		update_post_meta( $aku_id, '_ss_post_views', 320 );
		if ( isset( $author_ids['chairil'] ) ) {
			update_post_meta( $aku_id, '_ss_original_author_id', $author_ids['chairil'] );
		}
		if ( ! has_post_thumbnail( $aku_id ) ) {
			sukusastra_attach_image_from_url( $aku_id, 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=800' );
		}
	}

	// 5. New Sample Esai: Sastra di Era Digital dan Keberlangsungan Buku by Leila
	$existing = get_posts( array(
		'post_type'   => 'post',
		'title'       => 'Sastra di Era Digital dan Keberlangsungan Buku',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$digital_id = $existing[0]->ID;
	} else {
		$digital_id = wp_insert_post(
			array(
				'post_title'    => 'Sastra di Era Digital dan Keberlangsungan Buku',
				'post_content'  => '<p>Di era digital, buku fisik dihadapkan pada persaingan ketat dengan e-book dan konten media sosial. Namun, esensi sastra tetaplah sama: merajut kemanusiaan melalui kekuatan kata dan imajinasi.</p>',
				'post_excerpt'  => 'Melihat adaptasi dunia kesusastraan di era digital serta bagaimana novel-novel fisik tetap mempertahankan jiwanya.',
				'post_status'   => 'publish',
				'post_type'     => 'post',
				'post_category' => array( $cat_ids['esai'] ),
			)
		);
	}
	if ( $digital_id && ! is_wp_error( $digital_id ) ) {
		update_post_meta( $digital_id, '_ss_show_home', '1' );
		update_post_meta( $digital_id, '_ss_is_seo_article', '0' );
		update_post_meta( $digital_id, '_ss_post_views', 210 );
		if ( isset( $author_ids['leila'] ) ) {
			update_post_meta( $digital_id, '_ss_original_author_id', $author_ids['leila'] );
		}
		if ( ! has_post_thumbnail( $digital_id ) ) {
			sukusastra_attach_image_from_url( $digital_id, 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800' );
		}
	}

	// 6. Sample Ruang Baca (SEO article)
	$existing = get_posts( array(
		'post_type'   => 'post',
		'title'       => 'Panduan Menulis Puisi untuk Pemula',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$seo_id = $existing[0]->ID;
	} else {
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
	}
	if ( $seo_id && ! is_wp_error( $seo_id ) ) {
		update_post_meta( $seo_id, '_ss_show_home', '0' );
		update_post_meta( $seo_id, '_ss_is_seo_article', '1' );
		update_post_meta( $seo_id, '_ss_post_views', 5 );
	}

	// 7. Sample Review Buku CPT linked to Leila S. Chudori
	$existing = get_posts( array(
		'post_type'   => 'review_buku',
		'title'       => 'Mengurai Luka Sejarah dalam Laut Bercerita',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$review_id = $existing[0]->ID;
	} else {
		$review_id = wp_insert_post(
			array(
				'post_title'   => 'Mengurai Luka Sejarah dalam Laut Bercerita',
				'post_content' => '<p>Novel Laut Bercerita karya Leila S. Chudori bukan sekadar kisah fiksi, melainkan sebuah rekaman emosional atas tragedi penculikan aktivis 1998.</p>',
				'post_excerpt' => 'Review novel sejarah Laut Bercerita karya Leila S. Chudori.',
				'post_status'  => 'publish',
				'post_type'    => 'review_buku',
			)
		);
	}
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
		if ( ! has_post_thumbnail( $review_id ) ) {
			sukusastra_attach_image_from_url( $review_id, 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=800' );
		}
	}

	// 8. Sample Berita CPT
	$existing = get_posts( array(
		'post_type'   => 'berita',
		'title'       => 'Suku Sastra Meluncurkan Revamp Website Wajah Baru',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$berita_id = $existing[0]->ID;
	} else {
		$berita_id = wp_insert_post(
			array(
				'post_title'   => 'Suku Sastra Meluncurkan Revamp Website Wajah Baru',
				'post_content' => '<p>Redaksi Suku Sastra resmi meluncurkan revamp website utama hari ini.</p>',
				'post_excerpt' => 'Redaksi Suku Sastra merilis tampilan website baru dengan fitur dark mode.',
				'post_status'  => 'publish',
				'post_type'    => 'berita',
			)
		);
	}
	if ( $berita_id && ! is_wp_error( $berita_id ) ) {
		update_post_meta( $berita_id, '_ss_news_summary', 'Revamp website utama diluncurkan.' );
		update_post_meta( $berita_id, '_ss_location', 'Yogyakarta' );
		update_post_meta( $berita_id, '_ss_youtube_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' );
		update_post_meta( $berita_id, '_ss_post_views', 12 );
		if ( ! has_post_thumbnail( $berita_id ) ) {
			sukusastra_attach_image_from_url( $berita_id, 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800' );
		}
	}

	// 9. Sample Event 1: Workshop Kepenulisan Puisi & Cerpen 2026
	$existing = get_posts( array(
		'post_type'   => 'event',
		'title'       => 'Workshop Kepenulisan Puisi & Cerpen 2026',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$event_1_id = $existing[0]->ID;
	} else {
		$event_1_id = wp_insert_post(
			array(
				'post_title'   => 'Workshop Kepenulisan Puisi & Cerpen 2026',
				'post_content' => '<p>Daftarkan dirimu dalam Workshop Kepenulisan Kreatif intensif selama dua hari.</p>',
				'post_excerpt' => 'Workshop kepenulisan kreatif bersama editor Suku Sastra.',
				'post_status'  => 'publish',
				'post_type'    => 'event',
			)
		);
	}
	if ( $event_1_id && ! is_wp_error( $event_1_id ) ) {
		$next_year = (int) date( 'Y' ) + 1;
		update_post_meta( $event_1_id, '_ss_event_start', "{$next_year}-08-15" );
		update_post_meta( $event_1_id, '_ss_event_end', "{$next_year}-08-16" );
		update_post_meta( $event_1_id, '_ss_event_location', 'Zoom Cloud Meetings' );
		update_post_meta( $event_1_id, '_ss_event_status', 'upcoming' );
		update_post_meta( $event_1_id, '_ss_ticket_availability', 'available' );
		update_post_meta( $event_1_id, '_ss_booking_label', 'Daftar Sekarang' );
		update_post_meta( $event_1_id, '_ss_booking_url', 'https://forms.gle/sample-event' );
		update_post_meta( $event_1_id, '_ss_paid_ticket', '0' );
		update_post_meta( $event_1_id, '_ss_post_views', 55 );
		if ( ! has_post_thumbnail( $event_1_id ) ) {
			sukusastra_attach_image_from_url( $event_1_id, 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800' );
		}
	}

	// 10. Sample Event 2: Diskusi Buku Sastra Klasik Indonesia
	$existing = get_posts( array(
		'post_type'   => 'event',
		'title'       => 'Diskusi Buku Sastra Klasik Indonesia',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$event_2_id = $existing[0]->ID;
	} else {
		$event_2_id = wp_insert_post(
			array(
				'post_title'   => 'Diskusi Buku Sastra Klasik Indonesia',
				'post_content' => '<p>Ikuti diskusi santai membahas novel-novel klasik Indonesia.</p>',
				'post_excerpt' => 'Diskusi panel rutin membahas karya-karya Pramoedya dan Mochtar Lubis.',
				'post_status'  => 'publish',
				'post_type'    => 'event',
			)
		);
	}
	if ( $event_2_id && ! is_wp_error( $event_2_id ) ) {
		$next_year = (int) date( 'Y' ) + 1;
		update_post_meta( $event_2_id, '_ss_event_start', "{$next_year}-09-10" );
		update_post_meta( $event_2_id, '_ss_event_end', "{$next_year}-09-10" );
		update_post_meta( $event_2_id, '_ss_event_location', 'Perpustakaan Daerah DIY' );
		update_post_meta( $event_2_id, '_ss_event_status', 'upcoming' );
		update_post_meta( $event_2_id, '_ss_ticket_availability', 'available' );
		update_post_meta( $event_2_id, '_ss_booking_label', 'Hadir Gratis' );
		update_post_meta( $event_2_id, '_ss_booking_url', 'https://forms.gle/sample-event-2' );
		update_post_meta( $event_2_id, '_ss_paid_ticket', '0' );
		update_post_meta( $event_2_id, '_ss_post_views', 48 );
		if ( ! has_post_thumbnail( $event_2_id ) ) {
			sukusastra_attach_image_from_url( $event_2_id, 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800' );
		}
	}

	// 11. Sample Event 3: Malam Baca Puisi & Pentas Seni Sastra
	$existing = get_posts( array(
		'post_type'   => 'event',
		'title'       => 'Malam Baca Puisi & Pentas Seni Sastra',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$event_3_id = $existing[0]->ID;
	} else {
		$event_3_id = wp_insert_post(
			array(
				'post_title'   => 'Malam Baca Puisi & Pentas Seni Sastra',
				'post_content' => '<p>Apresiasi karya sastra lisan bersama sastrawan lokal.</p>',
				'post_excerpt' => 'Malam pentas pembacaan puisi terbuka di panggung seni Suku Sastra.',
				'post_status'  => 'publish',
				'post_type'    => 'event',
			)
		);
	}
	if ( $event_3_id && ! is_wp_error( $event_3_id ) ) {
		$next_year = (int) date( 'Y' ) + 1;
		update_post_meta( $event_3_id, '_ss_event_start', "{$next_year}-10-05" );
		update_post_meta( $event_3_id, '_ss_event_end', "{$next_year}-10-05" );
		update_post_meta( $event_3_id, '_ss_event_location', 'Gedung Kesenian Jakarta' );
		update_post_meta( $event_3_id, '_ss_event_status', 'upcoming' );
		update_post_meta( $event_3_id, '_ss_ticket_availability', 'available' );
		update_post_meta( $event_3_id, '_ss_booking_label', 'Beli Tiket' );
		update_post_meta( $event_3_id, '_ss_booking_url', 'https://forms.gle/sample-event-3' );
		update_post_meta( $event_3_id, '_ss_paid_ticket', '1' );
		update_post_meta( $event_3_id, '_ss_post_views', 87 );
		if ( ! has_post_thumbnail( $event_3_id ) ) {
			sukusastra_attach_image_from_url( $event_3_id, 'https://images.unsplash.com/photo-1514306191717-452ec28c7814?w=800' );
		}
	}

	// 12. Sample Event 4: Kongres Penulis & Sastrawan Muda
	$existing = get_posts( array(
		'post_type'   => 'event',
		'title'       => 'Kongres Penulis & Sastrawan Muda',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );
	if ( ! empty( $existing ) ) {
		$event_4_id = $existing[0]->ID;
	} else {
		$event_4_id = wp_insert_post(
			array(
				'post_title'   => 'Kongres Penulis & Sastrawan Muda',
				'post_content' => '<p>Temu akbar penulis dan sastrawan muda se-Indonesia.</p>',
				'post_excerpt' => 'Kongres nasional membahas masa depan industri literasi dan kepenulisan kreatif.',
				'post_status'  => 'publish',
				'post_type'    => 'event',
			)
		);
	}
	if ( $event_4_id && ! is_wp_error( $event_4_id ) ) {
		$next_year = (int) date( 'Y' ) + 1;
		update_post_meta( $event_4_id, '_ss_event_start', "{$next_year}-11-20" );
		update_post_meta( $event_4_id, '_ss_event_end', "{$next_year}-11-22" );
		update_post_meta( $event_4_id, '_ss_event_location', 'Taman Budaya Yogyakarta' );
		update_post_meta( $event_4_id, '_ss_event_status', 'upcoming' );
		update_post_meta( $event_4_id, '_ss_ticket_availability', 'available' );
		update_post_meta( $event_4_id, '_ss_booking_label', 'Registrasi' );
		update_post_meta( $event_4_id, '_ss_booking_url', 'https://forms.gle/sample-event-4' );
		update_post_meta( $event_4_id, '_ss_paid_ticket', '1' );
		update_post_meta( $event_4_id, '_ss_post_views', 92 );
		if ( ! has_post_thumbnail( $event_4_id ) ) {
			sukusastra_attach_image_from_url( $event_4_id, 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800' );
		}
	}

	// 13. Create Static Pages
	$static_pages = array(
		'tentang-kami' => array(
			'title'    => 'Tentang Kami',
			'template' => 'page-about.php',
			'content'  => '<h3><strong>Mukadimah Suku Sastra</strong></h3><p>Suku Sastra lahir dan tumbuh dalam suasana sastra dan budaya yang dicirikan oleh kemunculan banyak pusat. Suku Sastra menyadari diri sebagai salah satu dari pusat itu dan bersedia berkompetisi secara wajar dan sehat untuk mengajukan idealitasnya.</p><p>Idealitas itu adalah lingkungan dan/atau ekosistem sastra yang dinamis sesuai dengan semangat zamannya.</p><h3><strong>Yayasan Komunitas Sastra Suku Sastra</strong></h3><p>Yayasan Komunitas Sastra Suku Sastra (YKS3) merupakan organisasi nirlaba yang fokus pada pengembangan, pelestarian, dan publikasi karya-karya literasi dan kesusastraan melalui kerja-kerja eksploratif.</p><p>YKS3 menjadi ruang gagasan alternatif sebagai kebutuhan mendesak infrastruktur literasi dan kesusastraan di luar kebijakan pemerintah dan lingkungan akademik, namun tidak menutup kemungkinan bersinggungan dengan keduanya.</p><p>Kerja-kerja eksploratif yang dilakukan Suku Sastra dapat menumbuhkan pengalaman yang cair dan hangat sehingga melahirkan alternatif-alternatif pandangan yang memperkaya kebudayaan kita.</p>',
		),
		'hubungi-kami' => array(
			'title'    => 'Hubungi Kami',
			'template' => 'page-contact.php',
			'content'  => '<p>Silakan gunakan formulir di samping untuk mengirimkan pertanyaan, saran, kerja sama kemitraan, atau kritik membangun langsung ke meja redaksi kami.</p><p>Redaksi Suku Sastra sangat terbuka terhadap kolaborasi kebudayaan, publikasi warta komunitas, maupun kerja sama program literasi kreatif lainnya.</p>',
		),
		'redaksi' => array(
			'title'    => 'Redaksi',
			'template' => 'page-redaksi.php',
			'content'  => '<h3><strong>Mukadimah Redaksi Suku Sastra</strong></h3><p>Sebagai penjaga gerbang karya sastra, Dewan Redaktur Suku Sastra berkomitmen penuh dalam memilih, menyunting, dan menyajikan karya sastra berkualitas kepada pembaca setia. Setiap karya yang masuk akan melalui proses kurasi yang ketat dan objektif oleh dewan redaktur kami.</p>',
		),
		'ketentuan-pengiriman-karya' => array(
			'title'    => 'Ketentuan Pengiriman Karya',
			'template' => 'page-submit-work.php',
			'content'  => '<p>SukuSastra.com mengundang pecinta sastra untuk mengirimkan karya sastra baik berupa puisi, cerpen, petikan novel, esai, resensi buku, terjemahan, maupun laporan tentang peristiwa sastra. SukuSastra.com akan memberikan sekadar apresiasi untuk karya yang dimuat.</p><p>Pengiriman karya dilakukan melalui formulir Google Form resmi Suku Sastra. Kami sangat menghargai orisinalitas karya dan tulisan yang memiliki kebaruan gagasan serta kesegaran diksi sastrawi.</p>',
		),
	);

	foreach ( $static_pages as $slug => $page_data ) {
		$page_exists = get_page_by_path( $slug );
		if ( ! $page_exists ) {
			$page_id = wp_insert_post(
				array(
					'post_title'   => $page_data['title'],
					'post_content' => $page_data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_name'    => $slug,
				)
			);
			if ( $page_id && ! is_wp_error( $page_id ) ) {
				update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
				
				// Sideload sample headers for pages
				if ( 'tentang-kami' === $slug ) {
					sukusastra_attach_image_from_url( $page_id, 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=1200' );
				}
			}
		} else {
			// Update template if page already exists to match Poetzen templates
			update_post_meta( $page_exists->ID, '_wp_page_template', $page_data['template'] );
		}
	}

	// Create sample terbitan (Katalog Terbitan)
	$existing_terbitan = get_posts( array(
		'post_type'   => 'terbitan',
		'name'        => 'di-desa-karya-ivan-bunin',
		'post_status' => 'publish',
		'numberposts' => 1,
	) );

	if ( empty( $existing_terbitan ) ) {
		$terbitan_id = wp_insert_post( array(
			'post_title'   => 'Di Desa',
			'post_name'    => 'di-desa-karya-ivan-bunin',
			'post_content' => '<p>Ivan Bunin adalah sastrawan Rusia pertama yang meraih Nobel, dan dengan demikian semestinya menjadi sosok yang wajib dikenal pembaca sastra serius di Indonesia.</p><p><em>Di Desa</em> adalah potret paling jujur, kelam, sekaligus puitis tentang jiwa rakyat Rusia di ambang keruntuhan sebuah zaman. Melalui nasib dua bersaudara Krasoff, Tikhon dan Kuzma, Ivan Bunin membongkar kegetiran hidup di pelosok perdesaan Rusia yang stagnan.</p><p><em>Di Desa</em> adalah sebuah fragmen sejarah yang menangkap napas terakhir Kekaisaran Rusia sebelum dilumat revolusi.</p><p>Lengkapi koleksi buku bermutu Anda dengan kemewahan prosa salah satu maestro terbesar dunia ini.</p>',
			'post_excerpt' => 'Di Desa adalah potret paling jujur, kelam, sekaligus puitis tentang jiwa rakyat Rusia di ambang keruntuhan sebuah zaman.',
			'post_status'  => 'publish',
			'post_type'    => 'terbitan',
		) );

		if ( $terbitan_id && ! is_wp_error( $terbitan_id ) ) {
			update_post_meta( $terbitan_id, '_ss_book_price', 'Rp 55.000' );
			update_post_meta( $terbitan_id, '_ss_book_whatsapp', '628388966273' );
			update_post_meta( $terbitan_id, '_ss_book_author', 'Ivan Bunin' );
			update_post_meta( $terbitan_id, '_ss_book_translator', 'Isabel Hapgood (Inggris)' );
			update_post_meta( $terbitan_id, '_ss_book_publisher', 'Yayasan Komunitas Sastra Suku Sastra' );
			update_post_meta( $terbitan_id, '_ss_book_year', '2026' );
			update_post_meta( $terbitan_id, '_ss_book_edition', 'Pertama, Februari 2026' );
			update_post_meta( $terbitan_id, '_ss_book_pages', '240' );
			update_post_meta( $terbitan_id, '_ss_book_isbn', '978-623-1234-56-7' );
			update_post_meta( $terbitan_id, '_ss_book_dimensions', '13,5 x 19,5 cm' );
			update_post_meta( $terbitan_id, '_ss_book_cover_type', 'Softcover' );

			// Try to download and attach the local cover image
			$cover_url = 'http://sukusastra.local/wp-content/uploads/2026/02/Kaver-Di-Desa-Ivan-Bunin-3-Utuh-ISBN-scaled-1.png';
			sukusastra_attach_image_from_url( $terbitan_id, $cover_url );

			// Check if cover image was set as featured image, then store it in _ss_book_image_id meta as well
			$thumb_id = get_post_thumbnail_id( $terbitan_id );
			if ( $thumb_id ) {
				update_post_meta( $terbitan_id, '_ss_book_image_id', $thumb_id );
			}

			// Add secondary images for carousel
			$gallery_urls = array(
				'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800',
				'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800'
			);
			$gallery_ids_list = array();
			foreach ( $gallery_urls as $g_url ) {
				$g_id = sukusastra_sideload_image_id_from_url( $terbitan_id, $g_url );
				if ( $g_id ) {
					$gallery_ids_list[] = $g_id;
				}
			}
			if ( ! empty( $gallery_ids_list ) ) {
				update_post_meta( $terbitan_id, '_ss_terbitan_gallery', implode( ',', $gallery_ids_list ) );
			}
		}
	}

	// Flush rewrite rules to activate CPT URLs immediately
	flush_rewrite_rules();
}

/**
 * Helper to download an image from a URL and return its attachment ID.
 */
function sukusastra_sideload_image_id_from_url( int $post_id, string $image_url ): ?int {
	if ( ! $image_url ) {
		return null;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	// Download to temp file
	$tmp = download_url( $image_url );
	if ( is_wp_error( $tmp ) ) {
		return null;
	}

	$file_array = array(
		'name'     => basename( $image_url ) . '.jpg',
		'tmp_name' => $tmp,
	);

	// Sideload to media library
	$id = media_handle_sideload( $file_array, $post_id );
	if ( is_wp_error( $id ) ) {
		@unlink( $file_array['tmp_name'] );
		return null;
	}

	return (int) $id;
}


/**
 * Helper to download an image from a URL and set it as the featured image (thumbnail) of a post.
 */
function sukusastra_attach_image_from_url( int $post_id, string $image_url, string $desc = '' ): void {
	if ( ! $image_url ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	// Download to temp file
	$tmp = download_url( $image_url );
	if ( is_wp_error( $tmp ) ) {
		return;
	}

	$file_array = array(
		'name'     => basename( $image_url ) . '.jpg',
		'tmp_name' => $tmp,
	);

	// Sideload to media library
	$id = media_handle_sideload( $file_array, $post_id, $desc );
	if ( is_wp_error( $id ) ) {
		@unlink( $file_array['tmp_name'] );
		return;
	}

	// Set as thumbnail
	set_post_thumbnail( $post_id, $id );
}
