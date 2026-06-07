<?php
define( 'SUKUSASTRA_TESTING', true );
require __DIR__ . '/../inc/event-state.php';

function assert_same( mixed $expected, mixed $actual, string $message ): void {
	if ( $expected !== $actual ) {
		fwrite( STDERR, $message . PHP_EOL . 'Expected: ' . var_export( $expected, true ) . PHP_EOL . 'Actual: ' . var_export( $actual, true ) . PHP_EOL );
		exit( 1 );
	}
}

// Override or mock time check for deterministic test:
// Since strtotime uses the current timezone and date, let's use upcoming/past values that won't flake.
$next_year = (int) date( 'Y' ) + 1;
$prev_year = (int) date( 'Y' ) - 1;

$active = sukusastra_event_cta_state( 'upcoming', 'available', "{$next_year}-12-01", 'Pesan Tiket', 'https://example.com/book' );
assert_same( true, $active['enabled'], 'Upcoming available event should be clickable.' );
assert_same( 'Pesan Tiket', $active['label'], 'Upcoming event should use booking label.' );

$ended = sukusastra_event_cta_state( 'past', 'available', "{$prev_year}-01-01", 'Pesan Tiket', 'https://example.com/book' );
assert_same( false, $ended['enabled'], 'Past event should be disabled.' );
assert_same( 'Event Berakhir', $ended['label'], 'Past event should show ended label.' );

$sold_out = sukusastra_event_cta_state( 'upcoming', 'sold_out', "{$next_year}-12-01", 'Pesan Tiket', 'https://example.com/book' );
assert_same( false, $sold_out['enabled'], 'Sold out event should be disabled.' );
assert_same( 'Tiket Habis', $sold_out['label'], 'Sold out event should show sold out label.' );

$cancelled = sukusastra_event_cta_state( 'cancelled', 'available', "{$next_year}-12-01", 'Pesan Tiket', 'https://example.com/book' );
assert_same( false, $cancelled['enabled'], 'Cancelled event should be disabled.' );
assert_same( 'Event Dibatalkan', $cancelled['label'], 'Cancelled event should show cancelled label.' );

echo "event-state tests passed\n";
