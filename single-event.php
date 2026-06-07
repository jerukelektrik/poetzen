<?php
/**
 * Event single template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php
	$post_id = get_the_ID();
	$state = sukusastra_event_cta_state(
		sukusastra_get_meta( $post_id, '_ss_event_status', 'upcoming' ),
		sukusastra_get_meta( $post_id, '_ss_ticket_availability', 'available' ),
		sukusastra_get_meta( $post_id, '_ss_event_end' ),
		sukusastra_get_meta( $post_id, '_ss_booking_label', 'Booking' ),
		sukusastra_get_meta( $post_id, '_ss_booking_url' )
	);
	$is_paid = sukusastra_get_meta( $post_id, '_ss_paid_ticket', '0' );
	?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-10 lg:grid-cols-[minmax(0,760px)_320px]">
				<div>
				<p class="ss-eyebrow mb-2"><?php esc_html_e( 'Event Suku Sastra', 'sukusastra' ); ?></p>
				<h1 class="ss-page-title"><?php the_title(); ?></h1>
				
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mt-6 rounded overflow-hidden shadow-sm">
						<?php the_post_thumbnail( 'sukusastra-hero', array( 'class' => 'w-full object-cover' ) ); ?>
					</div>
				<?php endif; ?>

				<div class="mt-8 grid gap-3 sm:grid-cols-3 rounded-md border border-slate-200 p-5 text-sm dark:border-zinc-800 bg-white dark:bg-zinc-900 shadow-sm">
					<div>
						<span class="ss-event-label"><?php esc_html_e( 'Tanggal & Waktu', 'sukusastra' ); ?></span>
						<span class="ss-event-value">
							<?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_event_start' ) ); ?>
							<?php if ( sukusastra_get_meta( $post_id, '_ss_event_end' ) ) : ?>
								s.d. <?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_event_end' ) ); ?>
							<?php endif; ?>
						</span>
					</div>
					<div>
						<span class="ss-event-label"><?php esc_html_e( 'Lokasi / Format', 'sukusastra' ); ?></span>
						<span class="ss-event-value"><?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_event_location', 'Online' ) ); ?></span>
					</div>
					<div>
						<span class="ss-event-label"><?php esc_html_e( 'Jenis Tiket', 'sukusastra' ); ?></span>
						<span class="ss-event-value">
							<?php echo $is_paid === '1' ? esc_html__( 'Berbayar', 'sukusastra' ) : esc_html__( 'Gratis / Free', 'sukusastra' ); ?>
						</span>
					</div>
				</div>

				<div class="ss-reading mt-8"><?php the_content(); ?></div>
			</div>
			
			<?php get_template_part( 'template-parts/sidebar-single' ); ?>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
