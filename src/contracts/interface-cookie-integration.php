<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a class that controls a cookie or set of cookies.
 *
 * @since 1.0.0
 */
interface Cookie_Integration extends Integration {

	/**
	 * Checks whether the cookie or cookies controlled are allowed.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if allowed, false otherwise.
	 */
	public function is_allowed() : bool;

	/**
	 * Checks whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_applicable() : bool;
}
