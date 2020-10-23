<?php
/**
 * Functions
 *
 * This file will contain all functions
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main function to get all instance.
 *
 * @since 1.0.0
 *
 * @return  Customizely\Plugin|bool
 */
function customizely() {
	$plugin = Customizely\Plugin::$instance;
	if ( $plugin ) {
		return $plugin;
	}

	return false;
}

/**
 * Customizely Register Control.
 *
 * Register control for customizely.
 *
 * @since 1.0.0
 *
 * @param string $type Control type.
 * @param string $class Control class as string.
 * @param string $sanitize Control sanitize callback.
 *
 * @return void
 */
function customizely_register_control( $type, $class, $sanitize = 'sanitize_text_field' ) {
	$plugin = customizely();
	if ( $plugin && $plugin->customize ) {
		$plugin->customize->register_control( $type, $class );
	}
}

/**
 * Set Control Settings
 *
 * Set control settings for builder options.
 *
 * @since 1.0.0
 *
 * @param array $type Control type.
 * @param array $args Control settings array.
 *
 * @return void
 */
function customizely_set_control_settings( $type, $args ) {
	$plugin = customizely();
	if ( $plugin ) {
		$plugin->set_control_settings( $type, $args );
	}
}

/**
 * Get Control Common Settings
 *
 * @since 1.0.0
 *
 * @return array Default settings array
 */
