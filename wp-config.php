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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'hijama_database' );

/** Database username */
define( 'DB_USER', 'hijama_user_db' );

/** Database password */
define( 'DB_PASSWORD', 'g9;k]O98i.kt' );

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
define( 'AUTH_KEY',         '`D,V9XDW@}n>|[lIq!F)E$wt?cLK:c2M(Cr&=8rwzvnu)>m %g5bB78ysMt+A]Wh' );
define( 'SECURE_AUTH_KEY',  '0N8 F#gV_Wx)9j^rRu$h$?R@mai[N(SH{bz6ZB8V3dC>g,:UU7m;rSp}@D[^A(K0' );
define( 'LOGGED_IN_KEY',    ',Tj=oZ1f Ns{saxA8aOt~OoE3MZHTBS>mQ$0k*~id$3F|%)=?43csDe0u<2!A*H]' );
define( 'NONCE_KEY',        '5(z`$8.w-[^UzpHO!@#7VpUwa^@*B0rNf28JO;[wA$:NL;}DKRv{mtA[)@97D2D]' );
define( 'AUTH_SALT',        'wVegCAby_A3v70Wn^ZLRCBj+r-u5w&f9:&U[mcbgUu1^=__Pd4!SUJ^x|%B/t4N(' );
define( 'SECURE_AUTH_SALT', '[}xM,c<ep/ ~WV25$`@LG:P!HCT6&|si5ta&zWAcrm(>`P.~_P=qG4APwZ9gY31$' );
define( 'LOGGED_IN_SALT',   '7.]Z@lg%@$j0OETv{Y_Z]w!smE s<j(r3g;:E*wTbNY;.C@d}FShkP.GxFG@(0pF' );
define( 'NONCE_SALT',       '{B$g?UEAI~TqFflM@BdGp,tz7fdFw0wS0q<ViCyXG)k!+$iZ0-vA`m5.{~_o[2&B' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
define('WP_MEMORY_LIMIT','3000M');

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
