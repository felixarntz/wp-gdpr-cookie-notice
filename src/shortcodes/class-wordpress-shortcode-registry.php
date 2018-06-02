<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Registry class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Context_Shortcode;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Util\ID_Validator;

/**
 * Class for registering shortcodes in WordPress.
 *
 * @since 1.0.0
 */
class WordPress_Shortcode_Registry implements Shortcode_Registry {

	use ID_Validator;

	/**
	 * Registered shortcodes.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $shortcodes = [];

	/**
	 * Registers a shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string    $id        Unique identifier for the shortcode.
	 * @param Shortcode $shortcode Shortcode instance.
	 *
	 * @throws Invalid_Identifier_Exception Thrown when the identifier is invalid.
	 * @throws Duplicate_Identifier_Exception Thrown when the identifier is already in use.
	 */
	public function register( string $id, Shortcode $shortcode ) {
		if ( ! $this->is_valid_id( $id ) ) {
			throw Invalid_Identifier_Exception::from_id( $id );
		}

		if ( isset( $this->shortcodes[ $id ] ) ) {
			throw Duplicate_Identifier_Exception::from_id( $id );
		}

		$this->shortcodes[ $id ] = $shortcode;

		if ( ! $shortcode instanceof Context_Shortcode || $shortcode->supports_context( Context_Shortcode::DEFAULT_CONTEXT ) ) {
			add_shortcode( $shortcode->get_id(), [ $shortcode, 'get_output' ] );
		}
	}

	/**
	 * Retrieves a registered shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the shortcode.
	 * @return Shortcode Shortcode instance.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the shortcode for the identifier is not registered.
	 */
	public function get_registered( string $id ) : Shortcode {
		if ( ! isset( $this->shortcodes[ $id ] ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		return $this->shortcodes[ $id ];
	}

	/**
	 * Checks if a shortcode is registered.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the shortcode.
	 * @return bool True if the shortcode is registered, false otherwise.
	 */
	public function is_registered( string $id ) : bool {
		return isset( $this->shortcodes[ $id ] );
	}

	/**
	 * Gets the registered shortcodes.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $shortcode instance pairs.
	 */
	public function get_all_registered() : array {
		return $this->shortcodes;
	}
}
