<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Control interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

use WP_Customize_Manager;
use WP_Customize_Control;

/**
 * Interface for a Customizer control class.
 *
 * @since 1.0.0
 */
interface Customizer_Control {

	/**
	 * Type argument name.
	 */
	const ARG_TYPE = 'type';

	/**
	 * Label argument name.
	 */
	const ARG_LABEL = 'label';

	/**
	 * Description argument name.
	 */
	const ARG_DESCRIPTION = 'description';

	/**
	 * Capability argument name.
	 */
	const ARG_CAPABILITY = 'capability';

	/**
	 * Priority argument name.
	 */
	const ARG_PRIORITY = 'priority';

	/**
	 * Section argument name.
	 */
	const ARG_SECTION = 'section';

	/**
	 * Choices argument name.
	 */
	const ARG_CHOICES = 'choices';

	/**
	 * Input attributes argument name.
	 */
	const ARG_INPUT_ATTRS = 'input_attrs';

	/**
	 * Gets the Customizer control identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Customizer control identifier.
	 */
	public function get_id() : string;

	/**
	 * Gets the Customizer control arguments.
	 *
	 * @since 1.0.0
	 *
	 * @return array Customizer control arguments.
	 */
	public function get_args() : array;

	/**
	 * Parses Customizer control arguments including the identifier using a callback.
	 *
	 * @since 1.0.0
	 *
	 * @param callable $parse_callback Parse callback. Must filter the arguments passed and return them.
	 */
	public function parse_args( $parse_callback );

	/**
	 * Maps the control to a core `WP_Customize_Control` instance to register.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance where the control should be registered.
	 * @return WP_Customize_Control Customize control to register in WordPress core.
	 */
	public function map( WP_Customize_Manager $wp_customize ) : WP_Customize_Control;
}
