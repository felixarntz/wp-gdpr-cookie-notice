<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Customizer_Partial_Factory class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Partial;

/**
 * Class for instantiating Customizer partials.
 *
 * @since 1.0.0
 */
class Customizer_Partial_Factory {

	/**
	 * Instantiates a new Customizer partial.
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
	 * @return Customizer_Partial New Customizer partial instance.
	 */
	public function create( string $id, array $args = [] ) : Customizer_Partial {
		return new Base_Customizer_Partial( $id, $args );
	}
}
