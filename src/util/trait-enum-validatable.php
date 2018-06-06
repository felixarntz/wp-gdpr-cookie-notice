<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Util\Enum_Validatable trait
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Util;

/**
 * Trait for classes that need to validate their values against an enum.
 *
 * @since 1.0.0
 */
trait Enum_Validatable {

	/**
	 * Validates a value for the setting against the given enum.
	 *
	 * @since 1.0.0
	 *
	 * @param array    $enum     Enum values to validate against.
	 * @param WP_Error $validity Error object to add validation errors to.
	 * @param mixed    $value    Value to validate.
	 */
	protected function validate_enum( array $enum, WP_Error $validity, $value ) {
		if ( ! in_array( $value, $enum, true ) ) {
			$validity->add( 'value_not_supported', __( 'The value is not supported.', 'wp-gdpr-cookie-notice' ) );
		}
	}
}
