<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_AMP_Story_Markup class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Policy_Page;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Control\Privacy_Policy_Page;

/**
 * Class responsible for rendering cookie notice markup in AMP.
 *
 * @since 1.0.0
 */
class Cookie_Notice_AMP_Story_Markup extends Cookie_Notice_AMP_Markup {

	/**
	 * Renders the output.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		$this->render_opening_tag();

		?>
			<amp-story-consent id="wp-gdpr-cookie-notice" layout="nodisplay">
				<script type="application/json">
					<?php echo wp_json_encode( $this->get_story_consent_data() ); ?>
				</script>
			</amp-story-consent>
		<?php

		$this->render_closing_tag();
	}

	/**
	 * Prepares the notice heading for output.
	 *
	 * Overrides the base implementation since stories do not allow HTML in the heading.
	 *
	 * @since 1.0.0
	 *
	 * @param string $heading Notice heading.
	 * @return string Prepared notice heading.
	 */
	protected function prepare_heading( string $heading ) : string {
		return wp_strip_all_tags( $heading );
	}

	/**
	 * Prepares the notice content for output.
	 *
	 * Overrides the base implementation since stories do not allow HTML in the content.
	 *
	 * @since 1.0.0
	 *
	 * @param string $content Notice content.
	 * @return string Prepared notice content.
	 */
	protected function prepare_content( string $content ) : string {
		$content = wp_kses( $content, self::CONTEXT );
		$content = $this->shortcode_parser->parse_shortcodes( $content, self::CONTEXT );
		$content = wp_strip_all_tags( $content );

		return $content;
	}

	/**
	 * Gets data to add to `<amp-story-consent>` as JSON.
	 *
	 * This is a different use-case than `<amp-consent>`, which is still used for AMP stories as well.
	 * `<amp-story-consent>` actually renders the UI based on parameters (instead of allowing for manual HTML), i.e.
	 * the data returned here configures that UI.
	 *
	 * @since 1.0.0
	 * @link https://amp.dev/documentation/components/amp-consent/#prompt-ui-for-stories
	 *
	 * @return array Data to pass to the `<amp-story-consent>` element.
	 */
	protected function get_story_consent_data() {
		$heading = $this->get_heading();
		if ( ! empty( $heading ) ) {
			$heading = $this->prepare_heading( $heading );
		}
		$content = $this->get_content();
		if ( ! empty( $content ) ) {
			$content = $this->prepare_content( $content );
		}

		$story_consent_data = [
			'title'      => $heading,
			'message'    => $content,
			'vendors'    => array_values( ( new Cookie_Type_Enum() )->get_labels() ),
			'onlyAccept' => true,
		];

		$policy_title = __( 'Cookie Policy', 'wp-gdpr-cookie-notice' );
		$policy_url   = ( new Cookie_Policy_Page( $this->options ) )->get_url();
		if ( empty( $policy_url ) ) {
			$policy_title = __( 'Privacy Policy', 'wp-gdpr-cookie-notice' );
			$policy_url   = ( new Privacy_Policy_Page( $this->options ) )->get_url();
		}
		if ( ! empty( $policy_url ) ) {
			$story_consent_data['externalLink'] = [
				'title' => $policy_title,
				'href'  => $policy_url,
			];
		}

		return $story_consent_data;
	}
}
