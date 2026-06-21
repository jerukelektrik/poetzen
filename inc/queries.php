<?php
/**
 * Query helpers.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sukusastra_home_posts( string $category_slug, int $limit = 6, string $sort_by = 'terbaru' ): WP_Query {
	$home_visibility_query = array(
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
	);

	$args = array(
		'post_type'           => 'post',
		'posts_per_page'      => $limit,
		'category_name'       => $category_slug,
		'ignore_sticky_posts' => true,
		'meta_query'          => $home_visibility_query,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	if ( 'terpopuler' === $sort_by ) {
		$views_query = array(
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

		$args['meta_query'] = array(
			'relation' => 'AND',
			$home_visibility_query,
			$views_query,
		);
		$args['orderby'] = array(
			'views_clause' => 'DESC',
			'date'         => 'DESC',
		);
	}

	return new WP_Query( $args );
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

function sukusastra_text_matches_search_term( string $text, string $search ): bool {
	$search = trim( $search );
	if ( '' === $search ) {
		return false;
	}

	return (bool) preg_match( '/(^|[^\p{L}\p{N}])' . preg_quote( $search, '/' ) . '(?=$|[^\p{L}\p{N}])/iu', $text );
}

function sukusastra_find_penulis_ids_by_search( string $search ): array {
	global $wpdb;

	$search = trim( $search );
	if ( '' === $search ) {
		return array();
	}

	$ids = get_posts( array(
		'post_type'      => 'penulis',
		'posts_per_page' => -1,
		's'              => $search,
		'fields'         => 'ids',
		'post_status'    => 'publish',
	) );

	$title_like = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s AND post_title LIKE %s",
			'penulis',
			'publish',
			'%' . $wpdb->esc_like( $search ) . '%'
		)
	);

	$ids = array_values( array_unique( array_map( 'intval', array_merge( $ids, $title_like ) ) ) );
	return array_values( array_filter( $ids, function( int $penulis_id ) use ( $search ): bool {
		return sukusastra_text_matches_search_term( get_the_title( $penulis_id ), $search );
	} ) );
}

function sukusastra_find_posts_by_author_search( string $search ): array {
	global $wpdb;

	$search = trim( $search );
	if ( '' === $search ) {
		return array();
	}

	$post_ids = array();
	$penulis_ids = sukusastra_find_penulis_ids_by_search( $search );

	if ( ! empty( $penulis_ids ) ) {
		$linked_post_ids = get_posts( array(
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_ss_original_author_id',
					'value'   => $penulis_ids,
					'compare' => 'IN',
				),
			),
		) );
		$post_ids = array_merge( $post_ids, $linked_post_ids );
	}

	$matching_users = get_users( array(
		'search'         => '*' . $search . '*',
		'search_columns' => array( 'display_name', 'user_login', 'user_nicename' ),
		'fields'         => array( 'ID', 'display_name', 'user_login', 'user_nicename' ),
	) );
	$matching_user_ids = array();
	foreach ( $matching_users as $matching_user ) {
		$user_text = implode( ' ', array(
			$matching_user->display_name,
			$matching_user->user_login,
			$matching_user->user_nicename,
		) );
		if ( sukusastra_text_matches_search_term( $user_text, $search ) ) {
			$matching_user_ids[] = (int) $matching_user->ID;
		}
	}

	if ( ! empty( $matching_user_ids ) ) {
		$user_post_ids = get_posts( array(
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'author__in'     => $matching_user_ids,
		) );
		$post_ids = array_merge( $post_ids, $user_post_ids );
	}

	$title_post_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_status = %s AND post_title LIKE %s",
			'post',
			'publish',
			'%' . $wpdb->esc_like( $search ) . '%'
		)
	);
	$title_post_ids = array_filter( array_map( 'intval', $title_post_ids ), function( int $post_id ) use ( $search ): bool {
		return sukusastra_text_matches_search_term( get_the_title( $post_id ), $search );
	} );

	return array_values( array_unique( array_map( 'intval', array_merge( $post_ids, $title_post_ids ) ) ) );
}

function sukusastra_sort_author_search_post_ids( array $post_ids, int $priority_category_id = 0, string $sort_by = 'terbaru' ): array {
	$post_ids = array_values( array_unique( array_map( 'intval', $post_ids ) ) );
	if ( empty( $post_ids ) ) {
		return array();
	}

	$compare = function( int $first_id, int $second_id ) use ( $sort_by ): int {
		if ( 'abjad_a_z' === $sort_by || 'abjad_z_a' === $sort_by ) {
			$first_title  = strtolower( get_the_title( $first_id ) );
			$second_title = strtolower( get_the_title( $second_id ) );
			$result       = strcmp( $first_title, $second_title );
			return 'abjad_z_a' === $sort_by ? -$result : $result;
		}

		if ( 'terpopuler' === $sort_by ) {
			$first_views  = (int) get_post_meta( $first_id, '_ss_post_views', true );
			$second_views = (int) get_post_meta( $second_id, '_ss_post_views', true );
			if ( $first_views !== $second_views ) {
				return $second_views <=> $first_views;
			}
		}

		return get_post_time( 'U', true, $second_id ) <=> get_post_time( 'U', true, $first_id );
	};

	$priority_posts = array();
	$global_posts   = array();
	foreach ( $post_ids as $post_id ) {
		if ( $priority_category_id > 0 && has_category( $priority_category_id, $post_id ) ) {
			$priority_posts[] = $post_id;
		} else {
			$global_posts[] = $post_id;
		}
	}

	usort( $priority_posts, $compare );
	usort( $global_posts, $compare );

	return array_merge( $priority_posts, $global_posts );
}

function sukusastra_add_query_meta_clause( WP_Query $query, array $clause ): void {
	$meta_query = $query->get( 'meta_query' );
	if ( ! is_array( $meta_query ) ) {
		$meta_query = array();
	}

	if ( isset( $meta_query['relation'] ) ) {
		$query->set( 'meta_query', array(
			'relation' => 'AND',
			$meta_query,
			$clause,
		) );
		return;
	}

	$meta_query[] = $clause;
	$query->set( 'meta_query', $meta_query );
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
		$type_filter   = isset( $_GET['tipe_event'] ) ? sanitize_key( wp_unslash( $_GET['tipe_event'] ) ) : '';

		$meta_query = array( 'relation' => 'AND' );

		// 1. Filter by Status (Masih Berlangsung vs Sudah Berakhir)
		if ( 'ongoing' === $status_filter ) {
			// Masih Berlangsung: status is 'upcoming' AND end date has not passed (or not set)
			$meta_query[] = array(
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
			);
		} elseif ( 'ended' === $status_filter ) {
			// Sudah Berakhir: status is 'past', 'cancelled', OR end date has passed
			$meta_query[] = array(
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
			);
		}

		// 2. Filter by Type (Acara vs Sayembara)
		if ( 'acara' === $type_filter ) {
			$meta_query[] = array(
				'relation' => 'OR',
				array(
					'key'     => '_ss_event_type',
					'value'   => 'acara',
					'compare' => '=',
				),
				array(
					'key'     => '_ss_event_type',
					'compare' => 'NOT EXISTS',
				),
			);
		} elseif ( 'sayembara' === $type_filter ) {
			$meta_query[] = array(
				'key'     => '_ss_event_type',
				'value'   => 'sayembara',
				'compare' => '=',
			);
		}

		if ( count( $meta_query ) > 1 ) {
			$query->set( 'meta_query', $meta_query );
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

add_action( 'pre_get_posts', 'sukusastra_penulis_archive_filter' );
function sukusastra_penulis_archive_filter( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'penulis' ) ) {
		// 1. Keyword search filter
		$search = isset( $_GET['cari_penulis'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_penulis'] ) ) : '';
		if ( '' !== $search ) {
			$query->set( 's', $search );
		}

		// 2. Sorting filter
		$urut = isset( $_GET['urut_penulis'] ) ? sanitize_key( wp_unslash( $_GET['urut_penulis'] ) ) : 'a-z';
		if ( 'z-a' === $urut ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'DESC' );
		} else {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'ASC' );
		}
		
		// 3. Ensure we query enough authors per page
		$query->set( 'posts_per_page', 12 );
	}
}

add_action( 'pre_get_posts', 'sukusastra_archive_custom_filters' );
function sukusastra_archive_custom_filters( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_category() || is_tag() || is_tax() ) {
		if ( $query->is_category( array( 'puisi', 'cerpen', 'esai' ) ) ) {
			$query->set( 'posts_per_page', 15 );
		}

		// 1. Filter by author search
		$author_search = isset( $_GET['cari_penulis'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_penulis'] ) ) : '';
		$sort_by = isset( $_GET['sort_by'] ) ? sanitize_key( wp_unslash( $_GET['sort_by'] ) ) : 'terbaru';
		$is_author_search = '' !== $author_search;
		if ( '' !== $author_search ) {
			$matching_post_ids = sukusastra_find_posts_by_author_search( $author_search );

			if ( ! empty( $matching_post_ids ) ) {
				$priority_category_id = is_category() ? (int) get_queried_object_id() : 0;
				$matching_post_ids    = sukusastra_sort_author_search_post_ids( $matching_post_ids, $priority_category_id, $sort_by );

				if ( is_category() ) {
					$query->set( 'cat', '' );
					$query->set( 'category_name', '' );
					$query->set( 'category__in', array() );
					$query->set( 'category__and', array() );
				}

				$query->set( 'post__in', $matching_post_ids );
			} else {
				$query->set( 'post__in', array( 0 ) ); // Force empty results if no author matches
			}
		}

		// 2. Sorting
		if ( $is_author_search ) {
			$query->set( 'orderby', 'post__in' );
			$query->set( 'order', 'ASC' );
		} elseif ( 'abjad_a_z' === $sort_by ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'ASC' );
		} elseif ( 'abjad_z_a' === $sort_by ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'DESC' );
		} elseif ( 'terpopuler' === $sort_by ) {
			sukusastra_add_query_meta_clause( $query, array(
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
			) );
			$query->set( 'orderby', array(
				'views_clause' => 'DESC',
				'date'         => 'DESC',
			) );
		} else {
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'DESC' );
		}
	}
}

add_action( 'pre_get_posts', 'sukusastra_review_buku_archive_filter' );
function sukusastra_review_buku_archive_filter( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'review_buku' ) ) {
		// 1. Keyword search filter
		$search = isset( $_GET['cari_buku'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_buku'] ) ) : '';
		if ( '' !== $search ) {
			// Find post IDs matching title or content
			$post_ids_standard = get_posts( array(
				'post_type'      => 'review_buku',
				'posts_per_page' => -1,
				's'              => $search,
				'fields'         => 'ids',
				'post_status'    => 'publish',
			) );

			// Find post IDs matching meta fields: book title, book author, reviewer
			$post_ids_meta = get_posts( array(
				'post_type'      => 'review_buku',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'post_status'    => 'publish',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => '_ss_book_title',
						'value'   => $search,
						'compare' => 'LIKE',
					),
					array(
						'key'     => '_ss_book_author',
						'value'   => $search,
						'compare' => 'LIKE',
					),
					array(
						'key'     => '_ss_reviewer',
						'value'   => $search,
						'compare' => 'LIKE',
					),
				),
			) );

			// Find post IDs matching linked author (CPT penulis) name
			$matching_authors = get_posts( array(
				'post_type'      => 'penulis',
				'posts_per_page' => -1,
				's'              => $search,
				'fields'         => 'ids',
				'post_status'    => 'publish',
			) );

			$post_ids_linked_author = array();
			if ( ! empty( $matching_authors ) && ! is_wp_error( $matching_authors ) ) {
				$post_ids_linked_author = get_posts( array(
					'post_type'      => 'review_buku',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'     => '_ss_original_author_id',
							'value'   => $matching_authors,
							'compare' => 'IN',
						),
					),
				) );
			}

			// Find post IDs matching linked reviewer (CPT penulis) name
			$post_ids_linked_reviewer = array();
			if ( ! empty( $matching_authors ) && ! is_wp_error( $matching_authors ) ) {
				$post_ids_linked_reviewer = get_posts( array(
					'post_type'      => 'review_buku',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'     => '_ss_reviewer',
							'value'   => $matching_authors,
							'compare' => 'IN',
						),
					),
				) );
			}

			// Find post IDs matching linked book author (CPT penulis) name
			$post_ids_linked_book_author = array();
			if ( ! empty( $matching_authors ) && ! is_wp_error( $matching_authors ) ) {
				$post_ids_linked_book_author = get_posts( array(
					'post_type'      => 'review_buku',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'     => '_ss_book_author',
							'value'   => $matching_authors,
							'compare' => 'IN',
						),
					),
				) );
			}

			// Combine all matched IDs
			$combined_ids = array_unique( array_merge( $post_ids_standard, $post_ids_meta, $post_ids_linked_author, $post_ids_linked_reviewer, $post_ids_linked_book_author ) );

			if ( ! empty( $combined_ids ) ) {
				$query->set( 'post__in', $combined_ids );
			} else {
				$query->set( 'post__in', array( 0 ) ); // Force empty results
			}
		}

		// 2. Jenis Buku filter
		$jenis_buku = isset( $_GET['jenis_buku'] ) ? sanitize_key( wp_unslash( $_GET['jenis_buku'] ) ) : '';
		if ( in_array( $jenis_buku, array( 'puisi', 'cerpen', 'novel', 'nonfiksi' ), true ) ) {
			$meta_query = $query->get( 'meta_query' );
			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}
			$meta_query[] = array(
				'key'     => '_ss_book_type',
				'value'   => $jenis_buku,
				'compare' => '=',
			);
			$query->set( 'meta_query', $meta_query );
		}

		// 3. Sorting filter (sort_by)
		$sort_by = isset( $_GET['sort_by'] ) ? sanitize_key( wp_unslash( $_GET['sort_by'] ) ) : 'terbaru';
		if ( 'terpopuler' === $sort_by ) {
			$query->set( 'meta_key', '_ss_post_views' );
			$query->set( 'orderby', 'meta_value_num date' );
			$query->set( 'order', 'DESC' );
		} else {
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'DESC' );
		}

		// 4. Posts per page
		$query->set( 'posts_per_page', 8 );
	}
}

add_action( 'pre_get_posts', 'sukusastra_berita_archive_filter' );
function sukusastra_berita_archive_filter( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'berita' ) ) {
		// 1. Keyword search filter
		$search = isset( $_GET['cari_berita'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_berita'] ) ) : '';
		if ( '' !== $search ) {
			// Find post IDs matching title or content in CPT berita
			$post_ids_standard = get_posts( array(
				'post_type'      => 'berita',
				'posts_per_page' => -1,
				's'              => $search,
				'fields'         => 'ids',
				'post_status'    => 'publish',
			) );

			// Find post IDs matching linked author (CPT penulis) name
			$matching_authors = get_posts( array(
				'post_type'      => 'penulis',
				'posts_per_page' => -1,
				's'              => $search,
				'fields'         => 'ids',
				'post_status'    => 'publish',
			) );

			$post_ids_linked_author = array();
			if ( ! empty( $matching_authors ) && ! is_wp_error( $matching_authors ) ) {
				$post_ids_linked_author = get_posts( array(
					'post_type'      => 'berita',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'post_status'    => 'publish',
					'meta_query'     => array(
						array(
							'key'     => '_ss_original_author_id',
							'value'   => $matching_authors,
							'compare' => 'IN',
						),
					),
				) );
			}

			// Combine all matched IDs
			$combined_ids = array_unique( array_merge( $post_ids_standard, $post_ids_linked_author ) );

			if ( ! empty( $combined_ids ) ) {
				$query->set( 'post__in', $combined_ids );
			} else {
				$query->set( 'post__in', array( 0 ) ); // Force empty results
			}
		}

		// 2. Sorting filter (sort_by)
		$sort_by = isset( $_GET['sort_by'] ) ? sanitize_key( wp_unslash( $_GET['sort_by'] ) ) : 'terbaru';
		if ( 'terpopuler' === $sort_by ) {
			$query->set( 'meta_key', '_ss_post_views' );
			$query->set( 'orderby', 'meta_value_num date' );
			$query->set( 'order', 'DESC' );
		} else {
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'DESC' );
		}

		// 3. Posts per page (9 works perfectly for a 3-column grid)
		$query->set( 'posts_per_page', 9 );
	}
}

add_action( 'pre_get_posts', 'sukusastra_terbitan_archive_filter' );
function sukusastra_terbitan_archive_filter( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'terbitan' ) ) {
		// 1. Keyword search filter
		$search = isset( $_GET['cari_terbitan'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_terbitan'] ) ) : '';
		if ( '' !== $search ) {
			// Find post IDs matching title or content in CPT terbitan
			$post_ids_standard = get_posts( array(
				'post_type'      => 'terbitan',
				'posts_per_page' => -1,
				's'              => $search,
				'fields'         => 'ids',
				'post_status'    => 'publish',
			) );

			// Find post IDs matching author name meta field
			$post_ids_meta = get_posts( array(
				'post_type'      => 'terbitan',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'post_status'    => 'publish',
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => '_ss_book_author',
						'value'   => $search,
						'compare' => 'LIKE',
					),
					array(
						'key'     => '_ss_book_translator',
						'value'   => $search,
						'compare' => 'LIKE',
					),
				),
			) );

			// Combine all matched IDs
			$combined_ids = array_unique( array_merge( $post_ids_standard, $post_ids_meta ) );

			if ( ! empty( $combined_ids ) ) {
				$query->set( 'post__in', $combined_ids );
			} else {
				$query->set( 'post__in', array( 0 ) ); // Force empty results
			}
		}

		// 2. Sorting filter (sort_by)
		$sort_by = isset( $_GET['sort_by'] ) ? sanitize_key( wp_unslash( $_GET['sort_by'] ) ) : 'terbaru';
		if ( 'terpopuler' === $sort_by ) {
			$query->set( 'meta_key', '_ss_post_views' );
			$query->set( 'orderby', 'meta_value_num date' );
			$query->set( 'order', 'DESC' );
		} else {
			$query->set( 'orderby', 'date' );
			$query->set( 'order', 'DESC' );
		}

		// 3. Posts per page (12 works perfectly for a 4-column grid of book covers)
		$query->set( 'posts_per_page', 12 );
	}
}

function sukusastra_get_related_articles( int $post_id ): array {
	// 1. Check if manually selected
	$manual_ids_str = sukusastra_get_meta( $post_id, '_ss_related_post_id', '' );
	if ( $manual_ids_str ) {
		$manual_ids = array_filter( array_map( 'intval', explode( ',', $manual_ids_str ) ) );
		if ( ! empty( $manual_ids ) ) {
			$manual_posts = get_posts(
				array(
					'post_type'      => array( 'post', 'review_buku', 'berita' ),
					'post__in'       => $manual_ids,
					'posts_per_page' => count( $manual_ids ),
					'post_status'    => 'publish',
					'orderby'        => 'post__in', // preserve order selected in admin
				)
			);
			if ( ! empty( $manual_posts ) ) {
				return $manual_posts;
			}
		}
	}

	// 2. Otherwise, get automatically from the same category/post type
	$post_type = get_post_type( $post_id );
	$args = array(
		'post_type'           => $post_type,
		'posts_per_page'      => 2, // 2 posts by default for automatic fallback
		'post__not_in'        => array( $post_id ),
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	if ( 'post' === $post_type ) {
		$categories = get_the_category( $post_id );
		if ( ! empty( $categories ) ) {
			$args['category__in'] = array_map( function( $cat ) { return $cat->term_id; }, $categories );
		}
	}

	$related_query = new WP_Query( $args );
	$related_posts = array();
	if ( $related_query->have_posts() ) {
		$related_posts = $related_query->posts;
		wp_reset_postdata();
	}

	// 3. Fallback to standard post type if empty
	if ( empty( $related_posts ) && 'post' !== $post_type ) {
		$args['post_type'] = 'post';
		$fallback_query = new WP_Query( $args );
		if ( $fallback_query->have_posts() ) {
			$related_posts = $fallback_query->posts;
			wp_reset_postdata();
		}
	}

	return $related_posts;
}

function sukusastra_render_related_posts_block( array $related_posts ): string {
	if ( empty( $related_posts ) ) {
		return '';
	}

	$label = esc_html__( 'Baca Juga', 'sukusastra' );
	$items_html = '';

	foreach ( $related_posts as $related_post ) {
		$orig_author = sukusastra_get_original_author( $related_post->ID );
		if ( $orig_author ) {
			$author_name = $orig_author->post_title;
		} else {
			$author_post = get_post( $related_post->ID );
			$author_name = get_the_author_meta( 'display_name', $author_post->post_author );
		}

		$date = get_the_date( '', $related_post );

		$thumbnail_html = '';
		if ( has_post_thumbnail( $related_post->ID ) ) {
			$thumbnail_html = get_the_post_thumbnail( $related_post->ID, 'thumbnail', array( 'class' => 'w-full h-full object-cover hover:scale-105 transition-transform duration-500' ) );
		} else {
			$thumbnail_html = sprintf(
				'<div class="w-full h-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center text-slate-450 text-[10px] font-bold font-serif">%s</div>',
				esc_html( mb_substr( $related_post->post_title, 0, 2 ) )
			);
		}

		$title = esc_html( $related_post->post_title );
		$url   = esc_url( get_permalink( $related_post->ID ) );

		$is_multi = count( $related_posts ) > 1;
		$thumb_class = $is_multi ? 'w-14 h-14' : 'w-20 h-20';
		$title_class = $is_multi ? 'text-sm' : 'text-base';
		$meta_text_class = $is_multi ? 'text-[9px]' : 'text-[10px]';

		$items_html .= sprintf(
			'<div class="flex gap-3 items-center min-w-0">
				<a href="%1$s" class="block %2$s shrink-0 overflow-hidden rounded-xl border border-slate-200/50 dark:border-zinc-800/60 shadow-sm">
					%3$s
				</a>
				<div class="flex-1 min-w-0">
					<div class="%4$s font-bold uppercase tracking-wider text-slate-450 dark:text-zinc-550">
						<span class="text-slate-800 dark:text-zinc-200 font-bold">%5$s</span>
						<span class="text-slate-300 dark:text-zinc-700 font-normal">·</span>
						<span class="font-semibold normal-case text-slate-500 dark:text-zinc-400">%6$s</span>
					</div>
					<h5 class="%7$s font-black leading-snug text-slate-900 dark:text-zinc-50 mt-0.5 mb-0">
						<a href="%1$s" class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors">
							%8$s
						</a>
					</h5>
				</div>
			</div>',
			$url,
			$thumb_class,
			$thumbnail_html,
			$meta_text_class,
			esc_html( $author_name ),
			esc_html( $date ),
			$title_class,
			$title
		);
	}

	return sprintf(
		'<div class="ss-related-inline my-8 p-5 rounded-3xl border border-slate-200 bg-slate-100/85 dark:border-zinc-800 dark:bg-zinc-800/40 not-prose">
			<span class="text-red-700 dark:text-red-400 text-[10px] font-black uppercase tracking-wider block mb-3.5">%1$s</span>
			<div class="grid gap-4">
				%2$s
			</div>
		</div>',
		$label,
		$items_html
	);
}

add_shortcode( 'baca_juga', 'sukusastra_baca_juga_shortcode' );
function sukusastra_baca_juga_shortcode( array $atts = array() ): string {
	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return '';
	}

	$atts = shortcode_atts(
		array(
			'item' => '',
		),
		$atts,
		'baca_juga'
	);

	$related_posts = sukusastra_get_related_articles( $post_id );
	if ( empty( $related_posts ) ) {
		return '';
	}

	$selected_posts = array();
	if ( '' !== $atts['item'] ) {
		$indices = array_filter( array_map( 'intval', explode( ',', $atts['item'] ) ) );
		foreach ( $indices as $index ) {
			$zero_index = $index - 1;
			if ( isset( $related_posts[ $zero_index ] ) ) {
				$selected_posts[] = $related_posts[ $zero_index ];
			}
		}
	} else {
		$selected_posts = $related_posts;
	}

	return sukusastra_render_related_posts_block( $selected_posts );
}

// add_filter( 'the_content', 'sukusastra_inject_related_article' );
function sukusastra_inject_related_article( string $content ): string {
	if ( ! is_single() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$post_type = get_post_type();
	if ( ! in_array( $post_type, array( 'post', 'review_buku', 'berita' ), true ) ) {
		return $content;
	}

	// If the user already placed the shortcode manually, do not auto-inject
	if ( has_shortcode( $content, 'baca_juga' ) ) {
		return $content;
	}

	$related_posts = sukusastra_get_related_articles( get_the_ID() );
	if ( empty( $related_posts ) ) {
		return $content;
	}

	$first_post_html = sukusastra_render_related_posts_block( array( $related_posts[0] ) );
	$remaining_posts_html = '';
	if ( count( $related_posts ) > 1 ) {
		$remaining_posts = array_slice( $related_posts, 1 );
		$remaining_posts_html = sukusastra_render_related_posts_block( $remaining_posts );
	}

	preg_match_all( '/<p\b[^>]*>/i', $content, $paragraph_open_matches, PREG_OFFSET_CAPTURE );
	preg_match_all( '/<\/p>/i', $content, $paragraph_close_matches, PREG_OFFSET_CAPTURE );

	$paragraph_openings = $paragraph_open_matches[0];
	$paragraph_closings = $paragraph_close_matches[0];
	$paragraph_count = min( count( $paragraph_openings ), count( $paragraph_closings ) );

	if ( 0 === $paragraph_count ) {
		return $content . $first_post_html . $remaining_posts_html;
	}

	$insertions = array();
	$first_insert_index = min( 1, $paragraph_count - 1 );
	$insertions[] = array(
		'offset' => $paragraph_closings[ $first_insert_index ][1] + strlen( $paragraph_closings[ $first_insert_index ][0] ),
		'html'   => $first_post_html,
	);

	if ( '' !== $remaining_posts_html ) {
		if ( $paragraph_count > 2 ) {
			$second_insert_index = max( 0, $paragraph_count - 2 );
			$second_insert_offset = $paragraph_openings[ $second_insert_index ][1];
		} else {
			$second_insert_offset = strlen( $content );
		}

		$insertions[] = array(
			'offset' => $second_insert_offset,
			'html'   => $remaining_posts_html,
		);
	}

	usort( $insertions, function( array $first, array $second ): int {
		return $second['offset'] <=> $first['offset'];
	} );

	$new_content = $content;
	foreach ( $insertions as $insertion ) {
		$new_content = substr_replace( $new_content, $insertion['html'], $insertion['offset'], 0 );
	}

	return $new_content;
}

add_action( 'pre_get_posts', 'sukusastra_komunitas_archive_filter' );
function sukusastra_komunitas_archive_filter( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'komunitas' ) ) {
		$cari_komunitas = isset( $_GET['cari_komunitas'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_komunitas'] ) ) : '';
		$filter_prov    = isset( $_GET['filter_prov'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_prov'] ) ) : '';

		$meta_query = array();

		if ( '' !== $filter_prov ) {
			$meta_query[] = array(
				'key'     => '_ss_comm_province',
				'value'   => $filter_prov,
				'compare' => 'LIKE',
			);
		}

		if ( '' !== $cari_komunitas ) {
			// Find post IDs matching title or content, or city, in CPT komunitas
			$post_ids_standard = get_posts( array(
				'post_type'      => 'komunitas',
				'posts_per_page' => -1,
				's'              => $cari_komunitas,
				'fields'         => 'ids',
				'post_status'    => 'publish',
			) );

			$post_ids_city = get_posts( array(
				'post_type'      => 'komunitas',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'     => '_ss_comm_city',
						'value'   => $cari_komunitas,
						'compare' => 'LIKE',
					),
				),
			) );

			$combined_ids = array_unique( array_merge( $post_ids_standard, $post_ids_city ) );

			if ( ! empty( $combined_ids ) ) {
				$query->set( 'post__in', $combined_ids );
			} else {
				$query->set( 'post__in', array( 0 ) ); // Force empty results
			}
		}

		if ( ! empty( $meta_query ) ) {
			$query->set( 'meta_query', $meta_query );
		}

		$query->set( 'posts_per_page', 9 );
	}
}
