<?php
/**
 * Query helpers.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sukusastra_home_posts( string $category_slug, int $limit = 6 ): WP_Query {
	return new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => $limit,
			'category_name'       => $category_slug,
			'ignore_sticky_posts' => true,
			'meta_query'          => array(
				'relation' => 'OR',
				array(
					'key'     => '_ss_show_home',
					'value'   => '1',
					'compare' => '=',
				),
				array(
					'key'     => '_ss_show_home',
					'compare' => 'NOT EXISTS',
				),
			),
		)
	);
}

function sukusastra_latest_cpt( string $post_type, int $limit = 4 ): WP_Query {
	return new WP_Query(
		array(
			'post_type'           => $post_type,
			'posts_per_page'      => $limit,
			'ignore_sticky_posts' => true,
		)
	);
}

function sukusastra_upcoming_events( int $limit = 4 ): WP_Query {
	return new WP_Query(
		array(
			'post_type'      => 'event',
			'posts_per_page' => $limit,
			'meta_key'       => '_ss_event_start',
			'orderby'        => 'meta_value',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => '_ss_event_status',
					'value'   => 'upcoming',
					'compare' => '=',
				),
			),
		)
	);
}

function sukusastra_related_posts( int $post_id, int $limit = 4 ): WP_Query {
	$categories = wp_get_post_categories( $post_id );
	return new WP_Query(
		array(
			'post_type'      => get_post_type( $post_id ),
			'posts_per_page' => $limit,
			'post__not_in'   => array( $post_id ),
			'category__in'   => $categories,
		)
	);
}

function sukusastra_filter_args_from_request(): array {
	$post_type  = isset( $_GET['jenis_konten'] ) ? sanitize_key( wp_unslash( $_GET['jenis_konten'] ) ) : '';
	$category   = isset( $_GET['karya'] ) ? sanitize_key( wp_unslash( $_GET['karya'] ) ) : '';
	$sort_by    = isset( $_GET['sort_by'] ) ? sanitize_key( wp_unslash( $_GET['sort_by'] ) ) : 'terbaru';
	$sort_order = isset( $_GET['sort_order'] ) ? sanitize_key( wp_unslash( $_GET['sort_order'] ) ) : 'desc';

	if ( ! in_array( $sort_by, array( 'terbaru', 'terpopuler' ), true ) ) {
		$sort_by = 'terbaru';
	}
	if ( ! in_array( $sort_order, array( 'asc', 'desc' ), true ) ) {
		$sort_order = 'desc';
	}

	$order = strtoupper( $sort_order );

	$args = array(
		'post_type'      => in_array( $post_type, array( 'post', 'review_buku', 'berita', 'event' ), true ) ? $post_type : array( 'post', 'review_buku', 'berita', 'event' ),
		'posts_per_page' => 10,
		'paged'          => max( 1, get_query_var( 'paged' ) ),
	);

	if ( '' !== $category ) {
		$args['category_name'] = $category;
	}

	if ( 'terpopuler' === $sort_by ) {
		$views_meta_query = array(
			'relation' => 'OR',
			'views_clause' => array(
				'key'     => '_ss_post_views',
				'compare' => 'EXISTS',
				'type'    => 'NUMERIC',
			),
			'no_views_clause' => array(
				'key'     => '_ss_post_views',
				'compare' => 'NOT EXISTS',
			),
		);
		if ( isset( $args['meta_query'] ) && is_array( $args['meta_query'] ) ) {
			$args['meta_query'] = array(
				'relation' => 'AND',
				$args['meta_query'],
				$views_meta_query
			);
		} else {
			$args['meta_query'] = $views_meta_query;
		}

		$args['orderby'] = array(
			'views_clause' => $order,
			'date'         => $order,
		);
	} else {
		$args['orderby'] = 'date';
		$args['order']   = $order;
	}


	if ( is_search() ) {
		$search_query = get_search_query();
		if ( '' !== $search_query ) {
			// 1. Search for posts with standard keyword search
			$search_post_ids = get_posts(
				array(
					'post_type'      => $args['post_type'],
					'posts_per_page' => -1,
					's'              => $search_query,
					'fields'         => 'ids',
					'post_status'    => 'publish',
				)
			);

			// 2. Search for authors matching the keyword
			$matching_authors = get_posts(
				array(
					'post_type'      => 'penulis',
					'posts_per_page' => -1,
					's'              => $search_query,
					'fields'         => 'ids',
					'post_status'    => 'publish',
				)
			);

			$author_post_ids = array();
			if ( ! empty( $matching_authors ) && ! is_wp_error( $matching_authors ) ) {
				// Get posts written by these authors
				$author_post_ids = get_posts(
					array(
						'post_type'      => $args['post_type'],
						'posts_per_page' => -1,
						'meta_query'     => array(
							array(
								'key'     => '_ss_original_author_id',
								'value'   => $matching_authors,
								'compare' => 'IN',
							),
						),
						'fields'         => 'ids',
						'post_status'    => 'publish',
					)
				);
			}

			// Merge post IDs
			$combined_ids = array_unique( array_merge( $search_post_ids, $author_post_ids ) );

			if ( ! empty( $combined_ids ) ) {
				$args['post__in'] = $combined_ids;
			} else {
				$args['post__in'] = array( 0 ); // Force empty results
			}
		}
	}

	return $args;
}

add_action( 'pre_get_posts', 'sukusastra_event_archive_filter' );
function sukusastra_event_archive_filter( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'event' ) ) {
		$status_filter = isset( $_GET['status_event'] ) ? sanitize_key( wp_unslash( $_GET['status_event'] ) ) : '';

		if ( 'ongoing' === $status_filter ) {
			// Masih Berlangsung: status is 'upcoming' AND end date has not passed (or not set)
			$query->set( 'meta_query', array(
				'relation' => 'AND',
				array(
					'key'     => '_ss_event_status',
					'value'   => 'upcoming',
					'compare' => '=',
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => '_ss_event_end',
						'value'   => date( 'Y-m-d' ),
						'compare' => '>=',
						'type'    => 'DATE',
					),
					array(
						'key'     => '_ss_event_end',
						'compare' => 'NOT EXISTS',
					),
				),
			) );
		} elseif ( 'ended' === $status_filter ) {
			// Sudah Berakhir: status is 'past', 'cancelled', OR end date has passed
			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key'     => '_ss_event_status',
					'value'   => array( 'past', 'cancelled' ),
					'compare' => 'IN',
				),
				array(
					'key'     => '_ss_event_end',
					'value'   => date( 'Y-m-d' ),
					'compare' => '<',
					'type'    => 'DATE',
				),
			) );
		}
	}
}

function sukusastra_sidebar_latest_posts( int $exclude_id = 0, int $limit = 5 ): WP_Query {
	$args = array(
		'post_type'           => array( 'post', 'review_buku', 'berita', 'event' ),
		'posts_per_page'      => $limit,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'post_status'         => 'publish',
	);
	if ( $exclude_id > 0 ) {
		$args['post__not_in'] = array( $exclude_id );
	}
	return new WP_Query( $args );
}

function sukusastra_sidebar_popular_posts( int $exclude_id = 0, int $limit = 5 ): WP_Query {
	$args = array(
		'post_type'           => array( 'post', 'review_buku', 'berita', 'event' ),
		'posts_per_page'      => $limit,
		'ignore_sticky_posts' => true,
		'meta_key'            => '_ss_post_views',
		'orderby'             => 'meta_value_num',
		'order'               => 'DESC',
		'post_status'         => 'publish',
	);
	if ( $exclude_id > 0 ) {
		$args['post__not_in'] = array( $exclude_id );
	}
	return new WP_Query( $args );
}
