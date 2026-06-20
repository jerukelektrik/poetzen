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

					<?php
					$wa_option = sukusastra_get_option( 'whatsapp', '6285117175540' );
					$clean_num = preg_replace( '/[^0-9]/', '', $wa_option );
					if ( str_starts_with( $clean_num, '0' ) ) {
						$clean_num = '62' . substr( $clean_num, 1 );
					}
					if ( ! $clean_num ) {
						$clean_num = '6285117175540';
					}
					?>
					<!-- Custom Contact Form Block (Minimalist & Premium styling redirecting to WhatsApp) -->
					<form id="contact_wa_form" data-phone="<?php echo esc_attr( $clean_num ); ?>" class="ss-card rounded-3xl p-8 grid gap-6 bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800/80 shadow-sm">
						<h2 class="ss-section-title text-lg m-0"><?php esc_html_e( 'Kirim Pesan Langsung', 'sukusastra' ); ?></h2>
						
						<div class="grid gap-1.5">
							<label for="contact_name" class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500"><?php esc_html_e( 'Nama Lengkap', 'sukusastra' ); ?></label>
							<input type="text" id="contact_name" required class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500">
						</div>

						<div class="grid gap-1.5">
							<label for="contact_message" class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-zinc-500"><?php esc_html_e( 'Pesan Anda', 'sukusastra' ); ?></label>
							<textarea id="contact_message" rows="5" required class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-200 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-900 text-slate-900 dark:text-zinc-50 outline-none focus:border-red-700 dark:focus:border-red-500 resize-none" placeholder="<?php esc_attr_e( 'Tulis pesan Anda di sini...', 'sukusastra' ); ?>"></textarea>
						</div>

						<button type="submit" class="ss-button py-3 font-black uppercase tracking-wider text-xs w-full sm:w-auto self-start px-8 shadow-sm flex items-center justify-center gap-2">
							<svg class="w-4 h-4 fill-current" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16.01 3.2c-7.04 0-12.77 5.72-12.77 12.76 0 2.25.59 4.44 1.72 6.37L3.12 29l6.83-1.79a12.7 12.7 0 0 0 6.06 1.54c7.04 0 12.77-5.72 12.77-12.76S23.05 3.2 16.01 3.2Zm0 23.39c-1.91 0-3.79-.51-5.43-1.49l-.39-.23-4.05 1.06 1.08-3.95-.25-.41a10.56 10.56 0 0 1-1.62-5.61c0-5.86 4.78-10.63 10.65-10.63 5.86 0 10.64 4.77 10.64 10.63 0 5.86-4.78 10.63-10.64 10.63Zm5.83-7.96c-.32-.16-1.89-.93-2.18-1.04-.29-.11-.5-.16-.71.16-.21.32-.82 1.04-1.01 1.25-.19.21-.37.24-.69.08-.32-.16-1.35-.5-2.58-1.59-.95-.85-1.9-1.9-1.79-2.22-.19-.32-.02-.49.14-.65.15-.15.32-.37.48-.56.16-.19.21-.32.32-.53.11-.21.05-.4-.03-.56-.08-.16-.71-1.71-.98-2.34-.26-.62-.52-.53-.71-.54h-.61c-.21 0-.56.08-.85.4-.29.32-1.12 1.09-1.12 2.66s1.15 3.09 1.31 3.3c.16.21 2.27 3.46 5.5 4.85.77.33 1.37.53 1.84.68.77.24 1.48.21 2.04.13.62-.09 1.89-.77 2.16-1.52.27-.75.27-1.39.19-1.52-.08-.13-.29-.21-.61-.37Z"/></svg>
							<span><?php esc_html_e( 'Kirim via WhatsApp', 'sukusastra' ); ?></span>
						</button>
					</form>

					<script>
					document.addEventListener('DOMContentLoaded', function() {
						const form = document.getElementById('contact_wa_form');
						if (!form) return;

						form.addEventListener('submit', function(e) {
							e.preventDefault();
							const name = document.getElementById('contact_name').value.trim();
							const message = document.getElementById('contact_message').value.trim();
							const phone = form.getAttribute('data-phone');

							if (!name || !message || !phone) return;

							const formattedText = `Halo admin Suku Sastra, saya *${name}*.\n\n${message}`;
							const encodedText = encodeURIComponent(formattedText);
							const waUrl = `https://wa.me/${phone}?text=${encodedText}`;

							window.open(waUrl, '_blank', 'noopener');
						});
					});
					</script>
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
