<?php
/**
 * Buttons Control.
 *
 * This file will contain buttons control class for customize.
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019-2020, KitThemes
 * @since      1.1.0
 */

namespace Customizely\Controls;

use Customizely\Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Buttons Control.
 *
 * Buttons control for customize
 *
 * @since 1.1.0
 */
class Buttons extends Control {
	/**
	 * Type.
	 *
	 * Holds type of this control.
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 *
	 * @var string
	 */
	public $type = 'cmly_buttons';

	/**
	 * Input Template
	 *
	 * Tempate for main input to show at customizer.
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return  void
	 */
	public function input_template() {
		?>
		<div class="customizely-buttons-wrap">
			<# _.each(data.choices, function(label, key){ #>
				<label><input type="radio" value="{{ key }}" name="cmly-buttons-{{ data.id }}" <# if ( data.value == key ) { #>checked="checked"<# } #>><span>{{{ label }}}</span></label>
			<# }); #>
		</div>
		<?php
	}
}


/**
 * Register Buttons
 *
 * Register this buttons control to customize and Customizely Builder.
 *
 * @since 1.1.0
 *
 * @return  void
 */
function register_buttons() {
	customizely_register_control( 'cmly_buttons', 'Customizely\Controls\Buttons' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_buttons' );

/**
 * Register Buttons Settings
 *
 * Register this buttons control settings for Builder.
 *
 * @since 1.1.0
 *
 * @return void
 */
function register_buttons_settings() {
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
		'cmly_buttons',
		array(
			'label'   => 'Buttons',
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_buttons_settings' );
