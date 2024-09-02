(function ( $ ) {
	"use strict";

	$(function () {

		$( document ).ready(function() {	
			
           //in case general tab is disable for variable product
		    if ($("#product-type").val() == "variable") {
				$(".general_tab").show();
			}
			//on product type change
			$('#product-type').change(function(){
		    	if($(this).val() == 'variable'){
		    		$(".general_tab").show();
		    	}
		    });
		    //end for general tab
		});
	});

}(jQuery));