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

	// Query posts
	$query_args = array(
		'post_type'      => array( 'post', 'review_buku', 'berita', 'event', 'terbitan', 'penulis' ),
		'posts_per_page' => -1,
		'post_status'    => 'any',
	);

	$posts = get_posts( $query_args );
	$export_data = array();

	foreach ( $posts as $p ) {
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

		$export_data[] = array(
			'title'               => $p->post_title,
			'slug'                => $p->post_name,
			'content'             => $p->post_content,
			'excerpt'             => $p->post_excerpt,
			'date'                => $p->post_date,
			'post_type'           => $p->post_type,
			'categories'          => $categories,
			'featured_image_path' => $featured_image_path,
			'metas'               => $custom_metas,
		);
	}

	$json_content = wp_json_encode( $export_data, JSON_PRETTY_PRINT );

	header( 'Content-Description: File Transfer' );
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename="sukusastra_migration_' . date( 'Ymd_His' ) . '.json"' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate' );
	header( 'Pragma: public' );
	header( 'Content-Length: ' . strlen( $json_content ) );
	
	echo $json_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit;
}

/**
 * Render the Migration Page.
 */
function sukusastra_render_migration_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$import_success = false;
	$imported_count = 0;
	$skipped_count  = 0;
	$error_msg      = '';

	if ( isset( $_POST['sukusastra_import_json_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sukusastra_import_json_nonce'] ) ), 'sukusastra_do_json_import' ) ) {
		if ( ! empty( $_FILES['migration_json']['tmp_name'] ) ) {
			$file = sanitize_text_field( wp_unslash( $_FILES['migration_json']['tmp_name'] ) );
			$json_raw = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$data = json_decode( $json_raw, true );

			if ( is_array( $data ) ) {
				// We need to import 'penulis' CPT first so we can map other posts to them later
				// Split into penulis items and other items
				$penulis_items = array();
				$other_items   = array();

				foreach ( $data as $item ) {
					if ( 'penulis' === $item['post_type'] ) {
						$penulis_items[] = $item;
					} else {
						$other_items[] = $item;
					}
				}

				// Import function
				$process_item = function( $item ) use ( &$imported_count, &$skipped_count ) {
					$slug = sanitize_title( $item['slug'] );
					
					// Skip duplicate slug
					$existing = new WP_Query( array(
						'post_type'      => $item['post_type'],
						'name'           => $slug,
						'posts_per_page' => 1,
						'post_status'    => 'any',
					) );

					if ( $existing->have_posts() ) {
						$skipped_count++;
						return;
					}

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

						$imported_count++;
					}
				};

				// Process penulis CPT first
				foreach ( $penulis_items as $item ) {
					$process_item( $item );
				}

				// Process all other items
				foreach ( $other_items as $item ) {
					$process_item( $item );
				}

				$import_success = true;
			} else {
				$error_msg = __( 'Gagal membaca format JSON. Pastikan file valid.', 'sukusastra' );
			}
		} else {
			$error_msg = __( 'Silakan unggah file JSON hasil ekspor terlebih dahulu.', 'sukusastra' );
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Migrasi Database Suku Sastra', 'sukusastra' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'Sistem kustom untuk memindahkan artikel, custom post types, kategori, dan metadata antar server menggunakan file JSON super ringan.', 'sukusastra' ); ?>
		</p>

		<div style="display: flex; gap: 20px; margin-top: 20px; align-items: start;">
			
			<!-- Export Box -->
			<div class="card" style="flex: 1; padding: 20px; background: #fff; border-radius: 8px;">
				<h2><?php esc_html_e( '1. Ekspor Data (Lokal)', 'sukusastra' ); ?></h2>
				<p><?php esc_html_e( 'Gunakan tombol di bawah ini di website lokal Anda untuk mengunduh semua artikel dan pengaturannya ke dalam file JSON terkompresi.', 'sukusastra' ); ?></p>
				<p>
					<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'tools.php?page=sukusastra_migration&action=export_json' ), 'sukusastra_export_json_action', 'nonce' ) ); ?>" class="button button-primary button-large" style="background:#b42318; border-color:#b42318;">
						<?php esc_html_e( 'Download JSON Export', 'sukusastra' ); ?>
					</a>
				</p>
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
	</div>
	<?php
}
