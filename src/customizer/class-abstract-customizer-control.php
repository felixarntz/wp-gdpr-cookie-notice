<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer\Abstract_Customizer_Control class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Customizer_Control;
use WP_Customize_Manager;
use WP_Customize_Control;

/**
 * Base class representing a Customizer control.
 *
 * @since 1.0.0
 */
class Abstract_Customizer_Control implements Customizer_Control {

	/**
	 * Customizer control identifier.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $id;

	/**
	 * Customizer control arguments.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $args = [];

	/**
	 * Constructor.
	 *
	 * Sets the Customizer control identifier and arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Customizer control identifier.
	 * @param array  $args {
	 *     Optional. Customizer control arguments.
	 *
	 *     @type string $label       Control label.
	 *     @type string $description Control description.
	 *     @type string $capability  Control capability.
	 *     @type int    $priority    Control priority.
	 *     @type string $section     Control parent section.
	 *     @type array  $choices     Control choices to select from.
	 *     @type array  $input_attrs Additional arbitrary input attributes for the control.
	 * }
	 */
	public function __construct( string $id, array $args = [] ) {
		$this->id   = $id;
		$this->args = $args;
	}

	/**
	 * Gets the Customizer control identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Customizer control identifier.
	 */
	final public function get_id() : string {
		return $this->id;
	}

	/**
	 * Gets the Customizer control arguments.
	 *
	 * @since 1.0.0
	 *
	 * @return array Customizer control arguments.
	 */
	final public function get_args() : array {
		return $this->args;
	}

	/**
	 * Parses Customizer control arguments including the identifier using a callback.
	 *
	 * @since 1.0.0
	 *
	 * @param callable $parse_callback Parse callback. Must filter the arguments passed and return them.
	 */
	final public function parse_args( $parse_callback ) {
		$args       = $this->args;
		$args['id'] = $this->id;

		$args = call_user_func( $parse_callback, $args );

		if ( isset( $args['id'] ) ) {
			$this->id = $args['id'];
			unset( $args['id'] );
		}

		$this->args = $args;
	}

	/**
	 * Maps the control to a core `WP_Customize_Control` instance to register.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance where the control should be registered.
	 * @return WP_Customize_Control Customize control to register in WordPress core.
	 */
	public function map( WP_Customize_Manager $wp_customize ) : WP_Customize_Control {
		$args = $this->map_args( $this->args );

		return new WP_Customize_Control( $wp_customize, $this->id, $args );
	}

	/**
	 * Maps control arguments prior to passing them to a core `WP_Customize_Control` instance.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Control arguments.
	 * @return array Mapped control arguments.
	 */
	protected function map_args( array $args ) : array {
		return $args;
	}
}