function customizely_get_control_common_settings() {
	$responsive_depends = array(
		array(
			'id'        => 'responsive',
			'condition' => '=',
			'value'     => true,
		),
	);

	return array(
		array(
			'id'      => 'general',
			'label'   => __( 'General', 'customizely' ),
			'options' => array(
				array(
					'id'      => 'id',
					'label'   => __( 'ID', 'customizely' ),
					'info'    => __( 'Unique ID for input.', 'customizely' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'id'      => 'title',
					'label'   => __( 'Title', 'customizely' ),
					'info'    => __( 'Title of this input.', 'customizely' ),
					'type'    => 'text',
					'default' => 'Untitled',
				),
				array(
					'id'      => 'description',
					'label'   => __( 'Description', 'customizely' ),
					'info'    => __( 'Description or help text for this input.', 'customizely' ),
					'type'    => 'textarea',
					'default' => '',
				),
			),
		),
		array(
			'id'      => 'values',
			'label'   => __( 'Values', 'customizely' ),
			'options' => array(
				array(
					'id'      => 'responsive',
					'label'   => __( 'Responsive', 'customizely' ),
					'info'    => __( 'Turn on or off resposive input.', 'customizely' ),
					'type'    => 'switch',
					'default' => false,
				),
				array(
					'id'      => 'default',
					'label'   => __( 'Default', 'customizely' ),
					'info'    => __( 'Default value for this input.', 'customizely' ),
					'type'    => 'text',
					'default' => '',
				),
				array(
					'id'      => 'default_laptop',
					'label'   => __( 'Default (Laptop)', 'customizely' ),
					'info'    => __( 'Default value for this input for only Laptop view.', 'customizely' ),
					'type'    => 'text',
					'default' => '',
					'depends' => $responsive_depends,
				),
				array(
					'id'      => 'default_tablet',
					'label'   => __( 'Default (Tablet)', 'customizely' ),
					'info'    => __( 'Default value for this input for only Tablet view.', 'customizely' ),
					'type'    => 'text',
					'default' => '',
					'depends' => $responsive_depends,
				),
				array(
					'id'      => 'default_mobile_landscape',
					'label'   => __( 'Default (Mobile Landscape)', 'customizely' ),
					'info'    => __( 'Default value for this input for only Mobile Landscape view.', 'customizely' ),
					'type'    => 'text',
					'default' => '',
					'depends' => $responsive_depends,
				),
				array(
					'id'      => 'default_mobile',
					'label'   => __( 'Default (Mobile)', 'customizely' ),
					'info'    => __( 'Default value for this input for only Mobile view.', 'customizely' ),
					'type'    => 'text',
					'default' => '',
					'depends' => $responsive_depends,
				),
			),
		),
		array(
			'id'      => 'css',
			'label'   => __( 'CSS', 'customizely' ),
			'options' => array(
				array(
					'id'      => 'output',
					'label'   => __( 'CSS Output', 'customizely' ),
					'info'    => __( 'Use as CSS output', 'customizely' ),
					'type'    => 'switch',
					'default' => false,
				),
				array(
					'id'       => 'css',
					'label'    => __( 'CSS', 'customizely' ),
					'type'     => 'repeatable',
					'titleKey' => 'selector',
					'default'  => array(
						array(
							'selector' => 'body',
							'property' => '',
							'replace'  => '',
						),
					),
					'options'  => array(
						array(
							'id'      => 'selector',
							'label'   => __( 'Selector', 'customizely' ),
							'info'    => __( 'Valid CSS selector. Multiple selector can be separated by ",".', 'customizely' ),
							'type'    => 'text',
							'default' => '',
						),
						array(
							'id'      => 'property',
							'label'   => __( 'Property', 'customizely' ),
							'info'    => __( 'Valid CSS Property. Accept only single property.', 'customizely' ),
							'type'    => 'text',
							'default' => '',
						),
						array(
							'id'      => 'replace',
							'label'   => __( 'Replace', 'customizely' ),
							'info'    => __( 'Custom value with extended values. Use `{{value}}` tag to replace value. Example for border: `1px {{value}} #000`', 'customizely' ),
							'type'    => 'text',
							'default' => '',
						),
					),
					'depends'  => array(
						array(
							'id'        => 'output',
							'condition' => '=',
							'value'     => true,
						),
					),
				),
			),
		),
		array(
			'id'      => 'advance',
			'label'   => __( 'Advance', 'customizely' ),
			'options' => array(
				array(
					'id'      => 'transport',
					'label'   => __( 'Transport', 'customizely' ),
					'info'    => __( 'Options for rendering the live preview of changes in Customizer. Using **Refresh** makes the change visible by reloading the whole preview. Using **Post Message** allows to change without reloading.', 'customizely' ),
					'type'    => 'select',
					'choices' => array(
						'refresh'     => __( 'Refresh', 'customizely' ),
						'postMessage' => __( 'Post Message', 'customizely' ),
					),
					'default' => 'postMessage',
				),
				array(
					'id'      => 'priority',
					'label'   => __( 'Priority', 'customizely' ),
					'info'    => __( 'Order priority to load the input.', 'customizely' ),
					'type'    => 'number',
					'default' => 10,
				),
				array(
					'id'      => 'capability',
					'label'   => __( 'Capability', 'customizely' ),
					'info'    => __( 'Capability required for the input.', 'customizely' ),
					'type'    => 'text',
					'default' => 'edit_theme_options',
				),
			),
		),
	);
}

/**
 * Get Default Values Index
 *
 * Get all default values index as array
 *
 * @since 1.0.0
 *
 * @return array
 */
function customizely_get_deafult_values_index() {
	return array(
		'tab'   => 1,
		'items' => array( 1, 2, 3, 4, 5 ),
	);
}

/**
 * Get advance tab index
 *
 * Get Advance tab index
 *
 * @since 1.0.0
 *
 * @return int
 */
function customizely_get_advance_tab_index() {
	return 3;
}

/**
 * Customizer Value showing API
 *
 * @since 1.0.0
 *
 * @param array $atts  Shortcode attributes.
 *
 * @return  string Shortcode view
 */
function customizely_get_value_cb( $atts ) {
	$attr = shortcode_atts(
		array(
			'id'      => '',
			'default' => '',
		),
		$atts
	);
	// phpcs:ignore
	return print_r( get_theme_mod( $attr['id'], $attr['default'] ), true );
}

add_shortcode( 'cmly', 'customizely_get_value_cb' );

/**
 * Save Options
 *
 * Save customizer options to DB
 *
 * @since 1.0.0
 *
 * @return  void
 */
function customizely_save_options() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'customizely_save_options' ) ) {
		wp_send_json_error( __( 'Not authorized', 'customizely' ) );
	}

	$post_id = isset( $_POST['post_id'] ) ? sanitize_key( $_POST['post_id'] ) : '';

	$options = array();

	if ( isset( $_POST['options'] ) ) {
		// phpcs:ignore
		$options = customizely_sanitize_array( $_POST['options'] ); // Perfectly sanitized.
	}

	if ( empty( $post_id ) ) {
		$ctp_options = get_posts(
			array(
				'post_type'      => 'cmly_option',
				'posts_per_page' => 1,
			)
		);
		if ( \is_array( $ctp_options ) && \count( $ctp_options ) ) {
			$post_id = $ctp_options[0]->ID;
		} else {
			$post_id = wp_insert_post(
				array(
					'post_title' => __( 'Untitled', 'customizely' ),
					'post_type'  => 'cmly_option',
				),
				true
			);

			if ( is_wp_error( $post_id ) ) {
				wp_send_json_error( __( 'Something wrong!', 'customizely' ) );
			}

			$post_id = wp_update_post(
				array(
					'ID'          => $post_id,
					// translators: Post ID.
					'post_title'  => sprintf( __( 'Untitled #%d', 'customizely' ), $post_id ),
					'post_type'   => 'cmly_option',
					'post_status' => 'publish',
				),
				true
			);

			if ( is_wp_error( $post_id ) ) {
				wp_send_json_error( __( 'Something wrong!', 'customizely' ) );
			}
		}
	}

	$meta_id = update_post_meta( $post_id, '_options_data', $options );

	if ( false !== $meta_id ) {
		wp_send_json_success( __( 'Successfully saved!', 'customizely' ) );
	}

	wp_send_json_error( __( 'Nothing changed!', 'customizely' ) );
}

