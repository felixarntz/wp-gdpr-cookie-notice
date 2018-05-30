<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Integration interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a class that integrates with WordPress via hooks.
 *
 * @since 1.0.0
 */
interface Integration {

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks();
}
