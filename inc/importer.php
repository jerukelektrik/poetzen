<?php
/**
 * Penulis CPT Importer from CSV.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'sukusastra_penulis_importer_menu' );
function sukusastra_penulis_importer_menu(): void {
	add_submenu_page(
		'edit.php?post_type=penulis',
		__( 'Import Penulis via CSV', 'sukusastra' ),
		__( 'Import CSV', 'sukusastra' ),
		'manage_options',
		'sukusastra_import_penulis',
		'sukusastra_render_import_penulis_page'
	);
}

function sukusastra_render_import_penulis_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$import_success = false;
	$imported_count = 0;
	$skipped_count  = 0;
	$error_msg      = '';

	if ( isset( $_POST['sukusastra_import_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sukusastra_import_nonce'] ) ), 'sukusastra_do_import' ) ) {
		if ( ! empty( $_FILES['penulis_csv']['tmp_name'] ) ) {
			$file = sanitize_text_field( wp_unslash( $_FILES['penulis_csv']['tmp_name'] ) );
			
			if ( ( $handle = fopen( $file, 'r' ) ) !== false ) {
				// Read headers
				$headers = fgetcsv( $handle, 1000, ',' );
				
				// Map header names to indices
				$header_map = array();
				if ( $headers ) {
					foreach ( $headers as $index => $label ) {
						// Clean BOM and spaces
						$clean_label = trim( preg_replace( '/[\x00-\x1F\x80-\xFF]/', '', $label ) );
						$header_map[ $clean_label ] = $index;
					}
				}

				while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
					// Get values based on header mapping
					$name = '';
					if ( isset( $header_map['Display Name/Nama Penulis'] ) && isset( $data[ $header_map['Display Name/Nama Penulis'] ] ) ) {
						$name = trim( $data[ $header_map['Display Name/Nama Penulis'] ] );
					} elseif ( isset( $data[0] ) ) {
						$name = trim( $data[0] );
					}

					$slug = '';
					if ( isset( $header_map['Username/Slug'] ) && isset( $data[ $header_map['Username/Slug'] ] ) ) {
						$slug = sanitize_title( trim( $data[ $header_map['Username/Slug'] ] ) );
					} elseif ( isset( $data[1] ) ) {
						$slug = sanitize_title( trim( $data[1] ) );
					}

					if ( empty( $name ) ) {
						continue;
					}

					// Prevent duplicate import by checking slug
					$existing_query = new WP_Query( array(
						'post_type'      => 'penulis',
						'name'           => $slug,
						'posts_per_page' => 1,
						'post_status'    => 'any',
					) );

					if ( $existing_query->have_posts() ) {
						$skipped_count++;
						continue;
					}

					// If slug check passes, verify by title just in case
					$existing_title_query = new WP_Query( array(
						'post_type'      => 'penulis',
						'title'          => $name,
						'posts_per_page' => 1,
						'post_status'    => 'any',
					) );

					if ( $existing_title_query->have_posts() ) {
						$skipped_count++;
						continue;
					}

					// Fetch extra fields from CSV
					$biografi = '';
					if ( isset( $header_map['Biografi'] ) && isset( $data[ $header_map['Biografi'] ] ) ) {
						$biografi = wp_kses_post( trim( $data[ $header_map['Biografi'] ] ) );
					}

					// Insert post of CPT penulis
					$post_id = wp_insert_post( array(
						'post_title'   => $name,
						'post_name'    => $slug,
						'post_type'    => 'penulis',
						'post_status'  => 'publish',
						'post_content' => $biografi,
					) );

					if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
						// Store additional metadata
						if ( isset( $header_map['Email'] ) && isset( $data[ $header_map['Email'] ] ) ) {
							update_post_meta( $post_id, '_ss_penulis_email', sanitize_email( $data[ $header_map['Email'] ] ) );
						}
						
						// Tempat Lahir
						if ( isset( $header_map['Tempat Lahir'] ) && isset( $data[ $header_map['Tempat Lahir'] ] ) ) {
							update_post_meta( $post_id, '_ss_penulis_tempat_lahir', sanitize_text_field( $data[ $header_map['Tempat Lahir'] ] ) );
						}

						// Tanggal Lahir
						if ( isset( $header_map['Tanggal Lahir'] ) && isset( $data[ $header_map['Tanggal Lahir'] ] ) ) {
							update_post_meta( $post_id, '_ss_penulis_tanggal_lahir', sanitize_text_field( $data[ $header_map['Tanggal Lahir'] ] ) );
						}

						// Bio Summary (Deskripsi Singkat)
						if ( isset( $header_map['Bio Summary'] ) && isset( $data[ $header_map['Bio Summary'] ] ) ) {
							update_post_meta( $post_id, '_ss_penulis_bio_summary', sanitize_text_field( $data[ $header_map['Bio Summary'] ] ) );
						}
						
						$imported_count++;
					}
				}
				fclose( $handle );
				$import_success = true;
			} else {
				$error_msg = __( 'Gagal membuka file CSV.', 'sukusastra' );
			}
		} else {
			$error_msg = __( 'Silakan unggah file CSV terlebih dahulu.', 'sukusastra' );
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import Penulis via CSV', 'sukusastra' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'Gunakan halaman ini untuk mendaftarkan data Penulis secara masal langsung ke dalam Custom Post Type Penulis melalui file spreadsheet (.csv).', 'sukusastra' ); ?>
		</p>

		<?php if ( $import_success ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>
					<strong><?php echo esc_html( sprintf( __( 'Impor selesai! Berhasil menambahkan %1$d penulis baru, %2$d dilewati karena sudah ada.', 'sukusastra' ), $imported_count, $skipped_count ) ); ?></strong>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $error_msg ) ) : ?>
			<div class="notice notice-error is-dismissible">
				<p><strong><?php echo esc_html( $error_msg ); ?></strong></p>
			</div>
		<?php endif; ?>

		<div class="card" style="max-width: 600px; margin-top: 20px; padding: 20px;">
			<h2><?php esc_html_e( 'Unggah File CSV Penulis', 'sukusastra' ); ?></h2>
			<form method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( 'sukusastra_do_import', 'sukusastra_import_nonce' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="penulis_csv"><?php esc_html_e( 'Pilih File CSV', 'sukusastra' ); ?></label></th>
						<td>
							<input type="file" name="penulis_csv" id="penulis_csv" accept=".csv" required>
							<p class="description">
								<?php esc_html_e( 'File harus berupa format .csv dengan header kolom minimal "Display Name/Nama Penulis", "Username/Slug", "Biografi", "Tempat Lahir", "Tanggal Lahir", dan "Bio Summary".', 'sukusastra' ); ?>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Mulai Impor Penulis', 'sukusastra' ), 'primary' ); ?>
			</form>
		</div>
	</div>
	<?php
}

add_action( 'admin_menu', 'sukusastra_komunitas_importer_menu' );
function sukusastra_komunitas_importer_menu(): void {
	add_submenu_page(
		'edit.php?post_type=komunitas',
		__( 'Import Komunitas via CSV', 'sukusastra' ),
		__( 'Import CSV', 'sukusastra' ),
		'manage_options',
		'sukusastra_import_komunitas',
		'sukusastra_render_import_komunitas_page'
	);
}

function sukusastra_render_import_komunitas_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$import_success = false;
	$imported_count = 0;
	$skipped_count  = 0;
	$error_msg      = '';

	if ( isset( $_POST['sukusastra_comm_import_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sukusastra_comm_import_nonce'] ) ), 'sukusastra_do_comm_import' ) ) {
		if ( ! empty( $_FILES['komunitas_csv']['tmp_name'] ) ) {
			$file = sanitize_text_field( wp_unslash( $_FILES['komunitas_csv']['tmp_name'] ) );
			
			if ( ( $handle = fopen( $file, 'r' ) ) !== false ) {
				// Read headers
				$headers = fgetcsv( $handle, 1000, ',' );
				
				// Map header names to indices
				$header_map = array();
				if ( $headers ) {
					foreach ( $headers as $index => $label ) {
						// Clean BOM and spaces
						$clean_label = trim( preg_replace( '/[\x00-\x1F\x80-\xFF]/', '', $label ) );
						$header_map[ $clean_label ] = $index;
					}
				}

				while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
					// Extract Name
					$name = '';
					if ( isset( $header_map['Nama Komunitas'] ) && isset( $data[ $header_map['Nama Komunitas'] ] ) ) {
						$name = trim( $data[ $header_map['Nama Komunitas'] ] );
					} elseif ( isset( $data[0] ) ) {
						$name = trim( $data[0] );
					}

					if ( empty( $name ) ) {
						continue;
					}

					// Prevent duplicate import by checking post title
					$existing_query = new WP_Query( array(
						'post_type'      => 'komunitas',
						'title'          => $name,
						'posts_per_page' => 1,
						'post_status'    => 'any',
					) );

					if ( $existing_query->have_posts() ) {
						$skipped_count++;
						continue;
					}

					// Fetch extra fields from CSV
					$tentang = '';
					if ( isset( $header_map['Tentang Komunitas'] ) && isset( $data[ $header_map['Tentang Komunitas'] ] ) ) {
						$tentang = wp_kses_post( trim( $data[ $header_map['Tentang Komunitas'] ] ) );
					}

					// Insert post of CPT komunitas
					$post_id = wp_insert_post( array(
						'post_title'   => $name,
						'post_type'    => 'komunitas',
						'post_status'  => 'publish',
						'post_content' => $tentang,
					) );

					if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
						// Store metadata
						$meta_fields = array(
							'_ss_comm_name'         => 'Nama Komunitas',
							'_ss_comm_desc'         => 'Deskripsi Singkat',
							'_ss_comm_year'         => 'Tahun Berdiri',
							'_ss_comm_address'      => 'Alamat',
							'_ss_comm_city'         => 'Kota',
							'_ss_comm_province'     => 'Provinsi',
							'_ss_comm_website'      => 'Website',
							'_ss_comm_instagram'    => 'Instagram',
							'_ss_comm_tiktok'       => 'TikTok',
							'_ss_comm_youtube'      => 'YouTube',
							'_ss_comm_contact'      => 'Kontak',
							'_ss_comm_activities'   => 'Kegiatan',
							'_ss_comm_publications' => 'Publikasi Karya',
						);

						foreach ( $meta_fields as $meta_key => $csv_header ) {
							if ( isset( $header_map[ $csv_header ] ) && isset( $data[ $header_map[ $csv_header ] ] ) ) {
								$val = trim( $data[ $header_map[ $csv_header ] ] );
								if ( in_array( $meta_key, array( '_ss_comm_website', '_ss_comm_instagram', '_ss_comm_tiktok', '_ss_comm_youtube' ), true ) ) {
									// Support either clean URL or raw handle
									if ( ! empty( $val ) && ! str_starts_with( $val, 'http' ) ) {
										if ( '_ss_comm_instagram' === $meta_key ) {
											$val = 'https://instagram.com/' . trim( $val, '@' );
										} elseif ( '_ss_comm_tiktok' === $meta_key ) {
											$val = 'https://tiktok.com/@' . trim( $val, '@' );
										}
									}
									update_post_meta( $post_id, $meta_key, esc_url_raw( $val ) );
								} elseif ( in_array( $meta_key, array( '_ss_comm_activities', '_ss_comm_publications' ), true ) ) {
									update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $val ) );
								} else {
									update_post_meta( $post_id, $meta_key, sanitize_text_field( $val ) );
								}
							}
						}
						
						// Auto-set the _ss_comm_name using community name if not explicitly filled
						$saved_name = get_post_meta( $post_id, '_ss_comm_name', true );
						if ( empty( $saved_name ) ) {
							update_post_meta( $post_id, '_ss_comm_name', $name );
						}

						$imported_count++;
					}
				}
				fclose( $handle );
				$import_success = true;
			} else {
				$error_msg = __( 'Gagal membuka file CSV.', 'sukusastra' );
			}
		} else {
			$error_msg = __( 'Silakan unggah file CSV terlebih dahulu.', 'sukusastra' );
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import Komunitas via CSV', 'sukusastra' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'Gunakan halaman ini untuk mendaftarkan data Komunitas secara masal langsung ke dalam Custom Post Type Komunitas melalui file spreadsheet (.csv).', 'sukusastra' ); ?>
		</p>

		<?php if ( $import_success ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>
					<strong><?php echo esc_html( sprintf( __( 'Impor selesai! Berhasil menambahkan %1$d komunitas baru, %2$d dilewati karena sudah ada.', 'sukusastra' ), $imported_count, $skipped_count ) ); ?></strong>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $error_msg ) ) : ?>
			<div class="notice notice-error is-dismissible">
				<p><strong><?php echo esc_html( $error_msg ); ?></strong></p>
			</div>
		<?php endif; ?>

		<div class="card" style="max-width: 600px; margin-top: 20px; padding: 20px;">
			<h2><?php esc_html_e( 'Unggah File CSV Komunitas', 'sukusastra' ); ?></h2>
			<form method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( 'sukusastra_do_comm_import', 'sukusastra_comm_import_nonce' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="komunitas_csv"><?php esc_html_e( 'Pilih File CSV', 'sukusastra' ); ?></label></th>
						<td>
							<input type="file" name="komunitas_csv" id="komunitas_csv" accept=".csv" required>
							<p class="description" style="margin-top: 10px; line-height: 1.5;">
								<?php esc_html_e( 'File harus berupa format .csv dengan header kolom berikut:', 'sukusastra' ); ?><br>
								<code style="display: block; background: #f0f0f0; padding: 6px; margin-top: 4px; border-radius: 4px; border: 1px solid #ddd; font-family: monospace; font-size: 11px;">
									Nama Komunitas, Tentang Komunitas, Deskripsi Singkat, Tahun Berdiri, Alamat, Kota, Provinsi, Website, Instagram, TikTok, YouTube, Kontak, Kegiatan, Publikasi Karya
								</code>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Mulai Impor Komunitas', 'sukusastra' ), 'primary' ); ?>
			</form>
		</div>
	</div>
	<?php
}

add_action( 'rest_api_init', 'sukusastra_register_komunitas_api' );
function sukusastra_register_komunitas_api(): void {
	register_rest_route( 'sukusastra/v1', '/import-komunitas', array(
		'methods'             => 'POST',
		'callback'            => 'sukusastra_api_import_komunitas_callback',
		'permission_callback' => '__return_true',
	) );
}

function sukusastra_api_import_komunitas_callback( WP_REST_Request $request ) {
	$token = $request->get_header( 'X-SukuSastra-Token' );
	$valid_token = 'sukusastra_sheets_token_2026';

	if ( empty( $token ) || $token !== $valid_token ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Unauthorized: Invalid API Token.',
		), 401 );
	}

	$params = $request->get_json_params();
	if ( empty( $params ) ) {
		$params = $request->get_body_params();
	}

	$name = isset( $params['Nama Komunitas'] ) ? trim( $params['Nama Komunitas'] ) : '';
	if ( empty( $name ) ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Missing parameter: Nama Komunitas is required.',
		), 400 );
	}

	// Prevent duplicate check by title
	$existing = new WP_Query( array(
		'post_type'      => 'komunitas',
		'title'          => $name,
		'posts_per_page' => 1,
		'post_status'    => 'any',
	) );

	if ( $existing->have_posts() ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Duplicate: Community already exists.',
		), 200 );
	}

	$tentang = isset( $params['Tentang Komunitas'] ) ? wp_kses_post( trim( $params['Tentang Komunitas'] ) ) : '';

	// Insert post as draft
	$post_id = wp_insert_post( array(
		'post_title'   => $name,
		'post_type'    => 'komunitas',
		'post_status'  => 'draft',
		'post_content' => $tentang,
	) );

	if ( is_wp_error( $post_id ) || $post_id <= 0 ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Failed to create post in database.',
		), 500 );
	}

	// Map metadata
	$meta_fields = array(
		'_ss_comm_name'         => 'Nama Komunitas',
		'_ss_comm_desc'         => 'Deskripsi Singkat',
		'_ss_comm_year'         => 'Tahun Berdiri',
		'_ss_comm_address'      => 'Alamat',
		'_ss_comm_city'         => 'Kota',
		'_ss_comm_province'     => 'Provinsi',
		'_ss_comm_website'      => 'Website',
		'_ss_comm_instagram'    => 'Instagram',
		'_ss_comm_tiktok'       => 'TikTok',
		'_ss_comm_youtube'      => 'YouTube',
		'_ss_comm_contact'      => 'Kontak',
		'_ss_comm_activities'   => 'Kegiatan',
		'_ss_comm_publications' => 'Publikasi Karya',
	);

	foreach ( $meta_fields as $meta_key => $csv_header ) {
		if ( isset( $params[ $csv_header ] ) ) {
			$val = trim( $params[ $csv_header ] );
			if ( in_array( $meta_key, array( '_ss_comm_website', '_ss_comm_instagram', '_ss_comm_tiktok', '_ss_comm_youtube' ), true ) ) {
				if ( ! empty( $val ) && ! str_starts_with( $val, 'http' ) ) {
					if ( '_ss_comm_instagram' === $meta_key ) {
						$val = 'https://instagram.com/' . trim( $val, '@' );
					} elseif ( '_ss_comm_tiktok' === $meta_key ) {
						$val = 'https://tiktok.com/@' . trim( $val, '@' );
					}
				}
				update_post_meta( $post_id, $meta_key, esc_url_raw( $val ) );
			} elseif ( in_array( $meta_key, array( '_ss_comm_activities', '_ss_comm_publications' ), true ) ) {
				update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $val ) );
			} else {
				update_post_meta( $post_id, $meta_key, sanitize_text_field( $val ) );
			}
		}
	}

	// Auto-set Name metadata if blank
	$saved_name = get_post_meta( $post_id, '_ss_comm_name', true );
	if ( empty( $saved_name ) ) {
		update_post_meta( $post_id, '_ss_comm_name', $name );
	}

	return new WP_REST_Response( array(
		'success' => true,
		'message' => 'Community drafted successfully.',
		'post_id' => $post_id,
	), 200 );
}

add_action( 'rest_api_init', 'sukusastra_register_dongeng_api' );
function sukusastra_register_dongeng_api(): void {
	register_rest_route( 'sukusastra/v1', '/import-dongeng', array(
		'methods'             => 'POST',
		'callback'            => 'sukusastra_api_import_dongeng_callback',
		'permission_callback' => '__return_true',
	) );
}

function sukusastra_api_import_dongeng_callback( WP_REST_Request $request ) {
	$token = $request->get_header( 'X-SukuSastra-Token' );
	$valid_token = 'sukusastra_sheets_token_2026';

	if ( empty( $token ) || $token !== $valid_token ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Unauthorized: Invalid API Token.',
		), 401 );
	}

	$params = $request->get_json_params();
	if ( empty( $params ) ) {
		$params = $request->get_body_params();
	}

	$name = isset( $params['Judul Artikel'] ) ? trim( $params['Judul Artikel'] ) : '';
	if ( empty( $name ) ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Missing parameter: Judul Artikel is required.',
		), 400 );
	}

	// Prevent duplicate check by title
	$existing = new WP_Query( array(
		'post_type'      => 'post',
		'title'          => $name,
		'posts_per_page' => 1,
		'post_status'    => 'any',
	) );

	if ( $existing->have_posts() ) {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Duplicate: Article already exists.',
		), 200 );
	}

	// Fetch or create "Dongeng" category
	$cat_id = get_cat_ID( 'Dongeng' );
	if ( 0 === $cat_id ) {
		$new_cat = wp_insert_term( 'Dongeng', 'category' );
		if ( ! is_wp_error( $new_cat ) ) {
			$cat_id = $new_cat['term_id'];
		}
	}

	// Fetch or create CPT Penulis profile
	$author_name = isset( $params['Penulis Asli'] ) ? trim( $params['Penulis Asli'] ) : '';
	$author_id = 0;
	if ( ! empty( $author_name ) ) {
		$author_query = new WP_Query( array(
			'post_type'      => 'penulis',
			'title'          => $author_name,
			'posts_per_page' => 1,
			'post_status'    => 'any',
		) );
		if ( $author_query->have_posts() ) {
			$author_query->the_post();
			$author_id = get_the_ID();
			wp_reset_postdata();
		} else {
			// Auto create CPT Penulis profile
			$author_post_id = wp_insert_post( array(
				'post_title'  => $author_name,
				'post_type'   => 'penulis',
				'post_status' => 'publish',
			) );
			if ( ! is_wp_error( $author_post_id ) ) {
				$author_id = $author_post_id;
				update_post_meta( $author_id, '_ss_penulis_bio_summary', sprintf( esc_html__( 'Profil penulis %s.', 'sukusastra' ), $author_name ) );
			}
		}
	}

	$post_data = array(
		'post_title'   => $name,
		'post_content' => isset( $params['Body Artikel'] ) ? wp_kses_post( trim( $params['Body Artikel'] ) ) : '',
		'post_type'    => 'post',
		'post_status'  => 'draft',
	);

	if ( $cat_id > 0 ) {
		$post_data['post_category'] = array( $cat_id );
	}

	$post_id = wp_insert_post( $post_data );

	if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
		// Auto-set Ruang Baca/Artikel SEO and Show Home
		update_post_meta( $post_id, '_ss_is_seo_article', '1' );
		update_post_meta( $post_id, '_ss_show_home', '1' );

		// Map metadata
		if ( isset( $params['SEO Title'] ) ) {
			update_post_meta( $post_id, '_ss_seo_title', sanitize_text_field( $params['SEO Title'] ) );
		}
		if ( isset( $params['Meta Description'] ) ) {
			update_post_meta( $post_id, '_ss_meta_desc', sanitize_text_field( $params['Meta Description'] ) );
		}
		if ( isset( $params['Pesan Moral'] ) ) {
			update_post_meta( $post_id, '_ss_pesan_moral', sanitize_textarea_field( $params['Pesan Moral'] ) );
		}
		if ( $author_id > 0 ) {
			update_post_meta( $post_id, '_ss_original_author_id', (string) $author_id );
		}

		return new WP_REST_Response( array(
			'success' => true,
			'message' => 'Dongeng article drafted successfully.',
			'post_id' => $post_id,
		), 200 );
	} else {
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Failed to create article in database.',
		), 500 );
	}
}



