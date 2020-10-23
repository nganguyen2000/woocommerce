<?php
/**
 * Media Control.
 *
 * This file will contain media control class for customize.
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
 * Media Control.
 *
 * Media control for customize
 *
 * @since 1.0.1
 */
class Media extends Control {
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
	public $type = 'cmly_media';

	/**
	 * Meida Type.
	 *
	 * Holds media type of media control. default is `image`.
	 *
	 * @since 1.0.1
	 * @access public
	 * @static
	 *
	 * @var string
	 */
	public $media_type = 'image';

	/**
	 * Meida Modal Title.
	 *
	 * Holds media modal title.
	 *
	 * @since 1.0.1
	 * @access public
	 * @static
	 *
	 * @var string
	 */
	public $modal_title = '';

	/**
	 * Meida Modal Button Title.
	 *
	 * Holds media modal button title.
	 *
	 * @since 1.0.1
	 * @access public
	 * @static
	 *
	 * @var string
	 */
	public $modal_button_title = '';

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

		$this->json['mediaType']        = $this->media_type;
		$this->json['modalTitle']       = $this->modal_title;
		$this->json['modalButtonTitle'] = $this->modal_button_title;
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
		<div class="customizely-media-holder">
			<div class="customizely-media-placeholder"><span class="dashicons dashicons-plus"></span></div>
		</div>
		<div class="customizely-media-buttons">
			<button class="button button-primary customizely-add-media" type="button"><?php esc_html_e( 'Change', 'customizely' ); ?></button>
			<button class="button customizely-remove-media" type="button"><?php esc_html_e( 'Remove', 'customizely' ); ?></button>
		</div>
		<?php
	}
}

/**
 * Register Media
 *
 * Register this media control to customize and Customizely Builder.
 *
 * @since 1.0.1
 *
 * @return  void
 */
function register_media() {
	customizely_register_control( 'cmly_media', 'Customizely\Controls\Media' );
}
add_action( 'customizely_after_customize_init', 'Customizely\Controls\register_media' );

/**
 * Register Media Settings
 *
 * Register this media control settings for Builder.
 *
 * @since 1.0.1
 *
 * @return void
 */
function register_media_settings() {
	$default_options = \customizely_get_control_common_settings();

	$media_options = array(
		array(
			'id'      => 'media_type',
			'label'   => __( 'Media Type', 'customizely' ),
			'info'    => __( 'Select a media type.', 'customizely' ),
			'type'    => 'select',
			'choices' => array(
				'all'   => __( 'All', 'customizely' ),
				'image' => __( 'Image', 'customizely' ),
				'audio' => __( 'Audio', 'customizely' ),
				'video' => __( 'Video', 'customizely' ),
			),
			'default' => 'image',
		),
	);

	$media_advance_options = array(
		array(
			'id'      => 'modal_title',
			'label'   => __( 'Modal Title', 'customizely' ),
			'info'    => __( 'Set media modal title.', 'customizely' ),
			'type'    => 'text',
			'default' => __( 'Select or Upload Media Of Your Chosen Persuasion', 'customizely' ),
		),
		array(
			'id'      => 'modal_button_title',
			'label'   => __( 'Modal Button Title', 'customizely' ),
			'info'    => __( 'Set media modal select button title.', 'customizely' ),
			'type'    => 'text',
			'default' => __( 'Select', 'customizely' ),
		),
	);

	$values_index = \customizely_get_deafult_values_index();

	foreach ( $values_index['items'] as $value_index ) {
		$default_options[ $values_index['tab'] ]['options'][ $value_index ]['type'] = 'media';
	}

	$advance_tab_index = customizely_get_advance_tab_index();

	array_splice( $default_options[ $values_index['tab'] ]['options'], 0, 0, $media_options );
	array_splice( $default_options[ $advance_tab_index ]['options'], 0, 0, $media_advance_options );

	customizely_set_control_settings(
		'cmly_media',
		array(
			'label'   => __( 'Media', 'customizely' ),
			'options' => $default_options,
		)
	);
}
add_action( 'customizely_loaded', 'Customizely\Controls\register_media_settings' );

/**
 * Process Media Arguments
 *
 * This function will process special media arguments for customize.
 *
 * @since 1.0.1
 *
 * @param array $args Arguments array.
 * @param array $values Range control values.
 *
 * @return array
 */
function process_media_args( $args, $values ) {

	$args[0]['media_type']         = isset( $values['media_type'] ) ? $values['media_type'] : '';
	$args[0]['modal_title']        = isset( $values['modal_title'] ) ? $values['modal_title'] : '';
	$args[0]['modal_button_title'] = isset( $values['modal_button_title'] ) ? $values['modal_button_title'] : '';

	return $args;
}
add_filter( 'customizely_processed_cmly_media_control_args', 'Customizely\Controls\process_media_args', 10, 2 );

/**
 * Process media value for CSS
 *
 * It will take media ID and return media file url.
 *
 * @since 1.0.1
 *
 * @param string $css_value Media ID.
 *
 * @return string
 */
function process_media_value_for_css( $css_value ) {
	if ( ! empty( $css_value ) ) {
		$media = wp_get_attachment_image_src( $css_value, 'full' );

		if ( ! empty( $media ) && ! empty( $media[0] ) ) {
			$css_value = $media[0];
		}
	}
	return $css_value;
}
add_filter( 'customizely_before_process_cmly_media_value', 'Customizely\Controls\process_media_value_for_css' );
