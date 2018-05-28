<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin_Service_Container class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service_Container;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;

/**
 * Class for registering, retrieving and initializing plugin services.
 *
 * @since 1.0.0
 */
class Plugin_Service_Container implements Service_Container {

	/**
	 * Registered services.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $services = [];

	/**
	 * Registers a service.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $id      Unique identifier for the service.
	 * @param Service $service Service instance.
	 *
	 * @throws Invalid_Identifier_Exception Thrown when the identifier is invalid.
	 * @throws Duplicate_Identifier_Exception Thrown when the identifier is already in use.
	 */
	public function register_service( string $id, Service $service ) {
		if ( ! $this->is_valid_id( $id ) ) {
			throw Invalid_Identifier_Exception::from_id( $id );
		}

		if ( isset( $this->services[ $id ] ) ) {
			throw Duplicate_Identifier_Exception::from_id( $id );
		}

		$this->services[ $id ] = $service;
	}

	/**
	 * Retrieves a registered service.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the service.
	 * @return Service Service instance.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the service for the identifier is not registered.
	 */
	public function get_service( string $id ) : Service {
		if ( ! isset( $this->services[ $id ] ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		return $this->services[ $id ];
	}

	/**
	 * Gets the registered services.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $service instance pairs.
	 */
	public function get_services() : array {
		return $this->services;
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
