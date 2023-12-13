<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gamma' );

/** Database username */
define( 'DB_USER', 'digidbuser' );

/** Database password */
define( 'DB_PASSWORD', 'jkRT34*mwq' );

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
define( 'AUTH_KEY',         'Q5t4?a:~[]wCWswO,{Hhor>pA:mbklea-9K_C-%z+ }+x5Mcg_keIvX Sz3~qtdc' );
define( 'SECURE_AUTH_KEY',  ' 9Tg|vK@MVsT:Z45Q5*l,#O;=,(MTPk16K{lbi3/ztT|pkO,q:t_+jds[_hUw@T3' );
define( 'LOGGED_IN_KEY',    '#/VU;cv0@9}C8*0+JUhA6/Yz{_TzWS9.e%UX$41y?|-7til>u%hd9:^WV&gM@Kyt' );
define( 'NONCE_KEY',        'lQAs}D%J),.QDk;d5?4L-g+|6MwxpB6qm4g0]J.e,TUtcZu;:iVIL|)2jQK?n(zB' );
define( 'AUTH_SALT',        'iuZ)V?bL*pv)8c2g.&fpon||p)BKp8WVu =KYmh@A.(}f~%+0h#3GPhk K.lfmt0' );
define( 'SECURE_AUTH_SALT', ',EO]voGH#m|j9cZZZD)5Yb5.TwN&z>g)?XwV3ew&pvBgfDrt~{)PG+r%[kTrAoIc' );
define( 'LOGGED_IN_SALT',   '7Q|3D)1DN@u;Zn!)BM1x<P0v:smq?@Eh6{vOFL>@>L?}h3%;QG[ $JElbV~>xm2:' );
define( 'NONCE_SALT',       'DqoXCy&jY16zF^UjxrQ!n1MIy7iT]RL6[7_]en7*fm&fd[5wj3}Q`2_Zo=(B/x5m' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
