<?php
/**
 * Icons Control.
 *
 * This file will contain icons control class for customize.
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

namespace Customizely\Controls;

use Customizely\Control_Inline;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Icons Control.
 *
 * Icons control for customize
 *
 * @since 1.0.0
 */
class Icons extends Control_Inline {
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
	public $type = 'cmly_icons';

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
		<div class="customizely-icon-input"></div>
		<?php
	}
}

/**
 * Register Range
 *
 * Register this range control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_icons() {
	customizely_register_control( 'cmly_icons', 'Customizely\Controls\Icons' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_icons' );

/**
 * Register Range Settings
 *
 * Register this range control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_icons_settings() {
	$default_options = \customizely_get_control_common_settings();

	\customizely_set_control_settings(
		'cmly_icons',
		array(
			'label'   => 'Icon',
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_icons_settings' );
