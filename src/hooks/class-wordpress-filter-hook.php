<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Hooks\WordPress_Filter_Hook class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Hooks;

/**
 * Class representing a WordPress filter hook.
 *
 * @since 1.0.0
 */
class WordPress_Filter_Hook extends Abstract_Hook {

	/**
	 * Adds the hook.
	 *
	 * @since 1.0.0
	 */
	final public function add() {
		add_filter( $this->hook_name, $this->callback, $this->priority, $this->num_args );
	}

	/**
	 * Removes the hook.
	 *
	 * @since 1.0.0
	 */
	final public function remove() {
		remove_filter( $this->hook_name, $this->callback, $this->priority );
	}
}
