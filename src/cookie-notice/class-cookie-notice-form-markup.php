<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Form_Markup class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Renderable;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Parser;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Parser;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Class responsible for rendering cookie notice form markup.
 *
 * @since 1.0.0
 */
class Cookie_Notice_Form_Markup implements Renderable {

	/**
	 * Identifier for the 'submit_text' setting.
	 */
	const SETTING_SUBMIT_TEXT = 'submit_text';

	/**
	 * Identifier for the 'show_toggles' setting.
	 */
	const SETTING_SHOW_TOGGLES = 'show_toggles';

	/**
	 * Identifier for the 'show_learn_more' setting.
	 */
	const SETTING_SHOW_LEARN_MORE = 'show_learn_more';

	/**
	 * Identifier for the 'learn_more_text' setting.
	 */
	const SETTING_LEARN_MORE_TEXT = 'learn_more_text';

	/**
	 * Form action.
	 */
	const ACTION = 'wp_gdpr_cookie_notice_submit';

	/**
	 * Form nonce name.
	 */
	const NONCE_NAME = 'wp_gdpr_cookie_notice_nonce';

	/**
	 * Shortcode parser.
	 *
	 * @since 1.0.0
	 * @var Shortcode_Parser
	 */
	protected $shortcode_parser;

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
	 * Sets the shortcode parser and option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Shortcode_Parser $shortcode_parser Optional. Shortcode parser to use.
	 * @param Option_Reader    $options          Optional. Option reader to use.
	 */
	public function __construct( Shortcode_Parser $shortcode_parser = null, Option_Reader $options = null ) {
		if ( null === $shortcode_parser ) {
			$shortcode_parser = new WordPress_Shortcode_Parser();
		}

		if ( null === $options ) {
			$options = new Plugin_Option_Reader();
		}

		$this->shortcode_parser = $shortcode_parser;
		$this->options          = $options;
	}

	/**
	 * Renders the output.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		$this->render_opening_tag();

		?>
			<div class="wp-gdpr-cookie-notice-controls">
				<?php $this->render_controls(); ?>
			</div>
			<?php
			if ( ! $this->options->get_option( self::SETTING_SHOW_TOGGLES ) ) {
				$this->render_hidden_toggles();
			}
			?>
			<input type="hidden" name="action" value="<?php echo esc_attr( self::ACTION ); ?>">
			<?php wp_nonce_field( self::ACTION, self::NONCE_NAME, false ); ?>
		<?php

		$this->render_closing_tag();
	}

	/**
	 * Renders the notice controls.
	 *
	 * @since 1.0.0
	 */
	public function render_controls() {
		$options = $this->options->get_options();

		if ( ! empty( $options[ self::SETTING_SHOW_TOGGLES ] ) ) {
			$this->render_toggles();
		}

		if ( ! empty( $options[ self::SETTING_SHOW_LEARN_MORE ] ) ) {
			$this->render_learn_more_link( $options[ self::SETTING_LEARN_MORE_TEXT ] );
		}

		$this->render_submit_button( $options[ self::SETTING_SUBMIT_TEXT ] );
	}

	/**
	 * Renders the notice toggle checkboxes for granular cookie control.
	 *
	 * @since 1.0.0
	 */
	protected function render_toggles() {
		$cookie_type_labels = ( new Cookie_Type_Enum() )->get_labels();

		?>
		<fieldset class="wp-gdpr-cookie-notice-toggles">
			<legend class="screen-reader-text"><?php esc_html_e( 'Granular Cookie Control', 'wp-gdpr-cookie-notice' ); ?></legend>
			<?php
			$first = true;
			foreach ( $cookie_type_labels as $value => $label ) {
				$id = 'wp-gdpr-cookie-notice-toggle-' . $value;

				?>
				<div class="wp-gdpr-cookie-notice-toggle">
					<?php
					if ( $first ) {
						?>
						<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $value ); ?>" value="1" checked disabled>
						<?php

						$first = false;
					} else {
						?>
						<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $value ); ?>" value="1" checked>
						<?php
					}
					?>
					<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
				</div>
				<?php
			}
			?>
		</fieldset>
		<?php
	}

	/**
	 * Renders the notice toggles as hidden controls.
	 *
	 * @since 1.0.0
	 */
	protected function render_hidden_toggles() {
		$cookie_type_values = ( new Cookie_Type_Enum() )->get_values();

		foreach ( $cookie_type_values as $value ) {
			?>
			<input type="hidden" name="<?php echo esc_attr( $value ); ?>" value="1">
			<?php
		}
	}

	/**
	 * Renders the Learn More link for the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param string $link_text Text to use for the link anchor.
	 */
	protected function render_learn_more_link( string $link_text ) {
		$link = $this->shortcode_parser->parse_shortcodes( '[cookie_policy_link text="' . $link_text . '" show_if_empty="0"]', Cookie_Notice_Markup::CONTEXT );
		if ( empty( $link ) ) {
			return;
		}

		?>
		<div class="wp-gdpr-cookie-notice-learn-more">
			<?php echo $link; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
		</div>
		<?php
	}

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
			<button type="submit" class="wp-gdpr-cookie-notice-button"><?php echo esc_html( $button_text ); ?></button>
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
		<form id="wp-gdpr-cookie-notice-form" class="wp-gdpr-cookie-notice-form" method="POST">
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
