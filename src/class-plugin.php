<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Plugin class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Service_Container;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Service;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Data\WordPress_Option_Data_Repository;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Data\Cookie_Data_Repository;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Registry;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Parser_Registry;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Policy_Page;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Privacy_Policy_Page;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Preferences;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Integrations\WordPress_Cookie_Integration_Registry;

/**
 * Class controlling the plugin functionality.
 *
 * @since 1.0.0
 */
class Plugin implements Integration {

	/**
	 * Plugin main file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $main_file;

	/**
	 * Container for plugin's services.
	 *
	 * @since 1.0.0
	 * @var Service_Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * Sets the plugin service container.
	 *
	 * @since 1.0.0
	 *
	 * @param string            $main_file Plugin main file.
	 * @param Service_Container $container Optional. Service container to use. Default is the regular
	 *                                     plugin service container.
	 */
	public function __construct( string $main_file, Service_Container $container = null ) {
		if ( null === $container ) {
			$container = new Plugin_Service_Container();
		}

		$this->main_file = $main_file;
		$this->container = $container;

		$option_reader        = new Plugin_Option_Reader( new WordPress_Option_Data_Repository() );
		$shortcode_registry   = new WordPress_Shortcode_Parser_Registry( new WordPress_Shortcode_Registry() );
		$cookie_policy_page   = new Cookie_Policy_Page( $option_reader );
		$privacy_policy_page  = new Privacy_Policy_Page();
		$cookie_preferences   = new Cookie_Preferences( new Cookie_Data_Repository(), $cookie_policy_page, $privacy_policy_page );
		$cookie_notice        = new Cookie_Notice( $cookie_preferences, $shortcode_registry, $option_reader );
		$integration_registry = new WordPress_Cookie_Integration_Registry( $cookie_preferences, $option_reader );

		$this->container->add( 'options', $option_reader );
		$this->container->add( 'shortcodes', $shortcode_registry );
		$this->container->add( 'privacy_policy_page', $privacy_policy_page );
		$this->container->add( 'cookie_policy_page', $cookie_policy_page );
		$this->container->add( 'cookie_preferences', $cookie_preferences );
		$this->container->add( 'cookie_notice', $cookie_notice );
		$this->container->add( 'cookie_integrations', $integration_registry );
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		$option_reader        = $this->container->get( 'options' );
		$shortcode_registry   = $this->container->get( 'shortcodes' );
		$cookie_notice        = $this->container->get( 'cookie_notice' );
		$integration_registry = $this->container->get( 'cookie_integrations' );

		$integrations = [
			new Plugin_Settings( $option_reader ),
			new Plugin_Customizer( $option_reader ),
			new Plugin_Policies_Settings( $option_reader ),
			new Plugin_Content_Settings( $cookie_notice ),
			new Plugin_Appearance_Settings( $cookie_notice ),
			new Plugin_Integrations_Settings( $integration_registry ),
			new Plugin_Shortcodes( $shortcode_registry ),
			new Plugin_Notice_Controller( $cookie_notice ),
			new Plugin_Cookie_Integrations( $integration_registry ),
		];

		array_walk( $integrations, function( Integration $integration ) {
			$integration->add_hooks();
		} );

		$this->add_settings_menu_customize_link();
		$this->add_plugin_row_customize_link();
	}

	/**
	 * Retrieves a plugin service.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id Unique identifier of the service.
	 * @return Service Service instance.
	 */
	public function get_service( string $id ) : Service {
		return $this->container->get( $id );
	}

	/**
	 * Adds a link to customize the cookie notice to the settings menu.
	 *
	 * @since 1.0.0
	 */
	protected function add_settings_menu_customize_link() {
		add_action( 'admin_menu', function() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$title = __( 'Cookie Notice', 'wp-gdpr-cookie-notice' );

			add_options_page( $title, $title, 'customize', 'customize.php?autofocus[panel]=' . $this->container->get( 'options' )->get_setting_id() . '_' . Plugin_Customizer::PANEL );
		}, 5, 0 );
	}

	/**
	 * Adds a link to customize the cookie notice to the plugin row.
	 *
	 * @since 1.0.0
	 */
	protected function add_plugin_row_customize_link() {
		$plugin_file = plugin_basename( $this->main_file );

		add_filter( "plugin_action_links_{$plugin_file}", function( $actions ) {
			if ( current_user_can( 'customize' ) && current_user_can( 'manage_options' ) ) {
				$url = add_query_arg(
					'autofocus[panel]',
					$this->container->get( 'options' )->get_setting_id() . '_' . Plugin_Customizer::PANEL,
					admin_url( 'customize.php' )
				);

				array_unshift( $actions, '<a href="' . esc_url( $url ) . '">' . esc_html_x( 'Customize', 'plugin action link', 'wp-gdpr-cookie-notice' ) . '</a>' );
			}

			return $actions;
		}, 10, 1 );
	}
}
