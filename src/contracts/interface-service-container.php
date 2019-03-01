<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Service_Container interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;

/**
 * Interface for a service container class.
 *
 * @since 1.0.0
 */
interface Service_Container {

	/**
	 * Adds a service.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $id      Unique identifier for the service.
	 * @param Service $service Service instance.
	 *
	 * @throws Invalid_Identifier_Exception Thrown when the identifier is invalid.
	 * @throws Duplicate_Identifier_Exception Thrown when the identifier is already in use.
	 */
	public function add( string $id, Service $service );

	/**
	 * Retrieves an available service.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the service.
	 * @return Service Service instance.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the service for the identifier is not registered.
	 */
	public function get( string $id ) : Service;

	/**
	 * Checks if a service is available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the service.
	 * @return bool True if the service is available, false otherwise.
	 */
	public function has( string $id ) : bool;

	/**
	 * Gets the available services.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $service instance pairs.
	 */
	public function get_all() : array;
}