add_action( 'wp_ajax_customizely_save_options', 'customizely_save_options' );

/**
 * Get Options by Ajax
 *
 * With ajax call it will send options array to client
 *
 * @since 1.0.0
 *
 * @return void
 */
function customizely_get_options() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'customizely_get_options' ) ) {
		wp_send_json_error( __( 'Not authorized', 'customizely' ) );
	}

	$post_id = isset( $_POST['post_id'] ) ? sanitize_key( $_POST['post_id'] ) : '';

	if ( empty( $post_id ) ) {
		wp_send_json_error( __( 'Option ID is empty.', 'customizely' ) );
	}

	$options = get_post_meta( $post_id, '_options_data', true );

	if ( false !== $options ) {
		wp_send_json_success( $options );
	}

	wp_send_json_error( __( 'Something wrong.', 'customizely' ) );
}
add_action( 'wp_ajax_customizely_get_options', 'customizely_get_options' );

/**
 * Sanitize Array
 *
 * This function will sanitize array values to inser into DB.
 *
 * @since 1.0.0
 *
 * @param array $items Array items.
 *
 * @return  array Sanitized array
 */
function customizely_sanitize_array( &$items ) {
	if ( ! is_array( $items ) ) {
		return sanitize_text_field( $items );
	}

	$bool_values = array(
		'description_hidden',
		'no_unit',
		'rgba',
	);
	$int_values  = array(
		'priority',
	);

	if ( count( $items ) ) {
		foreach ( $items as $key => &$item ) {
			if ( ! is_array( $item ) ) {
				if ( in_array( $key, $bool_values, true ) ) {
					$item = is_string( $item ) ? ( 'true' === $item ? true : false ) : (bool) $item;
				} elseif ( in_array( $key, $int_values, true ) ) {
					$item = (int) $item;
				} elseif ( is_bool( $item ) ) {
					$item = (bool) $item;
				} elseif ( is_string( $item ) && ( 'false' === $item || 'true' === $item ) ) {
					$item = 'true' === $item ? true : false;
				} else {
					$item = sanitize_text_field( $item );
				}
			} elseif ( is_array( $item ) && count( $item ) === 0 ) {
				$item = array();
			} else {
				customizely_sanitize_array( $item );
			}
		}
	}

	return $items;
}

/**
 * Get Font Awesome Icons
 *
 * Get fontawesome icons as array
 *
 * @since 1.0.0
 *
 * @return array FontAwesome icons array
 */
