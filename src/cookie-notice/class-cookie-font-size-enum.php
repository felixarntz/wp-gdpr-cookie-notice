<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Font_Size_Enum class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Labelled_Enum;

/**
 * Cookie font size enum class.
 *
 * @since 1.0.0
 */
class Cookie_Font_Size_Enum implements Labelled_Enum {

	/**
	 * Identifier for the 'small' font size.
	 */
	const SIZE_SMALL = 'small';

	/**
	 * Identifier for the 'medium' font size.
	 */
	const SIZE_MEDIUM = 'medium';

	/**
	 * Identifier for the 'large' font size.
	 */
	const SIZE_LARGE = 'large';

	/**
	 * Identifier for the 'extra_large' font size.
	 */
	const SIZE_EXTRA_LARGE = 'extra_large';

	/**
	 * Gets the possible values for the enum.
	 *
	 * @since 1.0.0
	 *
	 * @return array Possible enum values.
	 */
	public function get_values() : array {
		return [
			self::SIZE_SMALL,
			self::SIZE_MEDIUM,
			self::SIZE_LARGE,
			self::SIZE_EXTRA_LARGE,
		];
	}

	/**
	 * Gets the possible values with their labels for the enum.
	 *
	 * @since 1.0.0
	 *
	 * @return array Possible enum values as $value => $label pairs.
	 */
	public function get_labels() : array {
		return [
			self::SIZE_SMALL       => __( 'Small', 'wp-gdpr-cookie-notice' ),
			self::SIZE_MEDIUM      => __( 'Medium', 'wp-gdpr-cookie-notice' ),
			self::SIZE_LARGE       => __( 'Large', 'wp-gdpr-cookie-notice' ),
			self::SIZE_EXTRA_LARGE => __( 'Extra Large', 'wp-gdpr-cookie-notice' ),
		];
	}
}
