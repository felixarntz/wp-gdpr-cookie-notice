<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Plugin_Customizer class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use WP_Customize_Manager;

/**
 * Class for registering plugin Customizer UI.
 *
 * @since 1.0.0
 */
class Plugin_Customizer implements Service {

	/**
	 * Initializes the class functionality.
	 *
	 * @since 1.0.0
	 */
	public function initialize() {
		add_action( 'init', function() {
			$this->register_customizer_ui();
		}, 10, 0 );
	}

	/**
	 * Registers plugin settings.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	protected function register_customizer_ui( WP_Customize_Manager $wp_customize ) {

		$customizer = new Plugin_Customizer_Proxy( $wp_customize );

		/**
		 * Fires when the plugin's settings are registered.
		 *
		 * @since 1.0.0
		 *
		 * @param Plugin_Customizer_Proxy $customizer Customizer proxy instance.
		 */
		do_action( 'wp_gdpr_cookie_notice_register_settings', $customizer );
	}
}
