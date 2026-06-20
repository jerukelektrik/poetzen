<?php
/**
 * Banner Placement System Helpers and Rendering.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch and filter active banners based on status, schedule, and page targeting.
 *
 * @param string $placement Placement type: 'popup', 'sidebar', or 'article'.
 * @return array List of filtered and sorted active banner arrays.
 */
function poetzen_get_active_banners( string $placement ): array {
	$options = get_option( 'sukusastra_options', array() );
	if ( empty( $options['banners'][ $placement ] ) || ! is_array( $options['banners'][ $placement ] ) ) {
		return array();
	}

	$today          = current_time( 'Y-m-d' );
	$active_banners = array();

	foreach ( $options['banners'][ $placement ] as $banner ) {
		// 1. Check basic status and image
		if ( empty( $banner['status'] ) || '1' !== $banner['status'] || empty( $banner['image'] ) ) {
			continue;
		}

		// 2. Check Schedule (Start and End date)
		$start = ! empty( $banner['start_date'] ) ? $banner['start_date'] : '';
		$end   = ! empty( $banner['end_date'] ) ? $banner['end_date'] : '';

		if ( $start && $today < $start ) {
			continue;
		}
		if ( $end && $today > $end ) {
			continue;
		}

		// 3. Check Targeting Page
		$target       = ! empty( $banner['target'] ) ? $banner['target'] : 'global';
		$target_value = ! empty( $banner['target_value'] ) ? $banner['target_value'] : '';
		$is_matched   = false;

		switch ( $target ) {
			case 'global':
				$is_matched = true;
				break;

			case 'all_cat':
				if ( is_category() ) {
					$is_matched = true;
				}
				break;

			case 'cat_specific':
				if ( ! empty( $target_value ) ) {
					$values = array_map( 'trim', explode( ',', $target_value ) );
					if ( is_category() ) {
						$cat = get_queried_object();
						if ( in_array( $cat->slug, $values, true ) || in_array( (string) $cat->term_id, $values, true ) ) {
							$is_matched = true;
						}
					} elseif ( is_single() ) {
						if ( has_category( $values ) ) {
							$is_matched = true;
						}
					}
				}
				break;

			case 'all_single':
				if ( is_single() ) {
					$is_matched = true;
				}
				break;

			case 'single_specific':
				if ( ! empty( $target_value ) && is_single() ) {
					$values  = array_map( 'trim', explode( ',', $target_value ) );
					$post_id = (string) get_the_ID();
					$post    = get_post();
					if ( in_array( $post_id, $values, true ) || in_array( $post->post_name, $values, true ) ) {
						$is_matched = true;
					}
				}
				break;
		}

		if ( $is_matched ) {
			$active_banners[] = $banner;
		}
	}

	// 4. Sort Banners by Order ascending
	usort(
		$active_banners,
		function( $a, $b ) {
			$order_a = isset( $a['order'] ) ? (int) $a['order'] : 0;
			$order_b = isset( $b['order'] ) ? (int) $b['order'] : 0;
			return $order_a <=> $order_b;
		}
	);

	return $active_banners;
}

/**
 * Render Sidebar Banners.
 */
function poetzen_render_sidebar_banners(): void {
	$banners = poetzen_get_active_banners( 'sidebar' );
	if ( empty( $banners ) ) {
		return;
	}

	echo '<div class="poetzen-sidebar-banners grid gap-4 mb-6">';
	foreach ( $banners as $banner ) {
		$target_url = ! empty( $banner['url'] ) ? esc_url( $banner['url'] ) : '#';
		?>
		<div class="poetzen-sidebar-banner w-full">
			<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm transition hover:opacity-95 duration-200">
				<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php esc_attr_e( 'Advertisement', 'sukusastra' ); ?>" class="w-full h-[100px] object-cover block">
			</a>
		</div>
		<?php
	}
	echo '</div>';
}

/**
 * Render Article Banner (Full banner below post content, before author box).
 */
function poetzen_render_article_banner(): void {
	$banners = poetzen_get_active_banners( 'article' );
	if ( empty( $banners ) ) {
		return;
	}

	echo '<div class="poetzen-article-banners grid gap-4 my-8 justify-center">';
	foreach ( $banners as $banner ) {
		$target_url = ! empty( $banner['url'] ) ? esc_url( $banner['url'] ) : '#';
		?>
		<div class="poetzen-article-banner flex justify-center w-full max-w-full">
			<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm transition hover:opacity-95 duration-200 max-w-full">
				<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php esc_attr_e( 'Advertisement', 'sukusastra' ); ?>" class="w-full max-w-[468px] h-auto md:h-[60px] object-cover block" style="aspect-ratio: 468/60;">
			</a>
		</div>
		<?php
	}
	echo '</div>';
}

