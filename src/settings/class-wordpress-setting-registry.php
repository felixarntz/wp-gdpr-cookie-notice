<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\WordPress_Setting_Registry class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Settings;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Setting;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Setting_Registry;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Duplicate_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\ID_Validator;
use WP_Customize_Manager;

/**
 * Class for registering settings in WordPress.
 *
 * @since 1.0.0
 */
class WordPress_Setting_Registry implements Setting_Registry {

	use ID_Validator;

	/**
	 * Option group to use in WordPress for registered settings.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $option_group;

	/**
	 * Registered settings.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Constructor.
	 *
	 * Sets the option group.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option_group Option group to use in WordPress for registered settings.
	 */
	public function __construct( string $option_group ) {
		$this->option_group = $option_group;
	}

	/**
	 * Registers a setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $id      Unique identifier for the setting.
	 * @param Setting $setting Setting instance.
	 *
	 * @throws Invalid_Identifier_Exception Thrown when the identifier is invalid.
	 * @throws Duplicate_Identifier_Exception Thrown when the identifier is already in use.
	 */
	public function register( string $id, Setting $setting ) {
		if ( ! $this->is_valid_id( $id ) ) {
			throw Invalid_Identifier_Exception::from_id( $id );
		}

		if ( isset( $this->settings[ $id ] ) ) {
			throw Duplicate_Identifier_Exception::from_id( $id );
		}

		$this->settings[ $id ] = $setting;

		add_action(
			'init',
			function() use ( $setting ) {
				$this->register_with_api( $setting );
			},
			PHP_INT_MAX,
			0
		);

		add_action(
			'customize_register',
			function( $wp_customize ) use ( $setting ) {
				$this->register_with_customizer( $wp_customize, $setting );
			},
			1,
			1
		);
	}

	/**
	 * Retrieves a registered setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the setting.
	 * @return Setting Setting instance.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the setting for the identifier is not registered.
	 */
	public function get_registered( string $id ) : Setting {
		if ( ! isset( $this->settings[ $id ] ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		return $this->settings[ $id ];
	}

	/**
	 * Checks if a setting is registered.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the setting.
	 * @return bool True if the setting is registered, false otherwise.
	 */
	public function is_registered( string $id ) : bool {
		return isset( $this->settings[ $id ] );
	}

	/**
	 * Gets the registered settings.
	 *
	 * @since 1.0.0
	 *
	 * @return array Map of $id => $setting instance pairs.
	 */
	public function get_all_registered() : array {
		return $this->settings;
	}

	/**
	 * Registers a setting with the WordPress REST API.
	 *
	 * @since 1.0.0
	 *
	 * @param Setting $setting Setting to register.
	 */
	protected function register_with_api( Setting $setting ) {
		$id     = $setting->get_id();
		$schema = $setting->get_schema();

		$args = array(
			'sanitize_callback' => array( $setting, 'sanitize_value' ),
			'show_in_rest'      => array( 'schema' => $schema ),
		);

		if ( isset( $schema[ Setting::ARG_TYPE ] ) ) {
			$args['type'] = $schema[ Setting::ARG_TYPE ];
		}

		if ( isset( $schema[ Setting::ARG_DESCRIPTION ] ) ) {
			$args['description'] = $schema[ Setting::ARG_DESCRIPTION ];
		}

		if ( isset( $schema[ Setting::ARG_DEFAULT ] ) ) {
			$args['default'] = $schema[ Setting::ARG_DEFAULT ];
		}

		register_setting( $this->option_group, $id, $args );

		add_filter( "option_$id", array( $setting, 'parse_value' ) );
	}

	/**
	 * Registers a setting with the WordPress REST API.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 * @param Setting              $setting      Setting to register.
	 * @param string               $parent_id    Optional. Parent setting ID.
	 */
	protected function register_with_customizer( WP_Customize_Manager $wp_customize, Setting $setting, string $parent_id = '' ) {
		$id = $setting->get_id();
		if ( ! empty( $parent_id ) ) {
			$id = $parent_id . '[' . $id . ']';
		}

		// If this is an aggregate setting, register the child settings separately in the Customizer.
		if ( $setting instanceof Setting_Registry ) {
			$child_settings = $setting->get_all_registered();

			foreach ( $child_settings as $child_setting ) {
				$this->register_with_customizer( $wp_customize, $child_setting, $id );
			}

			return;
		}

		$schema = $setting->get_schema();

		$args = array(
			'type'                 => 'option',
			'capability'           => 'manage_options',
			'transport'            => 'postMessage',
			'validate_callback'    => array( $setting, 'validate_value' ),
			'sanitize_callback'    => array( $setting, 'sanitize_value' ),
			'sanitize_js_callback' => array( $setting, 'parse_value' ),
		);

		if ( isset( $schema[ Setting::ARG_DEFAULT ] ) ) {
			$args['default'] = $schema[ Setting::ARG_DEFAULT ];
		}

		$wp_customize->add_setting( $id, $args );
	}
}
