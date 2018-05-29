<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Plugin_Customizer_Proxy class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer;
use WP_Customize_Manager;
use WP_Customize_Control;

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
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	public function __construct( string $section, WP_Customize_Manager $wp_customize ) {
		$this->section      = $section;
		$this->wp_customize = $wp_customize;
	}

	/**
	 * Adds a control to the Customizer..
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 */
	public function add_control( WP_Customize_Control $control ) {
		$control->section    = $this->section;
		$control->capability = 'manage_options';

		$this->wp_customize->add_control( $control );
	}
}
