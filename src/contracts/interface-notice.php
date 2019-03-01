<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Notice interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a notice class.
 *
 * @since 1.0.0
 */
interface Notice extends Renderable {

	/**
	 * Checks whether the notice is active and should be rendered.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the notice is active, false otherwise.
	 */
	public function is_active() : bool;

	/**
	 * Dismisses the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Optional. Additional form data passed while dismissing. Default empty array.
	 */
	public function dismiss( array $form_data = [] );

	/**
	 * Restores the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Optional. Additional form data passed while dismissing. Default empty array.
	 */
	public function restore( array $form_data = [] );
}
