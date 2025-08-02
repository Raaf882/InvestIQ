<?php
define( 'WP_CACHE', true );

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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u787703943_Sxv6I' );

/** Database username */
define( 'DB_USER', 'u787703943_I5i7E' );

/** Database password */
define( 'DB_PASSWORD', 'e06Zti034Q' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'fISZV.YLxC-x8w7~Om1Wo4=0~n|7X`YUmd+$o&wRk/i3LT c(zD+Y*UJ6O=6o)!(' );
define( 'SECURE_AUTH_KEY',   'MKS|=xz{@BgK.iuHo&C-BU4:zCLx}yP}ZJoA(j/Ne]/S5noJM!b.hwPHczGLS<1i' );
define( 'LOGGED_IN_KEY',     '}b@3C2<t::VV7vc%Ggf[N| b~bw;R8]D,x^bZW}V%B!Ya#PHRfFM|Iw~(i2FeRlf' );
define( 'NONCE_KEY',         '3iBf}`N<fZ]maCF<=)AS)FBvuDldBZr^@m@mgVU.0S6fb+k<JV7I8Kpd(Hun-e9g' );
define( 'AUTH_SALT',         '8%j1%0~$~^ZDP,FaI[x.:Y^o[d?fBS,`=Ls=S(]AXGgSgC7oV)(?0JpGYhz@UwQ9' );
define( 'SECURE_AUTH_SALT',  '2/aoH?M,AxL]%p<1=,[*V,T(]<dPaaAxwUe/%Zk$-Bu`^u;S.7Zi$_t8csb.fQqa' );
define( 'LOGGED_IN_SALT',    '?5FQky!R}Hw]mz9YgtyV5x[N8UE-o8H_K^@5)#J}p$^dH}cM:);tT>Xa{.m6_<(S' );
define( 'NONCE_SALT',        '0E?7<mr%%iw(*X&YxlWIVg:acp1[f{6f*5TzIDi&?W,_gIM<^4&T|65BQgN18||5' );
define( 'WP_CACHE_KEY_SALT', 'y^/RjS<X&IuRH-W+0I2Z<:pJpQ4ywB<#(SOj]$&1j: Skp#x)Nt=)OW~OssYb2=x' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', true );
}
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', 'd890d8e8ded1adfea27b264965143061' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
