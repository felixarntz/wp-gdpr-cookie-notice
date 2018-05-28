<?php
/**
 * PHPUnit tests bootstrap script.
 *
 * @package WP_GDPR_Cookie_Notice
 */

// Disable xdebug backtrace.
if ( function_exists( 'xdebug_disable' ) ) {
	xdebug_disable();
}

/**
 * Manually loads the main plugin file.
 */
function _manually_load_plugin() {
	require dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-gdpr-cookie-notice.php';
}

// Detect where to load the WordPress tests environment from.
if ( false !== getenv( 'WP_TESTS_DIR' ) ) {
	$test_root    = rtrim( getenv( 'WP_TESTS_DIR' ), '/' );
	$_manual_load = true;
} elseif ( false !== getenv( 'WP_DEVELOP_DIR' ) ) {
	$test_root    = getenv( 'WP_DEVELOP_DIR' ) . '/tests/phpunit';
	$_manual_load = true;
} elseif ( file_exists( '/tmp/wordpress-tests-lib/includes/bootstrap.php' ) ) {
	$test_root    = '/tmp/wordpress-tests-lib';
	$_manual_load = true;
} else {
	$test_root    = dirname( dirname( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) ) . '/tests/phpunit';
	$_manual_load = false;
}

require_once $test_root . '/includes/functions.php';

// Ensure the plugin is loaded.
if ( $_manual_load ) {
	tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );
} elseif ( empty( $GLOBALS['wp_tests_options']['active_plugins'] ) ) {
	$GLOBALS['wp_tests_options'] = array(
		'active_plugins' => array( 'wp-gdpr-cookie-notice/wp-gdpr-cookie-notice.php' ),
	);
}

// Load the WordPress tests environment.
require $test_root . '/includes/bootstrap.php';

$autoloader = new Leaves_And_Love\WP_GDPR_Cookie_Notice\Autoloader();
$autoloader->register_rule( 'Leaves_And_Love\\WP_GDPR_Cookie_Notice\\Tests', __DIR__ );
spl_autoload_register( array( $autoloader, 'load' ) );
