<?php
/**
 * Archive/search filters.
 *
 * @package SukuSastra
 */

$selected_type = isset( $_GET['jenis_konten'] ) ? sanitize_key( wp_unslash( $_GET['jenis_konten'] ) ) : '';
$selected_karya = isset( $_GET['karya'] ) ? sanitize_key( wp_unslash( $_GET['karya'] ) ) : '';
$sort_by    = isset( $_GET['sort_by'] ) ? sanitize_key( wp_unslash( $_GET['sort_by'] ) ) : 'terbaru';
$sort_order = isset( $_GET['sort_order'] ) ? sanitize_key( wp_unslash( $_GET['sort_order'] ) ) : 'desc';

if ( ! in_array( $sort_by, array( 'terbaru', 'terpopuler' ), true ) ) {
	$sort_by = 'terbaru';
}
if ( ! in_array( $sort_order, array( 'asc', 'desc' ), true ) ) {
	$sort_order = 'desc';
}

if ( is_category() ) {
	$cat_id = get_queried_object_id();
	if ( empty( $selected_karya ) ) {
		$selected_karya = get_category( $cat_id )->slug;
	}
}
?>
<form class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900 md:grid-cols-12 items-end shadow-sm" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<!-- Cari/Search -->
	<div class="md:col-span-4 grid gap-1.5">
		<label for="s" class="block text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500">
			<?php esc_html_e( 'Cari Karya atau Penulis', 'sukusastra' ); ?>
		</label>
		<div class="relative">
			<span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-zinc-500">
				<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
			</span>
			<input id="s" class="w-full rounded-lg border border-slate-200 bg-white pl-9 pr-3 py-2 text-sm text-slate-880 dark:border-zinc-800 dark:bg-zinc-950/50 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-red-755" type="search" name="s" placeholder="<?php esc_attr_e( 'Ketik judul, isi, atau nama penulis...', 'sukusastra' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
		</div>
	</div>

	<!-- Jenis Konten -->
	<div class="md:col-span-2 grid gap-1.5">
		<label for="jenis_konten" class="block text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500">
			<?php esc_html_e( 'Jenis Konten', 'sukusastra' ); ?>
		</label>
		<div class="relative">
			<select id="jenis_konten" class="w-full appearance-none rounded-lg border border-slate-200 bg-white pl-3 pr-8 py-2 text-sm text-slate-850 dark:border-zinc-800 dark:bg-zinc-950/50 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-red-750" name="jenis_konten">
				<option value=""><?php esc_html_e( 'Semua', 'sukusastra' ); ?></option>
				<option value="post" <?php selected( $selected_type, 'post' ); ?>>Post</option>
				<option value="review_buku" <?php selected( $selected_type, 'review_buku' ); ?>>Review Buku</option>
				<option value="berita" <?php selected( $selected_type, 'berita' ); ?>>Berita</option>
				<option value="event" <?php selected( $selected_type, 'event' ); ?>>Event</option>
			</select>
			<span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-450 dark:text-zinc-500">
				<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
			</span>
		</div>
	</div>

	<!-- Karya/Rubrik -->
	<div class="md:col-span-2 grid gap-1.5">
		<label for="karya" class="block text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500">
			<?php esc_html_e( 'Karya/Rubrik', 'sukusastra' ); ?>
		</label>
		<div class="relative">
			<select id="karya" class="w-full appearance-none rounded-lg border border-slate-200 bg-white pl-3 pr-8 py-2 text-sm text-slate-855 dark:border-zinc-800 dark:bg-zinc-950/50 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-red-755" name="karya">
				<option value=""><?php esc_html_e( 'Semua', 'sukusastra' ); ?></option>
				<option value="puisi" <?php selected( $selected_karya, 'puisi' ); ?>>Puisi</option>
				<option value="cerpen" <?php selected( $selected_karya, 'cerpen' ); ?>>Cerpen</option>
				<option value="esai" <?php selected( $selected_karya, 'esai' ); ?>>Esai</option>
				<option value="ruang-baca" <?php selected( $selected_karya, 'ruang-baca' ); ?>>Ruang Baca</option>
			</select>
			<span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-450 dark:text-zinc-500">
				<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
			</span>
		</div>
	</div>

	<!-- Urutkan -->
	<div class="md:col-span-2 grid gap-1.5">
		<label for="sort_by" class="block text-[11px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500">
			<?php esc_html_e( 'Urutkan', 'sukusastra' ); ?>
		</label>
		<div class="relative">
			<select id="sort_by" class="w-full appearance-none rounded-lg border border-slate-200 bg-white pl-3 pr-8 py-2 text-sm text-slate-855 dark:border-zinc-800 dark:bg-zinc-950/50 dark:text-zinc-300 focus:outline-none focus:ring-2 focus:ring-red-755" name="sort_by">
				<option value="terbaru" <?php selected( $sort_by, 'terbaru' ); ?>><?php esc_html_e( 'Terbaru', 'sukusastra' ); ?></option>
				<option value="terpopuler" <?php selected( $sort_by, 'terpopuler' ); ?>><?php esc_html_e( 'Terpopuler', 'sukusastra' ); ?></option>
			</select>
			<span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-450 dark:text-zinc-500">
				<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
			</span>
		</div>
	</div>

	<!-- Buttons -->
	<div class="md:col-span-2 flex gap-2">
		<input type="hidden" name="sort_order" id="sort_order" value="<?php echo esc_attr( $sort_order ); ?>">
		<button class="flex-1 bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-3 text-sm rounded-lg transition-colors text-center cursor-pointer dark:bg-zinc-800 dark:hover:bg-zinc-700 focus:outline-none flex items-center justify-center gap-1.5" type="submit">
			<svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
			</svg>
			<span><?php esc_html_e( 'Filter', 'sukusastra' ); ?></span>
		</button>
		<a class="flex-1 border border-slate-200 hover:bg-slate-50 dark:border-zinc-800 dark:hover:bg-zinc-950/50 text-slate-700 dark:text-zinc-300 font-bold py-2 px-2 text-sm rounded-lg transition-colors text-center flex items-center justify-center no-underline" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Reset', 'sukusastra' ); ?>
		</a>
	</div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const sortBySelect = document.getElementById('sort_by');
	if (sortBySelect) {
		sortBySelect.addEventListener('change', function() {
			sortBySelect.closest('form').submit();
		});
	}
});
</script>
