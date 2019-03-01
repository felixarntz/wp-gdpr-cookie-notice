<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Hooks\WordPress_Action_Hook class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Hooks;

/**
 * Class representing a WordPress action hook.
 *
 * @since 1.0.0
 */
class WordPress_Action_Hook extends Abstract_Hook {

	/**
	 * Adds the hook.
	 *
	 * @since 1.0.0
	 */
	final public function add() {
		add_action( $this->hook_name, $this->callback, $this->priority, $this->num_args );
	}

	/**
	 * Removes the hook.
	 *
	 * @since 1.0.0
	 */
	final public function remove() {
		remove_action( $this->hook_name, $this->callback, $this->priority );
	}
}
