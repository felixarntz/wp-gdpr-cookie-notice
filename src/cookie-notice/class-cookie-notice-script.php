<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Script class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Inline_Asset;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;

/**
 * Class representing a cookie notice script.
 *
 * @since 1.0.0
 */
class Cookie_Notice_Script implements Inline_Asset {

	/**
	 * ID attribute for the script.
	 */
	const ID_ATTR = 'wp-gdpr-cookie-notice-script';

	/**
	 * Option reader.
	 *
	 * @since 1.0.0
	 * @var Option_Reader
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * Sets the option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Option_Reader $options Optional. Option reader to use.
	 */
	public function __construct( Option_Reader $options = null ) {
		if ( null === $options ) {
			$options = new Plugin_Option_Reader();
		}

		$this->options = $options;
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
		( function() {
			var noticeWrap = document.getElementById( 'wp-gdpr-cookie-notice-wrap' );
			var form       = document.getElementById( 'wp-gdpr-cookie-notice-form' );

			if ( ! noticeWrap || ! form || 'function' !== typeof window.fetch || 'function' !== typeof window.FormData ) {
				return;
			}

			function acceptCookies() {
				var event = document.createEvent( 'HTMLEvents' );

				event.initEvent( 'wpGdprCookieNotice.acceptCookies', true, true );
				document.dispatchEvent( event );
			}

			form.addEventListener( 'submit', function( event ) {
				event.preventDefault();

				window.fetch( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
					method: 'POST',
					mode: 'same-origin',
					credentials: 'same-origin',
					body: new window.FormData( form )
				})
					.then( function( response ) {
						var contentType = response.headers.get( 'content-type' );

						if ( ! contentType || ! contentType.includes( 'application/json' ) ) {
							throw new TypeError( '<?php esc_attr_e( 'Malformed response.', 'wp-gdpr-cookie-notice' ); ?>' );
						}

						return response.json().then( function( result ) {
							return response.ok ? result : Promise.reject( result );
						});
					})
					.then( function() {
						noticeWrap.parentNode.removeChild( noticeWrap );

						acceptCookies();
					})
					.catch( function( result ) {
						if ( ! result.data || ! result.data.message ) {
							console.error( '<?php esc_attr_e( 'Bad request.', 'wp-gdpr-cookie-notice' ); ?>' );
							return;
						}

						console.error( result.data.message );
					});

				return false;
			});
		})();
		<?php
	}
}
