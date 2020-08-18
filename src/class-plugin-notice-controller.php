<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Plugin_Notice_Controller class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice;

use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Integration;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Notice;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Form_Aware;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Assets_Aware;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Exceptions\Notice_Submission_Exception;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Form_Markup;
use Felix_Arntz\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_AMP_Markup;

/**
 * Class for controlling the cookie notice.
 *
 * @since 1.0.0
 */
class Plugin_Notice_Controller implements Integration {

	/**
	 * Notice instance to control.
	 *
	 * @since 1.0.0
	 * @var Notice
	 */
	protected $notice;

	/**
	 * Constructor.
	 *
	 * Sets the notice to control.
	 *
	 * @since 1.0.0
	 *
	 * @param Notice $notice Notice to control.
	 */
	public function __construct( Notice $notice ) {
		$this->notice = $notice;
	}

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 */
	public function add_hooks() {
		add_action( 'wp_loaded', [ $this, 'load_notice' ], 1000, 0 );

		if ( ! $this->notice instanceof Form_Aware ) {
			return;
		}

		add_action( 'wp_loaded', [ $this, 'handle_notice_submission_request' ], 100, 0 );
		add_action( 'wp_ajax_' . Cookie_Notice_Form_Markup::ACTION, [ $this, 'handle_notice_submission_ajax' ], 10, 0 );
		add_action( 'wp_ajax_nopriv_' . Cookie_Notice_Form_Markup::ACTION, [ $this, 'handle_notice_submission_ajax' ], 10, 0 );

		add_action( 'wp_ajax_' . Cookie_Notice_AMP_Markup::AMP_CHECK_CONSENT_HREF_ACTION, [ $this, 'handle_amp_check_consent_href_ajax' ], 10, 0 );
		add_action( 'wp_ajax_nopriv_' . Cookie_Notice_AMP_Markup::AMP_CHECK_CONSENT_HREF_ACTION, [ $this, 'handle_amp_check_consent_href_ajax' ], 10, 0 );
	}

	/**
	 * Adds hooks to load the notice as necessary.
	 *
	 * @since 1.0.0
	 */
	public function load_notice() {
		add_action( 'wp_footer', [ $this->notice, 'render' ], 100, 0 );
		add_action( 'login_footer', [ $this->notice, 'render' ], 100, 0 );

		if ( ! $this->notice instanceof Assets_Aware ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', [ $this->notice, 'enqueue_assets' ], 100, 0 );
		add_action( 'login_enqueue_scripts', [ $this->notice, 'enqueue_assets' ], 100, 0 );
	}

	/**
	 * Handles a notice submission as necessary.
	 *
	 * @since 1.0.0
	 */
	public function handle_notice_submission_request() {
		if ( wp_doing_ajax() ) {
			return;
		}

		$form = $this->notice->get_form();

		if ( ! $form->is_submission() ) {
			return;
		}

		try {
			$form->handle_submission();
		} catch ( Notice_Submission_Exception $e ) {
			wp_die( esc_html( $e->getMessage() ), esc_html__( 'Cookie Notice Error', 'wp-gdpr-cookie-notice' ), 400 );
		}
	}

	/**
	 * Handles a notice submission via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function handle_notice_submission_ajax() {
		$form = $this->notice->get_form();

		if ( ! $form->is_submission() ) {
			wp_send_json_error( [ 'message' => __( 'No form submission detected.', 'wp-gdpr-cookie-notice' ) ], 400 );
		}

		try {
			$result = $form->handle_submission();
		} catch ( Notice_Submission_Exception $e ) {
			wp_send_json_error( [ 'message' => $e->getMessage() ], 400 );
		}

		wp_send_json_success( $result, 200 );
	}

	/**
	 * Handles the checkConsentHref request made by an <amp-consent> via AJAX.
	 *
	 * @since 1.0.0
	 */
	public function handle_amp_check_consent_href_ajax() {
		$active = false;

		$payload = file_get_contents( 'php://input' ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsRemoteFile
		if ( ! empty( $payload ) ) {
			$payload = json_decode( $payload, true );
			if ( ! empty( $payload['consentInstanceId'] ) && Cookie_Notice_AMP_Markup::AMP_INSTANCE === $payload['consentInstanceId'] ) {
				$active = $this->notice->is_active();
			}
		}

		wp_send_json( [ 'consentRequired' => $active ], 200 );
	}
}
