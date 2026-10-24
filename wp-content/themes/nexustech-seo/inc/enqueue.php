<?php
/**
 * Pemuatan aset front-end.
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue CSS & JS.
 */
function nexustech_enqueue_assets() {
	$uri = get_template_directory_uri();
	$ver = NEXUSTECH_VERSION;

	wp_enqueue_style(
		'nexustech-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'nexustech-base', get_stylesheet_uri(), array(), $ver );
	wp_enqueue_style(
		'bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
		array(),
		'5.3.0'
	);
	wp_enqueue_style(
		'fontawesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
		array(),
		'6.4.0'
	);
	wp_enqueue_style(
		'nexustech-theme',
		$uri . '/assets/css/theme.css',
		array( 'bootstrap', 'nexustech-fonts', 'nexustech-base' ),
		$ver
	);

	wp_enqueue_script(
		'bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.0',
		true
	);
	wp_enqueue_script( 'nexustech-theme', $uri . '/assets/js/theme.js', array(), $ver, true );
}
add_action( 'wp_enqueue_scripts', 'nexustech_enqueue_assets' );

/**
 * Preconnect ke Google Fonts.
 *
 * @param array  $urls          Daftar URL.
 * @param string $relation_type Tipe relasi.
 * @return array
 */
function nexustech_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'nexustech_resource_hints', 10, 2 );
