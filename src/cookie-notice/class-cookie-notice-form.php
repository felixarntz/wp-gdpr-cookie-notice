<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Form class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Notice;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Form;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Parser;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Renderable;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\Is_AMP;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Notice_Submission_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Parser;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Class representing a cookie notice form.
 *
 * @since 1.0.0
 */
class Cookie_Notice_Form implements Form {

	use Is_AMP;

	/**
	 * Notice the form is part of.
	 *
	 * @since 1.0.0
	 * @var Notice
	 */
	protected $notice;

	/**
	 * Cookie notice form markup.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Form_Markup
	 */
	protected $markup;

	/**
	 * Cookie notice form markup in AMP.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Form_AMP_Markup
	 */
	protected $amp_markup;

	/**
	 * Constructor.
	 *
	 * Sets the notice, shortcode parser and option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Notice           $notice           Cookie notice instance.
	 * @param Shortcode_Parser $shortcode_parser Optional. Shortcode parser to use.
	 * @param Option_Reader    $options          Optional. Option reader to use.
	 */
	public function __construct( Notice $notice, Shortcode_Parser $shortcode_parser = null, Option_Reader $options = null ) {
		if ( null === $shortcode_parser ) {
			$shortcode_parser = new WordPress_Shortcode_Parser();
		}

		if ( null === $options ) {
			$options = new Plugin_Option_Reader();
		}

		$this->notice     = $notice;
		$this->markup     = new Cookie_Notice_Form_Markup( $shortcode_parser, $options );
		$this->amp_markup = new Cookie_Notice_Form_AMP_Markup( $shortcode_parser, $options );
	}

	/**
	 * Renders the output.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		$this->get_markup()->render();
	}

	/**
	 * Handles a form submission.
	 *
	 * @since 1.0.0
	 *
	 * @return array Form submission result data.
	 *
	 * @throws Notice_Submission_Exception Thrown when the submission is invalid.
	 */
	public function handle_submission() : array {
		if ( ! wp_verify_nonce( filter_input( INPUT_POST, Cookie_Notice_Form_Markup::NONCE_NAME, FILTER_SANITIZE_STRING ), Cookie_Notice_Form_Markup::ACTION ) ) {
			throw new Notice_Submission_Exception( __( 'The form submission is invalid.', 'wp-gdpr-cookie-notice' ) );
		}

		$cookie_type_values = ( new Cookie_Type_Enum() )->get_values();

		$form_data = [];

		$first = true;
		foreach ( $cookie_type_values as $value ) {
			if ( $first ) {
				$form_data[ $value ] = true;
				$first               = false;

				continue;
			}

			$form_data[ $value ] = filter_input( INPUT_POST, $value, FILTER_VALIDATE_BOOLEAN );
		}

		$this->notice->dismiss( $form_data );

		return [
			'message' => __( 'Cookie preferences set.', 'wp-gdpr-cookie-notice' ),
		];
	}

	/**
	 * Checks if the current request is a form submission.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if a submission, false otherwise.
	 */
	public function is_submission() : bool {
		return Cookie_Notice_Form_Markup::ACTION === filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
	}

	/**
	 * Renders the notice controls.
	 *
	 * @since 1.0.0
	 */
	public function render_controls() {
		$this->get_markup()->render_controls();
	}

	/**
	 * Gets the notice markup instance to use.
	 *
	 * @since 1.0.0
	 *
	 * @return Renderable Markup instance.
	 */
	protected function get_markup() : Renderable {
		if ( $this->is_amp() ) {
			return $this->amp_markup;
		}

		return $this->markup;
	}
}