function customizely_get_icons() {
	$font_awesome_icons = array(
		'dashicons dashicons-admin-appearance',
		'dashicons dashicons-admin-collapse',
		'dashicons dashicons-admin-comments',
		'dashicons dashicons-admin-customizer',
		'dashicons dashicons-admin-generic',
		'dashicons dashicons-admin-home',
		'dashicons dashicons-admin-links',
		'dashicons dashicons-admin-media',
		'dashicons dashicons-admin-multisite',
		'dashicons dashicons-admin-network',
		'dashicons dashicons-admin-page',
		'dashicons dashicons-admin-plugins',
		'dashicons dashicons-admin-post',
		'dashicons dashicons-admin-settings',
		'dashicons dashicons-admin-site-alt',
		'dashicons dashicons-admin-site-alt2',
		'dashicons dashicons-admin-site-alt3',
		'dashicons dashicons-admin-site',
		'dashicons dashicons-admin-tools',
		'dashicons dashicons-admin-users',
		'dashicons dashicons-album',
		'dashicons dashicons-align-center',
		'dashicons dashicons-align-left',
		'dashicons dashicons-align-none',
		'dashicons dashicons-align-right',
		'dashicons dashicons-analytics',
		'dashicons dashicons-archive',
		'dashicons dashicons-arrow-down-alt',
		'dashicons dashicons-arrow-down-alt2',
		'dashicons dashicons-arrow-down',
		'dashicons dashicons-arrow-left-alt',
		'dashicons dashicons-arrow-left-alt2',
		'dashicons dashicons-arrow-left',
		'dashicons dashicons-arrow-right-alt',
		'dashicons dashicons-arrow-right-alt2',
		'dashicons dashicons-arrow-right',
		'dashicons dashicons-arrow-up-alt',
		'dashicons dashicons-arrow-up-alt2',
		'dashicons dashicons-arrow-up-duplicate',
		'dashicons dashicons-arrow-up',
		'dashicons dashicons-art',
		'dashicons dashicons-awards',
		'dashicons dashicons-backup',
		'dashicons dashicons-book-alt',
		'dashicons dashicons-book',
		'dashicons dashicons-buddicons-activity',
		'dashicons dashicons-buddicons-bbpress-logo',
		'dashicons dashicons-buddicons-buddypress-logo',
		'dashicons dashicons-buddicons-community',
		'dashicons dashicons-buddicons-forums',
		'dashicons dashicons-buddicons-friends',
		'dashicons dashicons-buddicons-groups',
		'dashicons dashicons-buddicons-pm',
		'dashicons dashicons-buddicons-replies',
		'dashicons dashicons-buddicons-topics',
		'dashicons dashicons-buddicons-tracking',
		'dashicons dashicons-building',
		'dashicons dashicons-businessman',
		'dashicons dashicons-businessperson',
		'dashicons dashicons-businesswoman',
		'dashicons dashicons-calendar-alt',
		'dashicons dashicons-calendar',
		'dashicons dashicons-camera-alt',
		'dashicons dashicons-camera',
		'dashicons dashicons-carrot',
		'dashicons dashicons-cart',
		'dashicons dashicons-category',
		'dashicons dashicons-chart-area',
		'dashicons dashicons-chart-bar',
		'dashicons dashicons-chart-line',
		'dashicons dashicons-chart-pie',
		'dashicons dashicons-clipboard',
		'dashicons dashicons-clock',
		'dashicons dashicons-cloud',
		'dashicons dashicons-code-standards',
		'dashicons dashicons-color-picker',
		'dashicons dashicons-controls-back',
		'dashicons dashicons-controls-forward',
		'dashicons dashicons-controls-pause',
		'dashicons dashicons-controls-play',
		'dashicons dashicons-controls-repeat',
		'dashicons dashicons-controls-skipback',
		'dashicons dashicons-controls-skipforward',
		'dashicons dashicons-controls-volumeoff',
		'dashicons dashicons-controls-volumeon',
		'dashicons dashicons-dashboard',
		'dashicons dashicons-desktop',
		'dashicons dashicons-dismiss',
		'dashicons dashicons-download',
		'dashicons dashicons-edit-large',
		'dashicons dashicons-edit',
		'dashicons dashicons-editor-aligncenter',
		'dashicons dashicons-editor-alignleft',
		'dashicons dashicons-editor-alignright',
		'dashicons dashicons-editor-bold',
		'dashicons dashicons-editor-break',
		'dashicons dashicons-editor-code-duplicate',
		'dashicons dashicons-editor-code',
		'dashicons dashicons-editor-contract',
		'dashicons dashicons-editor-customchar',
		'dashicons dashicons-editor-expand',
		'dashicons dashicons-editor-help',
		'dashicons dashicons-editor-indent',
		'dashicons dashicons-editor-insertmore',
		'dashicons dashicons-editor-italic',
		'dashicons dashicons-editor-justify',
		'dashicons dashicons-editor-kitchensink',
		'dashicons dashicons-editor-ltr',
		'dashicons dashicons-editor-ol-rtl',
		'dashicons dashicons-editor-ol',
		'dashicons dashicons-editor-outdent',
		'dashicons dashicons-editor-paragraph',
		'dashicons dashicons-editor-paste-text',
		'dashicons dashicons-editor-paste-word',
		'dashicons dashicons-editor-quote',
		'dashicons dashicons-editor-removeformatting',
		'dashicons dashicons-editor-rtl',
		'dashicons dashicons-editor-spellcheck',
		'dashicons dashicons-editor-strikethrough',
		'dashicons dashicons-editor-table',
		'dashicons dashicons-editor-textcolor',
		'dashicons dashicons-editor-ul',
		'dashicons dashicons-editor-underline',
		'dashicons dashicons-editor-unlink',
		'dashicons dashicons-editor-video',
		'dashicons dashicons-email-alt',
		'dashicons dashicons-email-alt2',
		'dashicons dashicons-email',
		'dashicons dashicons-excerpt-view',
		'dashicons dashicons-external',
		'dashicons dashicons-facebook-alt',
		'dashicons dashicons-facebook',
		'dashicons dashicons-feedback',
		'dashicons dashicons-filter',
		'dashicons dashicons-flag',
		'dashicons dashicons-format-aside',
		'dashicons dashicons-format-audio',
		'dashicons dashicons-format-chat',
		'dashicons dashicons-format-gallery',
		'dashicons dashicons-format-image',
		'dashicons dashicons-format-quote',
		'dashicons dashicons-format-status',
		'dashicons dashicons-format-video',
		'dashicons dashicons-forms',
		'dashicons dashicons-googleplus',
		'dashicons dashicons-grid-view',
		'dashicons dashicons-groups',
		'dashicons dashicons-hammer',
		'dashicons dashicons-heart',
		'dashicons dashicons-hidden',
		'dashicons dashicons-id-alt',
		'dashicons dashicons-id',
		'dashicons dashicons-image-crop',
		'dashicons dashicons-image-filter',
		'dashicons dashicons-image-flip-horizontal',
		'dashicons dashicons-image-flip-vertical',
		'dashicons dashicons-image-rotate-left',
		'dashicons dashicons-image-rotate-right',
		'dashicons dashicons-image-rotate',
		'dashicons dashicons-images-alt',
		'dashicons dashicons-images-alt2',
		'dashicons dashicons-index-card',
		'dashicons dashicons-info',
		'dashicons dashicons-instagram',
		'dashicons dashicons-laptop',
		'dashicons dashicons-layout',
		'dashicons dashicons-leftright',
		'dashicons dashicons-lightbulb',
		'dashicons dashicons-list-view',
		'dashicons dashicons-location-alt',
		'dashicons dashicons-location',
		'dashicons dashicons-lock-duplicate',
		'dashicons dashicons-lock',
		'dashicons dashicons-marker',
		'dashicons dashicons-media-archive',
		'dashicons dashicons-media-audio',
		'dashicons dashicons-media-code',
		'dashicons dashicons-media-default',
		'dashicons dashicons-media-document',
		'dashicons dashicons-media-interactive',
		'dashicons dashicons-media-spreadsheet',
		'dashicons dashicons-media-text',
		'dashicons dashicons-media-video',
		'dashicons dashicons-megaphone',
		'dashicons dashicons-menu-alt',
		'dashicons dashicons-menu-alt2',
		'dashicons dashicons-menu-alt3',
		'dashicons dashicons-menu',
		'dashicons dashicons-microphone',
		'dashicons dashicons-migrate',
		'dashicons dashicons-minus',
		'dashicons dashicons-money',
		'dashicons dashicons-move',
		'dashicons dashicons-nametag',
		'dashicons dashicons-networking',
		'dashicons dashicons-no-alt',
		'dashicons dashicons-no',
		'dashicons dashicons-palmtree',
		'dashicons dashicons-paperclip',
		'dashicons dashicons-performance',
		'dashicons dashicons-phone',
		'dashicons dashicons-playlist-audio',
		'dashicons dashicons-playlist-video',
		'dashicons dashicons-plugins-checked',
		'dashicons dashicons-plus-alt',
		'dashicons dashicons-plus-alt2',
		'dashicons dashicons-plus',
		'dashicons dashicons-portfolio',
		'dashicons dashicons-post-status',
		'dashicons dashicons-pressthis',
		'dashicons dashicons-products',
		'dashicons dashicons-randomize',
		'dashicons dashicons-redo',
		'dashicons dashicons-rest-api',
		'dashicons dashicons-rss',
		'dashicons dashicons-schedule',
		'dashicons dashicons-screenoptions',
		'dashicons dashicons-search',
		'dashicons dashicons-share-alt',
		'dashicons dashicons-share-alt2',
		'dashicons dashicons-share',
		'dashicons dashicons-shield-alt',
		'dashicons dashicons-shield',
		'dashicons dashicons-slides',
		'dashicons dashicons-smartphone',
		'dashicons dashicons-smiley',
		'dashicons dashicons-sort',
		'dashicons dashicons-sos',
		'dashicons dashicons-star-empty',
		'dashicons dashicons-star-filled',
		'dashicons dashicons-star-half',
		'dashicons dashicons-sticky',
		'dashicons dashicons-store',
		'dashicons dashicons-tablet',
		'dashicons dashicons-tag',
		'dashicons dashicons-tagcloud',
		'dashicons dashicons-testimonial',
		'dashicons dashicons-text-page',
		'dashicons dashicons-text',
		'dashicons dashicons-thumbs-down',
		'dashicons dashicons-thumbs-up',
		'dashicons dashicons-tickets-alt',
		'dashicons dashicons-tickets',
		'dashicons dashicons-tide',
		'dashicons dashicons-translation',
		'dashicons dashicons-trash',
		'dashicons dashicons-twitter-alt',
		'dashicons dashicons-twitter',
		'dashicons dashicons-undo',
		'dashicons dashicons-universal-access-alt',
		'dashicons dashicons-universal-access',
		'dashicons dashicons-unlock',
		'dashicons dashicons-update-alt',
		'dashicons dashicons-update',
		'dashicons dashicons-upload',
		'dashicons dashicons-vault',
		'dashicons dashicons-video-alt',
		'dashicons dashicons-video-alt2',
		'dashicons dashicons-video-alt3',
		'dashicons dashicons-visibility',
		'dashicons dashicons-warning',
		'dashicons dashicons-welcome-add-page',
		'dashicons dashicons-welcome-comments',
		'dashicons dashicons-welcome-learn-more',
		'dashicons dashicons-welcome-view-site',
		'dashicons dashicons-welcome-widgets-menus',
		'dashicons dashicons-welcome-write-blog',
		'dashicons dashicons-wordpress-alt',
		'dashicons dashicons-wordpress',
		'dashicons dashicons-yes-alt',
		'dashicons dashicons-yes',
		'dashicons dashicons-editor-distractionfree',
		'dashicons dashicons-exerpt-view',
		'dashicons dashicons-format-links',
		'dashicons dashicons-format-standard',
		'dashicons dashicons-post-trash',
		'dashicons dashicons-share1',
		'dashicons dashicons-welcome-edit-page',
	);

	return $font_awesome_icons;
}

