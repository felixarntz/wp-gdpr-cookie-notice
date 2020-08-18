<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Script_Utils class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Inline_Asset;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Preferences;

/**
 * Class representing a cookie notice utilities script.
 *
 * @since 1.0.0
 */
class Cookie_Notice_Script_Utils implements Inline_Asset {

	/**
	 * ID attribute for the script.
	 */
	const ID_ATTR = 'wp-gdpr-cookie-notice-script-utils';

	/**
	 * Cookie preferences.
	 *
	 * @since 1.0.0
	 * @var Cookie_Preferences
	 */
	protected $preferences;

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
	 * Gets the ID attribute used for the asset.
	 *
	 * @since 1.0.0
	 *
	 * @return string ID attribute.
	 */
	public function get_id() : string {
		return self::ID_ATTR;
	}

	/**
	 * Prints the full asset including the wrapping tags.
	 *
	 * @since 1.0.0
	 */
	public function print() {
		?>
		<script id="<?php echo esc_attr( self::ID_ATTR ); ?>" type="text/javascript">
			<?php $this->print_content(); ?>
		</script>
		<?php
	}

	/**
	 * Prints the actual asset content.
	 *
	 * @since 1.0.0
	 */
	public function print_content() {
		?>
		( function( exports ) {
			function isGoogleBot() {
				return navigator.userAgent && (
					-1 !== navigator.userAgent.indexOf( 'Googlebot' ) ||
					-1 !== navigator.userAgent.indexOf( 'Speed Insights' ) ||
					-1 !== navigator.userAgent.indexOf( 'Chrome-Lighthouse' )
				);
			}

			function cookiesAccepted( cookieType ) {
				var cookieParts = ( '; ' + document.cookie ).split( '; wp_gdpr_cookie_preferences=' );
				var cookie      = 2 === cookieParts.length ? cookieParts.pop().split( ';' ).shift() : '';

				cookieType = cookieType || 'functional';

				if ( ! cookie.length ) {
					return false;
				}

				try {
					cookie = JSON.parse( decodeURIComponent( cookie ) );
				} catch ( error ) {
					return false;
				}

				if ( ! cookie.last_modified || cookie.last_modified < <?php echo (int) $this->preferences->get_reference_timestamp(); ?>) {
					return false;
				}

				if ( ! cookie[ cookieType ] ) {
					return false;
				}

				return true;
			}

			function onAcceptCookies( callback ) {
				document.addEventListener( 'wpGdprCookieNotice.acceptCookies', function() {
					callback( exports.wpGdprCookieNoticeUtils );
				});
			}

			function isNoticeActive() {
				if ( cookiesAccepted() ) {
					return false;
				}

				if ( isGoogleBot() ) {
					return false;
				}

				return true;
			}

			exports.wpGdprCookieNoticeUtils = {
				cookiesAccepted: cookiesAccepted,
				onAcceptCookies: onAcceptCookies,
				isNoticeActive: isNoticeActive
			};
		})( window );
		<?php
	}
}
