<?php
/**
 * Template Name: Submit Work (Kirim Naskah) Page
 *
 * @package SukuSastra
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			
			<div class="grid gap-y-16 gap-x-12 lg:grid-cols-[1fr_360px]">
				<!-- Main Guidelines Column -->
				<div class="grid gap-8">
					<header class="border-b border-slate-100 pb-6 dark:border-zinc-800/80">
						<p class="ss-eyebrow mb-2"><?php esc_html_e( 'Kontribusi Karya', 'sukusastra' ); ?></p>
						<h1 class="ss-page-title"><?php the_title(); ?></h1>
					</header>

					<!-- Important Alert Box -->
					<div class="rounded-2xl border-l-4 border-amber-500 bg-amber-50/60 dark:bg-amber-950/20 p-5 dark:border-amber-600/70 text-slate-800 dark:text-zinc-200 shadow-sm leading-relaxed">
						<h3 class="text-sm font-black uppercase tracking-wider text-amber-800 dark:text-amber-400 m-0 mb-2"><?php esc_html_e( 'Prioritas Kuratorial Suku Sastra', 'sukusastra' ); ?></h3>
						<p class="text-sm m-0">
							<strong><?php esc_html_e( 'Kami memprioritaskan untuk membaca lebih cermat dan memublikasikan naskah yang mengangkat tema kesadaran lingkungan dan perubahan iklim.', 'sukusastra' ); ?></strong> <?php esc_html_e( 'Meskipun demikian, kami tidak menutup kemungkinan untuk memuat karya di luar tema tersebut dengan pertimbangan khusus.', 'sukusastra' ); ?>
						</p>
					</div>

					<div class="ss-reading">
						<?php the_content(); ?>
					</div>

					<!-- Direct Form Submit Section Card -->
					<div class="ss-card rounded-3xl p-8 bg-red-700 text-white border border-red-800 dark:bg-zinc-900/60 dark:border-zinc-800/80 dark:text-zinc-100 shadow-md flex flex-col sm:flex-row items-center justify-between gap-6">
						<div>
							<h2 class="text-xl font-black tracking-tight m-0"><?php esc_html_e( 'Siap Mengirimkan Karya?', 'sukusastra' ); ?></h2>
							<p class="text-xs text-red-100 dark:text-zinc-400 mt-1 max-w-md leading-relaxed"><?php esc_html_e( 'Kirim naskah tulisan Anda melalui Google Form resmi redaksi Suku Sastra.', 'sukusastra' ); ?></p>
						</div>
						<a class="ss-button bg-white text-red-700 hover:bg-slate-100 dark:bg-red-500 dark:text-zinc-950 dark:hover:bg-red-400 px-6 py-3 font-black text-xs uppercase tracking-wider no-underline rounded-xl shadow transition" href="https://forms.gle/gbwqaw67Gxoes13n7" target="_blank">
							<?php esc_html_e( 'Isi Formulir', 'sukusastra' ); ?>
						</a>
					</div>
				</div>

				<!-- Sidebar Info Column -->
				<aside class="grid content-start gap-6">
					<!-- Rate of Appreciation (Honorarium) Card -->
					<div class="ss-card rounded-3xl p-6 bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800/80 shadow-sm grid gap-5">
						<h3 class="ss-info-title m-0"><?php esc_html_e( 'Apresiasi Redaksi', 'sukusastra' ); ?></h3>
						
						<div class="grid gap-4 text-sm">
							<div class="pb-3 border-b border-slate-100 dark:border-zinc-800/60">
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1"><?php esc_html_e( 'Puisi (5 - 10 Judul)', 'sukusastra' ); ?></span>
								<strong class="text-base text-slate-900 dark:text-zinc-50">Rp 100.000,00</strong>
								<span class="block text-xs text-slate-500 dark:text-zinc-400 mt-0.5"><?php esc_html_e( '*Apresiasi diberikan untuk minimal 4 judul puisi yang dimuat.', 'sukusastra' ); ?></span>
							</div>

							<div class="pb-3 border-b border-slate-100 dark:border-zinc-800/60">
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1"><?php esc_html_e( 'Cerpen & Esai (500 - 2500 Kata)', 'sukusastra' ); ?></span>
								<strong class="text-base text-slate-900 dark:text-zinc-50">Rp 200.000,00</strong>
								<span class="block text-xs text-slate-500 dark:text-zinc-400 mt-0.5"><?php esc_html_e( '*Diutamakan esai sastra; dimuat minimal 1 kali sebulan.', 'sukusastra' ); ?></span>
							</div>

							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1"><?php esc_html_e( 'Resensi Buku (700 - 1000 Kata)', 'sukusastra' ); ?></span>
								<strong class="text-base text-slate-900 dark:text-zinc-50">Rp 100.000,00</strong>
								<span class="block text-xs text-slate-500 dark:text-zinc-400 mt-0.5"><?php esc_html_e( '*Harus buku sastra; dimuat minimal 1 kali sebulan.', 'sukusastra' ); ?></span>
							</div>
						</div>
					</div>

					<!-- Important Checklist Card -->
					<div class="rounded-3xl border border-slate-200 bg-white p-6 dark:border-zinc-800 dark:bg-[#262B4E]/40 shadow-sm">
						<h3 class="ss-widget-title mb-4"><?php esc_html_e( 'Persyaratan Utama', 'sukusastra' ); ?></h3>
						<ul class="grid gap-3 text-sm text-slate-700 dark:text-zinc-300 list-none p-0 m-0 leading-relaxed">
							<li class="flex gap-2">
								<svg class="w-4 h-4 text-green-600 dark:text-green-400 shrink-0 mt-0.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
								<span><?php esc_html_e( 'Karya orisinal & belum pernah dipublikasikan di media lain.', 'sukusastra' ); ?></span>
							</li>
							<li class="flex gap-2">
								<svg class="w-4 h-4 text-green-600 dark:text-green-400 shrink-0 mt-0.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
								<span><?php esc_html_e( 'Format pengiriman naskah berupa dokumen (.doc/.docx), bukan PDF.', 'sukusastra' ); ?></span>
							</li>
							<li class="flex gap-2">
								<svg class="w-4 h-4 text-green-600 dark:text-green-400 shrink-0 mt-0.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
								<span><?php esc_html_e( 'Naskah dikirim melalui formulir Google Form resmi, bukan e-mail.', 'sukusastra' ); ?></span>
							</li>
						</ul>
					</div>
				</aside>
			</div>
		</div>
	</article>
<?php endwhile; ?>

<?php get_footer(); ?>
