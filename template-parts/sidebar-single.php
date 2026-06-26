<?php
/**
 * Sidebar for single templates.
 *
 * @package SukuSastra
 */

$post_type = get_post_type();
if ( 'event' === $post_type ) {
	$post_id = get_the_ID();
	$state = sukusastra_event_cta_state(
		sukusastra_get_meta( $post_id, '_ss_event_status', 'upcoming' ),
		sukusastra_get_meta( $post_id, '_ss_ticket_availability', 'available' ),
		sukusastra_get_meta( $post_id, '_ss_event_end' ),
		sukusastra_get_meta( $post_id, '_ss_booking_label', 'Booking' ),
		sukusastra_get_meta( $post_id, '_ss_booking_url' )
	);
}

$aside_classes = 'ss-single-sidebar grid content-start gap-6';
if ( 'review_buku' !== $post_type ) {
	$aside_classes .= ' lg:sticky lg:top-24 self-start';
}
?>
<aside class="<?php echo esc_attr( $aside_classes ); ?>">

	<div class="hidden lg:block">
		<?php poetzen_render_sidebar_banners(); ?>
	</div>

	<!-- Tabs Widget -->
	<section class="ss-sidebar-tabs-widget rounded-md border border-slate-200 bg-white p-5 dark:border-zinc-800 dark:bg-[#262B4E]/40 shadow-sm">
		<div class="flex border-b border-slate-200 dark:border-zinc-800 mb-5 text-sm font-bold">
			<button class="ss-sidebar-tab-btn flex-1 pb-3 text-center border-b-[3px] text-red-700 border-red-700 dark:text-red-400 dark:border-red-500 focus:outline-none transition-colors duration-200 cursor-pointer" type="button" data-tab="latest">
				<?php esc_html_e( 'Terbaru', 'sukusastra' ); ?>
			</button>
			<button class="ss-sidebar-tab-btn flex-1 pb-3 text-center border-b-[3px] text-slate-400 border-transparent dark:text-zinc-500 hover:text-slate-850 dark:hover:text-zinc-200 focus:outline-none transition-colors duration-200 cursor-pointer" type="button" data-tab="popular">
				<?php esc_html_e( 'Terpopuler', 'sukusastra' ); ?>
			</button>
		</div>

		<!-- Latest Posts Tab -->
		<div class="ss-sidebar-tab-content flex flex-col gap-2" data-tab-panel="latest">
			<?php 
			$latest_posts = sukusastra_sidebar_latest_posts( get_the_ID(), 5 );
			if ( $latest_posts->have_posts() ) :
				while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
					?>
					<a href="<?php the_permalink(); ?>" class="flex gap-4 items-center p-2.5 rounded-xl transition-all duration-200 group hover:bg-red-50 dark:hover:bg-red-950/30">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-slate-100 dark:border-zinc-800/80 shadow-sm">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-105' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="grid gap-0.5 flex-1 min-w-0">
							<div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500 dark:text-zinc-400 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">
								<span class="font-semibold"><?php echo esc_html( sukusastra_get_post_type_label( get_the_ID() ) ); ?></span>
								<span>•</span>
								<span><?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' lalu' ); ?></span>
							</div>
							<h4 class="text-sm font-bold text-slate-900 dark:text-zinc-100 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors leading-snug line-clamp-2">
								<?php the_title(); ?>
							</h4>
						</div>
					</a>
					<?php 
				endwhile; 
				wp_reset_postdata(); 
			else :
				?>
				<p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2"><?php esc_html_e( 'Tidak ada tulisan terbaru', 'sukusastra' ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Popular Posts Tab -->
		<div class="ss-sidebar-tab-content hidden flex flex-col gap-2" data-tab-panel="popular">
			<?php 
			$popular_posts = sukusastra_sidebar_popular_posts( get_the_ID(), 5 );
			if ( $popular_posts->have_posts() ) :
				while ( $popular_posts->have_posts() ) : $popular_posts->the_post();
					?>
					<a href="<?php the_permalink(); ?>" class="flex gap-4 items-center p-2.5 rounded-xl transition-all duration-200 group hover:bg-red-50 dark:hover:bg-red-950/30">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-slate-100 dark:border-zinc-800/80 shadow-sm">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-105' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="grid gap-0.5 flex-1 min-w-0">
							<div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500 dark:text-zinc-400 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">
								<span class="font-semibold"><?php echo esc_html( sukusastra_get_post_type_label( get_the_ID() ) ); ?></span>
								<span>•</span>
								<span><?php echo esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' lalu' ); ?></span>
							</div>
							<h4 class="text-sm font-bold text-slate-900 dark:text-zinc-100 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors leading-snug line-clamp-2">
								<?php the_title(); ?>
							</h4>
						</div>
					</a>
					<?php 
				endwhile; 
				wp_reset_postdata(); 
			else :
				?>
				<p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2"><?php esc_html_e( 'Tidak ada tulisan populer', 'sukusastra' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Event Terbaru Widget -->
	<?php
	$today = current_time( 'Y-m-d' );
	$event_tabs_id = wp_unique_id( 'ss-event-tabs-' );
	$upcoming_events = new WP_Query(
		array(
			'post_type'           => 'event',
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish',
			'meta_key'            => '_ss_event_start',
			'orderby'             => 'meta_value',
			'order'               => 'ASC',
			'meta_query'          => array(
				'relation' => 'OR',
				array(
					'key'     => '_ss_event_status',
					'value'   => 'upcoming',
					'compare' => '=',
				),
				array(
					'key'     => '_ss_event_start',
					'value'   => $today,
					'compare' => '>=',
					'type'    => 'DATE',
				),
			),
		)
	);
	$past_events = new WP_Query(
		array(
			'post_type'           => 'event',
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'post_status'         => 'publish',
			'meta_key'            => '_ss_event_start',
			'orderby'             => 'meta_value',
			'order'               => 'DESC',
			'meta_query'          => array(
				'relation' => 'OR',
				array(
					'key'     => '_ss_event_status',
					'value'   => 'past',
					'compare' => '=',
				),
				array(
					'key'     => '_ss_event_end',
					'value'   => $today,
					'compare' => '<',
					'type'    => 'DATE',
				),
				array(
					'key'     => '_ss_event_start',
					'value'   => $today,
					'compare' => '<',
					'type'    => 'DATE',
				),
			),
		)
	);
	$render_event_list = static function( WP_Query $events, bool $is_past ): void {
		if ( $events->have_posts() ) :
			while ( $events->have_posts() ) :
				$events->the_post();
				$event_start = sukusastra_get_meta( get_the_ID(), '_ss_event_start' );
				$image_class = $is_past ? ' grayscale opacity-75' : '';
				?>
				<div class="flex gap-4 items-center">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-slate-100 dark:border-zinc-800/80 shadow-sm">
							<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover hover:scale-105 transition-transform duration-300' . $image_class ) ); ?>
						</a>
					<?php endif; ?>
					<div class="grid gap-0.5 flex-1 min-w-0">
						<h4 class="ss-sidebar-title">
							<a class="no-underline hover:text-red-700 dark:hover:text-red-300" href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h4>
						<div class="flex items-center gap-1.5 ss-meta">
							<?php if ( $event_start ) : ?>
								<span class="shrink-0 text-red-700 dark:text-red-400 font-semibold"><?php echo esc_html( date_i18n( 'j M Y', strtotime( $event_start ) ) ); ?></span>
							<?php else : ?>
								<span class="shrink-0"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php
			endwhile;
			wp_reset_postdata();
		else :
			?>
			<p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2">
				<?php echo esc_html( $is_past ? __( 'Belum ada event yang sudah berlangsung', 'sukusastra' ) : __( 'Belum ada event akan datang', 'sukusastra' ) ); ?>
			</p>
			<?php
		endif;
	};
	?>
	<section class="ss-event-tabs-widget rounded-md border border-slate-200 p-5 dark:border-zinc-800 bg-white dark:bg-[#262B4E]/40 shadow-sm" data-event-tabs="<?php echo esc_attr( $event_tabs_id ); ?>">
		<div class="flex border-b border-slate-200 dark:border-zinc-800 mb-5 text-sm font-bold">
			<button class="ss-event-tab-btn flex-1 pb-3 text-center border-b-[3px] text-red-700 border-red-700 dark:text-red-400 dark:border-red-500 focus:outline-none transition-colors duration-200 cursor-pointer" type="button" data-event-tab="coming">
				<?php esc_html_e( 'Akan Datang', 'sukusastra' ); ?>
			</button>
			<button class="ss-event-tab-btn flex-1 pb-3 text-center border-b-[3px] text-slate-400 border-transparent dark:text-zinc-500 hover:text-slate-850 dark:hover:text-zinc-200 focus:outline-none transition-colors duration-200 cursor-pointer" type="button" data-event-tab="past">
				<?php esc_html_e( 'Sudah Berlangsung', 'sukusastra' ); ?>
			</button>
		</div>
		<div class="ss-event-tab-content grid gap-4" data-event-tab-panel="coming">
			<?php $render_event_list( $upcoming_events, false ); ?>
		</div>
		<div class="ss-event-tab-content hidden grid gap-4" data-event-tab-panel="past">
			<?php $render_event_list( $past_events, true ); ?>
		</div>
	</section>
