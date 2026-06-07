<?php
/**
 * Native Theme Options.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'sukusastra_add_options_menu' );
function sukusastra_add_options_menu(): void {
	add_theme_page(
		__( 'Theme Options Suku Sastra', 'sukusastra' ),
		__( 'Theme Options', 'sukusastra' ),
		'edit_theme_options',
		'sukusastra_options',
		'sukusastra_render_options_page'
	);
}

function sukusastra_render_options_page(): void {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Theme Options Suku Sastra', 'sukusastra' ); ?></h1>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'sukusastra_options_group' );
			do_settings_sections( 'sukusastra_options' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action( 'admin_init', 'sukusastra_register_options' );
function sukusastra_register_options(): void {
	register_setting( 'sukusastra_options_group', 'sukusastra_options', 'sukusastra_sanitize_options' );

	add_settings_section(
		'sukusastra_social_section',
		__( 'Social Media & Kontak', 'sukusastra' ),
		'__return_false',
		'sukusastra_options'
	);

	add_settings_section(
		'sukusastra_scripts_section',
		__( 'Script Tambahan (Header / Footer)', 'sukusastra' ),
		'__return_false',
		'sukusastra_options'
	);

	add_settings_section(
		'sukusastra_footer_section',
		__( 'Footer Copyright', 'sukusastra' ),
		'__return_false',
		'sukusastra_options'
	);

	$social_fields = array(
		'instagram' => __( 'Instagram URL', 'sukusastra' ),
		'twitter'   => __( 'Twitter / X URL', 'sukusastra' ),
		'facebook'  => __( 'Facebook URL', 'sukusastra' ),
		'youtube'   => __( 'YouTube Channel URL', 'sukusastra' ),
		'whatsapp'  => __( 'WhatsApp Contact Number / Link', 'sukusastra' ),
	);

	foreach ( $social_fields as $key => $label ) {
		add_settings_field(
			"ss_{$key}_field",
			$label,
			'sukusastra_render_text_option',
			'sukusastra_options',
			'sukusastra_social_section',
			array( 'key' => $key )
		);
	}

	add_settings_field(
		'ss_copyright_field',
		__( 'Copyright Footer', 'sukusastra' ),
		'sukusastra_render_text_option',
		'sukusastra_options',
		'sukusastra_footer_section',
		array( 'key' => 'copyright' )
	);

	add_settings_field(
		'ss_header_scripts_field',
		__( 'Header Scripts (Google Analytics, dll.)', 'sukusastra' ),
		'sukusastra_render_textarea_option',
		'sukusastra_options',
		'sukusastra_scripts_section',
		array( 'key' => 'header_scripts' )
	);

	add_settings_field(
		'ss_footer_scripts_field',
		__( 'Footer Scripts', 'sukusastra' ),
		'sukusastra_render_textarea_option',
		'sukusastra_options',
		'sukusastra_scripts_section',
		array( 'key' => 'footer_scripts' )
	);
}

function sukusastra_render_text_option( array $args ): void {
	$options = get_option( 'sukusastra_options', array() );
	$key     = $args['key'];
	$value   = isset( $options[ $key ] ) ? $options[ $key ] : '';
	printf(
		'<input class="regular-text" name="sukusastra_options[%1$s]" type="text" value="%2$s">',
		esc_attr( $key ),
		esc_attr( $value )
	);
}

function sukusastra_render_textarea_option( array $args ): void {
	$options = get_option( 'sukusastra_options', array() );
	$key     = $args['key'];
	$value   = isset( $options[ $key ] ) ? $options[ $key ] : '';
	printf(
		'<textarea class="large-text code" rows="5" name="sukusastra_options[%1$s]">%2$s</textarea>',
		esc_attr( $key ),
		esc_textarea( $value )
	);
}

function sukusastra_sanitize_options( array $input ): array {
	$output = array();
	$text_keys = array( 'instagram', 'twitter', 'facebook', 'youtube', 'whatsapp', 'copyright' );
	foreach ( $text_keys as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
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
