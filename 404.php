<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package SukuSastra
 */

get_header(); ?>

<section class="ss-section">
	<div class="ss-container max-w-4xl mx-auto flex flex-col items-center text-center py-10 md:py-20">
		
		<!-- Large visual error indicator -->
		<div class="text-7xl md:text-9xl font-black text-red-700 dark:text-red-500 font-serif leading-none tracking-tight mb-4">
			<?php esc_html_e( '404', 'sukusastra' ); ?>
		</div>

		<!-- Title -->
		<h1 class="ss-page-title font-serif mb-3">
			<?php esc_html_e( 'Halaman Tidak Ditemukan', 'sukusastra' ); ?>
		</h1>

		<!-- Poetic Indonesian Message -->
		<p class="ss-body-serif max-w-xl text-base leading-7 mb-8 italic">
			<?php esc_html_e( 'Seperti bait sajak yang terhapus waktu atau sebuah kisah yang hilang dalam ingatan angin malam... Halaman yang Anda cari tidak dapat kami temukan di sini.', 'sukusastra' ); ?>
		</p>

		<!-- Home Button CTA -->
		<div class="flex flex-wrap gap-4 justify-center mb-10">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ss-button shadow-md">
				<?php esc_html_e( 'Kembali ke Beranda', 'sukusastra' ); ?>
			</a>
		</div>



	</div>
</section>

<?php get_footer(); ?>
