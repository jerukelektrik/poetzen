<?php
/**
 * Custom post types.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'sukusastra_register_post_types' );
function sukusastra_register_post_types(): void {
	sukusastra_register_review_buku_type();
	sukusastra_register_berita_type();
	sukusastra_register_event_type();
	sukusastra_register_penulis_type();
	sukusastra_register_terbitan_type();
	sukusastra_register_komunitas_type();

	// Flush rewrite rules on CPT change to prevent 404
	if ( ! get_transient( 'sukusastra_komunitas_flushed' ) ) {
		flush_rewrite_rules( false );
		set_transient( 'sukusastra_komunitas_flushed', '1', DAY_IN_SECONDS );
	}
}

function sukusastra_register_review_buku_type(): void {
	register_post_type(
		'review_buku',
		array(
			'labels'       => array(
				'name'          => __( 'Reviu Buku', 'sukusastra' ),
				'singular_name' => __( 'Reviu Buku', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Reviu Buku', 'sukusastra' ),
				'edit_item'     => __( 'Edit Reviu Buku', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-book-alt',
			'rewrite'      => array( 'slug' => 'review-buku' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			'taxonomies'   => array( 'category' ),
		)
	);
}

function sukusastra_register_berita_type(): void {
	register_post_type(
		'berita',
		array(
			'labels'       => array(
				'name'          => __( 'Peristiwa', 'sukusastra' ),
				'singular_name' => __( 'Peristiwa', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Peristiwa', 'sukusastra' ),
				'edit_item'     => __( 'Edit Peristiwa', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-megaphone',
			'rewrite'      => array( 'slug' => 'peristiwa' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
		)
	);
}

function sukusastra_register_event_type(): void {
	register_post_type(
		'event',
		array(
			'labels'       => array(
				'name'          => __( 'Event', 'sukusastra' ),
				'singular_name' => __( 'Event', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Event', 'sukusastra' ),
				'edit_item'     => __( 'Edit Event', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-calendar-alt',
			'rewrite'      => array( 'slug' => 'event' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
		)
	);
}

function sukusastra_register_penulis_type(): void {
	register_post_type(
		'penulis',
		array(
			'labels'       => array(
				'name'          => __( 'Penulis', 'sukusastra' ),
				'singular_name' => __( 'Penulis', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Penulis/Tokoh', 'sukusastra' ),
				'edit_item'     => __( 'Edit Penulis/Tokoh', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-admin-users',
			'rewrite'      => array( 'slug' => 'penulis' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		)
	);
}

function sukusastra_register_terbitan_type(): void {
	register_post_type(
		'terbitan',
		array(
			'labels'       => array(
				'name'          => __( 'Katalog Terbitan', 'sukusastra' ),
				'singular_name' => __( 'Terbitan', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Terbitan', 'sukusastra' ),
				'edit_item'     => __( 'Edit Terbitan', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-book-alt',
			'rewrite'      => array( 'slug' => 'katalog-terbitan' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			'taxonomies'   => array( 'category' ),
		)
	);
}

function sukusastra_register_komunitas_type(): void {
	register_post_type(
		'komunitas',
		array(
			'labels'       => array(
				'name'          => __( 'Komunitas', 'sukusastra' ),
				'singular_name' => __( 'Komunitas', 'sukusastra' ),
				'add_new_item'  => __( 'Tambah Komunitas', 'sukusastra' ),
				'edit_item'     => __( 'Edit Komunitas', 'sukusastra' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-groups',
			'rewrite'      => array( 'slug' => 'komunitas' ),
			'show_in_rest' => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		)
	);
}

/**
 * Add custom columns to CPT Penulis list in admin dashboard.
 */
add_filter( 'manage_edit-penulis_columns', 'sukusastra_set_penulis_columns' );
function sukusastra_set_penulis_columns( array $columns ): array {
	$new_columns = array();
	foreach ( $columns as $key => $title ) {
		$new_columns[ $key ] = $title;
		if ( 'title' === $key ) {
			$new_columns['jumlah_karya'] = __( 'Karya', 'sukusastra' );
		}
	}
	return $new_columns;
}

add_action( 'manage_penulis_posts_custom_column', 'sukusastra_custom_penulis_column', 10, 2 );
function sukusastra_custom_penulis_column( string $column, int $post_id ): void {
	if ( 'jumlah_karya' === $column ) {
		global $wpdb;
		$count = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->posts p 
				 INNER JOIN $wpdb->postmeta pm ON p.ID = pm.post_id 
				 WHERE pm.meta_key = '_ss_original_author_id' 
				   AND pm.meta_value = %d 
				   AND p.post_status = 'publish' 
				   AND p.post_type IN ('post', 'review_buku', 'event', 'berita')",
				$post_id
			)
		);

		if ( $count > 0 ) {
			echo '<strong>' . esc_html( $count ) . '</strong>';
		} else {
			echo '<span class="text-slate-400">—</span>';
		}
	}
}

/**
 * Make the custom Karya column sortable in admin dashboard list.
 */
add_filter( 'manage_edit-penulis_sortable_columns', 'sukusastra_penulis_sortable_columns' );
function sukusastra_penulis_sortable_columns( array $sortable_columns ): array {
	$sortable_columns['jumlah_karya'] = 'jumlah_karya';
	return $sortable_columns;
}

add_action( 'pre_get_posts', 'sukusastra_penulis_orderby_query' );
function sukusastra_penulis_orderby_query( WP_Query $query ): void {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'penulis' === $query->get( 'post_type' ) && 'jumlah_karya' === $query->get( 'orderby' ) ) {
		add_filter( 'posts_clauses', 'sukusastra_penulis_orderby_clauses', 10, 2 );
	}
}

