<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db_wordpress_local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'S!-? pRPYPf>2`&0WP16*/W{CCMF4-8-WxNVoBf.N$=$DA.CDW2^:##Y5Z/@U{e,' );
define( 'SECURE_AUTH_KEY',  'T5ik=XjSe!+T4>e2K@N+snbxqfZffOvp%Cy|KPk4&!ECqm}Xpf^|MA0:&@..7vg:' );
define( 'LOGGED_IN_KEY',    'd=+X?t)2:Piw]N+0L1pIq#fq;f#-SR]eLwGNGT*:uq Pf$&[V7Q)6ZnIqG#7c-:3' );
define( 'NONCE_KEY',        'X`4?*=Hb|B$|;!uZahr?ao_<@=_CU[]`pY1&#iGR`IDep9DHOeDKU(%18[|A$|g1' );
define( 'AUTH_SALT',        'Q7Cn`L>C%y E+ez{ag/y!S7@rMH61)p2 (`gu$L~o9@=a!Yh:v$^vYaxlI}8b*8/' );
define( 'SECURE_AUTH_SALT', '-~^PI+>]|u;s<klOrF+Pe2@8r^L KTEz<fHs;EJJM=Wy:/FBCu>[[!kMEK@yab.6' );
define( 'LOGGED_IN_SALT',   'cQ+`o`NjLpn:<p7hc^([yy=/iex TY)d?k md4}-N#ywy$d:-PiZ7 W,2rsehvoh' );
define( 'NONCE_SALT',       '9i)E5Pz_wAE#(D9m)T1QN$|nIp2,7G 12)jAuE(`pEpY%{gEHI)[qv?.$aUcUkj*' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
