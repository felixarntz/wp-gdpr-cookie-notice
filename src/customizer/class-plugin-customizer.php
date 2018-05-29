<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Plugin_Customizer class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer;
use WP_Customize_Manager;

/**
 * Class for registering plugin Customizer UI.
 *
 * @since 1.0.0
 */
class Plugin_Customizer implements Service {

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
	 * Initializes the class functionality.
	 *
	 * @since 1.0.0
	 */
	public function initialize() {
		add_action( 'customize_register', function( $wp_customize ) {
			$this->register_customizer_ui( $wp_customize );
		}, 10, 1 );
	}

	/**
	 * Registers plugin settings.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	protected function register_customizer_ui( WP_Customize_Manager $wp_customize ) {

		$wp_customize->add_panel( self::PANEL, [
			'title'       => __( 'Cookie Notice', 'wp-gdpr-cookie-notice' ),
			'description' => __( 'The cookie notice is displayed for visitors who have not specified their cookie preferences yet.', 'wp-gdpr-cookie-notice' ),
			'capability'  => 'manage_options',
		] );

		$wp_customize->add_section( self::SECTION_POLICIES, [
			'panel'      => self::PANEL,
			'title'      => _x( 'Policies', 'Customizer section', 'wp-gdpr-cookie-notice' ),
			'capability' => 'manage_options',
		] );

		$wp_customize->add_section( self::SECTION_CONTENT, [
			'panel'      => self::PANEL,
			'title'      => _x( 'Content', 'Customizer section', 'wp-gdpr-cookie-notice' ),
			'capability' => 'manage_options',
		] );

		$wp_customize->add_section( self::SECTION_APPEARANCE, [
			'panel'      => self::PANEL,
			'title'      => _x( 'Appearance', 'Customizer section', 'wp-gdpr-cookie-notice' ),
			'capability' => 'manage_options',
		] );

		/**
		 * Fires when the plugin's Customizer controls for the 'Policies' section are added.
		 *
		 * @since 1.0.0
		 *
		 * @param Customizer $customizer Customizer instance.
		 */
		do_action( 'wp_gdpr_cookie_notice_add_customizer_policies_controls', new Plugin_Customizer_Proxy( self::SECTION_POLICIES, $wp_customize ) );

		/**
		 * Fires when the plugin's Customizer controls for the 'Content' section are added.
		 *
		 * @since 1.0.0
		 *
		 * @param Customizer $customizer Customizer instance.
		 */
		do_action( 'wp_gdpr_cookie_notice_add_customizer_content_controls', new Plugin_Customizer_Proxy( self::SECTION_CONTENT, $wp_customize ) );

		/**
		 * Fires when the plugin's Customizer controls for the 'Appearance' section are added.
		 *
		 * @since 1.0.0
		 *
		 * @param Customizer $customizer Customizer instance.
		 */
		do_action( 'wp_gdpr_cookie_notice_add_customizer_appearance_controls', new Plugin_Customizer_Proxy( self::SECTION_APPEARANCE, $wp_customize ) );
	}
}
