<?php
/**
 * Common tests bootstrap behavior.
 *
 * @package WP_GDPR_Cookie_Notice
 */

// Disable xdebug backtrace.
if ( function_exists( 'xdebug_disable' ) ) {
	xdebug_disable();
}

define( 'TESTS_PLUGIN_DIR', dirname( dirname( __DIR__ ) ) );
