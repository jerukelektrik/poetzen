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
	? 'ss-category-archive-grid grid grid-cols-2 gap-3 sm:gap-5 lg:grid-cols-3 mt-4'
	: 'grid gap-5 sm:grid-cols-2 lg:grid-cols-3 mt-4';
?>
<section class="ss-section">
	<div class="ss-container">
		<?php sukusastra_breadcrumbs(); ?>
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
			<header class="border-b border-slate-100 pb-4 dark:border-zinc-800/80">
				<p class="ss-eyebrow mb-1"><?php echo esc_html( $eyebrow ); ?></p>
				<h1 class="ss-page-title"><?php single_cat_title(); ?></h1>
				<?php if ( category_description() ) : ?>
					<div class="mt-3 max-w-2xl ss-body"><?php echo category_description(); ?></div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<?php get_template_part( 'template-parts/filters' ); ?>
		
		<div class="<?php echo esc_attr( $grid_classes ); ?>">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<?php if ( $is_two_column_mobile ) : ?>
					<div class="ss-category-archive-card min-w-0">
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
