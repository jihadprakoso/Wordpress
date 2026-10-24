<?php
/**
 * Bootstrap tema NexusTech SEO.
 *
 * @package NexusTech_SEO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NEXUSTECH_VERSION', '1.0.0' );

/**
 * Nama template halaman (dipakai tema & seeder).
 */
define( 'NEXUSTECH_TPL_LANDING', 'page-templates/template-landing.php' );
define( 'NEXUSTECH_TPL_PILLAR', 'page-templates/template-pillar.php' );

$nexustech_modules = array(
	'setup',
	'enqueue',
	'seo-meta',
	'breadcrumbs',
	'schema',
	'internal-links',
	'meta-boxes',
	'sitemap',
);

foreach ( $nexustech_modules as $nexustech_module ) {
	require_once get_template_directory() . '/inc/' . $nexustech_module . '.php';
}
unset( $nexustech_modules, $nexustech_module );
