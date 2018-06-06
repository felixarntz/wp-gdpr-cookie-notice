<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Partial interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts;

use WP_Customize_Manager;
use WP_Customize_Partial;

/**
 * Interface for a Customizer partial class.
 *
 * @since 1.0.0
 */
interface Customizer_Partial {

	/**
	 * Type argument name.
	 */
	const ARG_TYPE = 'type';

	/**
	 * Capability argument name.
	 */
	const ARG_CAPABILITY = 'capability';

	/**
	 * Settings argument name.
	 */
	const ARG_SETTINGS = 'settings';

	/**
	 * Selector argument name.
	 */
	const ARG_SELECTOR = 'selector';

	/**
	 * Render callback argument name.
	 */
	const ARG_RENDER_CALLBACK = 'render_callback';

	/**
	 * Container inclusive argument name.
	 */
	const ARG_CONTAINER_INCLUSIVE = 'container_inclusive';

	/**
	 * Fallback refresh argument name.
	 */
	const ARG_FALLBACK_REFRESH = 'fallback_refresh';

	/**
	 * Gets the Customizer partial identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Customizer partial identifier.
	 */
	public function get_id() : string;

	/**
	 * Gets the Customizer partial arguments.
	 *
	 * @since 1.0.0
	 *
	 * @return array Customizer partial arguments.
	 */
	public function get_args() : array;

	/**
	 * Parses Customizer partial arguments including the identifier using a callback.
	 *
	 * @since 1.0.0
	 *
	 * @param callable $parse_callback Parse callback. Must filter the arguments passed and return them.
	 */
	public function parse_args( $parse_callback );

	/**
	 * Maps the partial to a core `WP_Customize_Partial` instance to register.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance where the partial should be registered.
	 * @return WP_Customize_Partial Customize partial to register in WordPress core.
	 */
	public function map( WP_Customize_Manager $wp_customize ) : WP_Customize_Partial;
}
