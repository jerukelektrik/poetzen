<?php
/**
 * Komunitas CPT archive template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<section class="ss-section">
	<div class="ss-container">
		<?php sukusastra_breadcrumbs(); ?>
		<div class="grid gap-6">
		<header class="border-b border-slate-100 pb-4 dark:border-zinc-800/80">
			<p class="ss-eyebrow mb-1"><?php esc_html_e( 'Jejaring Sastra', 'sukusastra' ); ?></p>
			<h1 class="ss-page-title"><?php esc_html_e( 'Komunitas Suku Sastra', 'sukusastra' ); ?></h1>
			<p class="mt-2 max-w-2xl ss-body"><?php esc_html_e( 'Temukan dan hubungi berbagai komunitas sastra, seni, dan budaya di seluruh Indonesia.', 'sukusastra' ); ?></p>
		</header>

		<?php
		$cari_komunitas = isset( $_GET['cari_komunitas'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_komunitas'] ) ) : '';
		$filter_prov    = isset( $_GET['filter_prov'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_prov'] ) ) : '';
		?>

		<!-- Filters and Search Bar Form -->
		<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'komunitas' ) ); ?>" class="bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800/80 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
			<div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
				<!-- Search input -->
				<div class="relative w-full sm:w-64">
					<input type="text" name="cari_komunitas" placeholder="<?php esc_attr_e( 'Cari nama atau kota...', 'sukusastra' ); ?>" value="<?php echo esc_attr( $cari_komunitas ); ?>" class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 transition-colors shadow-sm">
					<!-- Search SVG icon -->
					<div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
						<svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
						</svg>
					</div>
				</div>

				<!-- Province Filter input -->
				<div class="relative w-full sm:w-64">
					<input type="text" name="filter_prov" placeholder="<?php esc_attr_e( 'Cari provinsi...', 'sukusastra' ); ?>" value="<?php echo esc_attr( $filter_prov ); ?>" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 transition-colors shadow-sm font-semibold">
				</div>
			</div>

			<div class="flex items-center gap-2 w-full md:w-auto">
				<button type="submit" class="ss-button px-5 py-2.5 text-xs font-black uppercase tracking-wider transition-all">
					<?php esc_html_e( 'Filter', 'sukusastra' ); ?>
				</button>
				<?php if ( '' !== $cari_komunitas || '' !== $filter_prov ) : ?>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'komunitas' ) ); ?>" class="ss-button-secondary px-5 py-2.5 text-xs font-black uppercase tracking-wider no-underline transition-all">
						<?php esc_html_e( 'Reset', 'sukusastra' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</form>

		<!-- Komunitas Grid -->
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-2">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php
				$post_id    = get_the_ID();
				$comm_name  = sukusastra_get_meta( $post_id, '_ss_comm_name', get_the_title() );
				$comm_desc  = sukusastra_get_meta( $post_id, '_ss_comm_desc', '' );
				$comm_year  = sukusastra_get_meta( $post_id, '_ss_comm_year', '' );
				$comm_city  = sukusastra_get_meta( $post_id, '_ss_comm_city', '' );
				$comm_prov  = sukusastra_get_meta( $post_id, '_ss_comm_province', '' );
				?>
				<article <?php post_class( 'group flex flex-col min-w-0 bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800/80 rounded-3xl p-5 shadow-sm hover:shadow-md transition-shadow duration-300' ); ?>>
					<!-- Image placeholder / featured image -->
					<a class="block no-underline overflow-hidden rounded-2xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'sukusastra-card', array( 'class' => 'aspect-[3/2] w-full object-cover group-hover:scale-105 transition-transform duration-500' ) ); ?>
						<?php else : ?>
							<div class="ss-post-card-placeholder flex aspect-[3/2] w-full items-center justify-center bg-slate-50 dark:bg-zinc-900/40 p-6 group-hover:scale-105 transition-transform duration-500">
								<svg class="h-12 w-12 text-slate-350 dark:text-zinc-700 opacity-60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
							</div>
						<?php endif; ?>
					</a>

					<!-- Content info -->
					<div class="mt-4 flex flex-col flex-grow">
						<div class="flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-red-700 dark:text-red-400">
							<span><?php echo esc_html( $comm_city ); ?><?php echo $comm_prov ? ', ' . esc_html( $comm_prov ) : ''; ?></span>
							<?php if ( $comm_year ) : ?>
								<span class="text-slate-400 dark:text-zinc-550"><?php printf( esc_html__( 'Berdiri %s', 'sukusastra' ), esc_html( $comm_year ) ); ?></span>
							<?php endif; ?>
						</div>

						<h3 class="text-lg font-black leading-snug text-slate-900 dark:text-zinc-50 mt-1.5 group-hover:text-red-700 dark:group-hover:text-red-400 transition-colors">
							<a class="no-underline hover:text-red-700 dark:hover:text-red-400 transition-colors" href="<?php the_permalink(); ?>">
								<?php echo esc_html( $comm_name ); ?>
							</a>
						</h3>

						<?php if ( $comm_desc ) : ?>
							<p class="mt-2 text-sm leading-relaxed text-slate-650 dark:text-zinc-400 line-clamp-2">
								<?php echo esc_html( $comm_desc ); ?>
							</p>
						<?php endif; ?>

						<div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-100 dark:border-zinc-800/80">
							<a class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-zinc-300 hover:text-red-700 dark:hover:text-red-400 no-underline inline-flex items-center gap-1" href="<?php the_permalink(); ?>">
								<?php esc_html_e( 'Lihat Profil', 'sukusastra' ); ?>
								<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
							</a>
						</div>
					</div>
				</article>
			<?php endwhile; else : ?>
				<p class="text-sm text-slate-500 dark:text-zinc-400 py-6 text-center col-span-full"><?php esc_html_e( 'Belum ada komunitas terdaftar.', 'sukusastra' ); ?></p>
			<?php endif; ?>
		</div>

		<!-- Pagination -->
		<div class="mt-8 flex justify-center">
			<?php sukusastra_pagination(); ?>
		</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
