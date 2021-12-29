<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Form_AMP_Markup class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

/**
 * Class responsible for rendering cookie notice form markup in AMP.
 *
 * @since 1.0.0
 */
class Cookie_Notice_Form_AMP_Markup extends Cookie_Notice_Form_Markup {

	/**
	 * Renders the submit button for the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param string $button_text Text to use for the button.
	 */
	protected function render_submit_button( string $button_text ) {
		// There is only a form if the submission endpoint should be used in AMP.
		$button_type = $this->should_use_amp_endpoint() ? 'submit' : 'button';
		?>
		<div class="wp-gdpr-cookie-notice-submit">
			<button type="<?php echo esc_attr( $button_type ); ?>" class="wp-gdpr-cookie-notice-button" on="tap:wp-gdpr-cookie-notice-wrap.accept"><?php echo esc_html( $button_text ); ?></button>
		</div>
		<?php
	}

	/**
	 * Renders the opening tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_opening_tag() {
		// Do not render form tag if the submission endpoint should not be used in AMP.
		if ( ! $this->should_use_amp_endpoint() ) {
			return;
		}
		?>
		<form id="wp-gdpr-cookie-notice-form" class="wp-gdpr-cookie-notice-form" method="POST" action-xhr="<?php echo esc_url( add_query_arg( '_wp_amp_action_xhr_converted', '1', admin_url( 'admin-ajax.php' ) ) ); ?>">
		<?php
	}

	/**
	 * Renders the closing tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_closing_tag() {
		// Do not render form tag if the submission endpoint should not be used in AMP.
		if ( ! $this->should_use_amp_endpoint() ) {
			return;
		}
		?>
		</form>
		<?php
	}

	/**
	 * Returns whether to use the AJAX endpoint in AMP for submitting the cookie preferences.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the endpoint should be used. False if local storage should be used.
	 */
	protected function should_use_amp_endpoint() : bool {
		/**
		 * Filters whether to use the AJAX endpoint in AMP for submitting the cookie preferences
		 * or whether to exclusively use local storage for that.
		 *
		 * Note that the granular cookie consent per cookie type is not supported without using the endpoint.
		 *
		 * @since 1.0.0
		 *
		 * @param bool True if the endpoint should be used. False if local storage should be used.
		 */
		return (bool) apply_filters( 'wp_gdpr_cookie_notice_amp_use_submission_endpoint', true );
	}
}
