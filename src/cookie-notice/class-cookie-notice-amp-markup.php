<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_AMP_Markup class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

/**
 * Class responsible for rendering cookie notice markup in AMP.
 *
 * @since 1.0.0
 */
class Cookie_Notice_AMP_Markup extends Cookie_Notice_Markup {

	/**
	 * Instance name for the <amp-consent> used with AMP.
	 */
	const AMP_INSTANCE = 'wp-gdpr-cookie-notice';

	/**
	 * Action for the <amp-consent> checkConsentHref request.
	 */
	const AMP_CHECK_CONSENT_HREF_ACTION = 'wp_gdpr_cookie_notice_check_consent_href';

	/**
	 * Renders the opening tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_opening_tag() {
		?>
		<amp-consent id="wp-gdpr-cookie-notice-wrap" class="wp-gdpr-cookie-notice-wrap" layout="nodisplay">
			<script type="application/json">
				<?php echo wp_json_encode( $this->get_consent_data() ); ?>
			</script>
		<?php
	}

	/**
	 * Renders the closing tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_closing_tag() {
		?>
		</amp-consent>
		<?php
	}

	/**
	 * Gets data to add to `<amp-consent>` as JSON.
	 *
	 * @since 1.0.0
	 *
	 * @return array Data to pass to the `<amp-consent>` element.
	 */
	protected function get_consent_data() {
		return array(
			'consents' => array(
				self::AMP_INSTANCE => array(
					'checkConsentHref' => add_query_arg( 'action', self::AMP_CHECK_CONSENT_HREF_ACTION, admin_url( 'admin-ajax.php' ) ),
					'promptUI'         => 'wp-gdpr-cookie-notice',
				),
			),
		);
	}
}