function sukusastra_penulis_orderby_clauses( array $clauses, WP_Query $query ): array {
	global $wpdb;

	$order = strtoupper( $query->get( 'order' ) );
	if ( 'ASC' !== $order && 'DESC' !== $order ) {
		$order = 'DESC';
	}

	// Join with a subquery counting published posts per CPT penulis
	$clauses['join'] .= " LEFT JOIN (
		SELECT pm.meta_value AS penulis_id, COUNT(*) AS karya_count 
		FROM {$wpdb->postmeta} pm 
		INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
		WHERE pm.meta_key = '_ss_original_author_id' 
		  AND p.post_status = 'publish' 
		  AND p.post_type IN ('post', 'review_buku', 'event', 'berita')
		GROUP BY pm.meta_value
	) AS kc ON {$wpdb->posts}.ID = kc.penulis_id";

	$clauses['orderby'] = "COALESCE(kc.karya_count, 0) {$order}, {$wpdb->posts}.post_title {$order}";

	// Remove the filter so it doesn't affect subsequent queries
	remove_filter( 'posts_clauses', 'sukusastra_penulis_orderby_clauses', 10 );

	return $clauses;
}

/**
 * Add custom column "Penulis Asli" to the Posts list.
 */
add_filter( 'manage_post_posts_columns', 'sukusastra_add_post_columns' );
function sukusastra_add_post_columns( array $columns ): array {
	$new_columns = array();
	foreach ( $columns as $key => $title ) {
		$new_columns[ $key ] = $title;
		if ( 'title' === $key ) {
			$new_columns['penulis_asli'] = __( 'Penulis Asli', 'sukusastra' );
		}
	}
	return $new_columns;
}

/**
 * Render the content of the "Penulis Asli" column.
 */
add_action( 'manage_post_posts_custom_column', 'sukusastra_render_post_columns', 10, 2 );
function sukusastra_render_post_columns( string $column, int $post_id ): void {
	if ( 'penulis_asli' === $column ) {
		$orig_author = sukusastra_get_original_author( $post_id );
		$author_id = $orig_author ? $orig_author->ID : 0;
		$author_title = $orig_author ? $orig_author->post_title : '—';

		echo '<div class="penulis-asli-display">' . esc_html( $author_title ) . '</div>';
		echo '<div class="hidden-penulis-asli-id" style="display:none;" data-id="' . esc_attr( (string) $author_id ) . '"></div>';
	}
}

/**
 * Output the CPT Penulis select box inside WordPress Quick Edit template.
 */
add_action( 'quick_edit_custom_box', 'sukusastra_quick_edit_penulis', 10, 2 );
function sukusastra_quick_edit_penulis( string $column_name, string $post_type ): void {
	if ( 'penulis_asli' !== $column_name || 'post' !== $post_type ) {
		return;
	}

	$authors = get_posts(
		array(
			'post_type'      => 'penulis',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	wp_nonce_field( 'sukusastra_quick_edit_nonce', 'sukusastra_quick_edit_nonce' );
	?>
	<fieldset class="inline-edit-col-right" style="margin-top: 10px;">
		<div class="inline-edit-col">
			<label class="alignleft">
				<span class="title"><?php esc_html_e( 'Penulis Asli', 'sukusastra' ); ?></span>
				<select name="_ss_original_author_id" id="_ss_original_author_id" style="max-width: 300px;">
					<option value=""><?php esc_html_e( '— Tanpa Penulis/Tokoh —', 'sukusastra' ); ?></option>
					<?php foreach ( $authors as $author ) : ?>
						<option value="<?php echo esc_attr( (string) $author->ID ); ?>">
							<?php echo esc_html( $author->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
	</fieldset>
	<?php
}

/**
 * Save the quick edit data.
 */
add_action( 'save_post_post', 'sukusastra_save_quick_edit_penulis', 10, 3 );
function sukusastra_save_quick_edit_penulis( int $post_id, WP_Post $post, bool $update ): void {
	if ( ! $update ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['sukusastra_quick_edit_nonce'] ) || ! wp_verify_nonce( $_POST['sukusastra_quick_edit_nonce'], 'sukusastra_quick_edit_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['_ss_original_author_id'] ) ) {
		$author_id = sanitize_text_field( wp_unslash( $_POST['_ss_original_author_id'] ) );
		if ( '' === $author_id ) {
			delete_post_meta( $post_id, '_ss_original_author_id' );
		} else {
			update_post_meta( $post_id, '_ss_original_author_id', (int) $author_id );
		}
	}
}

/**
 * Print Javascript in edit screen footer to populate custom fields when Quick Edit opens.
 */
add_action( 'admin_footer-edit.php', 'sukusastra_quick_edit_javascript' );
function sukusastra_quick_edit_javascript(): void {
	global $current_screen;
	if ( ! $current_screen || 'post' !== $current_screen->post_type ) {
		return;
	}
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		const wp_inline_edit = inlineEditPost.edit;
		inlineEditPost.edit = function(id) {
			wp_inline_edit.apply(this, arguments);

			const post_id = inlineEditPost.getId(id);
			const row = $('#post-' + post_id);
			const penulis_id = row.find('.hidden-penulis-asli-id').data('id');

			const edit_row = $('#edit-' + post_id);
			edit_row.find('#_ss_original_author_id').val(penulis_id || '');
		};
	});
	</script>
	<?php
}




