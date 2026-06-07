<?php
/**
 * Template Name: Contact Us Page
 *
 * @package SukuSastra
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			
			<div class="grid gap-y-16 gap-x-12 lg:grid-cols-[1fr_360px]">
				<!-- Contact Form & Intro Column -->
				<div class="grid gap-8">
					<header class="border-b border-slate-100 pb-6 dark:border-zinc-800/80">
						<p class="ss-eyebrow mb-2"><?php esc_html_e( 'Hubungi Kami', 'sukusastra' ); ?></p>
						<h1 class="ss-page-title"><?php the_title(); ?></h1>
					</header>

					<div class="ss-body text-base leading-relaxed">
						<?php the_content(); ?>
					</div>

					<!-- Custom Contact Form Block (Minimalist & Premium styling) -->
					<form class="ss-card rounded-3xl p-8 grid gap-6 bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800/80 shadow-sm">
						<h2 class="ss-section-title text-lg m-0"><?php esc_html_e( 'Kirim Pesan Langsung', 'sukusastra' ); ?></h2>
						
						<div class="grid gap-4 sm:grid-cols-2">
							<div class="grid gap-1.5">
								<label for="contact_name" class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500"><?php esc_html_e( 'Nama Lengkap', 'sukusastra' ); ?></label>
								<input type="text" id="contact_name" required class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500">
							</div>
							<div class="grid gap-1.5">
								<label for="contact_email" class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500"><?php esc_html_e( 'Alamat Email', 'sukusastra' ); ?></label>
								<input type="email" id="contact_email" required class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500">
							</div>
						</div>

						<div class="grid gap-1.5">
							<label for="contact_subject" class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500"><?php esc_html_e( 'Subjek', 'sukusastra' ); ?></label>
							<input type="text" id="contact_subject" required class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500">
						</div>

						<div class="grid gap-1.5">
							<label for="contact_message" class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500"><?php esc_html_e( 'Pesan Anda', 'sukusastra' ); ?></label>
							<textarea id="contact_message" rows="5" required class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 resize-none"></textarea>
						</div>

						<button type="submit" class="ss-button py-3 font-black uppercase tracking-wider text-xs w-full sm:w-auto self-start px-8 shadow-sm"><?php esc_html_e( 'Kirim Pesan', 'sukusastra' ); ?></button>
					</form>
				</div>

				<!-- Info Sidebar Column -->
				<aside class="grid content-start gap-6">
					<!-- Contact Details Card -->
					<div class="ss-card rounded-3xl p-6 grid gap-5 bg-white dark:bg-[#262B4E]/40 shadow-sm border border-slate-200/60 dark:border-zinc-800/80">
						<h3 class="ss-info-title m-0"><?php esc_html_e( 'Informasi Kontak', 'sukusastra' ); ?></h3>
						
						<div class="grid gap-4 text-sm">
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1"><?php esc_html_e( 'Alamat Redaksi', 'sukusastra' ); ?></span>
								<a href="https://maps.app.goo.gl/2AjT7iAXrF2BSRgh6" target="_blank" class="no-underline text-slate-700 dark:text-zinc-300 hover:text-red-700 dark:hover:text-red-400 font-medium">
									Jalan Sewon Indah, Geneng, Panggungharjo, Sewon, Bantul, D.I. Yogyakarta
								</a>
							</div>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1"><?php esc_html_e( 'No. Telepon / WA', 'sukusastra' ); ?></span>
								<a href="https://wa.me/6285117175540" target="_blank" class="no-underline text-slate-700 dark:text-zinc-300 hover:text-red-700 dark:hover:text-red-400 font-medium">
									085117175540
								</a>
							</div>
							<div>
								<span class="block text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500 mb-1"><?php esc_html_e( 'Surat Elektronik', 'sukusastra' ); ?></span>
								<a href="mailto:redaksi.sukusastra@gmail.com" class="no-underline text-slate-700 dark:text-zinc-300 hover:text-red-700 dark:hover:text-red-400 font-medium">
									redaksi.sukusastra@gmail.com
								</a>
							</div>
						</div>
					</div>

					<!-- Interactive Embed Map -->
					<div class="rounded-3xl overflow-hidden border border-slate-200 dark:border-zinc-800 shadow-sm h-64 relative bg-slate-100 dark:bg-zinc-900">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.4839958739666!2d110.36067097587786!3d-7.844332892177309!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a57a075e8ef3d%3A0xe7a56114ec31bcf9!2sJl.%20Sewon%20Indah%2C%20Geneng%2C%20Panggungharjo%2C%20Kec.%20Sewon%2C%20Bantul%2C%20Daerah%20Istimewa%20Yogyakarta!5e0!3m2!1sid!2sid!4v1717772000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0"></iframe>
					</div>
				</aside>
			</div>
		</div>
	</article>
<?php endwhile; ?>

<?php get_footer(); ?>
