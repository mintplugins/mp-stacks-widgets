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
 * Unregister all sidebars if the URL variable to unset them is set. 
 * This is so that after, we can register a single sidebar on the brick's edit screen so there is no confusion about which sidebar is the right one.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_widgets_unregister_all_sidebars(){
	
	if ( !isset( $_GET['unregister_all_widgets'] ) ){
		return;
	}
	
	if ( !empty( $GLOBALS['wp_registered_sidebars'] ) ){
		
		foreach( $GLOBALS['wp_registered_sidebars'] as $sidebar_id => $sidebar ){
			unregister_sidebar( $sidebar_id );	
		}
	
	}
	 
}
add_action( 'widgets_init', 'mp_stacks_widgets_unregister_all_sidebars', 100 );
 
/**
 * Register the sidebars we need
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_widgets_register_sidebars(){
			
	//Get the registered sidebars
	$mp_stacks_sidebars = get_option( 'mp_stacks_sidebar_args' );
	
	//If our sidebar args have been set
	if ( !empty( $mp_stacks_sidebars ) ){
		foreach( $mp_stacks_sidebars as $mp_stacks_sidebar_args ){
			
			//If the sidebar id is empty, for backwards compatibility, use the brick title santizied	
			if ( empty( $mp_stacks_sidebar_args['id'] ) ){
				$mp_stacks_sidebar_args['id'] = sanitize_title( $mp_stacks_sidebar_args['name'] );
			}
						
			//register a sidebar for each brick that needs one
			register_sidebar( $mp_stacks_sidebar_args );
		}
	}
	
}
add_action( 'widgets_init', 'mp_stacks_widgets_register_sidebars', 101 );

/**
 * Generate Transients with info about what sidebars need to be generated
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_widgets_set_sidebars_transient(){
	
	if ( is_admin() ){
		return;	
	}
	
	if( !session_id() )
        session_start();
		
	if ( isset( $_SESSION['mp_stacks_widgets_refresh_on_next_frontendload'] ) && $_SESSION['mp_stacks_widgets_refresh_on_next_frontendload'] ){
		
		unset( $_SESSION['mp_stacks_widgets_refresh_on_next_frontendload'] );
		
		//Set the args for the new query
		$mp_brick_args = array(
			'post_type' => "mp_brick",
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);	
			
		//Create new query for stacks
		$mp_brick_query = new WP_Query( apply_filters( 'mp_brick_args', $mp_brick_args ) );
		
		$mp_stacks_sidebars = array();
		
		//Loop through all bricks
		if ( $mp_brick_query->have_posts() ) { 
			
			while( $mp_brick_query->have_posts() ) : $mp_brick_query->the_post(); 
				
				$brick_id = get_the_ID(); 
				
				//First Media Type
				$mp_stacks_first_content_type = get_post_meta($brick_id, 'brick_first_content_type', true);
	
				//Second Media Type
				$mp_stacks_second_content_type = get_post_meta($brick_id, 'brick_second_content_type', true);
					
				//If a content type is set to be widgets
				if ( $mp_stacks_first_content_type == 'widgets' || $mp_stacks_second_content_type == 'widgets' ){
					
					$title = "Brick: " . get_the_title( $brick_id );
					//Sidebar ID
					$sidebar_id = mp_core_get_post_meta( $brick_id, 'mp_stacks_widgets_brick_sidebar_id', 'none' );
					
					//If the sidebar id is empty, for backwards compatibility, use the brick title santizied
					if ( $sidebar_id == 'none' ){
						$sidebar_id = sanitize_title( $title );
					}
					
					//Add it to the widgets transient where we store all brick widgets
					$mp_stacks_sidebars[$sidebar_id] = array(
						'name'          => $title,
						'id'            => sanitize_title( $sidebar_id ),
						'description'   => '',
						'class'         => '',
						'before_widget' => '<div class="mp-stacks-widgets-item">',
						'after_widget'  => '</div>',
						'before_title'  => '<div class="mp-stacks-widgets-title">',
						'after_title'   => '</div>' 
					);
					
				}
	
			endwhile;
			
			//This transient is used to register a sidebar for each brick that needs one
			update_option( 'mp_stacks_sidebar_args', $mp_stacks_sidebars );
				
		}
	}

}
add_action( 'after_setup_theme', 'mp_stacks_widgets_set_sidebars_transient' );

/**
 * Ajax CallBack to Generate Transients with info about what sidebars need to be generated
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_ajax_set_sidebars_transient(){
	
	if( !session_id() )
        session_start();
		
	$_SESSION['mp_stacks_widgets_refresh_on_next_frontendload'] = true;
	
	//Set the registered sidebars to be blank. We only need to show the sidebar for this brick on the next page load - which is all this affects.
	$mp_stacks_sidebars = array();
	
	//Get the brick ID and set the name of the new sidebar
	$post_id = $_POST['mp_stacks_widgets_brick_id'];			
	$title = "Brick: " . get_the_title( $post_id );
	
	//BACKWARDS COMPATIBILITY: If this brick was published before February 10, 2015, get the title, convert it to a slug, and use that for the sidebar id.
	//Otherwise the sidebar ID originates in Javascript when the AJAX is called.
	$this_bricks_publish_date = strtotime( get_the_date( 'l, F j, Y', $post_id ) );
	$feb_15_2015_time = '1423608691';
	
	//If this brick was published before Feb 15, 2015 (when this change was made)
	if ( !empty( $this_bricks_publish_date ) && $feb_15_2015_time > $this_bricks_publish_date ){
		$sidebar_id = sanitize_title( $title );
	}
	else{
	
		//Sidebar ID
		$sidebar_id = mp_core_get_post_meta( $post_id, 'mp_stacks_widgets_brick_sidebar_id', 'none' );
		
		//If there isn't a sidebar id saved to this post already, it probably hasn't been saved before:
		if ( $sidebar_id == 'none' ){
			
			//So get the sidebar id from the javascript
			$sidebar_id = $_POST['mp_stacks_widgets_brick_sidebar_id'];
		}
	}
	
	//The the transient that we want a sidebar to be registered for this brick
	$mp_stacks_sidebars[$sidebar_id] = array(
		'name'          => $title,
		'id'            => sanitize_title( $sidebar_id ),
		'description'   => '',
		'class'         => '',
		'before_widget' => '<div class="mp-stacks-widgets-item">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="mp-stacks-widgets-title">',
		'after_title'   => '</div>' 
	);	
	
	//Save the transient
	update_option( 'mp_stacks_sidebar_args', $mp_stacks_sidebars );
	
	//Give a "Manage Widgets" button to the meta field for the user to click since their sidebar will now be available 
	echo json_encode( array( 
		'iframe' => '<iframe src="' . mp_core_add_query_arg( array( 'mp-stacks-minimal-admin' => true, 'unregister_all_widgets' => true ), admin_url( 'widgets.php' ) ) . '" target="_blank" width="100%" height="500px" frameborder="0" scrolling="no" onload=\'javascript:mp_stacks_resizeIframe(this);\'>' . __( 'Manage Widgets', 'mp_stacks_widgets' ) . '</a>',
		'mp_stacks_widgets_brick_sidebar_id' => sanitize_title( $sidebar_id )
		)
	);
	
	die();
	
}
add_action( 'wp_ajax_mp_stacks_widgets_register_sidebar', 'mp_stacks_ajax_set_sidebars_transient' );

/**
 * When the MP Stacks + Developer plugin is creating/exporting a Stack Template and we are dealing with the widget id, make it include the php (time() function
 * This way, the Stack template will use the time when a new Stack is created as the ID - rather than using the time when the original widget Brick was created.
 *
 * @access   public
 * @since    1.0.0
 * @return   $meta_value
 */
