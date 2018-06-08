<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Util\Minmax_Validatable trait
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Util;

use WP_Error;

/**
 * Trait for classes that need to validate their values against a minimum or maximum.
 *
 * @since 1.0.0
 */
trait Minmax_Validatable {

	/**
	 * Validates a value for the setting against the given minimum.
	 *
	 * @since 1.0.0
	 *
	 * @param int|float $min      Minimum value to validate against.
	 * @param WP_Error  $validity Error object to add validation errors to.
	 * @param mixed     $value    Value to validate.
	 */
	protected function validate_min( $min, WP_Error $validity, $value ) {
		if ( $value < $min ) {

			/* translators: %s: formatted numeric value */
			$validity->add( 'value_too_small', sprintf( __( 'The value must not be smaller than %s.', 'wp-gdpr-cookie-notice' ), number_format_i18n( $min ) ) );
		}
	}

	/**
	 * Validates a value for the setting against the given maximum.
	 *
	 * @since 1.0.0
	 *
	 * @param int|float $max      Maximum value to validate against.
	 * @param WP_Error  $validity Error object to add validation errors to.
	 * @param mixed     $value    Value to validate.
	 */
	protected function validate_max( $max, WP_Error $validity, $value ) {
		if ( $value > $max ) {

			/* translators: %s: formatted numeric value */
			$validity->add( 'value_too_great', sprintf( __( 'The value must not be greater than %s.', 'wp-gdpr-cookie-notice' ), number_format_i18n( $max ) ) );
		}
	}
}
