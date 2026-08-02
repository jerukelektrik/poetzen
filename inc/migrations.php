<?php
/**
 * Content migration helper scripts.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'sukusastra_migrate_acara_to_peristiwa' );
function sukusastra_migrate_acara_to_peristiwa(): void {
	if ( isset( $_GET['run_acara_migration'] ) && '1' === $_GET['run_acara_migration'] ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Anda tidak memiliki izin untuk menjalankan migrasi ini.', 'sukusastra' ) );
		}

		$term = get_category_by_slug( 'acara' );
		if ( ! $term ) {
			wp_die( esc_html__( 'Kategori dengan slug "acara" tidak ditemukan.', 'sukusastra' ) );
		}

		$posts = get_posts( array(
			'category'       => $term->term_id,
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'post_status'    => 'any',
		) );

		if ( empty( $posts ) ) {
			wp_die( esc_html__( 'Tidak ada tulisan (post) yang ditemukan di kategori "acara". Migrasi mungkin sudah selesai sebelumnya.', 'sukusastra' ) );
		}

		$migrated = array();
		foreach ( $posts as $p ) {
			// 1. Change post type to CPT 'berita' (Peristiwa)
			set_post_type( $p->ID, 'berita' );
			
			// 2. Remove term relationships for categories so they don't stay associated with standard categories
			wp_set_object_terms( $p->ID, null, 'category' );
			
			$migrated[] = sprintf( '- ID: %d | Title: <strong>%s</strong> (%s)', $p->ID, esc_html( $p->post_title ), esc_html( $p->post_status ) );
		}

		$output = '<h2>Migrasi Berhasil!</h2>';
		$output .= '<p>Berhasil memindahkan ' . count( $migrated ) . ' postingan dari kategori "acara" ke Custom Post Type (CPT) <strong>Peristiwa</strong> (berita):</p>';
		$output .= '<div style="background: #f5f5f5; border: 1px solid #ddd; padding: 15px; border-radius: 6px; font-family: monospace; max-height: 300px; overflow-y: auto;">' . implode( '<br>', $migrated ) . '</div>';
		$output .= '<p style="margin-top: 20px;"><a href="' . esc_url( admin_url( 'edit.php?post_type=berita' ) ) . '" class="button button-primary" style="display: inline-block; background: #2271b1; color: #fff; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-family: sans-serif;">Lihat di Daftar Peristiwa</a></p>';

		wp_die( $output );
	}
}