/**
 * Get Icons CSS
 *
 * Return icons for CSS content property
 *
 * @since 1.0.0
 *
 * @return array
 */
function customizely_get_icons_css() {
	$icons = array(
		'\f100' => 'admin appearance',
		'\f148' => 'admin collapse',
		'\f101' => 'admin comments',
		'\f540' => 'admin customizer',
		'\f111' => 'admin generic',
		'\f102' => 'admin home',
		'\f103' => 'admin links',
		'\f104' => 'admin media',
		'\f541' => 'admin multisite',
		'\f112' => 'admin network',
		'\f105' => 'admin page',
		'\f106' => 'admin plugins',
		'\f109' => 'admin post',
		'\f108' => 'admin settings',
		'\f11d' => 'admin site alt',
		'\f11e' => 'admin site alt2',
		'\f11f' => 'admin site alt3',
		'\f319' => 'admin site',
		'\f107' => 'admin tools',
		'\f110' => 'admin users',
		'\f514' => 'album',
		'\f134' => 'align center',
		'\f135' => 'align left',
		'\f138' => 'align none',
		'\f136' => 'align right',
		'\f183' => 'analytics',
		'\f480' => 'archive',
		'\f346' => 'arrow down alt',
		'\f347' => 'arrow down alt2',
		'\f140' => 'arrow down',
		'\f340' => 'arrow left alt',
		'\f341' => 'arrow left alt2',
		'\f141' => 'arrow left',
		'\f344' => 'arrow right alt',
		'\f345' => 'arrow right alt2',
		'\f139' => 'arrow right',
		'\f342' => 'arrow up alt',
		'\f343' => 'arrow up alt2',
		'\f143' => 'arrow up duplicate',
		'\f142' => 'arrow up',
		'\f309' => 'art',
		'\f313' => 'awards',
		'\f321' => 'backup',
		'\f331' => 'book alt',
		'\f330' => 'book',
		'\f452' => 'buddicons activity',
		'\f477' => 'buddicons bbpress logo',
		'\f448' => 'buddicons buddypress logo',
		'\f453' => 'buddicons community',
		'\f449' => 'buddicons forums',
		'\f454' => 'buddicons friends',
		'\f456' => 'buddicons groups',
		'\f457' => 'buddicons pm',
		'\f451' => 'buddicons replies',
		'\f450' => 'buddicons topics',
		'\f455' => 'buddicons tracking',
		'\f512' => 'building',
		'\f338' => 'businessman',
		'\f12e' => 'businessperson',
		'\f12f' => 'businesswoman',
		'\f508' => 'calendar alt',
		'\f145' => 'calendar',
		'\f129' => 'camera alt',
		'\f306' => 'camera',
		'\f511' => 'carrot',
		'\f174' => 'cart',
		'\f318' => 'category',
		'\f239' => 'chart area',
		'\f185' => 'chart bar',
		'\f238' => 'chart line',
		'\f184' => 'chart pie',
		'\f481' => 'clipboard',
		'\f469' => 'clock',
		'\f176' => 'cloud',
		'\f13a' => 'code standards',
		'\f131' => 'color picker',
		'\f518' => 'controls back',
		'\f519' => 'controls forward',
		'\f523' => 'controls pause',
		'\f522' => 'controls play',
		'\f515' => 'controls repeat',
		'\f516' => 'controls skipback',
		'\f517' => 'controls skipforward',
		'\f520' => 'controls volumeoff',
		'\f521' => 'controls volumeon',
		'\f226' => 'dashboard',
		'\f472' => 'desktop',
		'\f153' => 'dismiss',
		'\f316' => 'download',
		'\f327' => 'edit large',
		'\f464' => 'edit',
		'\f207' => 'editor aligncenter',
		'\f206' => 'editor alignleft',
		'\f208' => 'editor alignright',
		'\f200' => 'editor bold',
		'\f474' => 'editor break',
		'\f494' => 'editor code duplicate',
		'\f475' => 'editor code',
		'\f506' => 'editor contract',
		'\f220' => 'editor customchar',
		'\f211' => 'editor expand',
		'\f223' => 'editor help',
		'\f222' => 'editor indent',
		'\f209' => 'editor insertmore',
		'\f201' => 'editor italic',
		'\f214' => 'editor justify',
		'\f212' => 'editor kitchensink',
		'\f10c' => 'editor ltr',
		'\f12c' => 'editor ol rtl',
		'\f204' => 'editor ol',
		'\f221' => 'editor outdent',
		'\f476' => 'editor paragraph',
		'\f217' => 'editor paste text',
		'\f216' => 'editor paste word',
		'\f205' => 'editor quote',
		'\f218' => 'editor removeformatting',
		'\f320' => 'editor rtl',
		'\f210' => 'editor spellcheck',
		'\f224' => 'editor strikethrough',
		'\f535' => 'editor table',
		'\f215' => 'editor textcolor',
		'\f203' => 'editor ul',
		'\f213' => 'editor underline',
		'\f225' => 'editor unlink',
		'\f219' => 'editor video',
		'\f466' => 'email alt',
		'\f467' => 'email alt2',
		'\f465' => 'email',
		'\f164' => 'excerpt view',
		'\f504' => 'external',
		'\f305' => 'facebook alt',
		'\f304' => 'facebook',
		'\f175' => 'feedback',
		'\f536' => 'filter',
		'\f227' => 'flag',
		'\f123' => 'format aside',
		'\f127' => 'format audio',
		'\f125' => 'format chat',
		'\f161' => 'format gallery',
		'\f128' => 'format image',
		'\f122' => 'format quote',
		'\f130' => 'format status',
		'\f126' => 'format video',
		'\f314' => 'forms',
		'\f462' => 'googleplus',
		'\f509' => 'grid view',
		'\f307' => 'groups',
		'\f308' => 'hammer',
		'\f487' => 'heart',
		'\f530' => 'hidden',
		'\f337' => 'id alt',
		'\f336' => 'id',
		'\f165' => 'image crop',
		'\f533' => 'image filter',
		'\f169' => 'image flip horizontal',
		'\f168' => 'image flip vertical',
		'\f166' => 'image rotate left',
		'\f167' => 'image rotate right',
		'\f531' => 'image rotate',
		'\f232' => 'images alt',
		'\f233' => 'images alt2',
		'\f510' => 'index card',
		'\f348' => 'info',
		'\f12d' => 'instagram',
		'\f547' => 'laptop',
		'\f538' => 'layout',
		'\f229' => 'leftright',
		'\f339' => 'lightbulb',
		'\f163' => 'list view',
		'\f231' => 'location alt',
		'\f230' => 'location',
		'\f315' => 'lock duplicate',
		'\f160' => 'lock',
		'\f159' => 'marker',
		'\f501' => 'media archive',
		'\f500' => 'media audio',
		'\f499' => 'media code',
		'\f498' => 'media default',
		'\f497' => 'media document',
		'\f496' => 'media interactive',
		'\f495' => 'media spreadsheet',
		'\f491' => 'media text',
		'\f490' => 'media video',
		'\f488' => 'megaphone',
		'\f228' => 'menu alt',
		'\f329' => 'menu alt2',
		'\f349' => 'menu alt3',
		'\f333' => 'menu',
		'\f482' => 'microphone',
		'\f310' => 'migrate',
		'\f460' => 'minus',
		'\f526' => 'money',
		'\f545' => 'move',
		'\f484' => 'nametag',
		'\f325' => 'networking',
		'\f335' => 'no alt',
		'\f158' => 'no',
		'\f527' => 'palmtree',
		'\f546' => 'paperclip',
		'\f311' => 'performance',
		'\f525' => 'phone',
		'\f492' => 'playlist audio',
		'\f493' => 'playlist video',
		'\f485' => 'plugins checked',
		'\f502' => 'plus alt',
		'\f543' => 'plus alt2',
		'\f132' => 'plus',
		'\f322' => 'portfolio',
		'\f173' => 'post status',
		'\f157' => 'pressthis',
		'\f312' => 'products',
		'\f503' => 'randomize',
		'\f172' => 'redo',
		'\f124' => 'rest api',
		'\f303' => 'rss',
		'\f489' => 'schedule',
		'\f180' => 'screenoptions',
		'\f179' => 'search',
		'\f240' => 'share alt',
		'\f242' => 'share alt2',
		'\f237' => 'share',
		'\f334' => 'shield alt',
		'\f332' => 'shield',
		'\f181' => 'slides',
		'\f470' => 'smartphone',
		'\f328' => 'smiley',
		'\f156' => 'sort',
		'\f468' => 'sos',
		'\f154' => 'star empty',
		'\f155' => 'star filled',
		'\f459' => 'star half',
		'\f537' => 'sticky',
		'\f513' => 'store',
		'\f471' => 'tablet',
		'\f323' => 'tag',
		'\f479' => 'tagcloud',
		'\f473' => 'testimonial',
		'\f121' => 'text page',
		'\f478' => 'text',
		'\f542' => 'thumbs down',
		'\f529' => 'thumbs up',
		'\f524' => 'tickets alt',
		'\f486' => 'tickets',
		'\f10d' => 'tide',
		'\f326' => 'translation',
		'\f182' => 'trash',
		'\f302' => 'twitter alt',
		'\f301' => 'twitter',
		'\f171' => 'undo',
		'\f507' => 'universal access alt',
		'\f483' => 'universal access',
		'\f528' => 'unlock',
		'\f113' => 'update alt',
		'\f463' => 'update',
		'\f317' => 'upload',
		'\f178' => 'vault',
		'\f234' => 'video alt',
		'\f235' => 'video alt2',
		'\f236' => 'video alt3',
		'\f177' => 'visibility',
		'\f534' => 'warning',
		'\f133' => 'welcome add page',
		'\f117' => 'welcome comments',
		'\f118' => 'welcome learn more',
		'\f115' => 'welcome view site',
		'\f116' => 'welcome widgets menus',
		'\f119' => 'welcome write blog',
		'\f324' => 'wordpress alt',
		'\f120' => 'wordpress',
		'\f12a' => 'yes alt',
		'\f147' => 'yes',
		'\f211' => 'editor distractionfree',
		'\f164' => 'exerpt view',
		'\f103' => 'format links',
		'\f109' => 'format standard',
		'\f182' => 'post trash',
		'\f237' => 'share1',
		'\f119' => 'welcome edit page',
	);

	return $icons;
}

