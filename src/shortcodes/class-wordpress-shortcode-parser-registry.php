<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Parser_Registry class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Service;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Registry;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;

/**
 * Class for registering and parsing shortcodes in WordPress.
 *
 * @since 1.0.0
 */
class WordPress_Shortcode_Parser_Registry extends WordPress_Shortcode_Parser implements Service, Shortcode_Registry {

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
		$this->shortcode_registry->register( $id, $shortcode );
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
		return $this->shortcode_registry->get_registered( $id );
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
		return $this->shortcode_registry->is_registered( $id );
	}

	/**
	 * Gets the registered shortcodes.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $shortcode instance pairs.
	 */
	public function get_all_registered() : array {
		return $this->shortcode_registry->get_all_registered();
	}
}
