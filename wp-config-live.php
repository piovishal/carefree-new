<?php
define( 'WP_CACHE', false ); // By Speed Optimizer by SiteGround

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

//define( 'WP_AUTO_UPDATE_CORE', false );

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'cfColoradoDMKyhXMFStaging_1663613568' );

/** MySQL database username */
define( 'DB_USER', 'cfColoradoDMKyhXMFStaging_1663613568' );

/** MySQL database password */
define( 'DB_PASSWORD', '85hXkW91jBwzHZ79131kHRtVPLpJgixh1Ea0hUW2Cp0p6d6SUg4DS' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
define( 'WP_DEBUG', TRUE );

define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'jG{^<YMU)ghTZ.x]+Dn@aUk-dArsJbza$#BTpEF=@ ,o@-Iv-  Z(U1m/i>nvwTh' );
define( 'SECURE_AUTH_KEY',   '$Kq`MRc8NUenL)CSU2dRDDvy{)wlV5EPc8Qsr2EPF?g=<(wn2EV@.$.+vD^G?o6~' );
define( 'LOGGED_IN_KEY',     '(f YCEZRT;~%vuH7@@)f*v  Oke<[=Gx H%R<O{G|J~hqaML{X=owIK&fwO[n_>@' );
define( 'NONCE_KEY',         'CJejW7I]+*kdleZfTrr(d(0Tj}w`ESx_8G0nspuTcTlxO5XYEV=/}#GESFD@|[Jb' );
define( 'AUTH_SALT',         '0@;K1T}bw>#]5X#u:Qz_|9LlcLRkcSo+( v$nNsFM7h`k$)R{Y0i;IYGe|:Y`]TA' );
define( 'SECURE_AUTH_SALT',  'rKt1VM=A%8vDbM`Xb;`=C}(Fwx5dl@^7v`h~woWZ~.!H)yyK{xp/H7.MRj^],Sk_' );
define( 'LOGGED_IN_SALT',    '7-~l^L#@qv[/@kd*t0/|e.;lWxH^vPr/-q=g}_EC7s/cCA)V51* ;#|0^: !3G&>' );
define( 'NONCE_SALT',        '*d)sDl(.t-A}Z~N9 XTbNUzZ0Di|TL(sz,0GM~X$O&jeF!S4whQ>eX.(A=<e4Xa(' );
define( 'WP_CACHE_KEY_SALT', 'O2Ho3hnxtYEtF9fuCSpeA6z3h79oMR9JaN7NnIO0DyAFD1Y0n74C9gnilDcyMnud' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'tvk_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';