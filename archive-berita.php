<?php
/**
 * Berita CPT archive template.
 *
 * @package SukuSastra
 */

get_header(); ?>
<section class="ss-section">
	<div class="ss-container">
		<?php sukusastra_breadcrumbs(); ?>
		<div class="grid gap-6">
		<header class="border-b border-slate-100 pb-4 dark:border-zinc-800/80">
			<p class="ss-eyebrow mb-1"><?php esc_html_e( 'Warta Suku Sastra', 'sukusastra' ); ?></p>
			<h1 class="ss-page-title"><?php esc_html_e( 'Peristiwa & Kabar', 'sukusastra' ); ?></h1>
			<p class="mt-2 max-w-2xl ss-body"><?php esc_html_e( 'Menyajikan kabar terbaru seputar sastra, seni, kebudayaan, informasi kompetisi, serta agenda komunitas sastra tanah air.', 'sukusastra' ); ?></p>
		</header>

		<?php
		$cari_berita = isset( $_GET['cari_berita'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_berita'] ) ) : '';
		$sort_by     = isset( $_GET['sort_by'] ) ? sanitize_key( wp_unslash( $_GET['sort_by'] ) ) : 'terbaru';
		?>

		<!-- Filters and Search Bar Form -->
		<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'berita' ) ); ?>" class="bg-white dark:bg-[#262B4E] border border-slate-200/60 dark:border-zinc-800/80 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
			<div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
				<!-- Search input -->
				<div class="relative w-full sm:w-64">
					<input type="text" name="cari_berita" placeholder="<?php esc_attr_e( 'Cari peristiwa atau penulis...', 'sukusastra' ); ?>" value="<?php echo esc_attr( $cari_berita ); ?>" class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 transition-colors">
					<!-- Search SVG icon -->
					<div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
						<svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
						</svg>
					</div>
				</div>

				<!-- Sort dropdown -->
				<select name="sort_by" onchange="this.form.submit()" class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 cursor-pointer">
					<option value="terbaru" <?php selected( $sort_by, 'terbaru' ); ?>><?php esc_html_e( 'Terbaru', 'sukusastra' ); ?></option>
					<option value="terpopuler" <?php selected( $sort_by, 'terpopuler' ); ?>><?php esc_html_e( 'Terpopuler', 'sukusastra' ); ?></option>
				</select>
			</div>

			<div class="flex items-center gap-2 w-full md:w-auto">
				<button type="submit" class="ss-button px-5 py-2.5 text-xs font-black uppercase tracking-wider transition-all">
					<?php esc_html_e( 'Filter', 'sukusastra' ); ?>
				</button>
				<?php if ( '' !== $cari_berita || 'terbaru' !== $sort_by ) : ?>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'berita' ) ); ?>" class="ss-button-secondary px-5 py-2.5 text-xs font-black uppercase tracking-wider no-underline transition-all">
						<?php esc_html_e( 'Hapus Filter', 'sukusastra' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</form>

		<!-- News Loop Grid -->
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-2">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/cards/news-card' ); ?>
			<?php endwhile; else : ?>
				<div class="col-span-full rounded-md border border-slate-200 p-8 text-center dark:border-zinc-800 bg-white dark:bg-zinc-900">
					<p class="text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Tidak ada berita yang sesuai dengan pencarian Anda.', 'sukusastra' ); ?></p>
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
