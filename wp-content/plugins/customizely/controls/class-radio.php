<?php
/**
 * Radio Control.
 *
 * This file will contain radio control class for customize.
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
 * Radio Control.
 *
 * Radio control for customize
 *
 * @since 1.0.0
 */
class Radio extends Control {
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
	public $type = 'cmly_radio';

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
		<# _.each(data.choices, function(label, key){ #>
			<label><input type="radio" value="{{ key }}" name="cmly-radio-{{ data.id }}" <# if ( data.value == key ) { #>checked="checked"<# } #>><span></span> {{{ label }}}</label>
		<# }); #>
		<?php
	}
}


/**
 * Register Radio
 *
 * Register this radio control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_radio() {
	customizely_register_control( 'cmly_radio', 'Customizely\Controls\Radio' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_radio' );

/**
 * Register Radio Settings
 *
 * Register this radio control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_radio_settings() {
	$default_options = \customizely_get_control_common_settings();

	$choices = array(
		array(
			'id'       => 'choices',
			'label'    => __( 'Choices', 'customizely' ),
			'type'     => 'repeatable',
			'titleKey' => 'label',
			'default'  => array(
				array(
					'value' => '',
					'label' => 'Untitled',
				),
			),
			'options'  => array(
				array(
					'id'      => 'value',
					'label'   => __( 'Value', 'customizely' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'id'      => 'label',
					'label'   => __( 'Label', 'customizely' ),
					'type'    => 'text',
					'default' => 'Untitled',
				),
			),
		),
	);

	$values_index = \customizely_get_deafult_values_index();

	array_splice( $default_options[ $values_index['tab'] ]['options'], 0, 0, $choices );

	customizely_set_control_settings(
		'cmly_radio',
		array(
			'label'   => 'Radio',
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_radio_settings' );