</aside>

<script>
(function() {
	function initSidebarTabs() {
		document.querySelectorAll('.ss-sidebar-tabs-widget').forEach(widget => {
			const tabButtons = widget.querySelectorAll('.ss-sidebar-tab-btn');
			const tabContents = widget.querySelectorAll('.ss-sidebar-tab-content');

			tabButtons.forEach(btn => {
				btn.addEventListener('click', function() {
					const target = this.getAttribute('data-tab');

					tabButtons.forEach(b => {
						b.classList.remove('text-red-700', 'border-red-700', 'dark:text-red-400', 'dark:border-red-500');
						b.classList.add('text-slate-500', 'border-transparent', 'dark:text-zinc-400');
					});
					this.classList.add('text-red-700', 'border-red-700', 'dark:text-red-400', 'dark:border-red-500');
					this.classList.remove('text-slate-500', 'border-transparent', 'dark:text-zinc-400');

					tabContents.forEach(c => {
						if (c.getAttribute('data-tab-panel') === target) {
							c.classList.remove('hidden');
						} else {
							c.classList.add('hidden');
						}
					});
				});
			});
		});
	}

	function initEventTabs() {
		document.querySelectorAll('.ss-event-tabs-widget').forEach(widget => {
			const buttons = widget.querySelectorAll('.ss-event-tab-btn');
			const panels = widget.querySelectorAll('.ss-event-tab-content');

			buttons.forEach(button => {
				button.addEventListener('click', function() {
					const target = this.getAttribute('data-event-tab');

					buttons.forEach(btn => {
						btn.classList.remove('text-red-700', 'border-red-700', 'dark:text-red-400', 'dark:border-red-500');
						btn.classList.add('text-slate-400', 'border-transparent', 'dark:text-zinc-500');
					});
					this.classList.add('text-red-700', 'border-red-700', 'dark:text-red-400', 'dark:border-red-500');
					this.classList.remove('text-slate-400', 'border-transparent', 'dark:text-zinc-500');

					panels.forEach(panel => {
						if (panel.getAttribute('data-event-tab-panel') === target) {
							panel.classList.remove('hidden');
						} else {
							panel.classList.add('hidden');
						}
					});
				});
			});
		});
	}

	if (document.readyState !== 'loading') {
		initSidebarTabs();
		initEventTabs();
	} else {
		document.addEventListener('DOMContentLoaded', function() {
			initSidebarTabs();
			initEventTabs();
		});
	}
})();
</script>
