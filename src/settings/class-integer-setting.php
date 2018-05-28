<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Integer_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings;

use WP_Error;

/**
 * Class representing an integer setting.
 *
 * @since 1.0.0
 */
class Integer_Setting extends Abstract_Setting {

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
		$value = (int) $value;

		if ( isset( $this->schema[ self::ARG_MINIMUM ] )
			&& $value < (int) $this->schema[ self::ARG_MINIMUM ] ) {
			$validity->add( 'value_too_small', sprintf( __( 'The value must not be smaller than %s.', 'wp-gdpr-cookie-notice' ), number_format_i18n( (int) $this->schema[ self::ARG_MINIMUM ] ) ) );
		}

		if ( isset( $this->schema[ self::ARG_MAXIMUM ] )
			&& $value > (int) $this->schema[ self::ARG_MAXIMUM ] ) {
			$validity->add( 'value_too_great', sprintf( __( 'The value must not be greater than %s.', 'wp-gdpr-cookie-notice' ), number_format_i18n( (int) $this->schema[ self::ARG_MAXIMUM ] ) ) );
		}

		if ( isset( $this->schema[ self::ARG_ENUM ] )
			&& ! in_array( $value, array_map( 'intval', $this->schema[ self::ARG_ENUM ] ), true ) ) {
			$validity->add( 'value_not_supported', __( 'The value is not supported.', 'wp-gdpr-cookie-notice' ) );
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
		return (int) $value;
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
		return (int) $value;
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
			$schema[ self::ARG_TYPE ] = 'integer';
		}

		if ( ! isset( $schema[ self::ARG_DEFAULT ] ) ) {
			$schema[ self::ARG_DEFAULT ] = 0;
		}

		return $schema;
	}
}
