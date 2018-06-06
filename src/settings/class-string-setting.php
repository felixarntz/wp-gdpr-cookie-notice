<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\String_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Util\Enum_Validatable;
use WP_Error;

/**
 * Class representing a string setting.
 *
 * @since 1.0.0
 */
class String_Setting extends Abstract_Setting {

	use Enum_Validatable;

	/**
	 * Performs default validation for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $validity Error object to add validation errors to.
	 * @param mixed    $value    Value to validate.
	 * @return WP_Error Error object to add possible errors to.
	 */
	protected function default_validation_callback( WP_Error $validity, $value ) {
		$value = (string) $value;

		if ( ! empty( $this->schema[ self::ARG_FORMAT ] ) ) {
			$this->validate_format( $this->schema[ self::ARG_FORMAT ], $validity, $value );
		}

		if ( isset( $this->schema[ self::ARG_ENUM ] ) ) {
			$this->validate_enum( array_map( 'strval', $this->schema[ self::ARG_ENUM ] ), $validity, $value );
		}

		return $validity;
	}

	/**
	 * Performs default sanitization for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed Sanitized value.
	 */
	protected function default_sanitization_callback( $value ) {
		return (string) $value;
	}

	/**
	 * Performs default parsing for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to parse.
	 * @return mixed Parsed value.
	 */
	protected function default_parsing_callback( $value ) {
		return (string) $value;
	}

	/**
	 * Prepares the setting schema.
	 *
	 * @since 1.0.0
	 *
	 * @param array $schema Setting schema.
	 * @return array Prepared setting schema.
	 */
	protected function prepare_schema( array $schema ) : array {
		if ( empty( $schema[ self::ARG_TYPE ] ) ) {
			$schema[ self::ARG_TYPE ] = 'string';
		}

		if ( ! isset( $schema[ self::ARG_DEFAULT ] ) ) {
			$schema[ self::ARG_DEFAULT ] = '';
		}

		return $schema;
	}

	/**
	 * Validates a value for the setting against the given format.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $format   Format to validate against. Either 'date-time', 'email', or 'ip'.
	 * @param WP_Error $validity Error object to add validation errors to.
	 * @param mixed    $value    Value to validate.
	 */
	protected function validate_format( string $format, WP_Error $validity, $value ) {
		$formats = [
			'date-time' => [
				'callback'      => 'rest_parse_date',
				'error_code'    => 'value_no_date',
				'error_message' => __( 'The value is not a valid date.', 'wp-gdpr-cookie-notice' ),
			],
			'email'    => [
				'callback'      => 'is_email',
				'error_code'    => 'value_no_email',
				'error_message' => __( 'The value is not a valid email address.', 'wp-gdpr-cookie-notice' ),
			],
			'ip'       => [
				'callback'      => 'rest_is_ip_address',
				'error_code'    => 'value_no_ip',
				'error_message' => __( 'The value is not a valid IP address.', 'wp-gdpr-cookie-notice' ),
			],
		];

		if ( ! isset( $formats[ $format ] ) ) {
			return;
		}

		if ( ! call_user_func( $formats[ $format ]['callback'] ) ) {
			$validity->add( $formats[ $format ]['error_code'], $formats[ $format ]['error_message'] );
		}
	}
}
