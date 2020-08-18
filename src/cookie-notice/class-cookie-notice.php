<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Notice;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Form_Aware;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Assets_Aware;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Service;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Parser;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Form;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Renderable;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Inline_Asset;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Util\Is_AMP;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Parser;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Preferences;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Class representing a cookie notice.
 *
 * @since 1.0.0
 */
class Cookie_Notice implements Notice, Form_Aware, Assets_Aware, Service {

	use Is_AMP;

	/**
	 * Cookie preferences.
	 *
	 * @since 1.0.0
	 * @var Cookie_Preferences
	 */
	protected $preferences;

	/**
	 * Cookie notice form.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Form
	 */
	protected $form;

	/**
	 * Cookie notice markup.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Markup
	 */
	protected $markup;

	/**
	 * Cookie notice markup in AMP.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_AMP_Markup
	 */
	protected $amp_markup;

	/**
	 * Cookie notice stylesheet.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Stylesheet
	 */
	protected $stylesheet;

	/**
	 * Cookie notice script.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Script
	 */
	protected $script;

	/**
	 * Cookie notice script utilities.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Script_Utils
	 */
	protected $script_utils;

	/**
	 * Constructor.
	 *
	 * Sets the preferences, shortcode parser and option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Cookie_Preferences $preferences      Cookie preferences instance.
	 * @param Shortcode_Parser   $shortcode_parser Optional. Shortcode parser to use.
	 * @param Option_Reader      $options          Optional. Option reader to use.
	 */
	public function __construct( Cookie_Preferences $preferences, Shortcode_Parser $shortcode_parser = null, Option_Reader $options = null ) {
		if ( null === $shortcode_parser ) {
			$shortcode_parser = new WordPress_Shortcode_Parser();
		}

		if ( null === $options ) {
			$options = new Plugin_Option_Reader();
		}

		$this->preferences  = $preferences;
		$this->form         = new Cookie_Notice_Form( $this, $shortcode_parser, $options );
		$this->markup       = new Cookie_Notice_Markup( $this->form, $shortcode_parser, $options );
		$this->amp_markup   = new Cookie_Notice_AMP_Markup( $this->form, $shortcode_parser, $options );
		$this->stylesheet   = new Cookie_Notice_Stylesheet( $options );
		$this->script       = new Cookie_Notice_Script( $options );
		$this->script_utils = new Cookie_Notice_Script_Utils( $this->preferences );
	}

	/**
	 * Renders the output.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		if ( is_customize_preview() || $this->is_amp() ) {
			$this->get_markup()->render();
			return;
		}

		// The following script ensures the notice is only inserted into the page as necessary.
		?>
		<script type="text/template" id="wp-gdpr-cookie-notice-template">
			<?php $this->get_markup()->render(); ?>
		</script>
		<script type="text/javascript">
			( function() {
				var template, notice;

				if ( ! wpGdprCookieNoticeUtils.isNoticeActive() ) {
					return;
				}

				template = document.querySelector( '#wp-gdpr-cookie-notice-template' );
				notice   = document.createElement( 'div' );

				notice.innerHTML = template.textContent;
				notice           = notice.firstElementChild;

				template.parentNode.insertBefore( notice, template );
			})();
		</script>
		<?php
	}

	/**
	 * Checks whether the notice is active and should be rendered.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the notice is active, false otherwise.
	 */
	public function is_active() : bool {
		if ( $this->preferences->cookies_accepted() ) {
			return false;
		}

		$user_agent = filter_input( INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING );
		if ( ! empty( $user_agent ) &&
			(
				false !== strpos( $user_agent, 'Googlebot' ) ||
				false !== strpos( $user_agent, 'Speed Insights' ) ||
				false !== strpos( $user_agent, 'Chrome-Lighthouse' )
			)
		) {
			return false;
		}

		return true;
	}

	/**
	 * Dismisses the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Optional. Additional form data passed while dismissing. Default empty array.
	 */
	public function dismiss( array $form_data = [] ) {
		$cookie_type_values = ( new Cookie_Type_Enum() )->get_values();

		$preferences = [];
		foreach ( $cookie_type_values as $value ) {
			$preferences[ $value ] = ! empty( $form_data[ $value ] );
		}

		$this->preferences->set_preferences( $preferences );
	}

	/**
	 * Restores the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Optional. Additional form data passed while dismissing. Default empty array.
	 */
	public function restore( array $form_data = [] ) {
		$this->preferences->reset_preferences();
	}

	/**
	 * Enqueues the necessary assets.
	 *
	 * @since 1.0.0
	 *
	 * @global \WP_Query $wp_query WordPress post query object.
	 */
	public function enqueue_assets() {
		global $wp_query;

		// Skip assets when printing the service worker since they aren't enqueued but printed inline.
		if ( class_exists( 'WP_Service_Workers' ) && isset( $wp_query ) && $wp_query->is_main_query() && $wp_query->get( \WP_Service_Workers::QUERY_VAR ) ) {
			return;
		}

		$action          = current_action();
		$enqueue_scripts = '_enqueue_scripts';

		$prefix = 'wp';
		if ( strpos( $action, $enqueue_scripts ) === strlen( $action ) - strlen( $enqueue_scripts ) ) {
			$prefix = substr( $action, 0, strlen( $action ) - strlen( $enqueue_scripts ) );
		}

		add_action( "{$prefix}_head", [ $this->stylesheet, 'print' ], 1000 );

		if ( $this->is_amp() ) {
			return;
		}

		// Print the utilities script immediately so that it is available early.
		$this->script_utils->print();

		if ( is_customize_preview() ) {
			return;
		}

		add_action( "{$prefix}_footer", [ $this->script, 'print' ], 1000 );
	}

	/**
	 * Gets the notice form instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Form Form instance.
	 */
	public function get_form() : Form {
		return $this->form;
	}

	/**
	 * Gets the notice inline stylesheet instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Inline_Asset Inline stylesheet instance.
	 */
	public function get_stylesheet() : Inline_Asset {
		return $this->stylesheet;
	}

	/**
	 * Gets the notice inline script instance.
	 *
	 * @since 1.0.0
	 *
	 * @return Inline_Asset Inline script instance.
	 */
	public function get_script() : Inline_Asset {
		return $this->script;
	}

	/**
	 * Renders the notice heading.
	 *
	 * @since 1.0.0
	 */
	public function render_heading() {
		$this->get_markup()->render_heading();
	}

	/**
	 * Renders the notice content.
	 *
	 * @since 1.0.0
	 */
	public function render_content() {
		$this->get_markup()->render_content();
	}

	/**
	 * Gets the notice markup instance to use.
	 *
	 * @since 1.0.0
	 *
	 * @return Renderable Markup instance.
	 */
	protected function get_markup() : Renderable {

		// In the Customizer, do not display notice as <amp-consent> tag because it will not show up when already accepted.
		if ( $this->is_amp() && ! is_customize_preview() ) {
			return $this->amp_markup;
		}

		return $this->markup;
	}
}
