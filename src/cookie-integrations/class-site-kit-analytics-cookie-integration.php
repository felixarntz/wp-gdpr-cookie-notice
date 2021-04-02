<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\Site_Kit_Analytics_Cookie_Integration class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\Is_AMP;

/**
 * Class representing a cookie integration for Analytics in the "Site Kit by Google" plugin.
 *
 * @since 1.0.0
 */
class Site_Kit_Analytics_Cookie_Integration implements Cookie_Integration {
	use Is_AMP;

	/**
	 * Gets the cookie integration identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie integration identifier.
	 */
	final public function get_id() : string {
		return 'google_site_kit_analytics';
	}

	/**
	 * Gets the cookie type that the cookies managed by this integration are part of.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie type.
	 */
	final public function get_type() : string {
		return Cookie_Type_Enum::TYPE_ANALYTICS;
	}

	/**
	 * Gets the label to display alongside the checkbox for enabling this integration.
	 *
	 * @since 1.0.0
	 *
	 * @return string Enable checkbox label.
	 */
	public function get_enable_label() : string {
		return __( 'Block Google Analytics (Site Kit by Google plugin) from tracking visitors?', 'wp-gdpr-cookie-notice' );
	}

	/**
	 * Checks whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_applicable() : bool {
		// Cookie integration API was added to Site Kit in version 1.18.0.
		return defined( 'GOOGLESITEKIT_VERSION' ) && version_compare( GOOGLESITEKIT_VERSION, '1.18.0', '>=' );
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $allowed Whether cookies for the cookie type are currently allowed. Note that this value
	 *                      is not necessarily reliable since it is cookie-based and thus may be off in setups
	 *                      that leverage page caching. It is recommended to use a JS-only solution.
	 */
	public function add_hooks( bool $allowed ) {
		// For AMP: Adds the `data-block-on-consent` attribute so that the tag is loaded immediately once opted in.
		add_filter( 'googlesitekit_analytics_tag_amp_block_on_consent', '__return_true' );

		// For non-AMP: Blocks the tag completely on the server-side until opted in on next page load.
		if ( ! $allowed ) {
			add_filter( 'googlesitekit_analytics_tag_blocked', '__return_true' );
		}
	}
}
