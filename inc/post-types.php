<?php
/**
 * Custom post types.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'sukusastra_register_post_types' );
function sukusastra_register_post_types(): void {
	sukusastra_register_review_buku_type();
	sukusastra_register_berita_type();
	sukusastra_register_event_type();
	sukusastra_register_penulis_type();
	sukusastra_register_terbitan_type();
}

function sukusastra_register_review_buku_type(): void {
	register_post_type(
		'review_buku',
		array(
			'labels'       => array(
				'name'          => __( 'Reviu Buku', 'sukusastra' ),
				'singular_name' => __( 'Reviu Buku', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Reviu Buku', 'sukusastra' ),
				'edit_item'     => __( 'Edit Reviu Buku', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-book-alt',
			'rewrite'      => array( 'slug' => 'review-buku' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			'taxonomies'   => array( 'category' ),
		)
	);
}

function sukusastra_register_berita_type(): void {
	register_post_type(
		'berita',
		array(
			'labels'       => array(
				'name'          => __( 'Peristiwa', 'sukusastra' ),
				'singular_name' => __( 'Peristiwa', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Peristiwa', 'sukusastra' ),
				'edit_item'     => __( 'Edit Peristiwa', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-megaphone',
			'rewrite'      => array( 'slug' => 'peristiwa' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
		)
	);
}

function sukusastra_register_event_type(): void {
	register_post_type(
		'event',
		array(
			'labels'       => array(
				'name'          => __( 'Event', 'sukusastra' ),
				'singular_name' => __( 'Event', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Event', 'sukusastra' ),
				'edit_item'     => __( 'Edit Event', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-calendar-alt',
			'rewrite'      => array( 'slug' => 'event' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
		)
	);
}

function sukusastra_register_penulis_type(): void {
	register_post_type(
		'penulis',
		array(
			'labels'       => array(
				'name'          => __( 'Penulis', 'sukusastra' ),
				'singular_name' => __( 'Penulis', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Penulis/Tokoh', 'sukusastra' ),
				'edit_item'     => __( 'Edit Penulis/Tokoh', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-admin-users',
			'rewrite'      => array( 'slug' => 'penulis' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		)
	);
}

function sukusastra_register_terbitan_type(): void {
	register_post_type(
		'terbitan',
		array(
			'labels'       => array(
				'name'          => __( 'Katalog Terbitan', 'sukusastra' ),
				'singular_name' => __( 'Terbitan', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Terbitan', 'sukusastra' ),
				'edit_item'     => __( 'Edit Terbitan', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-book-alt',
			'rewrite'      => array( 'slug' => 'katalog-terbitan' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			'taxonomies'   => array( 'category' ),
		)
	);
}
