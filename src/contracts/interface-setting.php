<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Setting interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

use WP_Error;

/**
 * Interface for a setting class.
 *
 * @since 1.0.0
 */
interface Setting {

	/**
	 * Type argument name.
	 */
	const ARG_TYPE = 'type';

	/**
	 * Description argument name.
	 */
	const ARG_DESCRIPTION = 'description';

	/**
	 * Default argument name.
	 */
	const ARG_DEFAULT = 'default';

	/**
	 * Enum argument name.
	 */
	const ARG_ENUM = 'enum';

	/**
	 * Format argument name.
	 */
	const ARG_FORMAT = 'format';

	/**
	 * Minimum argument name.
	 */
	const ARG_MINIMUM = 'minimum';

	/**
	 * Maximum argument name.
	 */
	const ARG_MAXIMUM = 'maximum';

	/**
	 * Items argument name.
	 */
	const ARG_ITEMS = 'items';

	/**
	 * Properties argument name.
	 */
	const ARG_PROPERTIES = 'properties';

	/**
	 * Validate callback argument name.
	 */
	const ARG_VALIDATE_CALLBACK = 'validate_callback';

	/**
	 * Sanitize callback argument name.
	 */
	const ARG_SANITIZE_CALLBACK = 'sanitize_callback';

	/**
	 * Parse callback argument name.
	 */
	const ARG_PARSE_CALLBACK = 'parse_callback';

	/**
	 * Gets the setting identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Setting identifier.
	 */
	public function get_id() : string;

	/**
	 * Gets the schema that describes the setting.
	 *
	 * @since 1.0.0
	 *
	 * @return array Setting schema.
	 */
	public function get_schema() : array;

	/**
	 * Validates a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Error $validity Error object to add validation errors to.
	 * @param mixed    $value    Value to validate.
	 * @return bool|WP_Error True on success, error object on failure.
	 */
	public function validate_value( WP_Error $validity, $value );

	/**
	 * Sanitizes a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed Sanitized value.
	 */
	public function sanitize_value( $value );

	/**
	 * Parses a value for the setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value Value to parse.
	 * @return mixed Parsed value.
	 */
	public function parse_value( $value );
}
