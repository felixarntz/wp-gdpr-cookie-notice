<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Markup class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Renderable;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Form;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Parser;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Parser;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;

/**
 * Class responsible for rendering cookie notice markup.
 *
 * @since 1.0.0
 */
class Cookie_Notice_Markup implements Renderable {

	/**
	 * Identifier for the 'notice_heading' setting.
	 */
	const SETTING_NOTICE_HEADING = 'notice_heading';

	/**
	 * Identifier for the 'notice_content' setting.
	 */
	const SETTING_NOTICE_CONTENT = 'notice_content';

	/**
	 * Context for the cookie notice content.
	 */
	const CONTEXT = 'cookie_notice';

	/**
	 * Cookie notice form.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Form
	 */
	protected $form;

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
	 * @param Form             $form             Form to render as part of the notice markup.
	 * @param Shortcode_Parser $shortcode_parser Optional. Shortcode parser to use.
	 * @param Option_Reader    $options          Optional. Option reader to use.
	 */
	public function __construct( Form $form, Shortcode_Parser $shortcode_parser = null, Option_Reader $options = null ) {
		if ( null === $shortcode_parser ) {
			$shortcode_parser = new WordPress_Shortcode_Parser();
		}

		if ( null === $options ) {
			$options = new Plugin_Option_Reader();
		}

		$this->form             = $form;
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
			<div id="wp-gdpr-cookie-notice" class="wp-gdpr-cookie-notice" role="alert" aria-label="<?php esc_attr_e( 'Cookie Consent Notice', 'wp-gdpr-cookie-notice' ); ?>">
				<div class="wp-gdpr-cookie-notice-inner">
					<div class="wp-gdpr-cookie-notice-content-wrap">
						<div class="wp-gdpr-cookie-notice-heading">
							<?php $this->render_heading(); ?>
						</div>
						<div class="wp-gdpr-cookie-notice-content">
							<?php $this->render_content(); ?>
						</div>
					</div>
					<?php $this->form->render(); ?>
				</div>
			</div>
		<?php

		$this->render_closing_tag();
	}

	/**
	 * Renders the notice heading.
	 *
	 * @since 1.0.0
	 */
	public function render_heading() {
		$heading = $this->get_heading();

		if ( empty( $heading ) ) {
			return;
		}

		echo $this->prepare_heading( $heading ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Renders the notice content.
	 *
	 * @since 1.0.0
	 */
	public function render_content() {
		$content = $this->get_content();

		if ( empty( $content ) ) {
			return;
		}

		echo $this->prepare_content( $content ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Renders the opening tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_opening_tag() {
		?>
		<div id="wp-gdpr-cookie-notice-wrap" class="wp-gdpr-cookie-notice-wrap">
		<?php
	}

	/**
	 * Renders the closing tag.
	 *
	 * @since 1.0.0
	 */
	protected function render_closing_tag() {
		?>
		</div>
		<?php
	}

	/**
	 * Gets the notice heading text.
	 *
	 * @since 1.0.0
	 *
	 * @return string Notice heading.
	 */
	protected function get_heading() {
		return $this->options->get_option( self::SETTING_NOTICE_HEADING );
	}

	/**
	 * Gets the notice content text.
	 *
	 * @since 1.0.0
	 *
	 * @return string Notice content.
	 */
	protected function get_content() {
		return $this->options->get_option( self::SETTING_NOTICE_CONTENT );
	}

	/**
	 * Prepares the notice heading for output.
	 *
	 * @since 1.0.0
	 *
	 * @param string $heading Notice heading.
	 * @return string Prepared notice heading.
	 */
	protected function prepare_heading( string $heading ) : string {

		/**
		 * Filters the heading level to use for the cookie notice heading.
		 *
		 * @since 1.0.0
		 *
		 * @param string $heading_level Must be one of 'h1', 'h2', 'h3', 'h4', 'h5', or 'h6'.
		 *                              Default is 'h2'.
		 */
		$heading_level = strtolower( apply_filters( 'wp_gdpr_cookie_notice_heading_level', 'h2' ) );
		if ( ! in_array( $heading_level, [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ], true ) ) {
			$heading_level = 'h2';
		}

		return '<' . $heading_level . '>' . esc_html( $heading ) . '</' . $heading_level . '>';
	}

	/**
	 * Prepares the notice content for output.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Notice content.
	 * @return string Prepared notice content.
	 */
	protected function prepare_content( string $content ) : string {
		$content = wp_kses( $content, self::CONTEXT );
		$content = $this->shortcode_parser->parse_shortcodes( $content, self::CONTEXT );
		$content = wpautop( $content );

		return $content;
	}
}
