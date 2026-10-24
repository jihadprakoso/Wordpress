<?php
/**
 * Breadcrumb: tampilan visual.
 *
 * Daftar item juga dipakai ulang oleh inc/schema.php untuk BreadcrumbList.
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Susun daftar item breadcrumb.
 *
 * @return array[] Tiap item: array( 'label' => string, 'url' => string )
 */
function nexustech_breadcrumb_items() {
	$items = array(
		array(
			'label' => __( 'Beranda', 'nexustech-seo' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_front_page() ) {
		return $items;
	}

	$blog_id    = (int) get_option( 'page_for_posts' );
	$blog_url   = $blog_id ? (string) get_permalink( $blog_id ) : '';
	$blog_label = $blog_id ? get_the_title( $blog_id ) : __( 'Blog', 'nexustech-seo' );

	if ( is_home() ) {
		$items[] = array(
			'label' => $blog_label,
			'url'   => $blog_url,
		);
	} elseif ( is_singular( 'post' ) ) {
		$items[] = array(
			'label' => $blog_label,
			'url'   => $blog_url,
		);

		$categories = get_the_category();
		if ( $categories ) {
			$items[] = array(
				'label' => $categories[0]->name,
				'url'   => (string) get_category_link( $categories[0]->term_id ),
			);
		}

		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( (int) get_queried_object_id() ) ) as $ancestor_id ) {
			$items[] = array(
				'label' => get_the_title( $ancestor_id ),
				'url'   => (string) get_permalink( $ancestor_id ),
			);
		}

		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_category() ) {
		$items[] = array(
			'label' => $blog_label,
			'url'   => $blog_url,
		);
		$items[] = array(
			'label' => single_cat_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_tag() ) {
		$items[] = array(
			'label' => $blog_label,
			'url'   => $blog_url,
		);
		$items[] = array(
			'label' => single_tag_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_author() ) {
		$items[] = array(
			'label' => sprintf( __( 'Penulis: %s', 'nexustech-seo' ), get_the_author_meta( 'display_name', (int) get_queried_object_id() ) ),
			'url'   => '',
		);
	} elseif ( is_search() ) {
		$items[] = array(
			'label' => sprintf( __( 'Pencarian: %s', 'nexustech-seo' ), get_search_query() ),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		$items[] = array(
			'label' => __( 'Halaman tidak ditemukan', 'nexustech-seo' ),
			'url'   => '',
		);
	} elseif ( is_archive() ) {
		$items[] = array(
			'label' => wp_strip_all_tags( get_the_archive_title() ),
			'url'   => '',
		);
	}

	return $items;
}

/**
 * Cetak breadcrumb visual (Bootstrap).
 */
function nexustech_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$items = nexustech_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}

	echo '<nav class="nt-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'nexustech-seo' ) . '"><ol class="breadcrumb">';

	$last = count( $items ) - 1;
	foreach ( $items as $index => $item ) {
		if ( $index === $last || '' === $item['url'] ) {
			printf(
				'<li class="breadcrumb-item active" aria-current="page">%s</li>',
				esc_html( $item['label'] )
			);
		} else {
			printf(
				'<li class="breadcrumb-item"><a href="%s">%s</a></li>',
				esc_url( $item['url'] ),
				esc_html( $item['label'] )
			);
		}
	}

	echo '</ol></nav>';
}
