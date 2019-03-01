<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Autoloader class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice;

use Generator;

/**
 * Class used for autoloading.
 *
 * @since 1.0.0
 */
class Autoloader {

	/**
	 * Type to use for any autoloader rules.
	 */
	const TYPE_ANY = 7;

	/**
	 * Type to use for class autoloader rules.
	 */
	const TYPE_CLASS = 4;

	/**
	 * Type to use for trait autoloader rules.
	 */
	const TYPE_TRAIT = 2;

	/**
	 * Type to use for interface autoloader rules.
	 */
	const TYPE_INTERFACE = 1;

	/**
	 * Registered autoloader rules.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $rules = [];

	/**
	 * Registers an autoloader rule.
	 *
	 * @since 1.0.0
	 *
	 * @param string $namespace Namespace to register.
	 * @param string $basedir   Absolute directory to register for the namespace.
	 * @param int    $type      Optional. Bitmask for whether to consider classes, traits,
	 *                          interfaces or any combination of them. Use a mix of the
	 *                          type class constants available. Default is all types being
	 *                          considered.
	 */
	public function register_rule( string $namespace, string $basedir, int $type = self::TYPE_ANY ) {
		$this->rules[ $namespace ] = [
			rtrim( $basedir, '/' ),
			$type,
		];
	}

	/**
	 * Loads a class, trait, or interface.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Full name of a class, trait, or interface.
	 * @return bool True if a file was successfully loaded, false otherwise.
	 */
	public function load( string $name ) : bool {
		$file = $this->get_file( $name );

		if ( empty( $file ) ) {
			return false;
		}

		require_once $file;

		return true;
	}

	/**
	 * Gets the file to load for the name of a class, trait, or interface.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Full name of a class, trait, or interface.
	 * @return string File path, or empty string if none found.
	 */
	public function get_file( string $name ) : string {
		$files = $this->get_possible_files( $name );

		foreach ( $files as $file ) {
			if ( file_exists( $file ) ) {
				return $file;
			}
		}

		return '';
	}

	/**
	 * Gets the possible files to check for the name of a class, trait, or interface.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Full name of a class, trait, or interface.
	 * @return Generator Generator with each file path.
	 */
	public function get_possible_files( string $name ) : Generator {
		$parts = explode( '\\', $name );

		$path = '';
		while ( ! empty( $parts ) ) {
			$path      = '/' . $this->partial_name_to_partial_path( array_pop( $parts ) ) . $path;
			$namespace = implode( '\\', $parts );

			if ( isset( $this->rules[ $namespace ] ) ) {
				$basedir = $this->rules[ $namespace ][0];
				$type    = $this->rules[ $namespace ][1];

				$pathparts = explode( '/', $path );
				$filename  = array_pop( $pathparts );
				$filepath  = implode( '/', $pathparts );

				$type_prefixes = $this->get_type_prefixes( $type );
				foreach ( $type_prefixes as $type_prefix ) {
					yield $basedir . $filepath . '/' . $type_prefix . $filename . '.php';
				}
			}
		}
	}

	/**
	 * Gets the partial path to use for a partial name.
	 *
	 * @since 1.0.0
	 *
	 * @param string $partial_name Partial name of a class, trait, or interface.
	 * @return string Partial path to the file, relative to the parent namespace.
	 */
	protected function partial_name_to_partial_path( string $partial_name ) : string {
		return str_replace( '_', '-', strtolower( $partial_name ) );
	}

	/**
	 * Gets the type prefixes to check for a given type bitmask.
	 *
	 * @since 1.0.0
	 *
	 * @param int $type Bitmask for whether to consider classes, traits, interfaces
	 *                  or any combination of them.
	 * @return array List of type prefixes.
	 */
	protected function get_type_prefixes( int $type ) : array {
		$available_prefixes = [
			self::TYPE_CLASS     => 'class-',
			self::TYPE_TRAIT     => 'trait-',
			self::TYPE_INTERFACE => 'interface-',
		];

		$prefixes = [];
		foreach ( $available_prefixes as $bitmask => $prefix ) {
			if ( $type & $bitmask ) {
				$prefixes[] = $prefix;
			}
		}

		return $prefixes;
	}
}
