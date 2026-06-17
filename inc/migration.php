<?php
/**
 * Custom migration exporter and importer for Suku Sastra.
 * Exports posts and custom metadata to a lightweight JSON file,
 * and imports them while automatically registering physical media attachments.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'sukusastra_migration_admin_menu' );
function sukusastra_migration_admin_menu(): void {
	add_management_page(
		__( 'Migrasi Suku Sastra', 'sukusastra' ),
		__( 'Migrasi Suku Sastra', 'sukusastra' ),
		'manage_options',
		'sukusastra_migration',
		'sukusastra_render_migration_page'
	);
}

/**
 * Helper to get relative image path from attachment ID.
 */
function sukusastra_get_relative_image_path( int $attachment_id ): string {
	if ( ! $attachment_id ) {
		return '';
	}
	$attached_file = get_attached_file( $attachment_id );
	if ( $attached_file ) {
		$upload_dir = wp_upload_dir();
		return str_replace( $upload_dir['basedir'] . '/', '', $attached_file );
	}
	return '';
}

/**
 * Helper to register a physical file as a WordPress attachment.
 */
function sukusastra_register_image_attachment( string $relative_path, int $parent_post_id = 0 ): int {
	if ( empty( $relative_path ) ) {
		return 0;
	}

	$upload_dir = wp_upload_dir();
	$file_path  = $upload_dir['basedir'] . '/' . $relative_path;

	if ( ! file_exists( $file_path ) ) {
		return 0;
	}

	// Check if this attachment is already registered by searching for guid or meta filepath
	global $wpdb;
	$filename = basename( $file_path );
	$query    = $wpdb->prepare(
		"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
		$relative_path
	);
	$existing_id = (int) $wpdb->get_var( $query );

	if ( $existing_id > 0 ) {
		return $existing_id;
	}

	// Register the file in Media Library
	$wp_filetype = wp_check_filetype( $filename, null );
	$attachment  = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => preg_replace( '/\.[^.]+$/', '', $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $attachment, $file_path, $parent_post_id );

	if ( ! is_wp_error( $attach_id ) && $attach_id > 0 ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		update_post_meta( $attach_id, '_wp_attached_file', $relative_path );
		return $attach_id;
	}

	return 0;
}

/**
 * Handle one-off category to CPT migration.
 */
add_action( 'admin_init', 'sukusastra_handle_peristiwa_migration' );
function sukusastra_handle_peristiwa_migration(): void {
	if ( ! is_admin() || ! isset( $_GET['page'] ) || 'sukusastra_migration' !== $_GET['page'] ) {
		return;
	}

	if ( ! isset( $_GET['action'] ) || 'migrate_peristiwa' !== $_GET['action'] ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized access.', 'sukusastra' ) );
	}

	check_admin_referer( 'sukusastra_migrate_peristiwa_action', 'nonce' );

	global $wpdb;

	$term = get_category_by_slug( 'peristiwa' );
	if ( ! $term ) {
		wp_redirect( admin_url( 'tools.php?page=sukusastra_migration&error=category_not_found' ) );
		exit;
	}

	$term_taxonomy_id = $term->term_taxonomy_id;

	$post_ids = $wpdb->get_col( $wpdb->prepare(
		"SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d",
		$term_taxonomy_id
	) );

	if ( empty( $post_ids ) ) {
		wp_redirect( admin_url( 'tools.php?page=sukusastra_migration&error=no_posts_found' ) );
		exit;
	}

	$placeholders = implode( ',', array_fill( 0, count( $post_ids ), '%d' ) );
	$updated = $wpdb->query( $wpdb->prepare(
		"UPDATE $wpdb->posts SET post_type = 'berita' WHERE post_type = 'post' AND ID IN ($placeholders)",
		...$post_ids
	) );

	wp_redirect( admin_url( 'tools.php?page=sukusastra_migration&migration_success=1&count=' . (int) $updated ) );
	exit;
}

/**
 * Handle the Export Trigger.
 */
