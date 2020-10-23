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

use Customizely\Control;

/**
 * Customizely Control.
 *
 * Customizely main base control class to register controls for addons.
 *
 * @since 1.0.0
 */
abstract class Control_Inline extends Control {
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
		<div class="customizely-control-wrap customizely-inline-control customizely-control-<?php echo esc_attr( $this->type ); ?>">
			<div class="customizely-control-title">
				<# if ( data.label ) { #>
					<label class="customize-control-title">{{{ data.label }}} <# if ( data.description ) { #><span class="customizely-toggle-desc"><i class="dashicons dashicons-editor-help"></i></span><# } #></label>
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
			</div>
			<div class="customizely-control-input">
				<?php $this->input_template(); ?>
			</div>
			<# if ( data.description ) { #>
				<div class="description customize-control-description">{{{ data.description }}}</div>
			<# } #>
		</div>
		<?php
	}
}
