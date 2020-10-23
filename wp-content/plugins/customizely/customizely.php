<?php
/**
 * Plugin Name: Customizely
 * Plugin URI: https://wordpress.org/plugins/customizely/
 * Description: Style WP site with customizer
 * Version: 1.1.0
 * Author: KitThemes
 * Author URI: https://www.kitthemes.com/
 * License: GPLv3 or later
 * Text Domain: customizely
 * Domain Path: /languages/
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CUSTOMIZELY_VERSION', '1.1.0' );
define( 'CUSTOMIZELY_PATH', plugin_dir_path( __FILE__ ) );
define( 'CUSTOMIZELY_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', 'customizely_load_plugin_textdomain' );

/**
 * Load Customizely textdomain.
 *
 * Load gettext translate for Customizely text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function customizely_load_plugin_textdomain() {
	load_plugin_textdomain( 'customizely', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

if ( ! function_exists( 'cmly_fs' ) ) {
	/**
	 * Create a helper function for easy SDK access.
	 *
	 * @return Freemius Freemius instance
	 */
	function cmly_fs() {
		global $cmly_fs;

		if ( ! isset( $cmly_fs ) ) {
			// Include Freemius SDK.
			require_once CUSTOMIZELY_PATH . '/freemius/start.php';

			$cmly_fs = fs_dynamic_init(
				array(
					'id'             => '4431',
					'slug'           => 'customizely',
					'type'           => 'plugin',
					'public_key'     => 'pk_57b8e498ac6e99a4e39d278b19b55',
					'is_premium'     => false,
					'has_addons'     => false,
					'has_paid_plans' => false,
					'menu'           => array(
						'slug'    => 'customizely',
						'account' => false,
						'contact' => false,
					),
				)
			);
		}

		return $cmly_fs;
	}

	// Init Freemius.
	cmly_fs();
	// Signal that SDK was initiated.
	do_action( 'cmly_fs_loaded' );
}

// Require the main Plugin class.
require CUSTOMIZELY_PATH . 'includes/class-plugin.php';
