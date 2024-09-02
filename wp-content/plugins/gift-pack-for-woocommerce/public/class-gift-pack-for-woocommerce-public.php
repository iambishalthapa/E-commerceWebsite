<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Gift_Pack_For_Woocommerce
 * @subpackage Gift_Pack_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Gift_Pack_For_Woocommerce
 * @subpackage Gift_Pack_For_Woocommerce/public
 * @author     It Path Solutions <shailm@itpathsolutions.com>
 */
class Gift_Pack_For_Woocommerce_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->gpfw_options = Gift_Pack_For_Woocommerce::gpfw_get_options();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gift_Pack_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gift_Pack_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/gift-pack-for-woocommerce-public.css', array(), $this->version, 'all' );
		

	}
	public function gpfw_data_show(){
		include plugin_dir_path( dirname( __FILE__ ) ).'public/partials/gift-pack-for-woocommerce-public-display.php';
	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gift_Pack_For_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gift_Pack_For_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gift-pack-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
 			wp_localize_script($this->plugin_name, 'ajax_object',  array('ajaxurl' => admin_url('admin-ajax.php')));
 		

	}

    
 

	

	// Front: Add a text input field inside the add to cart form on single product page
	public function gpfw_addgift_pack_wrapper_price_option_to_single_product(){
		global $product;
		if( $product->is_type('variable') || ! $product->get_meta( 'gift_pack_wrapper_price' ) ) return;
	}

	public function head_styles() { ?>
<style>
.gpfw_gift_pack_fields {
    <?php if($this->gpfw_options['gpfw_gift_pack_bg_color'] !='') {
        ?>background: <?php echo esc_attr($this->gpfw_options['gpfw_gift_pack_bg_color']);
        ?>;
        <?php
    }

    else {
        ?>background: #fff1ec;
        <?php
    }

    ?>
}

.gpfw_giftwrap_base_gift_title {
    <?php if($this->gpfw_options['gpfw_giftwrap_base_gift_title_color'] !='') {
        ?>color: <?php echo esc_attr($this->gpfw_options['gpfw_giftwrap_base_gift_title_color']);
        ?>;
        <?php
    }

    else {
        ?>color: #172646;
        <?php
    }

    ?>
}

.gpfw_add_gift_pack_label {
    <?php if($this->gpfw_options['gpfw_add_gift_pack_label_color'] !='') {
        ?>color: <?php echo esc_attr($this->gpfw_options['gpfw_add_gift_pack_label_color']);
        ?>;
        <?php
    }

    else {
        ?>color: #172646;
        <?php
    }

    ?>
}

.gpfw_check_box .gpfw_gift_pack_price,
.gpfw_check_box input[type=checkbox] {
    <?php if($this->gpfw_options['gpfw_add_gift_pack_price_and_checkbox'] !='') {
        ?>color: <?php echo esc_attr($this->gpfw_options['gpfw_add_gift_pack_price_and_checkbox']);
        ?>;
        <?php
    }
    else {
        ?>color: #172646;
        <?php
    }

    ?>
}
</style>
<?php } 
public function gpfw_product_option_custom_field(){
    global $product;
    $pro_var = '';
    if($product->is_type('variable')){
        $pro_var = $product->get_variation_attributes();
    }
    $active_price = sanitize_text_field((float) str_replace(',', '.', $product->get_price()));
    $gift_pack_wrapper_price = (float) str_replace(',', '.', $product->get_meta('gift_pack_wrapper_price'));
    $gift_pack_wrapper_price_html = strip_tags(wc_price(wc_get_price_to_display($product, array('price' => $gift_pack_wrapper_price))));
    $active_price_html = wc_price($active_price);
    $gpfw_regular_price = (float) str_replace(',', '.', $product->get_regular_price());
    $disp_price_sum_html = wc_price($active_price + $gift_pack_wrapper_price);

    $current_language = apply_filters('wpml_current_language', NULL);
    
    $gift_price_option_name = 'gpfw_gift_price' . $current_language;
    $gift_pack_global_price = get_option($gift_price_option_name);
    $gift_pack_global_price = (float) str_replace(',', '.', $gift_pack_global_price);
    $gift_pack_wrappers_price_html = strip_tags(wc_price(wc_get_price_to_display($product, array('price' => $gift_pack_global_price))));

    // Ensure $active_price and $gift_pack_global_price are numeric
    $active_price = isset($active_price) ? floatval($active_price) : 0;
    $gift_pack_global_price = isset($gift_pack_global_price) ? floatval($gift_pack_global_price) : 0;

    // Calculate the total price and format it
    $gift_pack_global_price_html = wc_price($active_price + $gift_pack_global_price);

    // Get product prices
    $gpfw_regular_price = (float) str_replace(',', '.', $product->get_regular_price());
    $gpfw_sale_price = (float) str_replace(',', '.', $product->get_sale_price());

    // Check Regular Price
    if ($gpfw_regular_price != 0 && $gpfw_sale_price != 0) {
        $gpfw_total_regular_price = '<del>' . wc_price($gpfw_regular_price) . '</del>';
    }

    // Display the gift pack for WooCommerce if applicable
    if (($product->is_type('variable') && !empty($pro_var)) || $product->is_type('simple')) {
        include plugin_dir_path(dirname(__FILE__)) . 'public/partials/gift-pack-for-woocommerce-public-display.php';
    }
}


    public function gpfw_check_gift_wrap_function(){
		include plugin_dir_path( dirname( __FILE__ ) ).'public/partials/gift-pack-html.php';
		wp_die();
	}

	// Front: Calculate new item price and add it as custom cart item data
	public function gpfw_add_custom_product_data( $cart_item_data, $product_id, $variation_id ) {
		if (isset($_POST['gift_pack_option']) && !empty($_POST['gift_pack_option'])) {
			$active_price =  sanitize_text_field($_POST['active_price']);
			$gift_pack_wrapper_price = (float) stripslashes($_POST['gift_pack_wrapper_price']);
		  	$cart_item_data['new_price'] = sanitize_text_field($_POST['active_price']) + (float)($_POST['gift_pack_wrapper_price']);
			$cart_item_data['gift_pack_wrapper_price'] = (float) stripslashes($_POST['gift_pack_wrapper_price']);
			$cart_item_data['active_price'] = (float) stripslashes($_POST['active_price']);
			$cart_item_data['unique_key'] = md5(microtime().rand());
		}
		if( isset( $_REQUEST['gpfw-gift-pack-note'] ) && !empty($_POST['gift_pack_option'])) {
			$cart_item_data[ 'gpfw_gift_pack_note' ] = sanitize_text_field($_REQUEST['gpfw-gift-pack-note']);
			$cart_item_data['unique_key'] = md5( microtime().rand() );
		}

		
		if( isset( $_REQUEST['gpfw_default_gift_pack_img'] ) && !empty($_POST['gift_pack_option']) ) {
			$cart_item_data[ 'gpfw_default_gift_pack_img' ] = sanitize_text_field($_REQUEST['gpfw_default_gift_pack_img']);
			$cart_item_data['unique_key'] = md5( microtime().rand() );
		}
		if( isset( $_REQUEST['gpfw_giftpack_uploaded_value'] ) && !empty($_POST['gift_pack_option']) ) {
			$cart_item_data[ 'gpfw_giftpack_uploaded_value' ] = sanitize_text_field($_REQUEST['gpfw_giftpack_uploaded_value']);
			$cart_item_data['unique_key'] = md5( microtime().rand() );
		}
		return $cart_item_data;
	}

	// Front: Set the new calculated cart item price
	public function gpfw_extra_price_add_custom_price($cart) {
		if (is_admin() && !defined('DOING_AJAX'))
			return;
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
			return;
		foreach($cart->get_cart() as $cart_item) {
			if (isset($cart_item['new_price']))
				$cart_item['data']->set_price((float) $cart_item['new_price']);
	    }
	}

	// Front: Display option in cart item
	public function gpfw_display_custom_item_data($cart_item_data, $cart_item) {
		
		if (isset($cart_item['gift_pack_wrapper_price'])) {
			if($this->gpfw_options['gift_wrap_text']!=''){
				$gift_wrap_text = $this->gpfw_options['gift_wrap_text'];
			}
			else{
				$gift_wrap_text = __("Gift Wrap","gift-pack-for-woocommerce");
			}	
		
			$cart_item_data[] = array(
				'name' => __($gift_wrap_text, "gift-pack-for-woocommerce"),
				'value' => strip_tags( wc_price( wc_get_price_to_display( $cart_item['data'], array('price' => $cart_item['gift_pack_wrapper_price'] ) ) ) )
			);
		}
		if( isset( $cart_item['gpfw_gift_pack_note'] ) ) {

			if($this->gpfw_options['gpfw_gift_pack_message_text']!=''){
				$gpfw_gift_pack_message_text = $this->gpfw_options['gpfw_gift_pack_message_text'];
			}
			else{
				$gpfw_gift_pack_message_text = __("Giftpack Note","gift-pack-for-woocommerce");
			}	
			$cart_item_data[] = array( "name" => $gpfw_gift_pack_message_text, "value" => $cart_item['gpfw_gift_pack_note'] );
		}
		
		if( isset( $cart_item['gpfw_default_gift_pack_img']  ) && $this->gpfw_options['gpfw_gallery'] == '') {
			
			if($this->gpfw_options['gpfw_gift_pack_image_text']!=''){
				$gpfw_gift_pack_image_text = $this->gpfw_options['gpfw_gift_pack_image_text'];
			}
			else{
				$gpfw_gift_pack_image_text = __("Giftpack Image 3","gift-pack-for-woocommerce");
			}	
			$green_gift_pack = plugin_dir_url(__DIR__) . 'public/images/green_gift_pack.png'; 
			$blue_gift_pack = plugin_dir_url(__DIR__) . 'public/images/blue_gift_pack.png'; 
			$pink_gift_pack = plugin_dir_url(__DIR__) . 'public/images/pink_gift_pack.png'; 
			$yellow_gift_pack = plugin_dir_url(__DIR__) . 'public/images/yellow_gift_pack.png';
			if($cart_item['gpfw_default_gift_pack_img']=="gpfw_blue_pack"){ 
				$gpfw_default_pack_img ='<img width="30px" height="30px" src="'.esc_url($blue_gift_pack).'">';
			}
			else if($cart_item['gpfw_default_gift_pack_img']=="gpfw_green_pack"){
				$gpfw_default_pack_img ='<img width="30px" height="30px" src="'.esc_url($green_gift_pack).'">';
			}
			else if($cart_item['gpfw_default_gift_pack_img']=="gpfw_pink_pack"){
				$gpfw_default_pack_img ='<img width="30px" height="30px" src="'.esc_url($pink_gift_pack).'">';
			}
			else if($cart_item['gpfw_default_gift_pack_img']=="gpfw_yellow_pack"){
				$gpfw_default_pack_img ='<img width="30px" height="30px" src="'.esc_url($yellow_gift_pack).'">';
			}
			else{
				$gpfw_default_pack_img = __("No Image Found","gift-pack-for-woocommerce");
			}			
			$cart_item_data[] = array( "name" => $gpfw_gift_pack_image_text, "value" => $gpfw_default_pack_img );
		}
		else if ( isset( $cart_item['gpfw_giftpack_uploaded_value']  ) && $this->gpfw_options['gpfw_gallery'] != ''){
			if($this->gpfw_options['gpfw_gift_pack_image_text']!=''){
				$gpfw_gift_pack_image_text = $this->gpfw_options['gpfw_gift_pack_image_text'];
			}
			else{
				$gpfw_gift_pack_image_text = __("Giftpack Image 1","gift-pack-for-woocommerce");
			}	
			$gpfw_giftpack_uploaded_values = wp_get_attachment_image_src( $cart_item['gpfw_giftpack_uploaded_value'] );
			if ( $gpfw_giftpack_uploaded_values ) : 
				$gpfw_giftpack_uploaded_value='<img src="'.esc_url($gpfw_giftpack_uploaded_values[0]).'" width="30px" height="30px" />';
			endif;
			$cart_item_data[] = array( "name" => $gpfw_gift_pack_image_text, "value" => $gpfw_giftpack_uploaded_value );
		}
		return $cart_item_data;
	}

	public function gpfw_save_order_item_product_waranty( $item , $cart_item_key, $values, $order ) {
		include plugin_dir_path( dirname( __FILE__ ) ).'public/partials/gift-pack-data-in-order.php'; 
    }		

    public function gpfw_filter_woocommerce_post_class($classes, $product){
    	global $woocommerce_loop;

    	if(!is_product()){
    		return $classes;
    	}

    	if($woocommerce_loop['name'] == 'related'){
    		return $classes;
    	}
    
	    if($product->is_type('simple')){
	    	$classes[] = 'gpfw_product gpfw_product_simple';
	    }
	    else{
	    	$classes[] = 'gpfw_product gpfw_product_variable';
	    }
	    
	    return $classes;
    }
}