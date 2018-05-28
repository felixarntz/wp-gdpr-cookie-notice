<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\String_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings;

use WP_Error;

/**
 * Class representing a string setting.
 *
 * @since 1.0.0
 */
class String_Setting extends Abstract_Setting {

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

		if ( isset( $this->schema[ self::ARG_FORMAT ] ) ) {
			switch ( $this->schema[ self::ARG_FORMAT ] ) {
				case 'date-time':
					if ( ! rest_parse_date( $value ) ) {
						$validity->add( 'value_no_date', __( 'The value is not a valid date.', 'wp-gdpr-cookie-notice' ) );
					}
					break;
				case 'email':
					if ( ! is_email( $value ) ) {
						$validity->add( 'value_no_email', __( 'The value is not a valid email address.', 'wp-gdpr-cookie-notice' ) );
					}
					break;
				case 'ip':
					if ( ! rest_is_ip_address( $value ) ) {
						$validity->add( 'value_no_ip', __( 'The value is not a valid IP address.', 'wp-gdpr-cookie-notice' ) );
					}
					break;
			}
		}

		if ( isset( $this->schema[ self::ARG_ENUM ] )
			&& ! in_array( $value, array_map( 'strval', $this->schema[ self::ARG_ENUM ] ), true ) ) {
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
}
