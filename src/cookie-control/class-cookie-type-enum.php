<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Labelled_Enum;

/**
 * Cookie type enum class.
 *
 * @since 1.0.0
 */
class Cookie_Type_Enum implements Labelled_Enum {

	/**
	 * Identifier for functional cookies.
	 */
	const TYPE_FUNCTIONAL = 'functional';

	/**
	 * Identifier for preferences cookies.
	 */
	const TYPE_PREFERENCES = 'preferences';

	/**
	 * Identifier for analytics cookies.
	 */
	const TYPE_ANALYTICS = 'analytics';

	/**
	 * Identifier for marketing cookies.
	 */
	const TYPE_MARKETING = 'marketing';

	/**
	 * Gets the possible values for the enum.
	 *
	 * @since 1.0.0
	 *
	 * @return array Possible enum values.
	 */
	public function get_values() : array {
		return [
			self::TYPE_FUNCTIONAL,
			self::TYPE_PREFERENCES,
			self::TYPE_ANALYTICS,
			self::TYPE_MARKETING,
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
			self::TYPE_FUNCTIONAL  => _x( 'Functional', 'cookie type', 'wp-gdpr-cookie-notice' ),
			self::TYPE_PREFERENCES => _x( 'Preferences', 'cookie type', 'wp-gdpr-cookie-notice' ),
			self::TYPE_ANALYTICS   => _x( 'Analytics', 'cookie type', 'wp-gdpr-cookie-notice' ),
			self::TYPE_MARKETING   => _x( 'Marketing', 'cookie type', 'wp-gdpr-cookie-notice' ),
		];
	}
}
