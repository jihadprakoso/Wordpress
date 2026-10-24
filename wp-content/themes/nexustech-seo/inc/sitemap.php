<?php
/**
 * Endpoint SEO: /sitemap.xml (daftar URL) dan /robots.txt (menunjuk sitemap).
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Daftarkan rewrite rule untuk /sitemap.xml dan /robots.txt.
 *
 * WordPress hanya menambahkan rule robots.txt bila situs dipasang di akar
 * domain, sehingga pada instalasi sub-direktori endpoint ini harus didaftarkan
 * sendiri oleh tema.
 */
function nexustech_register_rewrites() {
	add_rewrite_rule( '^sitemap\.xml$', 'index.php?nexustech_sitemap=1', 'top' );
	add_rewrite_rule( '^robots\.txt$', 'index.php?nexustech_robots=1', 'top' );
}
add_action( 'init', 'nexustech_register_rewrites' );

/**
 * Daftarkan query var kustom.
 *
 * @param string[] $vars Query vars.
 * @return string[]
 */
function nexustech_register_query_vars( $vars ) {
	$vars[] = 'nexustech_sitemap';
	$vars[] = 'nexustech_robots';
	return $vars;
}
add_filter( 'query_vars', 'nexustech_register_query_vars' );

/**
 * Cegah redirect canonical menambahkan garis miring pada endpoint tema.
 *
 * @param string|false $redirect URL redirect canonical.
 * @return string|false
 */
function nexustech_skip_canonical_for_endpoints( $redirect ) {
	if ( get_query_var( 'nexustech_sitemap' ) || get_query_var( 'nexustech_robots' ) ) {
		return false;
	}

	return $redirect;
}
add_filter( 'redirect_canonical', 'nexustech_skip_canonical_for_endpoints' );

/**
 * Escape nilai untuk konteks XML.
 *
 * @param string $value Nilai mentah.
 * @return string
 */
function nexustech_xml_escape( $value ) {
	return htmlspecialchars( (string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8' );
}

/**
 * Susun satu entri <url>.
 *
 * @param string $loc     URL.
 * @param string $lastmod Tanggal modifikasi (format c) atau kosong.
 * @param string $freq    Changefreq.
 * @param string $priority Prioritas.
 * @return string
 */
function nexustech_sitemap_entry( $loc, $lastmod, $freq, $priority ) {
	$out  = "\t<url>\n";
	$out .= "\t\t<loc>" . nexustech_xml_escape( $loc ) . "</loc>\n";

	if ( $lastmod ) {
		$out .= "\t\t<lastmod>" . nexustech_xml_escape( $lastmod ) . "</lastmod>\n";
	}

	$out .= "\t\t<changefreq>" . nexustech_xml_escape( $freq ) . "</changefreq>\n";
	$out .= "\t\t<priority>" . nexustech_xml_escape( $priority ) . "</priority>\n";
	$out .= "\t</url>\n";

	return $out;
}

/**
 * Layani endpoint tema: /sitemap.xml dan /robots.txt.
 */
function nexustech_render_endpoints() {
	if ( get_query_var( 'nexustech_robots' ) ) {
		do_robots();
		exit;
	}

	if ( ! get_query_var( 'nexustech_sitemap' ) ) {
		return;
	}

	$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

	$front_id  = (int) get_option( 'page_on_front' );
	$front_mod = $front_id ? get_post_modified_time( 'c', true, $front_id ) : gmdate( 'c' );
	$xml      .= nexustech_sitemap_entry( home_url( '/' ), $front_mod, 'daily', '1.0' );

	$blog_id = (int) get_option( 'page_for_posts' );
	if ( $blog_id ) {
		$xml .= nexustech_sitemap_entry( get_permalink( $blog_id ), get_post_modified_time( 'c', true, $blog_id ), 'daily', '0.9' );
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'post__not_in'   => array_filter( array( $front_id, $blog_id ) ),
			'orderby'        => 'title',
			'order'          => 'ASC',
		)
	);

	foreach ( $pages as $page ) {
		$xml .= nexustech_sitemap_entry( get_permalink( $page ), get_post_modified_time( 'c', true, $page ), 'weekly', '0.8' );
	}

	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	foreach ( $posts as $post_item ) {
		$xml .= nexustech_sitemap_entry( get_permalink( $post_item ), get_post_modified_time( 'c', true, $post_item ), 'monthly', '0.7' );
	}

	foreach ( get_categories( array( 'hide_empty' => true ) ) as $category ) {
		$xml .= nexustech_sitemap_entry( get_category_link( $category->term_id ), '', 'weekly', '0.5' );
	}

	$xml .= '</urlset>' . "\n";

	header( 'Content-Type: application/xml; charset=UTF-8' );
	echo $xml; // phpcs:ignore WordPress.Security.EscapeOutput -- sudah di-escape per nilai.
	exit;
}
add_action( 'template_redirect', 'nexustech_render_endpoints' );

/**
 * Tambahkan baris Sitemap pada robots.txt.
 *
 * @param string $output Isi robots.txt.
 * @return string
 */
function nexustech_robots_txt( $output ) {
	return $output . "\nSitemap: " . home_url( '/sitemap.xml' ) . "\n";
}
add_filter( 'robots_txt', 'nexustech_robots_txt' );
