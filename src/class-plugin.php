<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service_Container;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Data\WordPress_Option_Data_Repository;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Data\Cookie_Data_Repository;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Policy_Page;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Privacy_Policy_Page;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Preferences;

/**
 * Class controlling the plugin functionality.
 *
 * @since 1.0.0
 */
class Plugin implements Integration {

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

		$option_reader       = new Plugin_Option_Reader( new WordPress_Option_Data_Repository() );
		$cookie_policy_page  = new Cookie_Policy_Page( $option_reader );
		$privacy_policy_page = new Privacy_Policy_Page();
		$cookie_preferences  = new Cookie_Preferences( new Cookie_Data_Repository(), $cookie_policy_page, $privacy_policy_page );

		$this->container->add( 'options', $option_reader );
		$this->container->add( 'privacy_policy_page', $privacy_policy_page );
		$this->container->add( 'cookie_policy_page', $cookie_policy_page );
		$this->container->add( 'cookie_preferences', $cookie_preferences );
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		$option_reader = $this->container->get( 'options' );

		$integrations = [
			new Plugin_Settings( $option_reader ),
			new Plugin_Customizer( $option_reader ),
			new Plugin_Policies_Settings( $option_reader ),
		];

		array_walk( $integrations, function( Integration $integration ) {
			$integration->add_hooks();
		} );
	}

	/**
	 * Retrieves a plugin service.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the service.
	 * @return Service Service instance.
	 */
	public function get_service( string $id ) : Service {
		return $this->container->get( $id );
	}
}
