<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Plugin_Appearance_Settings class
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
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Stylesheet;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Position_Enum;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Font_Size_Enum;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Button_Size_Enum;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Setting_Factory;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer\Customizer_Control_Factory;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Customizer\Customizer_Partial_Factory;

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
	 * Gets the default appearance settings to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of Setting instances.
	 */
	protected function get_settings() : array {
		$factory = new Setting_Factory();

		$settings = [
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_POSITION,
				[
					Setting::ARG_TYPE        => 'string',
					Setting::ARG_DESCRIPTION => __( 'Where the notice should appear.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT     => Cookie_Position_Enum::POSITION_BOTTOM,
					Setting::ARG_ENUM        => ( new Cookie_Position_Enum() )->get_values(),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_FONT_SIZE,
				[
					Setting::ARG_TYPE        => 'string',
					Setting::ARG_DESCRIPTION => __( 'The notice font size.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT     => Cookie_Font_Size_Enum::SIZE_MEDIUM,
					Setting::ARG_ENUM        => ( new Cookie_Font_Size_Enum() )->get_values(),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_TEXT_COLOR,
				[
					Setting::ARG_TYPE              => 'string',
					Setting::ARG_DESCRIPTION       => __( 'The notice text color.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT           => '#404040',
					Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_LINK_COLOR,
				[
					Setting::ARG_TYPE              => 'string',
					Setting::ARG_DESCRIPTION       => __( 'The notice link color.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT           => '#21759b',
					Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BACKGROUND_COLOR,
				[
					Setting::ARG_TYPE              => 'string',
					Setting::ARG_DESCRIPTION       => __( 'The notice background color.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT           => '#ffffff',
					Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BORDER_WIDTH,
				[
					Setting::ARG_TYPE        => 'integer',
					Setting::ARG_DESCRIPTION => __( 'The notice border width.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_MINIMUM     => 0,
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BORDER_COLOR,
				[
					Setting::ARG_TYPE              => 'string',
					Setting::ARG_DESCRIPTION       => __( 'The notice border color.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT           => '#cccccc',
					Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_SHOW_DROP_SHADOW,
				[
					Setting::ARG_TYPE        => 'boolean',
					Setting::ARG_DESCRIPTION => __( 'Whether to show a drop shadow on the notice.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT     => true,
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BUTTON_SIZE,
				[
					Setting::ARG_TYPE        => 'string',
					Setting::ARG_DESCRIPTION => __( 'The notice button size.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT     => Cookie_Button_Size_Enum::SIZE_MEDIUM,
					Setting::ARG_ENUM        => ( new Cookie_Button_Size_Enum() )->get_values(),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BUTTON_TEXT_COLOR,
				[
					Setting::ARG_TYPE              => 'string',
					Setting::ARG_DESCRIPTION       => __( 'The notice button text color.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT           => '#ffffff',
					Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BUTTON_BACKGROUND_COLOR,
				[
					Setting::ARG_TYPE              => 'string',
					Setting::ARG_DESCRIPTION       => __( 'The notice button background color.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT           => '#21759b',
					Setting::ARG_SANITIZE_CALLBACK => 'maybe_hash_hex_color',
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_SHOW_CONTROLS_BESIDE_CONTENT,
				[
					Setting::ARG_TYPE        => 'boolean',
					Setting::ARG_DESCRIPTION => __( 'Whether to show the notice controls beside the notice content.', 'wp-gdpr-cookie-notice' ),
					Setting::ARG_DEFAULT     => false,
				]
			),
		];

		return $settings;
	}

	/**
	 * Gets the default appearance controls to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of Customizer_Control instances.
	 */
	protected function get_controls() : array {
		$factory = new Customizer_Control_Factory();

		$controls = [
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_POSITION,
				[
					Customizer_Control::ARG_TYPE    => 'select',
					Customizer_Control::ARG_LABEL   => __( 'Position', 'wp-gdpr-cookie-notice' ),
					Customizer_Control::ARG_CHOICES => ( new Cookie_Position_Enum() )->get_labels(),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_FONT_SIZE,
				[
					Customizer_Control::ARG_TYPE    => 'radio',
					Customizer_Control::ARG_LABEL   => __( 'Font Size', 'wp-gdpr-cookie-notice' ),
					Customizer_Control::ARG_CHOICES => ( new Cookie_Font_Size_Enum() )->get_labels(),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_TEXT_COLOR,
				[
					Customizer_Control::ARG_TYPE  => 'color',
					Customizer_Control::ARG_LABEL => __( 'Text Color', 'wp-gdpr-cookie-notice' ),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_LINK_COLOR,
				[
					Customizer_Control::ARG_TYPE  => 'color',
					Customizer_Control::ARG_LABEL => __( 'Link Color', 'wp-gdpr-cookie-notice' ),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BACKGROUND_COLOR,
				[
					Customizer_Control::ARG_TYPE  => 'color',
					Customizer_Control::ARG_LABEL => __( 'Background Color', 'wp-gdpr-cookie-notice' ),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BORDER_WIDTH,
				[
					Customizer_Control::ARG_TYPE        => 'number',
					Customizer_Control::ARG_LABEL       => __( 'Border Width', 'wp-gdpr-cookie-notice' ),
					Customizer_Control::ARG_INPUT_ATTRS => [
						'min'  => '0',
						'step' => '1',
					],
				]
			),

			// TODO: Only show this control if the border width is greater than 0.
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BORDER_COLOR,
				[
					Customizer_Control::ARG_TYPE  => 'color',
					Customizer_Control::ARG_LABEL => __( 'Border Color', 'wp-gdpr-cookie-notice' ),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_SHOW_DROP_SHADOW,
				[
					Customizer_Control::ARG_TYPE  => 'checkbox',
					Customizer_Control::ARG_LABEL => __( 'Show drop shadow?', 'wp-gdpr-cookie-notice' ),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BUTTON_SIZE,
				[
					Customizer_Control::ARG_TYPE    => 'radio',
					Customizer_Control::ARG_LABEL   => __( 'Button Size', 'wp-gdpr-cookie-notice' ),
					Customizer_Control::ARG_CHOICES => ( new Cookie_Button_Size_Enum() )->get_labels(),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BUTTON_TEXT_COLOR,
				[
					Customizer_Control::ARG_TYPE  => 'color',
					Customizer_Control::ARG_LABEL => __( 'Button Text Color', 'wp-gdpr-cookie-notice' ),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_BUTTON_BACKGROUND_COLOR,
				[
					Customizer_Control::ARG_TYPE  => 'color',
					Customizer_Control::ARG_LABEL => __( 'Button Background Color', 'wp-gdpr-cookie-notice' ),
				]
			),
			$factory->create(
				Cookie_Notice_Stylesheet::SETTING_SHOW_CONTROLS_BESIDE_CONTENT,
				[
					Customizer_Control::ARG_TYPE        => 'checkbox',
					Customizer_Control::ARG_LABEL       => __( 'Show controls beside content?', 'wp-gdpr-cookie-notice' ),
					Customizer_Control::ARG_DESCRIPTION => __( 'This will make the button and the cookie control toggles appear beside the text.', 'wp-gdpr-cookie-notice' ),
				]
			),
		];

		return $controls;
	}

	/**
	 * Gets the default appearance partials to register.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of Customizer_Partial instances.
	 */
	protected function get_partials() : array {
		$factory = new Customizer_Partial_Factory();

		$stylesheet = $this->cookie_notice->get_stylesheet();

		// @codingStandardsIgnoreStart WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		$partials = [
			$factory->create( $stylesheet->get_id(), [
				Customizer_Partial::ARG_SETTINGS            => [
					Cookie_Notice_Stylesheet::SETTING_POSITION,
					Cookie_Notice_Stylesheet::SETTING_FONT_SIZE,
					Cookie_Notice_Stylesheet::SETTING_TEXT_COLOR,
					Cookie_Notice_Stylesheet::SETTING_LINK_COLOR,
					Cookie_Notice_Stylesheet::SETTING_BACKGROUND_COLOR,
					Cookie_Notice_Stylesheet::SETTING_BORDER_WIDTH,
					Cookie_Notice_Stylesheet::SETTING_BORDER_COLOR,
					Cookie_Notice_Stylesheet::SETTING_SHOW_DROP_SHADOW,
					Cookie_Notice_Stylesheet::SETTING_BUTTON_SIZE,
					Cookie_Notice_Stylesheet::SETTING_BUTTON_TEXT_COLOR,
					Cookie_Notice_Stylesheet::SETTING_BUTTON_BACKGROUND_COLOR,
					Cookie_Notice_Stylesheet::SETTING_SHOW_CONTROLS_BESIDE_CONTENT,
				],
				Customizer_Partial::ARG_SELECTOR            => '#' . $stylesheet->get_id(),
				Customizer_Partial::ARG_RENDER_CALLBACK     => [ $stylesheet, 'print_content' ],
				Customizer_Partial::ARG_CONTAINER_INCLUSIVE => false,
				Customizer_Partial::ARG_FALLBACK_REFRESH    => true,
			] ),
		];
		// @codingStandardsIgnoreEnd WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned

		return $partials;
	}
}
