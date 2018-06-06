<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Assets_Aware interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a class that uses assets.
 *
 * @since 1.0.0
 */
interface Assets_Aware {

	/**
	 * Enqueues the necessary assets.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets();
}
