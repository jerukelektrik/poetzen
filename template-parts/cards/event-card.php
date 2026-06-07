<?php
/**
 * Event card.
 *
 * @package SukuSastra
 */
$post_id = get_the_ID();
$state = sukusastra_event_cta_state(
	sukusastra_get_meta( $post_id, '_ss_event_status', 'upcoming' ),
	sukusastra_get_meta( $post_id, '_ss_ticket_availability', 'available' ),
	sukusastra_get_meta( $post_id, '_ss_event_end' ),
	sukusastra_get_meta( $post_id, '_ss_booking_label', 'Booking' ),
	sukusastra_get_meta( $post_id, '_ss_booking_url' )
);
?>
<article <?php post_class( 'ss-card grid gap-3' ); ?>>
	<p class="ss-eyebrow"><?php esc_html_e( 'Event', 'sukusastra' ); ?></p>
	<h3 class="ss-card-title">
		<a class="ss-card-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h3>
	<p class="ss-body">
		<?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_event_start' ) ); ?>
		<?php echo sukusastra_get_meta( $post_id, '_ss_event_location' ) ? ' · ' . esc_html( sukusastra_get_meta( $post_id, '_ss_event_location' ) ) : ''; ?>
	</p>
	<?php if ( $state['enabled'] ) : ?>
		<a class="ss-button w-fit" href="<?php echo esc_url( $state['url'] ); ?>"><?php echo esc_html( $state['label'] ); ?></a>
	<?php else : ?>
		<span class="ss-button-disabled w-fit" aria-disabled="true"><?php echo esc_html( $state['label'] ); ?></span>
	<?php endif; ?>
</article>
