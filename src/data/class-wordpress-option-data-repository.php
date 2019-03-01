<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Data\Option_Data_Repository class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Data;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Data_Repository;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\ID_Validator;

/**
 * Class for a data repository using WordPress options.
 *
 * @since 1.0.0
 */
class WordPress_Option_Data_Repository implements Data_Repository {

	use ID_Validator;

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

		update_option( $id, $data );
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
		$data = get_option( $id );

		if ( ! is_array( $data ) ) {
			throw Unregistered_Identifier_Exception::from_id( $id );
		}

		return $data;
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

		delete_option( $id );
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
		$data = get_option( $id );

		return is_array( $data );
	}
}
