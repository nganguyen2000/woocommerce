<?php
/**
 * Checkbox Control.
 *
 * This file will contain checkbox control class for customize.
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
 * Checkbox Control.
 *
 * Checkbox control for customize
 *
 * @since 1.0.0
 */
class Checkbox extends Control {
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
	public $type = 'cmly_checkbox';

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
			<label><input type="checkbox" value="{{ key }}" <# if ( _.contains(data.value, key) ) { #>checked="checked"<# } #>><span></span> {{{ label }}}</label>
		<# }); #>
		<?php
	}
}

/**
 * Register Checkbox
 *
 * Register this checkbox control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_checkbox() {
	customizely_register_control( 'cmly_checkbox', 'Customizely\Controls\Checkbox', 'customizely_sanitize_checkbox' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_checkbox' );

/**
 * Register Checkbox Settings
 *
 * Register this checkbox control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_checkbox_settings() {
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

	foreach ( $values_index['items'] as $value_index ) {
		$default_options[ $values_index['tab'] ]['options'][ $value_index ]['type']    = 'repeatable-single';
		$default_options[ $values_index['tab'] ]['options'][ $value_index ]['default'] = array();
		$default_options[ $values_index['tab'] ]['options'][ $value_index ]['option']  = array(
			'label'   => __( 'Value', 'customizely' ),
			'info'    => __( 'Enter a valid value from `Choices` option.', 'customizely' ),
			'type'    => 'text',
			'default' => '',
		);
	}

	array_splice( $default_options[ $values_index['tab'] ]['options'], 0, 0, $choices );

	customizely_set_control_settings(
		'cmly_checkbox',
		array(
			'label'   => 'Checkbox',
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_checkbox_settings' );
