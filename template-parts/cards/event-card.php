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

$event_type = sukusastra_get_meta( $post_id, '_ss_event_type', 'acara' );
$status = sukusastra_get_meta( $post_id, '_ss_event_status', 'upcoming' );
$ticket_availability = sukusastra_get_meta( $post_id, '_ss_ticket_availability', 'available' );
$end_date = sukusastra_get_meta( $post_id, '_ss_event_end' );
$paid = sukusastra_get_meta( $post_id, '_ss_paid_ticket', '0' );
$is_ended = ( 'past' === $status || 'cancelled' === $status || sukusastra_event_date_has_passed( $end_date ) );

$status_pill = '';
$pill_class = '';

if ( 'cancelled' === $status ) {
	$status_pill = __( 'Dibatalkan', 'sukusastra' );
	$pill_class = 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 border border-red-200/50 dark:border-red-900/30';
} elseif ( 'past' === $status || sukusastra_event_date_has_passed( $end_date ) ) {
	$status_pill = __( 'Sudah Selesai', 'sukusastra' );
	$pill_class = 'bg-slate-100 text-slate-600 dark:bg-zinc-800/80 dark:text-zinc-400 border border-slate-200/50 dark:border-zinc-700/50';
} elseif ( 'sayembara' === $event_type ) {
	$status_pill = __( 'Sayembara', 'sukusastra' );
	$pill_class = 'bg-amber-50 text-amber-850 dark:bg-amber-950/30 dark:text-amber-400 border border-amber-200/50 dark:border-amber-900/30';
} elseif ( 'sold_out' === $ticket_availability ) {
	$status_pill = __( 'Tiket Habis', 'sukusastra' );
	$pill_class = 'bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 border border-red-200/50 dark:border-red-900/30';
} else {
	// Ticket is available & Event is upcoming
	if ( '1' === $paid ) {
		$status_pill = __( 'Tiket Terbatas', 'sukusastra' );
		$pill_class = 'bg-purple-50 text-purple-700 dark:bg-purple-950/30 dark:text-purple-400 border border-purple-200/50 dark:border-purple-900/30';
	} else {
		$status_pill = __( 'Pendaftaran Gratis', 'sukusastra' );
		$pill_class = 'bg-amber-50 text-amber-850 dark:bg-amber-950/30 dark:text-amber-400 border border-amber-200/50 dark:border-amber-900/30';
	}
}

$start_date_raw = sukusastra_get_meta( $post_id, '_ss_event_start' );
$end_date_raw = sukusastra_get_meta( $post_id, '_ss_event_end' );
$formatted_date = '';

if ( 'sayembara' === $event_type ) {
	if ( $end_date_raw ) {
		$timestamp = strtotime( $end_date_raw );
		if ( $timestamp ) {
			$formatted_date = sprintf( __( 'Deadline: %s', 'sukusastra' ), date_i18n( 'd M Y', $timestamp ) );
		}
	} else {
		$formatted_date = __( 'Deadline: Akan diumumkan', 'sukusastra' );
	}
} else {
	if ( $start_date_raw ) {
		$timestamp = strtotime( $start_date_raw );
		if ( $timestamp ) {
			$formatted_date = date_i18n( 'D, d M Y', $timestamp );
		}
	}
}
?>
<article <?php post_class( 'group flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:border-slate-350 dark:border-zinc-800/80 dark:bg-[#262B4E] dark:hover:border-red-700/30 min-w-0' ); ?>>
	<!-- Image wrapper flush with borders -->
	<a class="block no-underline overflow-hidden aspect-[16/10] shrink-0 <?php echo $is_ended ? 'grayscale opacity-75' : ''; ?>" href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php 
			the_post_thumbnail( 
				'article-card', 
				array( 
					'class'    => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500',
					'sizes'    => '(max-width: 640px) 85vw, (max-width: 1024px) 45vw, 360px',
					'decoding' => 'async',
				) 
			); 
			?>
		<?php else : ?>
			<div class="flex w-full h-full items-center justify-center bg-slate-50 dark:bg-zinc-900/40 p-6 group-hover:scale-105 transition-transform duration-500">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php the_title_attribute(); ?>" class="max-h-12 max-w-full opacity-50 dark:opacity-20 object-contain" />
			</div>
		<?php endif; ?>
	</a>

	<!-- Content Block with padding -->
	<div class="flex flex-col gap-2 p-5 pt-4 flex-grow">
		<!-- Pill Badge -->
		<?php if ( $status_pill ) : ?>
			<span class="w-fit px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider <?php echo esc_attr( $pill_class ); ?>">
				<?php echo esc_html( $status_pill ); ?>
			</span>
		<?php endif; ?>

		<!-- Title -->
		<h3 class="ss-event-card-title text-base font-black leading-snug text-slate-900 dark:text-zinc-50 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors mt-1">
			<a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</h3>

		<!-- Event Info (Date & Location) -->
		<div class="text-xs font-semibold text-slate-500 dark:text-zinc-400 mt-1 flex flex-col gap-1.5">
			<?php if ( $formatted_date ) : ?>
				<span class="flex items-center gap-1.5">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-slate-400 dark:text-zinc-500 shrink-0">
						<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
					</svg>
					<span><?php echo esc_html( $formatted_date ); ?></span>
				</span>
			<?php endif; ?>
			<?php 
			if ( 'sayembara' === $event_type ) {
				$prize = sukusastra_get_meta( $post_id, '_ss_event_prize' );
				if ( $prize ) :
					?>
					<span class="flex items-center gap-1.5 text-slate-400 dark:text-zinc-500 font-bold">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-slate-400 dark:text-zinc-500 shrink-0">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3-3h.375a3 3 0 0 0 3-3v-.375a3 3 0 0 0-3-3H18.75m-12 9.375A3 3 0 0 0 9.75 15h.375a3 3 0 0 1 3 3m-9 0a3 3 0 0 0-3-3h-.375a3 3 0 0 1-3-3v-.375a3 3 0 0 1 3-3H5.25m3.75 0V7.5a3 3 0 0 1 3-3h1.5a3 3 0 0 1 3 3v5.25m-7.5-6h7.5" />
						</svg>
						<span><?php echo esc_html( sprintf( __( 'Hadiah: %s', 'sukusastra' ), $prize ) ); ?></span>
					</span>
					<?php
				endif;
			} else {
				$location = sukusastra_get_meta( $post_id, '_ss_event_location' );
				if ( $location ) :
					?>
					<span class="flex items-center gap-1.5 text-slate-400 dark:text-zinc-500 font-bold">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 text-slate-400 dark:text-zinc-500 shrink-0">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
						</svg>
						<span><?php echo esc_html( $location ); ?></span>
					</span>
					<?php
				endif;
			}
			?>
		</div>

		<!-- Price / Cost at bottom -->
		<div class="mt-auto pt-3 text-xs font-black text-slate-900 dark:text-zinc-50">
			<?php 
			if ( 'sayembara' === $event_type ) {
				$fee = sukusastra_get_meta( $post_id, '_ss_event_fee' );
				if ( $fee ) {
					echo esc_html( sprintf( __( 'Biaya: %s', 'sukusastra' ), $fee ) );
				} else {
					echo esc_html__( 'Biaya: Gratis', 'sukusastra' );
				}
			} else {
				if ( '1' === $paid ) {
					echo esc_html__( 'Mulai Berbayar', 'sukusastra' );
				} else {
					echo '<span class="text-red-750 dark:text-red-400">' . esc_html__( 'Gratis', 'sukusastra' ) . '</span>';
				}
			}
			?>
		</div>
	</div>
</article>

