<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Policy_Page class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Page;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;

/**
 * Class representing the cookie policy page.
 *
 * @since 1.0.0
 */
class Cookie_Policy_Page implements Service, Page {

	/**
	 * Identifier for the 'cookie_policy_page' setting.
	 */
	const SETTING_COOKIE_POLICY_PAGE = 'cookie_policy_page';

	/**
	 * Option reader.
	 *
	 * @since 1.0.0
	 * @var Option_Reader
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * Sets the option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Option_Reader $options Optional. Option reader to use.
	 */
	public function __construct( Option_Reader $options = null ) {
		if ( null === $options ) {
			$options = new Plugin_Option_Reader();
		}

		$this->options = $options;
	}

	/**
	 * Gets the ID of the page.
	 *
	 * @since 1.0.0
	 *
	 * @return int Page ID.
	 */
	public function get_id() : int {
		$id = $this->options->get_option( self::SETTING_COOKIE_POLICY_PAGE );

		if ( ! $id ) {
			return (int) get_option( 'wp_page_for_privacy_policy' );
		}

		return $id;
	}

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
