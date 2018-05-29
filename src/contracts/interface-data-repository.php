<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Data_Repository interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Invalid_Identifier_Exception;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Exceptions\Unregistered_Identifier_Exception;

/**
 * Interface for a data repository class.
 *
 * @since 1.0.0
 */
interface Data_Repository {

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
	public function set( string $id, array $data );

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
	public function get( string $id ) : array;

	/**
	 * Deletes an available set of data.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the set of data.
	 *
	 * @throws Unregistered_Identifier_Exception Thrown when the set of data for the identifier is not registered.
	 */
	public function delete( string $id );

	/**
	 * Checks if a set of data is available.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the set of data.
	 * @return bool True if the set of data is available, false otherwise.
	 */
	public function has( string $id ) : bool;
}
