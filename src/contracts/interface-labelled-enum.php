<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Labelled_Enum interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for an enum class that has labels.
 *
 * @since 1.0.0
 */
interface Labelled_Enum extends Enum {

	/**
	 * Gets the possible values with their labels for the enum.
	 *
	 * @since 1.0.0
	 *
	 * @return array Possible enum values as $value => $label pairs.
	 */
	public function get_labels() : array;
}
