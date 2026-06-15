<?php
/**
 * Simplified archive/category filters.
 *
 * @package SukuSastra
 */

$sort_by = isset( $_GET['sort_by'] ) ? sanitize_key( wp_unslash( $_GET['sort_by'] ) ) : 'terbaru';
$cari_penulis = isset( $_GET['cari_penulis'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_penulis'] ) ) : '';

if ( ! in_array( $sort_by, array( 'terbaru', 'terpopuler', 'abjad_a_z', 'abjad_z_a' ), true ) ) {
	$sort_by = 'terbaru';
}

// Current URL path
$current_url = preg_replace( '/\?.*/', '', $_SERVER['REQUEST_URI'] );
?>
<form class="ss-archive-filter bg-white dark:bg-[#262B4E] border border-slate-200/60 dark:border-zinc-800/80 rounded-3xl p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4" method="get" action="<?php echo esc_url( $current_url ); ?>">
	<div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
		<!-- Search by Author -->
		<div class="relative w-full sm:w-64">
			<input type="text" name="cari_penulis" placeholder="<?php esc_attr_e( 'Cari berdasarkan penulis...', 'sukusastra' ); ?>" value="<?php echo esc_attr( $cari_penulis ); ?>" class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 transition-colors">
			<!-- Search submit button -->
			<button type="submit" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors hover:text-red-700 dark:hover:text-red-400" aria-label="<?php esc_attr_e( 'Cari penulis', 'sukusastra' ); ?>">
				<svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
				</svg>
			</button>
		</div>

		<!-- Sort dropdown -->
		<select name="sort_by" onchange="this.form.submit()" class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 cursor-pointer">
			<option value="terbaru" <?php selected( $sort_by, 'terbaru' ); ?>><?php esc_html_e( 'Terbaru', 'sukusastra' ); ?></option>
			<option value="terpopuler" <?php selected( $sort_by, 'terpopuler' ); ?>><?php esc_html_e( 'Terpopuler', 'sukusastra' ); ?></option>
			<option value="abjad_a_z" <?php selected( $sort_by, 'abjad_a_z' ); ?>><?php esc_html_e( 'Abjad A-Z', 'sukusastra' ); ?></option>
			<option value="abjad_z_a" <?php selected( $sort_by, 'abjad_z_a' ); ?>><?php esc_html_e( 'Abjad Z-A', 'sukusastra' ); ?></option>
		</select>
	</div>

	<div class="flex items-center gap-2 w-full sm:w-auto">
		<button type="submit" class="ss-button px-5 py-2.5 text-xs font-black uppercase tracking-wider transition-all">
			<?php esc_html_e( 'Filter', 'sukusastra' ); ?>
		</button>
		<?php if ( '' !== $cari_penulis || 'terbaru' !== $sort_by ) : ?>
			<a href="<?php echo esc_url( $current_url ); ?>" class="ss-button-secondary px-5 py-2.5 text-xs font-black uppercase tracking-wider no-underline transition-all">
				<?php esc_html_e( 'Hapus Filter', 'sukusastra' ); ?>
			</a>
		<?php endif; ?>
	</div>
</form>
