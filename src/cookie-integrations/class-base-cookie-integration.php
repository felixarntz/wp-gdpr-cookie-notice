<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\Base_Cookie_Integration class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Hook;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Base class representing a cookie integration.
 *
 * @since 1.0.0
 */
class Base_Cookie_Integration implements Cookie_Integration {

	/**
	 * Cookie integration identifier.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $id;

	/**
	 * Cookie type that the cookies managed by this integration are part of.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $type = Cookie_Type_Enum::TYPE_FUNCTIONAL;

	/**
	 * Callback to check whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 * @var callable
	 */
	protected $applicable_callback = '__return_true';

	/**
	 * Hook objects to add when the cookies managed by this integration are allowed.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $hooks_to_add = [];

	/**
	 * Hook objects to remove when the cookies managed by this integration are not allowed.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $hooks_to_remove = [];

	/**
	 * Constructor.
	 *
	 * Sets the cookie integration identifier and arguments.
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
	 */
	public function __construct( string $id, array $args = [] ) {
		$this->id = $id;

		$arg_names = [
			Cookie_Integration::ARG_TYPE,
			Cookie_Integration::ARG_APPLICABLE_CALLBACK,
			Cookie_Integration::ARG_HOOKS_TO_ADD,
			Cookie_Integration::ARG_HOOKS_TO_REMOVE,
		];
		foreach ( $arg_names as $arg_name ) {
			if ( ! array_key_exists( $arg_name, $args ) ) {
				continue;
			}

			$this->$arg_name = $args[ $arg_name ];
		}
	}

	/**
	 * Gets the cookie integration identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie integration identifier.
	 */
	final public function get_id() : string {
		return $this->id;
	}

	/**
	 * Gets the cookie type that the cookies managed by this integration are part of.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie type.
	 */
	final public function get_type() : string {
		return $this->type;
	}

	/**
	 * Checks whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_applicable() : bool {
		return call_user_func( $this->applicable_callback );
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $allowed Whether cookies for the cookie type are currently allowed.
	 */
	public function add_hooks( bool $allowed ) {
		if ( ! $allowed ) {
			add_action( 'wp_loaded', function() {
				array_walk( $this->hooks_to_remove, function( Hook $hook ) {
					$hook->remove();
				} );
			}, PHP_INT_MAX, 0 );
			return;
		}

		array_walk( $this->hooks_to_add, function( Hook $hook ) {
			$hook->add();
		} );
	}
}
