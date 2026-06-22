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

	$has_multiple = count( $banners ) > 1;

	if ( ! $has_multiple ) {
		$banner     = $banners[0];
		$target_url = ! empty( $banner['url'] ) ? esc_url( $banner['url'] ) : '#';
		?>
		<div class="poetzen-sidebar-banners mb-6 w-full">
			<div class="poetzen-sidebar-banner w-full">
				<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm transition hover:opacity-95 duration-200">
					<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php esc_attr_e( 'Advertisement', 'sukusastra' ); ?>" class="w-full h-[100px] object-cover block">
				</a>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="poetzen-sidebar-slider relative w-full overflow-hidden rounded-xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm mb-6" style="height: 100px;" id="poetzen-sidebar-slider-container">
			<div class="poetzen-sidebar-slider-track flex transition-transform duration-500 ease-in-out h-full w-full">
				<?php foreach ( $banners as $banner ) : 
					$target_url = ! empty( $banner['url'] ) ? esc_url( $banner['url'] ) : '#';
					$image_url  = esc_url( $banner['image'] );
					?>
					<div class="poetzen-sidebar-slide min-w-full w-full h-full flex-shrink-0">
						<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block w-full h-full">
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Advertisement', 'sukusastra' ); ?>" class="w-full h-full object-cover block">
						</a>
					</div>
				<?php endforeach; ?>
			</div>
			<!-- Slider dots / indicators -->
			<div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
				<?php foreach ( $banners as $index => $banner ) : ?>
					<button class="poetzen-sidebar-slider-dot w-1.5 h-1.5 rounded-full bg-white/40 transition-colors duration-200 cursor-pointer border-0 p-0" data-slide="<?php echo $index; ?>" aria-label="<?php printf( esc_attr__( 'Slide %d', 'sukusastra' ), $index + 1 ); ?>"></button>
				<?php endforeach; ?>
			</div>
			<!-- Slider navigation arrows -->
			<button class="poetzen-sidebar-slider-prev absolute left-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center cursor-pointer transition duration-200 z-10 border-0 p-0" aria-label="<?php esc_attr_e( 'Previous Slide', 'sukusastra' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
			</button>
			<button class="poetzen-sidebar-slider-next absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center cursor-pointer transition duration-200 z-10 border-0 p-0" aria-label="<?php esc_attr_e( 'Next Slide', 'sukusastra' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
			</button>
		</div>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				var sliderContainer = document.getElementById('poetzen-sidebar-slider-container');
				if (!sliderContainer) return;

				var track = sliderContainer.querySelector('.poetzen-sidebar-slider-track');
				var slides = sliderContainer.querySelectorAll('.poetzen-sidebar-slide');
				var dots = sliderContainer.querySelectorAll('.poetzen-sidebar-slider-dot');
				var prevBtn = sliderContainer.querySelector('.poetzen-sidebar-slider-prev');
				var nextBtn = sliderContainer.querySelector('.poetzen-sidebar-slider-next');
				
				var currentIndex = 0;
				var slideCount = slides.length;
				var slideInterval = 5000;
				var autoPlayTimer;

				function updateSlider(index) {
					if (index >= slideCount) currentIndex = 0;
					else if (index < 0) currentIndex = slideCount - 1;
					else currentIndex = index;

					track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';

					dots.forEach(function(dot, idx) {
						if (idx === currentIndex) {
							dot.classList.remove('bg-white/40');
							dot.classList.add('bg-white', 'scale-110');
						} else {
							dot.classList.remove('bg-white', 'scale-110');
							dot.classList.add('bg-white/40');
						}
					});
				}

				function startAutoPlay() {
					stopAutoPlay();
					autoPlayTimer = setInterval(function() {
						updateSlider(currentIndex + 1);
					}, slideInterval);
				}

				function stopAutoPlay() {
					if (autoPlayTimer) {
						clearInterval(autoPlayTimer);
					}
				}

				dots.forEach(function(dot, idx) {
					dot.addEventListener('click', function() {
						updateSlider(idx);
						startAutoPlay();
					});
				});

				if (prevBtn) {
					prevBtn.addEventListener('click', function() {
						updateSlider(currentIndex - 1);
						startAutoPlay();
					});
				}
				if (nextBtn) {
					nextBtn.addEventListener('click', function() {
						updateSlider(currentIndex + 1);
						startAutoPlay();
					});
				}

				sliderContainer.addEventListener('mouseenter', stopAutoPlay);
				sliderContainer.addEventListener('mouseleave', startAutoPlay);

				updateSlider(0);
				startAutoPlay();
			});
		</script>
		<?php
	}
}

