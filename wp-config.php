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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         'y4D+nMqY.yu r%VqVie%o6`7Ias8JD@}XrLI6k(~[(Ibp|rDN=_ZO!R2J}C1S%Lb' );
define( 'SECURE_AUTH_KEY',  '7LSg/QqX+rAIH)WE?Wt%+^kU=|zhmaEN%$^sG|nrRJlMKb0e2FhpG:;ugH7JCl3[' );
define( 'LOGGED_IN_KEY',    ',1_2De,5nXokGr?%261.5v&]L*5G3fiRt^/[aWv&.FrLg1!6(du4a/wp5@g.W?i#' );
define( 'NONCE_KEY',        'q|E%Q/~K]K]YN)I9%>XgKWN##x:Dy@#9/QSB]?|$-]VA~#nwy6,O054]]gu`Wh{)' );
define( 'AUTH_SALT',        'r0J`-;C!2W03)z0:jvV3#9.[*8vTzG%s>eA{*g]$Vd=6DtLnGcSxtQ%DL+*NyJ.l' );
define( 'SECURE_AUTH_SALT', '8v^90Upbw_<f,2=k6&4~rr_@&nU$]%S4bC y%@R@D4x?EqY7)32?)=8u&n:pPs#8' );
define( 'LOGGED_IN_SALT',   's)- O86CsH|@9}HG;}Z}B?dezVo1ivGN&mWwd:xq3X]Xk<4np/no~?{%8SR?9oH<' );
define( 'NONCE_SALT',       '[mStO_,o[lPgy|{Q! Azai]2gxeqif;QO[qrE]Znsv<Pn0M)j=q-h#Wn_,)r`9)W' );
define('WP_HOME', 'http://mountainartisancollective.com');
define('WP_SITEURL', 'http://mountainartisancollective.com');

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

define('DISALLOW_FILE_EDIT', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
