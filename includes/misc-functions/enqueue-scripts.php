<?php
/**
 * This file contains the enqueue scripts function for the widgets plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Features
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */
 
/**
 * Enqueue JS and CSS for widgets 
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */

/**
 * Enqueue css and js
 *
 * Filter: mp_stacks_widgets_css_location
 */
function mp_stacks_widgets_enqueue_scripts(){
			
	//Enqueue widgets CSS
	wp_enqueue_style( 'mp_stacks_widgets_css', plugins_url( 'css/widgets.css', dirname( __FILE__ ) ) );
	
	//Enqueue widgets CSS
	wp_enqueue_script( 'mp_stacks_widgets_js', plugins_url( 'js/widgets.js', dirname( __FILE__ ) ), array( 'jquery', 'mp_stacks_front_end_js' ) );

}
 
/**
 * Enqueue css face for widgets
 */
add_action( 'wp_enqueue_scripts', 'mp_stacks_widgets_enqueue_scripts' );

/**
 * Enqueue css and js
 *
 * Filter: mp_stacks_widgets_css_location
 */
function mp_stacks_widgets_admin_enqueue_scripts(){
	
	//Enqueue Admin Features CSS
	wp_enqueue_style( 'mp_stacks_widgets_css', plugins_url( 'css/admin-widgets.css', dirname( __FILE__ ) ) );

}
 
/**
 * Enqueue css face for widgets
 */
add_action( 'admin_enqueue_scripts', 'mp_stacks_widgets_admin_enqueue_scripts' );