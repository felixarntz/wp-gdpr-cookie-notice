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
	 * Identifier for the plugin's aggregate setting.
	 */
	const SETTING_ID = 'wp_gdpr_cookie_notice';

	/**
	 * Internal storage for the plugin options.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $options = [];

	/**
	 * Internal flag for whether the options in the internal storage are outdated.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $options_dirty = false;

	/**
	 * Gets a single plugin option value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Option identifier.
	 * @return mixed Option value, or null if invalid option.
	 */
	public function get_option( string $id ) {
		$options = $this->get_options();

		if ( ! isset( $options[ $id ] ) ) {
			return null;
		}

		return $options[ $id ];
	}

	/**
	 * Gets all plugin option values.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options as $option => $value pairs.
	 */
	public function get_options() : array {
		if ( $this->options_dirty ) {
			$this->options       = (array) get_option( self::SETTING_ID );
			$this->options_dirty = false;
		}

		return $this->options;
	}

	/**
	 * Initializes the class functionality.
	 *
	 * @since 1.0.0
	 */
	public function initialize() {
		add_action( 'init', function() {
			$this->register_settings();

			$this->options_dirty = true;
		}, 10, 0 );

		$make_dirty = function() {
			$this->options_dirty = true;
		};

		add_action( 'add_option_' . self::SETTING_ID, $make_dirty, 10, 0 );
		add_action( 'update_option_' . self::SETTING_ID, $make_dirty, 10, 0 );
		add_action( 'delete_option_' . self::SETTING_ID, $make_dirty, 10, 0 );
	}

	/**
	 * Registers plugin settings.
	 *
	 * @since 1.0.0
	 */
	protected function register_settings() {
		$setting_registry = new Aggregate_Setting( self::SETTING_ID, [
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

		( new WordPress_Setting_Registry( self::SETTING_ID ) )->register( self::SETTING_ID, $setting_registry );
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
