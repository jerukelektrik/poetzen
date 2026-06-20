<?php
/**
 * Native Theme Options.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Build trigger: Monetization merge v1.0.2

// Enqueue WordPress Media Library on Suku Sastra Theme Options page
add_action( 'admin_enqueue_scripts', 'sukusastra_admin_enqueue_scripts' );
function sukusastra_admin_enqueue_scripts( string $hook_suffix ): void {
	if ( 'appearance_page_sukusastra_options' === $hook_suffix ) {
		wp_enqueue_media();
	}
}

add_action( 'admin_menu', 'sukusastra_add_options_menu' );
function sukusastra_add_options_menu(): void {
	add_theme_page(
		__( 'Theme Options Poetzen', 'sukusastra' ),
		__( 'Theme Options', 'sukusastra' ),
		'edit_theme_options',
		'sukusastra_options',
		'sukusastra_render_options_page'
	);
}

function sukusastra_render_options_page(): void {
	?>
	<div class="wrap ss-options-wrap">
		<h1><?php esc_html_e( 'Theme Options Poetzen', 'sukusastra' ); ?></h1>
		
		<?php if ( isset( $_GET['settings-updated'] ) && 'true' === $_GET['settings-updated'] ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><strong><?php esc_html_e( 'Pengaturan berhasil disimpan.', 'sukusastra' ); ?></strong></p>
			</div>
		<?php endif; ?>
		
		<form method="post" action="options.php" class="ss-options-form">
			<?php settings_fields( 'sukusastra_options_group' ); ?>
			
			<div class="ss-options-container">
				<!-- Sidebar Tabs -->
				<div class="ss-options-tabs">
					<button type="button" class="ss-tab-btn active" data-tab="branding">
						<svg class="ss-tab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.01-1.25a9 9 0 002.25-2.25m5.01 1.25l.075.03A12.061 12.061 0 0012 15.75H12m1.282-3.836A3 3 0 0012 11.25h-.002A3 3 0 0010.5 12.75V12.75a3 3 0 003.032 3.032l.075-.03c.55-.221 1.116-.493 1.68-.813zm0 0a15.998 15.998 0 003.388-1.62m-5.01-1.25a9 9 0 002.25-2.25m9 9a9 9 0 11-18 0 9 9 0 0118 0z" />
						</svg>
						<span class="ss-tab-label"><?php esc_html_e( 'Umum & Branding', 'sukusastra' ); ?></span>
					</button>
					<button type="button" class="ss-tab-btn" data-tab="homepage">
						<svg class="ss-tab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
						</svg>
						<span class="ss-tab-label"><?php esc_html_e( 'Tampilan Beranda', 'sukusastra' ); ?></span>
					</button>
					<button type="button" class="ss-tab-btn" data-tab="socials">
						<svg class="ss-tab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622a4.5 4.5 0 01-1.242-7.244l4.5-4.5a4.5 4.5 0 016.364 6.364l-1.757 1.757" />
						</svg>
						<span class="ss-tab-label"><?php esc_html_e( 'Media Sosial & Kontak', 'sukusastra' ); ?></span>
					</button>
					<button type="button" class="ss-tab-btn" data-tab="scripts">
						<svg class="ss-tab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
						</svg>
						<span class="ss-tab-label"><?php esc_html_e( 'Skrip Kustom', 'sukusastra' ); ?></span>
					</button>
					<button type="button" class="ss-tab-btn" data-tab="integration">
						<svg class="ss-tab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
						</svg>
						<span class="ss-tab-label"><?php esc_html_e( 'Integrasi', 'sukusastra' ); ?></span>
					</button>

					<button type="button" class="ss-tab-btn" data-tab="banners">
						<svg class="ss-tab-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<rect x="3" y="3" width="18" height="18" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M21 9H3M21 15H3M12 3v18" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<span class="ss-tab-label"><?php esc_html_e( 'Manajemen Banner', 'sukusastra' ); ?></span>
					</button>
				</div>
				
				<!-- Content Viewport -->
				<div class="ss-options-content">
					<?php
					$options = get_option( 'sukusastra_options', array() );
					?>
					
					<!-- Tab 1: Umum & Branding -->
					<div class="ss-tab-content active" id="tab-branding">
						<div class="ss-card">
							<h2><?php esc_html_e( 'Umum & Branding', 'sukusastra' ); ?></h2>
							
							<!-- Logo Light Mode -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Logo Light Mode', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Digunakan pada navigasi atas dan footer dengan latar belakang terang.', 'sukusastra' ); ?></p>
								<div class="ss-upload-wrapper">
									<input type="text" id="logo_light_input" class="regular-text ss-input-text" name="sukusastra_options[logo_light]" value="<?php echo esc_attr( isset( $options['logo_light'] ) ? $options['logo_light'] : '' ); ?>">
									<button type="button" class="button ss-upload-btn" data-input="logo_light_input"><?php esc_html_e( 'Pilih Gambar', 'sukusastra' ); ?></button>
								</div>
								<div class="ss-logo-preview" id="logo_light_preview">
									<?php if ( ! empty( $options['logo_light'] ) ) : ?>
										<img src="<?php echo esc_url( $options['logo_light'] ); ?>" alt="Logo Light Preview">
									<?php else : ?>
										<span class="text-slate-400 text-xs italic"><?php esc_html_e( 'Belum ada logo dipilih', 'sukusastra' ); ?></span>
									<?php endif; ?>
								</div>
							</div>
							
							<!-- Logo Dark Mode -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Logo Dark Mode', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Digunakan pada navigasi atas dan footer dengan latar belakang gelap.', 'sukusastra' ); ?></p>
								<div class="ss-upload-wrapper">
									<input type="text" id="logo_dark_input" class="regular-text ss-input-text" name="sukusastra_options[logo_dark]" value="<?php echo esc_attr( isset( $options['logo_dark'] ) ? $options['logo_dark'] : '' ); ?>">
									<button type="button" class="button ss-upload-btn" data-input="logo_dark_input"><?php esc_html_e( 'Pilih Gambar', 'sukusastra' ); ?></button>
								</div>
								<div class="ss-logo-preview bg-slate-900" id="logo_dark_preview">
									<?php if ( ! empty( $options['logo_dark'] ) ) : ?>
										<img src="<?php echo esc_url( $options['logo_dark'] ); ?>" alt="Logo Dark Preview">
									<?php else : ?>
										<span class="text-slate-500 text-xs italic"><?php esc_html_e( 'Belum ada logo dipilih', 'sukusastra' ); ?></span>
									<?php endif; ?>
								</div>
							</div>

							<!-- Footer Biography Text -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Deskripsi Footer', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Teks deskripsi/biografi singkat Suku Sastra di bagian footer website.', 'sukusastra' ); ?></p>
								<textarea class="large-text ss-textarea" rows="4" name="sukusastra_options[footer_bio]"><?php echo esc_textarea( isset( $options['footer_bio'] ) ? $options['footer_bio'] : '' ); ?></textarea>
							</div>

							<!-- Copyright Footer -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Copyright Footer', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Teks hak cipta di baris paling bawah website.', 'sukusastra' ); ?></p>
								<input type="text" class="regular-text ss-input-text" name="sukusastra_options[copyright]" value="<?php echo esc_attr( isset( $options['copyright'] ) ? $options['copyright'] : '' ); ?>">
							</div>

							<!-- Color Scheme -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Skema Warna Tema', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Pilih skema warna utama (brand color) untuk seluruh elemen website.', 'sukusastra' ); ?></p>
								<select name="sukusastra_options[color_scheme]" class="ss-select">
									<option value="crimson" <?php selected( isset( $options['color_scheme'] ) ? $options['color_scheme'] : 'crimson', 'crimson' ); ?>><?php esc_html_e( 'Klasik Suku Sastra (Crimson)', 'sukusastra' ); ?></option>
									<option value="emerald" <?php selected( isset( $options['color_scheme'] ) ? $options['color_scheme'] : 'emerald', 'emerald' ); ?>><?php esc_html_e( 'Hijau Daun (Emerald)', 'sukusastra' ); ?></option>
									<option value="sapphire" <?php selected( isset( $options['color_scheme'] ) ? $options['color_scheme'] : 'sapphire', 'sapphire' ); ?>><?php esc_html_e( 'Biru Samudra (Sapphire)', 'sukusastra' ); ?></option>
									<option value="amber" <?php selected( isset( $options['color_scheme'] ) ? $options['color_scheme'] : 'amber', 'amber' ); ?>><?php esc_html_e( 'Emas Amber (Amber)', 'sukusastra' ); ?></option>
									<option value="amethyst" <?php selected( isset( $options['color_scheme'] ) ? $options['color_scheme'] : 'amethyst', 'amethyst' ); ?>><?php esc_html_e( 'Ungu Amethyst (Amethyst)', 'sukusastra' ); ?></option>
								</select>
							</div>

							<!-- Font Family -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Kombinasi Huruf (Font Family)', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Pilih kombinasi huruf untuk judul, artikel, dan teks antarmuka.', 'sukusastra' ); ?></p>
								<select name="sukusastra_options[font_family]" class="ss-select">
									<option value="default" <?php selected( isset( $options['font_family'] ) ? $options['font_family'] : 'default', 'default' ); ?>><?php esc_html_e( 'EB Garamond & Playfair (Sastra Tradisional)', 'sukusastra' ); ?></option>
									<option value="modern" <?php selected( isset( $options['font_family'] ) ? $options['font_family'] : 'modern', 'modern' ); ?>><?php esc_html_e( 'Inter & Outfit (Sastra Modern)', 'sukusastra' ); ?></option>
									<option value="elegant" <?php selected( isset( $options['font_family'] ) ? $options['font_family'] : 'elegant', 'elegant' ); ?>><?php esc_html_e( 'Lora & Merriweather (Sastra Elegan)', 'sukusastra' ); ?></option>
									<option value="system" <?php selected( isset( $options['font_family'] ) ? $options['font_family'] : 'system', 'system' ); ?>><?php esc_html_e( 'Sistem Serif & Sans (Sastra Minimalis)', 'sukusastra' ); ?></option>
								</select>
							</div>

							<!-- Font Size -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Skala Ukuran Huruf (Font Size)', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Pilih skala ukuran huruf dasar untuk kenyamanan membaca.', 'sukusastra' ); ?></p>
								<select name="sukusastra_options[font_size]" class="ss-select">
									<option value="small" <?php selected( isset( $options['font_size'] ) ? $options['font_size'] : 'medium', 'small' ); ?>><?php esc_html_e( 'Kecil', 'sukusastra' ); ?></option>
									<option value="medium" <?php selected( isset( $options['font_size'] ) ? $options['font_size'] : 'medium', 'medium' ); ?>><?php esc_html_e( 'Sedang (Default)', 'sukusastra' ); ?></option>
									<option value="large" <?php selected( isset( $options['font_size'] ) ? $options['font_size'] : 'medium', 'large' ); ?>><?php esc_html_e( 'Besar', 'sukusastra' ); ?></option>
									<option value="xlarge" <?php selected( isset( $options['font_size'] ) ? $options['font_size'] : 'medium', 'xlarge' ); ?>><?php esc_html_e( 'Sangat Besar', 'sukusastra' ); ?></option>
								</select>
							</div>
						</div>
					</div>
					
					<!-- Tab 2: Tampilan Beranda -->
					<div class="ss-tab-content" id="tab-homepage">
						<div class="ss-card">
							<h2><?php esc_html_e( 'Tampilan Beranda', 'sukusastra' ); ?></h2>
							
							<!-- Toggle News Ticker -->
							<div class="ss-field-group flex-row">
								<div class="ss-field-text">
									<label class="ss-label"><?php esc_html_e( 'Aktifkan News Ticker', 'sukusastra' ); ?></label>
									<p class="ss-description"><?php esc_html_e( 'Menampilkan baris running text berita utama (News Update) di halaman beranda.', 'sukusastra' ); ?></p>
								</div>
								<div class="ss-toggle-wrapper">
									<label class="ss-switch">
										<input type="checkbox" name="sukusastra_options[toggle_news_ticker]" value="1" <?php checked( isset( $options['toggle_news_ticker'] ) ? $options['toggle_news_ticker'] : '1', '1' ); ?>>
										<span class="ss-slider"></span>
									</label>
								</div>
							</div>
							
							<!-- Toggle Penulis Stories -->
							<div class="ss-field-group flex-row">
								<div class="ss-field-text">
									<label class="ss-label"><?php esc_html_e( 'Aktifkan Penulis Stories', 'sukusastra' ); ?></label>
									<p class="ss-description"><?php esc_html_e( 'Menampilkan baris bulatan penulis (Stories) ala Instagram di halaman beranda.', 'sukusastra' ); ?></p>
								</div>
								<div class="ss-toggle-wrapper">
									<label class="ss-switch">
										<input type="checkbox" name="sukusastra_options[toggle_penulis_stories]" value="1" <?php checked( isset( $options['toggle_penulis_stories'] ) ? $options['toggle_penulis_stories'] : '1', '1' ); ?>>
										<span class="ss-slider"></span>
									</label>
								</div>
							</div>

							<!-- Color Header -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Warna Latar Header (Light Mode)', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Pilih warna latar belakang navigasi atas dalam mode terang.', 'sukusastra' ); ?></p>
								<input type="color" class="ss-input-color" name="sukusastra_options[header_bg_light]" value="<?php echo esc_attr( isset( $options['header_bg_light'] ) ? $options['header_bg_light'] : '#ffffff' ); ?>">
							</div>
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Warna Latar Header (Dark Mode)', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Pilih warna latar belakang navigasi atas dalam mode gelap.', 'sukusastra' ); ?></p>
								<input type="color" class="ss-input-color" name="sukusastra_options[header_bg_dark]" value="<?php echo esc_attr( isset( $options['header_bg_dark'] ) ? $options['header_bg_dark'] : '#262B4E' ); ?>">
							</div>

							<!-- Color Footer -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Warna Latar Footer', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Pilih warna latar belakang bagian kaki (footer) website.', 'sukusastra' ); ?></p>
								<input type="color" class="ss-input-color" name="sukusastra_options[footer_bg]" value="<?php echo esc_attr( isset( $options['footer_bg'] ) ? $options['footer_bg'] : '#090d16' ); ?>">
							</div>
						</div>
					</div>
					
					<!-- Tab 3: Media Sosial & Kontak -->
					<div class="ss-tab-content" id="tab-socials">
						<div class="ss-card">
							<h2><?php esc_html_e( 'Media Sosial & Kontak', 'sukusastra' ); ?></h2>
							
							<?php
							$social_fields = array(
								'instagram' => array( 'label' => __( 'Instagram URL', 'sukusastra' ), 'desc' => 'https://instagram.com/username' ),
								'twitter'   => array( 'label' => __( 'Twitter / X URL', 'sukusastra' ), 'desc' => 'https://twitter.com/username' ),
								'facebook'  => array( 'label' => __( 'Facebook URL', 'sukusastra' ), 'desc' => 'https://facebook.com/username' ),
								'youtube'   => array( 'label' => __( 'YouTube Channel URL', 'sukusastra' ), 'desc' => 'https://youtube.com/c/channelname' ),
								'tiktok'    => array( 'label' => __( 'TikTok URL', 'sukusastra' ), 'desc' => 'https://tiktok.com/@username' ),
								'linkedin'  => array( 'label' => __( 'LinkedIn URL', 'sukusastra' ), 'desc' => 'https://linkedin.com/company/username' ),
								'threads'   => array( 'label' => __( 'Threads URL', 'sukusastra' ), 'desc' => 'https://threads.net/@username' ),
								'whatsapp'  => array( 'label' => __( 'WhatsApp Contact Number / Link', 'sukusastra' ), 'desc' => '628123456789' ),
							);
							
							foreach ( $social_fields as $key => $field_info ) :
								?>
								<div class="ss-field-group">
									<label class="ss-label"><?php echo esc_html( $field_info['label'] ); ?></label>
									<input type="text" class="regular-text ss-input-text" name="sukusastra_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( isset( $options[ $key ] ) ? $options[ $key ] : '' ); ?>" placeholder="<?php echo esc_attr( $field_info['desc'] ); ?>">
								</div>
							<?php endforeach; ?>
						</div>
					</div>
					
					<!-- Tab 4: Skrip Kustom -->
					<div class="ss-tab-content" id="tab-scripts">
						<div class="ss-card">
							<h2><?php esc_html_e( 'Skrip Kustom', 'sukusastra' ); ?></h2>
							
							<!-- Header Scripts -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Header Scripts', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Dimasukkan ke dalam bagian <head> website. Berguna untuk Google Analytics, Meta Pixel, atau verifikasi kepemilikan domain.', 'sukusastra' ); ?></p>
								<textarea class="large-text ss-textarea code" rows="6" name="sukusastra_options[header_scripts]"><?php echo esc_textarea( isset( $options['header_scripts'] ) ? $options['header_scripts'] : '' ); ?></textarea>
							</div>
							
							<!-- Footer Scripts -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Footer Scripts', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Dimasukkan tepat sebelum tag penutup </body>. Berguna untuk kustom JS, Live Chat, dll.', 'sukusastra' ); ?></p>
								<textarea class="large-text ss-textarea code" rows="6" name="sukusastra_options[footer_scripts]"><?php echo esc_textarea( isset( $options['footer_scripts'] ) ? $options['footer_scripts'] : '' ); ?></textarea>
							</div>
						</div>
					</div>

					<!-- Tab 5: Integrasi -->
					<div class="ss-tab-content" id="tab-integration">
						<div class="ss-card">
							<h2><?php esc_html_e( 'Integrasi Layanan', 'sukusastra' ); ?></h2>
							
							<!-- Activate schema markup -->
							<div class="ss-field-group flex-row">
								<div class="ss-field-text">
									<label class="ss-label"><?php esc_html_e( 'Aktifkan Schema Markup (JSON-LD)', 'sukusastra' ); ?></label>
									<p class="ss-description"><?php esc_html_e( 'Secara otomatis menghasilkan schema data terstruktur untuk hasil pencarian Google yang lebih kaya.', 'sukusastra' ); ?></p>
								</div>
								<div class="ss-toggle-wrapper">
									<label class="ss-switch">
										<input type="checkbox" name="sukusastra_options[toggle_schema]" value="1" <?php checked( isset( $options['toggle_schema'] ) ? $options['toggle_schema'] : '1', '1' ); ?>>
										<span class="ss-slider"></span>
									</label>
								</div>
							</div>
							
							<!-- Google Search Console -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Google Search Console ID', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Masukkan kode verifikasi HTML (content value) Google Search Console Anda.', 'sukusastra' ); ?></p>
								<input type="text" class="regular-text ss-input-text" name="sukusastra_options[gsc_id]" value="<?php echo esc_attr( isset( $options['gsc_id'] ) ? $options['gsc_id'] : '' ); ?>" placeholder="e.g. zXyWvUtSrQpOnMlKjIhGfEdCbA">
							</div>

							<!-- Google Analytics -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Google Analytics Measurement ID', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Masukkan Google Analytics 4 Measurement ID Anda (G-XXXXXXXXXX).', 'sukusastra' ); ?></p>
								<input type="text" class="regular-text ss-input-text" name="sukusastra_options[ga_id]" value="<?php echo esc_attr( isset( $options['ga_id'] ) ? $options['ga_id'] : '' ); ?>" placeholder="e.g. G-XXXXXXXXXX">
							</div>

							<!-- Google Tag Manager -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Google Tag Manager Container ID', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Masukkan Google Tag Manager Container ID Anda (GTM-XXXXXXX).', 'sukusastra' ); ?></p>
								<input type="text" class="regular-text ss-input-text" name="sukusastra_options[gtm_id]" value="<?php echo esc_attr( isset( $options['gtm_id'] ) ? $options['gtm_id'] : '' ); ?>" placeholder="e.g. GTM-XXXXXXX">
							</div>

							<!-- Meta Pixel -->
							<div class="ss-field-group">
								<label class="ss-label"><?php esc_html_e( 'Meta Pixel ID', 'sukusastra' ); ?></label>
								<p class="ss-description"><?php esc_html_e( 'Masukkan ID Meta Pixel (Facebook Pixel) Anda.', 'sukusastra' ); ?></p>
								<input type="text" class="regular-text ss-input-text" name="sukusastra_options[meta_pixel_id]" value="<?php echo esc_attr( isset( $options['meta_pixel_id'] ) ? $options['meta_pixel_id'] : '' ); ?>" placeholder="e.g. 123456789012345">
							</div>
						</div>
					</div>



					<!-- Tab 7: Manajemen Banner -->
					<div class="ss-tab-content" id="tab-banners">
						<div class="ss-card">
							<h2><?php esc_html_e( 'Manajemen Banner', 'sukusastra' ); ?></h2>
							<p class="ss-description"><?php esc_html_e( 'Kelola banner katalog terbitan, popup, sidebar, dan artikel di bawah ini. Anda dapat mengaktifkan hingga 5 banner per lokasi.', 'sukusastra' ); ?></p>

							<!-- Sub-tabs Navigation -->
							<div class="poetzen-subtabs" style="display: flex; gap: 10px; border-bottom: 2px solid #f3f4f6; margin-top: 20px; margin-bottom: 25px; padding-bottom: 2px;">
								<button type="button" class="poetzen-subtab-btn active" data-subtab="catalog" style="background: transparent; border: 0; padding: 12px 20px; font-weight: 700; font-size: 13px; cursor: pointer; border-bottom: 3px solid #b42318; color: #b42318; margin-bottom: -2px; transition: all 0.2s ease; outline: none;"><?php esc_html_e( 'Katalog Banner', 'sukusastra' ); ?></button>
								<button type="button" class="poetzen-subtab-btn" data-subtab="popup" style="background: transparent; border: 0; padding: 12px 20px; font-weight: 700; font-size: 13px; cursor: pointer; border-bottom: 3px solid transparent; color: #6b7280; margin-bottom: -2px; transition: all 0.2s ease; outline: none;"><?php esc_html_e( 'Popup Banner', 'sukusastra' ); ?></button>
								<button type="button" class="poetzen-subtab-btn" data-subtab="sidebar" style="background: transparent; border: 0; padding: 12px 20px; font-weight: 700; font-size: 13px; cursor: pointer; border-bottom: 3px solid transparent; color: #6b7280; margin-bottom: -2px; transition: all 0.2s ease; outline: none;"><?php esc_html_e( 'Sidebar Banner', 'sukusastra' ); ?></button>
								<button type="button" class="poetzen-subtab-btn" data-subtab="article" style="background: transparent; border: 0; padding: 12px 20px; font-weight: 700; font-size: 13px; cursor: pointer; border-bottom: 3px solid transparent; color: #6b7280; margin-bottom: -2px; transition: all 0.2s ease; outline: none;"><?php esc_html_e( 'Article Banner', 'sukusastra' ); ?></button>
							</div>

							<?php
							$placements = array(
								'catalog' => array(
									'label' => __( 'Banner Katalog Terbitan (1200x150)', 'sukusastra' ),
									'desc'  => __( 'Ukuran rekomendasi: 1200x150 piksel. Tampil di halaman beranda di atas atau di area Katalog Terbitan.', 'sukusastra' ),
								),
								'popup'   => array(
									'label' => __( 'Popup Banners (Square 250x250)', 'sukusastra' ),
									'desc'  => __( 'Ukuran: 250x250 piksel. Tampil di tengah layar sebagai popup overlay.', 'sukusastra' ),
								),
								'sidebar' => array(
									'label' => __( 'Sidebar Banners (3:1 Rectangle 300x100)', 'sukusastra' ),
									'desc'  => __( 'Ukuran: 300x100 piksel. Tampil di sidebar artikel.', 'sukusastra' ),
								),
								'article' => array(
									'label' => __( 'Article Banners (Full Banner 468x60)', 'sukusastra' ),
									'desc'  => __( 'Ukuran: 468x60 piksel. Tampil di bawah isi artikel sebelum kotak penulis.', 'sukusastra' ),
								),
							);

							foreach ( $placements as $key => $info ) :
								?>
								<div class="poetzen-subtab-content <?php echo 'catalog' === $key ? '' : 'hidden'; ?>" id="subtab-<?php echo esc_attr( $key ); ?>">
									<h3 style="border-bottom: 1px solid #f3f4f6; padding-bottom: 8px; font-weight: 800; font-size: 15px; color: #111827; margin-top: 0;"><?php echo esc_html( $info['label'] ); ?></h3>
									<p class="ss-description" style="margin-bottom: 20px;"><?php echo esc_html( $info['desc'] ); ?></p>

									<div class="poetzen-banners-list" style="display: grid; gap: 15px; margin-bottom: 15px;">
										<?php
										for ( $i = 0; $i < 5; $i++ ) :
											$banner       = isset( $options['banners'][ $key ][ $i ] ) ? $options['banners'][ $key ][ $i ] : array();
											$status       = isset( $banner['status'] ) ? $banner['status'] : '0';
											$image        = isset( $banner['image'] ) ? $banner['image'] : '';
											$url          = isset( $banner['url'] ) ? $banner['url'] : '';
											$target       = isset( $banner['target'] ) ? $banner['target'] : 'global';
											$target_value = isset( $banner['target_value'] ) ? $banner['target_value'] : '';
											$order        = isset( $banner['order'] ) ? $banner['order'] : '0';
											$start_date   = isset( $banner['start_date'] ) ? $banner['start_date'] : '';
											$end_date     = isset( $banner['end_date'] ) ? $banner['end_date'] : '';
											?>
											<div class="poetzen-banner-card" style="border: 1px solid #e5e7eb; border-radius: 12px; background: #f9fafb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
												<div class="poetzen-banner-header" style="padding: 14px 20px; background: #f3f4f6; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: bold; border-bottom: 1px solid #e5e7eb; user-select: none;">
													<span style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #374151;">
														<span class="poetzen-accordion-icon" style="color: #9ca3af; font-family: monospace; font-size: 14px;">[+]</span>
														<?php printf( esc_html__( 'Slot Banner #%d', 'sukusastra' ), $i + 1 ); ?>
														<?php if ( '1' === $status ) : ?>
															<span style="background: #ecfdf5; color: #047857; font-size: 10px; padding: 2px 8px; border-radius: 9999px; font-weight: 700; border: 1px solid #a7f3d0;"><?php esc_html_e( 'Aktif', 'sukusastra' ); ?></span>
														<?php else : ?>
															<span style="background: #fef2f2; color: #b91c1c; font-size: 10px; padding: 2px 8px; border-radius: 9999px; font-weight: 700; border: 1px solid #fecaca;"><?php esc_html_e( 'Nonaktif', 'sukusastra' ); ?></span>
														<?php endif; ?>
													</span>
													<span style="font-size: 11px; color: #6b7280; font-weight: normal;">
														<?php
														if ( $url ) {
															echo esc_html( wp_parse_url( $url, PHP_URL_HOST ) );
														}
														?>
													</span>
												</div>
												<div class="poetzen-banner-body hidden" style="padding: 20px; display: flex; flex-direction: column; gap: 16px; border-top: 1px solid #e5e7eb; background: #ffffff;">
													<!-- Status Toggle -->
													<div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; border-bottom: 1px solid #f3f4f6;">
														<label class="ss-label" style="margin: 0; font-size: 13px; font-weight: 700; color: #374151;"><?php esc_html_e( 'Aktifkan Slot Banner Ini', 'sukusastra' ); ?></label>
														<div class="ss-toggle-wrapper">
															<label class="ss-switch">
																<input type="checkbox" name="sukusastra_options[banners][<?php echo esc_attr( $key ); ?>][<?php echo $i; ?>][status]" value="1" <?php checked( $status, '1' ); ?>>
																<span class="ss-slider"></span>
															</label>
														</div>
													</div>

													<!-- Image Selection -->
													<div class="ss-field-group" style="margin-bottom: 0;">
														<label class="ss-label"><?php esc_html_e( 'Gambar Banner', 'sukusastra' ); ?></label>
														<div class="ss-upload-wrapper" style="display: flex; gap: 10px; margin-top: 5px;">
															<input type="text" id="banner_<?php echo esc_attr( $key ); ?>_<?php echo $i; ?>_image_input" class="regular-text ss-input-text" style="flex: 1;" name="sukusastra_options[banners][<?php echo esc_attr( $key ); ?>][<?php echo $i; ?>][image]" value="<?php echo esc_attr( $image ); ?>">
															<button type="button" class="button ss-upload-btn" data-input="banner_<?php echo esc_attr( $key ); ?>_<?php echo $i; ?>_image_input" style="height: 40px; border-radius: 8px;"><?php esc_html_e( 'Pilih Gambar', 'sukusastra' ); ?></button>
														</div>
														<div class="ss-image-preview mt-2" id="banner_<?php echo esc_attr( $key ); ?>_<?php echo $i; ?>_image_preview" style="max-width: 100%; max-height: 120px; overflow: hidden; border: 1px dashed #d1d5db; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: #f9fafb; padding: 8px;">
															<?php if ( $image ) : ?>
																<img src="<?php echo esc_url( $image ); ?>" style="max-height: 100px; width: auto; object-contain; border-radius: 4px;">
															<?php else : ?>
																<span style="padding: 20px; color: #9ca3af; font-size: 12px; italic;"><?php esc_html_e( 'Belum ada gambar terpilih', 'sukusastra' ); ?></span>
															<?php endif; ?>
														</div>
													</div>

													<!-- Target Link -->
													<div class="ss-field-group" style="margin-bottom: 0;">
														<label class="ss-label"><?php esc_html_e( 'Tautan URL (Target Link)', 'sukusastra' ); ?></label>
														<input type="url" class="large-text ss-input-text" name="sukusastra_options[banners][<?php echo esc_attr( $key ); ?>][<?php echo $i; ?>][url]" value="<?php echo esc_url( $url ); ?>" placeholder="e.g. https://sukusastra.com/promo">
													</div>

													<!-- Grid of Target settings -->
													<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; padding-top: 12px; border-top: 1px solid #f3f4f6;">
														<!-- Display Target -->
														<div class="ss-field-group" style="margin-bottom: 0;">
															<label class="ss-label"><?php esc_html_e( 'Target Lokasi Penayangan', 'sukusastra' ); ?></label>
															<select name="sukusastra_options[banners][<?php echo esc_attr( $key ); ?>][<?php echo $i; ?>][target]" class="ss-select" style="width: 100%; margin-top: 5px;">
																<option value="global" <?php selected( $target, 'global' ); ?>><?php esc_html_e( 'Seluruh Website (Global)', 'sukusastra' ); ?></option>
																<option value="all_cat" <?php selected( $target, 'all_cat' ); ?>><?php esc_html_e( 'Semua Halaman Kategori', 'sukusastra' ); ?></option>
																<option value="cat_specific" <?php selected( $target, 'cat_specific' ); ?>><?php esc_html_e( 'Kategori Tertentu (atau Single Post dengan Kategori Ini)', 'sukusastra' ); ?></option>
																<option value="all_single" <?php selected( $target, 'all_single' ); ?>><?php esc_html_e( 'Semua Single Post', 'sukusastra' ); ?></option>
																<option value="single_specific" <?php selected( $target, 'single_specific' ); ?>><?php esc_html_e( 'Single Post Tertentu', 'sukusastra' ); ?></option>
															</select>
														</div>

														<!-- Target Value -->
														<div class="ss-field-group" style="margin-bottom: 0;">
															<label class="ss-label"><?php esc_html_e( 'Spesifikasi Target (ID / Slug)', 'sukusastra' ); ?></label>
															<input type="text" class="ss-input-text" style="width: 100%; margin-top: 5px;" name="sukusastra_options[banners][<?php echo esc_attr( $key ); ?>][<?php echo $i; ?>][target_value]" value="<?php echo esc_attr( $target_value ); ?>" placeholder="e.g. cerpen, puisi, 124">
															<span class="description" style="font-size: 11px; color: #9ca3af; margin-top: 4px;"><?php esc_html_e( 'Isi slug kategori (pisahkan koma) atau ID/slug postingan spesifik.', 'sukusastra' ); ?></span>
														</div>
													</div>

													<!-- Grid of Schedule & Order -->
													<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 16px; padding-top: 12px; border-top: 1px solid #f3f4f6;">
														<!-- Start Date -->
														<div class="ss-field-group" style="margin-bottom: 0;">
															<label class="ss-label"><?php esc_html_e( 'Tanggal Mulai', 'sukusastra' ); ?></label>
															<input type="date" class="ss-input-text" style="width: 100%; margin-top: 5px; height: 40px;" name="sukusastra_options[banners][<?php echo esc_attr( $key ); ?>][<?php echo $i; ?>][start_date]" value="<?php echo esc_attr( $start_date ); ?>">
														</div>

														<!-- End Date -->
														<div class="ss-field-group" style="margin-bottom: 0;">
															<label class="ss-label"><?php esc_html_e( 'Tanggal Berakhir', 'sukusastra' ); ?></label>
															<input type="date" class="ss-input-text" style="width: 100%; margin-top: 5px; height: 40px;" name="sukusastra_options[banners][<?php echo esc_attr( $key ); ?>][<?php echo $i; ?>][end_date]" value="<?php echo esc_attr( $end_date ); ?>">
														</div>

														<!-- Order -->
														<div class="ss-field-group" style="margin-bottom: 0;">
															<label class="ss-label"><?php esc_html_e( 'Urutan (Priority Order)', 'sukusastra' ); ?></label>
															<input type="number" min="0" class="ss-input-text" style="width: 100%; margin-top: 5px; height: 40px;" name="sukusastra_options[banners][<?php echo esc_attr( $key ); ?>][<?php echo $i; ?>][order]" value="<?php echo esc_attr( $order ); ?>">
														</div>
													</div>
												</div>
											</div>
										<?php endfor; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- Form Submit Button Section -->
					<div class="ss-submit-bar">
						<?php submit_button( __( 'Simpan Perubahan', 'sukusastra' ), 'primary large ss-submit-btn', 'submit', false ); ?>
					</div>
				</div>
			</div>
		</form>
	</div>

	<!-- Custom Premium Options Panel Styles -->
	<style>
		.ss-options-wrap {
			margin: 20px 20px 0 0;
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
		}
		.ss-options-wrap h1 {
			font-size: 28px;
			font-weight: 800;
			color: #111827;
			margin-bottom: 24px;
		}
		.ss-options-form {
			max-width: 1200px;
		}
		.ss-options-container {
			display: grid;
			grid-template-columns: 240px 1fr;
			gap: 24px;
			align-items: start;
			margin-top: 20px;
		}
		.ss-options-tabs {
			background: #1e2445;
			border-radius: 16px;
			padding: 16px 8px;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
			display: flex;
			flex-direction: column;
			gap: 6px;
		}
		.ss-tab-btn {
			background: transparent;
			border: 0;
			border-radius: 8px;
			padding: 12px 16px;
			color: #9ca3af;
			font-size: 14px;
			font-weight: 700;
			text-align: left;
			cursor: pointer;
			transition: all 0.2s ease-in-out;
			display: flex;
			align-items: center;
			gap: 10px;
			width: 100%;
		}
		.ss-tab-icon {
			width: 18px;
			height: 18px;
			stroke-width: 2.2;
		}
		.ss-tab-btn:hover {
			color: #ffffff;
			background: rgba(255, 255, 255, 0.05);
		}
		.ss-tab-btn.active {
			color: #ffffff;
			background: #b42318; /* Suku Sastra Crimson */
		}
		.ss-options-content {
			display: flex;
			flex-direction: column;
			gap: 24px;
		}
		.ss-tab-content {
			display: none;
		}
		.ss-tab-content.active {
			display: block;
		}
		.ss-card {
			background: #ffffff;
			border-radius: 16px;
			padding: 30px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.03);
			border: 1px solid rgba(0, 0, 0, 0.05);
		}
		.ss-card h2 {
			font-size: 20px;
			font-weight: 800;
			color: #111827;
			margin-top: 0;
			margin-bottom: 24px;
			border-bottom: 2px solid #f3f4f6;
			padding-bottom: 12px;
		}
		.ss-field-group {
			margin-bottom: 24px;
			display: flex;
			flex-direction: column;
			gap: 6px;
		}
		.ss-field-group.flex-row {
			flex-direction: row;
			justify-content: space-between;
			align-items: center;
			border-bottom: 1px solid #f3f4f6;
			padding-bottom: 16px;
			margin-bottom: 16px;
		}
		.ss-field-group.flex-row:last-child {
			border-bottom: 0;
			padding-bottom: 0;
		}
		.ss-field-text {
			max-width: 80%;
		}
		.ss-label {
			font-size: 14px;
			font-weight: 700;
			color: #374151;
		}
		.ss-description {
			font-size: 12px;
			color: #6b7280;
			margin: 0 0 8px 0;
			line-height: 1.5;
		}
		.ss-input-text {
			width: 100% !important;
			max-width: 500px !important;
			border-radius: 8px !important;
			border: 1px solid #d1d5db !important;
			padding: 8px 12px !important;
			box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02) !important;
			transition: all 0.2s ease !important;
		}
		.ss-input-text:focus {
			border-color: #b42318 !important;
			box-shadow: 0 0 0 3px rgba(180, 35, 24, 0.15) !important;
			outline: none !important;
		}
		.ss-textarea {
			width: 100% !important;
			max-width: 600px !important;
			border-radius: 8px !important;
			border: 1px solid #d1d5db !important;
			padding: 8px 12px !important;
			transition: all 0.2s ease !important;
		}
		.ss-textarea:focus {
			border-color: #b42318 !important;
			box-shadow: 0 0 0 3px rgba(180, 35, 24, 0.15) !important;
			outline: none !important;
		}
		.ss-select {
			width: 100% !important;
			max-width: 500px !important;
			border-radius: 8px !important;
			border: 1px solid #d1d5db !important;
			padding: 8px 12px !important;
			height: auto !important;
			background: #ffffff !important;
			box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02) !important;
			transition: all 0.2s ease !important;
		}
		.ss-select:focus {
			border-color: #b42318 !important;
			box-shadow: 0 0 0 3px rgba(180, 35, 24, 0.15) !important;
			outline: none !important;
		}
		.ss-input-color {
			border: 1px solid #d1d5db !important;
			border-radius: 8px !important;
			padding: 2px 6px !important;
			height: 38px !important;
			width: 80px !important;
			background: #ffffff !important;
			cursor: pointer;
			transition: all 0.2s ease !important;
		}
		.ss-input-color:focus {
			border-color: #b42318 !important;
			box-shadow: 0 0 0 3px rgba(180, 35, 24, 0.15) !important;
			outline: none !important;
		}
		.ss-upload-wrapper {
			display: flex;
			gap: 10px;
			align-items: center;
		}
		.ss-upload-btn {
			height: 38px !important;
			padding: 0 16px !important;
			border-radius: 8px !important;
			font-weight: 600 !important;
			cursor: pointer;
			background: #f3f4f6 !important;
			border-color: #d1d5db !important;
			color: #374151 !important;
		}
		.ss-upload-btn:hover {
			background: #e5e7eb !important;
			border-color: #9ca3af !important;
		}
		.ss-logo-preview {
			margin-top: 12px;
			max-width: 180px;
			min-height: 60px;
			border-radius: 8px;
			padding: 12px;
			border: 1px dashed #d1d5db;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.ss-logo-preview.bg-slate-900 {
			background: #0f172a;
			border-color: #334155;
		}
		.ss-logo-preview img {
			max-width: 100%;
			max-height: 80px;
			object-fit: contain;
		}

		/* Switch Toggle styles */
		.ss-switch {
			position: relative;
			display: inline-block;
			width: 50px;
			height: 26px;
		}
		.ss-switch input {
			opacity: 0;
			width: 0;
			height: 0;
		}
		.ss-slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: #d1d5db;
			transition: .3s;
			border-radius: 34px;
		}
		.ss-slider:before {
			position: absolute;
			content: "";
			height: 18px;
			width: 18px;
			left: 4px;
			bottom: 4px;
			background-color: white;
			transition: .3s;
			border-radius: 50%;
		}
		.ss-switch input:checked + .ss-slider {
			background-color: #b42318;
		}
		.ss-switch input:focus + .ss-slider {
			box-shadow: 0 0 1px #b42318;
		}
		.ss-switch input:checked + .ss-slider:before {
			transform: translateX(24px);
		}

		.ss-submit-bar {
			background: #ffffff;
			border-radius: 16px;
			padding: 16px 30px;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
			border: 1px solid rgba(0, 0, 0, 0.05);
			display: flex;
			justify-content: flex-end;
		}
		.ss-submit-btn {
			background: #b42318 !important;
			border-color: #b42318 !important;
			color: #ffffff !important;
			font-weight: 700 !important;
			border-radius: 8px !important;
			padding: 6px 24px !important;
			height: auto !important;
			line-height: 2 !important;
			cursor: pointer;
			transition: all 0.2s ease !important;
			box-shadow: 0 2px 4px rgba(180, 35, 24, 0.15) !important;
		}
		.ss-submit-btn:hover {
			background: #991c13 !important;
			border-color: #991c13 !important;
			box-shadow: 0 4px 6px rgba(180, 35, 24, 0.2) !important;
		}
	</style>

	<!-- Tab Switcher and Media Uploader JavaScript -->
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// 1. Tab switching logic
			const tabBtns = document.querySelectorAll('.ss-tab-btn');
			const tabContents = document.querySelectorAll('.ss-tab-content');
			
			tabBtns.forEach(btn => {
				btn.addEventListener('click', function() {
					const targetTab = this.getAttribute('data-tab');
					
					tabBtns.forEach(b => b.classList.remove('active'));
					tabContents.forEach(c => c.classList.remove('active'));
					
					this.classList.add('active');
					document.getElementById('tab-' + targetTab).classList.add('active');
				});
			});
			
			// 2. WordPress Media Uploader logic
			document.querySelectorAll('.ss-upload-btn').forEach(button => {
				button.addEventListener('click', function(e) {
					e.preventDefault();
					const inputId = this.getAttribute('data-input');
					const inputField = document.getElementById(inputId);
					const previewId = inputId.replace('_input', '_preview');
					const previewContainer = document.getElementById(previewId);
					
					let mediaUploader = wp.media({
						title: 'Pilih Logo Suku Sastra',
						button: {
							text: 'Gunakan Logo Ini'
						},
						multiple: false
					});
					
					mediaUploader.on('select', function() {
						const attachment = mediaUploader.state().get('selection').first().toJSON();
						if (inputField) {
							inputField.value = attachment.url;
						}
						if (previewContainer) {
							previewContainer.innerHTML = '<img src="' + attachment.url + '" alt="Logo Preview">';
						}
					});
					
					mediaUploader.open();
				});
			});

			// 3. Collapsible accordion for banner slots
			const accordionHeaders = document.querySelectorAll('.poetzen-banner-header');
			accordionHeaders.forEach(header => {
				header.addEventListener('click', function() {
					const body = this.nextElementSibling;
					const icon = this.querySelector('.poetzen-accordion-icon');
					body.classList.toggle('hidden');
					if (body.classList.contains('hidden')) {
						icon.textContent = '[+]';
					} else {
						icon.textContent = '[-]';
					}
				});
			});

			// 4. Sub-tab switching logic inside Banner tab
			const subtabBtns = document.querySelectorAll('.poetzen-subtab-btn');
			const subtabContents = document.querySelectorAll('.poetzen-subtab-content');
			
			subtabBtns.forEach(btn => {
				btn.addEventListener('click', function() {
					const targetSubtab = this.getAttribute('data-subtab');
					
					subtabBtns.forEach(b => {
						b.classList.remove('active');
						b.style.borderBottomColor = 'transparent';
						b.style.color = '#6b7280';
					});
					subtabContents.forEach(c => c.classList.add('hidden'));
					
					this.classList.add('active');
					this.style.borderBottomColor = '#b42318';
					this.style.color = '#b42318';
					document.getElementById('subtab-' + targetSubtab).classList.remove('hidden');
				});
			});
		});
	</script>
	<?php
}

