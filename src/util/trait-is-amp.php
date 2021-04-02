<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Util\Is_AMP trait
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Util;

/**
 * Trait for checking whether the current context is AMP.
 *
 * @since 1.0.0
 */
trait Is_AMP {

	/**
	 * Checks whether the current request is for an AMP endpoint.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if an AMP endpoint, false otherwise.
	 */
	protected function is_amp() {
		if ( $this->is_amp_story() ) {
			return true;
		}

		return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
	}

	/**
	 * Checks whether the current request is for an AMP story
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if an AMP story, false otherwise.
	 */
	protected function is_amp_story() {
		return is_singular( 'web-story' );
	}
}
