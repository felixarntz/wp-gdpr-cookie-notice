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
	 * Identifier argument name.
	 */
	const ARG_ID = 'id';

	/**
	 * Type argument name.
	 */
	const ARG_TYPE = 'type';

	/**
	 * Applicable callback argument name.
	 */
	const ARG_APPLICABLE_CALLBACK = 'applicable_callback';

	/**
	 * Hooks to add argument name.
	 */
	const ARG_HOOKS_TO_ADD = 'hooks_to_add';

	/**
	 * Hooks to remove argument name.
	 */
	const ARG_HOOKS_TO_REMOVE = 'hooks_to_remove';

	/**
	 * Gets the cookie integration identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie integration identifier.
	 */
	public function get_id() : string;

	/**
	 * Gets the cookie type that the cookies managed by this integration are part of.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie type.
	 */
	public function get_type() : string;

	/**
	 * Checks whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_applicable() : bool;

	/**
	 * Removes the necessary hooks to integrate.
	 *
	 * This is executed if the cookies managed by this integration are not allowed.
	 *
	 * @since 1.0.0
	 */
	public function remove_hooks();
}