<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Form interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Notice_Submission_Exception;

/**
 * Interface for a form class.
 *
 * @since 1.0.0
 */
interface Form extends Renderable {

	/**
	 * Handles a form submission.
	 *
	 * @since 1.0.0
	 *
	 * @return array Form submission result data.
	 *
	 * @throws Notice_Submission_Exception Thrown when the submission is invalid.
	 */
	public function handle_submission() : array;

	/**
	 * Checks if the current request is a form submission.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if a submission, false otherwise.
	 */
	public function is_submission() : bool;
}
