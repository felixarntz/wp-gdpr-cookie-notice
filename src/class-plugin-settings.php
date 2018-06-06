<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin_Settings class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Aggregate_Setting;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\WordPress_Setting_Registry;

/**
 * Class for registering plugin settings.
 *
 * @since 1.0.0
 */
class Plugin_Settings implements Integration {

	/**
	 * Option reader to manage for the plugin's settings.
	 *
	 * @since 1.0.0
	 * @var Option_Reader
	 */
	protected $option_reader;

	/**
	 * Constructor.
	 *
	 * Sets the option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Option_Reader $option_reader Option reader to use.
	 */
	public function __construct( Option_Reader $option_reader ) {
		$this->option_reader = $option_reader;
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		$setting_id = $this->option_reader->get_setting_id();

		add_action( 'init', [ $this, 'register_settings' ], 10, 0 );

		add_action( 'add_option_' . $setting_id, [ $this->option_reader, 'set_options_dirty' ], 10, 0 );
		add_action( 'update_option_' . $setting_id, [ $this->option_reader, 'set_options_dirty' ], 10, 0 );
		add_action( 'delete_option_' . $setting_id, [ $this->option_reader, 'set_options_dirty' ], 10, 0 );
	}

	/**
	 * Registers plugin settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {
		$setting_id       = $this->option_reader->get_setting_id();
		$setting_group    = $setting_id;
		$setting_registry = new Aggregate_Setting( $setting_id, [
			Aggregate_Setting::ARG_DESCRIPTION => __( 'Settings for the WP GDPR Cookie Notice plugin.', 'wp-gdpr-cookie-notice' ),
		] );

		/**
		 * Fires when the plugin's settings are registered.
		 *
		 * @since 1.0.0
		 *
		 * @param Setting_Registry $setting_registry Setting registry to register plugin settings with.
		 */
		do_action( 'wp_gdpr_cookie_notice_register_settings', $setting_registry );

		( new WordPress_Setting_Registry( $setting_group ) )->register( $setting_id, $setting_registry );

		$this->option_reader->set_options_dirty();
	}
}
