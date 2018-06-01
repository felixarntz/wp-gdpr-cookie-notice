<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin_Policies_Settings class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Page;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Policy_Page;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Setting_Factory;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Customizer_Control_Factory;

/**
 * Class for registering the plugin's policies settings and Customizer controls.
 *
 * @since 1.0.0
 */
class Plugin_Policies_Settings implements Integration {

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		add_action( 'wp_gdpr_cookie_notice_register_settings', [ $this, 'register_settings' ], 10, 1 );
		add_action( 'wp_gdpr_cookie_notice_add_customizer_policies_controls', [ $this, 'register_customizer_controls' ], 10, 1 );
	}

	/**
	 * Registers settings.
	 *
	 * @since 1.0.0
	 *
	 * @param Setting_Registry $setting_registry Setting registry instance.
	 */
	public function register_settings( Setting_Registry $setting_registry ) {
		$factory = new Setting_Factory();

		$settings = [
			$factory->create( Cookie_Policy_Page::SETTING_COOKIE_POLICY_PAGE, [
				Setting::ARG_TYPE              => 'integer',
				Setting::ARG_DESCRIPTION       => __( 'The cookie policy page ID.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_MINIMUM           => 0,
				Setting::ARG_VALIDATE_CALLBACK => function( $validity, $value ) {
					if ( ! empty( $value ) && 'page' !== get_post_type( (int) $value ) ) {
						$validity->add( 'value_no_page', __( 'The value is not an existing page.', 'wp-gdpr-cookie-notice' ) );
					}

					return $validity;
				}
			] ),
			$factory->create( Cookie_Policy_Page::SETTING_PRIVACY_POLICY_PAGE_COOKIE_SECTION_ID, [
				Setting::ARG_TYPE              => 'string',
				Setting::ARG_DESCRIPTION       => __( 'The ID attribute for the cookie information section in the privacy policy.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_SANITIZE_CALLBACK => 'sanitize_title',
			] ),
		];

		foreach ( $settings as $setting ) {
			$setting_registry->register( $setting->get_id(), $setting );
		}
	}

	/**
	 * Registers Customizer controls.
	 *
	 * @since 1.0.0
	 *
	 * @param Customizer $customizer Customizer instance.
	 */
	public function register_customizer_controls( Customizer $customizer ) {
		$controls = [

		];
	}
}
