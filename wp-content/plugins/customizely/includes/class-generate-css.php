<?php
/**
 * Generate CSS
 *
 * Generate CSS from options to show into front-end.
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

namespace Customizely;

/**
 * Generate CSS
 *
 * Generate CSS to show into front-end with this class.
 *
 * @since 1.0.0
 */
class Generate_CSS {
	/**
	 * Options
	 *
	 * It will hold Options class instance
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var Customizely\Options
	 */
	private $options;

	/**
	 * CSS
	 *
	 * It will hold all css velues for all media
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $css = array();
	/**
	 * CSS Laptop
	 *
	 * It will hold all CSS values for laptop media
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $css_laptop = array();

	/**
	 * CSS Tablet
	 *
	 * It will hold all CSS values for tablet media
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $css_tablet = array();

	/**
	 * CSS Mobile Landscape
	 *
	 * It will hold all CSS values for mobile media at landscape mode
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $css_mobile_landscape = array();

	/**
	 * CSS Mobile
	 *
	 * It will hold all CSS values for mobile media
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $css_mobile = array();

	/**
	 * Constructor method of Generate_CSS class
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param Options $options  Options class instance.
	 *
	 * @return void
	 */
	public function __construct( Options $options ) {
		$this->options = $options;

		add_action( 'wp_head', array( $this, 'render_css_to_front' ), 999 );
	}

	/**
	 * Render CSS to Front
	 *
	 * Render CSS to front-end of the site
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function render_css_to_front() {
		$css = get_option( 'cmly_css_cache', false );

		$validity = $this->validate_css( $css );

		if ( $validity->has_errors() ) {
			return;
		}

		if ( ! $css ) {
			$css = $this->get_css_string();
		}
		?>
		<style type="text/css"><?php echo wp_kses_post( $css ); ?></style>
		<?php
	}

	/**
	 * Validate CSS
	 *
	 * Check CSS validity and return wp_error.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $css CSS to validate.
	 *
	 * @return \WP_Error
	 */
	public function validate_css( $css ) {
		$validity = new \WP_Error();

		if ( preg_match( '#</?\w+#', $css ) ) {
			$validity->add( 'illegal_markup', __( 'Markup is not allowed in CSS.', 'customizely' ) );
		}

		return $validity;
	}

	/**
	 * Get CSS as String
	 *
	 * Convert CSS array to CSS string and return it.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string
	 */
	public function get_css_string() {
		$this->set_css();

		$css = $this->get_css();

		$css_string = '';

		if ( ! empty( $css['all'] ) ) {
			foreach ( $css['all'] as $selector => $properties ) {
				$css_string .= $selector . '{';
				foreach ( $properties as $property => $css_value ) {
					$css_string .= $property . ': ' . $css_value . ';';
				}
				$css_string .= '}';
			}
		}

		if ( ! empty( $css['laptop'] ) ) {
			$css_string .= '@media (min-width: 992px) and (max-width: 1199.98px){';
			foreach ( $css['laptop'] as $selector => $properties ) {
				$css_string .= $selector . '{';
				foreach ( $properties as $property => $css_value ) {
					$css_string .= $property . ': ' . $css_value . ';';
				}
				$css_string .= '}';
			}
			$css_string .= '}';
		}

		if ( ! empty( $css['tablet'] ) ) {
			$css_string .= '@media (min-width: 768px) and (max-width: 991.98px){';
			foreach ( $css['tablet'] as $selector => $properties ) {
				$css_string .= $selector . '{';
				foreach ( $properties as $property => $css_value ) {
					$css_string .= $property . ': ' . $css_value . ';';
				}
				$css_string .= '}';
			}
			$css_string .= '}';
		}

		if ( ! empty( $css['mobile_landscape'] ) ) {
			$css_string .= '@media (min-width: 576px) and (max-width: 767.98px){';
			foreach ( $css['mobile_landscape'] as $selector => $properties ) {
				$css_string .= $selector . '{';
				foreach ( $properties as $property => $css_value ) {
					$css_string .= $property . ': ' . $css_value . ';';
				}
				$css_string .= '}';
			}
			$css_string .= '}';
		}

		if ( ! empty( $css['mobile'] ) ) {
			$css_string .= '@media (max-width: 575.98px){';
			foreach ( $css['mobile'] as $selector => $properties ) {
				$css_string .= $selector . '{';
				foreach ( $properties as $property => $css_value ) {
					$css_string .= $property . ': ' . $css_value . ';';
				}
				$css_string .= '}';
			}
			$css_string .= '}';
		}

		return $css_string;
	}

	/**
	 * Get CSS
	 *
	 * Return CSS array as media array
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_css() {
		return array(
			'all'              => $this->css,
			'laptop'           => $this->css_laptop,
			'tablet'           => $this->css_tablet,
			'mobile_landscape' => $this->css_mobile_landscape,
			'mobile'           => $this->css_mobile,
		);
	}

	/**
	 * Set CSS
	 *
	 * Get CSS value for options and set it to CSS array
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function set_css() {
		$controls = $this->options->get_controls();

		foreach ( $controls as $control_id => $control ) {
			if ( ! empty( $control['args']['css'] ) ) {
				$is_responsive = $control['args']['responsive'];
				foreach ( $control['args']['css'] as $css_item ) {
					if ( ! isset( $this->css[ $css_item['selector'] ] ) ) {
						$this->css[ $css_item['selector'] ] = array();
					}
					$css_value = get_theme_mod( $control_id, $control['settings']['default'] );

					$css_value = apply_filters( 'customizely_before_process_' . $control['args']['type'] . '_value', $css_value, 'desktop' );

					$css_value = $this->process_value( $css_value, $css_item['replace'] );

					$css_value = apply_filters( 'customizely_after_process_' . $control['args']['type'] . '_value', $css_value, 'desktop' );

					if ( '' !== $css_value ) {
						$this->css[ $css_item['selector'] ][ $css_item['property'] ] = $css_value;
					}

					if ( $is_responsive ) {
						$devices = array(
							'laptop',
							'tablet',
							'mobile_landscape',
							'mobile',
						);

						foreach ( $devices as $device ) {
							$css_value = get_theme_mod( $control_id . '_' . $device, $control['settings'][ 'default_' . $device ] );
							$css_value = apply_filters( 'customizely_before_process_' . $control['args']['type'] . '_value', $css_value, $device );
							$css_value = $this->process_value( $css_value, $css_item['replace'] );
							$css_value = apply_filters( 'customizely_after_process_' . $control['args']['type'] . '_value', $css_value, $device );
							if ( '' !== $css_value ) {
								$this->{ 'css_' . $device }[ $css_item['selector'] ][ $css_item['property'] ] = $css_value;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Process Value
	 *
	 * It will process CSS value and return processed value.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $css_value CSS main value to process.
	 * @param string $replace   Replace value with this.
	 *
	 * @return string
	 */
	private function process_value( $css_value, $replace = '' ) {
		if ( ! empty( $replace ) ) {
			$css_value = empty( $css_value ) ? '' : preg_replace( '/\{\{(?:\s+)?value(?:\s+)?\}\}/', $css_value, $replace );
		}

		return $css_value;
	}
}