/**
 * Sanitize Color
 *
 * @since 1.0.0
 *
 * @param string $color  Color value.
 *
 * @return string sanitized color value.
 */
function customizely_sanitize_color( $color ) {
	if ( '' === $color ) {
		return '';
	}

	if ( preg_match( '/rgba/', $color ) ) {
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		return "rgba($red,$green,$blue,$alpha)";
	} elseif ( preg_match( '/rgb/', $color ) ) {
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgb(%d,%d,%d)', $red, $green, $blue );
		return "rgb($red,$green,$blue)";
	}

	return sanitize_hex_color( $color );
}

/**
 * Sanitize Checkbox
 *
 * Sanitize checkbox value for user input
 *
 * @since 1.0.0
 *
 * @param mixid $value Value to sanitize.
 *
 * @return array
 */
function customizely_sanitize_checkbox( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}

	return array_map( 'sanitize_text_field', $value );
}

/**
 * Add thumb for media REST API
 *
 * @since 1.0.1
 *
 * @param WP_REST_Response $response  Response object.
 * @param WP_Post          $post      Post object.
 * @param WP_REST_Request  $request    Request object.
 *
 * @return WP_REST_Response Response object.
 */
function customizely_add_thumb_to_media_rest( $response, $post, $request ) {
	$thumbnail_arr = wp_get_attachment_image_src( $post->ID, 'thumbnail', true );
	$thumbnail     = $thumbnail_arr && ! empty( $thumbnail_arr ) ? $thumbnail_arr[0] : false;
	$data          = $response->get_data();

	if ( ! empty( $data['mime_type'] ) ) {
		$file_type = explode( '/', $data['mime_type'] );
		if ( ! empty( $file_type[0] ) ) {
			$data['cmly_type'] = $file_type[0];
		}
	}

	if ( $thumbnail ) {
		$data['cmly_thumb'] = $thumbnail;
	}

	if ( ! empty( $data['cmly_type'] ) && ( 'video' === $data['cmly_type'] || 'audio' === $data['cmly_type'] ) ) {
		$data['cmly_thumb'] = $data['source_url'];
	}

	$response->set_data( $data );
	return $response;
}

add_filter( 'rest_prepare_attachment', 'customizely_add_thumb_to_media_rest', 10, 3 );
