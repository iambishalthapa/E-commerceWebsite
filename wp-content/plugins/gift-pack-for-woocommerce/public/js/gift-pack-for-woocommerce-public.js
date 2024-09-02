jQuery(document).ready(function($) {
	if($('div.gpwf_popup_enable').length){
	    var initPhotoSwipeFromDOM = function(gallerySelector) {
	        var parseThumbnailElements = function(el) {
	            var items = [];
	            el.find('.gift_pack_input').each(function() {
	                var $input = $(this).find('img');
	                var item = {
	                    src: $input.attr('src'),
	                    w: 500,
	                    h: 500,
	                };
	                items.push(item);
	            });
	            return items;
	        };

	        var openPhotoSwipe = function(index, galleryElement) {
	            var pswpElement = document.querySelectorAll('.pswp')[0];
	            var items = parseThumbnailElements(galleryElement);
	            var options = {
	                index: index,
	           
	            };

	            if (items.length > 0) {
	                var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
	                gallery.init();
	            }
	        };

	        $(document).on('click', gallerySelector + ' img', function() {
	            var index = $(this).closest('.gift_pack_input').index();
	            openPhotoSwipe(index, $(this).closest(gallerySelector));
	        });
	    };
	}


    // Load gpfw_option_input class functionality
	var gpfw_option_input = 'input[name="gift_pack_option"]';
	$(document).on('change',gpfw_option_input,function(){


		if($(this).prop('checked') === true) {
			var variable_pro = false;
			if($('div.gpfw_product.gpfw_product_variable').length){
				var gpfw_productID = $('input[name="variation_id"]').val();
				var var_pro_ID = $('input[name="product_id"]').val();
				var variable_pro = true;
			}
			else{
				var gpfw_productID = $('button[name="add-to-cart"]').val();
				if (typeof gpfw_productID === 'undefined') {
					var gpfw_productID = $('input[name="add-to-cart"]').val();
				}
				var var_pro_ID = 0;
			}

			if((gpfw_productID === undefined || gpfw_productID == 0) && variable_pro == true){
				$('.single_add_to_cart_button').click();
				$(this).prop('checked',false);
				return false;
			}

			var data = {
				'action': 'gpfw_check_gift_wrap',
				'gpfwcheckbox': 'checked',
				'gpfwproductID' : gpfw_productID,
				'var_pro_ID' : var_pro_ID
			};

			var gpfw_response = gpfw_ajax_request(data);
            gpfw_response.done(function(result){
            	var json_obj = JSON.parse(result);
            	$('.gift-pack_for-woocommerce-parent').html(json_obj.html);
				$('.gpfw-gift-pack-note').prop("required", true);
				
				if(variable_pro){
					if(json_obj.gpfw_sale && $('div.woocommerce-variation-price .price ins').length){
						$('.woocommerce-variation-price .price ins').html(json_obj.gift_pack_global_price_html);
					}
					else if(json_obj.gpfw_sale == 0 && $('div.woocommerce-variation-price .price ins').length == 0 && $('div.woocommerce-variation-price .price').length){
						$('.woocommerce-variation-price .price').html(json_obj.gift_pack_global_price_html);
					}
					else if(json_obj.gpfw_sale == 0 && $('div.woocommerce-variation-price .price').length == 0 && $('p.price ins').length){
						$('p.price ins').html(json_obj.gift_pack_global_price_html);
					}
					else if(json_obj.gpfw_sale == 0 && $('div.woocommerce-variation-price .price').length == 0 && $('p.price').length && $('p.price ins').length == 0){
						$('.price').html(json_obj.gift_pack_global_price_html);
					}
					else{
						$('p.price ins').html(json_obj.gift_pack_global_price_html);
					}
					$('input[name="active_price"]').val(json_obj.active_price);
				}
				else{
					if($('p.price ins').length == 1){
						$('.price ins').html(json_obj.gift_pack_global_price_html);
					}
					else{
						$('.price').html(json_obj.gift_pack_global_price_html);
					}
				}
				if($('div.gpwf_popup_enable').length){
					if (jQuery('.gpfw_giftpack_default_value').length){
			    		initPhotoSwipeFromDOM('.gpfw_giftpack_default_value');
			    	}
			    	if (jQuery('.gpfw_giftpack_uploaded_value').length){
			    		initPhotoSwipeFromDOM('.gpfw_giftpack_uploaded_value');
			    	}
			    }

            });


		}
		else{
			var variable_pro = false;
			if($('div.gpfw_product.gpfw_product_variable').length){
				var gpfw_productID = $('input[name="variation_id"]').val();
				var variable_pro = true;
			}
			else{
				var gpfw_productID = $('button[name="add-to-cart"]').val();
				if (typeof gpfw_productID === 'undefined') {
					var gpfw_productID = $('input[name="add-to-cart"]').val();
				}
			}

			var data = {
				'action': 'gpfw_check_gift_wrap',
				'gpfwcheckbox': 'unchecked',
				'gpfwproductID' : gpfw_productID
			};

			var gpfw_response = gpfw_ajax_request(data);
            gpfw_response.done(function(result){
            	var json_obj = JSON.parse(result);
            	$('.gift-pack_for-woocommerce-parent').html(json_obj.html);
				
				if(variable_pro){
					if(json_obj.gpfw_sale && $('div.woocommerce-variation-price .price ins').length){
						$('.woocommerce-variation-price .price ins').html(json_obj.gift_pack_global_price_html);
					}
					else if(json_obj.gpfw_sale == 0 && $('div.woocommerce-variation-price .price ins').length == 0 && $('div.woocommerce-variation-price .price').length){
						$('.woocommerce-variation-price .price').html(json_obj.gift_pack_global_price_html);
					}
					else if(json_obj.gpfw_sale == 0 && $('div.woocommerce-variation-price .price').length == 0 && $('p.price ins').length){
						$('.price ins').html(json_obj.gift_pack_global_price_html);
					}
					else if(json_obj.gpfw_sale == 0 && $('div.woocommerce-variation-price .price').length == 0 && $('p.price').length && $('p.price ins').length == 0){
						$('.price').html(json_obj.gift_pack_global_price_html);
					}
					else{
						$('p.price ins').html(json_obj.gift_pack_global_price_html);
					}
					$('input[name="active_price"]').val(json_obj.active_price);
				}
				else{
					if($('p.price ins').length == 1){
						$('.price ins').html(json_obj.gift_pack_global_price_html);
					}
					else{
						$('.price').html(json_obj.gift_pack_global_price_html);
					}
				}
            });
		}

	});

	$(document).on('change','.variations select',function(){
		$(gpfw_option_input).prop('checked',false);
		var val = $(this).val();
		$('.gift-pack_for-woocommerce-parent').html('');
		var variation_id = $('input[name="variation_id"]').val();
		var product_id = $('input[name="product_id"]').val();
		var data = {
			'action': 'gpfw_check_gift_wrap',
			'gpfwcheckbox': 'unchecked',
			'gpfwproductID' : variation_id,
			'var_pro_ID' : product_id
		};

		var gpfw_response = gpfw_ajax_request(data);
        gpfw_response.done(function(result){
        	var json_obj = JSON.parse(result);
        	if($('div.woocommerce-variation-price .price ins').length == 0 && $('div.woocommerce-variation-price .price').length == 0 && $('div.woocommerce-variation-price').length != 0 && $('p.price ins').length != 0){
        		$('p.price ins').html(json_obj.gift_pack_global_price_html);
        	}
        	else if($('div.woocommerce-variation-price .price ins').length == 0 && $('div.woocommerce-variation-price .price').length == 0 && $('div.woocommerce-variation-price').length != 0 && $('p.price ins').length == 0 && $('p.price').length != 0){
        		$('p.price').html(json_obj.gift_pack_global_price_html);
        	}
			$('input[name="active_price"]').val(json_obj.active_price);
        	
        });
	});


	$(document).on('click','a.reset_variations',function(){
		$(gpfw_option_input).prop('checked',false);
		$('.gift-pack_for-woocommerce-parent').html('');
	});

});

function gpfw_ajax_request(gpfw_data){
	return jQuery.ajax({
            type: "POST",
            url: ajax_object.ajaxurl,
            data: gpfw_data,
        });
}



