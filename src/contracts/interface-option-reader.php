<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Option_Reader interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a class to read options.
 *
 * @since 1.0.0
 */
interface Option_Reader {

	/**
	 * Gets a single option value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Option identifier.
	 * @return mixed Option value, or null if invalid option.
	 */
	public function get_option( string $id );

	/**
	 * Gets all option values.
	 *
	 * @since 1.0.0
	 *
	 * @return array Options as $option => $value pairs.
	 */
	public function get_options() : array;
}
