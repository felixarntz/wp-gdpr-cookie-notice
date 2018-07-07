<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Hooks\Abstract_Hook class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Hooks;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Hook;

/**
 * Base class representing a hook.
 *
 * @since 1.0.0
 */
abstract class Abstract_Hook implements Hook {

	/**
	 * Hook name.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $hook_name;

	/**
	 * Hook callback.
	 *
	 * @since 1.0.0
	 * @var callable
	 */
	protected $callback = null;

	/**
	 * Hook priority.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	protected $priority = 10;

	/**
	 * Number of arguments to pass to the hook callback.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	protected $num_args = 1;

	/**
	 * Constructor.
	 *
	 * Sets the hook name, callback and arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $hook_name Hook name.
	 * @param callable $callback  Hook callback.
	 * @param array    $args      {
	 *     Optional. Hook arguments.
	 *
	 *     @type int $priority Hook priority. Default 10.
	 *     @type int $num_args Number of arguments to pass to the hook callback. Default 1.
	 * }
	 */
	public function __construct( string $hook_name, callable $callback, array $args = [] ) {
		$this->hook_name = $hook_name;
		$this->callback  = $callback;

		$arg_names = [
			Hook::ARG_PRIORITY,
			Hook::ARG_NUM_ARGS,
		];
		foreach ( $arg_names as $arg_name ) {
			if ( ! array_key_exists( $arg_name, $args ) ) {
				continue;
			}

			$this->$arg_name = $args[ $arg_name ];
		}
	}

	/**
	 * Adds the hook.
	 *
	 * @since 1.0.0
	 */
	abstract public function add();

	/**
	 * Removes the hook.
	 *
	 * @since 1.0.0
	 */
	abstract public function remove();
}
