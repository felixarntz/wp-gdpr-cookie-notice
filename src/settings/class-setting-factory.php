<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Setting_Factory class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Settings;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Setting;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Type_Exception;

/**
 * Class for instantiating settings.
 *
 * @since 1.0.0
 */
class Setting_Factory {

	/**
	 * Instantiates a new setting.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Setting identifier.
	 * @param array  $args {
	 *     Optional. Setting arguments.
	 *
	 *     @type string    $type              Setting type. Either 'string', 'integer', 'float',
	 *                                        'boolean', 'array', 'object', or 'aggregate'. Default
	 *                                        'string'.
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
	 * @return Setting New setting instance.
	 *
	 * @throws Invalid_Type_Exception Thrown when the type is invalid.
	 */
	public function create( string $id, array $args = [] ) : Setting {
		if ( empty( $args[ Setting::ARG_TYPE ] ) ) {
			$args[ Setting::ARG_TYPE ] = 'string';
		}

		switch ( $args[ Setting::ARG_TYPE ] ) {
			case 'array':
				return new Array_Setting( $id, $args, $this->get_array_items_setting( $args ) );
			case 'object':
				return new Object_Setting( $id, $args, $this->get_object_properties_settings( $args ) );
			case 'aggregate':
				return new Aggregate_Setting( $id, $args, $this->get_object_properties_settings( $args ) );
			default:
				$type_map = [
					'string'  => String_Setting::class,
					'integer' => Integer_Setting::class,
					'float'   => Float_Setting::class,
					'boolean' => Boolean_Setting::class,
				];

				if ( ! isset( $type_map[ $args[ Setting::ARG_TYPE ] ] ) ) {
					throw Invalid_Type_Exception::from_setting_type( $args[ Setting::ARG_TYPE ] );
				}

				$classname = $type_map[ $args[ Setting::ARG_TYPE ] ];

				return new $classname( $id, $args );
		}
	}

	/**
	 * Gets a setting to use for an array setting's items.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Array setting arguments.
	 * @return Setting|null Setting instance, or null if not applicable.
	 */
	protected function get_array_items_setting( array $args ) : Setting {
		if ( isset( $args[ Setting::ARG_ITEMS ] ) ) {
			if ( $args[ Setting::ARG_ITEMS ] instanceof Setting ) {
				return $args[ Setting::ARG_ITEMS ];
			}

			if ( is_array( $args[ Setting::ARG_ITEMS ] ) ) {
				return $this->create( 'items', $args[ Setting::ARG_ITEMS ] );
			}
		}

		return null;
	}

	/**
	 * Gets an array of settings to use for an object setting's properties.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Object setting arguments.
	 * @return array Array of setting instances, or empty array if not applicable.
	 */
	protected function get_object_properties_settings( array $args ) : array {
		$properties = [];

		if ( empty( $args[ Setting::ARG_PROPERTIES ] ) || ! is_array( $args[ Setting::ARG_PROPERTIES ] ) ) {
			return $properties;
		}

		foreach ( $args[ Setting::ARG_PROPERTIES ] as $id => $property ) {
			if ( $property instanceof Setting ) {
				$properties[] = $property;
				continue;
			}

			if ( is_string( $id ) && is_array( $property ) ) {
				$properties[] = $this->create( $id, $property );
			}
		}

		return $properties;
	}
}
