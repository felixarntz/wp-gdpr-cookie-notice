<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer\Textarea_Customizer_Control class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer;

/**
 * Class representing a textarea Customizer control.
 *
 * @since 1.0.0
 */
class Textarea_Customizer_Control extends Abstract_Customizer_Control {

	/**
	 * Maps control arguments prior to passing them to a core `WP_Customize_Control` instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Control arguments.
	 * @return array Mapped control arguments.
	 */
	protected function map_args( array $args ) : array {
		$args         = parent::map_args( $args );
		$args['type'] = 'textarea';

		return $args;
	}
}
