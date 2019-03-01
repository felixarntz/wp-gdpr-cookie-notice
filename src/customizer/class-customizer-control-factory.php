<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer\Customizer_Control_Factory class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Customizer_Control;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Type_Exception;

/**
 * Class for instantiating Customizer controls.
 *
 * @since 1.0.0
 */
class Customizer_Control_Factory {

	/**
	 * Instantiates a new Customizer control.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Customizer control identifier.
	 * @param array  $args {
	 *     Optional. Customizer control arguments.
	 *
	 *     @type string $type        Control type. Either 'text', 'number', 'textarea',
	 *                               'select', 'radio', 'checkbox', 'dropdown-pages',
	 *                               'color', or 'media'. Default 'text'.
	 *     @type string $label       Control label.
	 *     @type string $description Control description.
	 *     @type string $capability  Control capability.
	 *     @type int    $priority    Control priority.
	 *     @type string $section     Control parent section.
	 *     @type array  $choices     Control choices to select from.
	 *     @type array  $input_attrs Additional arbitrary input attributes for the control.
	 * }
	 * @return Customizer_Control New Customizer control instance.
	 *
	 * @throws Invalid_Type_Exception Thrown when the type is invalid.
	 */
	public function create( string $id, array $args = [] ) : Customizer_Control {
		if ( empty( $args[ Customizer_Control::ARG_TYPE ] ) ) {
			$args[ Customizer_Control::ARG_TYPE ] = 'text';
		}

		$type_map = [
			'text'           => Text_Customizer_Control::class,
			'number'         => Number_Customizer_Control::class,
			'textarea'       => Textarea_Customizer_Control::class,
			'select'         => Select_Customizer_Control::class,
			'radio'          => Radio_Customizer_Control::class,
			'checkbox'       => Checkbox_Customizer_Control::class,
			'dropdown-pages' => Dropdown_Pages_Customizer_Control::class,
			'color'          => Color_Customizer_Control::class,
			'media'          => Media_Customizer_Control::class,
		];

		if ( ! isset( $type_map[ $args[ Customizer_Control::ARG_TYPE ] ] ) ) {
			throw Invalid_Type_Exception::from_customizer_control_type( $args[ Customizer_Control::ARG_TYPE ] );
		}

		$classname = $type_map[ $args[ Customizer_Control::ARG_TYPE ] ];

		return new $classname( $id, $args );
	}
}
