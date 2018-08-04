<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations\AMP_Block_On_Consent_Sanitizer class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Integrations;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Control\Cookie_Type_Enum;
use AMP_Base_Sanitizer;
use DOMElement;

/**
 * AMP sanitizer class adding `data-block-on-consent` attributes as necessary.
 *
 * @since 1.0.0
 */
class AMP_Block_On_Consent_Sanitizer extends AMP_Base_Sanitizer {

	/**
	 * Tags to prevent from building before cookie consent has been accepted.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $tags = [
		// Ads & analytics.
		'amp-ad-exit'               => Cookie_Type_Enum::TYPE_MARKETING,
		'amp-ad'                    => Cookie_Type_Enum::TYPE_MARKETING,
		'amp-analytics'             => Cookie_Type_Enum::TYPE_ANALYTICS,
		'amp-auto-ads'              => Cookie_Type_Enum::TYPE_MARKETING,
		'amp-call-tracking'         => Cookie_Type_Enum::TYPE_ANALYTICS,
		'amp-experiment'            => Cookie_Type_Enum::TYPE_ANALYTICS,
		'amp-pixel'                 => Cookie_Type_Enum::TYPE_ANALYTICS,
		'amp-sticky-ad'             => Cookie_Type_Enum::TYPE_MARKETING,
		// Dynamic content.
		'amp-google-document-embed' => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-gist'                  => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		// Media.
		'amp-brightcove'            => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-dailymotion'           => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-hulu'                  => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-soundcloud'            => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-vimeo'                 => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-youtube'               => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		// Social.
		'amp-addthis'               => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-beopinion'             => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-facebook-comments'     => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-facebook-like'         => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-facebook-page'         => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-facebook'              => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-gfycat'                => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-instagram'             => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-pinterest'             => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-reddit'                => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-riddle-quiz'           => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-social-share'          => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-twitter'               => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-vine'                  => Cookie_Type_Enum::TYPE_FUNCTIONAL,
		'amp-vk'                    => Cookie_Type_Enum::TYPE_FUNCTIONAL,
	];

	/**
	 * Sanitize the HTML contained in the DOMDocument received by the constructor.
	 *
	 * @since 1.0.0
	 */
	public function sanitize() {
		foreach ( $this->tags as $tagname => $consent_cookie_type ) {
			$nodes = $this->dom->getElementsByTagName( $tagname );

			for ( $i = 0; $i < $nodes->length; $i++ ) {
				$node = $nodes->item( $i );
				if ( ! $node instanceof DOMElement ) {
					continue;
				}

				// Managing with specific cookie types will be supported in the future.
				$node->setAttribute( 'data-block-on-consent', '' );
			}
		}
	}
}
