<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Parser class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Context_Shortcode;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Parser;

/**
 * Class for parsing shortcodes in WordPress.
 *
 * @since 1.0.0
 */
class WordPress_Shortcode_Parser implements Shortcode_Parser {

	/**
	 * Shortcode registry.
	 *
	 * @since 1.0.0
	 * @var Shortcode_Registry
	 */
	protected $shortcode_registry;

	/**
	 * Internal storage for original context shortcodes.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $context_stack = [];

	/**
	 * Constructor.
	 *
	 * Sets the shortcode registry to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Shortcode_Registry $shortcode_registry Optional. Shortcode registry to use.
	 */
	public function __construct( Shortcode_Registry $shortcode_registry = null ) {
		if ( null === $shortcode_registry ) {
			$shortcode_registry = new WordPress_Shortcode_Registry();
		}

		$this->shortcode_registry = $shortcode_registry;
	}

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
	public function parse_shortcodes( string $content, string $context = Context_Shortcode::DEFAULT_CONTEXT ) : string {
		if ( Context_Shortcode::DEFAULT_CONTEXT === $context ) {
			return do_shortcode( $content );
		}

		$this->switch_shortcode_context( $context );
		$ret = do_shortcode( $content );
		$this->restore_shortcode_context();

		return $ret;
	}

	/**
	 * Switches the global shortcode context to a given context.
	 *
	 * @since 1.0.0
	 *
	 * @global array $shortcode_tags Registered shortcode tags.
	 *
	 * @param string $context Context to switch to.
	 */
	protected function switch_shortcode_context( string $context ) {
		global $shortcode_tags;

		$this->context_stack[] = $shortcode_tags;

		$shortcodes = array_filter( $this->shortcode_registry->get_all_registered(), function( Shortcode $shortcode ) use ( $context ) {
			if ( $shortcode instanceof Context_Shortcode && $shortcode->supports_context( $context ) ) {
				return true;
			}

			if ( Context_Shortcode::DEFAULT_CONTEXT === $context && ! $shortcode instanceof Context_Shortcode ) {
				return true;
			}

			return false;
		} );

		$shortcode_tags = []; // phpcs:ignore WordPress.Variables.GlobalVariables.OverrideProhibited
		foreach ( $shortcodes as $shortcode ) {
			$shortcode_tags[ $shortcode->get_id() ] = [ $shortcode, 'get_output' ]; // phpcs:ignore WordPress.Variables.GlobalVariables.OverrideProhibited
		}
	}

	/**
	 * Restores the global shortcode context to the previously active context.
	 *
	 * @since 1.0.0
	 *
	 * @global array $shortcode_tags Registered shortcode tags.
	 */
	protected function restore_shortcode_context() {
		global $shortcode_tags;

		if ( empty( $this->context_stack ) ) {
			return;
		}

		$shortcode_tags = array_pop( $this->context_stack ); // phpcs:ignore WordPress.Variables.GlobalVariables.OverrideProhibited
	}
}
