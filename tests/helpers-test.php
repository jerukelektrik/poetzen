<?php
define( 'SUKUSASTRA_TESTING', true );

function home_url( string $path = '' ): string {
	return 'https://sukusastra.test' . $path;
}

require __DIR__ . '/../inc/helpers.php';

function assert_same( mixed $expected, mixed $actual, string $message ): void {
	if ( $expected !== $actual ) {
		fwrite( STDERR, $message . PHP_EOL . 'Expected: ' . var_export( $expected, true ) . PHP_EOL . 'Actual: ' . var_export( $actual, true ) . PHP_EOL );
		exit( 1 );
	}
}

assert_same( 'Beli Buku', sukusastra_cta_label( 'Beli Buku', 'Hubungi Kami' ), 'First CTA label should win.' );
assert_same( 'Hubungi Kami', sukusastra_cta_label( '', 'Hubungi Kami' ), 'Fallback CTA label should be used.' );
assert_same( '', sukusastra_cta_label( '', '' ), 'Empty labels should return empty string.' );

echo "helpers tests passed\n";