add_action( 'admin_init', 'sukusastra_handle_json_export' );
function sukusastra_handle_json_export(): void {
	if ( ! is_admin() || ! isset( $_GET['page'] ) || 'sukusastra_migration' !== $_GET['page'] ) {
		return;
	}

	if ( ! isset( $_GET['action'] ) || 'export_json' !== $_GET['action'] ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized access.', 'sukusastra' ) );
	}

	check_admin_referer( 'sukusastra_export_json_action', 'nonce' );

	$part = isset( $_GET['part'] ) ? (int) $_GET['part'] : 1;
	if ( $part < 1 || $part > 10 ) {
		$part = 1;
	}

	// Query posts
	$query_args = array(
		'post_type'      => array( 'post', 'review_buku', 'berita', 'event', 'terbitan', 'penulis' ),
		'posts_per_page' => -1,
		'post_status'    => 'any',
	);

	$posts = get_posts( $query_args );
	$total_posts = count( $posts );
	
	// Chunk the posts into 10 parts
	$chunk_size = (int) ceil( $total_posts / 10 );
	$offset = ( $part - 1 ) * $chunk_size;
	$posts_chunk = array_slice( $posts, $offset, $chunk_size );

	$export_data = array();

	foreach ( $posts_chunk as $p ) {
		// Categories
		$categories = array();
		if ( 'post' === $p->post_type || 'review_buku' === $p->post_type || 'terbitan' === $p->post_type ) {
			$cats = get_the_category( $p->ID );
			foreach ( $cats as $c ) {
				$categories[] = $c->name;
			}
		}

		// Featured Image
		$featured_image_path = sukusastra_get_relative_image_path( (int) get_post_thumbnail_id( $p->ID ) );

		// Metas (all starting with _ss_)
		$all_metas = get_post_meta( $p->ID );
		$custom_metas = array();
		
		foreach ( $all_metas as $key => $values ) {
			if ( strpos( $key, '_ss_' ) === 0 ) {
				$val = $values[0];
				
				// Handle specific meta keys that store attachment IDs
				if ( '_ss_book_image_id' === $key ) {
					$custom_metas['_ss_book_image_path'] = sukusastra_get_relative_image_path( (int) $val );
				} elseif ( '_ss_event_gallery' === $key || '_ss_terbitan_gallery' === $key ) {
					$ids = array_filter( array_map( 'intval', explode( ',', $val ) ) );
					$paths = array();
					foreach ( $ids as $id ) {
						$paths[] = sukusastra_get_relative_image_path( $id );
					}
					$custom_metas[ $key . '_paths' ] = implode( ',', array_filter( $paths ) );
				} elseif ( '_ss_original_author_id' === $key && ! empty( $val ) ) {
					$author_post = get_post( (int) $val );
					if ( $author_post ) {
						$custom_metas['_ss_original_author_slug'] = $author_post->post_name;
					}
				} else {
					$custom_metas[ $key ] = $val;
				}
			}
		}

		// Export strictly image and custom meta mapping only
		$export_data[] = array(
			'slug'                => $p->post_name,
			'post_type'           => $p->post_type,
			'categories'          => $categories,
			'featured_image_path' => $featured_image_path,
			'metas'               => $custom_metas,
		);
	}

	$json_content = wp_json_encode( $export_data, JSON_PRETTY_PRINT );

	header( 'Content-Description: File Transfer' );
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="sukusastra_images_part_' . $part . '_' . date( 'Ymd_His' ) . '.json"' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate' );
	header( 'Pragma: public' );
	header( 'Content-Length: ' . strlen( $json_content ) );
	
	echo $json_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit;
}

/**
 * Handle the Import Upload Trigger (handles sorting and redirection to batching).
 */
