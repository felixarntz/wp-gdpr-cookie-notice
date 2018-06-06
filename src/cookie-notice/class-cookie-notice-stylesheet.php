<?php
/**
 * Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice\Cookie_Notice_Stylesheet class
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Leaves_And_Love\WP_GDPR_Cookie_Notice\Cookie_Notice;

use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Inline_Asset;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Contracts\Option_Reader;
use Leaves_And_Love\WP_GDPR_Cookie_Notice\Settings\Plugin_Option_Reader;

/**
 * Class representing a cookie notice stylesheet.
 *
 * @since 1.0.0
 */
class Cookie_Notice_Stylesheet implements Inline_Asset {

	/**
	 * Identifier for the 'position' setting.
	 */
	const SETTING_POSITION = 'position';

	/**
	 * Identifier for the 'text_color' setting.
	 */
	const SETTING_TEXT_COLOR = 'text_color';

	/**
	 * Identifier for the 'link_color' setting.
	 */
	const SETTING_LINK_COLOR = 'link_color';

	/**
	 * Identifier for the 'background_color' setting.
	 */
	const SETTING_BACKGROUND_COLOR = 'background_color';

	/**
	 * Identifier for the 'border_width' setting.
	 */
	const SETTING_BORDER_WIDTH = 'border_width';

	/**
	 * Identifier for the 'border_color' setting.
	 */
	const SETTING_BORDER_COLOR = 'border_color';

	/**
	 * Identifier for the 'show_drop_shadow' setting.
	 */
	const SETTING_SHOW_DROP_SHADOW = 'show_drop_shadow';

	/**
	 * Identifier for the 'button_size' setting.
	 */
	const SETTING_BUTTON_SIZE = 'button_size';

	/**
	 * Identifier for the 'button_text_color' setting.
	 */
	const SETTING_BUTTON_TEXT_COLOR = 'button_text_color';

	/**
	 * Identifier for the 'button_background_color' setting.
	 */
	const SETTING_BUTTON_BACKGROUND_COLOR = 'button_background_color';

	/**
	 * ID attribute for the stylesheet.
	 */
	const ID_ATTR = 'wp-gdpr-cookie-notice-stylesheet';

	/**
	 * Option reader.
	 *
	 * @since 1.0.0
	 * @var Option_Reader
	 */
	protected $options;

	/**
	 * Constructor.
	 *
	 * Sets the option reader to use.
	 *
	 * @since 1.0.0
	 *
	 * @param Option_Reader $options Optional. Option reader to use.
	 */
	public function __construct( Option_Reader $options = null ) {
		if ( null === $options ) {
			$options = new Plugin_Option_Reader();
		}

		$this->options = $options;
	}

	/**
	 * Gets the ID attribute used for the asset.
	 *
	 * @since 1.0.0
	 *
	 * @return string ID attribute.
	 */
	public function get_id() : string {
		return self::ID_ATTR;
	}

	/**
	 * Prints the full asset including the wrapping tags.
	 *
	 * @since 1.0.0
	 */
	public function print() {
		?>
		<style id="<?php echo esc_attr( self::ID_ATTR ); ?>" type="text/css">
			<?php $this->print_content(); ?>
		</style>
		<?php
	}

