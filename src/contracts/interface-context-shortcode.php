<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Context_Shortcode interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a shortcode class that supports specific contexts.
 *
 * @since 1.0.0
 */
interface Context_Shortcode extends Shortcode {

	/**
	 * Contexts argument name.
	 */
	const ARG_CONTEXTS = 'contexts';

	/**
	 * Default shortcode context.
	 */
	const DEFAULT_CONTEXT = 'default';

	/**
	 * Gets the contexts that are supported by the shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @return array Supported shortcode contexts.
	 */
	public function get_contexts() : array;

	/**
	 * Checks whether a given context is supported by the shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param string $context Shortcode context.
	 * @return bool True if the context is supported, false otherwise.
	 */
	public function supports_context( string $context ) : bool;
}
