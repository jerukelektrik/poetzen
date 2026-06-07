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
			<div class="grid gap-10 lg:grid-cols-[minmax(0,760px)_320px]">
				<div>
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
				<?php endif; ?>
				
				<div class="ss-reading mt-8"><?php the_content(); ?></div>
				
				<?php if ( sukusastra_get_meta( get_the_ID(), '_ss_source_url' ) ) : ?>
					<div class="mt-8 pt-6 border-t border-slate-200 dark:border-zinc-800">
						<a class="ss-button-secondary" href="<?php echo esc_url( sukusastra_get_meta( get_the_ID(), '_ss_source_url' ) ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'Sumber / Rujukan', 'sukusastra' ); ?> &rarr;
						</a>
					</div>
				<?php endif; ?>
			</div>
			<?php get_template_part( 'template-parts/sidebar-single' ); ?>
			</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
