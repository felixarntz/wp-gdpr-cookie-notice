<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\Jetpack_Stats_Cookie_Integration class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Class representing a cookie integration for the stats module of the "Jetpack" plugin.
 *
 * @since 1.0.0
 */
class Jetpack_Stats_Cookie_Integration implements Cookie_Integration {

	/**
	 * Gets the cookie integration identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie integration identifier.
	 */
	final public function get_id() : string {
		return 'jetpack_stats';
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
	 * Checks whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_applicable() : bool {
		return defined( 'JETPACK__VERSION' );
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
		if ( ! $allowed ) {
			remove_action( 'template_redirect', 'stats_template_redirect', 1 );
			remove_action( 'wp_footer', 'stats_footer', 101 );
			remove_action( 'wp_head', 'stats_add_shutdown_action' );
		}
	}
}
