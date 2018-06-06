<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin_Shortcodes class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Context_Shortcode;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes\Shortcode_Factory;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice;

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

		foreach ( $shortcodes_args as $shortcode_args ) {
			$service_id = $shortcode_args['service_id'];

			$shortcode = $factory->create(
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
					Context_Shortcode::ARG_CONTEXTS => [ Cookie_Notice::CONTEXT ],
				]
			);

			$this->shortcode_registry->register( $shortcode->get_id(), $shortcode );
		}
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

		$classname = str_replace( '_', '-', $policy_service_id );
		$target    = '';
		$a11y_text = '';
		if ( ! empty( $atts['target'] ) ) {
			$target = ' target="' . esc_attr( $atts['target'] ) . '"';

			if ( '_blank' === $atts['target'] ) {
				$a11y_text = ' <span class="screen-reader-text"> ' . _x( '(opens in a new window)', 'accessibility hint', 'wp-gdpr-cookie-notice' ) . '</span>';
			}
		}

		return '<a href="' . esc_url( $url ) . '" class="' . esc_attr( $classname ) . '"' . $target . '>' . esc_html( $text ) . $a11y_text . '</a>';
	}
}
