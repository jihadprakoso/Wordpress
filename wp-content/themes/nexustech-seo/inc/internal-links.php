<?php
/**
 * Relasi internal link: pillar <-> cluster <-> artikel terkait.
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ambil pillar induk dari sebuah artikel cluster.
 *
 * @param int $post_id ID artikel (opsional).
 * @return WP_Post|null
 */
function nexustech_pillar_of( $post_id = 0 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	if ( ! $post_id ) {
		return null;
	}

	$pillar_id = (int) get_post_meta( $post_id, '_nexustech_pillar', true );
	if ( ! $pillar_id ) {
		return null;
	}

	$pillar = get_post( $pillar_id );
	if ( ! $pillar || 'publish' !== $pillar->post_status || 'page' !== $pillar->post_type ) {
		return null;
	}

	return $pillar;
}

/**
 * Ambil semua artikel cluster milik sebuah pillar.
 *
 * @param int $pillar_id ID pillar.
 * @param int $limit     Jumlah maksimum (-1 = semua).
 * @return WP_Post[]
 */
function nexustech_clusters_of( $pillar_id, $limit = -1 ) {
	$pillar_id = (int) $pillar_id;
	if ( ! $pillar_id ) {
		return array();
	}

	return get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'meta_key'       => '_nexustech_pillar',
			'meta_value'     => $pillar_id,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);
}

/**
 * Artikel terkait berdasarkan kategori yang sama.
 *
 * @param int $post_id ID artikel.
 * @param int $limit   Jumlah maksimum.
 * @return WP_Post[]
 */
function nexustech_related_posts( $post_id = 0, $limit = 3 ) {
	$post_id = $post_id ? (int) $post_id : (int) get_the_ID();
	$args    = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => (int) $limit,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);

	$categories = wp_get_post_categories( $post_id );
	if ( $categories ) {
		$args['category__in'] = $categories;
	}

	return get_posts( $args );
}

/**
 * URL halaman blog (page_for_posts).
 *
 * @return string
 */
function nexustech_blog_url() {
	$blog_id = (int) get_option( 'page_for_posts' );

	return $blog_id ? (string) get_permalink( $blog_id ) : home_url( '/' );
}

/**
 * Ambil halaman landing page (template landing) untuk blok layanan.
 *
 * @return WP_Post[]
 */
function nexustech_landing_pages() {
	return get_pages(
		array(
			'post_status' => 'publish',
			'meta_key'    => '_wp_page_template',
			'meta_value'  => NEXUSTECH_TPL_LANDING,
			'sort_column' => 'menu_order,post_title',
		)
	);
}

/**
 * Ambil halaman pillar (template pillar) untuk blok panduan.
 *
 * @return WP_Post[]
 */
function nexustech_pillar_pages() {
	return get_pages(
		array(
			'post_status' => 'publish',
			'meta_key'    => '_wp_page_template',
			'meta_value'  => NEXUSTECH_TPL_PILLAR,
			'sort_column' => 'menu_order,post_title',
		)
	);
}