/**
 * Render Article Banner (Full banner below post content, before author box).
 */
function poetzen_render_article_banner(): void {
	$banners = poetzen_get_active_banners( 'article' );
	if ( empty( $banners ) ) {
		return;
	}

	$has_multiple = count( $banners ) > 1;

	if ( ! $has_multiple ) {
		$banner     = $banners[0];
		$target_url = ! empty( $banner['url'] ) ? esc_url( $banner['url'] ) : '#';
		?>
		<div class="poetzen-article-banners flex justify-center my-8 w-full">
			<div class="poetzen-article-banner flex justify-center w-full max-w-[760px]">
				<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm transition hover:opacity-95 duration-200 w-full">
					<img src="<?php echo esc_url( $banner['image'] ); ?>" alt="<?php esc_attr_e( 'Advertisement', 'sukusastra' ); ?>" class="w-full h-auto md:h-[150px] object-cover block" style="aspect-ratio: 760/150;">
				</a>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="poetzen-article-banners flex justify-center my-8 w-full">
			<div class="poetzen-article-slider relative overflow-hidden rounded-xl border border-slate-200/50 dark:border-zinc-800/80 shadow-sm w-full max-w-[760px]" style="aspect-ratio: 760/150;" id="poetzen-article-slider-container">
				<div class="poetzen-article-slider-track flex transition-transform duration-500 ease-in-out h-full w-full">
					<?php foreach ( $banners as $banner ) : 
						$target_url = ! empty( $banner['url'] ) ? esc_url( $banner['url'] ) : '#';
						$image_url  = esc_url( $banner['image'] );
						?>
						<div class="poetzen-article-slide min-w-full w-full h-full flex-shrink-0">
							<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block w-full h-full">
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Advertisement', 'sukusastra' ); ?>" class="w-full h-full object-cover block">
							</a>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- Slider dots / indicators -->
				<div class="absolute bottom-1.5 left-1/2 -translate-x-1/2 flex gap-1 z-10">
					<?php foreach ( $banners as $index => $banner ) : ?>
						<button class="poetzen-article-slider-dot w-1.5 h-1.5 rounded-full bg-white/40 transition-colors duration-200 cursor-pointer border-0 p-0" data-slide="<?php echo $index; ?>" aria-label="<?php printf( esc_attr__( 'Slide %d', 'sukusastra' ), $index + 1 ); ?>"></button>
					<?php endforeach; ?>
				</div>
				<!-- Slider navigation arrows -->
				<button class="poetzen-article-slider-prev absolute left-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center cursor-pointer transition duration-200 z-10 border-0 p-0" aria-label="<?php esc_attr_e( 'Previous Slide', 'sukusastra' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
				</button>
				<button class="poetzen-article-slider-next absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center cursor-pointer transition duration-200 z-10 border-0 p-0" aria-label="<?php esc_attr_e( 'Next Slide', 'sukusastra' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
				</button>
			</div>
		</div>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				var sliderContainer = document.getElementById('poetzen-article-slider-container');
				if (!sliderContainer) return;

				var track = sliderContainer.querySelector('.poetzen-article-slider-track');
				var slides = sliderContainer.querySelectorAll('.poetzen-article-slide');
				var dots = sliderContainer.querySelectorAll('.poetzen-article-slider-dot');
				var prevBtn = sliderContainer.querySelector('.poetzen-article-slider-prev');
				var nextBtn = sliderContainer.querySelector('.poetzen-article-slider-next');
				
				var currentIndex = 0;
				var slideCount = slides.length;
				var slideInterval = 5000;
				var autoPlayTimer;

				function updateSlider(index) {
					if (index >= slideCount) currentIndex = 0;
					else if (index < 0) currentIndex = slideCount - 1;
					else currentIndex = index;

					track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';

					dots.forEach(function(dot, idx) {
						if (idx === currentIndex) {
							dot.classList.remove('bg-white/40');
							dot.classList.add('bg-white', 'scale-110');
						} else {
							dot.classList.remove('bg-white', 'scale-110');
							dot.classList.add('bg-white/40');
						}
					});
				}

				function startAutoPlay() {
					stopAutoPlay();
					autoPlayTimer = setInterval(function() {
						updateSlider(currentIndex + 1);
					}, slideInterval);
				}

				function stopAutoPlay() {
					if (autoPlayTimer) {
						clearInterval(autoPlayTimer);
					}
				}

				dots.forEach(function(dot, idx) {
					dot.addEventListener('click', function() {
						updateSlider(idx);
						startAutoPlay();
					});
				});

				if (prevBtn) {
					prevBtn.addEventListener('click', function() {
						updateSlider(currentIndex - 1);
						startAutoPlay();
					});
				}
				if (nextBtn) {
					nextBtn.addEventListener('click', function() {
						updateSlider(currentIndex + 1);
						startAutoPlay();
					});
				}

				sliderContainer.addEventListener('mouseenter', stopAutoPlay);
				sliderContainer.addEventListener('mouseleave', startAutoPlay);

				updateSlider(0);
				startAutoPlay();
			});
		</script>
		<?php
	}
}

