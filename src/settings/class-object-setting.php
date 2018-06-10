<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Object_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Setting;
use WP_Error;

/**
 * Class representing an object setting.
 *
 * @since 1.0.0
 */
class Object_Setting extends Abstract_Setting {

	/**
	 * Settings to use for each individual object property.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $properties_settings;

	/**
	 * Constructor.
	 *
	 * Sets the setting identifier and arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id            Setting identifier.
	 * @param array  $args          {
	 *     Optional. Setting arguments.
	 *
	 *     @type string    $description       Setting description.
	 *     @type mixed     $default           Setting default value.
	 *     @type array     $enum              Allowed setting enum values.
	 *     @type string    $format            Allowed setting value format.
	 *     @type int|float $minimum           Minimum allowed setting value.
	 *     @type int|float $maximum           Maximum allowed setting value.
	 *     @type array     $items             Items schema, if an array setting.
	 *     @type array     $properties        Properties schema, if an object setting.
	 *     @type callable  $validate_callback Setting validation callback.
	 *     @type callable  $sanitize_callback Setting sanitization callback.
	 *     @type callable  $parse_callback    Setting parse callback.
	 * }
	 * @param array  $properties_settings Optional. Settings to use for each individual object property.
	 */
	public function __construct( string $id, array $args = [], array $properties_settings = [] ) {
		$this->properties_settings = array_filter( $properties_settings, function( $setting ) {
			return $setting instanceof Setting;
		} );

		parent::__construct( $id, $args );
	}

	/**
	 * Performs default validation for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $validity Error object to add validation errors to.
	 * @param mixed    $value    Value to validate.
	 * @return WP_Error Error object to add possible errors to.
	 */
	protected function default_validation_callback( WP_Error $validity, $value ) {
		$value = (array) $value;

		foreach ( $this->properties_settings as $setting ) {
			$id             = $setting->get_id();
			$property_value = array_key_exists( $id, $value ) ? $value[ $id ] : null;

			$setting->validate_value( $validity, $property_value );
		}

		return $validity;
	}

	/**
	 * Performs default sanitization for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed Sanitized value.
	 */
	protected function default_sanitization_callback( $value ) {
		$value = (array) $value;

		foreach ( $this->properties_settings as $setting ) {
			$id             = $setting->get_id();
			$schema         = $setting->get_schema();
			$property_value = array_key_exists( $id, $value ) ? $value[ $id ] : ( isset( $schema[ self::ARG_DEFAULT ] ) ? $schema[ self::ARG_DEFAULT ] : null );

			$value[ $id ] = $setting->sanitize_value( $property_value );
		}

		return $value;
	}

	/**
	 * Performs default parsing for a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to parse.
	 * @return mixed Parsed value.
	 */
	protected function default_parsing_callback( $value ) {
		$value = (array) $value;

		foreach ( $this->properties_settings as $setting ) {
			$id             = $setting->get_id();
			$schema         = $setting->get_schema();
			$property_value = array_key_exists( $id, $value ) ? $value[ $id ] : ( isset( $schema[ self::ARG_DEFAULT ] ) ? $schema[ self::ARG_DEFAULT ] : null );

			$value[ $id ] = $setting->parse_value( $property_value );
		}

		return $value;
	}

	/**
	 * Prepares the setting schema.
	 *
	 * @since 1.0.0
	 *
	 * @param array $schema Setting schema.
	 * @return array Prepared setting schema.
	 */
	protected function prepare_schema( array $schema ) : array {
		if ( empty( $schema[ self::ARG_TYPE ] ) ) {
			$schema[ self::ARG_TYPE ] = 'object';
		}

		if ( ! isset( $schema[ self::ARG_DEFAULT ] ) ) {
			$schema[ self::ARG_DEFAULT ] = array();
		}

		$schema[ self::ARG_PROPERTIES ] = array();

		foreach ( $this->properties_settings as $setting ) {
			$setting_id     = $setting->get_id();
			$setting_schema = $setting->get_schema();

			$schema[ self::ARG_PROPERTIES ][ $setting_id ] = $setting_schema;

			if ( isset( $setting_schema[ self::ARG_DEFAULT ] ) ) {
				$schema[ self::ARG_DEFAULT ][ $setting_id ] = $setting_schema[ self::ARG_DEFAULT ];
			}
		}

		return $schema;
	}
}
