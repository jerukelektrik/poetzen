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

$orig_author = sukusastra_get_original_author( get_the_ID() );
?>
<aside class="grid gap-6">
	<!-- Event Action Block (Only for single events) -->
	<?php if ( 'event' === $post_type && isset( $state ) ) : ?>
		<div class="ss-card rounded grid gap-3">
			<p class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Pendaftaran', 'sukusastra' ); ?></p>
			<?php if ( $state['enabled'] ) : ?>
				<a class="ss-button w-full text-center block" href="<?php echo esc_url( $state['url'] ); ?>"><?php echo esc_html( $state['label'] ); ?></a>
			<?php else : ?>
				<span class="ss-button-disabled w-full text-center block" aria-disabled="true"><?php echo esc_html( $state['label'] ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- Author Widget (Only shown if original CPT penulis exists) -->
	<?php if ( $orig_author ) : ?>
		<section class="rounded-md border border-slate-200 bg-white p-5 dark:border-zinc-800 dark:bg-[#262B4E]/40 shadow-sm">
			<h3 class="ss-widget-title mb-3"><?php esc_html_e( 'Penulis', 'sukusastra' ); ?></h3>
			<div class="flex items-center gap-3">
				<?php if ( has_post_thumbnail( $orig_author->ID ) ) : ?>
					<div class="h-12 w-12 shrink-0 overflow-hidden rounded-full border border-slate-100 dark:border-zinc-800 shadow-sm">
						<?php echo get_the_post_thumbnail( $orig_author->ID, 'thumbnail', array( 'class' => 'h-full w-full object-cover' ) ); ?>
					</div>
				<?php else : ?>
					<div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-700 font-serif text-lg font-black text-white dark:bg-zinc-800 shadow-sm">
						<?php echo esc_html( substr( $orig_author->post_title, 0, 1 ) ); ?>
					</div>
				<?php endif; ?>
				<div>
					<h4 class="ss-author-name">
						<a class="no-underline hover:text-red-700 dark:hover:text-red-300" href="<?php echo esc_url( get_permalink( $orig_author->ID ) ); ?>">
							<?php echo esc_html( $orig_author->post_title ); ?>
						</a>
					</h4>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<!-- Tabs Widget -->
	<section class="rounded-md border border-slate-200 bg-white p-5 dark:border-zinc-800 dark:bg-[#262B4E]/40 shadow-sm">
		<div class="flex border-b border-slate-200 dark:border-zinc-800 mb-4 text-sm font-bold">
			<button class="ss-sidebar-tab-btn flex-1 pb-2 text-center border-b-2 text-red-700 border-red-700 dark:text-red-400 dark:border-red-500 focus:outline-none transition-colors duration-200 cursor-pointer" data-tab="ss-tab-latest">
				<?php esc_html_e( 'Terbaru', 'sukusastra' ); ?>
			</button>
			<button class="ss-sidebar-tab-btn flex-1 pb-2 text-center border-b-2 text-slate-500 border-transparent dark:text-zinc-400 hover:text-slate-800 dark:hover:text-zinc-200 focus:outline-none transition-colors duration-200 cursor-pointer" data-tab="ss-tab-popular">
				<?php esc_html_e( 'Terpopuler', 'sukusastra' ); ?>
			</button>
		</div>

		<!-- Latest Posts Tab -->
		<div id="ss-tab-latest" class="ss-sidebar-tab-content grid gap-4">
			<?php 
			$latest_posts = sukusastra_sidebar_latest_posts( get_the_ID(), 5 );
			if ( $latest_posts->have_posts() ) :
				while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
					?>
					<div class="flex gap-3 items-start">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="h-12 w-12 shrink-0 overflow-hidden rounded border border-slate-100 dark:border-zinc-800 shadow-sm">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
							</a>
						<?php endif; ?>
						<div class="grid gap-1 flex-1 min-w-0">
							<h4 class="ss-sidebar-title">
								<a class="no-underline hover:text-red-700 dark:hover:text-red-300" href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h4>
							<div class="flex items-center gap-1.5 ss-meta">
								<span class="truncate"><?php echo esc_html( sukusastra_get_post_type_label( get_the_ID() ) ); ?></span>
								<span>·</span>
								<span class="shrink-0"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span>
							</div>
						</div>
					</div>
					<?php 
				endwhile; 
				wp_reset_postdata(); 
			else :
				?>
				<p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2"><?php esc_html_e( 'Tidak ada tulisan terbaru', 'sukusastra' ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Popular Posts Tab -->
		<div id="ss-tab-popular" class="ss-sidebar-tab-content hidden grid gap-4">
			<?php 
			$popular_posts = sukusastra_sidebar_popular_posts( get_the_ID(), 5 );
			if ( $popular_posts->have_posts() ) :
				while ( $popular_posts->have_posts() ) : $popular_posts->the_post();
					?>
					<div class="flex gap-3 items-start">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="h-12 w-12 shrink-0 overflow-hidden rounded border border-slate-100 dark:border-zinc-800 shadow-sm">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
							</a>
						<?php endif; ?>
						<div class="grid gap-1 flex-1 min-w-0">
							<h4 class="ss-sidebar-title">
								<a class="no-underline hover:text-red-700 dark:hover:text-red-300" href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h4>
							<div class="flex items-center gap-1.5 ss-meta">
								<span class="truncate"><?php echo esc_html( sukusastra_get_post_type_label( get_the_ID() ) ); ?></span>
								<span>·</span>
								<span class="shrink-0"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span>
							</div>
						</div>
					</div>
					<?php 
				endwhile; 
				wp_reset_postdata(); 
			else :
				?>
				<p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2"><?php esc_html_e( 'Tidak ada tulisan populer', 'sukusastra' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Related Reading (Original fallback) -->
	<?php get_template_part( 'template-parts/related-posts' ); ?>

	<!-- Event Terbaru Widget -->
	<section class="rounded-md border border-slate-200 p-5 dark:border-zinc-800 bg-white dark:bg-[#262B4E]/40 shadow-sm">
		<h2 class="ss-widget-title mb-3"><?php esc_html_e( 'Event Terbaru', 'sukusastra' ); ?></h2>
		<div class="grid gap-4">
			<?php 
			$latest_events = new WP_Query( array(
				'post_type'           => 'event',
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => true,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'post_status'         => 'publish'
			) );
			if ( $latest_events->have_posts() ) :
				while ( $latest_events->have_posts() ) : $latest_events->the_post();
					?>
					<div class="flex gap-3 items-start">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" class="h-10 w-10 shrink-0 overflow-hidden rounded border border-slate-100 dark:border-zinc-800 shadow-sm">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'h-full w-full object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
							</a>
						<?php endif; ?>
						<div class="grid gap-0.5 flex-1 min-w-0">
							<h4 class="ss-sidebar-title">
								<a class="no-underline hover:text-red-700 dark:hover:text-red-300" href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h4>
							<div class="flex items-center gap-1.5 ss-meta">
								<?php 
								$event_start = sukusastra_get_meta( get_the_ID(), '_ss_event_start' );
								if ( $event_start ) :
									?>
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
				<p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2"><?php esc_html_e( 'Belum ada event terbaru', 'sukusastra' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</aside>

<script>
(function() {
	function initSidebarTabs() {
		const tabButtons = document.querySelectorAll('.ss-sidebar-tab-btn');
		const tabContents = document.querySelectorAll('.ss-sidebar-tab-content');

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
					if (c.id === target) {
						c.classList.remove('hidden');
					} else {
						c.classList.add('hidden');
					}
				});
			});
		});
	}

	if (document.readyState !== 'loading') {
		initSidebarTabs();
	} else {
		document.addEventListener('DOMContentLoaded', initSidebarTabs);
	}
})();
</script>