add_action( 'admin_init', 'sukusastra_handle_json_import' );
function sukusastra_handle_json_import(): void {
	if ( ! is_admin() || ! isset( $_GET['page'] ) || 'sukusastra_migration' !== $_GET['page'] ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['sukusastra_import_json_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sukusastra_import_json_nonce'] ) ), 'sukusastra_do_json_import' ) ) {
		if ( ! empty( $_FILES['migration_json']['tmp_name'] ) ) {
			$file = sanitize_text_field( wp_unslash( $_FILES['migration_json']['tmp_name'] ) );
			$json_raw = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$data = json_decode( $json_raw, true );

			if ( is_array( $data ) ) {
				// Sort: process CPT 'penulis' first so author mapping works correctly
				usort( $data, function( $a, $b ) {
					if ( 'penulis' === $a['post_type'] && 'penulis' !== $b['post_type'] ) {
						return -1;
					}
					if ( 'penulis' !== $a['post_type'] && 'penulis' === $b['post_type'] ) {
						return 1;
					}
					return 0;
				} );

				update_option( 'sukusastra_migration_data', $data );

				$redirect_url = admin_url( 'tools.php?page=sukusastra_migration&step=import&offset=0&imported=0&skipped=0' );
				wp_safe_redirect( $redirect_url );
				exit;
			} else {
				wp_safe_redirect( admin_url( 'tools.php?page=sukusastra_migration&import_error=invalid_json' ) );
				exit;
			}
		} else {
			wp_safe_redirect( admin_url( 'tools.php?page=sukusastra_migration&import_error=no_file' ) );
			exit;
		}
	}
}

/**
 * Render the Migration Page.
 */
