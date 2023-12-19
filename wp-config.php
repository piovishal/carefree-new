<?php

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings

// By SiteGround Optimizer


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
define( 'DB_NAME', 'dbnchwybbmu09a' );

/** Database username */
define( 'DB_USER', 'uhg3fusfx1oaf' );

/** Database password */
define( 'DB_PASSWORD', 'llkbsvhplelu' );

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
define( 'AUTH_KEY',          '|((gkl9/x~#k/!U<!szZ_:<+;ux2`s$DFP{H{#4,<>6sevAHo^1KnbaeqRpT]x)N' );
define( 'SECURE_AUTH_KEY',   'a1]vRcAE{+Ji7FHvjmCQA,&,dqZVOsiNd,pFl35?O!m)4R>;43kGXJcz*z*$dz_q' );
define( 'LOGGED_IN_KEY',     ':C#%=0o;jawsVBSUcXO=X%5[=?4.pvpo$m?NR@2al(|[hMn&uT4[n84aE=,jo+f ' );
define( 'NONCE_KEY',         '(hD#Q=]nV&@erIdR6r i-eat,6HOai3 !F}^ft%Vq!-E~Nf~#|T-xQ}qSf=bM^lg' );
define( 'AUTH_SALT',         'ZjL9g-/bYRJ62PsbE%T#X]M)rg,e[enc.)+luJwj4D9zE,*MfttW:KSp8ph6L>{|' );
define( 'SECURE_AUTH_SALT',  '2WX[9@o`;[yei=>1,sFBJo==J#dcKL=N>>CzDP@L7c3og.3BHpI(S7(I?cIs,fTv' );
define( 'LOGGED_IN_SALT',    '3gn|O%~!L$ak-IHweKj%b]:u~3H@goVxlb3t#4OO] T~bo1(Eg=-]?=&_54^^yBe' );
define( 'NONCE_SALT',        'g/<&#]Z+T{Z?wDN|7Dk,{du?}A|y}FM8ku=~lp_96*_BH8X7%r_KSHe(UW,Y7m]W' );
define( 'WP_CACHE_KEY_SALT', '>3[6t%/ZOoU|x4}2auoJ<X85`gvGs4?BV{`D9xaVT1W@26a3KJ&r?D$Pk44-=R+`' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'tvk_';


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
// if ( ! defined( 'WP_DEBUG' ) ) {
// 	define( 'WP_DEBUG', true );
// }else{

// 	define( 'WP_DEBUG', true );
// }
/* define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false); */
// Enable WP_DEBUG mode
define( 'WP_DEBUG', false );

// Enable Debug logging to the /wp-content/debug.log file
//  define( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings
// define( 'WP_DEBUG_DISPLAY', true );
// @ini_set( 'display_errors', 0 ); 

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', false );

// ini_set('max_execution_time',350);
// define( 'WP_MAX_MEMORY_LIMIT', '512M' );
define( 'WP_MEMORY_LIMIT', '1024M' );
// ini_set( 'max_input_vars' , 3000 );
define('WP_POST_REVISIONS', 4);

/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
