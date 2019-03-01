<?php
/**
 * Unit tests bootstrap script.
 *
 * @package WP_GDPR_Cookie_Notice
 */

require __DIR__ . '/bootstrap-common.php';

require_once TESTS_PLUGIN_DIR . '/vendor/autoload.php';

require_once TESTS_PLUGIN_DIR . '/src/class-autoloader.php';

$autoloader = new Felix_Arntz\WP_GDPR_Cookie_Notice\Autoloader();
$autoloader->register_rule( 'Felix_Arntz\\WP_GDPR_Cookie_Notice', TESTS_PLUGIN_DIR . '/src' );
$autoloader->register_rule( 'Felix_Arntz\\WP_GDPR_Cookie_Notice\\Contracts', TESTS_PLUGIN_DIR . '/src/contracts', Felix_Arntz\WP_GDPR_Cookie_Notice\Autoloader::TYPE_INTERFACE );
$autoloader->register_rule( 'Felix_Arntz\\WP_GDPR_Cookie_Notice\\Tests', __DIR__ );
spl_autoload_register( array( $autoloader, 'load' ) );
