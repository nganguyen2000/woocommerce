<?php
/**
 * Color Control.
 *
 * This file will contain color control class for customize.
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
 * Color Control.
 *
 * Color control for customize
 *
 * @since 1.0.0
 */
class Color extends Control_Inline {
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
	public $type = 'cmly_color';

	/**
	 * RGBA.
	 *
	 * It will hold boolean value. The color input will be
	 * RGBA supported or not.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var bool
	 */
	public $rgba = false;

	/**
	 * To JSON.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['rgba'] = (bool) $this->rgba;
	}

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
		var rgbaAttr = '';
		if ( data.rgba ) {
			rgbaAttr = 'data-alpha=true';
		}
		#>
		<input class="customizely-color-input" type="text" placeholder="{{{ data.placeholder }}}" value="{{{ data.value }}}" {{{ rgbaAttr }}} />
		<?php
	}
}

/**
 * Register Color
 *
 * Register this color control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_color() {
	customizely_register_control( 'cmly_color', 'Customizely\Controls\Color', 'customizely_sanitize_color' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_color' );

/**
 * Register Color Settings
 *
 * Register this color control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_color_settings() {
	$default_options = \customizely_get_control_common_settings();

	$extra_options = array(
		array(
			'id'       => 'rgba',
			'label'    => __( 'Enable RGBA', 'customizely' ),
			'type'     => 'switch',
			'titleKey' => 'label',
			'default'  => false,
		),
	);

	$values_index = \customizely_get_deafult_values_index();

	foreach ( $values_index['items'] as $value_index ) {
		$default_options[ $values_index['tab'] ]['options'][ $value_index ]['type'] = 'color';
	}

	array_splice( $default_options[ $values_index['tab'] ]['options'], 0, 0, $extra_options );

	customizely_set_control_settings(
		'cmly_color',
		array(
			'label'   => __( 'Color', 'customizely' ),
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_color_settings' );

/**
 * Process Color Control Arguments
 *
 * @param array $processed_args Processed arguments.
 * @param array $values Unprocessed arguments.
 *
 * @since 1.0.0
 *
 * @return array Return control arguments
 */
function process_color_control_args( $processed_args, $values ) {
	$processed_args[0]['rgba'] = isset( $values['rgba'] ) ? $values['rgba'] : false;
	return $processed_args;
}
add_filter( 'customizely_processed_cmly_color_control_args', 'Customizely\Controls\process_color_control_args', 10, 2 );
