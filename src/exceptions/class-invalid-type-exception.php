<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Type_Exception class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions;

/**
 * Thrown when a type is invalid.
 *
 * @since 1.0.0
 */
class Invalid_Type_Exception extends Exception {

	/**
	 * Returns a new exception instance from a given setting type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Type for which the exception should be returned.
	 * @return Invalid_Type_Exception New exception instance.
	 */
	public static function from_setting_type( string $type ) : self {

		/* translators: %s: setting type */
		return new self( __( 'The setting type %s is invalid.', 'wp-gdpr-cookie-notice' ), $type );
	}

	/**
	 * Returns a new exception instance from a given Customizer control type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Type for which the exception should be returned.
	 * @return Invalid_Type_Exception New exception instance.
	 */
	public static function from_customizer_control_type( string $type ) : self {

		/* translators: %s: Customizer control type */
		return new self( __( 'The Customizer control type %s is invalid.', 'wp-gdpr-cookie-notice' ), $type );
	}
}
