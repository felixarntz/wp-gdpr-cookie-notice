<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Enum interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for an enum class.
 *
 * @since 1.0.0
 */
interface Enum {

	/**
	 * Gets the possible values for the enum.
	 *
	 * @since 1.0.0
	 *
	 * @return array Possible enum values.
	 */
	public function get_values() : array;
}