/**
 * Render Popup Banner (Max 5, shows highest priority active popup).
 */
function poetzen_render_popup_banners(): void {
	$banners = poetzen_get_active_banners( 'popup' );
	if ( empty( $banners ) ) {
		return;
	}

	// Only show the highest priority (first after sort) active banner
	$banner     = $banners[0];
	$target_url = ! empty( $banner['url'] ) ? esc_url( $banner['url'] ) : '#';
	$image_url  = esc_url( $banner['image'] );
	$banner_id  = md5( $image_url . $target_url );
	?>
	<div id="poetzen-popup-banner" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden opacity-0 poetzen-popup-overlay" data-popup-id="<?php echo esc_attr( $banner_id ); ?>">
		<div class="relative bg-white dark:bg-zinc-950 p-2.5 rounded-2xl shadow-2xl max-w-[90vw] max-h-[90vh] border border-slate-200/80 dark:border-zinc-800 transform scale-95 transition-transform duration-300 poetzen-popup-content">
			<button id="poetzen-popup-close" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white dark:bg-zinc-800 text-slate-800 dark:text-zinc-200 flex items-center justify-center shadow-lg border border-slate-150 dark:border-zinc-700 cursor-pointer transition hover:bg-red-50 dark:hover:bg-red-950/20" aria-label="<?php esc_attr_e( 'Tutup Iklan', 'sukusastra' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
					<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
				</svg>
			</button>
			<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block w-[250px] h-[250px] overflow-hidden rounded-xl bg-slate-50 dark:bg-zinc-900">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Promo Banner', 'sukusastra' ); ?>" class="w-full h-full object-cover block">
			</a>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var popup = document.getElementById('poetzen-popup-banner');
			if (!popup) return;

			var hasTriggered = false;

			function triggerPopup() {
				if (hasTriggered) return;
				hasTriggered = true;

				window.removeEventListener('scroll', handleScroll);

				popup.classList.remove('hidden');
				setTimeout(function() {
					popup.classList.add('opacity-100');
					popup.style.opacity = '1';
					var content = popup.querySelector('.poetzen-popup-content');
					if (content) {
						content.classList.remove('scale-95');
						content.classList.add('scale-100');
					}
					var closeBtn = document.getElementById('poetzen-popup-close');
					if (closeBtn) closeBtn.focus();
				}, 50);
			}

			function handleScroll() {
				triggerPopup();
			}

			window.addEventListener('scroll', handleScroll);

			function closePopup() {
				popup.style.opacity = '0';
				var content = popup.querySelector('.poetzen-popup-content');
				if (content) {
					content.classList.remove('scale-100');
					content.classList.add('scale-95');
				}
				setTimeout(function() {
					popup.classList.add('hidden');
				}, 300);
			}

			var closeBtn = document.getElementById('poetzen-popup-close');
			if (closeBtn) {
				closeBtn.addEventListener('click', closePopup);
			}

			popup.addEventListener('click', function(e) {
				if (e.target === popup) {
					closePopup();
				}
			});

			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' && !popup.classList.contains('hidden')) {
					closePopup();
				}
			});
		});
	</script>
	<?php
}

/**
 * Sanitize all input values for banners placement settings.
 *
 * @param array $input Banners input array from $_POST.
 * @return array Sanitized banners array.
 */
function poetzen_sanitize_banners( array $input ): array {
	$sanitized       = array();
	$placements      = array( 'catalog', 'popup', 'sidebar', 'article' );
	$allowed_targets = array( 'global', 'all_cat', 'cat_specific', 'all_single', 'single_specific' );

	foreach ( $placements as $placement ) {
		$sanitized[ $placement ] = array();
		if ( isset( $input[ $placement ] ) && is_array( $input[ $placement ] ) ) {
			foreach ( $input[ $placement ] as $i => $banner ) {
				$sanitized[ $placement ][ $i ] = array(
					'status'       => isset( $banner['status'] ) && '1' === $banner['status'] ? '1' : '0',
					'image'        => isset( $banner['image'] ) ? esc_url_raw( $banner['image'] ) : '',
					'url'          => isset( $banner['url'] ) ? esc_url_raw( $banner['url'] ) : '',
					'target'       => isset( $banner['target'] ) && in_array( $banner['target'], $allowed_targets, true ) ? $banner['target'] : 'global',
					'target_value' => isset( $banner['target_value'] ) ? sanitize_text_field( $banner['target_value'] ) : '',
					'start_date'   => isset( $banner['start_date'] ) ? sanitize_text_field( $banner['start_date'] ) : '',
					'end_date'     => isset( $banner['end_date'] ) ? sanitize_text_field( $banner['end_date'] ) : '',
					'order'        => isset( $banner['order'] ) ? (int) $banner['order'] : 0,
				);
			}
		}
	}
	return $sanitized;
}
