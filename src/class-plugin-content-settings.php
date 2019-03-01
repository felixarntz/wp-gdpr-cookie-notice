<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Plugin_Content_Settings class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Setting_Registry;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Setting;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Customizer;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Customizer_Control;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Customizer_Partial;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Form_Markup;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Markup;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Setting_Factory;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer\Customizer_Control_Factory;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer\Customizer_Partial_Factory;

/**
 * Class for registering the plugin's content settings and Customizer controls.
 *
 * @since 1.0.0
 */
class Plugin_Content_Settings implements Integration {

	/**
	 * Cookie notice to use.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice
	 */
	protected $cookie_notice;

	/**
	 * Constructor.
	 *
	 * Sets the cookie notice to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Cookie_Notice $cookie_notice Cookie notice to use.
	 */
	public function __construct( Cookie_Notice $cookie_notice ) {
		$this->cookie_notice = $cookie_notice;
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		add_action( 'wp_gdpr_cookie_notice_register_settings', [ $this, 'register_settings' ], 10, 1 );
		add_action( 'wp_gdpr_cookie_notice_add_customizer_content_controls', [ $this, 'register_customizer_controls' ], 10, 1 );
	}

	/**
	 * Registers settings.
	 *
	 * @since 1.0.0
	 *
	 * @param Setting_Registry $setting_registry Setting registry instance.
	 */
	public function register_settings( Setting_Registry $setting_registry ) {
		$settings = $this->get_settings();

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
		$controls = $this->get_controls();

		foreach ( $controls as $control ) {
			$customizer->add_control( $control );
		}

		$partials = $this->get_partials();

		foreach ( $partials as $partial ) {
			$customizer->add_partial( $partial );
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

		$settings = [
			$factory->create( Cookie_Notice_Markup::SETTING_NOTICE_HEADING, [
				Setting::ARG_TYPE        => 'string',
				Setting::ARG_DESCRIPTION => __( 'The cookie notice heading.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT     => __( 'This Site Uses Cookies', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Markup::SETTING_NOTICE_CONTENT, [
				Setting::ARG_TYPE        => 'string',
				Setting::ARG_DESCRIPTION => __( 'The cookie notice content.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT     => _x( 'This site, like many others, uses small files called cookies to help us improve and customize your experience. Learn more about how we use cookies in [cookie_policy_link text="our cookie policy"].', 'content containing shortcode', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Form_Markup::SETTING_SUBMIT_TEXT, [
				Setting::ARG_TYPE        => 'string',
				Setting::ARG_DESCRIPTION => __( 'The submit button text.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT     => _x( 'OK', 'submit button text', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Form_Markup::SETTING_SHOW_TOGGLES, [
				Setting::ARG_TYPE        => 'boolean',
				Setting::ARG_DESCRIPTION => __( 'Whether to show toggles for granular cookie control.', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Form_Markup::SETTING_SHOW_LEARN_MORE, [
				Setting::ARG_TYPE        => 'boolean',
				Setting::ARG_DESCRIPTION => __( 'Whether to show a Learn More link.', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Form_Markup::SETTING_LEARN_MORE_TEXT, [
				Setting::ARG_TYPE        => 'string',
				Setting::ARG_DESCRIPTION => __( 'The text to use for the Learn More link.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT     => _x( 'Learn more about cookies', 'link text', 'wp-gdpr-cookie-notice' ),
			] ),
		];

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

		$cookie_policy_link  = '<code>[cookie_policy_link text="' . __( 'Cookie Policy', 'wp-gdpr-cookie-notice' ) . '"]</code>';
		$privacy_policy_link = '<code>[privacy_policy_link text="' . __( 'Privacy Policy', 'wp-gdpr-cookie-notice' ) . '"]</code>';

		$controls = [
			$factory->create( Cookie_Notice_Markup::SETTING_NOTICE_HEADING, [
				Customizer_Control::ARG_TYPE  => 'text',
				Customizer_Control::ARG_LABEL => __( 'Notice Heading', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Markup::SETTING_NOTICE_CONTENT, [
				Customizer_Control::ARG_TYPE        => 'textarea',
				Customizer_Control::ARG_LABEL       => __( 'Notice Content', 'wp-gdpr-cookie-notice' ),

				/* translators: 1: shortcode tag, 2: other shortcode tag */
				Customizer_Control::ARG_DESCRIPTION => sprintf( __( 'You may use the shortcodes %1$s and %2$s.', 'wp-gdpr-cookie-notice' ), $cookie_policy_link, $privacy_policy_link ),
			] ),
			$factory->create( Cookie_Notice_Form_Markup::SETTING_SUBMIT_TEXT, [
				Customizer_Control::ARG_TYPE  => 'text',
				Customizer_Control::ARG_LABEL => __( 'Submit Button Text', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Form_Markup::SETTING_SHOW_TOGGLES, [
				Customizer_Control::ARG_TYPE  => 'checkbox',
				Customizer_Control::ARG_LABEL => __( 'Show toggles for granular cookie control?', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Form_Markup::SETTING_SHOW_LEARN_MORE, [
				Customizer_Control::ARG_TYPE  => 'checkbox',
				Customizer_Control::ARG_LABEL => __( 'Show a Learn More link?', 'wp-gdpr-cookie-notice' ),
			] ),

			// TODO: Only show this control if the learn more link is enabled.
			$factory->create( Cookie_Notice_Form_Markup::SETTING_LEARN_MORE_TEXT, [
				Customizer_Control::ARG_TYPE  => 'text',
				Customizer_Control::ARG_LABEL => __( 'Learn More Text', 'wp-gdpr-cookie-notice' ),
			] ),
		];

		return $controls;
	}

	/**
	 * Gets the default content partials to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of Customizer_Partial instances.
	 */
	protected function get_partials() : array {
		$factory = new Customizer_Partial_Factory();

		// @codingStandardsIgnoreStart WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		$partials = [
			$factory->create( 'cookie_notice_heading', [
				Customizer_Partial::ARG_SETTINGS            => [ Cookie_Notice_Markup::SETTING_NOTICE_HEADING ],
				Customizer_Partial::ARG_SELECTOR            => '.wp-gdpr-cookie-notice-heading',
				Customizer_Partial::ARG_RENDER_CALLBACK     => [ $this->cookie_notice, 'render_heading' ],
				Customizer_Partial::ARG_CONTAINER_INCLUSIVE => false,
				Customizer_Partial::ARG_FALLBACK_REFRESH    => true,
			] ),
			$factory->create( 'cookie_notice_content', [
				Customizer_Partial::ARG_SETTINGS            => [ Cookie_Notice_Markup::SETTING_NOTICE_CONTENT ],
				Customizer_Partial::ARG_SELECTOR            => '.wp-gdpr-cookie-notice-content',
				Customizer_Partial::ARG_RENDER_CALLBACK     => [ $this->cookie_notice, 'render_content' ],
				Customizer_Partial::ARG_CONTAINER_INCLUSIVE => false,
				Customizer_Partial::ARG_FALLBACK_REFRESH    => true,
			] ),
			$factory->create( 'cookie_notice_controls', [
				Customizer_Partial::ARG_SETTINGS            => [
					Cookie_Notice_Form_Markup::SETTING_SUBMIT_TEXT,
					Cookie_Notice_Form_Markup::SETTING_SHOW_TOGGLES,
					Cookie_Notice_Form_Markup::SETTING_SHOW_LEARN_MORE,
					Cookie_Notice_Form_Markup::SETTING_LEARN_MORE_TEXT,
				],
				Customizer_Partial::ARG_SELECTOR            => '.wp-gdpr-cookie-notice-controls',
				Customizer_Partial::ARG_RENDER_CALLBACK     => [ $this->cookie_notice->get_form(), 'render_controls' ],
				Customizer_Partial::ARG_CONTAINER_INCLUSIVE => false,
				Customizer_Partial::ARG_FALLBACK_REFRESH    => true,
			] ),
		];
		// @codingStandardsIgnoreEnd WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned

		return $partials;
	}
}
