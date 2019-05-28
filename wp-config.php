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
define('DB_NAME', 'hmc_proj');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'hr-$WNMf?Cwn<XUMQ^=hQb~4C>*Qcn{:X[? laTLx6.Ik!VyqIc:Qh#$OW6Knf^f');
define('SECURE_AUTH_KEY',  'X5Ny82T>ZTI1!vx[k,t_Q)7)*~S]cmuuK8LxBsb$`$-<yY1{LJ{&kqK2XXLD%]oa');
define('LOGGED_IN_KEY',    'J;.(3xOSO6`W5[@mUlF5Uid.OK,^`.eb?LU_;#V%%k[h??XF ;OM<vl6;q_YuCM`');
define('NONCE_KEY',        ' =W$Lz?,>H[oOm_uhV&B=cY{Vw#X#8f%SlvryvvgiMB{<-|Ud#LIU0k:,mh?F :T');
define('AUTH_SALT',        '`UXIO2/*KW#)<pSl%6$Tw3gxVY$foi7n!D#!+.g.p=DK`+V3f!hy?QDG&h8c+32m');
define('SECURE_AUTH_SALT', '<^eH.n|{s>}l?-1@I[i=(+nVkqkU8x$-f(Gg|Bm7G&vZ76+q43gK]Lpt:BlKnj!y');
define('LOGGED_IN_SALT',   'Qc(-!!l5YY5p}sCme.|i!0Ij7r <_>m%EUtbN[S?k`I>Rw!2 f<9_$Hvo:p@)NZP');
define('NONCE_SALT',       'oYsg+:.tHrhQl9|6sN;#^PXD=!XgA,k{Efp4KHRi=dE#K;7f(9uh7$:S`FhP7vgn');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'hmc';

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
//change debug on 
//define('WP_DEBUG', false);
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
