<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Form_Aware interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a class that contains a form.
 *
 * @since 1.0.0
 */
interface Form_Aware {

	/**
	 * Gets the form instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Form Form instance.
	 */
	public function get_form() : Form;
}
