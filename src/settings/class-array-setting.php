<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Array_Setting class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Settings;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Setting;
use WP_Error;

/**
 * Class representing an array setting.
 *
 * @since 1.0.0
 */
class Array_Setting extends Abstract_Setting {

	/**
	 * Setting to use for each individual array item.
	 *
	 * @since 1.0.0
	 * @var Setting
	 */
	protected $items_setting;

	/**
	 * Constructor.
	 *
	 * Sets the setting identifier and arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $id            Setting identifier.
	 * @param array   $args          {
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
	 * @param Setting $items_setting Optional. Setting to use for each individual array item.
	 */
	public function __construct( string $id, array $args = [], Setting $items_setting = null ) {
		$this->items_setting = $items_setting;

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

		if ( null !== $this->items_setting ) {
			foreach ( $value as $item_value ) {
				$this->items_setting->validate_value( $validity, $item_value );
			}
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

		if ( null !== $this->items_setting ) {
			foreach ( $value as $index => $item_value ) {
				$value[ $index ] = $this->items_setting->sanitize_value( $item_value );
			}
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

		if ( null !== $this->items_setting ) {
			foreach ( $value as $index => $item_value ) {
				$value[ $index ] = $this->items_setting->parse_value( $item_value );
			}
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
			$schema[ self::ARG_TYPE ] = 'array';
		}

		if ( ! isset( $schema[ self::ARG_DEFAULT ] ) ) {
			$schema[ self::ARG_DEFAULT ] = array();
		}

		if ( null !== $this->items_setting ) {
			$schema[ self::ARG_ITEMS ] = $this->items_setting->get_schema();
		}

		return $schema;
	}
}
