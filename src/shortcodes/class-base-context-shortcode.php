<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes\Base_Context_Shortcode class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Context_Shortcode;

/**
 * Base class representing a shortcode that supports specific contexts.
 *
 * @since 1.0.0
 */
class Base_Context_Shortcode extends Base_Shortcode implements Context_Shortcode {

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
	 *     @type array $contexts Supported shortcode contexts. Default is
	 *                           only the 'default' context is supported.
	 * }
	 */
	public function __construct( string $id, callable $callback, array $args = [] ) {
		parent::__construct( $id, $callback, $args );

		$this->args = wp_parse_args( $args, [
			Context_Shortcode::ARG_CONTEXTS => [ Context_Shortcode::DEFAULT_CONTEXT ],
		] );
	}

	/**
	 * Gets the contexts that are supported by the shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @return array Supported shortcode contexts.
	 */
	final public function get_contexts() : array {
		return (array) $this->args[ Context_Shortcode::ARG_CONTEXTS ];
	}

	/**
	 * Checks whether a given context is supported by the shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context Shortcode context.
	 * @return bool True if the context is supported, false otherwise.
	 */
	final public function supports_context( string $context ) : bool {
		return in_array( $context, (array) $this->args[ Context_Shortcode::ARG_CONTEXTS ], true );
	}
}
