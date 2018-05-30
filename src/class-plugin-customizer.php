<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Plugin_Customizer class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Customizer;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Customizer\Plugin_Customizer_Proxy;
use WP_Customize_Manager;

/**
 * Class for registering plugin Customizer UI.
 *
 * @since 1.0.0
 */
class Plugin_Customizer implements Integration {

	/**
	 * Identifier for the Customizer panel.
	 */
	const PANEL = 'settings';

	/**
	 * Identifier for the policies Customizer section.
	 */
	const SECTION_POLICIES = 'policies';

	/**
	 * Identifier for the content Customizer section.
	 */
	const SECTION_CONTENT = 'content';

	/**
	 * Identifier for the appearance Customizer section.
	 */
	const SECTION_APPEARANCE = 'appearance';

	/**
	 * Option reader to manage for the plugin's settings.
	 *
	 * @since 1.0.0
	 * @var Option_Reader
	 */
	protected $option_reader;

	/**
	 * Constructor.
	 *
	 * Sets the option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Option_Reader $option_reader Optional. Option reader to use.
	 */
	public function __construct( Option_Reader $option_reader = null ) {
		$this->option_reader = $option_reader;
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		add_action( 'customize_register', [ $this, 'register_customizer_ui' ], 10, 1 );
	}

	/**
	 * Registers plugin settings.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 */
	public function register_customizer_ui( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_panel( $this->prefix_id( self::PANEL ), [
			'title'       => __( 'Cookie Notice', 'wp-gdpr-cookie-notice' ),
			'description' => __( 'The cookie notice is displayed for visitors who have not specified their cookie preferences yet.', 'wp-gdpr-cookie-notice' ),
			'capability'  => 'manage_options',
		] );

		$this->register_customizer_section( $wp_customize, self::SECTION_POLICIES, [
			'title' => _x( 'Policies', 'Customizer section', 'wp-gdpr-cookie-notice' ),
		] );

		$this->register_customizer_section( $wp_customize, self::SECTION_CONTENT, [
			'title' => _x( 'Content', 'Customizer section', 'wp-gdpr-cookie-notice' ),
		] );

		$this->register_customizer_section( $wp_customize, self::SECTION_APPEARANCE, [
			'title' => _x( 'Appearance', 'Customizer section', 'wp-gdpr-cookie-notice' ),
		] );
	}

	/**
	 * Registers a Customizer section.
	 *
	 * An action is fired inside this method for adding controls to the section.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer instance.
	 * @param string               $section_id   Section identifier.
	 * @param array                $section_args Optional. Section arguments. Default empty array.
	 */
	public function register_customizer_section( WP_Customize_Manager $wp_customize, string $section_id, array $section_args = [] ) {
		$section_args = wp_parse_args( $section_args, [
			'panel'      => $this->prefix_id( self::PANEL ),
			'capability' => 'manage_options',
		] );

		$wp_customize->add_section( $this->prefix_id( $section_id ), $section_args );

		/**
		 * Fires when the plugin's Customizer controls a given section are added.
		 *
		 * The dynamic portion of the hook name, {$section_id} refers to the section identifier.
		 * It can be either 'policies', 'content', or 'appearance'.
		 *
		 * @since 1.0.0
		 *
		 * @param Customizer $customizer Customizer instance.
		 */
		do_action( "wp_gdpr_cookie_notice_add_customizer_{$section_id}_controls", new Plugin_Customizer_Proxy( $this->prefix_id( $section_id ), $this->option_reader->get_setting_id(), $wp_customize ) );
	}

	/**
	 * Prefixes an identifier with the plugin base prefix.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Identifier.
	 * @return string Prefixed identifier.
	 */
	private function prefix_id( string $id ) : string {
		return $this->option_reader->get_setting_id() . '_' . $id;
	}
}
