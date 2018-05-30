<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Policy_Page class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;

/**
 * Class representing the cookie policy page.
 *
 * @since 1.0.0
 */
class Cookie_Policy_Page extends Abstract_Page implements Service {

	/**
	 * Identifier for the 'cookie_policy_page' setting.
	 */
	const SETTING_COOKIE_POLICY_PAGE = 'cookie_policy_page';

	/**
	 * Identifier for the 'privacy_policy_page_cookie_section_id' setting.
	 */
	const SETTING_PRIVACY_POLICY_PAGE_COOKIE_SECTION_ID = 'privacy_policy_page_cookie_section_id';

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

		if ( ! $id && $this->options->get( self::SETTING_PRIVACY_POLICY_PAGE_COOKIE_SECTION_ID ) ) {
			return (int) get_option( 'wp_page_for_privacy_policy' );
		}

		return $id;
	}

	/**
	 * Gets the URL to the page.
	 *
	 * @since 1.0.0
	 *
	 * @return string Page URL.
	 */
	public function get_url() : string {
		$url = parent::get_url();

		if ( ! empty( $url ) && $this->get_id() === (int) get_option( 'wp_page_for_privacy_policy' ) ) {
			$url .= '#' . $this->options->get( self::SETTING_PRIVACY_POLICY_PAGE_COOKIE_SECTION_ID );
		}

		return $url;
	}
}
