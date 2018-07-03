<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Data\Cookie_Data_Repository class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Data;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Data_Repository;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Util\ID_Validator;

/**
 * Class for a data repository using cookies.
 *
 * @since 1.0.0
 */
class Cookie_Data_Repository implements Data_Repository {

	use ID_Validator;

	/**
	 * Available datasets.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $datasets = [];

	/**
	 * Sets a set of data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Unique identifier for the set of data.
	 * @param array  $data Data array.
	 *
	 * @throws Invalid_Identifier_Exception Thrown when the identifier is invalid.
	 */
	public function set( string $id, array $data ) {
		if ( ! $this->is_valid_id( $id ) ) {
			throw Invalid_Identifier_Exception::from_id( $id );
		}

		$this->datasets[ $id ] = $data;
		$this->write_cookie( $id, $data );
	}

	/**
	 * Retrieves an available set of data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the set of data.
	 * @return array Data array.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the set of data for the identifier is not registered.
	 */
	public function get( string $id ) : array {
		if ( ! $this->has( $id ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		return $this->datasets[ $id ];
	}

	/**
	 * Deletes an available set of data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the set of data.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the set of data for the identifier is not registered.
	 */
	public function delete( string $id ) {
		if ( ! $this->has( $id ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		unset( $this->datasets[ $id ] );
		$this->unset_cookie( $id );
	}

	/**
	 * Checks if a set of data is available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the set of data.
	 * @return bool True if the set of data is available, false otherwise.
	 */
	public function has( string $id ) : bool {
		if ( ! isset( $this->datasets[ $id ] ) ) {
			$data = $this->read_cookie( $id );
			if ( null === $data ) {
				return false;
			}

			$this->datasets[ $id ] = $data;
		}

		return true;
	}

	/**
	 * Writes data for a given identifier into a cookie.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   Identifier.
	 * @param array  $data Data array.
	 */
	protected function write_cookie( string $id, array $data ) {
		$this->set_wp_cookie( $id, wp_json_encode( $data ), time() + YEAR_IN_SECONDS );
	}

	/**
	 * Reads data for a given identifier from a cookie.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Identifier.
	 * @return array|null Data array on success, or null on failure.
	 */
	protected function read_cookie( string $id ) {
		$cookie = filter_input( INPUT_COOKIE, $id );
		if ( ! $cookie ) {
			return null;
		}

		$data = json_decode( $cookie, true );
		if ( ! is_array( $data ) ) {
			return null;
		}

		return $data;
	}

	/**
	 * Unsets a cookie for a given identifier.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Identifier.
	 */
	protected function unset_cookie( string $id ) {
		$this->set_wp_cookie( $id, '', time() - HOUR_IN_SECONDS );
	}

	/**
	 * Sets a cookie correctly.
	 *
	 * Uses cookie domain, path and secure definitions from WordPress.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id      Cookie ID.
	 * @param string $content Cookie content.
	 * @param int    $expire  Optional. When the cookie should expire. Expects
	 *                        a UNIX timestamp, or 0 in order to expire at the
	 *                        end of the current session. Default 0.
	 */
	protected function set_wp_cookie( string $id, string $content, int $expire = 0 ) {
		setcookie( $id, $content, $expire, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	}
}
