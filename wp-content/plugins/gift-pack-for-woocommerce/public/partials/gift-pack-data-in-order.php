<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Gift_Pack_For_Woocommerce
 * @subpackage Gift_Pack_For_Woocommerce/public/partials
 */
   // Display Custom Meta Data in Email

  /* Gift Wrap Label */
	if($this->gpfw_options['gift_wrap_text']!=''){
	  $gift_wrap_text = sanitize_text_field($this->gpfw_options['gift_wrap_text']);
	}
	else{
	  $gift_wrap_text = __("Gift Wrap","gift-pack-for-woocommerce");
	}	
	if( isset( $values['gift_wrap_text'] ) ) {
	  $item->update_meta_data( $gift_wrap_text, $values['gift_wrap_text'] );
	}
       /* Gift Wrapper Price */
	if( isset( $values['gift_pack_wrapper_price'] ) ) {
	  $gift_pack_prices = get_woocommerce_currency_symbol(). $values['gift_pack_wrapper_price'];
   	  $item->update_meta_data( $gift_wrap_text, $gift_pack_prices );
	}
       /* GiftPack Message Label */
	if($this->gpfw_options['gpfw_gift_pack_message_text']!=''){
	  $gpfw_gift_pack_message_text = sanitize_text_field($this->gpfw_options['gpfw_gift_pack_message_text']);
	}
	else{
	  $gpfw_gift_pack_message_text = __("Giftpack Note","gift-pack-for-woocommerce");
	}	
        /* GiftPack Note */
	if( isset( $values['gpfw_gift_pack_note'] ) ) {
	  $item->update_meta_data( $gpfw_gift_pack_message_text, $values['gpfw_gift_pack_note'] );
	}
       /* GiftPack Image Label */
	if($this->gpfw_options['gpfw_gift_pack_image_text']!=''){
	  $gpfw_gift_pack_image_text = sanitize_text_field($this->gpfw_options['gpfw_gift_pack_image_text']);
	}
	else{
	  $gpfw_gift_pack_image_text = __("Giftpack Image","gift-pack-for-woocommerce");
	}	
       /* GiftPack Default Image */
	if( isset( $values['gpfw_default_gift_pack_img'] ) ) {	        
	  $green_gift_pack = plugin_dir_url(__DIR__) . 'images/green_gift_pack.png'; 
      $blue_gift_pack = plugin_dir_url(__DIR__) . 'images/blue_gift_pack.png'; 
	  $pink_gift_pack = plugin_dir_url(__DIR__) . 'images/pink_gift_pack.png'; 
	  $yellow_gift_pack = plugin_dir_url(__DIR__) . 'images/yellow_gift_pack.png';
	  if($values['gpfw_default_gift_pack_img']=="gpfw_blue_pack"){ 
	    $gpfw_default_pack_img ='<img width="30px" height="30px" src="'.esc_url($blue_gift_pack).'">';
	  } else if($values['gpfw_default_gift_pack_img']=="gpfw_green_pack"){
	    $gpfw_default_pack_img ='<img width="30px" height="30px" src="'.esc_url($green_gift_pack).'">';
	  } else if($values['gpfw_default_gift_pack_img']=="gpfw_pink_pack"){
	    $gpfw_default_pack_img ='<img width="30px" height="30px" src="'.esc_url($pink_gift_pack).'">';
	  } else if($values['gpfw_default_gift_pack_img']=="gpfw_yellow_pack"){
	    $gpfw_default_pack_img ='<img width="30px" height="30px" src="'.esc_url($yellow_gift_pack).'">';
	  }
	  else{
	    $gpfw_default_pack_img = __("No Image Found","gift-pack-for-woocommerce");
	  }		
	    $item->update_meta_data( $gpfw_gift_pack_image_text, $gpfw_default_pack_img );	
	  } 
          /* GiftPack Custom Giftpack Image */
    else if(isset($values['gpfw_giftpack_uploaded_value'])){
	  $gpfw_giftpack_uploaded_values = wp_get_attachment_image_src( $values['gpfw_giftpack_uploaded_value'] );
	  if ( $gpfw_giftpack_uploaded_values ) : 
	    $gpfw_giftpack_uploaded_value='<img src="'.esc_url($gpfw_giftpack_uploaded_values[0]).'" width="30px" height="30px" />';
	  endif;
	    $item->update_meta_data( $gpfw_gift_pack_image_text, $gpfw_giftpack_uploaded_value );	
	}
?>