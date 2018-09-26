<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Data_Repository;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Data\WordPress_Option_Data_Repository;

/**
 * Class for accessing plugin option values.
 *
 * @since 1.0.0
 */
class Plugin_Option_Reader implements Service, Option_Reader {

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
	 * Sets the data repository to use.
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
		if ( $this->options_dirty || is_customize_preview() ) {
			$this->options       = $this->data_repository->get( self::SETTING_ID );
			$this->options_dirty = false;
		}

		return $this->options;
	}

	/**
	 * Sets the internal flag that the options in the internal storage are outdated.
	 *
	 * @since 1.0.0
	 */
	public function set_options_dirty() {
		$this->options_dirty = true;
	}

	/**
	 * Gets the setting identifier used by the option reader.
	 *
	 * @since 1.0.0
	 *
	 * @return string Setting identifier.
	 */
	public function get_setting_id() : string {
		return self::SETTING_ID;
	}
}
