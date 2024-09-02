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

/* If Global Price is not set with checkmark */
$gpfw_object = Gift_Pack_For_Woocommerce::gpfw_get_options();
global $product;
$pro_cat_arr = array();
$terms = get_the_terms($product->get_id(),'product_cat');

foreach($terms as $key => $value){
    $pro_cat_arr[] = $value->term_id;
}

$gpfw_pro_cat = $gpfw_object['gpfw_pro_cat'];

if ($gpfw_object['gpfw_cat_enable'] == 'yes' && is_array($gpfw_pro_cat) && is_array($pro_cat_arr) && !empty($gpfw_pro_cat) && !empty($pro_cat_arr)) {
    $intersection = array_intersect($gpfw_pro_cat, $pro_cat_arr);
    if (empty($intersection)) {
        return;
    }
}

$swipe_class = '';
if($gpfw_object['gpfw_popup_option'] == 'yes'){
    $swipe_class = 'gpwf_popup_enable';
}

?>

<?php if($this->gpfw_options['gpfw_global_price'] == '' ) {   ?>
<div class="hidden-field gpfw_gift_pack_fields">
    <div class="form-row form-row-wide" id="gift_pack_option_field" data-priority="">
        <div class="woocommerce-input-wrapper gpfw_check_price">
            <div class="gpfw_giftwrap_base_gift_title">
                <?php if($this->gpfw_options['gpfw_choose_gift_pack_msg'] != '' ) { 
                            $gpfw_choose_gift_pack_msg= $this->gpfw_options['gpfw_choose_gift_pack_msg'];
                            echo esc_html($gpfw_choose_gift_pack_msg);  
                          } else{
                              esc_html_e('Buying for a loved one?','gift-pack-for-woocommerce');  
                          }
                    ?>
            </div>
            <div class="gpfw_giftwrap_base_gift">
                <?php if($this->gpfw_options['gpfw_gift_pack_bg_img'] != '' ) { 
                    $gpfw_gift_pack_bg_imgs= $this->gpfw_options['gpfw_gift_pack_bg_img']
                    ?>
                <img src="<?php echo esc_url($gpfw_gift_pack_bg_imgs); ?>" class="gpfw_img_responsive"
                    alt="gift_pack_img">

                <?php } else { 
                       $default_gift_pack_images = plugin_dir_url(__DIR__). 'images/default_gift.png';
                    ?>
                <img src="<?php echo esc_url($default_gift_pack_images); ?>" class="gpfw_img_responsive"
                    alt="gift_pack_img">
                <?php } ?>

            </div>
            <label class="checkbox gpfw_check_box">
                <input type="checkbox" class="input-checkbox" name="gift_pack_option" id="gift_pack_option" value="1">
                <div class="gpfw_add_gift_pack_label">
                    <?php 
                            if($this->gpfw_options['gpfw_gift_wrap_text_label'] != '' ) { 
                                $gpfw_gift_wrap_text_label= $this->gpfw_options['gpfw_gift_wrap_text_label'];
                                echo esc_html($gpfw_gift_wrap_text_label);  
                            } 
                            else{
                                esc_html_e('Add Gift Wrap','gift-pack-for-woocommerce');  
                            }
                        ?>
                </div>
                <div class="gpfw_check_price">
                    <div class="gpfw_gift_pack_price"><?php echo esc_html($gift_pack_wrapper_price_html); ?></div>
                </div>
            </label>
        </div>
    </div>
    <input type="hidden" name="gift_pack_wrapper_price" value="<?php echo esc_attr($gift_pack_wrapper_price); ?>">
    <input type="hidden" name="active_price" value="<?php echo esc_attr($active_price); ?>">
</div>
<?php } else if($this->gpfw_options['gpfw_global_price'] == 1){  ?>
<!-- Gift Wrapper Global Price  -->
<div class="hidden-field gpfw_gift_pack_fields">
    <div class="form-row form-row-wide" id="gift_pack_option_field" data-priority="">
        <span class="woocommerce-input-wrapper gpfw_check_price">
            <div class="gpfw_giftwrap_base_gift_title">
                <?php if($this->gpfw_options['gpfw_choose_gift_pack_msg'] != '' ) { 
                            $gpfw_choose_gift_pack_msg= $this->gpfw_options['gpfw_choose_gift_pack_msg'];
                            echo esc_html($gpfw_choose_gift_pack_msg);  
                          } else{
                            esc_html_e('Buying for a loved one?','gift-pack-for-woocommerce');  
                          }
                    ?>
            </div>
            <div class="gpfw_giftwrap_base_gift">
                <?php if($this->gpfw_options['gpfw_gift_pack_bg_img'] != '' ) { 
                    $gpfw_gift_pack_bg_img= $this->gpfw_options['gpfw_gift_pack_bg_img']
                    ?>
                <img src="<?php echo esc_url($gpfw_gift_pack_bg_img); ?>" class="gpfw_img_responsive"
                    alt="gift_pack_img">
                <?php } else { 
                     $default_gift_pack_images = plugin_dir_url(__DIR__). 'images/default_gift.png';
                 ?>
                <img src="<?php echo esc_url($default_gift_pack_images); ?>" class="gpfw_img_responsive"
                    alt="gift_pack_img">
                <?php } ?>
            </div>
            <label class="checkbox gpfw_check_box">
                <input type="checkbox" class="input-checkbox" name="gift_pack_option" id="gift_pack_option" value="1">
                <div class="gpfw_add_gift_pack_label">
                    <?php 
                           if($this->gpfw_options['gpfw_gift_wrap_text_label'] != '' ) { 
                               $gpfw_gift_wrap_text_label= $this->gpfw_options['gpfw_gift_wrap_text_label'];
                               echo esc_html($gpfw_gift_wrap_text_label);  
                           } 
                           else{
                               esc_html_e('Add Gift Wrap','gift-pack-for-woocommerce');  
                           }
                           
                       ?>
                </div>
                <div class="gpfw_gift_pack_price"><?php echo esc_html($gift_pack_wrappers_price_html); ?></div>
            </label>

            
        </span>
    </div>
    <input type="hidden" name="gift_pack_wrapper_price" value="<?php echo esc_attr((float)$gift_pack_global_price); ?>">
    <input type="hidden" name="active_price" value="<?php echo esc_attr($active_price); ?>">
</div>
<?php } ?>
<div class="gift-pack_for-woocommerce-parent <?php echo esc_attr($swipe_class ); ?>">
</div>