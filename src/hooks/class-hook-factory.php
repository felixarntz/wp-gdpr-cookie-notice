<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Hooks\Hook_Factory class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Hooks;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Hook;

/**
 * Class for instantiating hooks.
 *
 * @since 1.0.0
 */
class Hook_Factory {

	/**
	 * Instantiates a new hook.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $hook_name Hook name.
	 * @param callable $callback  Hook callback.
	 * @param array    $args      {
	 *     Optional. Hook arguments.
	 *
	 *     @type string $type     Hook type. Either 'action' or 'filter'. Default 'action'.
	 *     @type int    $priority Hook priority. Default 10.
	 *     @type int    $num_args Number of arguments to pass to the hook callback. Default 1.
	 * }
	 * @return Hook New hook instance.
	 */
	public function create( string $hook_name, callable $callback, array $args = [] ) : Hook {
		if ( ! empty( $args[ Hook::ARG_TYPE ] ) && 'filter' === $args[ Hook::ARG_TYPE ] ) {
			return new WordPress_Filter_Hook( $hook_name, $callback, $args );
		}

		return new WordPress_Action_Hook( $hook_name, $callback, $args );
	}
}
