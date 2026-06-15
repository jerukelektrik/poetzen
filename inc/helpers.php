<?php
/**
 * Shared theme helpers.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUKUSASTRA_TESTING' ) ) {
	exit;
}

/**
 * Fallback primary menu output when no menu is configured.
 */
function sukusastra_primary_menu_fallback(): void {
	$items = array(
		'Puisi'       => home_url( '/category/puisi/' ),
		'Cerpen'      => home_url( '/category/cerpen/' ),
		'Esai'        => home_url( '/category/esai/' ),
		'Review Buku' => home_url( '/review-buku/' ),
		'Berita'      => home_url( '/berita/' ),
		'Event'       => home_url( '/event/' ),
	);

	echo '<ul class="flex flex-col gap-4 ss-nav-text md:flex-row md:items-center md:gap-8">';
	foreach ( $items as $label => $url ) {
		printf(
			'<li><a class="no-underline text-slate-700 hover:text-red-700 dark:text-zinc-300 dark:hover:text-red-300" href="%s">%s</a></li>',
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}

function sukusastra_get_meta( int $post_id, string $key, string $default = '' ): string {
	$value = get_post_meta( $post_id, $key, true );
	return is_string( $value ) && '' !== $value ? $value : $default;
}

function sukusastra_checked( string $value, string $expected ): string {
	return checked( $value, $expected, false );
}

function sukusastra_selected( string $value, string $expected ): string {
	return selected( $value, $expected, false );
}

function sukusastra_cta_label( string $primary, string $fallback ): string {
	$primary = trim( $primary );
	if ( '' !== $primary ) {
		return $primary;
	}

	return trim( $fallback );
}

function sukusastra_get_author_category_id(): int {
	return 0;
}

function sukusastra_get_original_author( int $post_id ): ?WP_Post {
	$author_id = get_post_meta( $post_id, '_ss_original_author_id', true );
	if ( ! empty( $author_id ) ) {
		$author_post = get_post( (int) $author_id );
		if ( $author_post && 'penulis' === $author_post->post_type ) {
			return $author_post;
		}
	}
	return null;
}

function sukusastra_find_penulis_by_name( string $name ): ?WP_Post {
	$name = trim( wp_strip_all_tags( $name ) );
	if ( '' === $name ) {
		return null;
	}

	$matches = get_posts(
		array(
			'post_type'      => 'penulis',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			's'              => $name,
		)
	);

	foreach ( $matches as $match ) {
		if ( 0 === strcasecmp( trim( $match->post_title ), $name ) ) {
			return $match;
		}
	}

	return null;
}

function sukusastra_is_author_category( int $cat_id ): bool {
	return false;
}

function sukusastra_get_post_type_label( int $post_id ): string {
	$post_type = get_post_type( $post_id );
	if ( 'post' === $post_type ) {
		$categories = get_the_category( $post_id );
		if ( ! empty( $categories ) ) {
			return $categories[0]->name;
		}
		return esc_html__( 'Tulisan', 'sukusastra' );
	} elseif ( 'review_buku' === $post_type ) {
		return esc_html__( 'Review Buku', 'sukusastra' );
	} elseif ( 'berita' === $post_type ) {
		return esc_html__( 'Berita', 'sukusastra' );
	} elseif ( 'event' === $post_type ) {
		return esc_html__( 'Event', 'sukusastra' );
	} elseif ( 'terbitan' === $post_type ) {
		return esc_html__( 'Katalog Terbitan', 'sukusastra' );
	}
	$obj = get_post_type_object( $post_type );
	return $obj ? esc_html( $obj->labels->singular_name ) : esc_html__( 'Artikel', 'sukusastra' );
}

function sukusastra_breadcrumbs(): void {
	if ( is_front_page() || is_home() ) {
		return;
	}

	echo '<nav class="ss-breadcrumbs mb-6 flex flex-wrap items-center gap-2 text-xs font-bold text-slate-500 dark:text-zinc-400" aria-label="Breadcrumb">';
	
	// Home link (gray pill with home icon)
	printf(
		'<a href="%1$s" class="inline-flex items-center gap-1.5 rounded-full bg-slate-200/60 px-3 py-1.5 no-underline hover:bg-slate-300 text-slate-700 dark:bg-[#262B4E]/60 dark:hover:bg-[#262B4E] dark:text-zinc-200 transition-colors shadow-sm">%2$s<span>%3$s</span></a>',
		esc_url( home_url( '/' ) ),
		'<svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>',
		esc_html__( 'Home', 'sukusastra' )
	);

	$separator = '<span class="text-slate-300 dark:text-zinc-700">/</span>';

	if ( is_singular() ) {
		$post_id   = get_the_ID();
		$post_type = get_post_type();

		if ( 'post' !== $post_type && 'page' !== $post_type ) {
			$post_type_obj = get_post_type_object( $post_type );
			$archive_link  = get_post_type_archive_link( $post_type );
			$cpt_label     = $post_type_obj->labels->singular_name;

			if ( 'review_buku' === $post_type ) {
				$cpt_label    = __( 'Review Buku', 'sukusastra' );
				$archive_link = home_url( '/review-buku/' );
			} elseif ( 'berita' === $post_type ) {
				$cpt_label    = __( 'Berita', 'sukusastra' );
				$archive_link = home_url( '/berita/' );
			} elseif ( 'event' === $post_type ) {
				$cpt_label    = __( 'Event', 'sukusastra' );
				$archive_link = home_url( '/event/' );
			} elseif ( 'penulis' === $post_type ) {
				$cpt_label    = __( 'Penulis', 'sukusastra' );
				$archive_link = home_url( '/penulis/' );
			} elseif ( 'terbitan' === $post_type ) {
				$cpt_label    = __( 'Katalog Terbitan', 'sukusastra' );
				$archive_link = home_url( '/katalog-terbitan/' );
			}

			echo $separator;
			printf(
				'<a href="%1$s" class="rounded-full bg-slate-200/60 px-3 py-1.5 no-underline hover:bg-slate-300 text-slate-700 dark:bg-[#262B4E]/60 dark:hover:bg-[#262B4E] dark:text-zinc-200 transition-colors shadow-sm">%2$s</a>',
				esc_url( $archive_link ),
				esc_html( $cpt_label )
			);
		} elseif ( 'post' === $post_type ) {
			$categories = get_the_category( $post_id );
			if ( ! empty( $categories ) ) {
				$cat = $categories[0];
				echo $separator;
				printf(
					'<a href="%1$s" class="rounded-full bg-slate-200/60 px-3 py-1.5 no-underline hover:bg-slate-300 text-slate-700 dark:bg-[#262B4E]/60 dark:hover:bg-[#262B4E] dark:text-zinc-200 transition-colors shadow-sm">%2$s</a>',
					esc_url( get_category_link( $cat->term_id ) ),
					esc_html( $cat->name )
				);
			}
		} elseif ( 'page' === $post_type ) {
			$post = get_post( $post_id );
			if ( $post->post_parent ) {
				$anc = array_reverse( get_post_ancestors( $post->ID ) );
				foreach ( $anc as $ancestor ) {
					echo $separator;
					printf(
						'<a href="%1$s" class="rounded-full bg-slate-200/60 px-3 py-1.5 no-underline hover:bg-slate-300 text-slate-700 dark:bg-[#262B4E]/60 dark:hover:bg-[#262B4E] dark:text-zinc-200 transition-colors shadow-sm">%2$s</a>',
						esc_url( get_permalink( $ancestor ) ),
						esc_html( get_the_title( $ancestor ) )
					);
				}
			}
		}

		echo $separator;
		printf(
			'<span class="rounded-full bg-slate-200/20 px-3 py-1.5 text-slate-400 dark:bg-[#262B4E]/20 dark:text-zinc-500 max-w-[200px] truncate shadow-inner">%1$s</span>',
			esc_html( get_the_title() )
		);

	} elseif ( is_category() ) {
		echo $separator;
		printf(
			'<span class="rounded-full bg-slate-200/20 px-3 py-1.5 text-slate-400 dark:bg-[#262B4E]/20 dark:text-zinc-500 shadow-inner">%1$s %2$s</span>',
			esc_html__( 'Kategori:', 'sukusastra' ),
			esc_html( single_cat_title( '', false ) )
		);
	} elseif ( is_tag() ) {
		echo $separator;
		printf(
			'<span class="rounded-full bg-slate-200/20 px-3 py-1.5 text-slate-400 dark:bg-[#262B4E]/20 dark:text-zinc-500 shadow-inner">%1$s %2$s</span>',
			esc_html__( 'Topik:', 'sukusastra' ),
			esc_html( single_tag_title( '', false ) )
		);
	} elseif ( is_search() ) {
		echo $separator;
		printf(
			'<span class="rounded-full bg-slate-200/20 px-3 py-1.5 text-slate-400 dark:bg-[#262B4E]/20 dark:text-zinc-500 shadow-inner">%1$s "%2$s"</span>',
			esc_html__( 'Cari:', 'sukusastra' ),
			esc_html( get_search_query() )
		);
	} elseif ( is_post_type_archive() ) {
		$post_type_obj = get_queried_object();
		$label         = $post_type_obj->labels->singular_name;
		if ( 'event' === $post_type_obj->name ) {
			$label = __( 'Event', 'sukusastra' );
		}
		echo $separator;
		printf(
			'<span class="rounded-full bg-slate-200/20 px-3 py-1.5 text-slate-400 dark:bg-[#262B4E]/20 dark:text-zinc-500 shadow-inner">%1$s</span>',
			esc_html( $label )
		);
	} elseif ( is_archive() ) {
		echo $separator;
		printf(
			'<span class="rounded-full bg-slate-200/20 px-3 py-1.5 text-slate-400 dark:bg-[#262B4E]/20 dark:text-zinc-500 shadow-inner">%1$s</span>',
			esc_html( get_the_archive_title() )
		);
	}

	echo '</nav>';
}

/**
 * Render custom paginated navigation with SVG icons.
 */
function sukusastra_pagination(): void {
	the_posts_pagination( array(
		'prev_text'          => '<span class="sr-only">' . esc_html__( 'Sebelumnya', 'sukusastra' ) . '</span><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>',
		'next_text'          => '<span class="sr-only">' . esc_html__( 'Berikutnya', 'sukusastra' ) . '</span><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>',
		'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Halaman', 'sukusastra' ) . ' </span>',
	) );
}

/**
 * Get reviewer information (name and link/permalink).
 * Supports both CPT penulis post IDs and legacy text values.
 */
function sukusastra_get_reviewer_info( int $post_id ): array {
	$reviewer = get_post_meta( $post_id, '_ss_reviewer', true );
	if ( empty( $reviewer ) ) {
		return array( 'name' => '', 'url' => '' );
	}

	if ( is_numeric( $reviewer ) ) {
		$reviewer_post = get_post( (int) $reviewer );
		if ( $reviewer_post && 'penulis' === $reviewer_post->post_type ) {
			return array(
				'name' => $reviewer_post->post_title,
				'url'  => get_permalink( $reviewer_post->ID ),
			);
		}
	}

	$reviewer_post = sukusastra_find_penulis_by_name( (string) $reviewer );
	if ( $reviewer_post ) {
		return array(
			'name' => $reviewer_post->post_title,
			'url'  => get_permalink( $reviewer_post->ID ),
		);
	}

	// Fallback to raw string value
	return array(
		'name' => $reviewer,
		'url'  => '',
	);
}

/**
 * Get book author information (name and link/permalink).
 * Supports both CPT penulis post IDs and legacy text values.
 */
function sukusastra_get_book_author_info( int $post_id ): array {
	$book_author = get_post_meta( $post_id, '_ss_book_author', true );
	if ( empty( $book_author ) ) {
		return array( 'name' => '', 'url' => '' );
	}

	if ( is_numeric( $book_author ) ) {
		$author_post = get_post( (int) $book_author );
		if ( $author_post && 'penulis' === $author_post->post_type ) {
			return array(
				'name' => $author_post->post_title,
				'url'  => get_permalink( $author_post->ID ),
			);
		}
	}

	$author_post = sukusastra_find_penulis_by_name( (string) $book_author );
	if ( $author_post ) {
		return array(
			'name' => $author_post->post_title,
			'url'  => get_permalink( $author_post->ID ),
		);
	}

	// Fallback to raw string value
	return array(
		'name' => $book_author,
		'url'  => '',
	);
}


