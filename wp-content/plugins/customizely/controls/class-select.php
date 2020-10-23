<?php
/**
 * Select Control.
 *
 * This file will contain select control class for customize.
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
 * Select Control.
 *
 * Select control for customize
 *
 * @since 1.0.0
 */
class Select extends Control {
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
	public $type = 'cmly_select';

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
		<#
			var placeholder = '';
			if(data.placeholder){
				placeholder = 'data-placeholder="'+data.placeholder+'"';
			}
			var multiple = '';
			if(data.multiple){
				multiple = 'multiple=multiple';
			}
		#>
		<select {{{ placeholder }}} {{{ multiple }}}>
			<# if(data.placeholder){ #><option value="">{{{ data.placeholder }}}</option><# } #>
			<# _.each(data.choices, function(label, key){ #>
				<option value="{{ key }}" <# if ( data.value == key ) { #>selected="selected"<# } #>>{{{ label }}}</option>
			<# }); #>
		</select>
		<?php
	}
}

/**
 * Register Select
 *
 * Register this select control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_select() {
	customizely_register_control( 'cmly_select', 'Customizely\Controls\Select' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_select' );

add_action(
	'customizely_after_customize_init',
	function () {
		customizely_register_control( 'cmly_select', 'Customizely\Controls\Select' );
	}
);

/**
 * Register Select Settings
 *
 * Register this select control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_select_settings() {
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
		'cmly_select',
		array(
			'label'   => 'Select',
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_select_settings' );
