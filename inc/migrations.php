<?php
/**
 * Category migration helper script.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'sukusastra_migrate_prosa_to_cerpen' );
function sukusastra_migrate_prosa_to_cerpen(): void {
	if ( isset( $_GET['run_prosa_migration'] ) && '1' === $_GET['run_prosa_migration'] ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Anda tidak memiliki izin untuk menjalankan migrasi ini.', 'sukusastra' ) );
		}

		$prosa_term = get_category_by_slug( 'prosa' );
		$cerpen_term = get_category_by_slug( 'cerpen' );

		if ( ! $prosa_term || ! $cerpen_term ) {
			wp_die( esc_html__( 'Kategori "prosa" atau "cerpen" tidak ditemukan.', 'sukusastra' ) );
		}

		$posts = get_posts( array(
			'category'       => $prosa_term->term_id,
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'post_status'    => 'any',
		) );

		if ( empty( $posts ) ) {
			wp_die( esc_html__( 'Tidak ada postingan di kategori "prosa". Migrasi mungkin sudah selesai sebelumnya.', 'sukusastra' ) );
		}

		$migrated = array();
		foreach ( $posts as $p ) {
			$current_cats = wp_get_post_categories( $p->ID );
			
			// Remove prosa and add cerpen
			$new_cats = array_diff( $current_cats, array( $prosa_term->term_id ) );
			$new_cats[] = $cerpen_term->term_id;
			$new_cats = array_unique( $new_cats );
			
			wp_set_post_categories( $p->ID, $new_cats );
			
			$migrated[] = sprintf( '- ID: %d | Title: <strong>%s</strong>', $p->ID, esc_html( $p->post_title ) );
		}

		$output = '<h2>Migrasi Kategori Berhasil!</h2>';
		$output .= '<p>Berhasil memindahkan ' . count( $migrated ) . ' postingan dari kategori "prosa" ke "cerpen":</p>';
		$output .= '<div style="background: #f5f5f5; border: 1px solid #ddd; padding: 15px; border-radius: 6px; font-family: monospace; max-height: 300px; overflow-y: auto;">' . implode( '<br>', $migrated ) . '</div>';
		$output .= '<p style="margin-top: 20px;"><a href="' . esc_url( admin_url( 'edit.php' ) ) . '" class="button button-primary" style="display: inline-block; background: #2271b1; color: #fff; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-family: sans-serif;">Kembali ke Dashboard</a></p>';

		wp_die( $output );
	}
}
