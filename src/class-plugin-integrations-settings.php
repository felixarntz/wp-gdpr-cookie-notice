<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin_Integrations_Settings class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Control;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\WordPress_Cookie_Integration_Registry;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Setting_Factory;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Customizer_Control_Factory;

/**
 * Class for registering the plugin's cookie integrations settings and Customizer controls.
 *
 * @since 1.0.0
 */
class Plugin_Integrations_Settings implements Integration {

	/**
	 * Cookie integration registry to use.
	 *
	 * @since 1.0.0
	 * @var Cookie_Integration_Registry
	 */
	protected $cookie_integrations;

	/**
	 * Constructor.
	 *
	 * Sets the cookie integration registry to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Cookie_Integration_Registry $cookie_integrations Cookie integration registry to use.
	 */
	public function __construct( Cookie_Integration_Registry $cookie_integrations ) {
		$this->cookie_integrations = $cookie_integrations;
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		add_action( 'wp_gdpr_cookie_notice_register_settings', [ $this, 'register_settings' ], 10, 1 );
		add_action( 'wp_gdpr_cookie_notice_add_customizer_integrations_controls', [ $this, 'register_customizer_controls' ], 10, 1 );
	}

	/**
	 * Registers settings.
	 *
	 * @since 1.0.0
	 *
	 * @param Setting_Registry $setting_registry Setting registry instance.
	 */
	public function register_settings( Setting_Registry $setting_registry ) {
		// The integrations must all be registered in order to register their settings.
		$register_callback = function() use ( $setting_registry ) {
			$settings = $this->get_settings();

			foreach ( $settings as $setting ) {
				$setting_registry->register( $setting->get_id(), $setting );
			}
		};

		if ( did_action( 'wp_gdpr_cookie_notice_register_cookie_integrations' ) ) {
			$register_callback();
			return;
		}

		add_action( 'wp_gdpr_cookie_notice_register_cookie_integrations', $register_callback, PHP_INT_MAX, 0 );
	}

	/**
	 * Registers Customizer controls.
	 *
	 * @since 1.0.0
	 *
	 * @param Customizer $customizer Customizer instance.
	 */
	public function register_customizer_controls( Customizer $customizer ) {
		$controls = $this->get_controls();

		foreach ( $controls as $control ) {
			$customizer->add_control( $control );
		}
	}

	/**
	 * Gets the default content settings to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of Setting instances.
	 */
	protected function get_settings() : array {
		$factory = new Setting_Factory();

		$settings            = [];
		$cookie_integrations = $this->cookie_integrations->get_all_registered();
		foreach ( $cookie_integrations as $cookie_integration ) {
			$setting_slug = sprintf( WordPress_Cookie_Integration_Registry::ENABLED_SETTING_GENERATOR, $cookie_integration->get_id() );

			$settings[] = $factory->create( $setting_slug, [
				Setting::ARG_TYPE        => 'boolean',
				/* translators: %s: cookie integration identifier */
				Setting::ARG_DESCRIPTION => sprintf( __( 'Whether to enable the %s cookie integration.', 'wp-gdpr-cookie-notice' ), $cookie_integration->get_id() ),
				Setting::ARG_DEFAULT     => true,
			] );
		}

		return $settings;
	}

	/**
	 * Gets the default content controls to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of Customizer_Control instances.
	 */
	protected function get_controls() : array {
		$factory = new Customizer_Control_Factory();

		$controls            = [];
		$cookie_integrations = $this->cookie_integrations->get_all_registered();
		foreach ( $cookie_integrations as $cookie_integration ) {
			if ( ! $cookie_integration->is_applicable() ) {
				continue;
			}

			$setting_slug = sprintf( WordPress_Cookie_Integration_Registry::ENABLED_SETTING_GENERATOR, $cookie_integration->get_id() );

			$controls[] = $factory->create( $setting_slug, [
				Customizer_Control::ARG_TYPE  => 'checkbox',
				Customizer_Control::ARG_LABEL => $cookie_integration->get_enable_label(),
			] );
		}

		return $controls;
	}
}
