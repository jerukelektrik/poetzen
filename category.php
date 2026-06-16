<?php
/**
 * Category archive.
 *
 * @package SukuSastra
 */
get_header();

$cat_id = get_queried_object_id();
$category = get_queried_object();
$category_slug = isset( $category->slug ) ? $category->slug : '';
$is_two_column_mobile = in_array( $category_slug, array( 'puisi', 'cerpen', 'esai' ), true );
$is_author = sukusastra_is_author_category( $cat_id );
$eyebrow = $is_author ? __( 'Penulis Suku Sastra', 'sukusastra' ) : __( 'Kategori', 'sukusastra' );
$grid_classes = $is_two_column_mobile
	? 'ss-category-archive-grid grid grid-cols-1 gap-0 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 mt-4'
	: 'grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-4';
$category_feature_posts = ( $is_two_column_mobile && isset( $GLOBALS['wp_query']->posts ) )
	? array_slice( $GLOBALS['wp_query']->posts, 0, 5 )
	: array();
?>
<section class="ss-section ss-category-page">
	<div class="ss-container">
		<div class="ss-category-breadcrumbs">
			<?php sukusastra_breadcrumbs(); ?>
		</div>
		<div class="grid gap-6">
		<?php if ( $is_author ) : ?>
			<!-- Premium Author Profile Header -->
			<header class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-6 border-b border-slate-100 pb-6 dark:border-zinc-800/80">
				<div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-red-755 font-serif text-3xl font-black text-white bg-red-700 dark:bg-zinc-800">
					<?php echo esc_html( substr( get_cat_name( $cat_id ), 0, 1 ) ); ?>
				</div>
				<div>
					<p class="ss-eyebrow mb-1"><?php echo esc_html( $eyebrow ); ?></p>
					<h1 class="ss-page-title"><?php single_cat_title(); ?></h1>
					<?php if ( category_description() ) : ?>
						<div class="mt-3 max-w-2xl ss-body"><?php echo category_description(); ?></div>
					<?php else : ?>
						<p class="mt-2 text-xs italic text-slate-500 dark:text-zinc-500"><?php printf( esc_html__( 'Kumpulan karya terpilih oleh %s.', 'sukusastra' ), get_cat_name( $cat_id ) ); ?></p>
					<?php endif; ?>
				</div>
			</header>
		<?php else : ?>
			<header class="ss-category-hero border-b border-slate-100 pb-4 dark:border-zinc-800/80">
				<p class="ss-category-hero-eyebrow ss-eyebrow mb-1"><?php echo esc_html( $eyebrow ); ?></p>
				<h1 class="ss-page-title"><?php single_cat_title(); ?></h1>
				<?php if ( category_description() ) : ?>
					<div class="mt-3 max-w-2xl ss-body"><?php echo category_description(); ?></div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<?php if ( ! empty( $category_feature_posts ) ) : ?>
			<div class="ss-category-mobile-hero md:hidden">
				<?php foreach ( $category_feature_posts as $feature_post ) : ?>
					<?php
					$feature_post_id = $feature_post->ID;
					$orig_author = sukusastra_get_original_author( $feature_post_id );
					$author_name = $orig_author ? $orig_author->post_title : get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $feature_post_id ) );
					?>
					<article <?php post_class( 'ss-category-mobile-hero-card group', $feature_post_id ); ?>>
						<a class="ss-category-mobile-hero-media" href="<?php echo esc_url( get_permalink( $feature_post_id ) ); ?>">
							<?php if ( has_post_thumbnail( $feature_post_id ) ) : ?>
								<?php echo get_the_post_thumbnail( $feature_post_id, 'sukusastra-card', array( 'class' => 'h-full w-full object-cover transition-transform duration-500 group-hover:scale-105' ) ); ?>
							<?php else : ?>
								<div class="ss-category-mobile-hero-placeholder flex items-center justify-center bg-slate-50 dark:bg-zinc-900/40 p-6 h-full w-full group-hover:scale-105 transition-transform duration-500">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.svg' ); ?>" alt="<?php echo esc_attr( get_the_title( $feature_post_id ) ); ?>" class="max-h-12 max-w-full opacity-50 dark:opacity-20 object-contain" />
								</div>
							<?php endif; ?>
						</a>
						<div class="ss-category-mobile-hero-body">
							<h2 class="ss-category-mobile-hero-title">
								<a href="<?php echo esc_url( get_permalink( $feature_post_id ) ); ?>"><?php echo esc_html( get_the_title( $feature_post_id ) ); ?></a>
							</h2>
							<div class="ss-category-mobile-hero-meta">
								<span><?php echo esc_html( $author_name ); ?></span>
								<span aria-hidden="true">•</span>
								<span><?php echo esc_html( get_the_date( '', $feature_post_id ) ); ?></span>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/filters' ); ?>
		
		<div class="<?php echo esc_attr( $grid_classes ); ?>">
			<?php if ( have_posts() ) : $archive_index = 0; while ( have_posts() ) : the_post(); $archive_index++; ?>
				<?php if ( $is_two_column_mobile ) : ?>
					<div class="ss-category-archive-card min-w-0 <?php echo $archive_index <= 5 ? 'hidden md:block' : ''; ?>">
						<?php get_template_part( 'template-parts/cards/post-card' ); ?>
					</div>
				<?php else : ?>
					<?php get_template_part( 'template-parts/cards/post-card' ); ?>
				<?php endif; ?>
			<?php endwhile; else : ?>
				<p class="col-span-full text-center py-12 text-slate-500 dark:text-zinc-400">
					<?php esc_html_e( 'Belum ada karya oleh penulis ini.', 'sukusastra' ); ?>
				</p>
			<?php endif; ?>
		</div>
		<div class="mt-6 border-t border-slate-200/20 pt-6">
			<?php sukusastra_pagination(); ?>
		</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>
