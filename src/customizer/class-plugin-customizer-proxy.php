<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Plugin_Customizer_Proxy class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer;

use WP_Customize_Manager;

/**
 * Class wrapping the WordPress Customizer as a plugin-specific proxy.
 *
 * @since 1.0.0
 */
class Plugin_Customizer_Proxy implements Service {

	/**
	 * Identifier for the Customizer panel.
	 */
	const PANEL = 'wp_gdpr_cookie_notice';

	/**
	 * Identifier for the policies Customizer section.
	 */
	const SECTION_POLICIES = 'wp_gdpr_cookie_notice_policies';

	/**
	 * Identifier for the content Customizer section.
	 */
	const SECTION_CONTENT = 'wp_gdpr_cookie_notice_content';

	/**
	 * Identifier for the appearance Customizer section.
	 */
	const SECTION_APPEARANCE = 'wp_gdpr_cookie_notice_appearance';

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
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	public function __construct( WP_Customize_Manager $wp_customize ) {
		$this->wp_customize = $wp_customize;

		$this->add_base_ui();
	}

	/**
	 * Adds a control to the policies section.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 */
	public function add_policies_control( WP_Customize_Control $control ) {
		$control->section = self::SECTION_POLICIES;
		$this->wp_customize->add_control( $control );
	}

	/**
	 * Adds a control to the content section.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 */
	public function add_content_control( WP_Customize_Control $control ) {
		$control->section = self::SECTION_CONTENT;
		$this->wp_customize->add_control( $control );
	}

	/**
	 * Adds a control to the appearance section.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Control $control Control instance.
	 */
	public function add_appearance_control( WP_Customize_Control $control ) {
		$control->section = self::SECTION_APPEARANCE;
		$this->wp_customize->add_control( $control );
	}

	/**
	 * Adds the base UI consisting of the panel and sections.
	 *
	 * @since 1.0.0
	 */
	protected function add_base_ui() {
		$this->wp_customize->add_panel( self::PANEL, [
			'title'       => __( 'Cookie Notice', 'wp-gdpr-cookie-notice' ),
			'description' => __( 'The cookie notice is displayed for visitors who have not specified their cookie preferences yet.', 'wp-gdpr-cookie-notice' ),
			'capability'  => 'manage_options',
		] );

		$this->wp_customize->add_section( self::SECTION_POLICIES, [
			'panel'      => self::PANEL,
			'title'      => _x( 'Policies', 'Customizer section', 'wp-gdpr-cookie-notice' ),
			'capability' => 'manage_options',
		] );

		$this->wp_customize->add_section( self::SECTION_CONTENT, [
			'panel'      => self::PANEL,
			'title'      => _x( 'Content', 'Customizer section', 'wp-gdpr-cookie-notice' ),
			'capability' => 'manage_options',
		] );

		$this->wp_customize->add_section( self::SECTION_APPEARANCE, [
			'panel'      => self::PANEL,
			'title'      => _x( 'Appearance', 'Customizer section', 'wp-gdpr-cookie-notice' ),
			'capability' => 'manage_options',
		] );
	}
}
