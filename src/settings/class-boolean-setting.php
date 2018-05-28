<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Boolean_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings;

use WP_Error;

/**
 * Class representing a boolean setting.
 *
 * @since 1.0.0
 */
class Boolean_Setting extends Abstract_Setting {

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
		return (bool) $value;
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
		if ( in_array( $value, array( 'false', 'FALSE', 'no', 'NO', '0' ), true ) ) {
			return false;
		}

		return (bool) $value;
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
			$schema[ self::ARG_TYPE ] = 'boolean';
		}

		if ( ! isset( $schema[ self::ARG_DEFAULT ] ) ) {
			$schema[ self::ARG_DEFAULT ] = false;
		}

		return $schema;
	}
}
