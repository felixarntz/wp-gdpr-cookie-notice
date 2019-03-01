<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Abstract_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Settings;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Setting;
use WP_Error;

/**
 * Base class representing a setting.
 *
 * @since 1.0.0
 */
abstract class Abstract_Setting implements Setting {

	/**
	 * Setting identifier.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $id;

	/**
	 * Setting schema.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $schema = [];

	/**
	 * Validation callback for the setting's value.
	 *
	 * @since 1.0.0
	 * @var callable
	 */
	protected $validate_callback;

	/**
	 * Sanitization callback for the setting's value in un-slashed form.
	 *
	 * @since 1.0.0
	 * @var callable
	 */
	protected $sanitize_callback;

	/**
	 * Parse callback for the setting's value coming from the database.
	 *
	 * @since 1.0.0
	 * @var callable
	 */
	protected $parse_callback;

	/**
	 * Constructor.
	 *
	 * Sets the setting identifier and arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Setting identifier.
	 * @param array  $args {
	 *     Optional. Setting arguments.
	 *
	 *     @type string    $description       Setting description.
	 *     @type mixed     $default           Setting default value.
	 *     @type array     $enum              Allowed setting enum values.
	 *     @type string    $format            Allowed setting value format.
	 *     @type int|float $minimum           Minimum allowed setting value.
	 *     @type int|float $maximum           Maximum allowed setting value.
	 *     @type array     $items             Items schema, if an array setting.
	 *     @type array     $properties        Properties schema, if an object setting.
	 *     @type callable  $validate_callback Setting validation callback.
	 *     @type callable  $sanitize_callback Setting sanitization callback.
	 *     @type callable  $parse_callback    Setting parse callback.
	 * }
	 */
	public function __construct( string $id, array $args = [] ) {
		$this->id = $id;

		$callback_args = [
			self::ARG_VALIDATE_CALLBACK,
			self::ARG_SANITIZE_CALLBACK,
			self::ARG_PARSE_CALLBACK,
		];
		foreach ( $callback_args as $callback_arg ) {
			if ( array_key_exists( $callback_arg, $args ) ) {
				$this->$callback_arg = $args[ $callback_arg ];
				unset( $args[ $callback_arg ] );
			}
		}

		$this->schema = $this->prepare_schema( $args );
	}

	/**
	 * Gets the setting identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Setting identifier.
	 */
	final public function get_id() : string {
		return $this->id;
	}

	/**
	 * Gets the schema that describes the setting.
	 *
	 * @since 1.0.0
	 *
	 * @return array Setting schema.
	 */
	final public function get_schema() : array {
		return $this->schema;
	}

	/**
	 * Validates a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $validity Error object to add validation errors to.
	 * @param mixed    $value    Value to validate.
	 * @return bool|WP_Error True on success, error object on failure.
	 */
	final public function validate_value( WP_Error $validity, $value ) {
		$validity = $this->default_validation_callback( $validity, $value );

		if ( null !== $this->validate_callback ) {
			$validity = call_user_func( $this->validate_callback, $validity, $value );
		}

		if ( is_wp_error( $validity ) ) {
			if ( empty( $validity->errors ) ) {
				$validity = true;
			}
			return $validity;
		}

		if ( ! $validity ) {
			return new WP_Error( 'invalid_value', __( 'Invalid value.', 'wp-gdpr-cookie-notice' ) );
		}

		return true;
	}

	/**
	 * Sanitizes a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed Sanitized value.
	 */
	final public function sanitize_value( $value ) {
		$value = $this->default_sanitization_callback( $value );

		if ( null !== $this->sanitize_callback ) {
			$value = call_user_func( $this->sanitize_callback, $value );
		}

		return $value;
	}

	/**
	 * Parses a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to parse.
	 * @return mixed Parsed value.
	 */
	final public function parse_value( $value ) {
		$value = $this->default_parsing_callback( $value );

		if ( null !== $this->parse_callback ) {
			$value = call_user_func( $this->parse_callback, $value );
		}

		return $value;
	}

	/**
	 * Performs default validation for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $validity Error object to add validation errors to.
	 * @param mixed    $value    Value to validate.
	 * @return WP_Error Error object to add possible errors to.
	 */
	abstract protected function default_validation_callback( WP_Error $validity, $value );

	/**
	 * Performs default sanitization for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed Sanitized value.
	 */
	abstract protected function default_sanitization_callback( $value );

	/**
	 * Performs default parsing for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to parse.
	 * @return mixed Parsed value.
	 */
	abstract protected function default_parsing_callback( $value );

	/**
	 * Prepares the setting schema.
	 *
	 * @since 1.0.0
	 *
	 * @param array $schema Setting schema.
	 * @return array Prepared setting schema.
	 */
	abstract protected function prepare_schema( array $schema ) : array;
}
