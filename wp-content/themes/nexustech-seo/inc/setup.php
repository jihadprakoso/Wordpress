<?php
/**
 * Theme supports, menu, widget area, dan ukuran gambar.
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Daftar fitur tema yang diaktifkan.
 */
function nexustech_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 180,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'nexustech-seo' ),
			'footer'  => __( 'Footer Navigation', 'nexustech-seo' ),
		)
	);

	add_image_size( 'nexustech-card', 720, 420, true );
}
add_action( 'after_setup_theme', 'nexustech_setup' );

/**
 * Lebar konten global.
 */
function nexustech_content_width() {
	$GLOBALS['content_width'] = 1140;
}
add_action( 'after_setup_theme', 'nexustech_content_width', 0 );

/**
 * Widget area sidebar.
 */
function nexustech_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Main Sidebar', 'nexustech-seo' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Ditampilkan pada arsip dan halaman statis.', 'nexustech-seo' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title h5">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'nexustech_widgets_init' );

/**
 * Panjang excerpt pada kartu artikel.
 *
 * @param int $length Panjang default.
 * @return int
 */
function nexustech_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'nexustech_excerpt_length' );

/**
 * Penutup excerpt.
 *
 * @return string
 */
function nexustech_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'nexustech_excerpt_more' );

/**
 * Bersihkan rewrite rule saat tema diaktifkan (dipakai sitemap.xml).
 */
function nexustech_after_switch_theme() {
	nexustech_register_rewrites();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'nexustech_after_switch_theme' );

/**
 * Body class tambahan untuk keperluan styling.
 *
 * @param array $classes Body classes.
 * @return array
 */
function nexustech_body_classes( $classes ) {
	$classes[] = 'nexustech-body';

	if ( is_singular() && has_post_thumbnail() ) {
		$classes[] = 'has-hero-image';
	}

	return $classes;
}
add_filter( 'body_class', 'nexustech_body_classes' );
