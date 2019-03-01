<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Position_Enum class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Labelled_Enum;

/**
 * Cookie position enum class.
 *
 * @since 1.0.0
 */
class Cookie_Position_Enum implements Labelled_Enum {

	/**
	 * Identifier for the 'top' position.
	 */
	const POSITION_TOP = 'top';

	/**
	 * Identifier for the 'bottom' position.
	 */
	const POSITION_BOTTOM = 'bottom';

	/**
	 * Identifier for the 'overlay' position.
	 */
	const POSITION_OVERLAY = 'overlay';

	/**
	 * Gets the possible values for the enum.
	 *
	 * @since 1.0.0
	 *
	 * @return array Possible enum values.
	 */
	public function get_values() : array {
		return [
			self::POSITION_TOP,
			self::POSITION_BOTTOM,
			self::POSITION_OVERLAY,
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
			self::POSITION_TOP     => __( 'Top of the page', 'wp-gdpr-cookie-notice' ),
			self::POSITION_BOTTOM  => __( 'Bottom of the page', 'wp-gdpr-cookie-notice' ),
			self::POSITION_OVERLAY => __( 'Modal overlay', 'wp-gdpr-cookie-notice' ),
		];
	}
}
