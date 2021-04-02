<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\Monster_Insights_Cookie_Integration class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\Is_AMP;

/**
 * Class representing a cookie integration for the "Google Analytics by MonsterInsights" plugin.
 *
 * @since 1.0.0
 */
class Monster_Insights_Cookie_Integration implements Cookie_Integration {
	use Is_AMP;

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
	 * Gets the label to display alongside the checkbox for enabling this integration.
	 *
	 * @since 1.0.0
	 *
	 * @return string Enable checkbox label.
	 */
	public function get_enable_label() : string {
		return __( 'Block Google Analytics (MonsterInsights plugin)?', 'wp-gdpr-cookie-notice' );
	}

	/**
	 * Checks whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_applicable() : bool {
		return function_exists( 'MonsterInsights' );
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
		add_action(
			'monsterinsights_tracking_before_analytics',
			function() {
				// For AMP, this is handled by the AMP_Block_On_Consent_Cookie_Integration class.
				if ( $this->is_amp() ) {
					return;
				}

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
			},
			1,
			0
		);
	}
}
