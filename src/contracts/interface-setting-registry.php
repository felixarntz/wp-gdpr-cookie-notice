<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting_Registry interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;

/**
 * Interface for a setting registry class.
 *
 * @since 1.0.0
 */
interface Setting_Registry {

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
	public function register( string $id, Setting $setting );

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
	public function get_registered( string $id ) : Setting;

	/**
	 * Checks if a setting is registered.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the setting.
	 * @return bool True if the setting is registered, false otherwise.
	 */
	public function is_registered( string $id ) : bool;

	/**
	 * Gets the registered settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $setting instance pairs.
	 */
	public function get_all_registered() : array;
}
