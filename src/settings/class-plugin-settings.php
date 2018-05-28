<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Settings class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting_Registry;

/**
 * Class for registering plugin settings.
 *
 * @since 1.0.0
 */
class Plugin_Settings implements Service {

	/**
	 * Initializes the class functionality.
	 *
	 * @since 1.0.0
	 */
	public function initialize() {
		add_action( 'init', function() {
			$this->register_settings();
		}, 10, 0 );
	}

	/**
	 * Registers plugin settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_settings() {
		$id = 'wp_gdpr_cookie_notice';

		$setting_registry = new Aggregate_Setting( $id, [
			Aggregate_Setting::ARG_DESCRIPTION => __( 'Settings for the WP GDPR Cookie Notice plugin.', 'wp-gdpr-cookie-notice' ),
		] );

		$this->register_default_settings( $setting_registry );

		/**
		 * Fires when the plugin's settings are registered.
		 *
		 * @since 1.0.0
		 *
		 * @param Setting_Registry $setting_registry Setting registry to register plugin settings with.
		 */
		do_action( 'wp_gdpr_cookie_notice_register_settings', $setting_registry );

		( new WordPress_Setting_Registry( $id ) )->register( $id, $setting_registry );
	}

	/**
	 * Registers the plugin's default settings.
	 *
	 * @since 1.0.0
	 *
	 * @param Setting_Registry $setting_registry Setting registry to register plugin settings with.
	 */
	protected function register_default_settings( Setting_Registry $setting_registry ) {

	}
}
