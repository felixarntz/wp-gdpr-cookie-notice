<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Preferences class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Data_Repository;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Page;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Labelled_Enum;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Data\Cookie_Data_Repository;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Cookie_Type_Exception;

/**
 * Class for managing cookie preferences.
 *
 * @since 1.0.0
 */
class Cookie_Preferences implements Service {

	/**
	 * Identifier for the preferences cookie to use.
	 */
	const COOKIE_ID = 'wp_gdpr_cookie_preferences';

	/**
	 * Identifier for the 'last_modified' cookie field.
	 */
	const COOKIE_LAST_MODIFIED = 'last_modified';

	/**
	 * Cookie data repository.
	 *
	 * @since 1.0.0
	 * @var Data_Repository
	 */
	protected $data_repository;

	/**
	 * Cookie policy page.
	 *
	 * @since 1.0.0
	 * @var Page
	 */
	protected $cookie_policy_page;

	/**
	 * Privacy policy page.
	 *
	 * @since 1.0.0
	 * @var Page
	 */
	protected $privacy_policy_page;

	/**
	 * Constructor.
	 *
	 * Sets the data repository and cookie types to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Data_Repository $data_repository     Optional. Data repository to use.
	 * @param Page            $cookie_policy_page  Optional. Cookie policy page instance.
	 * @param Page            $privacy_policy_page Optional. Privacy policy page instance.
	 */
	public function __construct( Data_Repository $data_repository = null, Page $cookie_policy_page = null, Page $privacy_policy_page = null ) {
		if ( null === $data_repository ) {
			$data_repository = new Cookie_Data_Repository();
		}

		if ( null === $cookie_policy_page ) {
			$cookie_policy_page = new Cookie_Policy_Page();
		}

		if ( null === $privacy_policy_page ) {
			$privacy_policy_page = new Privacy_Policy_Page();
		}

		$this->data_repository     = $data_repository;
		$this->cookie_policy_page  = $cookie_policy_page;
		$this->privacy_policy_page = $privacy_policy_page;
	}

	/**
	 * Checks whether cookies are accepted for a given type.
	 *
	 * @since 1.0.0
	 *
	 * @param string $type Cookie type to check. Default is the type for functional cookies.
	 * @return bool True if cookies for the type are accepted, false otherwise.
	 *
	 * @throws Invalid_Cookie_Type_Exception Thrown when the cookie type is invalid.
	 */
	public function cookies_accepted( string $type = Cookie_Type_Enum::TYPE_FUNCTIONAL ) : bool {
		if ( ! in_array( $type, ( new Cookie_Type_Enum() )->get_values(), true ) ) {
			throw Invalid_Cookie_Type_Exception::from_type( $type );
		}

		$preferences = $this->get_preferences();

		return ! empty( $preferences[ $type ] );
	}

	/**
	 * Gets the user's cookie preferences.
	 *
	 * @since 1.0.0
	 *
	 * @return array Cookie preferences as $key => $value pairs.
	 */
	public function get_preferences() : array {
		$defaults = $this->get_default_preferences();

		if ( ! $this->data_repository->has( self::COOKIE_ID ) ) {
			return $defaults;
		}

		$preferences = $this->data_repository->get( self::COOKIE_ID );

		if ( $this->is_out_of_date( $preferences ) ) {
			$this->data_repository->delete( self::COOKIE_ID );

			return $defaults;
		}

		$preferences = array_intersect_key( wp_parse_args( $preferences, $defaults ), $defaults );

		return $preferences;
	}

	/**
	 * Sets the user's cookie preferences.
	 *
	 * @since 1.0.0
	 *
	 * @param array $preferences Cookie preferences as $key => $value pairs.
	 */
	public function set_preferences( array $preferences ) {
		$defaults = $this->get_default_preferences();

		$preferences = array_intersect_key( wp_parse_args( $preferences, $defaults ), $defaults );
		$preferences = $this->set_last_modified( $preferences );

		$this->data_repository->set( self::COOKIE_ID, $preferences );
	}

	/**
	 * Resets the user's cookie preferences.
	 *
	 * @since 1.0.0
	 */
	public function reset_preferences() {
		$this->data_repository->delete( self::COOKIE_ID );
	}

	/**
	 * Gets the default preferences.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default preferences as $key => $value pairs.
	 */
	protected function get_default_preferences() : array {
		return array_fill_keys( ( new Cookie_Type_Enum() )->get_values(), false );
	}

	/**
	 * Checks whether given cookie preferences are out of date.
	 *
	 * Based on the preferences 'last_modified' information, they are considered outdated
	 * if the cookie is older than when the cookie policy page has been last modified.
	 *
	 * @since 1.0.0
	 *
	 * @param array $preferences Cookie preferences as $key => $value pairs.
	 * @return bool True if the preferences are out of date, false otherwise.
	 */
	protected function is_out_of_date( array $preferences ) : bool {
		if ( empty( $preferences[ self::COOKIE_LAST_MODIFIED ] ) ) {
			return true;
		}

		$policy_page = $this->cookie_policy_page;
		if ( ! $policy_page->get_id() ) {
			$policy_page = $this->privacy_policy_page;
		}

		$policy_last_modified = (int) strtotime( $policy_page->get_last_modified_date() );

		return $preferences[ self::COOKIE_LAST_MODIFIED ] < $policy_last_modified;
	}

	/**
	 * Adds the 'last_modified' information to given cookie preferences.
	 *
	 * @since 1.0.0
	 *
	 * @param array $preferences Cookie preferences as $key => $value pairs.
	 * @return array Modified cookie preferences array including 'last_modified' information.
	 */
	protected function set_last_modified( array $preferences ) : array {
		$preferences[ self::COOKIE_LAST_MODIFIED ] = (int) current_time( 'timestamp' );

		return $preferences;
	}
}
