<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Notice;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\With_Assets;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Service;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Shortcode_Parser;
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
class Cookie_Notice implements Notice, With_Assets, Service {

	/**
	 * Identifier for the 'notice_heading' setting.
	 */
	const SETTING_NOTICE_HEADING = 'notice_heading';

	/**
	 * Identifier for the 'notice_content' setting.
	 */
	const SETTING_NOTICE_CONTENT = 'notice_content';

	/**
	 * Identifier for the 'submit_text' setting.
	 */
	const SETTING_SUBMIT_TEXT = 'submit_text';

	/**
	 * Identifier for the 'show_toggles' setting.
	 */
	const SETTING_SHOW_TOGGLES = 'show_toggles';

	/**
	 * Identifier for the 'show_learn_more' setting.
	 */
	const SETTING_SHOW_LEARN_MORE = 'show_learn_more';

	/**
	 * Identifier for the 'learn_more_text' setting.
	 */
	const SETTING_LEARN_MORE_TEXT = 'learn_more_text';

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
	 * Option reader.
	 *
	 * @since 1.0.0
	 * @var Option_Reader
	 */
	protected $options;

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
	 * Sets the preferences and option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Cookie_Preferences $preferences Cookie preferences instance.
	 * @param Option_Reader      $options     Optional. Option reader to use.
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
		$this->stylesheet       = new Cookie_Notice_Stylesheet( $this->options );
		$this->script           = new Cookie_Notice_Script( $this->options );
	}

	/**
	 * Renders the notice output.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		?>
		<div class="wp-gdpr-cookie-notice-wrap">
			<div id="wp-gdpr-cookie-notice" class="wp-gdpr-cookie-notice" aria-label="<?php esc_attr_e( 'Cookie Consent Notice', 'wp-gdpr-cookie-notice' ); ?>">
				<form id="wp-gdpr-cookie-notice-form" class="wp-gdpr-cookie-notice-form" method="POST">
					<div class="wp-gdpr-cookie-notice-heading">
						<?php $this->render_heading(); ?>
					</div>
					<div class="wp-gdpr-cookie-notice-content">
						<?php $this->render_content(); ?>
					</div>
					<div class="wp-gdpr-cookie-notice-controls">
						<?php $this->render_controls(); ?>
					</div>
				</form>
			</div>
		</div>
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
		return ! $this->preferences->cookies_accepted();
	}

	/**
	 * Dismisses the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param array $form_data Optional. Additional form data passed while dismissing. Default empty array.
	 */
	public function dismiss( array $form_data = [] ) {
		$cookie_type_values = $this->preferences->get_cookie_types()->get_values();

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
	 */
	public function enqueue_assets() {
		$action          = current_action();
		$enqueue_scripts = '_enqueue_scripts';

		$prefix = 'wp';
		if ( strpos( $action, $enqueue_scripts ) === strlen( $action ) - strlen( $enqueue_scripts ) ) {
			$prefix = substr( $action, 0, strlen( $action ) - strlen( $enqueue_scripts ) );
		}

		add_action( "{$prefix}_head", array( $this->stylesheet, 'print' ), 1000 );

		if ( is_customize_preview() ) {
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
	 * Renders the notice controls.
	 *
	 * @since 1.0.0
	 */
	public function render_controls() {
		$options = $this->options->get_options();

		if ( ! empty( $options[ self::SETTING_SHOW_TOGGLES ] ) ) {
			$this->render_toggles();
		} else {
			$this->render_hidden_toggles();
		}

		if ( ! empty( $options[ self::SETTING_SHOW_LEARN_MORE ] ) ) {
			$this->render_learn_more_link( $options[ self::SETTING_LEARN_MORE_TEXT ] );
		}

		$this->render_submit_button( $options[ self::SETTING_SUBMIT_TEXT ] );
	}

	/**
	 * Renders the notice toggle checkboxes for granular cookie control.
	 *
	 * @since 1.0.0
	 */
	protected function render_toggles() {
		$cookie_type_labels = $this->preferences->get_cookie_types()->get_labels();

		?>
		<fieldset class="wp-gdpr-cookie-notice-toggles">
			<legend class="screen-reader-text"><?php esc_html_e( 'Granular Cookie Control', 'wp-gdpr-cookie-notice' ); ?></legend>
			<?php
			$first = true;
			foreach ( $cookie_type_labels as $value => $label ) {
				$id = 'wp-gdpr-cookie-notice-toggle-' . $value;

				?>
				<div class="wp-gdpr-cookie-notice-toggle">
					<?php
					if ( $first ) {
						?>
						<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $value ); ?>" value="1" checked readonly required>
						<?php
					} else {
						?>
						<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $value ); ?>" value="1" checked>
						<?php
					}
					?>
					<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
				</div>
				<?php

				$first = false;
			}
			?>
		</fieldset>
		<?php
	}

	/**
	 * Renders the notice toggles as hidden controls.
	 *
	 * @since 1.0.0
	 */
	protected function render_hidden_toggles() {
		$cookie_type_values = $this->preferences->get_cookie_types()->get_values();

		foreach ( $cookie_type_values as $value ) {
			?>
			<input type="hidden" name="<?php echo esc_attr( $value ); ?>" value="1">
			<?php
		}
	}

	/**
	 * Renders the Learn More link for the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param string $button_text Text to use for the link anchor.
	 */
	protected function render_learn_more_link( string $link_text ) {
		$link = $this->shortcode_parser->parse_shortcodes( '[cookie_policy_link text="' . $link_text . '" show_if_empty="0"]', self::CONTEXT );
		if ( empty( $link ) ) {
			return;
		}

		?>
		<div class="wp-gdpr-cookie-notice-learn-more">
			<?php echo $link; /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
		</div>
		<?php
	}

	/**
	 * Renders the submit button for the notice.
	 *
	 * @since 1.0.0
	 *
	 * @param string $button_text Text to use for the button.
	 */
	protected function render_submit_button( string $button_text ) {
		?>
		<div class="wp-gdpr-cookie-notice-submit">
			<button type="submit" class="wp-gdpr-cookie-notice-button"><?php echo esc_html( $button_text ); ?></button>
		</div>
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
	protected function prepare_heading( string $heading ) : string

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
}
