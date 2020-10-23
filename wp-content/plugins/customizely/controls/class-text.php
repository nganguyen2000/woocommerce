<?php
/**
 * Text Control.
 *
 * This file will contain text control class for customize.
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

namespace Customizely\Controls;

use Customizely\Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Text Control.
 *
 * Text control for customize
 *
 * @since 1.0.0
 */
class Text extends Control {
	/**
	 * Type.
	 *
	 * Holds type of this control.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var string
	 */
	public $type = 'cmly_text';

	/**
	 * Input Template
	 *
	 * Tempate for main input to show at customizer.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return  void
	 */
	public function input_template() {
		?>
		<input class="" type="text" placeholder="{{{ data.placeholder }}}" value="{{{ data.value }}}" />
		<?php
	}
}

/**
 * Register Text
 *
 * Register this text control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_text() {
	customizely_register_control( 'cmly_text', 'Customizely\Controls\Text' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_text' );

/**
 * Register Text Settings
 *
 * Register this text control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_text_settings() {
	$default_options = \customizely_get_control_common_settings();

	customizely_set_control_settings(
		'cmly_text',
		array(
			'label'   => __( 'Text', 'customizely' ),
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_text_settings' );

