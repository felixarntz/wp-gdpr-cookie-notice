<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin_Cookie_Integrations class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Preferences;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Hooks\Hook_Factory;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\Cookie_Integration_Factory;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\WordPress_Cookie_Integration_Registry;

/**
 * Class for registering plugin cookie integrations.
 *
 * @since 1.0.0
 */
class Plugin_Cookie_Integrations implements Integration {

	/**
	 * Cookie preferences instance to use.
	 *
	 * @since 1.0.0
	 * @var Cookie_Preferences
	 */
	protected $preferences;

	/**
	 * Constructor.
	 *
	 * Sets the cookie preferences to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Cookie_Preferences $preferences Cookie preferences instance to use.
	 */
	public function __construct( Cookie_Preferences $preferences ) {
		$this->preferences = $preferences;
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		add_action( 'init', [ $this, 'register_cookie_integrations' ], 10, 0 );
	}

	/**
	 * Registers plugin cookie integrations.
	 *
	 * @since 1.0.0
	 */
	public function register_cookie_integrations() {
		$integration_registry = new WordPress_Cookie_Integration_Registry( $this->preferences );

		$integrations = $this->get_cookie_integrations();
		foreach ( $integrations as $integration ) {
			$integration_registry->register( $integration->get_id(), $integration );
		}

		/**
		 * Fires when the plugin's cookie integrations are registered.
		 *
		 * @since 1.0.0
		 *
		 * @param Cookie_Integration_Registry $integration_registry Cookie integration registry to register plugin cookie integrations with.
		 */
		do_action( 'wp_gdpr_cookie_notice_register_cookie_integrations', $integration_registry );
	}

	/**
	 * Gets the default cookie integrations to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of Cookie_Integration instances.
	 */
	protected function get_cookie_integrations() : array {
		$factory = new Cookie_Integration_Factory();

		// TODO: Add integrations.
		$integrations = [];

		return $integrations;
	}
}
