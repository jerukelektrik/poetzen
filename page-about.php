<?php
/**
 * Template Name: About Us Page
 *
 * @package SukuSastra
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
	<article class="ss-section">
		<div class="ss-container">
			<?php sukusastra_breadcrumbs(); ?>
			
			<div class="grid gap-y-16 gap-x-12 lg:grid-cols-[1fr_320px]">
				<!-- Main Content Column -->
				<div class="grid gap-8">
					<header class="border-b border-slate-100 pb-6 dark:border-zinc-800/80">
						<p class="ss-eyebrow mb-2"><?php esc_html_e( 'Tentang Kami', 'sukusastra' ); ?></p>
						<h1 class="ss-page-title"><?php the_title(); ?></h1>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="rounded-3xl overflow-hidden shadow-md border border-slate-100 dark:border-zinc-800/80">
							<?php the_post_thumbnail( 'sukusastra-hero', array( 'class' => 'w-full object-cover aspect-[21/9]' ) ); ?>
						</div>
					<?php endif; ?>

					<div class="ss-reading">
						<?php the_content(); ?>
					</div>
				</div>

				<!-- Sidebar Column -->
				<aside class="grid content-start gap-8">
					<!-- Organization Card -->
					<div class="ss-card rounded-2xl p-6">
						<h3 class="ss-info-title m-0 mb-3"><?php esc_html_e( 'Yayasan Kami', 'sukusastra' ); ?></h3>
						<p class="text-sm text-slate-700 dark:text-zinc-300 leading-relaxed">
							<?php esc_html_e( 'Yayasan Komunitas Sastra Suku Sastra (YKS3) merupakan organisasi nirlaba yang berfokus pada pengembangan, pelestarian, dan publikasi karya kesusastraan.', 'sukusastra' ); ?>
						</p>
					</div>

					<!-- Programs List -->
					<div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-zinc-800 dark:bg-[#262B4E]/40 shadow-sm">
						<h3 class="ss-widget-title mb-4"><?php esc_html_e( 'Fokus Program', 'sukusastra' ); ?></h3>
						<ul class="grid gap-3 text-sm font-bold text-slate-700 dark:text-zinc-300 list-none p-0 m-0">
							<li class="flex items-center gap-2">
								<span class="w-1.5 h-1.5 rounded-full bg-red-700 dark:bg-red-400"></span>
								<span><?php esc_html_e( 'Kelas Menulis', 'sukusastra' ); ?></span>
							</li>
							<li class="flex items-center gap-2">
								<span class="w-1.5 h-1.5 rounded-full bg-red-700 dark:bg-red-400"></span>
								<span><?php esc_html_e( 'Penerbitan & Publikasi', 'sukusastra' ); ?></span>
							</li>
							<li class="flex items-center gap-2">
								<span class="w-1.5 h-1.5 rounded-full bg-red-700 dark:bg-red-400"></span>
								<span><?php esc_html_e( 'Diskusi & Bedah Buku', 'sukusastra' ); ?></span>
							</li>
							<li class="flex items-center gap-2">
								<span class="w-1.5 h-1.5 rounded-full bg-red-700 dark:bg-red-400"></span>
								<span><?php esc_html_e( 'Festival Sastra', 'sukusastra' ); ?></span>
							</li>
							<li class="flex items-center gap-2">
								<span class="w-1.5 h-1.5 rounded-full bg-red-700 dark:bg-red-400"></span>
								<span><?php esc_html_e( 'Sastra Pemberdayaan', 'sukusastra' ); ?></span>
							</li>
						</ul>
					</div>

					<!-- Submission CTA -->
					<?php get_template_part( 'template-parts/cta-submit' ); ?>
				</aside>
			</div>
		</div>
	</node>
<?php endwhile; ?>

<?php get_footer(); ?>
