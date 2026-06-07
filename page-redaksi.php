<?php
/**
 * Template Name: Editorial Board (Redaksi) Page
 *
 * @package SukuSastra
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			
			<div class="grid gap-12">
				<!-- Header Intro Block -->
				<header class="border-b border-slate-100 pb-6 dark:border-zinc-800/80 max-w-3xl">
					<p class="ss-eyebrow mb-2"><?php esc_html_e( 'Sidang Penulis & Pengurus', 'sukusastra' ); ?></p>
					<h1 class="ss-page-title"><?php the_title(); ?></h1>
					<p class="mt-4 ss-body text-base"><?php esc_html_e( 'Susunan keredaksian Yayasan Komunitas Sastra Suku Sastra yang mengelola, menyunting, dan memublikasikan karya-karya literasi pilihan.', 'sukusastra' ); ?></p>
				</header>

				<!-- Editorial Board Grid Cards -->
				<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
					<!-- Leader -->
					<div class="ss-card rounded-2xl p-6 text-center grid gap-3 border border-red-200/50 bg-red-50/20 dark:border-red-950/40 dark:bg-red-950/10">
						<div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-700 text-white font-serif text-2xl font-black shadow-md">F</div>
						<div>
							<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 m-0">Fairuzul Mumtaz</h3>
							<p class="text-[10px] font-black uppercase tracking-wider text-red-700 dark:text-red-400 mt-1"><?php esc_html_e( 'Pemimpin Redaksi', 'sukusastra' ); ?></p>
						</div>
					</div>

					<!-- Secretary -->
					<div class="ss-card rounded-2xl p-6 text-center grid gap-3">
						<div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 font-serif text-2xl font-black shadow-sm">M</div>
						<div>
							<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 m-0">Maharani Khan Jade</h3>
							<p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mt-1"><?php esc_html_e( 'Sekretaris', 'sukusastra' ); ?></p>
						</div>
					</div>

					<!-- Treasurer -->
					<div class="ss-card rounded-2xl p-6 text-center grid gap-3">
						<div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 font-serif text-2xl font-black shadow-sm">B</div>
						<div>
							<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 m-0">Brenda Christina Putri</h3>
							<p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mt-1"><?php esc_html_e( 'Bendahara', 'sukusastra' ); ?></p>
						</div>
					</div>

					<!-- Visual Designer -->
					<div class="ss-card rounded-2xl p-6 text-center grid gap-3">
						<div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 font-serif text-2xl font-black shadow-sm">D</div>
						<div>
							<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 m-0">Dhea Jhoty Putri</h3>
							<p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mt-1"><?php esc_html_e( 'Desain Visual', 'sukusastra' ); ?></p>
						</div>
					</div>

					<!-- Social Media -->
					<div class="ss-card rounded-2xl p-6 text-center grid gap-3">
						<div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 font-serif text-2xl font-black shadow-sm">K</div>
						<div>
							<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 m-0">Kanya Kiarra</h3>
							<p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mt-1"><?php esc_html_e( 'Media Sosial', 'sukusastra' ); ?></p>
						</div>
					</div>

					<!-- Public Relations -->
					<div class="ss-card rounded-2xl p-6 text-center grid gap-3">
						<div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 font-serif text-2xl font-black shadow-sm">Z</div>
						<div>
							<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 m-0">Zsa Zsa Yusharyahya Permata</h3>
							<p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mt-1"><?php esc_html_e( 'Humas & Kerja Sama', 'sukusastra' ); ?></p>
						</div>
					</div>

					<!-- Public Relations 2 -->
					<div class="ss-card rounded-2xl p-6 text-center grid gap-3">
						<div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300 font-serif text-2xl font-black shadow-sm">K</div>
						<div>
							<h3 class="text-base font-black text-slate-900 dark:text-zinc-50 m-0">Kind Shella Happy M.</h3>
							<p class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mt-1"><?php esc_html_e( 'Humas & Kerja Sama', 'sukusastra' ); ?></p>
						</div>
					</div>
				</div>

				<!-- Editors / Redaktur Section -->
				<div class="border-t border-slate-100 pt-8 dark:border-zinc-800/80">
					<h2 class="ss-section-title text-xl mb-6"><?php esc_html_e( 'Dewan Redaktur / Editorial Board', 'sukusastra' ); ?></h2>
					<div class="grid gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
						<?php 
						$editors = array( 'An. Ismanto', 'Muhammad Qadhafi', 'Maharani Khan Jade', 'Kanya Kiarra', 'Dhea Jhoty Putri', 'Kind Shella Happy M.' );
						foreach ( $editors as $editor ) :
							?>
							<div class="flex items-center gap-3 bg-slate-50 dark:bg-zinc-900/40 p-4 rounded-xl border border-slate-200/50 dark:border-zinc-800/60">
								<div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-200 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 font-serif text-sm font-black">
									<?php echo esc_html( substr( $editor, 0, 1 ) ); ?>
								</div>
								<span class="text-sm font-bold text-slate-800 dark:text-zinc-200"><?php echo esc_html( $editor ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- Additional Info / Text area -->
				<div class="ss-reading border-t border-slate-100 pt-8 dark:border-zinc-800/80">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</article>
<?php endwhile; ?>

<?php get_footer(); ?>
