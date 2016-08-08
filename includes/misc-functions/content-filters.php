<?php 
/**
 * This file contains the function which hooks to a brick's content output
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Widgets
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2015, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * This function hooks to the brick css output. If it is supposed to be a 'feature', then it will add the css for those widgets to the brick's css
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_css_widgets( $css_output, $post_id, $first_content_type, $second_content_type ){

	if ( $first_content_type != 'widgets' && $second_content_type != 'widgets' ){
		return $css_output;	
	}
	
	//Enqueue widgets CSS
	wp_enqueue_style( 'mp_stacks_widgets_css', plugins_url( 'css/widgets.css', dirname( __FILE__ ) ), array(), MP_STACKS_WIDGETS_VERSION );
	
	//Get Features Metabox Repeater Array
	$widgets_repeaters = get_post_meta( $post_id, 'mp_widgets_repeater', true );
	
	//Get Widgets Per Row
	$widgets_per_row = mp_core_get_post_meta( $post_id, 'widgets_per_row', 3 );
	
	//Widget Spacing Below Title
	$widgets_title_bottom_margin = mp_core_get_post_meta( $post_id, 'widgets_title_bottom_margin', '10' );
	
	//Widget Spacing Below Each Widget Link
	$widgets_text_bottom_margin = mp_core_get_post_meta( $post_id, 'widgets_text_bottom_margin', '5' );
	
	//Show Underlines?
	$widgets_link_underlines = mp_core_get_post_meta_checkbox( $post_id, 'widgets_link_underlines', false );
	$widgets_link_underlines = $widgets_link_underlines ? 'underline' : 'none';
	
	//Title Color
	$widgets_title_color = mp_core_get_post_meta( $post_id, 'widgets_title_color' );
	
	//Title Size
	$widgets_title_size = mp_core_get_post_meta( $post_id, 'widgets_title_size', '25' );
	
	//Text Color
	$widgets_text_color = mp_core_get_post_meta( $post_id, 'widgets_text_color' );
	
	//Text Size
	$widgets_text_size = mp_core_get_post_meta( $post_id, 'widgets_text_size', '16' );
	
	//Text Line Height
	$widgets_text_line_height = empty( $widgets_text_size ) ? NULL : ( $widgets_text_size + $widgets_text_bottom_margin );
	
	//Link Color
	$widgets_links_color = mp_core_get_post_meta( $post_id, 'widgets_links_color' );
	
	//Link Hover Color
	$widgets_links_hover_color = mp_core_get_post_meta( $post_id, 'widgets_links_hover_color' );
	
	//List Item Spacing
	$widgets_list_item_spacing = mp_core_get_post_meta( $post_id, 'widgets_list_item_spacing', 15 );
	
	//List bullet points
	$list_bullet_points = mp_core_get_post_meta( $post_id, 'widgets_list_bullet_points', 'none' );
	$list_bullet_points = $list_bullet_points == 'none' ? 'none' : 'outside';
	
	$css_widgets_output = '#mp-brick-' . $post_id . ' .mp-stacks-widgets-item{
		width:' . (100/$widgets_per_row) .'%;
	}
	#mp-brick-' . $post_id . ' .mp-stacks-widgets .mp-stacks-widgets-title{
		' . mp_core_css_line( 'font-size', $widgets_title_size, 'px' ) .'
		' . mp_core_css_line( 'line-height', $widgets_title_size, 'px' ) .'
		' . mp_core_css_line( 'margin-bottom', $widgets_title_bottom_margin, 'px' ) .'
		' . mp_core_css_line( 'color', $widgets_title_color ) .'
	}
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item,
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item div,
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item p{
		' . mp_core_css_line( 'font-size', $widgets_text_size, 'px' ) .'
		' . mp_core_css_line( 'line-height', $widgets_text_line_height, 'px' ) .'
		' . mp_core_css_line( 'color', $widgets_text_color ) .'
	}
	#mp-brick-' . $post_id . ' ul{
		list-style: ' .$list_bullet_points . ';
	}
	#mp-brick-' . $post_id . ' li{
		' . mp_core_css_line( 'margin-bottom', $widgets_list_item_spacing, 'px' ) .'
	}
	#mp-brick-' . $post_id . ' .sub-menu li:first-child{
		' . mp_core_css_line( 'margin-top', $widgets_list_item_spacing, 'px' ) .'
	}
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item li a{
		' . mp_core_css_line( 'color', $widgets_links_color ) .'
		text-decoration: ' . $widgets_link_underlines . ';
	}
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item li a:hover{
		' . mp_core_css_line( 'color', $widgets_links_hover_color ) .'
	}';
	
	return $css_widgets_output . $css_output;
		
}
add_filter('mp_brick_additional_css', 'mp_stacks_brick_content_output_css_widgets', 10, 4);
 
/**
 * This function hooks to the brick output. If it is supposed to be a 'widgets', then it will output the widgets
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_widgets($default_content_output, $mp_stacks_content_type, $brick_id){
	
	//If this stack content type isn't set to be an widgets	
	if ($mp_stacks_content_type != 'widgets'){
		return $default_content_output; 	
	}
	
	//Set default value for $content_output to NULL
	$content_output = NULL;	
		
	//Widgetss per row
	$widgets_per_row = mp_core_get_post_meta($brick_id, 'widgets_per_row', 3);
	
	ob_start(); 
	
	//Get Widgets Output
	?><div class="mp-stacks-widgets"><?php
	
	//Set counter to 0
	$counter = 1;
						
	$sidebar_found = dynamic_sidebar( mp_core_get_post_meta( $brick_id, 'mp_stacks_widgets_brick_sidebar_id', false ) ); 
	
	// Backwards Compatibility for old widget areas that were saved without an ID but just the title.
	if ( !$sidebar_found ){
		dynamic_sidebar( sanitize_title( "Brick: " . get_the_title( $brick_id ) ) ); 
	}
	
	if ( $widgets_per_row == $counter ){
		
		//Add clear div to bump a new row
		echo '<div class="mp-stacks-widgets-item-clearedfix"></div>';
		
		//Reset counter
		$counter = 1;
	}
	else{
		
		//Increment Counter
		$counter = $counter + 1;
		
	}
	
	echo '</div>';
	
	//Return
	return ob_get_clean();
	
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_widgets', 10, 3);