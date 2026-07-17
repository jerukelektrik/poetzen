<?php
/**
 * Dynamic XML Sitemap System without plugins.
 *
 * @package SukuSastra
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. Register Rewrite Rules
add_action( 'init', 'sukusastra_sitemap_init' );
function sukusastra_sitemap_init(): void {
	add_rewrite_rule( '^sitemap\.xml$', 'index.php?ss_sitemap=index', 'top' );
	add_rewrite_rule( '^sitemap\.xsl$', 'index.php?ss_sitemap=xsl', 'top' );
	add_rewrite_rule( '^sitemap-([a-z0-9_-]+)\.xml$', 'index.php?ss_sitemap=$matches[1]', 'top' );
}

// Automatically flush rewrite rules once on next admin load to register the sitemap URLs
add_action( 'admin_init', 'sukusastra_sitemap_flush_rules_once' );
function sukusastra_sitemap_flush_rules_once(): void {
	if ( ! get_option( 'sukusastra_sitemap_flushed_v1' ) ) {
		sukusastra_sitemap_init();
		flush_rewrite_rules();
		update_option( 'sukusastra_sitemap_flushed_v1', 1 );
	}
}


// 2. Add query vars
add_filter( 'query_vars', 'sukusastra_sitemap_query_vars' );
function sukusastra_sitemap_query_vars( array $vars ): array {
	$vars[] = 'ss_sitemap';
	return $vars;
}

// 3. Prevent canonical redirects for sitemap URLs
add_filter( 'redirect_canonical', 'sukusastra_sitemap_prevent_canonical', 10, 2 );
function sukusastra_sitemap_prevent_canonical( string $redirect_url, string $requested_url ): string {
	if ( false !== strpos( $requested_url, 'sitemap' ) ) {
		return '';
	}
	return $redirect_url;
}

// 4. Handle template redirect
add_action( 'template_redirect', 'sukusastra_sitemap_handle_redirect' );
function sukusastra_sitemap_handle_redirect(): void {
	$sitemap = get_query_var( 'ss_sitemap' );
	if ( ! $sitemap ) {
		return;
	}

	if ( 'xsl' === $sitemap ) {
		header( 'Content-Type: text/xml; charset=utf-8' );
		sukusastra_render_sitemap_xsl();
		exit;
	}

	header( 'Content-Type: text/xml; charset=utf-8' );
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<?xml-stylesheet type="text/xsl" href="' . esc_url( home_url( '/sitemap.xsl' ) ) . '"?>' . "\n";

	if ( 'index' === $sitemap ) {
		sukusastra_render_sitemap_index();
	} else {
		sukusastra_render_sitemap_section( $sitemap );
	}
	exit;
}

// 5. Get sitemap last modified time
function sukusastra_get_sitemap_lastmod( string $post_type = 'post', string $taxonomy = '' ): string {
	global $wpdb;
	if ( $taxonomy ) {
		$lastmod = $wpdb->get_var( $wpdb->prepare(
			"SELECT MAX(p.post_modified_gmt) 
			 FROM {$wpdb->posts} p
			 INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
			 INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
			 WHERE tt.taxonomy = %s AND p.post_status = 'publish'",
			$taxonomy
		) );
	} else {
		$lastmod = $wpdb->get_var( $wpdb->prepare(
			"SELECT MAX(post_modified_gmt) FROM {$wpdb->posts} WHERE post_type = %s AND post_status = 'publish'",
			$post_type
		) );
	}
	return $lastmod ? mysql2date( 'Y-m-d H:i +00:00', $lastmod ) : date( 'Y-m-d H:i +00:00' );
}

// 6. Get sitemap taxonomy term last modified time
function sukusastra_get_term_lastmod( int $term_id, string $taxonomy ): string {
	global $wpdb;
	$lastmod = $wpdb->get_var( $wpdb->prepare(
		"SELECT MAX(p.post_modified_gmt) 
		 FROM {$wpdb->posts} p
		 INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
		 WHERE tr.term_taxonomy_id = %d AND p.post_status = 'publish'",
		$term_id
	) );
	return $lastmod ? mysql2date( 'Y-m-d H:i +00:00', $lastmod ) : date( 'Y-m-d H:i +00:00' );
}

// 7. Render Sitemap Index
function sukusastra_render_sitemap_index(): void {
	$sitemaps = array(
		'post'      => 'post',
		'page'      => 'page',
		'penulis'   => 'penulis',
		'berita'    => 'berita',
		'event'     => 'event',
		'terbitan'  => 'terbitan',
		'komunitas' => 'komunitas',
	);

	echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

	foreach ( $sitemaps as $key => $type ) {
		$lastmod = sukusastra_get_sitemap_lastmod( $type );
		echo '  <sitemap>' . "\n";
		echo '    <loc>' . esc_url( home_url( "/sitemap-{$key}.xml" ) ) . '</loc>' . "\n";
		echo '    <lastmod>' . esc_html( $lastmod ) . '</lastmod>' . "\n";
		echo '  </sitemap>' . "\n";
	}

	// Taxonomies
	$taxonomies = array(
		'category' => 'category',
		'post_tag' => 'post_tag',
	);
	foreach ( $taxonomies as $key => $tax ) {
		$lastmod = sukusastra_get_sitemap_lastmod( 'post', $tax );
		echo '  <sitemap>' . "\n";
		echo '    <loc>' . esc_url( home_url( "/sitemap-{$key}.xml" ) ) . '</loc>' . "\n";
		echo '    <lastmod>' . esc_html( $lastmod ) . '</lastmod>' . "\n";
		echo '  </sitemap>' . "\n";
	}

	echo '</sitemapindex>' . "\n";
}

// 8. Render Sub Sitemap Section
function sukusastra_render_sitemap_section( string $section ): void {
	$valid_post_types = array( 'post', 'page', 'penulis', 'berita', 'event', 'terbitan', 'komunitas' );
	$valid_taxonomies = array( 'category', 'post_tag' );

	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

	if ( in_array( $section, $valid_post_types, true ) ) {
		$query = new WP_Query(
			array(
				'post_type'      => $section,
				'post_status'    => 'publish',
				'posts_per_page' => 1000,
				'orderby'        => 'modified',
				'order'          => 'DESC',
			)
		);

		while ( $query->have_posts() ) {
			$query->the_post();
			echo '  <url>' . "\n";
			echo '    <loc>' . esc_url( get_permalink() ) . '</loc>' . "\n";
			echo '    <lastmod>' . esc_html( get_the_modified_date( 'Y-m-d H:i +00:00' ) ) . '</lastmod>' . "\n";
			echo '  </url>' . "\n";
		}
		wp_reset_postdata();
	} elseif ( in_array( $section, $valid_taxonomies, true ) ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $section,
				'hide_empty' => true,
			)
		);

		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				echo '  <url>' . "\n";
				echo '    <loc>' . esc_url( get_term_link( $term ) ) . '</loc>' . "\n";
				$lastmod = sukusastra_get_term_lastmod( $term->term_id, $section );
				echo '    <lastmod>' . esc_html( $lastmod ) . '</lastmod>' . "\n";
				echo '  </url>' . "\n";
			}
		}
	}

	echo '</urlset>' . "\n";
}

// 9. Render XSL Stylesheet
function sukusastra_render_sitemap_xsl(): void {
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	?>
<xsl:stylesheet version="2.0"
	xmlns:html="http://www.w3.org/TR/REC-html40"
	xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
	xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>XML Sitemap - Suku Sastra</title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<style type="text/css">
					body {
						font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
						color: #333;
						margin: 0;
						padding: 40px 20px;
						background: #fafafa;
					}
					.container {
						max-width: 800px;
						margin: 0 auto;
						background: #fff;
						padding: 30px;
						border-radius: 12px;
						box-shadow: 0 4px 12px rgba(0,0,0,0.05);
					}
					h1 {
						font-size: 24px;
						color: #1e293b;
						margin-top: 0;
						margin-bottom: 10px;
					}
					p {
						font-size: 14px;
						color: #64748b;
						margin-bottom: 20px;
						line-height: 1.5;
					}
					p a {
						color: #b91c1c;
						text-decoration: none;
						font-weight: bold;
					}
					p a:hover {
						text-decoration: underline;
					}
					table {
						width: 100%;
						border-collapse: collapse;
						margin-top: 20px;
					}
					th {
						text-align: left;
						padding: 12px 10px;
						background: #f1f5f9;
						color: #475569;
						font-size: 13px;
						font-weight: bold;
						border-bottom: 2px solid #e2e8f0;
					}
					td {
						padding: 12px 10px;
						border-bottom: 1px solid #e2e8f0;
						font-size: 13px;
					}
					tr:nth-child(even) td {
						background: #f8fafc;
					}
					tr:hover td {
						background: #f1f5f9;
					}
					a {
						color: #0f172a;
						text-decoration: none;
					}
					a:hover {
						color: #b91c1c;
						text-decoration: underline;
					}
					.lastmod {
						color: #64748b;
						font-family: monospace;
					}
				</style>
			</head>
			<body>
				<div class="container">
					<h1>XML Sitemap Index</h1>
					<p>Sitemap ini dibuat secara otomatis untuk membantu mesin pencari seperti Google mengindeks tulisan dan halaman di <a href="https://www.sukusastra.com/">Suku Sastra</a> secara cepat.</p>
					
					<xsl:if test="sitemap:sitemapindex">
						<p>Sitemap Index ini memiliki <xsl:value-of select="count(sitemap:sitemapindex/sitemap:sitemap)"/> bagian sitemap.</p>
						<table>
							<thead>
								<tr>
									<th width="70%">Sitemap</th>
									<th width="30%">Last Modified</th>
								</tr>
							</thead>
							<tbody>
								<xsl:for-each select="sitemap:sitemapindex/sitemap:sitemap">
									<tr>
										<td>
											<a href="{sitemap:loc}"><xsl:value-of select="sitemap:loc"/></a>
										</td>
										<td class="lastmod">
											<xsl:value-of select="sitemap:lastmod"/>
										</td>
									</tr>
								</xsl:for-each>
							</tbody>
						</table>
					</xsl:if>
					
					<xsl:if test="sitemap:urlset">
						<p>Sitemap ini berisi <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/> URL.</p>
						<p><a href="https://www.sukusastra.com/sitemap.xml">&larr; Kembali ke Sitemap Index</a></p>
						<table>
							<thead>
								<tr>
									<th width="80%">URL</th>
									<th width="20%">Last Modified</th>
								</tr>
							</thead>
							<tbody>
								<xsl:for-each select="sitemap:urlset/sitemap:url">
									<tr>
										<td>
											<a href="{sitemap:loc}"><xsl:value-of select="sitemap:loc"/></a>
										</td>
										<td class="lastmod">
											<xsl:value-of select="sitemap:lastmod"/>
										</td>
									</tr>
								</xsl:for-each>
							</tbody>
						</table>
					</xsl:if>
				</div>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>
	<?php
}
