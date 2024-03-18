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
define( 'DB_NAME', 'shop' );

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
define( 'AUTH_KEY',         '#s%XNzk]q*!*?8{2*OzC%%{^cn80Q82!w>Yd/J>rXtELylEClv&a#:e^{y?F%Vrt' );
define( 'SECURE_AUTH_KEY',  'vAFcy AK@EAnTTJyI/woi?oqp4[2wey]h.i-S1=UuY3E,mx{r)0t^<oBW;xINS(7' );
define( 'LOGGED_IN_KEY',    'u<(ctog$4=;|p>2&{@T[rcicXq c:^-5(u_kp:[rxZ+oQA^bPwH09WBw}*X01i6J' );
define( 'NONCE_KEY',        'W}_NFd$Ecb:XnN7mV_fj$6xhHv$LkMn%k(aF&9.^FJ-J&](71l4lS$:-!Tb$jJ8/' );
define( 'AUTH_SALT',        ';VKTEptLW,oJWY5,|3J<zU8Aen7dO 3FaPz-f|6SXe=m5H+<Sps&|Z<?Lo:L]C1#' );
define( 'SECURE_AUTH_SALT', '40b>)M$w^8rOJf2O>P7}y,qx!sl#sRL0P@5LNcEP^^^l-$bgfjKC]MGpWD@1A!=k' );
define( 'LOGGED_IN_SALT',   'odpU)<=h`tI80b9hKd7SUTNb9*qP|>6Q`wHY1pclOTB1[eoy-#+>`_kO rbd_VcS' );
define( 'NONCE_SALT',       'Um=2mnoLpp08Pr;Gv].k+YalqR4N.t9=V##[qUga/WhRc%Ao*6BMLn{{iQhc%`rC' );

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
