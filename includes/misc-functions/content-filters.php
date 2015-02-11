<?php 
/**
 * This file contains the function which hooks to a brick's content output
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Widgets
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
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
	
	//Get Features Metabox Repeater Array
	$widgets_repeaters = get_post_meta( $post_id, 'mp_widgets_repeater', true );
	
	//Get Widgets Per Row
	$widgets_per_row = get_post_meta( $post_id, 'widgets_per_row', true );
	$widgets_per_row = empty( $widgets_per_row ) ? 1 : $widgets_per_row;
	
	//Widget Spacing Below Title
	$widgets_title_bottom_margin = get_post_meta( $post_id, 'widgets_title_bottom_margin', true );
	$widgets_title_bottom_margin = empty( $widgets_title_bottom_margin ) ? 'inherit' : $widgets_title_bottom_margin . 'px';
	
	//Widget Spacing Below Each Widget Link
	$widgets_text_bottom_margin = get_post_meta( $post_id, 'widgets_text_bottom_margin', true );
	$widgets_text_bottom_margin = empty( $widgets_text_bottom_margin ) ? 'inherit' : $widgets_text_bottom_margin . 'px';
	
	//Show Underlines?
	$widgets_link_underlines = get_post_meta( $post_id, 'widgets_link_underlines', true );
	$widgets_link_underlines = empty( $widgets_link_underlines ) ? 'none' : 'underline';
	
	//Title Color
	$widgets_title_color = get_post_meta( $post_id, 'widgets_title_color', true );
	$widgets_title_color = empty( $widgets_title_color ) ? 'inherit' : $widgets_title_color;
	
	//Title Size
	$widgets_title_size = get_post_meta( $post_id, 'widgets_title_size', true );
	$widgets_title_size = empty( $widgets_title_size ) ? 'inherit' : $widgets_title_size . 'px';
	
	//Text Color
	$widgets_text_color = get_post_meta( $post_id, 'widgets_text_color', true );
	$widgets_text_color = empty( $widgets_text_color ) ? 'inherit' : $widgets_text_color . '';
	
	//Text Size
	$widgets_text_size = get_post_meta( $post_id, 'widgets_text_size', true );
	$widgets_text_size = empty( $widgets_text_size ) ? 'inherit' : $widgets_text_size . 'px';
	
	//Text Line Height
	$widgets_text_line_height = $widgets_text_size == 'inherit' ? 'inherit' : ( $widgets_text_size + $widgets_text_bottom_margin ) . 'px';
	
	//Link Color
	$widgets_links_color = get_post_meta( $post_id, 'widgets_links_color', true );
	$widgets_links_color = empty( $widgets_links_color ) ? 'inherit' : $widgets_links_color;
	
	//Link Hover Color
	$widgets_links_hover_color = get_post_meta( $post_id, 'widgets_links_hover_color', true );
	$widgets_links_hover_color = empty( $widgets_links_hover_color ) ? 'inherit' : $widgets_links_hover_color;
	
	//List Item Spacing
	$widgets_list_item_spacing = mp_core_get_post_meta( $post_id, 'widgets_list_item_spacing', 15 );
	
	//List bullet points
	$list_bullet_points = mp_core_get_post_meta( $post_id, 'widgets_list_bullet_points', 'none' );
	$list_bullet_points = $list_bullet_points == 'none' ? 'none' : 'outside';
	
	$css_widgets_output = '#mp-brick-' . $post_id . ' .mp-stacks-widgets-item{
		width:' . (100/$widgets_per_row) .'%;
	}
	#mp-brick-' . $post_id . ' .mp-stacks-widgets .mp-stacks-widgets-title{
		font-size:' . $widgets_title_size .';
		line-height:'  . $widgets_title_size .';
		margin-bottom: ' . $widgets_title_bottom_margin . ';
		color: ' . $widgets_title_color . ';
	}
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item,
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item div,
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item p{
		font-size:' . $widgets_text_size .';
		line-height:'  . $widgets_text_line_height . ';
		color: ' . $widgets_text_color . ';
	}
	#mp-brick-' . $post_id . ' ul{
		list-style: ' .$list_bullet_points . ';
	}
	#mp-brick-' . $post_id . ' li{
		margin-bottom:' . $widgets_list_item_spacing . 'px;	
	}
	#mp-brick-' . $post_id . ' .sub-menu li:first-child{
		margin-top:' . $widgets_list_item_spacing . 'px;	
	}
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item li a{
		color: ' . $widgets_links_color . ';
		text-decoration: ' . $widgets_link_underlines . ';
	}
	#mp-brick-' . $post_id . ' .mp-stacks-widgets-item li a:hover{
		color: ' . $widgets_links_hover_color . ';
	}
	@media screen and (max-width: 600px){
		#mp-brick-' . $post_id . ' .mp-stacks-widgets-item{ 
			width:100%;
			max-width:100%;
		}
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
	$widgets_per_row = get_post_meta($brick_id, 'widgets_per_row', true);
	$widgets_per_row = empty( $widgets_per_row ) ? '2' : $widgets_per_row;
	
	ob_start(); 
	
	//Get Widgets Output
	?><div class="mp-stacks-widgets"><?php
	
	//Set counter to 0
	$counter = 1;
						
	dynamic_sidebar( sanitize_title( "Brick: " . get_the_title( $brick_id ) ) ); 
	
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