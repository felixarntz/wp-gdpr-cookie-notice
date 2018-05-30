<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Abstract_Page class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Page;

/**
 * Base class representing a page.
 *
 * @since 1.0.0
 */
abstract class Abstract_Page implements Page {

	/**
	 * Gets the ID of the page.
	 *
	 * @since 1.0.0
	 *
	 * @return int Page ID.
	 */
	abstract public function get_id() : int;

	/**
	 * Gets the title of the page.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page title.
	 */
	public function get_title() : string {
		$id = $this->get_id();

		if ( ! $id ) {
			return '';
		}

		return get_the_title( $id );
	}

	/**
	 * Gets the URL to the page.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page URL.
	 */
	public function get_url() : string {
		$id = $this->get_id();

		if ( ! $id ) {
			return '';
		}

		return get_permalink( $id );
	}

	/**
	 * Gets the date the page was created.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page created date.
	 */
	public function get_created_date() : string {
		$id = $this->get_id();

		if ( ! $id ) {
			return '1970-01-01 00:00:00';
		}

		$post = get_post( $id );
		if ( ! $post ) {
			return '1970-01-01 00:00:00';
		}

		return $post->post_date;
	}

	/**
	 * Gets the date the page was last modified.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page last modified date.
	 */
	public function get_last_modified_date() : string {
		$id = $this->get_id();

		if ( ! $id ) {
			return '1970-01-01 00:00:00';
		}

		$post = get_post( $id );
		if ( ! $post ) {
			return '1970-01-01 00:00:00';
		}

		return $post->post_modified;
	}
}
