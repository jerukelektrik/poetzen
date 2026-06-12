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
		$tipe_event   = isset( $_GET['tipe_event'] ) ? sanitize_key( wp_unslash( $_GET['tipe_event'] ) ) : '';
		$status_event = isset( $_GET['status_event'] ) ? sanitize_key( wp_unslash( $_GET['status_event'] ) ) : '';
		if ( ! in_array( $status_event, array( 'ongoing', 'ended' ), true ) ) {
			$status_event = 'all';
		}
		?>

		<!-- Event Filters Form -->
		<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>" class="bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800/80 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
			<div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
				<!-- Tipe Event dropdown -->
				<select name="tipe_event" onchange="this.form.submit()" class="px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 cursor-pointer shadow-sm font-semibold">
					<option value=""><?php esc_html_e( 'Semua Event & Sayembara', 'sukusastra' ); ?></option>
					<option value="acara" <?php selected( $tipe_event, 'acara' ); ?>><?php esc_html_e( 'Acara / Workshop', 'sukusastra' ); ?></option>
					<option value="sayembara" <?php selected( $tipe_event, 'sayembara' ); ?>><?php esc_html_e( 'Sayembara Menulis', 'sukusastra' ); ?></option>
				</select>

				<!-- Status/Sort dropdown -->
				<select name="status_event" onchange="this.form.submit()" class="px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 cursor-pointer shadow-sm font-semibold">
					<option value="all" <?php selected( $status_event, 'all' ); ?>><?php esc_html_e( 'Semua Status', 'sukusastra' ); ?></option>
					<option value="ongoing" <?php selected( $status_event, 'ongoing' ); ?>><?php esc_html_e( 'Masih Berlangsung', 'sukusastra' ); ?></option>
					<option value="ended" <?php selected( $status_event, 'ended' ); ?>><?php esc_html_e( 'Sudah Berakhir', 'sukusastra' ); ?></option>
				</select>
			</div>

			<div class="flex items-center gap-2 w-full md:w-auto">
				<button type="submit" class="ss-button px-5 py-2.5 text-xs font-black uppercase tracking-wider transition-all">
					<?php esc_html_e( 'Filter', 'sukusastra' ); ?>
				</button>
				<?php if ( '' !== $tipe_event || 'all' !== $status_event ) : ?>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>" class="ss-button-secondary px-5 py-2.5 text-xs font-black uppercase tracking-wider no-underline transition-all">
						<?php esc_html_e( 'Reset', 'sukusastra' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</form>

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
			<?php sukusastra_pagination(); ?>
		</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
