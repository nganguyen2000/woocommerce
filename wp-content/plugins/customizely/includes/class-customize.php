<?php
/**
 * Customize Class File
 *
 * This file will contain Customize Class.
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

namespace Customizely;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customize Class
 *
 * This class will handel all type of actions for customize.
 *
 * @since 1.0.0
 */
class Customize {

	/**
	 * Customize Manager
	 *
	 * I will hold instance of WP_Customize_Manager class.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var WP_Customize_Manager
	 */
	private $wp_manager;

	/**
	 * Panel ID
	 *
	 * I it will hold panel id until call $this->reset_panel().
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var string
	 */
	private $panel = '';

	/**
	 * Section ID
	 *
	 * I it will hold section id until call $this->reset_section().
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var string
	 */
	private $section = '';

	/**
	 * Controls
	 *
	 * I it will hold all controls type class names.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $controls = array();

	/**
	 * Sanitize Callbacks
	 *
	 * I it will hold all controls sanitize callbacks.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @var array
	 */
	private $sanitize_cbs = array();

	/**
	 * Constructor Method for Customize
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param WP_Customize_Manager $manager instance of WP_Customize_Manager.
	 *
	 * @return void
	 */
	public function __construct( $manager ) {
		$this->wp_manager = $manager;

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'scripts' ), 100 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'styles' ), 100 );

		add_action( 'customize_controls_print_footer_scripts', array( $this, 'print_control_templates' ), 10 );
	}

	/**
	 * Scripts
	 *
	 * Register and enqueue all scripts for customizer
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return  void
	 */
	public function scripts() {
		$min = \defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'kit-chosen', CUSTOMIZELY_URL . 'assets/js/chosen.jquery' . $min . '.js', array(), '1.0.0', true );
		wp_enqueue_script( 'kit-color-picker', CUSTOMIZELY_URL . 'assets/js/color-picker-rgba' . $min . '.js', array( 'wp-color-picker' ), '1.0.0', true );
		wp_enqueue_script( 'kit-iconpicker', CUSTOMIZELY_URL . 'assets/js/kit-iconpicker' . $min . '.js', array(), '1.0.0', true );

		$dependency = array(
			'jquery',
			'customize-base',
			'customize-controls',
			'kit-iconpicker',
			'wp-color-picker',
			'kit-color-picker',
			'kit-chosen',
			'wp-i18n',
			'wp-api-fetch',
		);
		wp_enqueue_script( 'customizely', CUSTOMIZELY_URL . 'assets/js/customizely' . $min . '.js', $dependency, CUSTOMIZELY_VERSION, true );

		$icons     = customizely_get_icons();
		$icons_css = customizely_get_icons();
		$icons     = apply_filters( 'customizely_icons', $icons );
		$icons_css = apply_filters( 'customizely_icons_css', $icons_css );
		$icons     = \wp_json_encode( $icons );
		$icons_css = \wp_json_encode( $icons_css );

		$inline_scripts  = '';
		$inline_scripts .= "var cmlyIcons=$icons;";
		$inline_scripts .= "var cmlyIconsCSS=$icons_css;";

		wp_add_inline_script( 'customizely', $inline_scripts, 'before' );
	}

	/**
	 * Styles
	 *
	 * Register and enqueue all styles for customizer
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return  void
	 */
	public function styles() {
		$min = \defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'kit-iconpicker', CUSTOMIZELY_URL . 'assets/css/kit-iconpicker' . $min . '.css', array(), '1.0.0' );
		wp_enqueue_style( 'kit-chosen', CUSTOMIZELY_URL . 'assets/css/chosen' . $min . '.css', array(), '1.0.0' );
		wp_enqueue_style( 'customizely', CUSTOMIZELY_URL . 'assets/css/customizely' . $min . '.css', array( 'kit-iconpicker', 'kit-chosen' ), CUSTOMIZELY_VERSION );
	}

	/**
	 * Add Panel.
	 *
	 * Add panel at customize
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $id Unique ID for panel.
	 * @param array  $args Array of properties for the new Panel object.
	 *
	 * @return void
	 */
	public function add_panel( $id, $args = array() ) {
		$this->wp_manager->add_panel( $id, $args );
	}

	/**
	 * Add Section.
	 *
	 * Add section at Section.
	 *
	 * @param string $id Unique ID for section.
	 * @param array  $args Array of properties for the new Section object.
	 *
	 * @return void
	 */
	public function add_section( $id, $args = array() ) {
		$this->wp_manager->add_section( $id, $args );
	}

	/**
	 * Add Section.
	 *
	 * Add section at Section.
	 *
	 * @param string $id Unique ID for section.
	 * @param array  $args Array of properties for the new Section object.
	 *
	 * @return void
	 */
	public function add_control( $id, $args = array() ) {
		$args['settings']['sanitize_callback'] = $this->sanitize_cbs[ $args['args']['type'] ];

		$this->wp_manager->add_setting( $id, $args['settings'] );

		if ( isset( $args['args']['responsive'] ) && $args['args']['responsive'] ) {
			$settings_laptop            = $args['settings'];
			$settings_laptop['default'] = isset( $settings_laptop['default_laptop'] ) ? $settings_laptop['default_laptop'] : $settings_laptop['default'];
			$this->wp_manager->add_setting( $id . '_laptop', $settings_laptop );

			$settings_tablet            = $args['settings'];
			$settings_tablet['default'] = isset( $settings_tablet['default_tablet'] ) ? $settings_tablet['default_tablet'] : $settings_tablet['default'];
			$this->wp_manager->add_setting( $id . '_tablet', $settings_tablet );

			$settings_ml            = $args['settings'];
			$settings_ml['default'] = isset( $settings_ml['default_mobile_landscape'] ) ? $settings_ml['default_mobile_landscape'] : $settings_ml['default'];
			$this->wp_manager->add_setting( $id . '_mobile_landscape', $settings_ml );

			$settings_mobile            = $args['settings'];
			$settings_mobile['default'] = isset( $settings_mobile['default_mobile'] ) ? $settings_mobile['default_mobile'] : $settings_mobile['default'];
			$this->wp_manager->add_setting( $id . '_mobile', $settings_mobile );

			$args['args']['settings'] = array(
				'default'          => $id,
				'desktop'          => $id,
				'laptop'           => $id . '_laptop',
				'tablet'           => $id . '_tablet',
				'mobile-landscape' => $id . '_mobile_landscape',
				'mobile'           => $id . '_mobile',
			);
		}

		$control_class = $this->get_control( $args['args']['type'] );
		$this->wp_manager->add_control( new $control_class( $this->wp_manager, $id, $args['args'] ) );
	}

	/**
	 * Register Controls.
	 *
	 * All controls will be register here with 'register_control' filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $type Control type.
	 * @param string $class Control class as string.
	 * @param string $sanitize Control sanitize callback.
	 *
	 * @return void
	 */
	public function register_control( $type, $class, $sanitize = 'sanitize_text_field' ) {
		$this->controls[ $type ]     = $class;
		$this->sanitize_cbs[ $type ] = $sanitize;
	}

	/**
	 * Get Controls.
	 *
	 * Get All controls as array.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array $this->controls will be return.
	 */
	public function get_controls() {
		return $this->controls;
	}

	/**
	 * Get Controls.
	 *
	 * Get All controls as array.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $type Control type.
	 *
	 * @return array $this->controls will be return.
	 */
	public function get_control( $type = '' ) {
		if ( isset( $this->controls[ $type ] ) ) {
			return $this->controls[ $type ];
		}

		return 'WP_Customize_Control';
	}

	/**
	 * Print Control Templates.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function print_control_templates() {
		$controls = $this->get_controls();

		foreach ( $controls as $control_type => $control_class ) {
			if ( class_exists( $control_class ) ) {
				$control = new $control_class( $this->wp_manager, 'temp', array( 'settings' => array() ) );
				$control->print_template();
			}
		}
	}
}
