<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'shoes_wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         ']P4CG]`IH%A~;2YBm/8#_dN)&`IjT@QjAXL#rm v+i4^n=oZ`o*P(nnB N+!JE_o' );
define( 'SECURE_AUTH_KEY',  '|VL4U/I_?@N^|Z:-Iwu>USQgc1]xhnXg_ks;Q*^4Mb6S%sd~Sh<{>}3%gG|ZmP<h' );
define( 'LOGGED_IN_KEY',    '#scGYy$2mT>@O|;500Ms?}F?pL^7|JH@:9Y{^jTa9(R9qKoj $q|!+nWz.G;QrBW' );
define( 'NONCE_KEY',        'Jbi/6V@eeBMZ=1=W=:S_qq}%^gOzo?K{%r9$~9Ey|mJ}d`]#cPm;2x: .@fE~snx' );
define( 'AUTH_SALT',        '>e>%{,I-?Ztv(m1$KHw#UkIY3w.y2`ayX4 7ar0?l5=M_lSrnHrt=:N)wR-!=[vp' );
define( 'SECURE_AUTH_SALT', 'z{zPk>OWb(]~W*]7wOhS_sx,9MkNPjYBD`ddmT.1;1*Qm[)IrgQV?ktx~L,xC}G]' );
define( 'LOGGED_IN_SALT',   'OlCF}P8p8)5W),!.~@mUjgjgweiq7kanp|>]&,)NZnh?&-FO}: gO+tT*|zj1y[v' );
define( 'NONCE_SALT',       '`zu*ji9xO{AKgz&ttm`uy$bn<fmbw*3.IDF>/X5AV7OEgT`^>WHcWzVhgX*mUIc(' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
