<?php
/**
 * Submit-work CTA.
 *
 * @package SukuSastra
 */

$is_home = is_front_page();
if ( $is_home ) : 
	// Render as a prominent full-width banner block below hero
	?>
	<section class="ss-section py-8 bg-red-700 text-white border-y border-red-800 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-100">
		<div class="ss-container flex flex-col md:flex-row items-center justify-between gap-6">
			<div>
				<h2 class="text-2xl font-black tracking-tight"><?php esc_html_e( 'Punya puisi, cerpen, atau esai?', 'sukusastra' ); ?></h2>
				<p class="ss-body text-red-100 dark:text-zinc-400 mt-1 max-w-xl"><?php esc_html_e( 'Baca ketentuan pengiriman dan kirimkan karya terbaikmu ke redaksi Suku Sastra.', 'sukusastra' ); ?></p>
			</div>
			<a class="ss-button bg-white text-red-700 hover:bg-slate-100 dark:bg-red-500 dark:text-zinc-950 dark:hover:bg-red-400 transition" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>">
				<?php esc_html_e( 'Lihat Ketentuan', 'sukusastra' ); ?>
			</a>
		</div>
	</section>
	<?php
else :
	// Render as a standard sidebar card widget
	?>
	<aside class="rounded-md border border-red-200 bg-red-50 p-5 dark:border-red-900/60 dark:bg-red-950/30">
		<p class="ss-eyebrow"><?php esc_html_e( 'Kirim Karya', 'sukusastra' ); ?></p>
		<h2 class="mt-2 ss-card-title text-slate-900 dark:text-zinc-50"><?php esc_html_e( 'Punya puisi, cerpen, atau esai?', 'sukusastra' ); ?></h2>
		<p class="mt-2 ss-body text-slate-700 dark:text-zinc-300"><?php esc_html_e( 'Baca ketentuan pengiriman dan kirimkan karya terbaikmu ke redaksi Suku Sastra.', 'sukusastra' ); ?></p>
		<a class="ss-button mt-4" href="<?php echo esc_url( home_url( '/ketentuan-pengiriman-karya/' ) ); ?>"><?php esc_html_e( 'Lihat Ketentuan', 'sukusastra' ); ?></a>
	</aside>
	<?php
endif;
