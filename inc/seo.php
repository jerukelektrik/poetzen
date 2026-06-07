<?php
/**
 * Native SEO output.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'pre_get_document_title', 'sukusastra_document_title' );
function sukusastra_document_title( string $title ): string {
	if ( is_singular() ) {
		$seo_title = sukusastra_get_meta( get_queried_object_id(), '_ss_seo_title' );
		return '' !== $seo_title ? $seo_title : $title;
	}
	return $title;
}

add_action( 'wp_head', 'sukusastra_render_seo_tags', 1 );
function sukusastra_render_seo_tags(): void {
	if ( is_search() || is_404() ) {
		printf( '<meta name="robots" content="noindex,follow">' . "\n" );
		return;
	}

	if ( is_singular() ) {
		$post_id = get_queried_object_id();
		$description = sukusastra_get_meta( $post_id, '_ss_meta_desc', wp_strip_all_tags( get_the_excerpt( $post_id ) ) );
		$canonical = sukusastra_get_meta( $post_id, '_ss_canonical', get_permalink( $post_id ) );
		$robots = sukusastra_get_meta( $post_id, '_ss_robots', 'index,follow' );
		$image = get_the_post_thumbnail_url( $post_id, 'large' );

		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
		printf( '<meta name="robots" content="%s">' . "\n", esc_attr( $robots ) );
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( wp_get_document_title() ) );
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( get_permalink( $post_id ) ) );
		if ( $image ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		}
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		if ( '1' === sukusastra_get_option( 'toggle_schema', '1' ) ) {
			sukusastra_render_json_ld( $post_id );
		}
		return;
	}

	if ( is_tag() ) {
		$term = get_queried_object();
		$robots = $term instanceof WP_Term && 0 === (int) $term->count ? 'noindex,follow' : 'index,follow';
		printf( '<meta name="robots" content="%s">' . "\n", esc_attr( $robots ) );
	}
}

function sukusastra_render_json_ld( int $post_id ): void {
	$post_type = get_post_type( $post_id );
	$data = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Article',
		'headline' => get_the_title( $post_id ),
		'datePublished' => get_the_date( DATE_W3C, $post_id ),
		'dateModified'  => get_the_modified_date( DATE_W3C, $post_id ),
		'url'      => get_permalink( $post_id ),
	);

	if ( 'review_buku' === $post_type ) {
		$data['@type'] = 'Review';
		$data['itemReviewed'] = array(
			'@type' => 'Book',
			'name'  => sukusastra_get_meta( $post_id, '_ss_book_title', get_the_title( $post_id ) ),
			'author'=> sukusastra_get_meta( $post_id, '_ss_book_author' ),
		);
	}

	if ( 'event' === $post_type ) {
		$data['@type'] = 'Event';
		$data['name'] = get_the_title( $post_id );
		$data['startDate'] = sukusastra_get_meta( $post_id, '_ss_event_start' );
		$data['endDate'] = sukusastra_get_meta( $post_id, '_ss_event_end' );
		$data['eventStatus'] = 'cancelled' === sukusastra_get_meta( $post_id, '_ss_event_status' ) ? 'https://schema.org/EventCancelled' : 'https://schema.org/EventScheduled';
		$data['location'] = array(
			'@type' => 'Place',
			'name'  => sukusastra_get_meta( $post_id, '_ss_event_location' ),
		);
	}

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
