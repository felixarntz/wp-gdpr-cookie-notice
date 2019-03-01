<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions;

/**
 * Thrown when an identifier is already in use.
 *
 * @since 1.0.0
 */
class Duplicate_Identifier_Exception extends Exception {

	/**
	 * Returns a new exception instance from a given identifier.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Identifier for which the exception should be returned.
	 * @return Duplicate_Identifier_Exception New exception instance.
	 */
	public static function from_id( string $id ) : self {

		/* translators: %s: string identifier */
		return new self( __( 'The identifier %s is already in use.', 'wp-gdpr-cookie-notice' ), $id );
	}
}
