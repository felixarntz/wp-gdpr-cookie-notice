<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin_Appearance_Settings class
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
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Partial;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Stylesheet;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Position_Enum;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Button_Size_Enum;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Setting_Factory;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Customizer_Control_Factory;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Customizer_Partial_Factory;

/**
 * Class for registering the plugin's appearance settings and Customizer controls.
 *
 * @since 1.0.0
 */
class Plugin_Appearance_Settings implements Integration {

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
		add_action( 'wp_gdpr_cookie_notice_add_customizer_appearance_controls', [ $this, 'register_customizer_controls' ], 10, 1 );
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
			$factory->create( Cookie_Notice_Stylesheet::SETTING_POSITION, [
				Setting::ARG_TYPE        => 'string',
				Setting::ARG_DESCRIPTION => __( 'Where the notice should appear.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT     => Cookie_Position_Enum::POSITION_BOTTOM,
				Setting::ARG_ENUM        => ( new Cookie_Position_Enum() )->get_values(),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_TEXT_COLOR, [
				Setting::ARG_TYPE              => 'string',
				Setting::ARG_DESCRIPTION       => __( 'The notice text color.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT           => '#404040',
				Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_LINK_COLOR, [
				Setting::ARG_TYPE              => 'string',
				Setting::ARG_DESCRIPTION       => __( 'The notice link color.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT           => '#21759b',
				Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BACKGROUND_COLOR, [
				Setting::ARG_TYPE              => 'string',
				Setting::ARG_DESCRIPTION       => __( 'The notice background color.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT           => '#ffffff',
				Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BORDER_WIDTH, [
				Setting::ARG_TYPE        => 'integer',
				Setting::ARG_DESCRIPTION => __( 'The notice border width.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_MINIMUM     => 0,
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BORDER_COLOR, [
				Setting::ARG_TYPE              => 'string',
				Setting::ARG_DESCRIPTION       => __( 'The notice border color.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT           => '#cccccc',
				Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_SHOW_DROP_SHADOW, [
				Setting::ARG_TYPE        => 'boolean',
				Setting::ARG_DESCRIPTION => __( 'Whether to show a drop shadow on the notice.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT     => true,
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BUTTON_SIZE, [
				Setting::ARG_TYPE        => 'string',
				Setting::ARG_DESCRIPTION => __( 'The notice button size.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT     => Cookie_Button_Size_Enum::SIZE_MEDIUM,
				Setting::ARG_ENUM        => ( new Cookie_Button_Size_Enum() )->get_values(),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BUTTON_TEXT_COLOR, [
				Setting::ARG_TYPE              => 'string',
				Setting::ARG_DESCRIPTION       => __( 'The notice button text color.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT           => '#ffffff',
				Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BUTTON_BACKGROUND_COLOR, [
				Setting::ARG_TYPE              => 'string',
				Setting::ARG_DESCRIPTION       => __( 'The notice button background color.', 'wp-gdpr-cookie-notice' ),
				Setting::ARG_DEFAULT           => '#21759b',
				Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
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
			$factory->create( Cookie_Notice_Stylesheet::SETTING_POSITION, [
				Customizer_Control::ARG_TYPE    => 'select',
				Customizer_Control::ARG_LABEL   => __( 'Position', 'wp-gdpr-cookie-notice' ),
				Customizer_Control::ARG_CHOICES => ( new Cookie_Position_Enum() )->get_labels(),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_TEXT_COLOR, [
				Customizer_Control::ARG_TYPE  => 'color',
				Customizer_Control::ARG_LABEL => __( 'Text Color', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_LINK_COLOR, [
				Customizer_Control::ARG_TYPE  => 'color',
				Customizer_Control::ARG_LABEL => __( 'Link Color', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BACKGROUND_COLOR, [
				Customizer_Control::ARG_TYPE  => 'color',
				Customizer_Control::ARG_LABEL => __( 'Background Color', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BORDER_WIDTH, [
				Customizer_Control::ARG_TYPE        => 'number',
				Customizer_Control::ARG_LABEL       => __( 'Border Width', 'wp-gdpr-cookie-notice' ),
				Customizer_Control::ARG_INPUT_ATTRS => [ 'min' => '0', 'step' => '1' ],
			] ),

			// TODO: Only show this control if the border width is greater than 0.
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BORDER_COLOR, [
				Customizer_Control::ARG_TYPE  => 'color',
				Customizer_Control::ARG_LABEL => __( 'Border Color', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_SHOW_DROP_SHADOW, [
				Customizer_Control::ARG_TYPE  => 'checkbox',
				Customizer_Control::ARG_LABEL => __( 'Show drop shadow?', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BUTTON_SIZE, [
				Customizer_Control::ARG_TYPE    => 'radio',
				Customizer_Control::ARG_LABEL   => __( 'Button Size', 'wp-gdpr-cookie-notice' ),
				Customizer_Control::ARG_CHOICES => ( new Cookie_Button_Size_Enum() )->get_labels(),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BUTTON_TEXT_COLOR, [
				Customizer_Control::ARG_TYPE  => 'color',
				Customizer_Control::ARG_LABEL => __( 'Button Text Color', 'wp-gdpr-cookie-notice' ),
			] ),
			$factory->create( Cookie_Notice_Stylesheet::SETTING_BUTTON_BACKGROUND_COLOR, [
				Customizer_Control::ARG_TYPE  => 'color',
				Customizer_Control::ARG_LABEL => __( 'Button Background Color', 'wp-gdpr-cookie-notice' ),
			] ),
		];

		$control_ids = [];
		foreach ( $controls as $control ) {
			$control_ids[] = $control->get_id();
			$customizer->add_control( $control );
		}

		$stylesheet = $this->cookie_notice->get_stylesheet();
		$partial    = ( new Customizer_Partial_Factory() )->create( $stylesheet->get_id(), [
			Customizer_Partial::ARG_SETTINGS            => $control_ids,
			Customizer_Partial::ARG_SELECTOR            => '#' . $stylesheet->get_id(),
			Customizer_Partial::ARG_RENDER_CALLBACK     => [ $stylesheet, 'print_content' ],
			Customizer_Partial::ARG_CONTAINER_INCLUSIVE => false,
			Customizer_Partial::ARG_FALLBACK_REFRESH    => true,
		] );
		$customizer->add_partial( $partial );
	}
}
