<?php
/**
 * News single template.
 *
 * @package SukuSastra
 */
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php $youtube = sukusastra_get_meta( get_the_ID(), '_ss_youtube_url' ); ?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			<div class="grid gap-y-16 gap-x-10 lg:grid-cols-[minmax(0,760px)_320px]">
				<div>
				
				<!-- Mobile Sidebar Banner (Tampil hanya di Mobile) -->
				<div class="block lg:hidden mb-6">
					<?php poetzen_render_sidebar_banners(); ?>
				</div>

				<p class="ss-eyebrow mb-2"><?php esc_html_e( 'Berita', 'sukusastra' ); ?></p>
				<h1 class="ss-page-title"><?php the_title(); ?></h1>
				
				<!-- Media Embed Area -->
				<?php if ( $youtube ) : ?>
					<div class="mt-8 aspect-video w-full overflow-hidden rounded bg-slate-200 dark:bg-zinc-800 shadow-sm">
						<?php echo wp_oembed_get( esc_url( $youtube ) ); ?>
					</div>
				<?php elseif ( has_post_thumbnail() ) : ?>
					<div class="mt-8 rounded overflow-hidden shadow-sm">
						<?php the_post_thumbnail( 'sukusastra-hero', array( 'class' => 'w-full object-cover' ) ); ?>
					</div>
				<?php else : ?>
					<div class="mt-8 rounded overflow-hidden shadow-sm flex items-center justify-center bg-white dark:bg-[#262B4E]/40 border border-slate-200/60 dark:border-zinc-800 p-8 h-64 md:h-[320px]">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php the_title_attribute(); ?>" class="h-32 md:h-40 w-auto opacity-60 dark:opacity-30 object-contain" />
					</div>
				<?php endif; ?>
				
				<div class="ss-reading mt-8"><?php the_content(); ?></div>

				<?php poetzen_render_article_banner(); ?>

				<?php 
				$tags = get_the_tags();
				if ( $tags ) : 
					?>
					<div class="mt-8 flex flex-wrap gap-2 items-center">
						<span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-zinc-500 mr-1"><?php esc_html_e( 'Topik:', 'sukusastra' ); ?></span>
						<?php foreach ( $tags as $tag ) : ?>
							<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="px-3 py-1 text-xs font-semibold rounded-full border border-slate-200 hover:border-red-700 hover:text-red-700 dark:border-zinc-800 dark:hover:border-red-400 dark:hover:text-red-400 text-slate-650 dark:text-zinc-400 transition-colors no-underline">
								#<?php echo esc_html( $tag->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				
				<?php if ( sukusastra_get_meta( get_the_ID(), '_ss_source_url' ) ) : ?>
					<div class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800">
						<a class="ss-button-secondary" href="<?php echo esc_url( sukusastra_get_meta( get_the_ID(), '_ss_source_url' ) ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'Sumber / Rujukan', 'sukusastra' ); ?> &rarr;
						</a>
				<?php endif; ?>

				<?php sukusastra_display_related_posts(); ?>
			</div>
			<?php get_template_part( 'template-parts/sidebar-single' ); ?>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
