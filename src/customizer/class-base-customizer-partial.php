<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Base_Customizer_Partial class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Partial;
use WP_Customize_Manager;
use WP_Customize_Partial;

/**
 * Base class representing a Customizer partial.
 *
 * @since 1.0.0
 */
class Base_Customizer_Partial implements Customizer_Partial {

	/**
	 * Customizer partial identifier.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $id;

	/**
	 * Customizer partial arguments.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $args = [];

	/**
	 * Constructor.
	 *
	 * Sets the Customizer partial identifier and arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Customizer partial identifier.
	 * @param array  $args {
	 *     Optional. Customizer partial arguments.
	 *
	 *     @type string   $type                Partial type.
	 *     @type string   $capability          Partial capability.
	 *     @type array    $settings            Partial settings.
	 *     @type string   $selector            Partial selector.
	 *     @type callable $render_callback     Partial render callback.
	 *     @type bool     $container_inclusive Whether the partial container should be included.
	 *     @type bool     $fallback_refresh    Whether to use a full refresh as fallback.
	 * }
	 */
	public function __construct( string $id, array $args = [] ) {
		$this->id   = $id;
		$this->args = $args;
	}

	/**
	 * Gets the Customizer partial identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Customizer partial identifier.
	 */
	final public function get_id() : string {
		return $this->id;
	}

	/**
	 * Gets the Customizer partial arguments.
	 *
	 * @since 1.0.0
	 *
	 * @return array Customizer partial arguments.
	 */
	final public function get_args() : array {
		return $this->args;
	}

	/**
	 * Parses Customizer partial arguments including the identifier using a callback.
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
	 * Maps the partial to a core `WP_Customize_Partial` instance to register.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance where the partial should be registered.
	 * @return WP_Customize_Partial Customize partial to register in WordPress core.
	 */
	public function map( WP_Customize_Manager $wp_customize ) : WP_Customize_Partial {
		return new WP_Customize_Partial( $wp_customize->selective_refresh, $this->id, $this->args );
	}
}
