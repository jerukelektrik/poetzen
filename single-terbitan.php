<?php
/**
 * Single Terbitan (Publication Catalog) Template.
 *
 * @package Poetzen
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php
	$post_id = get_the_ID();
	
	// Retrieve book metadata
	$price = sukusastra_get_meta( $post_id, '_ss_book_price' );
	$author = sukusastra_get_meta( $post_id, '_ss_book_author' );
	$translator = sukusastra_get_meta( $post_id, '_ss_book_translator' );
	$publisher = sukusastra_get_meta( $post_id, '_ss_book_publisher', __( 'Yayasan Komunitas Sastra Suku Sastra', 'sukusastra' ) );
	$year = sukusastra_get_meta( $post_id, '_ss_book_year' );
	$edition = sukusastra_get_meta( $post_id, '_ss_book_edition' );
	$book_pages = sukusastra_get_meta( $post_id, '_ss_book_pages' );
	$isbn = sukusastra_get_meta( $post_id, '_ss_book_isbn' );
	$dimensions = sukusastra_get_meta( $post_id, '_ss_book_dimensions' );
	$cover_type = sukusastra_get_meta( $post_id, '_ss_book_cover_type' );

	$original_title = sukusastra_get_meta( $post_id, '_ss_book_original_title' );
	$translator_en  = sukusastra_get_meta( $post_id, '_ss_book_translator_en' );
	$city           = sukusastra_get_meta( $post_id, '_ss_book_city' );
	$paper          = sukusastra_get_meta( $post_id, '_ss_book_paper' );
	$editor         = sukusastra_get_meta( $post_id, '_ss_book_editor' );
	$proofreader    = sukusastra_get_meta( $post_id, '_ss_book_proofreader' );
	$layout         = sukusastra_get_meta( $post_id, '_ss_book_layout' );
	$cover_design   = sukusastra_get_meta( $post_id, '_ss_book_cover_design' );

	// Build WhatsApp order link
	$wa_meta = sukusastra_get_meta( $post_id, '_ss_book_whatsapp', '' );
	if ( ! $wa_meta ) {
		$options = get_option( 'sukusastra_options', array() );
		$wa_meta = isset( $options['whatsapp'] ) && '' !== $options['whatsapp'] ? $options['whatsapp'] : '628388966273';
	}

	if ( str_starts_with( $wa_meta, 'http' ) ) {
		$wa_url = $wa_meta;
	} else {
		$clean_num = preg_replace( '/[^0-9]/', '', $wa_meta );
		if ( str_starts_with( $clean_num, '0' ) ) {
			$clean_num = '62' . substr( $clean_num, 1 );
		}
		if ( ! $clean_num ) {
			$clean_num = '628388966273';
		}
		
		$message = sprintf(
			__( 'Halo Suku Sastra, saya ingin memesan buku *%s* terbitan Suku Sastra.', 'sukusastra' ),
			get_the_title()
		);
		$wa_url = 'https://api.whatsapp.com/send?phone=' . $clean_num . '&text=' . rawurlencode( $message );
	}
	?>

	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			
			<div class="grid gap-y-12 gap-x-10 lg:grid-cols-[380px_1fr] items-start">
				
				<!-- Left Column: Portrait Carousel Gallery -->
				<div class="w-full max-w-[380px] mx-auto lg:mx-0 lg:sticky lg:top-24">
					<div class="bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800 rounded-3xl p-5 shadow-sm">
						<?php get_template_part( 'template-parts/terbitan-gallery' ); ?>
					</div>
				</div>

				<!-- Right Column: Book Metadata and Details -->
				<div class="flex flex-col gap-6">
					<div>
						<p class="ss-eyebrow mb-2">
							<?php esc_html_e( 'Katalog Terbitan Suku Sastra', 'sukusastra' ); ?>
						</p>
						<h1 class="ss-page-title leading-tight mb-4"><?php the_title(); ?></h1>
						
						<!-- Top-level Book Identity list -->
						<div class="space-y-2 text-sm text-slate-700 dark:text-zinc-300 font-serif border-l-2 border-red-700 dark:border-red-500 pl-4 py-1 bg-slate-50/50 dark:bg-zinc-900/20 rounded-r-xl pr-4">
							<div>
								<span class="font-bold inline-block w-24 text-slate-400 dark:text-zinc-550 font-sans text-xs uppercase tracking-wide"><?php esc_html_e( 'Judul Asli:', 'sukusastra' ); ?></span>
								<span class="italic"><?php echo esc_html( $original_title ? $original_title : '-' ); ?></span>
							</div>
							<div>
								<span class="font-bold inline-block w-24 text-slate-400 dark:text-zinc-550 font-sans text-xs uppercase tracking-wide"><?php esc_html_e( 'Penulis:', 'sukusastra' ); ?></span>
								<span><?php echo esc_html( $author ? $author : '-' ); ?></span>
							</div>
							<div>
								<span class="font-bold inline-block w-24 text-slate-400 dark:text-zinc-550 font-sans text-xs uppercase tracking-wide"><?php esc_html_e( 'Penerjemah:', 'sukusastra' ); ?></span>
								<span><?php echo esc_html( $translator ? $translator : '-' ); ?></span>
							</div>
							<div>
								<span class="font-bold inline-block w-24 text-slate-400 dark:text-zinc-550 font-sans text-xs uppercase tracking-wide"><?php esc_html_e( 'Penyunting:', 'sukusastra' ); ?></span>
								<span><?php echo esc_html( $editor ? $editor : '-' ); ?></span>
							</div>
							<div>
								<span class="font-bold inline-block w-24 text-slate-400 dark:text-zinc-550 font-sans text-xs uppercase tracking-wide"><?php esc_html_e( 'Penerbit:', 'sukusastra' ); ?></span>
								<span><?php echo esc_html( $publisher ? $publisher : '-' ); ?></span>
							</div>
						</div>
					</div>

					<!-- Pricing and WhatsApp order CTA -->
					<div class="p-5 md:p-6 rounded-3xl bg-red-50/40 dark:bg-red-950/10 border border-red-200/30 dark:border-red-900/20 flex flex-col sm:flex-row sm:items-center justify-between gap-5 shadow-inner">
						<div>
							<span class="text-[10px] font-black text-slate-400 dark:text-zinc-550 uppercase tracking-wider block mb-1">
								<?php esc_html_e( 'Harga Buku', 'sukusastra' ); ?>
							</span>
							<span class="text-3xl font-black text-red-700 dark:text-red-400 font-display">
								<?php echo esc_html( $price ? $price : __( 'Hubungi Kami', 'sukusastra' ) ); ?>
							</span>
						</div>
						<div class="shrink-0">
							<a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-full text-white bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-700 hover:to-emerald-600 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 no-underline font-black text-xs uppercase tracking-wider border border-transparent">
								<!-- WhatsApp icon -->
								<svg class="h-4 w-4 shrink-0 overflow-visible fill-current" viewBox="0 0 32 32" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg">
									<path d="M16 5.25c-5.88 0-10.65 4.67-10.65 10.43 0 1.98.57 3.84 1.57 5.42l-1.02 5.65 5.82-1.33c1.3.45 2.74.7 4.28.7 5.88 0 10.65-4.67 10.65-10.44S21.88 5.25 16 5.25Zm0 18.92c-1.39 0-2.69-.28-3.84-.8l-.38-.17-3.22.74.57-3.13-.24-.4a8.47 8.47 0 0 1-1.35-4.73c0-4.69 3.8-8.49 8.46-8.49s8.46 3.8 8.46 8.49c0 4.68-3.8 8.49-8.46 8.49Z"/>
									<path d="M20.75 18.44c-.25-.13-1.47-.73-1.7-.81-.23-.09-.39-.13-.56.13-.16.25-.64.81-.79.97-.14.17-.29.19-.54.07-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.48-1.38-1.73-.15-.25-.02-.39.11-.52.12-.11.25-.29.38-.44.13-.15.17-.25.25-.42.08-.16.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.4-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.22.25-.86.85-.86 2.07s.88 2.4 1.01 2.56c.12.17 1.74 2.69 4.23 3.77.59.25 1.05.4 1.41.51.59.19 1.13.16 1.55.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.08.15-1.18-.06-.11-.23-.17-.48-.29Z"/>
								</svg>
								<span><?php esc_html_e( 'Pesan via WhatsApp', 'sukusastra' ); ?></span>
							</a>
						</div>
					</div>

					<!-- Premium Tabbed Interface -->
					<div class="mt-4">
						<div class="flex gap-6 border-b border-slate-200 dark:border-zinc-800/80 mb-6 text-sm font-bold tracking-wider uppercase">
							<button class="ss-tab-trigger active pb-3 border-b-2 border-red-700 dark:border-red-500 text-red-700 dark:text-red-500 cursor-pointer transition-all focus:outline-none" data-target="ss-tab-deskripsi">
								<?php esc_html_e( 'Deskripsi Buku', 'sukusastra' ); ?>
							</button>
							<button class="ss-tab-trigger pb-3 border-b-2 border-transparent text-slate-400 dark:text-zinc-550 hover:text-red-700 dark:hover:text-red-500 cursor-pointer transition-all focus:outline-none" data-target="ss-tab-detail">
								<?php esc_html_e( 'Detail Buku', 'sukusastra' ); ?>
							</button>
						</div>

						<div class="ss-tab-panes">
							<!-- Tab 1: Synopsis/Description -->
							<div id="ss-tab-deskripsi" class="ss-tab-pane active ss-reading">
								<?php the_content(); ?>
							</div>

							<!-- Tab 2: Full Specifications Table -->
							<div id="ss-tab-detail" class="ss-tab-pane hidden">
								<div class="bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800 rounded-3xl p-6 shadow-sm">
									<div class="grid gap-x-6 gap-y-3 sm:grid-cols-2 text-sm">
										<?php if ( $original_title ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Judul Asli (Original Title)', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold italic"><?php echo esc_html( $original_title ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $author ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Penulis (Author)', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $author ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $translator ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Penerjemah (Indonesian Translator)', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $translator ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $translator_en ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Penerjemah Ed. Inggris', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $translator_en ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $editor ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Penyunting (Editor)', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $editor ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $proofreader ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Proofreader', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $proofreader ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $layout ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Penata Letak (Layout Designer)', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $layout ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $cover_design ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Desain Sampul (Cover Designer)', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $cover_design ); ?></span>
											</div>
										<?php endif; ?>

										<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
											<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Penerbit', 'sukusastra' ); ?></span>
											<span class="text-slate-800 dark:text-zinc-200 font-bold text-right pl-4"><?php echo esc_html( $publisher ); ?></span>
										</div>

										<?php if ( $city ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Kota Terbit', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $city ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $year ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Tahun Terbit', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $year ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $edition ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Cetakan', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $edition ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $book_pages ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Jumlah Halaman', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php printf( esc_html__( '%s hlm', 'sukusastra' ), esc_html( $book_pages ) ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $paper ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Kertas (Paper Type)', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $paper ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $dimensions ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Ukuran Dimensi', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $dimensions ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $cover_type ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'Jenis Sampul', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $cover_type ); ?></span>
											</div>
										<?php endif; ?>

										<?php if ( $isbn ) : ?>
											<div class="flex justify-between py-1.5 border-b border-slate-100/50 dark:border-zinc-800/40">
												<span class="text-slate-400 dark:text-zinc-550 font-semibold"><?php esc_html_e( 'ISBN', 'sukusastra' ); ?></span>
												<span class="text-slate-800 dark:text-zinc-200 font-bold"><?php echo esc_html( $isbn ); ?></span>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Tags -->
					<?php
					$tags = get_the_tags();
					if ( $tags ) :
						?>
						<div class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t border-slate-100/55 dark:border-zinc-800/30">
							<span class="text-xs font-bold text-slate-400 dark:text-zinc-550 uppercase tracking-wider mr-1"><?php esc_html_e( 'Tags:', 'sukusastra' ); ?></span>
							<?php foreach ( $tags as $tag ) : ?>
								<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="text-xs bg-slate-100 hover:bg-slate-200 dark:bg-zinc-800/50 dark:hover:bg-zinc-800 text-slate-600 dark:text-zinc-350 px-3 py-1 rounded-full no-underline transition-colors border border-slate-200/40 dark:border-zinc-800/50">
									#<?php echo esc_html( $tag->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<!-- Recommendations Section: Related Katalog Terbitan -->
			<?php
			$other_books = new WP_Query(
				array(
					'post_type'      => 'terbitan',
					'posts_per_page' => 4,
					'post__not_in'   => array( $post_id ),
					'post_status'    => 'publish',
				)
			);
			if ( $other_books->have_posts() ) :
				?>
				<div class="related_katalog_terbitan mt-20 border-t border-slate-200/60 dark:border-zinc-800 pt-12">
					<h2 class="ss-section-title mb-8 flex items-center gap-2 font-display">
						<span><?php esc_html_e( 'Katalog Terbitan Terkait', 'sukusastra' ); ?></span>
					</h2>
					<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
						<?php 
						while ( $other_books->have_posts() ) : 
							$other_books->the_post(); 
							get_template_part( 'template-parts/cards/terbitan-card' );
						endwhile; 
						wp_reset_postdata(); 
						?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</article>
<?php endwhile; ?>

<!-- Tabs Switcher JS script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	const triggers = document.querySelectorAll('.ss-tab-trigger');
	const panes = document.querySelectorAll('.ss-tab-pane');
	
	if (triggers.length === 0 || panes.length === 0) return;

	triggers.forEach(trigger => {
		trigger.addEventListener('click', function() {
			const targetId = this.getAttribute('data-target');
			
			// Reset all triggers active state classes
			triggers.forEach(t => {
				t.classList.remove('active', 'border-red-700', 'dark:border-red-500', 'text-red-700', 'dark:text-red-500');
				t.classList.add('border-transparent', 'text-slate-400', 'dark:text-zinc-550');
			});
			
			// Add active classes to clicked tab
			this.classList.add('active', 'border-red-700', 'dark:border-red-500', 'text-red-700', 'dark:text-red-500');
			this.classList.remove('border-transparent', 'text-slate-400', 'dark:text-zinc-550');
			
			// Toggle panels visibility
			panes.forEach(pane => {
				if (pane.id === targetId) {
					pane.classList.remove('hidden');
					pane.classList.add('active');
				} else {
					pane.classList.add('hidden');
					pane.classList.remove('active');
				}
			});
		});
	});
});
</script>

<?php get_footer(); ?>
