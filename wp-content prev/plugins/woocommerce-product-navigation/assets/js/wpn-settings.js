jQuery( document ).ready(function($) {

	/*
	*	 Handle WPN Next Image Uploading
	*/
	$('.wpn_upload_next_image').live('click', function( event ){
	
		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( typeof next_file_frame !== 'undefined' && next_file_frame ) {
			next_file_frame.open();
			return;
		}

		// Create the media frame.
		next_file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false
		});

		// When an file is selected, run a callback.
		next_file_frame.on( 'select', function() {
		
			attachment = next_file_frame.state().get('selection').first().toJSON();

			// Update front end
			$('.wpn_next_url_label').text( attachment.filename );
			$('.wpn_next_id').attr( 'value', attachment.id );
			$('.wpn_next_img_preview').attr('src', attachment.url );
		});
		
		// Open the Modal
		next_file_frame.open();
	});

	/*
	*	 Handle WPN Previous Image Uploading
	*/
	$('.wpn_upload_previous_image').live('click', function( event ){
	
		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( typeof prev_file_frame !== 'undefined' && prev_file_frame ) {
			prev_file_frame.open();
			return;
		}

		// Create the media frame.
		prev_file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false
		});

		// When an file is selected, run a callback.
		prev_file_frame.on( 'select', function() {

			attachment = prev_file_frame.state().get('selection').first().toJSON();
				console.log( attachment);

			// Update front end
			$('.wpn_previous_url_label').text( attachment.filename );
			$('.wpn_previous_id').attr( 'value', attachment.id );
			$('.wpn_previous_img_preview').attr('src', attachment.url );
		});
		
		// Open the Modal
		prev_file_frame.open();
	});
	
	/*
	*	Remove file when selected
	*/
	$('.wpn_remove_file').live('click', function( event ){
		$(this).parent().find('.wpn_url_label').text( '' );
		$(this).parent().find('.wpn_id').attr( 'value', '' );
		$(this).parent().find('.wpn_img_preview').attr('src', '' );
	});
});