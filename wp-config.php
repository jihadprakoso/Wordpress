<?php
/**
 * Konfigurasi WordPress — Project "SEO & Landing Page Network" (tema nexustech-seo)
 *
 * Instalasi ini sengaja dipisah dari ../Wordpress/ agar kedua tema
 * (jihad-wp-theme dan nexustech-seo) tidak saling menindih:
 * database, tema, dan URL-nya berbeda.
 */

// ** Pengaturan database ** //
define( 'DB_NAME', 'db_nexustech_seo_local' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**
 * Authentication unique keys and salts.
 */
define( 'AUTH_KEY',         'Z3s/o3bOyNBwtS4iwjN3a0WL4PRa/SBtXFvaEE6aUygnkfo139l3VLdpIMxhQphq' );
define( 'SECURE_AUTH_KEY',  '3bvVN1SmcjrDCep6dqC+gMTeyWajm6rXRa+Enw23agwhx4x2QxREtAxhWVR48pkI' );
define( 'LOGGED_IN_KEY',    'NbmqjObrZRqTPi/ZXNOwz+9mUVPdFX9kwskfU5ELmi7jrsdlBytIXkVfzESiF1vw' );
define( 'NONCE_KEY',        'XH0aX1sbal3aWs3aC1HE7XcNSL9bSvFThWPFfBq8BsfKhgi2sR4ZOTGXy4z34Mml' );
define( 'AUTH_SALT',        'V5TlLksQTVtgTOXcaWYPFaz5h8gjvc6mWCE8r8C2srhZ3CN6swaQ3nkvMkhR5ki4' );
define( 'SECURE_AUTH_SALT', 'eHpCj19KbHf8O6FMiWNJbq2l0rf+QWwUuHK3t1ASHVDuWUIIxOjduE3KBus00Jxi' );
define( 'LOGGED_IN_SALT',   'j6XhlrCEnhXwNQZ+IKSQbboby1LmYLEeX++h98moIbTgvX/Qo6LsDlaIbpJn105+' );
define( 'NONCE_SALT',       'PRLhIhgLjDKZ16FPamYuYD+bpBugOCJokOuWYCAR+dP9Oq0Ye9iStmc7SSOzTiku' );

/** Prefix tabel database. */
$table_prefix = 'wp_';

/** Alamat situs dipatok lewat konstanta agar tidak bergantung isi DB. */
define( 'WP_HOME',    'http://localhost/My_Porto/SEO-Landing' );
define( 'WP_SITEURL', 'http://localhost/My_Porto/SEO-Landing' );

/* Mode pengembangan: error dicatat ke wp-content/debug.log, tidak ditampilkan. */
define( 'WP_DEBUG',         true );
define( 'WP_DEBUG_LOG',     true );
define( 'WP_DEBUG_DISPLAY', false );

/* Hardening instalasi lokal. */
define( 'DISALLOW_FILE_EDIT',       true );
define( 'AUTOMATIC_UPDATER_DISABLED', true );

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
