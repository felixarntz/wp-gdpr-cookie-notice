<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\Simple_Analytics_Cookie_Integration class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Class representing a cookie integration for the "Simple Analytics" plugin.
 *
 * @since 1.0.0
 */
class Simple_Analytics_Cookie_Integration implements Cookie_Integration {

	/**
	 * Gets the cookie integration identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie integration identifier.
	 */
	final public function get_id() : string {
		return 'simple_analytics';
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
		return class_exists( 'Theme_Blvd_Simple_Analytics' );
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $allowed Whether cookies for the cookie type are currently allowed.
	 */
	public function add_hooks( bool $allowed ) {
		if ( $allowed ) {
			return;
		}

		add_action( 'wp_head', function() {
			$options  = get_option( 'themeblvd_analytics', [] );
			if ( empty( $options['google_id'] ) ) {
				return;
			}

			?>
			<script type="text/javascript">
				window['ga-disable-<?php echo esc_attr( $options['google_id'] ); ?>'] = true;
			</script>
			<?php
		}, 1, 0 );
	}
}
