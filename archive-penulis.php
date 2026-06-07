<?php
/**
 * Penulis CPT archive template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<section class="ss-section">
	<div class="ss-container">
		<?php sukusastra_breadcrumbs(); ?>
		<div class="grid gap-6">
		<header class="border-b border-slate-100 pb-4 dark:border-zinc-800/80">
			<p class="ss-eyebrow mb-1"><?php esc_html_e( 'Direktori Tokoh', 'sukusastra' ); ?></p>
			<h1 class="ss-page-title"><?php esc_html_e( 'Penulis Suku Sastra', 'sukusastra' ); ?></h1>
			<p class="mt-2 max-w-2xl ss-body"><?php esc_html_e( 'Telusuri profil sastrawan, penulis, esais, penyair, dan kurator karya terbaik di portal Suku Sastra.', 'sukusastra' ); ?></p>
		</header>

		<?php
		$search = isset( $_GET['cari_penulis'] ) ? sanitize_text_field( wp_unslash( $_GET['cari_penulis'] ) ) : '';
		$urut   = isset( $_GET['urut_penulis'] ) ? sanitize_key( wp_unslash( $_GET['urut_penulis'] ) ) : 'a-z';
		?>

		<!-- Filters and Search Bar Form -->
		<form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'penulis' ) ); ?>" class="bg-white dark:bg-[#262B4E] border border-slate-200/60 dark:border-zinc-800/80 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
			<div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
				<!-- Search input -->
				<div class="relative w-full sm:w-64">
					<input type="text" name="cari_penulis" placeholder="<?php esc_attr_e( 'Cari penulis...', 'sukusastra' ); ?>" value="<?php echo esc_attr( $search ); ?>" class="w-full pl-10 pr-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 transition-colors">
					<!-- Search SVG icon -->
					<div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
						<svg class="w-4 h-4 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
						</svg>
					</div>
				</div>

				<!-- Sort dropdown -->
				<select name="urut_penulis" onchange="this.form.submit()" class="px-4 py-2 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 cursor-pointer">
					<option value="a-z" <?php selected( $urut, 'a-z' ); ?>><?php esc_html_e( 'Nama A-Z', 'sukusastra' ); ?></option>
					<option value="z-a" <?php selected( $urut, 'z-a' ); ?>><?php esc_html_e( 'Nama Z-A', 'sukusastra' ); ?></option>
				</select>
			</div>

			<div class="flex items-center gap-2 w-full md:w-auto">
				<button type="submit" class="ss-button px-5 py-2.5 text-xs font-black uppercase tracking-wider transition-all">
					<?php esc_html_e( 'Filter', 'sukusastra' ); ?>
				</button>
				<?php if ( '' !== $search || 'a-z' !== $urut ) : ?>
					<a href="<?php echo esc_url( get_post_type_archive_link( 'penulis' ) ); ?>" class="ss-button-secondary px-5 py-2.5 text-xs font-black uppercase tracking-wider no-underline transition-all">
						<?php esc_html_e( 'Hapus Filter', 'sukusastra' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</form>

		<!-- Writers Loop Grid -->
		<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-2">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article class="ss-card rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center group">
					<!-- Writer Portrait -->
					<div class="relative p-[4px] rounded-full bg-gradient-to-tr from-slate-200 to-slate-300 dark:from-zinc-800 dark:to-zinc-700 group-hover:from-red-700 group-hover:via-amber-500 group-hover:to-yellow-500 transition-all duration-500 mb-4 shadow">
						<div class="bg-white dark:bg-[#262B4E] p-[3px] rounded-full">
							<?php 
							$avatar_url = '';
							if ( has_post_thumbnail() ) {
								$avatar_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
							}
							if ( ! $avatar_url ) {
								$avatar_url = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=120&h=120&fit=crop';
							}
							?>
							<img src="<?php echo esc_url( $avatar_url ); ?>" alt="<?php the_title(); ?>" class="w-20 h-20 rounded-full object-cover">
						</div>
					</div>

					<!-- Writer Name -->
					<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 leading-tight">
						<a class="no-underline hover:text-red-700 dark:hover:text-red-400" href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h3>

					<!-- Birth Place & Date -->
					<?php 
					$birth_place = get_post_meta( get_the_ID(), '_ss_penulis_tempat_lahir', true );
					$birth_date = get_post_meta( get_the_ID(), '_ss_penulis_tanggal_lahir', true );
					if ( $birth_place || $birth_date ) : ?>
						<p class="text-[10px] font-semibold text-slate-400 dark:text-zinc-500 mt-1.5 flex items-center gap-1">
							<svg class="w-3 h-3 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
								<path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
							</svg>
							<span><?php echo esc_html( trim( implode( ', ', array_filter( array( $birth_place, $birth_date ) ) ) ) ); ?></span>
						</p>
					<?php endif; ?>

					<!-- Bio Summary -->
					<?php 
					$bio = get_post_meta( get_the_ID(), '_ss_penulis_bio_summary', true );
					if ( $bio ) : ?>
						<p class="text-[11px] leading-relaxed text-slate-500 dark:text-zinc-400 mt-3 font-serif line-clamp-2 italic">
							<?php echo esc_html( $bio ); ?>
						</p>
					<?php else : ?>
						<p class="text-[11px] leading-relaxed text-slate-400 dark:text-zinc-500 mt-3 font-serif line-clamp-2 italic">
							<?php esc_html_e( 'Sastrawan terkemuka yang berkontribusi aktif di media Suku Sastra.', 'sukusastra' ); ?>
						</p>
					<?php endif; ?>

					<!-- Works Count Badge -->
					<?php
					$works_count = get_posts( array(
						'post_type'      => array( 'post', 'review_buku' ),
						'posts_per_page' => -1,
						'meta_query'     => array(
							array(
								'key'     => '_ss_original_author_id',
								'value'   => get_the_ID(),
								'compare' => '=',
							),
						),
						'fields'         => 'ids',
					) );
					$count = count( $works_count );
					?>
					<div class="mt-4 bg-slate-100 dark:bg-zinc-800/80 px-3 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider text-slate-600 dark:text-zinc-400">
						<?php printf( _n( '%d Karya', '%d Karya', $count, 'sukusastra' ), $count ); ?>
					</div>

					<!-- CTA Link -->
					<a class="mt-5 text-[10px] font-black uppercase tracking-wider text-red-700 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 no-underline flex items-center gap-1 transition-all group-hover:translate-x-0.5" href="<?php the_permalink(); ?>">
						<span>Lihat Profil & Karya</span>
						<svg class="w-3.5 h-3.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
						</svg>
					</a>
				</article>
			<?php endwhile; else : ?>
				<div class="col-span-full rounded-md border border-slate-200 p-8 text-center dark:border-zinc-800 bg-white dark:bg-zinc-900">
					<p class="text-slate-500 dark:text-zinc-400"><?php esc_html_e( 'Tidak ada penulis yang sesuai dengan pencarian Anda.', 'sukusastra' ); ?></p>
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
