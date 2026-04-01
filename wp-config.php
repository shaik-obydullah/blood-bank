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
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'if0_41015595_wp618' );

/** Database username */
define( 'DB_USER', '41015595_2' );

/** Database password */
define( 'DB_PASSWORD', '8FR1p]3S3(' );

/** Database hostname */
define( 'DB_HOST', 'sql312.byetcluster.com' );

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
define( 'AUTH_KEY',         'd5fmmkwzonjmkmex66vbrp20l5d93jku0hmpbnusatakjj6tnwcvjm0pk88hmwgb' );
define( 'SECURE_AUTH_KEY',  'd0lt9qwf2md7xgemf2tke4krb9fbyeghcevhjkeg7yc9z8bc0yfhkl7bohv56sv6' );
define( 'LOGGED_IN_KEY',    'h56b5hcziiw4jsyzkxxoiacicnaphwfskb0pjynhg551xd12c01vqtdwcea6uhng' );
define( 'NONCE_KEY',        'dnfcojncw5psbu4ea2winwhkskoyqfrbleh44qiacpg5kwrh4by24eb164sttu6o' );
define( 'AUTH_SALT',        'yxk7na76ujwd8o3qg9xofif5u8a1cqigwlr5oiqq0v17xtyd0or4polsqi3yqnoz' );
define( 'SECURE_AUTH_SALT', 'wpq4oldnabrdsg824cvmak0bpla2bcofqkrvskfuagnash6v4cmlqtjpvibdutan' );
define( 'LOGGED_IN_SALT',   'wimhyy4i82bea2vntvrf1ocmwfonlhmgotmh870hzd5fqp3oiwwlc94qdpnxyxmn' );
define( 'NONCE_SALT',       'pukg6shz5njlyva9loimzhfnvxzjtichp85lrjqa4psesbsot669ju9w3gvuoqds' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp6r_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
