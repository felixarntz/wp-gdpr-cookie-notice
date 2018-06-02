<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Parser interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a shortcode parser class.
 *
 * @since 1.0.0
 */
interface Shortcode_Parser {

	/**
	 * Parses shortcodes in a given string.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Content to parse shortcodes in.
	 * @param string $context Optional. Shortcode context to use for the content.
	 *                        Default is 'default'.
	 * @return string Content with parsed shortcodes.
	 */
	public function parse_shortcodes( string $content, string $context = Context_Shortcode::DEFAULT_CONTEXT ) : string;
}
