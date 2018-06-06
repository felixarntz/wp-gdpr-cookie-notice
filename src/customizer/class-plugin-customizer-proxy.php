<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Plugin_Customizer_Proxy class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Control;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer_Partial;
use WP_Customize_Manager;

/**
 * Class wrapping the WordPress Customizer as a plugin-specific proxy.
 *
 * @since 1.0.0
 */
class Plugin_Customizer_Proxy implements Customizer {

	/**
	 * Customizer section identifier to use for controls.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $section;

	/**
	 * Base setting identifier to use for controls.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $setting_id;

	/**
	 * Core Customizer instance.
	 *
	 * @since 1.0.0
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	/**
	 * Constructor.
	 *
	 * Sets the core Customizer instance.
	 *
	 * @since 1.0.0
	 *
	 * @param string               $section      Customizer section identifier to use for controls.
	 * @param string               $setting_id   Base setting identifier to use for controls.
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	public function __construct( string $section, string $setting_id, WP_Customize_Manager $wp_customize ) {
		$this->section      = $section;
		$this->setting_id   = $setting_id;
		$this->wp_customize = $wp_customize;
	}

	/**
	 * Adds a control to the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param Customizer_Control $control Control instance.
	 */
	public function add_control( Customizer_Control $control ) {
		$control->parse_args( function( $args ) {
			$args['id']                                 = $this->prefix_setting_name( $args['id'] );
			$args[ Customizer_Control::ARG_SECTION ]    = $this->section;
			$args[ Customizer_Control::ARG_CAPABILITY ] = 'manage_options';

			return $args;
		} );

		$this->wp_customize->add_control( $control->map( $this->wp_customize ) );
	}

	/**
	 * Adds a partial to the Customizer.
	 *
	 * @since 1.0.0
	 *
	 * @param Customizer_Partial $partial Control instance.
	 */
	public function add_partial( Customizer_Partial $partial ) {
		$partial->parse_args( function( $args ) {
			$args['id']                                 = $this->prefix_setting_name( $args['id'] );
			$args[ Customizer_Partial::ARG_CAPABILITY ] = 'manage_options';

			if ( ! empty( $args[ Customizer_Partial::ARG_SETTINGS ] ) ) {
				$args[ Customizer_Partial::ARG_SETTINGS ] = array_map( [ $this, 'prefix_setting_name' ], $args[ Customizer_Partial::ARG_SETTINGS ] );
			}

			return $args;
		} );

		$this->wp_customize->selective_refresh->add_partial( $partial->map( $this->wp_customize ) );
	}

	/**
	 * Prefixes a setting name with the plugin base prefix.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name Setting name.
	 * @return string Prefixed setting name.
	 */
	private function prefix_setting_name( string $name ) : string {
		return $this->setting_id . '[' . $name . ']';
	}
}
