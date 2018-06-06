<?php
/**
 * Plugin initialization file
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 *
 * @wordpress-plugin
 * Plugin Name: WP GDPR Cookie Notice
 * Plugin URI:  https://wordpress.org/plugins/wp-gdpr-cookie-notice/
 * Description: Simple performant GDPR-compliant cookie consent notice that supports granular cookie control and live preview customization.
 * Version:     1.0.0
 * Author:      Felix Arntz
 * Author URI:  https://leaves-and-love.net
 * License:     GNU General Public License v2 (or later)
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-gdpr-cookie-notice
 * Tags:        gdpr, cookie notice, cookie consent, granular cookie control, customizer
 */

/* This file must be parseable by PHP 5.2. */

defined( 'ABSPATH' ) || exit;

/**
 * Gets the plugin controller instance.
 *
 * Initializes the instance if it does not exist yet.
 *
 * @since 1.0.0
 *
 * @return Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin Plugin controller instance.
 *
 * @throws RuntimeException Thrown when the PHP or WordPress versions used are insufficient.
 */
function wp_gdpr_cookie_notice() {
	static $plugin = null;

	if ( null !== $plugin ) {
		return $plugin;
	}

	$required_php_version = '7.0';
	$required_wp_version  = '4.9.6';
	$php_version          = phpversion();
	$wp_version           = $GLOBALS['wp_version'];

	if ( version_compare( $php_version, $required_php_version, '<' ) ) {
		throw new RuntimeException( sprintf( __( 'WP GDPR Cookie Notice requires at least PHP version %1$s, but you are only running version %2$s.', 'wp-gdpr-cookie-notice' ), $required_php_version, $php_version ) );
	}

	if ( version_compare( $wp_version, $required_wp_version, '<' ) ) {
		throw new RuntimeException( sprintf( __( 'WP GDPR Cookie Notice requires at least WordPress version %1$s, but you are only running version %2$s.', 'wp-gdpr-cookie-notice' ), $required_wp_version, $wp_version ) );
	}

	$namespace = 'Leaves_And_Love\\WP_GDPR_Cookie_Notice';
	$basedir   = plugin_dir_path( __FILE__ ) . 'src';

	require_once $basedir . '/class-autoloader.php';

	$autoloader_class = $namespace . '\\Autoloader';
	$autoloader       = new $autoloader_class();
	$autoloader->register_rule( $namespace, $basedir );
	$autoloader->register_rule( $namespace . '\\Contracts', $basedir . '/contracts', constant( $namespace . '\\Autoloader::TYPE_INTERFACE' ) );
	spl_autoload_register( array( $autoloader, 'load' ) );

	$plugin_class = $namespace . '\\Plugin';
	$plugin       = new $plugin_class( __FILE__ );
	$plugin->add_hooks();

	return $plugin;
}

wp_gdpr_cookie_notice();
