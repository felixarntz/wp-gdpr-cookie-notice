<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Hook interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a hook class.
 *
 * @since 1.0.0
 */
interface Hook {

	/**
	 * Hook name argument name.
	 */
	const ARG_HOOK_NAME = 'hook_name';

	/**
	 * Callback argument name.
	 */
	const ARG_CALLBACK = 'callback';

	/**
	 * Type argument name.
	 */
	const ARG_TYPE = 'type';

	/**
	 * Priority argument name.
	 */
	const ARG_PRIORITY = 'priority';

	/**
	 * Number of arguments argument name.
	 */
	const ARG_NUM_ARGS = 'num_args';

	/**
	 * Adds the hook.
	 *
	 * @since 1.0.0
	 */
	public function add();

	/**
	 * Removes the hook.
	 *
	 * @since 1.0.0
	 */
	public function remove();
}
