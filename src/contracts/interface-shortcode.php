<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a shortcode class.
 *
 * @since 1.0.0
 */
interface Shortcode {

	/**
	 * Defaults argument name.
	 */
	const ARG_DEFAULTS = 'defaults';

	/**
	 * Gets the shortcode identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Shortcode identifier.
	 */
	public function get_id() : string;

	/**
	 * Gets the shortcode output for given attributes and content.
	 *
	 * @since 1.0.0
	 *
	 * @param array       $atts    Optional. Shortcode attributes. Default empty array.
	 * @param string|null $content Optional. Shortcode content. Default null.
	 * @return string Shortcode output.
	 */
	public function get_output( array $atts = [], string $content = null ) : string;
}
