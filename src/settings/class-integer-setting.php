<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Integer_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Settings;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\Minmax_Validatable;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\Enum_Validatable;
use WP_Error;

/**
 * Class representing an integer setting.
 *
 * @since 1.0.0
 */
class Integer_Setting extends Abstract_Setting {

	use Minmax_Validatable, Enum_Validatable;

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

		if ( isset( $this->schema[ self::ARG_MINIMUM ] ) ) {
			$this->validate_min( (int) $this->schema[ self::ARG_MINIMUM ], $validity, $value );
		}

		if ( isset( $this->schema[ self::ARG_MAXIMUM ] ) ) {
			$this->validate_max( (int) $this->schema[ self::ARG_MAXIMUM ], $validity, $value );
		}

		if ( isset( $this->schema[ self::ARG_ENUM ] ) ) {
			$this->validate_enum( array_map( 'intval', $this->schema[ self::ARG_ENUM ] ), $validity, $value );
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
