<?php
/**
 * URL Control.
 *
 * This file will contain url control class for customize.
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
 * URL Control.
 *
 * URL control for customize
 *
 * @since 1.0.0
 */
class URL extends Control {
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
	public $type = 'cmly_url';

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
		<input class="" type="url" placeholder="{{{ data.placeholder }}}" value="{{{ data.value }}}" />
		<?php
	}
}

/**
 * Register URL
 *
 * Register this url control to customize and Customizely Builder.
 *
 * @since 1.0.0
 *
 * @return  void
 */
function register_url() {
	customizely_register_control( 'cmly_url', 'Customizely\Controls\URL', 'sanitize_url' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_url' );

/**
 * Register URL Settings
 *
 * Register this url control settings for Builder.
 *
 * @since 1.0.0
 *
 * @return void
 */
function register_url_settings() {
	$default_options = \customizely_get_control_common_settings();

	customizely_set_control_settings(
		'cmly_url',
		array(
			'label'   => __( 'URL', 'customizely' ),
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_url_settings' );

