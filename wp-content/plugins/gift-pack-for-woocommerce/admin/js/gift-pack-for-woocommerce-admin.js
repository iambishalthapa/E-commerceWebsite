(function ( $ ) {
	"use strict";

	$(function () {

		$( document ).ready(function() {	
			
			$('#gpfw_gift_pack_bg_color').wpColorPicker();
			$('#gpfw_giftwrap_base_gift_title_color').wpColorPicker();
			$('#gpfw_add_gift_pack_label_color').wpColorPicker();
			$('#gpfw_add_gift_pack_price_and_checkbox').wpColorPicker();
			
			var title_icon_uploader; 
		    $('#gpfw_gift_pack_bg_img_btn').click(function(e) {
		 
		        e.preventDefault();
		 
		        //If the uploader object has already been created, reopen the dialog
		        if (title_icon_uploader) {
		            title_icon_uploader.open();
		            return;
		        }
		 
		        //Extend the wp.media object
		        title_icon_uploader = wp.media.frames.file_frame = wp.media({
		            title: 'Choose Image',
		            button: {
		                text: 'Choose Image'
		            },
		            multiple: false
		        });
		 
		        //When a file is selected, grab the URL and set it as the text field's value
		        title_icon_uploader.on('select', function() {		        	        	
		            var attachment = title_icon_uploader.state().get('selection').first().toJSON();		            
		            $('#gpfw_gift_pack_bg_img').val(attachment.url);
		        });
		 
		        //Open the uploader dialog
		        title_icon_uploader.open();
		 
		    });

		    $('#gpfw_cat_enable').change(function(){
		    	if($(this).prop('checked')){
		    		$('#gpfw_product_category').show();
		    	}else{
		    		$('#gpfw_product_category').hide();
		    	}
		    });
		});
			//  //Validation for price field of setting screen
		    //   var regex = /^(\d*\.?\d*)$/;
            // // Attach keypress event listener to the input field
		    //   $("#gpfw_gift_price").on("keypress", function(e) {
		    //     var inputValue = $(this).val();
		    //     var charCode = e.which ? e.which : e.keyCode;

		    //     // Check if the pressed key is not a number or decimal point
		    //     if (
		    //       (charCode > 31 && (charCode < 48 || charCode > 57)) && // Not a number key
		    //       (charCode !== 46 || inputValue.indexOf(".") !== -1) // Not a decimal point or more than one decimal point
		    //     ) {
		    //       e.preventDefault(); // Prevent the character from being entered
		    //     }

		    //     // Validate the input value against the regex
		    //     if (!regex.test(inputValue)) {
		    //       $(this).val(""); // If invalid, remove the entire text
		    //     }
		    //   });

		    // //Validation

			// $("#gpfw_gift_price").on("keypress", function(e) {
			//     var charCode = e.which ? e.which : e.keyCode;
			//     var validChars = [44, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57]; 
			//     if (validChars.indexOf(charCode) === -1) {
			//         e.preventDefault(); 
			//     }
			// });
			// $("#gpfw_gift_price").on("input", function() {
			//     var value = $(this).val();
			//     var formattedValue = value.replace(/[^0-9,.]/g, ''); 
			//     $(this).val(formattedValue);
			// });


		// Allow both commas and periods in the price input field
		$("#gpfw_gift_price").on("input", function() {
		    var value = $(this).val();
		    // Replace anything that is not a number, comma, or period
		    var formattedValue = value.replace(/[^0-9,.]/g, '');
		    $(this).val(formattedValue);
		});

		// Handle keypress events to allow only valid characters
		$("#gpfw_gift_price").on("keypress", function(e) {
		    var charCode = e.which ? e.which : e.keyCode;
		    // Allow numbers, comma, and period
		    var validChars = [44, 46, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57]; 
		    if (validChars.indexOf(charCode) === -1) {
		        e.preventDefault();
		    }
		});


	});

}(jQuery));

