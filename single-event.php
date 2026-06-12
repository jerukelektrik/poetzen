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
	$event_type = sukusastra_get_meta( $post_id, '_ss_event_type', 'acara' );
	$prize = sukusastra_get_meta( $post_id, '_ss_event_prize' );
	$fee = sukusastra_get_meta( $post_id, '_ss_event_fee' );
	$rules_url = sukusastra_get_meta( $post_id, '_ss_event_rules_url' );
	?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[minmax(0,760px)_320px]">
				<div>
				<p class="ss-eyebrow mb-2">
					<?php 
					if ( 'sayembara' === $event_type ) {
						esc_html_e( 'Sayembara Menulis', 'sukusastra' );
					} else {
						esc_html_e( 'Event Suku Sastra', 'sukusastra' );
					}
					?>
				</p>
				<h1 class="ss-page-title"><?php the_title(); ?></h1>
				
				<?php 
				$gallery_ids = sukusastra_get_meta( $post_id, '_ss_event_gallery', '' );
				if ( has_post_thumbnail() || $gallery_ids ) : 
					$event_status = sukusastra_get_meta( $post_id, '_ss_event_status', 'upcoming' );
					$event_end = sukusastra_get_meta( $post_id, '_ss_event_end' );
					$is_ended = ( 'past' === $event_status || 'cancelled' === $event_status || sukusastra_event_date_has_passed( $event_end ) );
					?>
					<!-- Poster/Pamflet Carousel or Single image below title -->
					<div class="mt-6 max-w-3xl rounded-2xl overflow-hidden shadow-md border border-slate-200/60 dark:border-zinc-800 bg-white dark:bg-[#262B4E]/40 p-6 <?php echo $is_ended ? 'grayscale opacity-75' : ''; ?>">
						<span class="ss-event-label text-center block mb-4"><?php esc_html_e( 'Pamflet / Poster', 'sukusastra' ); ?></span>
						<?php get_template_part( 'template-parts/event-gallery' ); ?>
					</div>
				<?php endif; ?>

				<div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4 rounded-2xl border border-slate-200 p-6 text-sm dark:border-zinc-800 bg-white dark:bg-[#262B4E]/40 shadow-sm items-center">
					<?php if ( 'sayembara' === $event_type ) : ?>
						<!-- Tipe Sayembara -->
						<div>
							<span class="ss-event-label"><?php esc_html_e( 'Deadline Pengiriman', 'sukusastra' ); ?></span>
							<span class="ss-event-value">
								<?php 
								$end_date = sukusastra_get_meta( $post_id, '_ss_event_end' );
								if ( $end_date ) {
									echo esc_html( date_i18n( 'd M Y', strtotime( $end_date ) ) );
								} else {
									esc_html_e( 'Akan diumumkan', 'sukusastra' );
								}
								?>
							</span>
						</div>
						<div>
							<span class="ss-event-label"><?php esc_html_e( 'Total Hadiah', 'sukusastra' ); ?></span>
							<span class="ss-event-value"><?php echo esc_html( $prize ? $prize : '-' ); ?></span>
						</div>
						<div>
							<span class="ss-event-label"><?php esc_html_e( 'Biaya Pendaftaran', 'sukusastra' ); ?></span>
							<span class="ss-event-value">
								<?php echo esc_html( $fee ? $fee : __( 'Gratis / Free', 'sukusastra' ) ); ?>
							</span>
						</div>
						<div class="flex flex-col gap-1.5 w-full">
							<span class="ss-event-label mb-0.5"><?php esc_html_e( 'Pendaftaran', 'sukusastra' ); ?></span>
							<?php if ( $state['enabled'] ) : ?>
								<a class="ss-button w-full text-center block" href="<?php echo esc_url( $state['url'] ); ?>"><?php echo esc_html( $state['label'] ? $state['label'] : __( 'Kirim Karya', 'sukusastra' ) ); ?></a>
							<?php else : ?>
								<span class="ss-button-disabled w-full text-center block" aria-disabled="true"><?php echo esc_html( $state['label'] ? $state['label'] : __( 'Ditutup', 'sukusastra' ) ); ?></span>
							<?php endif; ?>
							<?php if ( $rules_url ) : ?>
								<a class="ss-button-secondary w-full text-center block py-1.5 text-xs font-bold" href="<?php echo esc_url( $rules_url ); ?>" target="_blank"><?php esc_html_e( 'Unduh Panduan', 'sukusastra' ); ?></a>
							<?php endif; ?>
						</div>
					<?php else : ?>
						<!-- Tipe Acara / Agenda (Default) -->
						<div>
							<span class="ss-event-label"><?php esc_html_e( 'Tanggal & Waktu', 'sukusastra' ); ?></span>
							<span class="ss-event-value">
								<?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_event_start' ) ); ?>
								<?php if ( sukusastra_get_meta( $post_id, '_ss_event_end' ) ) : ?>
									<br>s.d. <?php echo esc_html( sukusastra_get_meta( $post_id, '_ss_event_end' ) ); ?>
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
						<div class="flex flex-col gap-1.5 w-full">
							<span class="ss-event-label mb-0.5"><?php esc_html_e( 'Pendaftaran', 'sukusastra' ); ?></span>
							<?php if ( $state['enabled'] ) : ?>
								<a class="ss-button w-full text-center block" href="<?php echo esc_url( $state['url'] ); ?>"><?php echo esc_html( $state['label'] ); ?></a>
							<?php else : ?>
								<span class="ss-button-disabled w-full text-center block" aria-disabled="true"><?php echo esc_html( $state['label'] ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="ss-reading mt-8"><?php the_content(); ?></div>
			</div>
			
			<?php get_template_part( 'template-parts/sidebar-single' ); ?>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
