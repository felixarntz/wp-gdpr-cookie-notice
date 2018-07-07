<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\Cookie_Integration_Factory class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;

/**
 * Class for instantiating cookie integrations.
 *
 * @since 1.0.0
 */
class Cookie_Integration_Factory {

	/**
	 * Instantiates a new cookie integration.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Cookie integration identifier.
	 * @param array  $args {
	 *     Optional. Cookie integration arguments.
	 *
	 *     @type string   $type                Cookie type that the cookies managed by this integration are part of.
	 *                                         Default is 'functional'.
	 *     @type callable $applicable_callback Callback to check whether the cookie integration is applicable to the
	 *                                         current setup. Default always returns true.
	 *     @type array    $hooks_to_add        Hook objects to add when the cookies managed by this integration are allowed.
	 *                                         Default empty array.
	 *     @type array    $hooks_to_remove     Hook objects to remove when the cookies managed by this integration are not
	 *                                         allowed. Default empty array.
	 * }
	 * @return Cookie_Integration New cookie integration instance.
	 */
	public function create( string $id, array $args = [] ) : Cookie_Integration {
		return new Base_Cookie_Integration( $id, $args );
	}
}