	/**
	 * Prints the actual asset content.
	 *
	 * @since 1.0.0
	 */
	public function print_content() {
		$options = $this->options->get_options();

		if ( Cookie_Position_Enum::POSITION_OVERLAY === $options[ self::SETTING_POSITION ] ) {
			?>
			.wp-gdpr-cookie-notice-wrap,
			.wp-gdpr-cookie-notice {
				position: fixed;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				z-index: 999999;
			}

			@media (min-width: 42rem) {
				.wp-gdpr-cookie-notice {
					top: 50%;
					left: 50%;
					max-width: 40rem;
					transform: translate(-50%, -50%);
					border-width: <?php echo esc_attr( $options[ self::SETTING_BORDER_WIDTH ] ) . 'px'; ?>;
					<?php if ( $options[ self::SETTING_SHOW_DROP_SHADOW ] ) : ?>
						box-shadow: 3px 3px 5px 0 rgba(0, 0, 0, 0.1);
					<?php endif; ?>
				}
			}
			<?php
		} else {
			?>
			.wp-gdpr-cookie-notice-wrap,
			.wp-gdpr-cookie-notice {
				position: fixed;
				right: 0;
				left: 0;
				<?php echo Cookie_Position_Enum::POSITION_TOP === $options[ self::SETTING_POSITION ] ? 'top' : 'bottom'; ?>: 0;
				z-index: 999999;
			}

			.wp-gdpr-cookie-notice {
				border-width: <?php echo Cookie_Position_Enum::POSITION_TOP === $options[ self::SETTING_POSITION ] ? '0 0 ' . esc_attr( $options[ self::SETTING_BORDER_WIDTH ] ) . 'px' : esc_attr( $options[ self::SETTING_BORDER_WIDTH ] ) . 'px 0 0'; ?>;
				<?php if ( $options[ self::SETTING_SHOW_DROP_SHADOW ] ) : ?>
					box-shadow: 0 <?php echo Cookie_Position_Enum::POSITION_TOP === $options[ self::SETTING_POSITION ] ? '3px' : '-3px'; ?> 5px 0 rgba(0, 0, 0, 0.1);
				<?php endif; ?>
			}
			<?php
		}

		?>
		.wp-gdpr-cookie-notice-wrap {
			background-color: rgba(0, 0, 0, 0.65);
		}

		.wp-gdpr-cookie-notice {
			padding: 0.75rem 1rem;
			font-size: 90%;
			line-height: 1.4;
			color: <?php echo esc_attr( $options[ self::SETTING_TEXT_COLOR ] ); ?>;
			background-color: <?php echo esc_attr( $options[ self::SETTING_BACKGROUND_COLOR ] ); ?>;
			border-color: <?php echo esc_attr( $options[ self::SETTING_BORDER_COLOR ] ); ?>;
			border-style: solid;
		}

		.wp-gdpr-cookie-notice > * {
			display: block;
			margin: 0 auto !important;
			max-width: 40rem;
		}

		.wp-gdpr-cookie-notice a,
		.wp-gdpr-cookie-notice a:visited {
			color: <?php echo esc_attr( $options[ self::SETTING_LINK_COLOR ] ); ?>;
		}

		.wp-gdpr-cookie-notice a:hover,
		.wp-gdpr-cookie-notice a:focus {
			color: <?php echo esc_attr( $this->darken_color( $options[ self::SETTING_LINK_COLOR ], 25 ) ); ?>;
		}

		.wp-gdpr-cookie-notice-heading,
		.wp-gdpr-cookie-notice-content {
			margin-bottom: 0.2rem !important;
		}

		.wp-gdpr-cookie-notice-heading > * {
			margin: 0 !important;
			padding: 0 !important;
			font-size: 110%;
		}

		.wp-gdpr-cookie-notice-content > * {
			margin: 0 0 0.5rem !important;
			padding: 0 !important;
		}

		.wp-gdpr-cookie-notice-content > *:last-child {
			margin: 0 !important;
		}

		.wp-gdpr-cookie-notice-form {
			padding: 0 !important;
			background: transparent !important;
			box-shadow: none !important;
		}

		.wp-gdpr-cookie-notice-controls {
			display: flex;
			align-items: center;
			justify-content: flex-end;
		}

		.wp-gdpr-cookie-notice-controls > * {
			margin-right: 0.8rem;
		}

		.wp-gdpr-cookie-notice-controls > *:last-child {
			margin-right: 0;
		}

		.wp-gdpr-cookie-notice-toggles {
			display: block;
			margin: 0 0.8rem 0 0;
			padding: 0;
			border: 0;
			flex: 1;
			font-size: 90%;
		}

		.wp-gdpr-cookie-notice-toggle {
			display: block;
			float: left;
			margin-right: 0.8rem;
		}

		.wp-gdpr-cookie-notice-toggle input[type="checkbox"] {
			margin-right: 0.2rem;
		}

		.wp-gdpr-cookie-notice-toggle:last-child {
			margin-right: 0;
		}

		.wp-gdpr-cookie-notice-button {
			display: inline-block;
			<?php
			switch ( $options[ self::SETTING_BUTTON_SIZE ] ) {
				case Cookie_Button_Size_Enum::SIZE_SMALL:
					?>
					padding: 0.25rem 0.4rem;
					font-size: 90%;
					<?php
					break;
				case Cookie_Button_Size_Enum::SIZE_LARGE:
					?>
					padding: 0.6rem 0.8rem;
					font-size: 100%;
					<?php
					break;
				default:
					?>
					padding: 0.4rem 0.6rem;
					font-size: 90%;
					<?php
					break;
			}
			?>
			color: <?php echo esc_attr( $options[ self::SETTING_BUTTON_TEXT_COLOR ] ); ?>;
			background-color: <?php echo esc_attr( $options[ self::SETTING_BUTTON_BACKGROUND_COLOR ] ); ?>;
			border: 0;
			border-radius: 0;
			appearance: none;
		}

		.wp-gdpr-cookie-notice-button:hover,
		.wp-gdpr-cookie-notice-button:focus {
			color: <?php echo esc_attr( $options[ self::SETTING_BUTTON_TEXT_COLOR ] ); ?>;
			background-color: <?php echo esc_attr( $this->darken_color( $options[ self::SETTING_BUTTON_BACKGROUND_COLOR ], 25 ) ); ?>;
		}
		<?php
	}

	/**
	 * Darkens a hex color string about a given percentage.
	 *
	 * @since 1.0.0
	 *
	 * @param string $color      Hex color string.
	 * @param int    $percentage Percentage to darken about.
	 * @return string Darkened hex color string.
	 */
	protected function darken_color( string $color, int $percentage ) : string {
		if ( empty( $color ) ) {
			return $color;
		}

		$rgb = $this->hex_to_rgb( $color );

		$darkened = [];
		foreach ( $rgb as $channel ) {
			$darkened_channel = (int) round( $channel * ( 1.0 - $percentage / 100.0 ) );
			if ( $darkened_channel < 0 ) {
				$darkened_channel = 0;
			}
			$darkened[] = $darkened_channel;
		}

		return $this->rgb_to_hex( $darkened );
	}

	/**
	 * Converts a hex color string into an RGB array.
	 *
	 * @since 1.0.0
	 *
	 * @param string $color Hex color string.
	 * @return array RGB color array.
	 */
	protected function hex_to_rgb( string $color ) : array {
		if ( strlen( $color ) === 4 ) {
			$rgb = array_map( function( $color_part ) {
				return $color_part . $color_part;
			}, str_split( substr( $color, 1 ), 1 ) );
		} else {
			$rgb = str_split( substr( $color, 1 ), 2 );
		}

		return array_map( 'hexdec', $rgb );
	}

	/**
	 * Converts an RGB array into a hex color string.
	 *
	 * @since 1.0.0
	 *
	 * @param array $color RGB color array.
	 * @return string Hex color string.
	 */
	protected function rgb_to_hex( array $color ) : string {
		$hex = array_map( function( $color_part ) {
			return zeroise( dechex( $color_part ), 2 );
		}, $color );

		return '#' . $hex[0] . $hex[1] . $hex[2];
	}
}
