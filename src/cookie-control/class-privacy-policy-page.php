<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Privacy_Policy_Page class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;

/**
 * Class representing the privacy policy page.
 *
 * @since 1.0.0
 */
class Privacy_Policy_Page extends Abstract_Page implements Service {

	/**
	 * Gets the ID of the page.
	 *
	 * @since 1.0.0
	 *
	 * @return int Page ID.
	 */
	public function get_id() : int {
		return (int) get_option( 'wp_page_for_privacy_policy' );
	}
}
