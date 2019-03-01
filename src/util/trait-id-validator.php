<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Util\ID_Validator trait
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Util;

/**
 * Trait for validating identifiers.
 *
 * @since 1.0.0
 */
trait ID_Validator {

	/**
	 * Checks whether an identifier is valid.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Identifier to check.
	 * @return bool True if valid, false otherwise.
	 */
	protected function is_valid_id( string $id ) : bool {
		return preg_match( '/^[a-z0-9_]+$/', $id );
	}
}
