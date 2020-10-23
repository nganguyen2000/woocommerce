<?php
/**
 * Main Plugin file to run this plugin
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

namespace Customizely;

use Customizely\Customize;
use Customizely\Admin_Page;
use Customizely\Options;
use Customizely\Generate_CSS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizely Plugin
 *
 * The main class file to initialize this plugin.
 *
 * @since 1.0.0
 */
class Plugin {
	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	/**
	 * Customize.
	 *
	 * Holds the Customize instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Customizely\Customize
	 */
	public $customize = null;

	/**
	 * Options Instance.
	 *
	 * Holds the Options instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Customizely\Options
	 */
	public $options = null;

	/**
	 * Customizely Admin Page.
	 *
	 * It will hold customizely main admin page.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Customizely\Admin_Page
	 */
	public $page_customizely = null;

	/**
	 * Customizely Container Settings
	 *
	 * It will hold customizely container settings for builder.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Customizely\Admin_Page
	 */
	public $container_settings = array();

	/**
	 * Customizely Control Settings
	 *
	 * It will hold customizely control settings for builder.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Customizely\Admin_Page
	 */
	public $control_settings = array();

	/**
	 * Customizely Generate CSS
	 *
	 * It will hold customizely generate CSS object to render css.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Customizely\Generate_CSS
	 */
	public $css = array();

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'customizely' ), '1.0.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'customizely' ), '1.0.0' );
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			/**
			 * Customizely loaded.
			 *
			 * Fires when Customizely was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'customizely_loaded' );
		}

		return self::$instance;
	}

	/**
	 * Constructor Method for plugin
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		$this->set_container_settings();
		$this->includes();
		$this->init();

		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_pages' ), 999 );

		add_filter( 'customize_previewable_devices', array( $this, 'custom_devices' ) );
	}

	/**
	 * Set Container Settings
	 *
	 * This method will set defaults container settings.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function set_container_settings() {
		$common_settings                   = array(
			array(
				'id'      => 'general',
				'label'   => __( 'General', 'customizely' ),
				'options' => array(
					array(
						'id'      => 'id',
						'label'   => __( 'Panel ID', 'customizely' ),
						'type'    => 'text',
						'default' => '',
					),
					array(
						'id'      => 'title',
						'label'   => __( 'Title', 'customizely' ),
						'type'    => 'text',
						'default' => 'Untitled',
					),
					array(
						'id'      => 'description',
						'label'   => __( 'Description', 'customizely' ),
						'type'    => 'textarea',
						'default' => '',
					),
				),
			),
			array(
				'id'      => 'advance',
				'label'   => __( 'Advance', 'customizely' ),
				'options' => array(
					array(
						'id'      => 'priority',
						'label'   => __( 'Priority', 'customizely' ),
						'type'    => 'number',
						'default' => 160,
					),
					array(
						'id'      => 'capability',
						'label'   => __( 'Capability', 'customizely' ),
						'type'    => 'text',
						'default' => 'edit_theme_options',
					),
				),
			),
		);
		$this->container_settings['panel'] = array(
			'label'   => __( 'Panel', 'customizely' ),
			'options' => $common_settings,
		);

		$section_settings = $common_settings;

		$section_settings[0][0]['label'] = __( 'Section ID', 'customizely' );

		$section_settings[0][] = array(
			'id'      => 'description_hidden',
			'label'   => __( 'Hide description', 'customizely' ),
			'type'    => 'switch',
			'default' => false,
		);

		$this->container_settings['section'] = array(
			'label'   => __( 'Section', 'customizely' ),
			'options' => $section_settings,
		);
	}

	/**
	 * Initialize
	 *
	 * Initialize some classes which is not require hook.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return  void
	 */
	public function init() {
		$this->options = new Options();
		$this->css     = new Generate_CSS( $this->options );

		$this->register_builder_options();

		new Post_Type();
	}

	/**
	 * Include all files
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function includes() {
		require_once CUSTOMIZELY_PATH . 'includes/functions.php';
		require_once CUSTOMIZELY_PATH . 'includes/class-admin-page.php';
		require_once CUSTOMIZELY_PATH . 'includes/class-options.php';
		require_once CUSTOMIZELY_PATH . 'includes/class-customize.php';
		require_once CUSTOMIZELY_PATH . 'includes/class-post-type.php';
		require_once CUSTOMIZELY_PATH . 'includes/class-generate-css.php';

		if ( ! class_exists( 'WP_Customize_Control' ) ) {
			require_once ABSPATH . WPINC . '/class-wp-customize-control.php';
		}

		require_once CUSTOMIZELY_PATH . 'includes/class-control.php';
		require_once CUSTOMIZELY_PATH . 'includes/class-control-inline.php';

		require_once CUSTOMIZELY_PATH . 'controls/class-text.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-url.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-textarea.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-checkbox.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-radio.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-select.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-switch-input.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-range.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-number.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-icons.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-color.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-media.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-image-select.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-buttons.php';
		require_once CUSTOMIZELY_PATH . 'controls/class-color-palette.php';

		/**
		 * Include control from other plugins/themes
		 *
		 * @since 1.0.0
		 */
		do_action( 'customizely_include_controls' );
	}

	/**
	 * Register builder options
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return  void
	 */
	public function register_builder_options() {
		if ( ! $this->options ) {
			return;
		}

		$number_of_posts = apply_filters( 'register_builder_post_count', 1 );

		$posts = get_posts(
			array(
				'post_type'      => 'cmly_option',
				'posts_per_page' => $number_of_posts,
			)
		);

		if ( is_array( $posts ) && count( $posts ) ) {
			foreach ( $posts as $post ) {
				$options = get_post_meta( $post->ID, '_options_data', true );

				foreach ( $options as $option ) {
					if ( 'section' === $option['type'] ) {
						$this->process_section( $option );
					} elseif ( 'panel' === $option['type'] ) {
						$this->options->add_panel( $option['values']['id'], $option['values'] );
						if ( isset( $option['sections'] ) && is_array( $option['sections'] ) && count( $option['sections'] ) ) {
							foreach ( $option['sections'] as $section ) {
								$this->process_section( $section );
							}
						}
						$this->options->end_panel();
					}
				}
			}
		}
	}

	/**
	 * Process sections
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $section Section array.
	 *
	 * @return  void
	 */
	public function process_section( $section ) {
		$this->options->add_section( $section['values']['id'], $section['values'] );
		if ( isset( $section['controls'] ) && is_array( $section['controls'] ) && count( $section['controls'] ) ) {
			foreach ( $section['controls'] as $control ) {
				$args = $this->process_control_array( $control['values'], $control['type'] );
				$this->options->add_control( $control['values']['id'], $args[0], $args[1] );
			}
		}
		$this->options->end_section();
	}

	/**
	 * Process control array
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array  $values  Array of values.
	 * @param string $type Control type.
	 *
	 * @return  array processed array for control
	 */
	public function process_control_array( $values, $type = 'cmly_text' ) {
		$choices = array();
		if ( isset( $values['choices'] ) && is_array( $values['choices'] ) && count( $values['choices'] ) ) {
			foreach ( $values['choices'] as $choice ) {
				if ( ! \is_array( $choice ) ) {
					$choices = $values['choices'];
					break;
				}
				$choices[ $choice['value'] ] = $choice['label'];
			}
		}

		$processed_args = array(
			array(
				'label'       => isset( $values['title'] ) ? $values['title'] : '',
				'description' => isset( $values['description'] ) ? $values['description'] : '',
				'priority'    => isset( $values['priority'] ) ? $values['priority'] : 10,
				'type'        => $type,
				'choices'     => $choices,
				'responsive'  => isset( $values['responsive'] ) && $values['responsive'] ? true : false,
				'css'         => isset( $values['output'] ) && isset( $values['css'] ) && $values['output'] ? $values['css'] : array(),
			),
			array(
				'default'                  => isset( $values['default'] ) ? $values['default'] : '',
				'default_laptop'           => isset( $values['default_laptop'] ) ? $values['default_laptop'] : '',
				'default_tablet'           => isset( $values['default_tablet'] ) ? $values['default_tablet'] : '',
				'default_mobile_landscape' => isset( $values['default_mobile_landscape'] ) ? $values['default_mobile_landscape'] : '',
				'default_mobile'           => isset( $values['default_mobile'] ) ? $values['default_mobile'] : '',
				'capability'               => isset( $values['capability'] ) ? $values['capability'] : 'edit_theme_options',
				'transport'                => isset( $values['transport'] ) ? $values['transport'] : 'refresh',
				'theme_supports'           => isset( $values['theme_supports'] ) ? $values['theme_supports'] : '',
			),
		);

		$processed_args = apply_filters( "customizely_processed_{$type}_control_args", $processed_args, $values );

		return $processed_args;
	}

	/**
	 * Constructor Method for plugin
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param WP_Customize_Manager $wp_customize   instance of WP_Customize_Manager.
	 *
	 * @return void
	 */
	public function customize_register( $wp_customize ) {
		/**
		 * Customizely before customize Init.
		 *
		 * It will fire before register of Customize
		 *
		 * @since 1.0.0
		 */
		do_action( 'customizely_before_customize_init' );

		// Init Customize class.
		$this->customize = new Customize( $wp_customize );

		/**
		 * Customizely after customize Init.
		 *
		 * It will fire after register of Customize
		 *
		 * @since 1.0.0
		 */
		do_action( 'customizely_after_customize_init' );

		$panels   = $this->options->get_panels();
		$sections = $this->options->get_sections();
		$controls = $this->options->get_controls();

		if ( is_array( $panels ) && count( $panels ) ) {
			foreach ( $panels as $panel_id => $panel ) {
				$this->customize->add_panel( $panel_id, $panel );
			}
		}

		if ( is_array( $sections ) && count( $sections ) ) {
			foreach ( $sections as $section_id => $section ) {
				$this->customize->add_section( $section_id, $section );
			}
		}

		if ( is_array( $controls ) && count( $controls ) ) {
			foreach ( $controls as $control_id => $control ) {
				$this->customize->add_control( $control_id, $control );
			}
		}

		/**
		 * Customizely before customize Init.
		 *
		 * It will fire before register of Customize
		 *
		 * @since 1.0.0
		 *
		 * @param Customizely\Customize $this->customize Customizely\Customize instance.
		 */
		do_action( 'customizely_register_customize', $this->customize );

		/**
		 * Customizely customize register end.
		 *
		 * It will fire after register of Customize and options.
		 *
		 * @since 1.0.0
		 */
		do_action( 'customizely_customize_register_end' );
	}

	/**
	 * Set Control Settings
	 *
	 * Set control settings for builder options.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $type Control type.
	 * @param array $args Control settings array.
	 *
	 * @return  void
	 */
	public function set_control_settings( $type, $args ) {
		$this->control_settings[ $type ] = $args;
	}

	/**
	 * Register Admin Pages
	 *
	 * All admin pages will be register here.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_admin_pages() {
		$this->add_page_customizely();
	}

	/**
	 * Add Page Customizely
	 *
	 * Add plugin main admin page.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function add_page_customizely() {
		$this->page_customizely = new Admin_Page( 'customizely', __( 'Customizely', 'customizely' ), 'manage_options', CUSTOMIZELY_PATH . 'views/customizely.php' );

		$ctp_options = get_posts(
			array(
				'post_type'      => 'cmly_option',
				'posts_per_page' => 1,
			)
		);

		$option_id = '';
		if ( \is_array( $ctp_options ) && \count( $ctp_options ) ) {
			$option_id = $ctp_options[0]->ID;
		}

		$inline_script  = 'var cmly_controls = ' . wp_json_encode( $this->control_settings ) . ';';
		$inline_script .= 'var cmly_containers = ' . wp_json_encode( $this->container_settings ) . ';';
		$inline_script .= 'var cmlyAjaxUrl = "' . admin_url( 'admin-ajax.php' ) . '";';
		$inline_script .= 'var cmlyNonce = "' . wp_create_nonce( 'customizely_save_options' ) . '";';
		$inline_script .= 'var cmlyGetOptionNonce = "' . wp_create_nonce( 'customizely_get_options' ) . '";';
		$inline_script .= 'var cmlyPostID = "' . sanitize_key( $option_id ) . '";';

		$min = \defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$this->page_customizely
			->set_icon( CUSTOMIZELY_URL . 'assets/images/icon.svg' )
			->set_style( 'wp-components' )
			->set_style(
				'customizely-builder',
				array(
					'src' => CUSTOMIZELY_URL . 'assets/css/builder' . $min . '.css',
					'ver' => CUSTOMIZELY_VERSION,
				)
			)
			->set_script( 'wp-i18n' )
			->set_script( 'wp-components' )
			->set_script( 'react' )
			->set_script( 'react-dom' )
			->set_script( 'wp-api-fetch' )
			->enqueue_media()
			->set_script(
				'react-markdown',
				array(
					'src'       => CUSTOMIZELY_URL . 'assets/js/react-markdown.js',
					'deps'      => array( 'react', 'react-dom' ),
					'ver'       => CUSTOMIZELY_VERSION,
					'in_footer' => true,
				)
			)
			->set_script(
				'customizely-builder',
				array(
					'src'       => CUSTOMIZELY_URL . 'assets/js/builder' . $min . '.js',
					'deps'      => array( 'react', 'react-dom', 'wp-i18n', 'react-markdown', 'wp-components' ),
					'ver'       => CUSTOMIZELY_VERSION,
					'in_footer' => true,
				)
			)
			->add_inline_script(
				'customizely-builder',
				$inline_script,
				'before'
			)
			->done();
	}

	/**
	 * Custom Devices
	 *
	 * Add new devices to this array.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $devices Default array of devices.
	 *
	 * @return array Filtered new devices array.
	 */
	public function custom_devices( $devices ) {
		$laptop           = array(
			'laptop' => array(
				'label' => __( 'Enter laptop preview mode', 'customizely' ),
			),
		);
		$mobile_landscape = array(
			'mobile-landscape' => array(
				'label' => __( 'Enter mobile landscape preview mode', 'customizely' ),
			),
		);

		$devices = array_slice( $devices, 0, 1, true ) + $laptop + array_slice( $devices, 1, 1, true ) + $mobile_landscape + array_slice( $devices, 1, null, true );

		return $devices;
	}
}

Plugin::instance();
