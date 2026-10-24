<?php
/**
 * Meta SEO: title, description, canonical, Open Graph, Twitter Card, robots.
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta description untuk tampilan yang sedang dibuka.
 *
 * Prioritas: meta box -> excerpt -> isi konten -> deskripsi situs.
 *
 * @return string
 */
function nexustech_get_meta_description() {
	$text = '';

	if ( is_singular() ) {
		$post_id = (int) get_queried_object_id();
		$text    = (string) get_post_meta( $post_id, '_nexustech_meta_description', true );

		if ( '' === trim( $text ) ) {
			$post = get_queried_object();
			if ( $post instanceof WP_Post ) {
				$text = ( '' !== trim( (string) $post->post_excerpt ) ) ? $post->post_excerpt : $post->post_content;
			}
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$text = term_description();
	} elseif ( is_author() ) {
		$text = (string) get_the_author_meta( 'description', (int) get_queried_object_id() );
	} elseif ( is_home() ) {
		$blog_id = (int) get_option( 'page_for_posts' );
		if ( $blog_id ) {
			$text = (string) get_post_meta( $blog_id, '_nexustech_meta_description', true );
		}
	}

	if ( '' === trim( $text ) ) {
		$text = (string) get_bloginfo( 'description' );
	}

	$text = wp_strip_all_tags( strip_shortcodes( (string) $text ) );
	$text = trim( preg_replace( '/\s+/u', ' ', $text ) );

	if ( '' === $text ) {
		return '';
	}

	if ( function_exists( 'mb_strlen' ) && mb_strlen( $text ) > 155 ) {
		$text = rtrim( mb_substr( $text, 0, 152 ) ) . '...';
	}

	return $text;
}

/**
 * URL canonical untuk tampilan yang sedang dibuka.
 *
 * @return string
 */
function nexustech_get_canonical() {
	if ( is_404() ) {
		return '';
	}

	if ( is_singular() ) {
		return (string) get_permalink();
	}

	if ( is_front_page() ) {
		return home_url( '/' );
	}

	if ( is_home() ) {
		$blog_id = (int) get_option( 'page_for_posts' );
		return (string) ( $blog_id ? get_permalink( $blog_id ) : home_url( '/' ) );
	}

	$paged = max( 1, (int) get_query_var( 'paged' ) );
	if ( $paged > 1 ) {
		return (string) get_pagenum_link( $paged );
	}

	if ( is_category() ) {
		return (string) get_category_link( (int) get_queried_object_id() );
	}

	if ( is_tag() ) {
		return (string) get_tag_link( (int) get_queried_object_id() );
	}

	if ( is_author() ) {
		return (string) get_author_posts_url( (int) get_queried_object_id() );
	}

	if ( is_search() ) {
		return (string) get_search_link( get_search_query() );
	}

	return home_url( '/' );
}

/**
 * Gambar utama untuk Open Graph.
 *
 * @return string
 */
function nexustech_get_og_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = get_the_post_thumbnail_url( (int) get_queried_object_id(), 'full' );
		if ( $src ) {
			return (string) $src;
		}
	}

	return '';
}

/**
 * Judul dokumen versi kustom.
 *
 * @param string $title Judul bawaan WordPress.
 * @return string
 */
function nexustech_document_title( $title ) {
	$site = get_bloginfo( 'name', 'display' );

	if ( is_front_page() ) {
		$tagline = trim( (string) get_bloginfo( 'description', 'display' ) );
		return $tagline ? $site . ' — ' . $tagline : $site;
	}

	if ( is_home() ) {
		$blog_id = (int) get_option( 'page_for_posts' );
		$label   = $blog_id ? get_the_title( $blog_id ) : __( 'Blog', 'nexustech-seo' );
		return sprintf( '%1$s | %2$s', $label, $site );
	}

	if ( is_singular() ) {
		return sprintf( '%1$s | %2$s', wp_strip_all_tags( get_the_title() ), $site );
	}

	if ( is_category() ) {
		return sprintf( '%1$s | %2$s', sprintf( __( 'Kategori: %s', 'nexustech-seo' ), single_cat_title( '', false ) ), $site );
	}

	if ( is_tag() ) {
		return sprintf( '%1$s | %2$s', sprintf( __( 'Tag: %s', 'nexustech-seo' ), single_tag_title( '', false ) ), $site );
	}

	if ( is_author() ) {
		return sprintf( '%1$s | %2$s', sprintf( __( 'Penulis: %s', 'nexustech-seo' ), get_the_author_meta( 'display_name', (int) get_queried_object_id() ) ), $site );
	}

	if ( is_search() ) {
		return sprintf( '%1$s | %2$s', sprintf( __( 'Hasil pencarian "%s"', 'nexustech-seo' ), get_search_query() ), $site );
	}

	if ( is_404() ) {
		return sprintf( '%1$s | %2$s', __( 'Halaman tidak ditemukan', 'nexustech-seo' ), $site );
	}

	if ( is_archive() ) {
		return sprintf( '%1$s | %2$s', wp_strip_all_tags( get_the_archive_title() ), $site );
	}

	return $title;
}
add_filter( 'pre_get_document_title', 'nexustech_document_title' );

/**
 * Cetak seluruh tag SEO ke <head>.
 */
function nexustech_print_seo_head() {
	$desc      = nexustech_get_meta_description();
	$canonical = nexustech_get_canonical();
	$image     = nexustech_get_og_image();
	$title     = wp_get_document_title();
	$site      = get_bloginfo( 'name', 'display' );
	$noindex   = is_search() || is_404();

	if ( '' !== $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}

	if ( '' !== $canonical ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
	}

	printf(
		'<meta name="robots" content="%s">' . "\n",
		$noindex ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'
	);

	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular( 'post' ) ? 'article' : 'website' );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $site ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );

	if ( '' !== $desc ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	}

	if ( '' !== $canonical ) {
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $canonical ) );
	}

	if ( '' !== $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}

	if ( is_singular( 'post' ) ) {
		printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( get_the_date( 'c' ) ) );
		printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( get_the_modified_date( 'c' ) ) );
	}

	printf( '<meta name="twitter:card" content="%s">' . "\n", '' !== $image ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );

	if ( '' !== $desc ) {
		printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	}

	if ( '' !== $image ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
	}

	printf( '<meta name="theme-color" content="#4F46E5">' . "\n" );
}
add_action( 'wp_head', 'nexustech_print_seo_head', 1 );
