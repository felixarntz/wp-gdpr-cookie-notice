<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration_Registry interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;

/**
 * Interface for a cookie integration registry class.
 *
 * @since 1.0.0
 */
interface Cookie_Integration_Registry {

	/**
	 * Registers a cookie integration.
	 *
	 * @since 1.0.0
	 *
	 * @param string             $id                 Unique identifier for the cookie integration.
	 * @param Cookie_Integration $cookie_integration Cookie_Integration instance.
	 *
	 * @throws Invalid_Identifier_Exception Thrown when the identifier is invalid.
	 * @throws Duplicate_Identifier_Exception Thrown when the identifier is already in use.
	 */
	public function register( string $id, Cookie_Integration $cookie_integration );

	/**
	 * Retrieves a registered cookie integration.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the cookie integration.
	 * @return Cookie_Integration Cookie integration instance.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the cookie integration for the identifier is not registered.
	 */
	public function get_registered( string $id ) : Cookie_Integration;

	/**
	 * Checks if a cookie integration is registered.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the cookie integration.
	 * @return bool True if the cookie integration is registered, false otherwise.
	 */
	public function is_registered( string $id ) : bool;

	/**
	 * Gets the registered cookie integrations.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $cookie_integration instance pairs.
	 */
	public function get_all_registered() : array;
}
