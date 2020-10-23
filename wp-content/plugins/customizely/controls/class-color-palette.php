<?php
/**
 * Color Palette Control.
 *
 * This file will contain color palette control class for customize.
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
 * Color Palette Control.
 *
 * Color Palette control for customize
 *
 * @since 1.1.0
 */
class Color_Palette extends Control {
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
	public $type = 'cmly_color_palette';

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
		<div class="customizely-color-palette-wrap">
			<# _.each(data.choices, function(label){ #>
				<label><input type="radio" value="{{ label }}" name="cmly-color-palette-{{ data.id }}" <# if ( data.value == label ) { #>checked="checked"<# } #>><span style="background-color: {{ label }};"></span></label>
			<# }); #>
		</div>
		<?php
	}
}


/**
 * Register Color Palette
 *
 * Register this color palette control to customize and Customizely Builder.
 *
 * @since 1.1.0
 *
 * @return  void
 */
function register_color_palette() {
	customizely_register_control( 'cmly_color_palette', 'Customizely\Controls\Color_Palette' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_color_palette' );

/**
 * Register Color Palette Settings
 *
 * Register this color palette control settings for Builder.
 *
 * @since 1.1.0
 *
 * @return void
 */
function register_color_palette_settings() {
	$default_options = \customizely_get_control_common_settings();

	$choices = array(
		array(
			'id'      => 'choices',
			'label'   => __( 'Colors', 'customizely' ),
			'type'    => 'repeatable-single',
			'default' => array( '#000' ),
			'inline'  => true,
			'option'  => array(
				'id'      => 'value',
				'type'    => 'color',
				'default' => '#000',
			),
		),
	);

	$values_index = \customizely_get_deafult_values_index();

	foreach ( $values_index['items'] as $value_index ) {
		$default_options[ $values_index['tab'] ]['options'][ $value_index ]['type'] = 'color';
	}

	array_splice( $default_options[ $values_index['tab'] ]['options'], 0, 0, $choices );

	customizely_set_control_settings(
		'cmly_color_palette',
		array(
			'label'   => 'Color Palette',
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_color_palette_settings' );