jQuery( function( $ ) {
	/*
	 * Sortable images
	 */
	$('ul.gpfw_gallery_gallery_mtb').sortable({
		items:'li',
		cursor:'-webkit-grabbing', /* mouse cursor */
		scrollSensitivity:40,
		/*
		You can set your custom CSS styles while this element is dragging
		start:function(event,ui){
			ui.item.css({'background-color':'grey'});
		},
		*/
		stop:function(event,ui){
			ui.item.removeAttr('style');

			var sort = new Array(), 
			    gallery = $(this); 

			/* each time after dragging we resort our array */
			gallery.find('li').each(function(index){
				sort.push( $(this).attr('data-id') );
			});
			/* add the array value to the hidden input field */
			gallery.parent().next().val( sort.join() );
			/* console.log(sort); */
		}
	});
	/*
	 * Multiple images uploader
	 */
	$('.gpfw_gallery_upload_gallery_button').click( function(e){ /* on button click*/
		e.preventDefault();

		var button = $(this),
		    hiddenfield = button.prev(),
		    hiddenfieldvalue = hiddenfield.val().split(","), /* the array of added image IDs */
	    	    custom_uploader = wp.media({
			title: 'Insert images', /* popup title */
			library : {type : 'image'},
			button: {text: 'Use these images'}, /* "Insert" button text */
			multiple: true
		    }).on('select', function() {

			var attachments = custom_uploader.state().get('selection').map(function( a ) {
				a.toJSON();
            	return a;
			}),
			thesamepicture = false,
			i;
			console.log(custom_uploader);

			/* loop through all the images */
          		for (i = 0; i < attachments.length; ++i) {

				/* if you don't want the same images to be added multiple time */
				if( !in_array( attachments[i].id, hiddenfieldvalue ) ) {
					
					/* add HTML element with an image */
					$('ul.gpfw_gallery_gallery_mtb').append('<li data-id='+attachments[i].id+'><span style="background-image:url(' + attachments[i].attributes.url + ')"></span><a href="javascript:void(0);" class="gpfw_gallery_gallery_remove">&times;</a></li>');
					/* add an image ID to the array of all images */
					hiddenfieldvalue.push( attachments[i].id );
				} else {
					thesamepicture = true;
				}
          		}
			/* refresh sortable */
			$( "ul.gpfw_gallery_gallery_mtb" ).sortable( "refresh" );
			/* add the IDs to the hidden field value */
			hiddenfield.val( hiddenfieldvalue.join() );
			/* you can print a message for users if you want to let you know about the same images */
			if( thesamepicture == true ) alert('The same images are not allowed.');
		}).open();
	});

	/*
	 * Remove certain images
	 */
	$('body').on('click', '.gpfw_gallery_gallery_remove', function(){
		
		var id = $(this).parent().attr('data-id'),
		    gallery = $(this).parent().parent(),
			
		    hiddenfield = gallery.parent().next(),
		    hiddenfieldvalue = hiddenfield.val().split(","),
		    i = hiddenfieldvalue.indexOf(id);
			console.log(gallery);
		$(this).parent().remove();
        console.log(hiddenfieldvalue);
		/* remove certain array element */
		if(i != -1) {
			hiddenfieldvalue.splice(i, 1);
		}

		/* add the IDs to the hidden field value */
		hiddenfield.val( hiddenfieldvalue.join() );

		/* refresh sortable */
		gallery.sortable( "refresh" );

		return false;
	});
	/*
	 * Selected item
	 */
	$('body').on('mousedown', 'ul.gpfw_gallery_gallery_mtb li', function(){
		var el = $(this);
		el.parent().find('li').removeClass('gpfw-gallery-active');
		el.addClass('gpfw-gallery');
	});
});

function in_array(el, arr) {
	for(var i in arr) {
		if(arr[i] == el) return true;
	}
	return false;
}