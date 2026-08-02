<?php
/**
 * Category migration helper script.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'sukusastra_migrate_film_to_esai' );
function sukusastra_migrate_film_to_esai(): void {
	if ( isset( $_GET['run_film_migration'] ) && '1' === $_GET['run_film_migration'] ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Anda tidak memiliki izin untuk menjalankan migrasi ini.', 'sukusastra' ) );
		}

		$film_term = get_category_by_slug( 'film' );
		$esai_term = get_category_by_slug( 'esai' );

		if ( ! $film_term || ! $esai_term ) {
			wp_die( esc_html__( 'Kategori "film" atau "esai" tidak ditemukan.', 'sukusastra' ) );
		}

		$posts = get_posts( array(
			'category'       => $film_term->term_id,
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'post_status'    => 'any',
		) );

		if ( empty( $posts ) ) {
			wp_die( esc_html__( 'Tidak ada postingan di kategori "film". Migrasi mungkin sudah selesai sebelumnya.', 'sukusastra' ) );
		}

		$migrated = array();
		foreach ( $posts as $p ) {
			$current_cats = wp_get_post_categories( $p->ID );
			
			// Remove film and add esai
			$new_cats = array_diff( $current_cats, array( $film_term->term_id ) );
			$new_cats[] = $esai_term->term_id;
			$new_cats = array_unique( $new_cats );
			
			wp_set_post_categories( $p->ID, $new_cats );
			
			$migrated[] = sprintf( '- ID: %d | Title: <strong>%s</strong>', $p->ID, esc_html( $p->post_title ) );
		}

		$output = '<h2>Migrasi Kategori Film Berhasil!</h2>';
		$output .= '<p>Berhasil memindahkan ' . count( $migrated ) . ' postingan dari kategori "film" ke "esai":</p>';
		$output .= '<div style="background: #f5f5f5; border: 1px solid #ddd; padding: 15px; border-radius: 6px; font-family: monospace; max-height: 300px; overflow-y: auto;">' . implode( '<br>', $migrated ) . '</div>';
		$output .= '<p style="margin-top: 20px;"><a href="' . esc_url( admin_url( 'edit.php' ) ) . '" class="button button-primary" style="display: inline-block; background: #2271b1; color: #fff; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-family: sans-serif;">Kembali ke Dashboard</a></p>';

		wp_die( $output );
	}
}
