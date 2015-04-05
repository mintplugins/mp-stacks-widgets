jQuery(document).ready(function($){
	
	//Trigger the creation of the transient which, in turn, tells the widgets_init to register a sidebar for this brick	
	$( document ).on( 'mp_stacks_content_type_change', function(event, content_type, post_id){
		
		event.preventDefault();
		
		//console.log(content_type);
		
		if ( content_type != "widgets" ){
			return;
		}
		
		//if there is no post id, this post hasn't been saved yet:
		if ( post_id == '' ){
			//Create a unique id for this sidebar using the number of seconds since 1970
			mp_stacks_widgets_brick_sidebar_id = 'mp_stacks_widgets_sidebar_id_' + ( new Date().getTime() / 1000 );
		}
		else{
			//Get the sidebar's unique id from the hidden field for the sidebar id for this brick
			mp_stacks_widgets_brick_sidebar_id = $('#mp_stacks_widgets_brick_sidebar_id').val();
		}
					
		// Use ajax to trigger the creation of the transient
		var postData = {
			action: 'mp_stacks_widgets_register_sidebar',
			mp_stacks_widgets_brick_id: post_id,
			mp_stacks_widgets_brick_sidebar_id: mp_stacks_widgets_brick_sidebar_id
		};
		
		//Ajax to create sidebar transient which registers new sidebars
		$.ajax({
			type: "POST",
			data: postData,
			dataType:"json",
			url: mp_stacks_vars.ajaxurl,
			success: function (response) {
				
				//Once it's done, show the button which links to the sidebars/widgets page.
				$( '.mp_field_manage_sidebar' ).html(response.iframe);
				
				//Place the unique sidebar id into the hidden field for the sidebar id for this brick.
				$('#mp_stacks_widgets_brick_sidebar_id').val(response.mp_stacks_widgets_brick_sidebar_id);
			
			}
		}).fail(function (data) {
			console.log(data);
		});	
		
	});
	
}); 