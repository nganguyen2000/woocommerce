<?php
/**
 * Image Select Control.
 *
 * This file will contain radio control class for customize.
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.1
 */

namespace Customizely\Controls;

use Customizely\Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Image Select Control.
 *
 * Image select control for customize
 *
 * @since 1.0.1
 */
class Image_Select extends Control {
	/**
	 * Type.
	 *
	 * Holds type of this control.
	 *
	 * @since 1.0.1
	 * @access public
	 * @static
	 *
	 * @var string
	 */
	public $type = 'cmly_image_select';

	/**
	 * Col
	 *
	 * Holds number of columns to show.
	 *
	 * @since 1.0.1
	 * @access public
	 *
	 * @var int
	 */
	public $col = 0;

	/**
	 * No Padding
	 *
	 * Holds is padding will work or not work.
	 *
	 * @since 1.0.1
	 * @access public
	 *
	 * @var bool
	 */
	public $no_padding = false;

	/**
	 * To JSON.
	 *
	 * @since 1.0.1
	 * @access public
	 *
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['col']       = $this->col;
		$this->json['noPadding'] = $this->no_padding;
	}

	/**
	 * Input Template
	 *
	 * Tempate for main input to show at customizer.
	 *
	 * @since 1.0.1
	 * @access public
	 *
	 * @return  void
	 */
	public function input_template() {
		?>
		<#
		console.log( data );
			var noPaddingClass = data.noPadding ? 'customizely-image-select-no-padding' : '';
		#>
		<div class="customizely-image-select-wrap customizely-image-select-col-{{ data.col }} {{ noPaddingClass }}">
			<# _.each(data.choices, function(label, key){ #>
				<div class="customizely-image-select-column">
					<label>
						<input type="radio" value="{{ key }}" name="cmly-image-select-{{ data.id }}" <# if ( data.value == key ) { #>checked="checked"<# } #>>
						<img src="{{ label }}">
					</label>
				</div>
			<# }); #>
		</div>
		<?php
	}
}


/**
 * Register Image Select
 *
 * Register this radio control to customize and Customizely Builder.
 *
 * @since 1.0.1
 *
 * @return  void
 */
function register_image_select() {
	customizely_register_control( 'cmly_image_select', 'Customizely\Controls\Image_Select' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_image_select' );

/**
 * Register Image Select Settings
 *
 * Register this radio control settings for Builder.
 *
 * @since 1.0.1
 *
 * @return void
 */
function register_image_select_settings() {
	$default_options = \customizely_get_control_common_settings();

	$choices = array(
		array(
			'id'       => 'choices',
			'label'    => __( 'Choices', 'customizely' ),
			'type'     => 'repeatable',
			'titleKey' => 'value',
			'default'  => array(
				array(
					'value' => '',
					'label' => '',
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
					'id'        => 'label',
					'label'     => __( 'Image', 'customizely' ),
					'type'      => 'media',
					'library'   => 'image',
					'store'     => 'url',
					'mediaType' => 'image',
					'default'   => '',
				),
			),
		),
	);

	$advance_options = array(
		array(
			'id'      => 'col',
			'label'   => __( 'Column', 'customizely' ),
			'info'    => __( 'Number of columns to show.', 'customizely' ),
			'type'    => 'select',
			'choices' => array(
				1 => __( '1 Column', 'customizely' ),
				2 => __( '2 Columns', 'customizely' ),
				3 => __( '3 Columns', 'customizely' ),
				4 => __( '4 Columns', 'customizely' ),
			),
			'default' => 3,
		),
		array(
			'id'      => 'no_padding',
			'label'   => __( 'No Padding', 'customizely' ),
			'info'    => __( 'Turn off or on padding of inside image select.', 'customizely' ),
			'type'    => 'switch',
			'default' => false,
		),
	);

	$values_index      = \customizely_get_deafult_values_index();
	$advance_tab_index = \customizely_get_advance_tab_index();

	array_splice( $default_options[ $values_index['tab'] ]['options'], 0, 0, $choices );
	array_splice( $default_options[ $advance_tab_index ]['options'], 0, 0, $advance_options );

	customizely_set_control_settings(
		'cmly_image_select',
		array(
			'label'   => 'Image Select',
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_image_select_settings' );

/**
 * Process Image Select Arguments
 *
 * This function will process special image select arguments for customize.
 *
 * @since 1.0.1
 *
 * @param array $args Arguments array.
 * @param array $values Range control values.
 *
 * @return array
 */
function process_cmly_image_select_args( $args, $values ) {
	$args[0]['col']        = isset( $values['col'] ) ? $values['col'] : 3;
	$args[0]['no_padding'] = isset( $values['no_padding'] ) ? $values['no_padding'] : false;

	return $args;
}
add_filter( 'customizely_processed_cmly_image_select_control_args', 'Customizely\Controls\process_cmly_image_select_args', 10, 2 );

