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
		'Reviu Buku' => home_url( '/review-buku/' ),
		'Peristiwa'   => home_url( '/peristiwa/' ),
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

/**
 * Get outline SVG icon for a given Suku Sastra category/page title.
 */
function sukusastra_get_menu_icon( string $title ): string {
	$title = strtolower( trim( $title ) );
	
	// Home icon
	if ( 'depan' === $title || 'home' === $title || 'beranda' === $title ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>';
	}
	
	// Peristiwa / Berita / News
	if ( 'peristiwa' === $title || 'berita' === $title || 'news' === $title ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h6v3H6v-3Z" /></svg>';
	}
	
	// Puisi
	if ( 'puisi' === $title || 'poetry' === $title ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>';
	}
	
	// Cerpen
	if ( 'cerpen' === $title || 'short story' === $title || 'cerita' === $title ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-16.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-16.25v16.25" /></svg>';
	}
	
	// Esai
	if ( 'esai' === $title || 'essay' === $title ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>';
	}
	
	// Buku / Reviu Buku
	if ( 'buku' === $title || 'review buku' === $title || 'reviu buku' === $title || 'book' === $title ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.578 4.3l2.07 1.484a2.25 2.25 0 0 1 .902 1.8v10.166a2.25 2.25 0 0 1-2.25 2.25h-9.5a2.25 2.25 0 0 1-2.25-2.25V5.25A2.25 2.25 0 0 1 8.828 3h6.5a2.25 2.25 0 0 1 2.25 1.3Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 7.5h6m-6 3h6m-6 3h3" /></svg>';
	}
	
	// Katalog Terbitan / Terbitan
	if ( 'katalog terbitan' === $title || 'terbitan' === $title || 'store' === $title || 'katalog' === $title ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>';
	}

	// Agenda / Event
	if (
		'agenda' === $title ||
		'event' === $title ||
		'agenda/event' === $title ||
		'event & agenda' === $title ||
		str_contains( $title, 'agenda' ) ||
		str_contains( $title, 'event' )
	) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 4.5h13.5A1.5 1.5 0 0 1 20.25 6v13.5A1.5 1.5 0 0 1 18.75 21H5.25a1.5 1.5 0 0 1-1.5-1.5V6a1.5 1.5 0 0 1 1.5-1.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12h.008v.008H8.25V12Zm3.75 0h.008v.008H12V12Zm3.75 0h.008v.008h-.008V12Zm-7.5 3.75h.008v.008H8.25v-.008Zm3.75 0h.008v.008H12v-.008Z" /></svg>';
	}

	// Penulis / Tokoh
	if ( 'penulis' === $title || 'tokoh' === $title || 'authors' === $title ) {
		return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0A17.933 17.933 0 0 1 12 21.75a17.933 17.933 0 0 1-7.5-1.632Z" /></svg>';
	}
	
	// Default document icon
	return '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>';
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
		return esc_html__( 'Reviu Buku', 'sukusastra' );
	} elseif ( 'berita' === $post_type ) {
		return esc_html__( 'Peristiwa', 'sukusastra' );
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
				$cpt_label    = __( 'Reviu Buku', 'sukusastra' );
				$archive_link = home_url( '/review-buku/' );
			} elseif ( 'berita' === $post_type ) {
				$cpt_label    = __( 'Peristiwa', 'sukusastra' );
				$archive_link = home_url( '/peristiwa/' );
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

/**
 * Render related articles block for single templates.
 */
function sukusastra_display_related_posts(): void {
	$related_posts = sukusastra_get_related_articles( get_the_ID() );
	if ( empty( $related_posts ) ) {
		return;
	}

	$label = esc_html__( 'Baca Juga', 'sukusastra' );
	?>
	<div class="mt-10 not-prose ss-related-block">
		<h3 class="text-lg font-black uppercase tracking-wider text-slate-900 dark:text-zinc-50 mb-5 border-b border-slate-100 pb-2 dark:border-zinc-800/80"><?php echo esc_html( $label ); ?></h3>
		<div class="grid gap-4 sm:grid-cols-2">
			<?php foreach ( $related_posts as $related_post ) : ?>
				<?php 
				$url = get_permalink( $related_post->ID );
				$title = $related_post->post_title;
				$date = get_the_date( '', $related_post );
				$orig_author = sukusastra_get_original_author( $related_post->ID );
				$author_name = $orig_author ? $orig_author->post_title : get_the_author_meta( 'display_name', $related_post->post_author );
				?>
				<div class="flex gap-3 items-center p-3 rounded-2xl border border-slate-200/60 bg-white dark:border-zinc-800/80 dark:bg-[#262B4E]/20 shadow-sm min-w-0">
					<?php if ( has_post_thumbnail( $related_post->ID ) ) : ?>
						<a href="<?php echo esc_url( $url ); ?>" class="block w-16 h-16 shrink-0 overflow-hidden rounded-xl border border-slate-200/50 dark:border-zinc-800/60 shadow-sm">
							<?php echo get_the_post_thumbnail( $related_post->ID, 'thumbnail', array( 'class' => 'w-full h-full object-cover hover:scale-105 transition-transform duration-500' ) ); ?>
						</a>
					<?php endif; ?>
					<div class="flex-1 min-w-0">
						<div class="text-[9px] font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500">
							<span class="text-slate-700 dark:text-zinc-300"><?php echo esc_html( $author_name ); ?></span>
							<span class="text-slate-355 dark:text-zinc-655">·</span>
							<span class="font-semibold normal-case text-slate-400 dark:text-zinc-500"><?php echo esc_html( $date ); ?></span>
						</div>
						<h5 class="text-sm font-black leading-snug text-slate-900 dark:text-zinc-50 mt-1 mb-0 line-clamp-2">
							<a href="<?php echo esc_url( $url ); ?>" class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors">
								<?php echo esc_html( $title ); ?>
							</a>
						</h5>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Fix footnote href attributes that contain leading slashes (e.g. href="/#fn-id" -> href="#fn-id")
 * to prevent unwanted homepage redirects.
 */
add_filter( 'the_content', 'sukusastra_fix_footnote_hrefs', 20 );
function sukusastra_fix_footnote_hrefs( string $content ): string {
	if ( is_admin() || empty( $content ) ) {
		return $content;
	}

	return preg_replace(
		'/href=["\']\/?#([^"\']+)["\']/',
		'href="#$1"',
		$content
	);
}

/**
 * Footnote Shortcode and Content Auto-Parser
 * Allows writing [fn]Footnote content[/fn] or [footnote]Footnote content[/footnote] anywhere in post content.
 * Automatically converts them to numbered superscript links ^1, ^2, ^3 and generates a styled
 * footnote section at the bottom of the article.
 */
add_filter( 'the_content', 'sukusastra_parse_footnote_shortcodes', 15 );
function sukusastra_parse_footnote_shortcodes( string $content ): string {
	if ( is_admin() || empty( $content ) ) {
		return $content;
	}

	if ( ! str_contains( $content, '[fn]' ) && ! str_contains( $content, '[footnote]' ) ) {
		return $content;
	}

	$footnotes = array();
	$count = 0;

	$content = preg_replace_callback(
		'/\[(?:fn|footnote)\](.*?)\[\/(?:fn|footnote)\]/s',
		function ( $matches ) use ( &$footnotes, &$count ) {
			$count++;
			$fn_id = 'fn-' . $count;
			$fn_link_id = 'fnref-' . $count;
			$fn_text = trim( $matches[1] );

			$footnotes[] = array(
				'id'      => $fn_id,
				'link_id' => $fn_link_id,
				'text'    => $fn_text,
				'num'     => $count,
			);

			return sprintf(
				'<sup class="fn" id="%s"><a href="#%s">%d</a></sup>',
				esc_attr( $fn_link_id ),
				esc_attr( $fn_id ),
				$count
			);
		},
		$content
	);

	if ( ! empty( $footnotes ) ) {
		$list_html = '<div class="ss-footnotes-wrapper mt-10 pt-6 border-t border-slate-200 dark:border-zinc-800">';
		$list_html .= '<h4 class="text-sm font-black uppercase tracking-wider text-slate-500 dark:text-zinc-400 mb-3">' . esc_html__( 'Catatan Kaki:', 'sukusastra' ) . '</h4>';
		$list_html .= '<ol class="wp-block-footnotes list-decimal pl-5 text-sm leading-relaxed">';

		foreach ( $footnotes as $fn ) {
			$list_html .= sprintf(
				'<li id="%s" class="my-2 text-slate-700 dark:text-zinc-300">%s <a href="#%s" class="no-underline text-red-700 dark:text-red-400 font-bold ml-1" aria-label="Kembali ke teks">↩︎</a></li>',
				esc_attr( $fn['id'] ),
				$fn['text'],
				esc_attr( $fn['link_id'] )
			);
		}

		$list_html .= '</ol></div>';

		$content .= $list_html;
	}

	return $content;
}



