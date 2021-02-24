jQuery(document).ready(function($){
	var post_id = mp_stacks_getQueryVariable('post');

	getSidebar('widgets', post_id, false);

	function getSidebar( content_type, post_id, content_type_num ){

		if ( content_type != "widgets" ){
			return;
		}

		var mp_stacks_widgets_brick_sidebar_id = $('#mp_stacks_widgets_brick_sidebar_id').val();
				
		//if there is no sidebar id yet, this widget content-type hasn't been saved yet:
		if ( ! mp_stacks_widgets_brick_sidebar_id ){
			//Create a unique id for this sidebar using the number of seconds since 1970
			mp_stacks_widgets_brick_sidebar_id = 'mp_stacks_widgets_sidebar_id_' + ( new Date().getTime() / 1000 );
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
		
	}
	
}); 