add_action( 'admin_init', 'sukusastra_register_options' );
function sukusastra_register_options(): void {
	register_setting( 'sukusastra_options_group', 'sukusastra_options', 'sukusastra_sanitize_options' );
}

function sukusastra_sanitize_options( array $input ): array {
	$output = array();
	$text_keys = array( 
		'instagram', 'twitter', 'facebook', 'youtube', 'tiktok', 'linkedin', 'threads', 'whatsapp', 'copyright', 'logo_light', 'logo_dark',
		'gsc_id', 'ga_id', 'gtm_id', 'meta_pixel_id',
		'color_scheme', 'font_family', 'font_size',
		'header_bg_light', 'header_bg_dark', 'footer_bg',
		'monetization_banner_image', 'monetization_banner_link'
	);
	foreach ( $text_keys as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
	}

	$output['footer_bio'] = isset( $input['footer_bio'] ) ? sanitize_textarea_field( $input['footer_bio'] ) : '';

	if ( isset( $input['banners'] ) && is_array( $input['banners'] ) ) {
		$output['banners'] = poetzen_sanitize_banners( $input['banners'] );
	} else {
		$output['banners'] = array();
	}

	$checkbox_keys = array( 'toggle_news_ticker', 'toggle_penulis_stories', 'toggle_schema', 'monetization_banner_toggle' );
	foreach ( $checkbox_keys as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) && '1' === $input[ $key ] ? '1' : '0';
	}

	$script_keys = array( 'header_scripts', 'footer_scripts' );
	foreach ( $script_keys as $key ) {
		if ( isset( $input[ $key ] ) ) {
			if ( current_user_can( 'unfiltered_html' ) ) {
				$output[ $key ] = $input[ $key ];
			} else {
				$output[ $key ] = wp_kses_post( $input[ $key ] );
			}
		} else {
			$output[ $key ] = '';
		}
	}

	return $output;
}

function sukusastra_get_option( string $key, string $default = '' ): string {
	$options = get_option( 'sukusastra_options', array() );
	return isset( $options[ $key ] ) && '' !== trim( $options[ $key ] ) ? $options[ $key ] : $default;
}