/**
 * Render Popup Banner (Max 5, shows highest priority active popup, or slider if multiple are active).
 */
function poetzen_render_popup_banners(): void {
	$banners = poetzen_get_active_banners( 'popup' );
	if ( empty( $banners ) ) {
		return;
	}

	$has_multiple = count( $banners ) > 1;
	$first_banner = $banners[0];
	$banner_id    = md5( esc_url( $first_banner['image'] ) . ( ! empty( $first_banner['url'] ) ? esc_url( $first_banner['url'] ) : '#' ) );
	?>
	<div id="poetzen-popup-banner" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden opacity-0 poetzen-popup-overlay" data-popup-id="<?php echo esc_attr( $banner_id ); ?>">
		<div class="relative bg-white dark:bg-zinc-950 p-2.5 rounded-2xl shadow-2xl max-w-[90vw] max-h-[90vh] border border-slate-200/80 dark:border-zinc-800 transform scale-95 transition-transform duration-300 poetzen-popup-content">
			<button id="poetzen-popup-close" class="absolute -top-3 -right-3 w-8 h-8 rounded-full bg-white dark:bg-zinc-800 text-slate-800 dark:text-zinc-200 flex items-center justify-center shadow-lg border border-slate-150 dark:border-zinc-700 cursor-pointer transition hover:bg-red-50 dark:hover:bg-red-950/20" aria-label="<?php esc_attr_e( 'Tutup Iklan', 'sukusastra' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
					<path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
				</svg>
			</button>

			<?php if ( ! $has_multiple ) : 
				$target_url = ! empty( $first_banner['url'] ) ? esc_url( $first_banner['url'] ) : '#';
				$image_url  = esc_url( $first_banner['image'] );
				?>
				<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl bg-slate-50 dark:bg-zinc-900" style="width: 500px; height: 500px; max-width: 100%; max-height: 100%; aspect-ratio: 1/1;">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Promo Banner', 'sukusastra' ); ?>" class="w-full h-full object-cover block">
				</a>
			<?php else : ?>
				<div class="poetzen-popup-slider relative overflow-hidden rounded-xl bg-slate-50 dark:bg-zinc-900" style="width: 500px; height: 500px; max-width: 100%; max-height: 100%; aspect-ratio: 1/1;" id="poetzen-popup-slider-container">
					<div class="poetzen-popup-slider-track flex transition-transform duration-500 ease-in-out h-full w-full">
						<?php foreach ( $banners as $banner ) : 
							$target_url = ! empty( $banner['url'] ) ? esc_url( $banner['url'] ) : '#';
							$image_url  = esc_url( $banner['image'] );
							?>
							<div class="poetzen-popup-slide min-w-full w-full h-full flex-shrink-0">
								<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener" class="block w-full h-full">
									<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'Promo Banner', 'sukusastra' ); ?>" class="w-full h-full object-cover block">
								</a>
							</div>
						<?php endforeach; ?>
					</div>
					<!-- Slider dots / indicators -->
					<div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
						<?php foreach ( $banners as $index => $banner ) : ?>
							<button class="poetzen-popup-slider-dot w-2 h-2 rounded-full bg-white/40 transition-colors duration-200 cursor-pointer border-0 p-0" data-slide="<?php echo $index; ?>" aria-label="<?php printf( esc_attr__( 'Slide %d', 'sukusastra' ), $index + 1 ); ?>"></button>
						<?php endforeach; ?>
					</div>
					<!-- Slider navigation arrows -->
					<button class="poetzen-popup-slider-prev absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center cursor-pointer transition duration-200 z-10 border-0 p-0" aria-label="<?php esc_attr_e( 'Previous Slide', 'sukusastra' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
					</button>
					<button class="poetzen-popup-slider-next absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center cursor-pointer transition duration-200 z-10 border-0 p-0" aria-label="<?php esc_attr_e( 'Next Slide', 'sukusastra' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var popup = document.getElementById('poetzen-popup-banner');
			if (!popup) return;

			var popupId = popup.getAttribute('data-popup-id') || 'default';
			var sessionKey = 'poetzen_popup_shown_' + popupId;

			// Skip if already shown in this session
			if (sessionStorage.getItem(sessionKey)) {
				return;
			}

			var hasTriggered = false;
			var isSlider = <?php echo $has_multiple ? 'true' : 'false'; ?>;
			var autoPlayTimer;

			// Slider variables
			var track, slides, dots, prevBtn, nextBtn;
			var currentIndex = 0;
			var slideCount = 0;
			var slideInterval = 5000;

			if (isSlider) {
				var sliderContainer = document.getElementById('poetzen-popup-slider-container');
				if (sliderContainer) {
					track = sliderContainer.querySelector('.poetzen-popup-slider-track');
					slides = sliderContainer.querySelectorAll('.poetzen-popup-slide');
					dots = sliderContainer.querySelectorAll('.poetzen-popup-slider-dot');
					prevBtn = sliderContainer.querySelector('.poetzen-popup-slider-prev');
					nextBtn = sliderContainer.querySelector('.poetzen-popup-slider-next');
					slideCount = slides.length;
				}
			}

			function updateSlider(index) {
				if (!isSlider || !track) return;
				if (index >= slideCount) currentIndex = 0;
				else if (index < 0) currentIndex = slideCount - 1;
				else currentIndex = index;

				track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';

				dots.forEach(function(dot, idx) {
					if (idx === currentIndex) {
						dot.classList.remove('bg-white/40');
						dot.classList.add('bg-white', 'scale-110');
					} else {
						dot.classList.remove('bg-white', 'scale-110');
						dot.classList.add('bg-white/40');
					}
				});
			}

			function startAutoPlay() {
				if (!isSlider) return;
				stopAutoPlay();
				autoPlayTimer = setInterval(function() {
					updateSlider(currentIndex + 1);
				}, slideInterval);
			}

			function stopAutoPlay() {
				if (autoPlayTimer) {
					clearInterval(autoPlayTimer);
				}
			}

			if (isSlider && dots) {
				dots.forEach(function(dot, idx) {
					dot.addEventListener('click', function() {
						updateSlider(idx);
						startAutoPlay();
					});
				});

				if (prevBtn) {
					prevBtn.addEventListener('click', function() {
						updateSlider(currentIndex - 1);
						startAutoPlay();
					});
				}
				if (nextBtn) {
					nextBtn.addEventListener('click', function() {
						updateSlider(currentIndex + 1);
						startAutoPlay();
					});
				}

				var sliderContainer = document.getElementById('poetzen-popup-slider-container');
				if (sliderContainer) {
					sliderContainer.addEventListener('mouseenter', stopAutoPlay);
					sliderContainer.addEventListener('mouseleave', startAutoPlay);
				}
			}

			function triggerPopup() {
				if (hasTriggered) return;
				hasTriggered = true;

				window.removeEventListener('scroll', handleScroll);

				// Mark as shown in this session
				try {
					sessionStorage.setItem(sessionKey, '1');
				} catch (e) {
					// Fallback for private/incognito browsing
				}

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

					if (isSlider) {
						updateSlider(0);
						startAutoPlay();
					}
				}, 50);
			}

			function handleScroll() {
				triggerPopup();
			}

			window.addEventListener('scroll', handleScroll);

			function closePopup() {
				stopAutoPlay();
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
