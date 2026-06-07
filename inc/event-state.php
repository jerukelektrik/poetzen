<?php
/**
 * Event state helpers.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'SUKUSASTRA_TESTING' ) ) {
	exit;
}

function sukusastra_event_cta_state( string $status, string $ticket_availability, string $end_date, string $booking_label, string $booking_url ): array {
	if ( 'cancelled' === $status ) {
		return array(
			'enabled' => false,
			'label'   => 'Event Dibatalkan',
			'url'     => '',
			'reason'  => 'cancelled',
		);
	}

	if ( 'past' === $status || sukusastra_event_date_has_passed( $end_date ) ) {
		return array(
			'enabled' => false,
			'label'   => 'Event Berakhir',
			'url'     => '',
			'reason'  => 'ended',
		);
	}

	if ( 'sold_out' === $ticket_availability ) {
		return array(
			'enabled' => false,
			'label'   => 'Tiket Habis',
			'url'     => '',
			'reason'  => 'sold_out',
		);
	}

	if ( '' === trim( $booking_url ) ) {
		return array(
			'enabled' => false,
			'label'   => 'Booking Belum Tersedia',
			'url'     => '',
			'reason'  => 'missing_url',
		);
	}

	return array(
		'enabled' => true,
		'label'   => '' !== trim( $booking_label ) ? $booking_label : 'Booking',
		'url'     => $booking_url,
		'reason'  => 'available',
	);
}

function sukusastra_event_date_has_passed( string $end_date ): bool {
	if ( '' === trim( $end_date ) ) {
		return false;
	}

	$timestamp = strtotime( $end_date . ' 23:59:59' );
	if ( false === $timestamp ) {
		return false;
	}

	return $timestamp < time();
}
