<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Inline_Asset interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for an asset that renders inline.
 *
 * @since 1.0.0
 */
interface Inline_Asset {

	/**
	 * Gets the ID attribute used for the asset.
	 *
	 * @since 1.0.0
	 *
	 * @return string ID attribute.
	 */
	public function get_id() : string;

	/**
	 * Prints the full asset including the wrapping tags.
	 *
	 * @since 1.0.0
	 */
	public function print();

	/**
	 * Prints the actual asset content.
	 *
	 * @since 1.0.0
	 */
	public function print_content();
}
