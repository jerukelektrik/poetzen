<?php
/**
 * Event archive template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<section class="ss-section">
	<div class="ss-container">
		<?php sukusastra_breadcrumbs(); ?>
		<div class="grid gap-6">
		<header class="border-b border-slate-100 pb-4 dark:border-zinc-800/80">
			<p class="ss-eyebrow mb-1"><?php esc_html_e( 'Agenda Suku Sastra', 'sukusastra' ); ?></p>
			<h1 class="ss-page-title"><?php esc_html_e( 'Event & Workshop', 'sukusastra' ); ?></h1>
			<p class="mt-2 max-w-2xl ss-body"><?php esc_html_e( 'Ikuti diskusi sastra, kelas menulis puisi, cerpen, esai, dan berbagai workshop literasi menarik lainnya.', 'sukusastra' ); ?></p>
		</header>

		<?php
		$active_status = isset( $_GET['status_event'] ) ? sanitize_key( wp_unslash( $_GET['status_event'] ) ) : '';
		if ( ! in_array( $active_status, array( 'ongoing', 'ended' ), true ) ) {
			$active_status = 'all';
		}
		?>

		<!-- Event Filters -->
		<div class="flex flex-wrap gap-2.5">
			<a class="px-4 py-2 rounded-lg text-sm font-bold no-underline transition-all <?php echo $active_status === 'all' ? 'bg-red-700 text-white dark:bg-red-500 dark:text-zinc-950 shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700'; ?>" href="<?php echo esc_url( remove_query_arg( 'status_event' ) ); ?>">
				<?php esc_html_e( 'Semua Event', 'sukusastra' ); ?>
			</a>
			<a class="px-4 py-2 rounded-lg text-sm font-bold no-underline transition-all <?php echo $active_status === 'ongoing' ? 'bg-red-700 text-white dark:bg-red-500 dark:text-zinc-950 shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700'; ?>" href="<?php echo esc_url( add_query_arg( 'status_event', 'ongoing' ) ); ?>">
				<?php esc_html_e( 'Masih Berlangsung', 'sukusastra' ); ?>
			</a>
			<a class="px-4 py-2 rounded-lg text-sm font-bold no-underline transition-all <?php echo $active_status === 'ended' ? 'bg-red-700 text-white dark:bg-red-500 dark:text-zinc-950 shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700'; ?>" href="<?php echo esc_url( add_query_arg( 'status_event', 'ended' ) ); ?>">
				<?php esc_html_e( 'Sudah Berakhir', 'sukusastra' ); ?>
			</a>
		</div>

		<!-- Event Loop -->
		<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-2">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/cards/event-card' ); ?>
			<?php endwhile; else : ?>
				<div class="col-span-full rounded-md border border-slate-200 p-8 text-center dark:border-zinc-800 bg-white dark:bg-zinc-900">
					<p class="text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Tidak ada event yang sesuai dengan filter ini.', 'sukusastra' ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<!-- Pagination -->
		<div class="mt-6 border-t border-slate-200/20 pt-6">
			<?php the_posts_pagination(); ?>
		</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
