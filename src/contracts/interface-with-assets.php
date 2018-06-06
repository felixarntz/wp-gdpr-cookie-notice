<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\With_Assets interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a class that uses assets that need to be enqueued.
 *
 * @since 1.0.0
 */
interface With_Assets {

	/**
	 * Enqueues the necessary assets.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets();
}
