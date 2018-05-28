<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Aggregate_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;

/**
 * Class representing an aggregate setting that bundles multiple settings.
 *
 * @since 1.0.0
 */
class Aggregate_Setting extends Object_Setting implements Setting_Registry {

	/**
	 * Registers a setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $id      Unique identifier for the setting.
	 * @param Setting $setting Setting instance.
	 *
	 * @throws Invalid_Identifier_Exception Thrown when the identifier is invalid.
	 * @throws Duplicate_Identifier_Exception Thrown when the identifier is already in use.
	 */
	public function register( string $id, Setting $setting ) {
		if ( ! $this->is_valid_id( $id ) ) {
			throw Invalid_Identifier_Exception::from_id( $id );
		}

		if ( isset( $this->properties_settings[ $id ] ) ) {
			throw Duplicate_Identifier_Exception::from_id( $id );
		}

		$this->properties_settings[ $id ] = $setting;

		$setting_id     = $setting->get_id();
		$setting_schema = $setting->get_schema();

		$this->schema[ self::ARG_PROPERTIES ][ $setting_id ] = $setting_schema;

		if ( isset( $setting_schema[ self::ARG_DEFAULT ] ) ) {
			$this->schema[ self::ARG_DEFAULT ][ $setting_id ] = $setting_schema[ self::ARG_DEFAULT ];
		}
	}

	/**
	 * Retrieves a registered setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the setting.
	 * @return Setting Setting instance.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the setting for the identifier is not registered.
	 */
	public function get_registered( string $id ) : Setting {
		if ( ! isset( $this->properties_settings[ $id ] ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		return $this->properties_settings[ $id ];
	}

	/**
	 * Checks if a setting is registered.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the setting.
	 * @return bool True if the setting is registered, false otherwise.
	 */
	public function is_registered( string $id ) : boolean {
		return isset( $this->properties_settings[ $id ] );
	}

	/**
	 * Gets the registered settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $setting instance pairs.
	 */
	public function get_all_registered() : array {
		return $this->properties_settings;
	}

	/**
	 * Checks whether an identifier is valid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Identifier to check.
	 * @return bool True if valid, false otherwise.
	 */
	protected function is_valid_id( string $id ) : bool {
		return preg_match( '/^[a-z0-9_]+$/', $id );
	}
}
