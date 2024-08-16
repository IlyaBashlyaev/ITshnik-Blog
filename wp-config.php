<?php
define( 'WP_CACHE', false ); // Added by WP Rocket

/** Enable W3 Total Cache */
 // Added by WP Rocket

/** Enable W3 Total Cache */
 // Added by WP Rocket

 // Added by WP Rocket

 // Added by WP Rocket

error_reporting(0);
 // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
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
define( 'DB_NAME', 'blog' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         '+mE7_NQ^2%,LmAm_{bp-{=WY.|5]ahJ;_,j_/pg6/hSp:bgWW};1];nEhQ^-k~[3' );
define( 'SECURE_AUTH_KEY',  '^YkuL}[kM{omFL6>>(/C7N+PgX=agd2eZXi*CfE)6wsH7%&Wc Do<QCnJf2q-g^r' );
define( 'LOGGED_IN_KEY',    '~>~obB5s0m_HPxc5,xN~OhJB?!)Py~Mz.E(f/U?d3-es V0}jnQ9s<A=cJ1[Md5g' );
define( 'NONCE_KEY',        'JKgN&S*:r9G>K4[4-UnGs3w6)@honz^ow@/(0c JB??eR!yp1+jx%Tye{<}Rn(Tj' );
define( 'AUTH_SALT',        '6:e^,T-}N.0HJ0}zI|z~HA{TTIx)=YBUe@+e9FDNoHD[4967hyqxVMq`$IcCUu??' );
define( 'SECURE_AUTH_SALT', '$ k!=DE?yi:!XN(gF|ku_Di3Eg(uGIQ4$O@~i.~5#MO]#42KOVa7jt65YItHzOT+' );
define( 'LOGGED_IN_SALT',   ' P}Rq:-D<Y4DE)9WwWezO-uZW=+<K{xM&8G2%WCC]Y#]8 #:3/lp|%GiJP8C?|PR' );
define( 'NONCE_SALT',       '/}443s<kj$0j0J$i@e&l}>b}HP<lYOcR|%L,v.Av5=vGDb*c>iF1o1Lc1CY,G&yQ' );

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

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
