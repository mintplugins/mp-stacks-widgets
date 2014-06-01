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
 * Register the sidebars we need
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_register_sidebars(){
	
	//register a sidebar for each brick that needs one
	$mp_stacks_sidebars = get_site_transient( 'mp_stacks_sidebar_args' );
	
	//If our sidebar args have been set
	if ( !empty( $mp_stacks_sidebars ) ){
		foreach( $mp_stacks_sidebars as $mp_stacks_sidebar_args ){
			//register a sidebar for each brick that needs one
			register_sidebar( $mp_stacks_sidebar_args );
		}
	}
	//Otherwise show a notice that we need to refresh the widgets page where the query is run
	else{
		
		add_action( 'admin_notices', 'mp_stacks_widgets_refresh_widget_page_notice');
			
	}
	
}
add_action( 'widgets_init', 'mp_stacks_register_sidebars' );

/**
 * Generate Transients with info about what sidebars need to be generated
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_set_sidebars_transient(){
	
	//register a sidebar for each brick that needs one
	$mp_stacks_sidebar_args_timer = get_site_transient( 'mp_stacks_sidebar_args_timer' );
	
	//Get current page
	$current_page = get_current_screen();
	
	//Only load if the trasient is fresher than 24 hours (86400 seconds) OR if we are on the Widgets page
	if ( $mp_stacks_sidebar_args_timer > ( time() + 86400 ) ||  $current_page->id != 'widgets'){
		
		//Otherwise get us outta here
		return;	
	}
	
	//Set the args for the new query
	$mp_brick_args = array(
		'post_type' => "mp_brick",
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	);	
		
	//Create new query for stacks
	$mp_brick_query = new WP_Query( apply_filters( 'mp_brick_args', $mp_brick_args ) );
	
	//Loop through all bricks
	if ( $mp_brick_query->have_posts() ) { 
		
		while( $mp_brick_query->have_posts() ) : $mp_brick_query->the_post(); 
			
			$brick_id = get_the_ID(); 
			
			//First Media Type
			$mp_stacks_first_content_type = get_post_meta($brick_id, 'brick_first_content_type', true);

			//Second Media Type
			$mp_stacks_second_content_type = get_post_meta($brick_id, 'brick_second_content_type', true);
				
			if ( $mp_stacks_first_content_type == 'widgets' || $mp_stacks_second_content_type == 'widgets' ){
				
				$title = "Brick: " . get_the_title( $brick_id );
				$slug = sanitize_title( $title );
				
				$mp_stacks_sidebars[$slug] = array(
					'name'          => $title,
					'id'            => $slug,
					'description'   => '',
					'class'         => '',
					'before_widget' => '<div class="mp-stacks-widgets-item">',
					'after_widget'  => '</div>',
					'before_title'  => '<div class="mp-stacks-widgets-title">',
					'after_title'   => '</div>' 
				);
				
				//register a sidebar for each brick that needs one
				set_site_transient( 'mp_stacks_sidebar_args_timer', time() );
				
				//register a sidebar for each brick that needs one
				set_site_transient( 'mp_stacks_sidebar_args', $mp_stacks_sidebars );
			}

		endwhile;
		
	}
	
}
add_action( 'current_screen', 'mp_stacks_set_sidebars_transient' );

/**
 * If the sidebars trasnient hasn't been created yet, show this message which will generate the sidebars we need
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_widgets_refresh_widget_page_notice(){
	
	echo ' <div class="updated">';
	echo '<p>' . __('You may need to refresh the widgets page to generate Widget Areas needed by MP Stacks + Widgets.', 'mp_stacks_widgets') . '<a href="' . admin_url( 'widgets.php' ) . '">' . __( 'Refresh Widgets', 'mp_stacks_widgets' ) . '</p>';	
	echo '</div>';
		
}