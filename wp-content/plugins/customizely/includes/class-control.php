<?php
/**
 * Customizely Control.
 *
 * Customizely main base control to register controls for addons.
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

namespace Customizely;

/**
 * Customizely Control.
 *
 * Customizely main base control class to register controls for addons.
 *
 * @since 1.0.0
 */
abstract class Control extends \WP_Customize_Control {
	/**
	 * Default.
	 *
	 * It will hold default value.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var mix
	 */
	public $default = null;

	/**
	 * Default Laptop.
	 *
	 * It will hold default value for laptop.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var mix
	 */
	public $default_laptop = null;

	/**
	 * Default Tablet.
	 *
	 * It will hold default value for tablet.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var mix
	 */
	public $default_tablet = null;

	/**
	 * Default Mobile Landscape.
	 *
	 * It will hold default value for mobile landscape mode.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var mix
	 */
	public $default_mobile_landscape = null;

	/**
	 * Default Mobile.
	 *
	 * It will hold default value for mobile.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var mix
	 */
	public $default_mobile = null;

	/**
	 * Depends.
	 *
	 * It will hold depends of control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var array
	 */
	public $depends = null;

	/**
	 * Choices.
	 *
	 * It will hold choices for choices supported control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var array
	 */
	public $choices = null;

	/**
	 * Placeholder.
	 *
	 * It will hold placeholder text for control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	public $placeholder = null;

	/**
	 * Responsive.
	 *
	 * It will hold that input will be responsive supported or not.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var bool
	 */
	public $responsive = false;

	/**
	 * CSS.
	 *
	 * It will hold css array.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var bool
	 */
	public $css = array();

	/**
	 * Constructor Method for control
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Argument of control.
	 *
	 * @return void
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		if ( $this->responsive && isset( $args['settings'] ) && is_array( $args['settings'] ) ) {
			$this->settings = array();
			foreach ( $args['settings'] as $setting_key => $setting ) {
				$this->settings[ $setting_key ] = $this->manager->get_setting( $setting );
			}
		}

	}

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
			$this->json['default']                  = $this->settings['default']->default;
			$this->json['default_laptop']           = $this->settings['laptop']->default;
			$this->json['default_tablet']           = $this->settings['tablet']->default;
			$this->json['default_mobile-landscape'] = $this->settings['mobile-landscape']->default;
			$this->json['default_mobile']           = $this->settings['mobile']->default;
		} else {
			$this->json['default'] = $this->setting->default;
		}

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}

		$this->json['value']       = $this->value();
		$this->json['link']        = $this->get_link();
		$this->json['id']          = $this->id;
		$this->json['depends']     = $this->depends;
		$this->json['choices']     = $this->choices;
		$this->json['placeholder'] = $this->placeholder;
		$this->json['responsive']  = $this->responsive;
		$this->json['css']         = $this->css;
	}
	/**
	 * Render Content
	 *
	 * Render content function for php render. But it will not use.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function render_content() {}

	/**
	 * Content Template
	 *
	 * Main template for Control.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function content_template() {
		?>
		<div class="customizely-control-wrap customizely-control-<?php echo esc_attr( $this->type ); ?>">
			<div class="customizely-control-title">
				<# if ( data.label ) { #>
					<label class="customize-control-title">{{{ data.label }}}<# if ( data.description ) { #><span class="customizely-toggle-desc"><i class="dashicons dashicons-editor-help"></i></span><# } #></label>
				<# } #>
				<# if ( data.responsive ) { #>
					<ul class="customizely-control-responsive">
						<li>
							<button class="customizely-device-desktop" type="button" data-device="desktop">
								<span class="dashicons dashicons-desktop"></span>
							</button>
						</li>
						<li>
							<button class="customizely-device-laptop" type="button" data-device="laptop">
								<span class="dashicons dashicons-laptop"></span>
							</button>
						</li>
						<li>
							<button class="customizely-device-tablet" type="button" data-device="tablet">
								<span class="dashicons dashicons-tablet"></span>
							</button>
						</li>
						<li>
							<button class="customizely-device-mobile-landscape" type="button" data-device="mobile-landscape">
								<span class="dashicons dashicons-smartphone"></span>
							</button>
						</li>
						<li>
							<button class="customizely-device-mobile" type="button" data-device="mobile">
								<span class="dashicons dashicons-smartphone"></span>
							</button>
						</li>
					</ul>
				<# } #>
				<# if ( data.description ) { #>
					<div class="description customize-control-description">{{{ data.description }}}</div>
				<# } #>
			</div>
			<div class="customizely-control-input">
				<?php $this->input_template(); ?>
			</div>
		</div>
		<?php
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
	abstract public function input_template();
}