function sukusastra_render_migration_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Handle GET Batch Processing
	if ( isset( $_GET['step'] ) && 'import' === $_GET['step'] ) {
		$data = get_option( 'sukusastra_migration_data' );
		if ( ! is_array( $data ) ) {
			wp_safe_redirect( admin_url( 'tools.php?page=sukusastra_migration&import_error=data_lost' ) );
			exit;
		}

		$offset = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
		$imported_count = isset( $_GET['imported'] ) ? (int) $_GET['imported'] : 0;
		$skipped_count = isset( $_GET['skipped'] ) ? (int) $_GET['skipped'] : 0;
		$batch_size = 5; // Process 5 items per batch to prevent server resource limits

		$total_items = count( $data );
		$batch_items = array_slice( $data, $offset, $batch_size );

		// Process this batch
		foreach ( $batch_items as $item ) {
			@set_time_limit( 30 );
			$slug = sanitize_title( $item['slug'] );
			
			// Skip duplicate slug
			$existing = new WP_Query( array(
				'post_type'      => $item['post_type'],
				'name'           => $slug,
				'posts_per_page' => 1,
				'post_status'    => 'any',
			) );

			$post_id = 0;

			if ( $existing->have_posts() ) {
				$post_id = $existing->posts[0]->ID;
				$skipped_count++;
			} else {
				// Only insert post if we have title (meaning it's a full export)
				if ( ! empty( $item['title'] ) ) {
					// Insert post
					$post_id = wp_insert_post( array(
						'post_title'   => $item['title'],
						'post_name'    => $slug,
						'post_content' => $item['content'],
						'post_excerpt' => $item['excerpt'],
						'post_date'    => $item['date'],
						'post_type'    => $item['post_type'],
						'post_status'  => 'publish',
					) );

					if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
						$imported_count++;
					}
				} else {
					$skipped_count++;
				}
			}

			if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
				// 1. Categories
				if ( ! empty( $item['categories'] ) ) {
					$cat_ids = array();
					foreach ( $item['categories'] as $cat_name ) {
						$term = get_term_by( 'name', $cat_name, 'category' );
						if ( $term ) {
							$cat_ids[] = (int) $term->term_id;
						} else {
							$new_cat = wp_insert_term( $cat_name, 'category' );
							if ( ! is_wp_error( $new_cat ) ) {
								$cat_ids[] = (int) $new_cat['term_id'];
							}
						}
					}
					wp_set_post_categories( $post_id, $cat_ids );
				}

				// 2. Featured Image (Register if physical file exists)
				if ( ! empty( $item['featured_image_path'] ) ) {
					$attach_id = sukusastra_register_image_attachment( $item['featured_image_path'], $post_id );
					if ( $attach_id > 0 ) {
						set_post_thumbnail( $post_id, $attach_id );
					}
				}

				// 3. Metadata
				if ( ! empty( $item['metas'] ) ) {
					foreach ( $item['metas'] as $key => $val ) {
						// Reconstruct specific attachment paths to new IDs
						if ( '_ss_book_image_path' === $key ) {
							$attach_id = sukusastra_register_image_attachment( $val, $post_id );
							if ( $attach_id > 0 ) {
								update_post_meta( $post_id, '_ss_book_image_id', $attach_id );
							}
						} elseif ( '_ss_event_gallery_paths' === $key || '_ss_terbitan_gallery_paths' === $key ) {
							$meta_target_key = str_replace( '_paths', '', $key );
							$paths = explode( ',', $val );
							$new_ids = array();
							foreach ( $paths as $path ) {
								$new_ids[] = sukusastra_register_image_attachment( $path, $post_id );
							}
							$filtered_ids = array_filter( $new_ids );
							if ( ! empty( $filtered_ids ) ) {
								update_post_meta( $post_id, $meta_target_key, implode( ',', $filtered_ids ) );
							}
						} elseif ( '_ss_original_author_slug' === $key ) {
							// Search CPT penulis by slug
							$author_query = new WP_Query( array(
								'post_type'      => 'penulis',
								'name'           => $val,
								'posts_per_page' => 1,
								'post_status'    => 'any',
							) );
							if ( $author_query->have_posts() ) {
								$author_id = $author_query->posts[0]->ID;
								update_post_meta( $post_id, '_ss_original_author_id', $author_id );
							}
						} else {
							update_post_meta( $post_id, $key, $val );
						}
					}
				}
			}
		}

		$new_offset = $offset + $batch_size;
		$percent = min( 100, round( ( $new_offset / $total_items ) * 100 ) );

		if ( $new_offset >= $total_items ) {
			delete_option( 'sukusastra_migration_data' );
			?>
			<script>
				window.location.href = '<?php echo esc_url_raw( admin_url( 'tools.php?page=sukusastra_migration&import_success=1&imported=' . $imported_count . '&skipped=' . $skipped_count ) ); ?>';
			</script>
			<?php
			return;
		}

		// Show progress
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Sedang Mengimpor Data...', 'sukusastra' ); ?></h1>
			<p class="description"><?php esc_html_e( 'Proses ini membagi beban impor menjadi beberapa tahap kecil agar server tidak overload.', 'sukusastra' ); ?></p>
			
			<div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 20px; max-width: 600px; border-top: 4px solid #b42318;">
				<div style="font-size: 16px; margin-bottom: 15px;">
					<strong>Progres: <?php echo esc_html( $new_offset ); ?> / <?php echo esc_html( $total_items ); ?> item</strong> (<?php echo esc_html( $percent ); ?>%)
				</div>
				<div style="background: #eee; border-radius: 10px; height: 16px; width: 100%; overflow: hidden; margin-bottom: 20px;">
					<div style="background: #b42318; width: <?php echo esc_attr( $percent ); ?>%; height: 100%; transition: width 0.2s ease;"></div>
				</div>
				<ul style="margin: 0; padding: 0 0 0 20px; list-style-type: disc; line-height: 1.6;">
					<li>Berhasil diimpor/sync baru: <strong><?php echo esc_html( $imported_count ); ?></strong></li>
					<li>Dilewati/update (sudah ada): <strong><?php echo esc_html( $skipped_count ); ?></strong></li>
				</ul>
				<p style="color: #666; font-style: italic; margin-top: 25px; font-size: 13px;">Mohon jangan tutup atau refresh tab browser ini sampai selesai...</p>
			</div>
		</div>
		<script>
			setTimeout(function() {
				window.location.href = '<?php echo esc_url_raw( admin_url( 'tools.php?page=sukusastra_migration&step=import&offset=' . $new_offset . '&imported=' . $imported_count . '&skipped=' . $skipped_count ) ); ?>';
			}, 300);
		</script>
		<?php
		return;
	}

	$import_success = isset( $_GET['import_success'] ) && '1' === $_GET['import_success'];
	$imported_count = isset( $_GET['imported'] ) ? (int) $_GET['imported'] : 0;
	$skipped_count  = isset( $_GET['skipped'] ) ? (int) $_GET['skipped'] : 0;
	$error_msg      = '';

	$migration_success = isset( $_GET['migration_success'] ) && '1' === $_GET['migration_success'];
	$migrated_count    = isset( $_GET['count'] ) ? (int) $_GET['count'] : 0;
	$migration_error   = isset( $_GET['error'] ) ? sanitize_text_field( wp_unslash( $_GET['error'] ) ) : '';

	if ( isset( $_GET['import_error'] ) ) {
		$err = sanitize_text_field( wp_unslash( $_GET['import_error'] ) );
		if ( 'invalid_json' === $err ) {
			$error_msg = __( 'Gagal membaca format JSON. Pastikan file valid.', 'sukusastra' );
		} elseif ( 'no_file' === $err ) {
			$error_msg = __( 'Silakan unggah file JSON hasil ekspor terlebih dahulu.', 'sukusastra' );
		} elseif ( 'data_lost' === $err ) {
			$error_msg = __( 'Data migrasi hilang atau kedaluwarsa. Silakan upload ulang.', 'sukusastra' );
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Migrasi Database Suku Sastra', 'sukusastra' ); ?></h1>

		<?php if ( $migration_success ) : ?>
			<div class="notice notice-success is-dismissible" style="margin-left: 0; margin-right: 0; margin-top: 15px;">
				<p>
					<strong><?php echo esc_html( sprintf( __( 'Berhasil! Sebanyak %d artikel peristiwa telah dipindahkan ke Post Type Berita.', 'sukusastra' ), $migrated_count ) ); ?></strong>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( 'category_not_found' === $migration_error ) : ?>
			<div class="notice notice-error is-dismissible" style="margin-left: 0; margin-right: 0; margin-top: 15px;">
				<p><strong><?php esc_html_e( 'Gagal: Kategori dengan slug "peristiwa" tidak ditemukan.', 'sukusastra' ); ?></strong></p>
			</div>
		<?php elseif ( 'no_posts_found' === $migration_error ) : ?>
			<div class="notice notice-warning is-dismissible" style="margin-left: 0; margin-right: 0; margin-top: 15px;">
				<p><strong><?php esc_html_e( 'Perhatian: Tidak ada artikel yang ditemukan di kategori "peristiwa".', 'sukusastra' ); ?></strong></p>
			</div>
		<?php endif; ?>

		<p class="description">
			<?php esc_html_e( 'Sistem kustom untuk memindahkan artikel, custom post types, kategori, dan metadata antar server menggunakan file JSON super ringan.', 'sukusastra' ); ?>
		</p>

		<div style="display: flex; gap: 20px; margin-top: 20px; align-items: start;">
			
			<!-- Export Box -->
			<div class="card" style="flex: 1.2; padding: 20px; background: #fff; border-radius: 8px;">
				<h2><?php esc_html_e( '1. Ekspor Data Gambar & Meta (Lokal)', 'sukusastra' ); ?></h2>
				<p style="margin-bottom: 20px;"><?php esc_html_e( 'Unduh data pemetaan gambar lokal Anda. Data ini dipecah menjadi 10 bagian kecil agar proses impor di server staging dijamin sukses 100% tanpa timeout.', 'sukusastra' ); ?></p>
				
				<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
					<?php
					$query_args = array(
						'post_type'      => array( 'post', 'review_buku', 'berita', 'event', 'terbitan', 'penulis' ),
						'posts_per_page' => -1,
						'post_status'    => 'any',
					);
					$posts_query = get_posts( $query_args );
					$total_posts = count( $posts_query );
					$chunk_size = (int) ceil( $total_posts / 10 );

					for ( $part = 1; $part <= 10; $part++ ) {
						$start = ( $part - 1 ) * $chunk_size + 1;
						$end = min( $total_posts, $part * $chunk_size );
						
						$export_url = wp_nonce_url(
							admin_url( 'tools.php?page=sukusastra_migration&action=export_json&part=' . $part ),
							'sukusastra_export_json_action',
							'nonce'
						);
						?>
						<a href="<?php echo esc_url( $export_url ); ?>" class="button button-secondary" style="text-align: center; padding: 6px 0; font-weight: 600; display: block; border-color: #ccc; color: #333;">
							Part <?php echo esc_html( $part ); ?> (<?php echo esc_html( $start ); ?>-<?php echo esc_html( $end ); ?>)
						</a>
						<?php
					}
					?>
				</div>
			</div>

			<!-- Import Box -->
			<div class="card" style="flex: 1; padding: 20px; background: #fff; border-radius: 8px;">
				<h2><?php esc_html_e( '2. Impor Data (Staging/Live)', 'sukusastra' ); ?></h2>
				<p><?php esc_html_e( 'Gunakan formulir di bawah ini di website staging Anda untuk mengunggah file JSON ekspor dan mengimpor seluruh data.', 'sukusastra' ); ?></p>
				
				<?php if ( $import_success ) : ?>
					<div class="notice notice-success is-dismissible" style="margin-left: 0; margin-right: 0;">
						<p>
							<strong><?php echo esc_html( sprintf( __( 'Impor Berhasil! Berhasil memindahkan %1$d data baru, %2$d dilewati karena sudah ada.', 'sukusastra' ), $imported_count, $skipped_count ) ); ?></strong>
						</p>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $error_msg ) ) : ?>
					<div class="notice notice-error is-dismissible" style="margin-left: 0; margin-right: 0;">
						<p><strong><?php echo esc_html( $error_msg ); ?></strong></p>
					</div>
				<?php endif; ?>

				<form method="post" enctype="multipart/form-data" style="margin-top: 15px;">
					<?php wp_nonce_field( 'sukusastra_do_json_import', 'sukusastra_import_json_nonce' ); ?>
					<input type="file" name="migration_json" accept=".json" required style="display: block; margin-bottom: 15px;">
					<?php submit_button( __( 'Mulai Impor Migrasi', 'sukusastra' ), 'primary button-large' ); ?>
				</form>
			</div>

		</div>

		<!-- One-off Tools Box -->
		<div class="card" style="background: #fff; padding: 20px; border-radius: 8px; margin-top: 25px; border-top: 4px solid #b42318; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 100%;">
			<h2>3. Perkakas Khusus (One-Off Tools)</h2>
			<p>Gunakan tombol di bawah ini untuk memindahkan semua artikel dari kategori <strong>Peristiwa</strong> (slug: <code>peristiwa</code>) ke Custom Post Type <strong>Berita</strong> secara otomatis dan aman langsung di database.</p>
			
			<?php
			$migrate_url = wp_nonce_url(
				admin_url( 'tools.php?page=sukusastra_migration&action=migrate_peristiwa' ),
				'sukusastra_migrate_peristiwa_action',
				'nonce'
			);
			?>
			<p style="margin-top: 15px;">
				<a href="<?php echo esc_url( $migrate_url ); ?>" class="button button-primary" onclick="return confirm('Apakah Anda yakin ingin memindahkan semua artikel di kategori Peristiwa ke Post Type Berita? Tindakan ini akan mengubah post_type tulisan secara permanen.');" style="padding: 5px 15px; font-weight: 600;">
					Migrasikan Kategori Peristiwa ke CPT Berita
				</a>
			</p>
		</div>

		<!-- Diagnostics Box -->
		<div style="background: #fff; padding: 20px; border-radius: 8px; margin-top: 25px; border-top: 4px solid #00a0d2; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 100%;">
			<h2>Diagnostics / Status Folder Server</h2>
			<p>Gunakan status di bawah ini untuk memastikan file gambar Anda diletakkan di folder hosting yang tepat. Jika folder dilaporkan tidak terdeteksi, pendaftaran gambar ke Media Library akan otomatis dilewati.</p>
			
			<table class="widefat striped" style="margin-top: 15px; border: 1px solid #ccc; border-collapse: collapse; width: 100%;">
				<thead>
					<tr style="background: #f9f9f9;">
						<th style="padding: 10px; border: 1px solid #ccc; font-weight: bold; text-align: left;">Parameter Pemeriksaan</th>
						<th style="padding: 10px; border: 1px solid #ccc; font-weight: bold; text-align: left;">Hasil Deteksi Staging</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="padding: 10px; border: 1px solid #ccc; width: 40%;"><strong>Uploads Base Directory (Jalur PHP Server)</strong></td>
						<td style="padding: 10px; border: 1px solid #ccc;"><code><?php echo esc_html( wp_upload_dir()['basedir'] ); ?></code></td>
					</tr>
					<tr>
						<td style="padding: 10px; border: 1px solid #ccc;"><strong>Folder 2025 di Hosting</strong></td>
						<td style="padding: 10px; border: 1px solid #ccc;">
							<?php 
							$dir_2025 = wp_upload_dir()['basedir'] . '/2025';
							if ( is_dir( $dir_2025 ) ) {
								echo '<span style="color: green; font-weight: bold;">✔ Terdeteksi (Folder Ada)</span><br>';
								$subdirs = array_filter( glob( $dir_2025 . '/*' ), 'is_dir' );
								if ( ! empty( $subdirs ) ) {
									echo 'Subfolder bulan: ' . esc_html( implode( ', ', array_map( 'basename', $subdirs ) ) ) . '<br>';
									$first_sub = $subdirs[0];
									$files = array_diff( scandir( $first_sub ), array( '.', '..' ) );
									echo 'Bulan ' . esc_html( basename( $first_sub ) ) . ' berisi ' . count( $files ) . ' file (Sample: ' . esc_html( implode( ', ', array_slice( $files, 0, 3 ) ) ) . ')';
								} else {
									echo '<span style="color: orange;">Folder 2025 kosong / tidak berisi subfolder bulan!</span>';
								}
							} else {
								echo '<span style="color: red; font-weight: bold;">❌ Tidak Terdeteksi (Folder Belum Ada / Salah Letak)</span>';
							}
							?>
						</td>
					</tr>
					<tr>
						<td style="padding: 10px; border: 1px solid #ccc;"><strong>Folder 2026 di Hosting</strong></td>
						<td style="padding: 10px; border: 1px solid #ccc;">
							<?php 
							$dir_2026 = wp_upload_dir()['basedir'] . '/2026';
							if ( is_dir( $dir_2026 ) ) {
								echo '<span style="color: green; font-weight: bold;">✔ Terdeteksi (Folder Ada)</span><br>';
								$subdirs = array_filter( glob( $dir_2026 . '/*' ), 'is_dir' );
								if ( ! empty( $subdirs ) ) {
									echo 'Subfolder bulan: ' . esc_html( implode( ', ', array_map( 'basename', $subdirs ) ) ) . '<br>';
									$first_sub = $subdirs[0];
									$files = array_diff( scandir( $first_sub ), array( '.', '..' ) );
									echo 'Bulan ' . esc_html( basename( $first_sub ) ) . ' berisi ' . count( $files ) . ' file (Sample: ' . esc_html( implode( ', ', array_slice( $files, 0, 3 ) ) ) . ')';
								} else {
									echo '<span style="color: orange;">Folder 2026 kosong / tidak berisi subfolder bulan!</span>';
								}
							} else {
								echo '<span style="color: red; font-weight: bold;">❌ Tidak Terdeteksi (Folder Belum Ada / Salah Letak)</span>';
							}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}
