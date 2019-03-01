<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Cookie_Type_Exception class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions;

/**
 * Thrown when a cookie type is invalid.
 *
 * @since 1.0.0
 */
class Invalid_Cookie_Type_Exception extends Exception {

	/**
	 * Returns a new exception instance from a given cookie type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Cookie type for which the exception should be returned.
	 * @return Invalid_Cookie_Type_Exception New exception instance.
	 */
	public static function from_type( string $type ) : self {

		/* translators: %s: cookie type */
		return new self( __( 'The cookie type %s is invalid.', 'wp-gdpr-cookie-notice' ), $type );
	}
}
