<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Form_AMP_Markup class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice;

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
		?>
		<div class="wp-gdpr-cookie-notice-submit">
			<button type="submit" class="wp-gdpr-cookie-notice-button" on="tap:wp-gdpr-cookie-notice-wrap.accept"><?php echo esc_html( $button_text ); ?></button>
		</div>
		<?php
	}

	/**
	 * Renders the opening tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_opening_tag() {
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
		?>
		</form>
		<?php
	}
}
