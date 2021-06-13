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
define( 'DB_NAME', 'dbvq9lt7x7gis1' );

/** MySQL database username */
define( 'DB_USER', 'ucbtjwb1trrjg' );

/** MySQL database password */
define( 'DB_PASSWORD', '9w4cunjfgu0a' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          ' ju^[^SWWN^3hNXoN$~x,]};>}y_vAvkey7-S{r;.z/y_`T[9}qg^##6!*(B[=Gv' );
define( 'SECURE_AUTH_KEY',   'D3nmA`JA?-c.I3 J2UTg?6}j_?wSTM@eceRXM9T~@d*Tp/27`kzP#PZn.GKo^!ar' );
define( 'LOGGED_IN_KEY',     '<xCgCt8z:^b N0R ROXM#k@{}U,7(~> xz5`<g= SrRD:!$u|D+|~dQ=X61EAasA' );
define( 'NONCE_KEY',         '{,CXS2S$ [*|na7C@)*J,1a4(M*Hm&S5=h={^87FQ>t8hEm@Q.]:y & WIWR$-AZ' );
define( 'AUTH_SALT',         '$1#<r$/pz_GobH;P$6N]$i3Ml_CIjxq]tx+HIQ.SM+g?+t|:Sr[{%poQd*p%2U}(' );
define( 'SECURE_AUTH_SALT',  '[@->#W0jo6O(].Q,s+tJ[sQN00SJh:h?# 05I6CV_X))_,Zs>zDTFk;ILU,cN:VS' );
define( 'LOGGED_IN_SALT',    '[18F$&tLXgqg8o|*m~96U~E6Ps|M-eh+Og(:9myXrM-gIUW&@M3@M*13,oE?i!:I' );
define( 'NONCE_SALT',        '$C4Cd;O>$vT`&KmrA8muUz84/4P:yix9P5xeeOXuw#y<m|++oZ4=yj6h>/1gm@GQ' );
define( 'WP_CACHE_KEY_SALT', 'H!8;]xTKFiL$YDR~YIRGwv>H/5$4M+;+b&c;bN#c)~z=6+yH %qd?inS#b:/[5@a' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'lvc_';

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system

/* Desactivar Barra 
add_filter('show_admin_bar', '__return_false');
*/
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}
