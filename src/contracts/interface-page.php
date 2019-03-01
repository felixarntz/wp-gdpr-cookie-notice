<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Page interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a class representing a page.
 *
 * @since 1.0.0
 */
interface Page {

	/**
	 * Gets the ID of the page.
	 *
	 * @since 1.0.0
	 *
	 * @return int Page ID.
	 */
	public function get_id() : int;

	/**
	 * Gets the title of the page.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page title.
	 */
	public function get_title() : string;

	/**
	 * Gets the URL to the page.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page URL.
	 */
	public function get_url() : string;

	/**
	 * Gets the date the page was created.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page created date.
	 */
	public function get_created_date() : string;

	/**
	 * Gets the date the page was last modified.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page last modified date.
	 */
	public function get_last_modified_date() : string;
}
