<?php
/**
 * Setup sekali-jalan untuk instalasi WordPress project "SEO & Landing Page Network".
 *
 * Menangani: instalasi WP, opsi dasar, menghapus konten bawaan, dan mengaktifkan tema.
 * Idempoten — aman dijalankan berulang.
 *
 * Jalankan:
 *   /Applications/XAMPP/xamppfiles/bin/php tools/setup-site.php
 *
 * @package nexustech-seo
 */

if ( PHP_SAPI !== 'cli' ) {
	http_response_code( 403 );
	exit( 'Skrip ini hanya boleh dijalankan dari command line.' );
}

define( 'WP_INSTALLING', true );

require_once dirname( __DIR__ ) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

// ------------------------------------------------------------------
// 1. Instalasi WordPress
// ------------------------------------------------------------------
if ( ! is_blog_installed() ) {
	$result = wp_install(
		'NexusTech SEO Network',
		'admin',
		'admin@nexustech.local',
		false,
		'',
		'admin'
	);
	echo "WordPress terinstal. User ID: {$result['user_id']}\n";
} else {
	echo "WordPress sudah terinstal, lewati langkah instalasi.\n";
}

// ------------------------------------------------------------------
// 2. Opsi dasar
// ------------------------------------------------------------------
update_option( 'blogdescription', 'Agensi digital: web development, SEO, dan mobile app.' );
update_option( 'timezone_string', 'Asia/Jakarta' );
update_option( 'date_format', 'j F Y' );
update_option( 'posts_per_page', 6 );
update_option( 'blog_public', 0 );          // situs demo lokal, jangan diindeks mesin pencari
update_option( 'default_ping_status', 'closed' );
update_option( 'default_comment_status', 'closed' );

// Permalink SEO: /%postname%/ (tanpa prefix tanggal/ID)
update_option( 'permalink_structure', '/%postname%/' );
if ( function_exists( 'flush_rewrite_rules' ) ) {
	flush_rewrite_rules();
}

// ------------------------------------------------------------------
// 3. Hapus konten bawaan WordPress
// ------------------------------------------------------------------
$default_post = get_page_by_path( 'hello-world', OBJECT, 'post' );
if ( $default_post instanceof WP_Post ) {
	wp_delete_post( $default_post->ID, true );
	echo "Post bawaan 'hello-world' dihapus.\n";
}

$default_page = get_page_by_path( 'sample-page', OBJECT, 'page' );
if ( $default_page instanceof WP_Post ) {
	wp_delete_post( $default_page->ID, true );
	echo "Page bawaan 'sample-page' dihapus.\n";
}

// ------------------------------------------------------------------
// 4. Aktifkan tema nexustech-seo bila folder temanya sudah ada
// ------------------------------------------------------------------
$theme_slug = 'nexustech-seo';
$themes     = wp_get_themes();
if ( isset( $themes[ $theme_slug ] ) ) {
	switch_theme( $theme_slug );
	echo "Tema aktif: {$theme_slug}\n";
} else {
	echo "PERINGATAN: tema '{$theme_slug}' belum ada di wp-content/themes/. Tema belum diaktifkan.\n";
}

echo "Setup selesai.\n";
