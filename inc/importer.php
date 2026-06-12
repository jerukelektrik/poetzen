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

					// Insert post
					$post_id = wp_insert_post( array(
						'post_title'  => $name,
						'post_name'   => $slug,
						'post_type'   => 'penulis',
						'post_status' => 'publish',
					) );

					if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
						// Store additional metadata if available
						if ( isset( $header_map['Email'] ) && isset( $data[ $header_map['Email'] ] ) ) {
							update_post_meta( $post_id, '_ss_penulis_email', sanitize_email( $data[ $header_map['Email'] ] ) );
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
			<?php esc_html_e( 'Gunakan halaman ini untuk mendaftarkan akun Penulis secara masal langsung ke dalam Custom Post Type Penulis melalui file spreadsheet (.csv).', 'sukusastra' ); ?>
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
								<?php esc_html_e( 'File harus berupa format .csv dengan header kolom minimal "Display Name/Nama Penulis" dan "Username/Slug".', 'sukusastra' ); ?>
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
