<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

use WP_Customize_Control;

/**
 * Interface for a simplified Customizer class.
 *
 * @since 1.0.0
 */
interface Customizer {

	/**
	 * Adds a control to the Customizer..
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 */
	public function add_control( WP_Customize_Control $control );
}
