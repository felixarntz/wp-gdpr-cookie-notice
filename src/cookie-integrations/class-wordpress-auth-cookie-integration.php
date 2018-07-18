<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\WordPress_Auth_Cookie_Integration class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Class representing a cookie integration for authentication cookies set by WordPress.
 *
 * @since 1.0.0
 */
class WordPress_Auth_Cookie_Integration implements Cookie_Integration {

	/**
	 * Gets the cookie integration identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie integration identifier.
	 */
	final public function get_id() : string {
		return 'wordpress_auth';
	}

	/**
	 * Gets the cookie type that the cookies managed by this integration are part of.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie type.
	 */
	final public function get_type() : string {
		return Cookie_Type_Enum::TYPE_FUNCTIONAL;
	}

	/**
	 * Checks whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_applicable() : bool {
		return true;
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
			add_filter( 'send_auth_cookies', '__return_false' );
			add_filter( 'wp_login_errors', function( $errors ) {
				$errors->add( 'cookies_not_allowed', __( 'You have to accept the cookie notice in order to be able to log in.', 'wp-gdpr-cookie-notice' ) );
				return $errors;
			} );
			add_action( 'login_footer', function() {
				?>
				<script type="text/javascript">
					if ( window.wpGdprCookieNoticeUtils ) {
						window.wpGdprCookieNoticeUtils.onAcceptCookies( function( utils ) {
							var loginError = document.querySelector( '#login_error' );
							if ( loginError && utils.cookiesAccepted( '<?php echo esc_attr( $this->get_type() ); ?>' ) ) {
								loginError.parentNode.removeChild( loginError );
							}
						});
					}
				</script>
				<?php
			});
		}
	}
}
