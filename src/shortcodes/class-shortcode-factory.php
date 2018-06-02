<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes\Shortcode_Factory class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Context_Shortcode;

/**
 * Class for instantiating shortcodes.
 *
 * @since 1.0.0
 */
class Shortcode_Factory {

	/**
	 * Instantiates a new shortcode.
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
	 * @return Shortcode New shortcode instance.
	 */
	public function create( string $id, callable $callback, array $args = [] ) : Shortcode {
		if ( isset( $args[ Context_Shortcode::ARG_CONTEXTS ] ) ) {
			return new Base_Context_Shortcode( $id, $callback, $args );
		}

		return new Base_Shortcode( $id, $callback, $args );
	}
}
