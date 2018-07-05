<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Notice;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Form_Aware;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Assets_Aware;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Parser;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Form;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Inline_Asset;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Shortcodes\WordPress_Shortcode_Parser;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Preferences;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;

/**
 * Class representing a cookie notice.
 *
 * @since 1.0.0
 */
class Cookie_Notice implements Notice, Form_Aware, Assets_Aware, Service {

	/**
	 * Identifier for the 'notice_heading' setting.
	 */
	const SETTING_NOTICE_HEADING = 'notice_heading';

	/**
	 * Identifier for the 'notice_content' setting.
	 */
	const SETTING_NOTICE_CONTENT = 'notice_content';

	/**
	 * Context for the cookie notice content.
	 */
	const CONTEXT = 'cookie_notice';

	/**
	 * Cookie preferences.
	 *
	 * @since 1.0.0
	 * @var Cookie_Preferences
	 */
	protected $preferences;

	/**
	 * Shortcode parser.
	 *
	 * @since 1.0.0
	 * @var Shortcode_Parser
	 */
	protected $shortcode_parser;

	/**
	 * Option reader.
	 *
	 * @since 1.0.0
	 * @var Option_Reader
	 */
	protected $options;

	/**
	 * Cookie notice form.
	 *
	 * @since 1.0.0
	 * @var Cookie_Notice_Form
	 */
	protected $form;

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

		$this->preferences      = $preferences;
		$this->shortcode_parser = $shortcode_parser;
		$this->options          = $options;
		$this->form             = new Cookie_Notice_Form( $this, $this->shortcode_parser, $this->options );
		$this->stylesheet       = new Cookie_Notice_Stylesheet( $this->options );
		$this->script           = new Cookie_Notice_Script( $this->options );
	}

	/**
	 * Renders the notice output.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		if ( is_customize_preview() || $this->is_amp() ) {
			$this->render_html();
			return;
		}

		?>
		<script type="text/template" id="wp-gdpr-cookie-notice-template">
			<?php $this->render_html(); ?>
		</script>
		<script type="text/javascript">
			( function() {
				var isGoogleBot = navigator.userAgent && ( -1 !== navigator.userAgent.indexOf( 'Googlebot' ) || -1 !== navigator.userAgent.indexOf( 'Speed Insights' ) );
				var template    = document.querySelector( '#wp-gdpr-cookie-notice-template' );
				var notice      = document.createElement( 'div' );

				if ( isGoogleBot ) {
					return;
				}

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

		$user_agent = filter_input( INPUT_SERVER, 'HTTP_USER_AGENT' );
		if ( ! empty( $user_agent ) && ( false !== strpos( $user_agent, 'Googlebot' ) || false !== strpos( $user_agent, 'Speed Insights' ) ) ) {
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
	 * Enqueues the necessary assets.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets() {
		$action          = current_action();
		$enqueue_scripts = '_enqueue_scripts';

		$prefix = 'wp';
		if ( strpos( $action, $enqueue_scripts ) === strlen( $action ) - strlen( $enqueue_scripts ) ) {
			$prefix = substr( $action, 0, strlen( $action ) - strlen( $enqueue_scripts ) );
		}

		add_action( "{$prefix}_head", array( $this->stylesheet, 'print' ), 1000 );

		if ( is_customize_preview() || $this->is_amp() ) {
			return;
		}

		add_action( "{$prefix}_footer", array( $this->script, 'print' ), 1000 );
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
		$heading = $this->options->get_option( self::SETTING_NOTICE_HEADING );

		if ( empty( $heading ) ) {
			return;
		}

		echo $this->prepare_heading( $heading ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Renders the notice content.
	 *
	 * @since 1.0.0
	 */
	public function render_content() {
		$content = $this->options->get_option( self::SETTING_NOTICE_CONTENT );

		if ( empty( $content ) ) {
			return;
		}

		echo $this->prepare_content( $content ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Renders the notice output HTML only.
	 *
	 * @since 1.0.0
	 */
	protected function render_html() {
		$tag        = 'div';
		$extra_attr = '';

		if ( $this->is_amp() ) {
			$tag        = 'amp-user-notification';
			$extra_attr = ' layout="nodisplay"';
		}

		?>
		<<?php echo $tag; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> id="wp-gdpr-cookie-notice-wrap" class="wp-gdpr-cookie-notice-wrap"<?php echo $extra_attr; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>>
			<div id="wp-gdpr-cookie-notice" class="wp-gdpr-cookie-notice" role="alert" aria-label="<?php esc_attr_e( 'Cookie Consent Notice', 'wp-gdpr-cookie-notice' ); ?>">
				<div class="wp-gdpr-cookie-notice-inner">
					<div class="wp-gdpr-cookie-notice-content-wrap">
						<div class="wp-gdpr-cookie-notice-heading">
							<?php $this->render_heading(); ?>
						</div>
						<div class="wp-gdpr-cookie-notice-content">
							<?php $this->render_content(); ?>
						</div>
					</div>
					<?php $this->form->render(); ?>
				</div>
			</div>
		</<?php echo $tag; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>>
		<?php
	}

	/**
	 * Prepares the notice heading for output.
	 *
	 * @since 1.0.0
	 *
	 * @param string $heading Notice heading.
	 * @return string Prepared notice heading.
	 */
	protected function prepare_heading( string $heading ) : string {

		/**
		 * Filters the heading level to use for the cookie notice heading.
		 *
		 * @since 1.0.0
		 *
		 * @param string $heading_level Must be one of 'h1', 'h2', 'h3', 'h4', 'h5', or 'h6'.
		 *                              Default is 'h2'.
		 */
		$heading_level = strtolower( apply_filters( 'wp_gdpr_cookie_notice_heading_level', 'h2' ) );
		if ( ! in_array( $heading_level, [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ], true ) ) {
			$heading_level = 'h2';
		}

		return '<' . $heading_level . '>' . esc_html( $heading ) . '</' . $heading_level . '>';
	}

	/**
	 * Prepares the notice content for output.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Notice content.
	 * @return string Prepared notice content.
	 */
	protected function prepare_content( string $content ) : string {
		$content = wp_kses( $content, self::CONTEXT );
		$content = $this->shortcode_parser->parse_shortcodes( $content, self::CONTEXT );
		$content = wpautop( $content );

		return $content;
	}

	/**
	 * Checks whether the current request is for an AMP endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if an AMP endpoint, false otherwise.
	 */
	protected function is_amp() {
		return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
	}
}
