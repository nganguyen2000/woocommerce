<?php
/**
 * Switch Input Control.
 *
 * This file will contain switch control class for customize.
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
 * Switch Control.
 *
 * Switch control for customize
 *
 * @since 1.0.0
 */
class Switch_Input extends Control_Inline {
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
	public $type = 'cmly_switch';

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

		if ( $this->responsive ) {
			if ( isset( $this->setting['default'] ) ) {
				$this->json['default'] = is_string( $this->setting['default']->default ) ? ( 'true' === $this->setting['default']->default ? true : false ) : (bool) $this->setting['default']->default;
			} else {
				$this->json['default'] = false;
			}
		} else {
			$this->json['default'] = is_string( $this->setting->default ) ? ( 'true' === $this->setting->default ? true : false ) : (bool) $this->setting->default;

			if ( isset( $this->default ) ) {
				$this->json['default'] = is_string( $this->default ) ? ( 'true' === $this->default ? true : false ) : (bool) $this->default;
			}
		}

		$value = $this->value();

		$this->json['value'] = is_string( $value ) ? ( 'true' === $value ? true : false ) : (bool) $value;
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
		<label class="customizely-switch-control"><input type="checkbox" <# if ( data.value ) { #>checked="checked"<# } #>><span></span></label>
		<?php
	}
}

add_action(
	'customizely_after_customize_init',
	function () {
		customizely_register_control( 'cmly_switch', 'Customizely\Controls\Switch_Input', 'rest_sanitize_boolean' );
	}
);

add_action(
	'customizely_loaded',
	function () {
		$default_options = \customizely_get_control_common_settings();

		$values_index = \customizely_get_deafult_values_index();

		foreach ( $values_index['items'] as $value_index ) {
			$default_options[ $values_index['tab'] ]['options'][ $value_index ]['type']    = 'switch';
			$default_options[ $values_index['tab'] ]['options'][ $value_index ]['default'] = false;
		}

		customizely_set_control_settings(
			'cmly_switch',
			array(
				'label'   => 'Switch',
				'options' => $default_options,
			)
		);
	}
);
