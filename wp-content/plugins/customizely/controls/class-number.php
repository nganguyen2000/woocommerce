<?php
/**
 * Number Control.
 *
 * This file will contain number control class for customize.
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
 * Number Control.
 *
 * Number control for customize
 *
 * @since 1.0.0
 */
class Number extends Control_Inline {
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
	public $type = 'cmly_number';

	/**
	 * Minimum
	 *
	 * Holds minimum value of number input.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var int
	 */
	public $min = 0;

	/**
	 * Maximum
	 *
	 * Holds maximum value of number input.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var int
	 */
	public $max = 100;

	/**
	 * Step
	 *
	 * Holds step value of number input.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var int
	 */
	public $step = 1;

	/**
	 * No Unit
	 *
	 * Holds is unit will work or not work with number input.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var bool
	 */
	public $no_unit = false;

	/**
	 * Default Unit
	 *
	 * Holds default unit for number input.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var int
	 */
	public $unit = 'px';

	/**
	 * Default Unit
	 *
	 * Holds supported units for number input.
	 * Supported units should be: 'px', '%', 'em', 'rem', 'ex', 'ch', 'vw', 'vh', 'vmin', 'vmax', 'cm', 'mm', 'in', 'pt', 'pc'
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var array
	 */
	public $units = array( 'px', '%', 'em', 'rem' );

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

		$this->json['min']  = $this->min;
		$this->json['max']  = $this->max;
		$this->json['step'] = $this->step;

		$this->json['no_unit'] = $this->no_unit;
		$this->json['unit']    = $this->unit;
		$this->json['units']   = $this->units;
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
			var numberInputType = data.no_unit ? 'number' : 'text';
		#>
		<div class="customizely-input-number">
			<input class="customizely-number-input" type="{{{ numberInputType }}}" min="{{{ data.min }}}" max="{{{ data.max }}}" step="{{{ data.step }}}" placeholder="{{{ data.placeholder }}}" value="{{{ data.value }}}" />
			<button type="button" class="customizely-number-input-up"><span class="dashicons dashicons-arrow-up"></span></button>
			<button type="button" class="customizely-number-input-down"><span class="dashicons dashicons-arrow-down"></span></button>
		</div>
		<?php
	}
}

/**
 * Register Number
 *
 * Register this number control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_number() {
	customizely_register_control( 'cmly_number', 'Customizely\Controls\Number' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_number' );

/**
 * Register Number Settings
 *
 * Register this number control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_number_settings() {
	$default_options = \customizely_get_control_common_settings();

	$number_options = array(
		array(
			'id'      => 'min',
			'label'   => __( 'Minimum', 'customizely' ),
			'info'    => __( 'Order priority to load the input.', 'customizely' ),
			'type'    => 'number',
			'default' => 0,
		),
		array(
			'id'      => 'max',
			'label'   => __( 'Maximum', 'customizely' ),
			'info'    => __( 'Order priority to load the input.', 'customizely' ),
			'type'    => 'number',
			'default' => 100,
		),
		array(
			'id'      => 'step',
			'label'   => __( 'Step', 'customizely' ),
			'info'    => __( 'Order priority to load the input.', 'customizely' ),
			'type'    => 'number',
			'default' => 1,
		),
		array(
			'id'      => 'no_unit',
			'label'   => __( 'No Unit', 'customizely' ),
			'info'    => __( 'Order priority to load the input.', 'customizely' ),
			'type'    => 'switch',
			'default' => false,
		),
		array(
			'id'      => 'unit',
			'label'   => __( 'Default Unit', 'customizely' ),
			'info'    => __( 'Order priority to load the input.', 'customizely' ),
			'type'    => 'text',
			'default' => 'px',
			'depends' => array(
				array(
					'id'        => 'no_unit',
					'condition' => '=',
					'value'     => false,
				),
			),
		),
		array(
			'id'      => 'units',
			'label'   => __( 'Valid CSS Units', 'customizely' ),
			'type'    => 'repeatable-single',
			'default' => array( 'px', '%', 'em', 'rem' ),
			'option'  => array(
				'label'   => __( 'Unit', 'customizely' ),
				'info'    => __( 'Enter a valid CSS unit. All valid CSS Units are `px`, `%,` `em`, `rem`, `ex`, `ch`, `vw`, `vh`, `vmin`, `vmax`, `cm`, `mm`, `in`, `pt` and `pc`.', 'customizely' ),
				'type'    => 'text',
				'default' => '',
			),
			'depends' => array(
				array(
					'id'        => 'no_unit',
					'condition' => '=',
					'value'     => false,
				),
			),
		),
	);

	$values_index = \customizely_get_deafult_values_index();

	array_splice( $default_options[ $values_index['tab'] ]['options'], 0, 0, $number_options );

	customizely_set_control_settings(
		'cmly_number',
		array(
			'label'   => __( 'Number', 'customizely' ),
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_number_settings' );

/**
 * Process Number Arguments
 *
 * This function will process special number arguments for customize.
 *
 * @param array $args Arguments array.
 * @param array $values Number control values.
 *
 * @return array
 */
function process_number_args( $args, $values ) {

	$args[0]['min']     = isset( $values['min'] ) ? $values['min'] : 0;
	$args[0]['max']     = isset( $values['max'] ) ? $values['max'] : 100;
	$args[0]['step']    = isset( $values['step'] ) ? $values['step'] : 1;
	$args[0]['no_unit'] = isset( $values['no_unit'] ) ? $values['no_unit'] : false;
	$args[0]['unit']    = isset( $values['unit'] ) ? $values['unit'] : 'px';
	$args[0]['units']   = isset( $values['units'] ) ? $values['units'] : array( 'px', '%', 'em', 'rem' );

	return $args;
}
add_filter( 'customizely_processed_cmly_number_control_args', 'Customizely\Controls\process_number_args', 10, 2 );
