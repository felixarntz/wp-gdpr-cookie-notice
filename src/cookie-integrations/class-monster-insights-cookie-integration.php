<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\Monster_Insights_Cookie_Integration class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Class representing a cookie integration for the "Google Analytics by MonsterInsights" plugin.
 *
 * @since 1.0.0
 */
class Monster_Insights_Cookie_Integration implements Cookie_Integration {

	/**
	 * Gets the cookie integration identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie integration identifier.
	 */
	final public function get_id() : string {
		return 'monster_insights';
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
		return class_exists( 'Theme_Blvd_Monster_Insights' );
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
		add_action( 'monsterinsights_tracking_before_analytics', function() {
			$ua = monsterinsights_get_ua();
			if ( empty( $ua ) ) {
				return;
			}

			?>
			<script type="text/javascript">
				if ( window.wpGdprCookieNoticeUtils && ! window.wpGdprCookieNoticeUtils.cookiesAccepted( '<?php echo esc_attr( $this->get_type() ); ?>' ) ) {
					window['ga-disable-<?php echo esc_attr( $ua ); ?>'] = true;
				}
			</script>
			<?php
		}, 1, 0 );
	}
}
