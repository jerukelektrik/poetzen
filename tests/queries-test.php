<?php
define( 'SUKUSASTRA_TESTING', true );

// Mock global states
$mock_is_search = false;
$mock_search_query = '';
$mock_get_posts_return = array();

// Mock WP functions
function sanitize_key( string $key ): string {
	return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( $key ) );
}

// Mock of theme helper used in queries.php
function sukusastra_get_author_category_id(): int {
	return 0;
}

function wp_unslash( mixed $value ): mixed {
	return $value;
}

function absint( mixed $value ): int {
	return abs( (int) $value );
}

function sanitize_text_field( string $str ): string {
	return trim( $str );
}

function sanitize_title( string $title ): string {
	$title = strtolower( $title );
	$title = preg_replace( '/[^a-z0-9\s\-]/', '', $title );
	$title = preg_replace( '/[\s\-]+/', '-', $title );
	return trim( $title, '-' );
}

function get_query_var( string $var, mixed $default = '' ): mixed {
	if ( 'paged' === $var ) {
		return 1;
	}
	return $default;
}

function is_search(): bool {
	global $mock_is_search;
	return $mock_is_search;
}

function get_search_query(): string {
	global $mock_search_query;
	return $mock_search_query;
}

function get_posts( array $args ): array {
	global $mock_get_posts_return;
	return $mock_get_posts_return;
}

function is_wp_error( mixed $thing ): bool {
	return false;
}

// Require queries.php
require __DIR__ . '/../inc/queries.php';

function assert_same( mixed $expected, mixed $actual, string $message ): void {
	if ( $expected !== $actual ) {
		fwrite( STDERR, $message . PHP_EOL . 'Expected: ' . var_export( $expected, true ) . PHP_EOL . 'Actual: ' . var_export( $actual, true ) . PHP_EOL );
		exit( 1 );
	}
}

// ----------------------------------------------------
// Test Case 1: Default sorting (Terbaru DESC)
// ----------------------------------------------------
$_GET = array();
$mock_is_search = false;
$mock_search_query = '';
$mock_get_posts_return = array();

$args = sukusastra_filter_args_from_request();

assert_same( 'date', $args['orderby'], 'Default orderby should be date.' );
assert_same( 'DESC', $args['order'], 'Default order should be DESC.' );

// ----------------------------------------------------
// Test Case 2: Sorting Terbaru ASC
// ----------------------------------------------------
$_GET = array(
	'sort_by'    => 'terbaru',
	'sort_order' => 'asc',
);

$args = sukusastra_filter_args_from_request();

assert_same( 'date', $args['orderby'], 'Orderby should be date for terbaru.' );
assert_same( 'ASC', $args['order'], 'Order should be ASC.' );

// ----------------------------------------------------
// Test Case 3: Sorting Terpopuler DESC
// ----------------------------------------------------
$_GET = array(
	'sort_by'    => 'terpopuler',
	'sort_order' => 'desc',
);

$args = sukusastra_filter_args_from_request();

$expected_views_meta_query = array(
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

assert_same( $expected_views_meta_query, $args['meta_query'], 'Meta query for views should match.' );
assert_same( array( 'views_clause' => 'DESC', 'date' => 'DESC' ), $args['orderby'], 'Orderby array should match.' );

// ----------------------------------------------------
// Test Case 4: Search keyword with mock results
// ----------------------------------------------------
$_GET = array(
	's' => 'chairil',
);
$mock_is_search = true;
$mock_search_query = 'chairil';
$mock_get_posts_return = array( 12 );

$args = sukusastra_filter_args_from_request();

assert_same( array( 12 ), $args['post__in'], 'Post ID should match mocked return.' );

echo "queries tests passed\n";

