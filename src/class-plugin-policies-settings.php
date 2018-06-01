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
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Control;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Policy_Page;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Setting_Factory;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Customizer_Control_Factory;
use WP_Customize_Manager;

/**
 * Class for registering the plugin's policies settings and Customizer controls.
 *
 * @since 1.0.0
 */
class Plugin_Policies_Settings implements Integration {

	/**
	 * Option reader to manage for the plugin's settings.
	 *
	 * @since 1.0.0
	 * @var Option_Reader
	 */
	protected $option_reader;

	/**
	 * Constructor.
	 *
	 * Sets the option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Option_Reader $option_reader Optional. Option reader to use.
	 */
	public function __construct( Option_Reader $option_reader = null ) {
		$this->option_reader = $option_reader;
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		add_action( 'wp_gdpr_cookie_notice_register_settings', [ $this, 'register_settings' ], 10, 1 );
		add_action( 'wp_gdpr_cookie_notice_add_customizer_policies_controls', [ $this, 'register_customizer_controls' ], 10, 1 );
		add_action( 'customize_register', [ $this, 'register_privacy_policy_page_control' ], 10, 1 );
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
				},
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
		$factory = new Customizer_Control_Factory();

		$controls = [
			$factory->create( Cookie_Policy_Page::SETTING_COOKIE_POLICY_PAGE, [
				Customizer_Control::ARG_TYPE        => 'dropdown-pages',
				Customizer_Control::ARG_LABEL       => __( 'Cookie Policy Page', 'wp-gdpr-cookie-notice' ),
				Customizer_Control::ARG_DESCRIPTION => __( 'Select the page that contains your cookie policy, if available.', 'wp-gdpr-cookie-notice' ),
				'allow_addition'                    => true,
			] ),

			// TODO: Only show this control if privacy policy page is set.
			$factory->create( Cookie_Policy_Page::SETTING_PRIVACY_POLICY_PAGE_COOKIE_SECTION_ID, [
				Customizer_Control::ARG_TYPE        => 'text',
				Customizer_Control::ARG_LABEL       => __( 'Cookie Section ID', 'wp-gdpr-cookie-notice' ),
				Customizer_Control::ARG_DESCRIPTION => __( 'If your privacy policy page contains a cookie policy section, enter the ID attribute of that section.', 'wp-gdpr-cookie-notice' ),
			] ),
		];

		foreach ( $controls as $control ) {
			$customizer->add_control( $control );
		}
	}

	/**
	 * Registers a Customizer control for core's Privacy Policy page setting.
	 *
	 * Since this setting is not a plugin-specific setting, its control is added using
	 * the core Customizer API directly.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	public function register_privacy_policy_page_control( WP_Customize_Manager $wp_customize ) {
		if ( ! $wp_customize->get_setting( 'wp_page_for_privacy_policy' ) ) {
			$wp_customize->add_setting( 'wp_page_for_privacy_policy', [
				'type'              => 'option',
				'capability'        => 'manage_privacy_options',
				'transport'         => 'postMessage',
				'validate_callback' => function( $validity, $value ) {
					if ( ! empty( $value ) && 'page' !== get_post_type( (int) $value ) ) {
						$validity->add( 'value_no_page', __( 'The value is not an existing page.', 'wp-gdpr-cookie-notice' ) );
					}

					return $validity;
				},
				'sanitize_callback' => 'absint',
				'parse_callback'    => 'absint',
				'default'           => 0,
			] );
		}

		$wp_customize->add_control( 'wp_page_for_privacy_policy', [
			'section'        => $this->option_reader->get_setting_id() . '_' . Plugin_Customizer::SECTION_POLICIES,
			'type'           => 'dropdown-pages',
			'label'          => __( 'Privacy Policy Page', 'wp-gdpr-cookie-notice' ),
			'description'    => __( 'Select the page that contains your privacy policy.', 'wp-gdpr-cookie-notice' ),
			'priority'       => 5,
			'allow_addition' => true,
		] );
	}
}
