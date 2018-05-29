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
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Data_Repository;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Data\WordPress_Option_Data_Repository;

/**
 * Class for registering plugin settings.
 *
 * @since 1.0.0
 */
class Plugin_Settings implements Service, Option_Reader {

	/**
	 * Identifier for the plugin's aggregate setting.
	 */
	const SETTING_ID = 'wp_gdpr_cookie_notice';

	/**
	 * Options data repository.
	 *
	 * @since 1.0.0
	 * @var Data_Repository
	 */
	protected $data_repository;

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
	 * Constructor.
	 *
	 * Sets the data repository and cookie types to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Data_Repository $data_repository Optional. Data repository to use.
	 */
	public function __construct( Data_Repository $data_repository = null ) {
		if ( null === $data_repository ) {
			$data_repository = new WordPress_Option_Data_Repository();
		}

		$this->data_repository = $data_repository;
	}

	/**
	 * Gets a single option value.
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
	 * Gets all option values.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options as $option => $value pairs.
	 */
	public function get_options() : array {
		if ( $this->options_dirty ) {
			$this->options       = $this->data_repository->get( self::SETTING_ID );
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
}
