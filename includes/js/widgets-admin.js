jQuery(document).ready(function($){
	
	//Triger the creation of the transient which, in turn, tells the widgets_init to register a sidebar for this brick	
	$( document ).on( 'mp_stacks_content_type_change', function(event, content_type, post_id){
		
		event.preventDefault();
		
		if ( content_type != "widgets" ){
			return;
		}
					
		// Use ajax to trigger the creation of the transient
		var postData = {
			action: 'mp_stacks_widgets_register_sidebar',
			mp_stacks_widgets_brick_id: post_id			
		};
		
		//Ajax to create sidebar transient which registers new sidebars
		$.ajax({
			type: "POST",
			data: postData,
			url: mp_stacks_vars.ajaxurl,
			success: function (response) {
				
				//Once it's done, show the button which links to the sidebars/widgets page.
				$( '.mp_field_manage_sidebar' ).append(response);
			
			}
		}).fail(function (data) {
			console.log(data);
		});	
		
	});
	
}); 