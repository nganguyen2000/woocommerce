<?php
/**
 * Post Type.
 *
 * Customizely main base post type for builder to save option data.
 *
 * @package    Customizely
 * @author     KitThemes (https://www.kitthemes.com/)
 * @copyright  Copyright (c) 2019, KitThemes
 * @since      1.0.0
 */

namespace Customizely;

/**
 * Post Type.
 *
 * Customizely main base post type for builder to save option data.
 *
 * @since 1.0.0
 */
class Post_Type {
	/**
	 * Constructor Method for Post_Type
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}
	/**
	 * Register Post Type
	 *
	 * It will register custom post type for options data
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => _x( 'Options', 'post type general name', 'customizely' ),
			'singular_name'      => _x( 'Option', 'post type singular name', 'customizely' ),
			'menu_name'          => _x( 'Options', 'admin menu', 'customizely' ),
			'name_admin_bar'     => _x( 'Option', 'add new on admin bar', 'customizely' ),
			'add_new'            => _x( 'Add New', 'option', 'customizely' ),
			'add_new_item'       => __( 'Add New Option', 'customizely' ),
			'new_item'           => __( 'New Option', 'customizely' ),
			'edit_item'          => __( 'Edit Option', 'customizely' ),
			'view_item'          => __( 'View Option', 'customizely' ),
			'all_items'          => __( 'All Options', 'customizely' ),
			'search_items'       => __( 'Search Options', 'customizely' ),
			'parent_item_colon'  => __( 'Parent Options:', 'customizely' ),
			'not_found'          => __( 'No options found.', 'customizely' ),
			'not_found_in_trash' => __( 'No options found in Trash.', 'customizely' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Option post type for saving customizely options.', 'customizely' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'query_var'          => false,
			'rewrite'            => array(),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
		);

		register_post_type( 'cmly_option', $args );
	}
}
