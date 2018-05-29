<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Initializable;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service_Container;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Settings;

/**
 * Class controlling the plugin functionality.
 *
 * @since 1.0.0
 */
class Plugin implements Initializable, Service_Container {

	/**
	 * Plugin main file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $main_file;

	/**
	 * Container for plugin's services.
	 *
	 * @since 1.0.0
	 * @var Service_Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * Sets the plugin service container.
	 *
	 * @since 1.0.0
	 *
	 * @param string            $main_file Plugin main file.
	 * @param Service_Container $container Optional. Service container to use. Default is the regular
	 *                                     plugin service container.
	 */
	public function __construct( string $main_file, Service_Container $container = null ) {
		if ( null === $container ) {
			$container = new Plugin_Service_Container();
		}

		$this->main_file = $main_file;
		$this->container = $container;

		$this->add( 'settings', new Plugin_Settings() );
	}

	/**
	 * Initializes the class functionality.
	 *
	 * @since 1.0.0
	 */
	public function initialize() {
		$services = $this->container->get_all();

		array_walk( $services, function( Service $service ) {
			$service->initialize();
		} );
	}

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
	public function add( string $id, Service $service ) {
		$this->container->add( $id, $service );
	}

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
	public function get( string $id ) : Service {
		return $this->container->get( $id );
	}

	/**
	 * Checks if a service is available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the service.
	 * @return bool True if the service is available, false otherwise.
	 */
	public function has( string $id ) : bool {
		return $this->container->has( $id );
	}

	/**
	 * Gets the available services.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $service instance pairs.
	 */
	public function get_all() : array {
		return $this->container->get_all();
	}
}
