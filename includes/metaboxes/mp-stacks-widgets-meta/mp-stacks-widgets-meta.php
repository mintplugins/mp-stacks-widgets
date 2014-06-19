<?php
/**
 * This page contains functions for modifying the metabox for widgets as a media type
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks Widgets
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Add Widgets as a Media Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_widgets_create_meta_box(){
	
	//Get current page
	$current_page = get_current_screen();
	
	//Only load if we are on an mp_brick page
	if ( $current_page->id != 'mp_brick' ){
		return;	
	}
	
	$menus = wp_get_nav_menus( array('orderby' => 'name') );
	
	foreach ( $menus as $menu ){
		$menu_select[$menu->term_id] = $menu->name;
	}
	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_widgets_add_meta_box = array(
		'metabox_id' => 'mp_stacks_widgets_metabox', 
		'metabox_title' => __( '"Widgets" Content-Type', 'mp_stacks_widgets'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_widgets_items_array = array(
		array(
			'field_id'			=> 'widgets_per_row',
			'field_title' 	=> __( 'Widgets Per Row', 'mp_stacks_widgets'),
			'field_description' 	=> __( 'How many widgets should there be per row?', 'mp_stacks_widgets' ),
			'field_type' 	=> 'number',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'widgets_title_showhider',
			'field_title' 	=> __( 'Widgets Title Styling', 'mp_stacks_widgets'),
			'field_description' 	=> __( '', 'mp_stacks_widgets' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
			array(
				'field_id'			=> 'widgets_title_color',
				'field_title' 	=> __( 'Widgets Title Colors', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'Select the color the titles will be (leave blank for theme default)', 'mp_stacks_widgets' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => 'widgets_title_showhider',
			),
			array(
				'field_id'			=> 'widgets_title_size',
				'field_title' 	=> __( 'Widgets Title Size', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'Enter the text size the titles will be (leave blank for theme default)', 'mp_stacks_widgets' ),
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider' => 'widgets_title_showhider',
			),
			array(
				'field_id'			=> 'widgets_title_bottom_margin',
				'field_title' 	=> __( 'Spacing Below Title', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'How much space would you like to have in between the title and the widgets? (In Pixels)', 'mp_stacks_widgets' ),
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider' => 'widgets_title_showhider',
			),
		array(
			'field_id'			=> 'widgets_text_showhider',
			'field_title' 	=> __( 'Widgets Text Styling', 'mp_stacks_widgets'),
			'field_description' 	=> __( '', 'mp_stacks_widgets' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
			array(
				'field_id'			=> 'widgets_text_color',
				'field_title' 	=> __( 'Text Colors', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'Select the color the text will be in all widgets.', 'mp_stacks_widgets' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => 'widgets_text_showhider',
			),
			array(
				'field_id'			=> 'widgets_text_size',
				'field_title' 	=> __( 'Text Font Size', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'Enter the text size for all widgets. (leave blank for theme default)', 'mp_stacks_widgets' ),
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider' => 'widgets_text_showhider',
			),
			array(
				'field_id'			=> 'widgets_text_bottom_margin',
				'field_title' 	=> __( 'Spacing Between Lines', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'How much space would you like to have in between each text line in the widgets? (In Pixels)', 'mp_stacks_widgets' ),
				'field_type' 	=> 'number',
				'field_value' => '',
				'field_showhider' => 'widgets_text_showhider',
			),
		array(
			'field_id'			=> 'widgets_links_showhider',
			'field_title' 	=> __( 'Widgets Link Styling', 'mp_stacks_widgets'),
			'field_description' 	=> __( '', 'mp_stacks_widgets' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
			array(
				'field_id'			=> 'widgets_links_color',
				'field_title' 	=> __( 'Link Colors', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'Select the color the links will be in all widgets (leave blank for theme default)', 'mp_stacks_widgets' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => 'widgets_links_showhider',
			),
			array(
				'field_id'			=> 'widgets_links_hover_color',
				'field_title' 	=> __( 'Mouse-Over Link Colors', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'Select the color the links will be when the mouse is over them in all widgets (leave blank for theme default)', 'mp_stacks_widgets' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => 'widgets_links_showhider',
			),
			array(
				'field_id'			=> 'widgets_link_underlines',
				'field_title' 	=> __( 'Underline Links in Widgets?', 'mp_stacks_widgets'),
				'field_description' 	=> __( 'Do you want the links in the widgets to be underlined?', 'mp_stacks_widgets' ),
				'field_type' 	=> 'checkbox',
				'field_value' => '',
				'field_showhider' => 'widgets_links_showhider',
			),
		array(
			'field_id'			=> 'manage_sidebar',
			'field_title' 	=> __( 'Manage Widget Area for this Brick', 'mp_stacks_widgets'),
			'field_description' 	=> '<br />',
			'field_type' 	=> 'basictext',
			'field_value' => '',
		)
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_widgets_add_meta_box = has_filter('mp_stacks_widgets_meta_box_array') ? apply_filters( 'mp_stacks_widgets_meta_box_array', $mp_stacks_widgets_add_meta_box) : $mp_stacks_widgets_add_meta_box;
	
	//Globalize the and populate mp_stacks_features_items_array (do this before filter hooks are run)
	global $global_mp_stacks_widgets_items_array;
	$global_mp_stacks_widgets_items_array = $mp_stacks_widgets_items_array;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_widgets_items_array = has_filter('mp_stacks_widgets_items_array') ? apply_filters( 'mp_stacks_widgets_items_array', $mp_stacks_widgets_items_array) : $mp_stacks_widgets_items_array;
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_widgets_meta_box;
	$mp_stacks_widgets_meta_box = new MP_CORE_Metabox($mp_stacks_widgets_add_meta_box, $mp_stacks_widgets_items_array);
}
add_action('current_screen', 'mp_stacks_widgets_create_meta_box');