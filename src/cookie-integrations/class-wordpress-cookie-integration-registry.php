<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\WordPress_Cookie_Integration_Registry class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Preferences;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Util\ID_Validator;

/**
 * Class for registering cookie integrations in WordPress.
 *
 * @since 1.0.0
 */
class WordPress_Cookie_Integration_Registry implements Cookie_Integration_Registry {

	use ID_Validator;

	/**
	 * Cookie preferences.
	 *
	 * @since 1.0.0
	 * @var Cookie_Preferences
	 */
	protected $preferences;

	/**
	 * Registered cookie integrations.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $cookie_integrations = [];

	/**
	 * Constructor.
	 *
	 * Sets the preferences to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Cookie_Preferences $preferences Cookie preferences instance.
	 */
	public function __construct( Cookie_Preferences $preferences ) {
		$this->preferences = $preferences;
	}

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
	public function register( string $id, Cookie_Integration $cookie_integration ) {
		if ( ! $this->is_valid_id( $id ) ) {
			throw Invalid_Identifier_Exception::from_id( $id );
		}

		if ( isset( $this->cookie_integrations[ $id ] ) ) {
			throw Duplicate_Identifier_Exception::from_id( $id );
		}

		$this->cookie_integrations[ $id ] = $cookie_integration;

		add_action( 'init', function() use ( $cookie_integration ) {
			$this->add_integration_hooks( $cookie_integration );
		}, PHP_INT_MAX, 0 );
	}

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
	public function get_registered( string $id ) : Cookie_Integration {
		if ( ! isset( $this->cookie_integrations[ $id ] ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		return $this->cookie_integrations[ $id ];
	}

	/**
	 * Checks if a cookie integration is registered.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the cookie integration.
	 * @return bool True if the cookie integration is registered, false otherwise.
	 */
	public function is_registered( string $id ) : bool {
		return isset( $this->cookie_integrations[ $id ] );
	}

	/**
	 * Gets the registered cookie integrations.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $cookie_integration instance pairs.
	 */
	public function get_all_registered() : array {
		return $this->cookie_integrations;
	}

	/**
	 * Adds the necessary hooks for a cookie integration if applicable.
	 *
	 * @since 1.0.0
	 *
	 * @param Cookie_Integration $cookie_integration Cookie integration to add hooks for.
	 */
	protected function add_integration_hooks( Cookie_Integration $cookie_integration ) {
		if ( ! $cookie_integration->is_active() ) {
			return;
		}

		if ( ! $this->preferences->cookies_accepted( $cookie_integration->get_type() ) ) {
			add_action( 'wp_loaded', array( $cookie_integration, 'remove_hooks' ), PHP_INT_MAX, 0 );
			return;
		}

		$cookie_integration->add_hooks();
	}
}
