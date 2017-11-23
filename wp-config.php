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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'MPT');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Qx1*qafB-fjM');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'rW/{;@x4S-xzIpyIy#X90c:3.0M1_.N{AZ/0=72%Wf9#2*fid+ty_Z$o9?- s8>p');
define('SECURE_AUTH_KEY',  '8z!Oz$YmT;-P<qswOsbLjN4<(jeYy5!dLa|bMl2N!du);*4KK|5I8!mf|k;1[pTX');
define('LOGGED_IN_KEY',    ',;%[Sh)FYs#|-)`} 8w+d.wY54$>|saM8FaL89[uQd(0z`6|`+kVh]-DI&}K[cO.');
define('NONCE_KEY',        '(T|nlK%[3trakKV;X]D^$c=S4:#Djj0A[erx*nGaBI!w-6(RT8L|~IKE$#1%+v@`');
define('AUTH_SALT',        '2SdO|2gUYj$}xv,+.& -U|,NMEZvD`T@3$]e|p;Z%?5i:Ew^IuR07G4awU207vw2');
define('SECURE_AUTH_SALT', ')>N[D>hxV!.w!X*KHWJa<eS|n/utl6M(Q7N3#XoTkHG~;@MXi |Nz8KuhS`LnfA+');
define('LOGGED_IN_SALT',   'kH*s%GLq@d`]4zA%8XT[~>5ptdgu&=tG#[[Vp?EzYX$;vZE35d)I<Qi*w$a`%&kU');
define('NONCE_SALT',       'vsa~{wBxzr-ib!_u61 tGX>4Tc;~mJ_5zN-]8m}7r-%U~de]Va:w<`;$GU$Arz~s');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
