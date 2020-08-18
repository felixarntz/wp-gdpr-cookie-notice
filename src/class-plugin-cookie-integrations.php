<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Plugin_Cookie_Integrations class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration_Registry;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\WordPress_Cookie_Integration_Registry;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\AMP_Block_On_Consent_Cookie_Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\Jetpack_Stats_Cookie_Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\Monster_Insights_Cookie_Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\Simple_Analytics_Cookie_Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\Site_Kit_Cookie_Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\WordPress_Auth_Cookie_Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\WordPress_Comments_Cookie_Integration;

/**
 * Class for registering plugin cookie integrations.
 *
 * @since 1.0.0
 */
class Plugin_Cookie_Integrations implements Integration {

	/**
	 * Cookie integration registry to use.
	 *
	 * @since 1.0.0
	 * @var Cookie_Integration_Registry
	 */
	protected $cookie_integrations;

	/**
	 * Constructor.
	 *
	 * Sets the cookie preferences to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Cookie_Integration_Registry $cookie_integrations Cookie integration registry to use.
	 */
	public function __construct( Cookie_Integration_Registry $cookie_integrations ) {
		$this->cookie_integrations = $cookie_integrations;
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
		$integration_registry = $this->cookie_integrations;

		$integrations = $this->get_default_cookie_integrations();
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
	protected function get_default_cookie_integrations() : array {
		$integrations = [
			new AMP_Block_On_Consent_Cookie_Integration(),
			new Jetpack_Stats_Cookie_Integration(),
			new Monster_Insights_Cookie_Integration(),
			new Simple_Analytics_Cookie_Integration(),
			new Site_Kit_Cookie_Integration(),
			new WordPress_Auth_Cookie_Integration(),
			new WordPress_Comments_Cookie_Integration(),
		];

		return $integrations;
	}
}
