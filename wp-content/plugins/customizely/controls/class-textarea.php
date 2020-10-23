<?php
/**
 * Textarea Control.
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
class Textarea extends Control {
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
	public $type = 'cmly_textarea';

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
		<textarea placeholder="{{{ data.placeholder }}}">{{{ data.value }}}</textarea>
		<?php
	}
}

/**
 * Register Textarea
 *
 * Register this text control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_textarea() {
	customizely_register_control( 'cmly_textarea', 'Customizely\Controls\Textarea', 'sanitize_textarea_field' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_textarea' );

/**
 * Register Textarea Settings
 *
 * Register this text control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_textarea_settings() {
	$default_options = \customizely_get_control_common_settings();

	$values_index = \customizely_get_deafult_values_index();

	foreach ( $values_index['items'] as $value_index ) {
		$default_options[ $values_index['tab'] ]['options'][ $value_index ]['type'] = 'textarea';
	}

	customizely_set_control_settings(
		'cmly_textarea',
		array(
			'label'   => __( 'Textarea', 'customizely' ),
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_textarea_settings' );