function mp_stacks_widgets_developer_template_metafield_value( $meta_value, $meta_key ){
	
	if ( $meta_key == 'mp_stacks_widgets_brick_sidebar_id' ){
		$meta_value = "'mp_stacks_widgets_sidebar_id_' . time()";	
	}
	
	return $meta_value;
}
add_filter( 'mp_stacks_developer_template_metafield_value', 'mp_stacks_widgets_developer_template_metafield_value', 10, 2 );

/**
 * When creating a Stack template through the MP Stack templater function, make sure that a sidebar id is saved (if either content-type is set to be a "widget")
 *
 * @access   public
 * @since    1.0.0
 *
 */
function mp_stacks_widgets_add_sidebar_id_when_creating_stack_template( $brick_meta, $content_type_1, $content_type_2 ){
	
	//If this Brick (in this stack template) is set to have a widegt area as a Content-Type
	if ( $content_type_1 == 'widgets' || $content_type_2 == 'widgets' ){
		//Set the Brick's sidebar_id to be the current time (this is the time when the Stack Template was created)
		$brick_meta['mp_stacks_widgets_brick_sidebar_id'] = 'mp_stacks_widgets_sidebar_id_' . time();
	}
	
	return $brick_meta;
	
}
add_filter( 'mp_stacks_template_extra_meta', 'mp_stacks_widgets_add_sidebar_id_when_creating_stack_template', 10, 3 );

/**
 * When a Brick with a Widget is deleted, remove the widget info from the WP Options table as well so it doesn't try to register a sidebar for that brick anymore.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_widgets_upon_delete_brick($post_id) { 
	
	//Get the Sidebar ID for this Brick
	$sidebar_id = mp_core_get_post_meta( $post_id, 'mp_stacks_widgets_brick_sidebar_id', 'none' );
	
	if ( $sidebar_id == 'none' ){
		return false;	
	}
		
	//Get the registered sidebars
	$mp_stacks_sidebars = get_option( 'mp_stacks_sidebar_args' );
	
	//Remove this sidebar from the list of sidebars saved in the Wp Options table
	unset( $mp_stacks_sidebars[$sidebar_id] );
	
	//Update the list of sidebars to register
	update_option( 'mp_stacks_sidebar_args', $mp_stacks_sidebars );

}
add_action( 'delete_post', 'mp_stacks_widgets_upon_delete_brick' );