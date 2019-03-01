<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Plugin_Shortcodes class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Registry;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Context_Shortcode;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\Shortcode_Factory;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Markup;

/**
 * Class for registering plugin shortcodes.
 *
 * @since 1.0.0
 */
class Plugin_Shortcodes implements Integration {

	/**
	 * Shortcode registry for the plugin's shortcodes.
	 *
	 * @since 1.0.0
	 * @var Shortcode_Registry
	 */
	protected $shortcode_registry;

	/**
	 * Constructor.
	 *
	 * Sets the shortcode registry to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Shortcode_Registry $shortcode_registry Shortcode registry to use.
	 */
	public function __construct( Shortcode_Registry $shortcode_registry ) {
		$this->shortcode_registry = $shortcode_registry;
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		add_action( 'init', [ $this, 'register_shortcodes' ], 1, 0 );
	}

	/**
	 * Registers the plugin's default shortcodes.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcodes() {
		$shortcodes = $this->get_shortcodes();

		foreach ( $shortcodes as $shortcode ) {
			$this->shortcode_registry->register( $shortcode->get_id(), $shortcode );
		}
	}

	/**
	 * Gets the default shortcodes to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of Shortcode instances.
	 */
	protected function get_shortcodes() : array {
		$factory = new Shortcode_Factory();

		$shortcodes_args = [
			[
				'id'           => 'privacy_policy_link',
				'service_id'   => 'privacy_policy_page',
				'default_text' => __( 'Privacy Policy', 'wp-gdpr-cookie-notice' ),
			],
			[
				'id'           => 'cookie_policy_link',
				'service_id'   => 'cookie_policy_page',
				'default_text' => __( 'Cookie Policy', 'wp-gdpr-cookie-notice' ),
			],
		];

		$shortcodes = [];
		foreach ( $shortcodes_args as $shortcode_args ) {
			$service_id = $shortcode_args['service_id'];

			$shortcodes[] = $factory->create(
				$shortcode_args['id'],
				function( $atts ) use ( $service_id ) {
					return $this->get_policy_link( $service_id, $atts );
				},
				[
					Context_Shortcode::ARG_DEFAULTS => [
						'text'          => $shortcode_args['default_text'],
						'target'        => '',
						'show_if_empty' => '1',
					],
					Context_Shortcode::ARG_CONTEXTS => [ Cookie_Notice_Markup::CONTEXT ],
				]
			);
		}

		return $shortcodes;
	}

	/**
	 * Gets HTML markup for a policy link, based on a given policy service and attributes.
	 *
	 * If no URL is available, simply the link text passed in the arguments is returned.
	 *
	 * @since 1.0.0
	 *
	 * @param string $policy_service_id Policy service identifier. Either 'privacy_policy_page',
	 *                                  or 'cookie_policy_page'.
	 * @param array  $atts              {
	 *     Attributes for the link.
	 *
	 *     @type string $text   Text to display for the link.
	 *     @type string $target Value for the link's target attribute.
	 * }
	 * @return string HTML markup.
	 */
	protected function get_policy_link( string $policy_service_id, array $atts ) : string {
		$url  = wp_gdpr_cookie_notice()->get_service( $policy_service_id )->get_url();
		$text = $atts['text'];

		if ( empty( $url ) ) {
			if ( empty( $atts['show_if_empty'] ) || in_array( $atts['show_if_empty'], [ 'false', 'FALSE', 'no', 'NO', '0' ], true ) ) {
				return '';
			}

			return esc_html( $text );
		}

		return $this->get_link( $url, $text, [
			'class'  => str_replace( '_', '-', $policy_service_id ),
			'target' => $atts['target'],
		] );
	}

	/**
	 * Gets a link tag from a given URL, text and attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url  Link URL.
	 * @param string $text Link anchor text.
	 * @param array  $atts Optional. Extra attributes for the link tag. Default empty array.
	 * @return string Link tag.
	 */
	protected function get_link( string $url, string $text, array $atts = [] ) : string {
		$a11y_text = '';
		if ( ! empty( $atts['target'] ) && '_blank' === $atts['target'] ) {
			$a11y_text = ' <span class="screen-reader-text"> ' . _x( '(opens in a new window)', 'accessibility hint', 'wp-gdpr-cookie-notice' ) . '</span>';
		}

		return '<a href="' . esc_url( $url ) . '"' . $this->atts( $atts ) . '>' . esc_html( $text ) . $a11y_text . '</a>';
	}

	/**
	 * Creates an attribute string from given attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Attributes as $attr => $value pairs.
	 * @return string Attribute string.
	 */
	protected function atts( array $atts ) : string {
		$output = '';

		foreach ( $atts as $attr => $value ) {
			if ( empty( $value ) ) {
				continue;
			}

			$output .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
		}

		return $output;
	}
}
