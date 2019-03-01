<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\Base_Shortcode class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode;

/**
 * Base class representing a shortcode.
 *
 * @since 1.0.0
 */
class Base_Shortcode implements Shortcode {

	/**
	 * Shortcode identifier.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $id;

	/**
	 * Shortcode callback.
	 *
	 * @since 1.0.0
	 * @var callable
	 */
	protected $callback;

	/**
	 * Shortcode arguments.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $args = [];

	/**
	 * Constructor.
	 *
	 * Sets the shortcode identifier, callback and arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string   $id       Shortcode identifier.
	 * @param callable $callback Shortcode callback.
	 * @param array    $args     {
	 *     Optional. Shortcode arguments.
	 *
	 *     @type array $defaults Default shortcode attribute values.
	 * }
	 */
	public function __construct( string $id, callable $callback, array $args = [] ) {
		$this->id       = $id;
		$this->callback = $callback;
		$this->args     = wp_parse_args( $args, [
			Shortcode::ARG_DEFAULTS => [],
		] );
	}

	/**
	 * Gets the shortcode identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Shortcode identifier.
	 */
	final public function get_id() : string {
		return $this->id;
	}

	/**
	 * Gets the shortcode output for given attributes and content.
	 *
	 * @since 1.0.0
	 *
	 * @param array       $atts    Optional. Shortcode attributes. Default empty array.
	 * @param string|null $content Optional. Shortcode content. Default null.
	 * @return string Shortcode output.
	 */
	final public function get_output( array $atts = [], string $content = null ) : string {
		$atts = shortcode_atts( $this->get_defaults(), $atts, $this->id );

		return call_user_func( $this->callback, $atts, $content );
	}

	/**
	 * Gets the default shortcode attribute values.
	 *
	 * @since 1.0.0
	 *
	 * @return array Shortcode attribute defaults.
	 */
	protected function get_defaults() : array {
		return (array) $this->args[ Shortcode::ARG_DEFAULTS ];
	}
}
