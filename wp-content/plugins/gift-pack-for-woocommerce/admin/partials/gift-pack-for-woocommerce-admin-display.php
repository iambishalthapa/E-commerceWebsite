<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Gift_Pack_For_Woocommerce
 * @subpackage Gift_Pack_For_Woocommerce/admin/partials
 */
$gpfw_object = new Gift_Pack_For_Woocommerce;
$gpfw_options = Gift_Pack_For_Woocommerce::gpfw_get_options();

?>
<!-- Gift Pack For Woocommerce Admin Side Fields -->
<div class="wrap">
    <h2 class="gift_pack_for_woocommerce_title"><?php esc_html_e( 'Gift Pack For Woocommerce' );?></h2>
    <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="save_gpfw_update_settings" />
        <div class="gift_packs_for_woocommerce">
        <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Choose your own gift pack/wrapper images ','gift-pack-for-woocommerce');?></div>
            <!-- Image Upload / Update -->
            <div class="gpfw_file_uploads">
                <div class="gpfw_gallery_sec">
                    <ul class="gpfw_gallery_gallery_mtb">
                        <?php
                            $hidden = array();
                            if( $images = get_posts( array(
                                'post_type' => 'attachment',
                                'orderby' => 'post__in',
                                'order' => 'ASC',
                                'post__in' => explode(',',$gpfw_options['gpfw_gallery']), 
                                'numberposts' => -1,
                                'post_mime_type' => 'image'
                            ) ) ) {
                            foreach( $images as $image ) {
                                    $hidden[] = $image->ID;
                                    $image_src = wp_get_attachment_image_src( $image->ID, array( 80, 80 ) );
                                    $gpfw_gallery_image_alt = get_post_meta($image->ID, '_wp_attachment_image_alt', TRUE);
                                    $url = site_url();
                                    $gpfw_gallery_url = $url . '/wp-admin/upload.php?item=' . $image->ID ;
                                    ?>
                        <li data-id="<?php echo esc_attr($image->ID);  ?>">
                            <a id="gpfw_uploaded_gallery_url"><img src="<?php echo esc_url($image_src[0]); ?>" alt="<?php echo esc_attr($gpfw_gallery_image_alt); ?>" /></a><a href="#" class="gpfw_gallery_gallery_remove">&times;</a>
                        </li>

                        <?php }

                            } ?>
                    </ul>
                    <div style="clear:both"></div>
                </div>
                <input type="hidden" name="gpfw_gallery" value="<?php echo esc_attr(join(',',$hidden)); ?>"
                    class="file-input" />
                <a href="#" class="button gpfw_gallery_upload_gallery_button"><?php esc_html_e('Click here to add gift pack/wrapper images','gift-pack-for-woocommerce');?></a>
                <?php if ( ! did_action( 'wp_enqueue_media' ) ) wp_enqueue_media(); ?>
            </div>
            <div class="global_price_value">
                <div class="global_price_title backend_field_title"><?php esc_html_e('Disable Gift Pack Images:','gift-pack-for-woocommerce');?></div>
                <div class="global_price_meta">
                    <input type="checkbox" name="gpfw_disable_gift_pack_images" id="gpfw_disable_gift_pack_images" value="1"
                    <?php if($gpfw_options['gpfw_disable_gift_pack_images']) esc_html_e('checked="checked"','gift-pack-for-woocommerce'); else '';?>>
                </div>
            </div>
            <div class="global_price_value gpfw_global_prices">
                <div class="global_price_title backend_field_title"><?php esc_html_e('Enable Global Price:','gift-pack-for-woocommerce');?>
                <br />
                <small><?php esc_html_e( 'This price will apply to all products globally','gift-pack-for-woocommerce' );?></small>
                 </div>
                <div class="global_price_meta">
                    <input type="checkbox" name="gpfw_global_price" id="gpfw_global_price" value="1"
                    <?php if($gpfw_options['gpfw_global_price']) esc_html_e('checked="checked"','gift-pack-for-woocommerce'); else '';?> >
                </div>
            </div>
<!--Gift Wrap Price-->
<?php       
    $current_language = apply_filters('wpml_current_language', NULL);
    if ($current_language) {
        $gift_price_option_name = 'gpfw_gift_price' . $current_language;
        $gpfw_gift_price = get_option($gift_price_option_name);

        if (empty($gpfw_gift_price)) {
            // If the stored value is empty, set a default value
            $gpfw_gift_price = '10';
            update_option($gift_price_option_name, $gpfw_gift_price);
        }

        if (isset($_POST['gpfw_gift_price'])) {
            $updated_gift_price = sanitize_text_field($_POST['gpfw_gift_price']);
            update_option($gift_price_option_name, $updated_gift_price);
            $gpfw_gift_price = $updated_gift_price;
        }
    } else {
        // Fallback if WPML is not active
        $gpfw_gift_price = get_option('gpfw_gift_price');

        if (empty($gpfw_gift_price)) {
            // If the stored value is empty, set a default value
            $gpfw_gift_price = '10';
            update_option('gpfw_gift_price', $gpfw_gift_price);
        }

        if (isset($_POST['gpfw_gift_price'])) {
            $updated_gift_price = sanitize_text_field($_POST['gpfw_gift_price']);
            update_option('gpfw_gift_price', $updated_gift_price);
            $gpfw_gift_price = $updated_gift_price;
        }
    }
?>
<div class="global_price_value">
    <div class="gpfw_gift_price_value backend_field_title"><?php esc_html_e('Gift Wrap Price:', 'gift-pack-for-woocommerce'); ?></div>
        <div class="gpfw_gift_value">
            <input type="text" name="gpfw_gift_price" id="gpfw_gift_price" value="<?php echo esc_attr($gpfw_gift_price); ?>">
        </div>
</div>

<div class="global_price_value gpfw_global_prices"><h3><strong><?php esc_html_e('Change Default Labels:','gift-pack-for-woocommerce');?></strong></h3></div>

<!--Gift Wrap-->
    <?php
        $current_language = apply_filters('wpml_current_language', NULL);
        if ($current_language) {
            $gift_wrap_text_option_name = 'gift_wrap_text' . $current_language;

        if (isset($_POST['gift_wrap_text'])) {
            $updated_gift_wrap_text = sanitize_text_field($_POST['gift_wrap_text']);
            update_option($gift_wrap_text_option_name, $updated_gift_wrap_text);
        }
        $gift_wrap_text = get_option($gift_wrap_text_option_name);
        } else {
        $gift_wrap_text = get_option('gift_wrap_text');
        }
    ?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_wrap backend_field_title"><?php esc_html_e('Gift Wrap:', 'gift-pack-for-woocommerce'); ?><br />
        <small><?php esc_html_e('Used at Cart/Checkout', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <div class="gpfw_gift_value">
        <input type="text" name="gift_wrap_text" id="gift_wrap_text" value="<?php echo esc_attr($gift_wrap_text); ?>" placeholder="<?php esc_html_e("Enter your Gift Wrap Text", "gift-pack-for-woocommerce"); ?>">
    </div>
</div>

<!--Gift Pack Greeting Label-->
    <?php
        $current_language = apply_filters('wpml_current_language', NULL);
        if ($current_language) {
            $gpfw_gift_pack_message_text_option_name = 'gpfw_gift_pack_message_text' . $current_language;

        if (isset($_POST['gpfw_gift_pack_message_text'])) {
            $updated_gift_wrap_message_text = sanitize_text_field($_POST['gpfw_gift_pack_message_text']);
            update_option($gpfw_gift_pack_message_text_option_name, $updated_gift_wrap_message_text);
        }

        $gpfw_gift_pack_message_text = get_option($gpfw_gift_pack_message_text_option_name);
    } else {
        $gpfw_gift_pack_message_text = get_option('gpfw_gift_pack_message_text');
    }
    ?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Gift Pack Greeting Label:', 'gift-pack-for-woocommerce'); ?><br />
        <small><?php esc_html_e('Used at Cart/Checkout', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <div class="gpfw_gift_value">
        <input type="text" name="gpfw_gift_pack_message_text" id="gpfw_gift_price" value="<?php echo esc_attr($gpfw_gift_pack_message_text); ?>" placeholder="<?php esc_html_e("Enter your Greeting", "gift-pack-for-woocommerce"); ?>">
    </div>
</div>


<!--Gift Pack Images Label-->
    <?php
        $current_language = apply_filters('wpml_current_language', NULL);

        if ($current_language) {
        $gpfw_gift_pack_image_text_option_name = 'gpfw_gift_pack_image_text' . $current_language;

        if (isset($_POST['gpfw_gift_pack_image_text'])) {
            $updated_gift_wrap_image_text = sanitize_text_field($_POST['gpfw_gift_pack_image_text']);
            update_option($gpfw_gift_pack_image_text_option_name, $updated_gift_wrap_image_text);
        }

        $gpfw_gift_pack_image_text = get_option($gpfw_gift_pack_image_text_option_name);
        } else {
        $gpfw_gift_pack_image_text = get_option('gpfw_gift_pack_image_text');
        }
    ?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Gift Pack Images Label:', 'gift-pack-for-woocommerce'); ?><br />
        <small><?php esc_html_e('Used at Cart/Checkout', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <div class="gpfw_gift_value">
        <input type="text" name="gpfw_gift_pack_image_text" id="gpfw_gift_price" value="<?php echo esc_attr($gpfw_gift_pack_image_text); ?>" placeholder="<?php esc_html_e("Enter your Gift Image Label Text", "gift-pack-for-woocommerce"); ?>">
    </div>
</div>

<div class="global_price_value">
    <div class="global_price_title backend_field_title"><?php esc_html_e('Disable Gift Pack Note:','gift-pack-for-woocommerce');?></div>
        <div class="global_price_meta">
            <input type="checkbox" name="gpfw_disable_gift_pack_note" id="gpfw_disable_gift_pack_note" value="1"
        <?php if($gpfw_options['gpfw_disable_gift_pack_note']) esc_html_e('checked="checked"','gift-pack-for-woocommerce'); else '';?>>
    </div>
</div>

<!--Placeholder Texts for Greeting Message-->
    <?php
        $current_language = apply_filters('wpml_current_language', NULL);
        if ($current_language) {
        $gift_price_option_name = 'gpfw_gift_pack_note_placeholder' . $current_language;

        if (isset($_POST['gpfw_gift_pack_note_placeholder'])) {
            $updated_gift_price = sanitize_text_field($_POST['gpfw_gift_pack_note_placeholder']);
            update_option($gift_price_option_name, $updated_gift_price);
        }

        $gpfw_gift_pack_note_placeholder = get_option($gift_price_option_name);
        } else {
        $gpfw_gift_pack_note_placeholder = get_option('gpfw_gift_pack_note_placeholder');
        }
        $current_language = apply_filters('wpml_current_language', NULL);
        if ($current_language) {
            $gpfw_gift_pack_note_placeholder = 'gpfw_gift_pack_note_placeholder' . $current_language;
            if (isset($_POST['gpfw_gift_pack_note_placeholder'])) {
                $updated_gift_pack_note_placeholder = sanitize_text_field($_POST['gpfw_gift_pack_note_placeholder']);
                update_option($gpfw_gift_pack_note_placeholder, $updated_gift_pack_note_placeholder);
            }
            $gpfw_gift_pack_note_placeholder = get_option($gpfw_gift_pack_note_placeholder);
        }
    ?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Placeholder Texts for Greeting Message:', 'gift-pack-for-woocommerce'); ?><br />
        <small><?php esc_html_e('Used in Product Details', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <div class="gpfw_gift_value">
        <input type="text" name="gpfw_gift_pack_note_placeholder" id="gpfw_gift_price" value="<?php echo esc_attr($gpfw_gift_pack_note_placeholder); ?>" placeholder="<?php esc_html_e("Placeholder Texts for Greeting Message", "gift-pack-for-woocommerce"); ?>">
    </div>
</div>

<!--Gift Wrap Label-->
    <?php 
        $current_language = apply_filters('wpml_current_language', NULL);
        if ($current_language) {
        $gpfw_gift_wrap_text_label = 'gpfw_gift_wrap_text_label' . $current_language;

        if (isset($_POST['gpfw_gift_wrap_text_label'])) {
            $updated_gift_wrap_text_label = sanitize_text_field($_POST['gpfw_gift_wrap_text_label']);
            update_option($gpfw_gift_wrap_text_label, $updated_gift_wrap_text_label);
        }
        $gpfw_gift_wrap_text_label = get_option($gpfw_gift_wrap_text_label);
        } else {
        $gpfw_gift_wrap_text_label = get_option('gpfw_gift_wrap_text_label');
        }
    ?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Gift Wrap Label:', 'gift-pack-for-woocommerce'); ?><br />
        <small><?php esc_html_e('Used in Product Details (Frontend)', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <div class="gpfw_gift_value">
        <input type="text" name="gpfw_gift_wrap_text_label" id="gpfw_gift_wrap_text_label" value="<?php echo esc_attr($gpfw_gift_wrap_text_label); ?>" placeholder="<?php esc_html_e('Gift Wrap Label', 'gift-pack-for-woocommerce'); ?>">
    </div>
</div>

<!--Choose Gift Pack Image Label-->
    <?php
        $current_language = apply_filters('wpml_current_language', NULL);

        if ($current_language) {
        $gpfw_choose_gift_pack_img = 'gpfw_choose_gift_pack_img' . $current_language;

        if (isset($_POST['gpfw_choose_gift_pack_img'])) {
            $updated_gift_pack_img = sanitize_text_field($_POST['gpfw_choose_gift_pack_img']);
            update_option($gpfw_choose_gift_pack_img, $updated_gift_pack_img);
        }
        $gpfw_choose_gift_pack_img = get_option($gpfw_choose_gift_pack_img);
        } else {
        $gpfw_choose_gift_pack_img = get_option('gpfw_choose_gift_pack_img');
        }
    ?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Choose Gift Pack Image Label:', 'gift-pack-for-woocommerce'); ?><br />
        <small><?php esc_html_e('Used in Product Details (Frontend)', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <div class="gpfw_gift_value">
        <input type="text" name="gpfw_choose_gift_pack_img" id="gpfw_gift_wrap_text_label" value="<?php echo esc_attr($gpfw_choose_gift_pack_img); ?>" placeholder="<?php esc_html_e('Choose Gift Pack Image', 'gift-pack-for-woocommerce'); ?>">
    </div>
</div>

<!--Add Gift Pack Message-->
    <?php
        $current_language = apply_filters('wpml_current_language', NULL);

        if ($current_language) {
        $gpfw_choose_gift_pack_msg = 'gpfw_choose_gift_pack_msg' . $current_language;

        if (isset($_POST['gpfw_choose_gift_pack_msg'])) {
            $updated_gift_pack_msg = sanitize_text_field($_POST['gpfw_choose_gift_pack_msg']);
            update_option($gpfw_choose_gift_pack_msg, $updated_gift_pack_msg);
        }

        $gpfw_choose_gift_pack_msg = get_option($gpfw_choose_gift_pack_msg);
        } else {
        $gpfw_choose_gift_pack_msg = get_option('gpfw_choose_gift_pack_msg');
        }
    ?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Add Gift Pack Message', 'gift-pack-for-woocommerce'); ?><br />
        <small><?php esc_html_e('Used in Product Details (Frontend)', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <div class="gpfw_gift_value">
        <input type="text" name="gpfw_choose_gift_pack_msg" id="gpfw_choose_gift_pack_msg" value="<?php echo esc_attr($gpfw_choose_gift_pack_msg); ?>" placeholder="<?php esc_html_e("Add Gift Pack Message", "gift-pack-for-woocommerce"); ?>">
    </div>
</div>


<!--Gift Background Image-->
    <?php
        $current_language = apply_filters('wpml_current_language', NULL);

        if ($current_language) {
        $gpfw_gift_pack_bg_img = 'gpfw_gift_pack_bg_img' . $current_language;

        if (isset($_POST['gpfw_gift_pack_bg_img'])) {
            $updated_gift_pack_bg_img = sanitize_text_field($_POST['gpfw_gift_pack_bg_img']);
            update_option($gpfw_gift_pack_bg_img, $updated_gift_pack_bg_img);
        }

        $gpfw_gift_pack_bg_img = get_option($gpfw_gift_pack_bg_img);
        } else {
        $gpfw_gift_pack_bg_img = get_option('gpfw_gift_pack_bg_img');
        }
    ?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Gift Background Image', 'gift-pack-for-woocommerce'); ?>
        <br />
        <small><?php esc_html_e('Please use 78px*198px size image (Frontend Gift BG Image)', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <input type="text" name="gpfw_gift_pack_bg_img" id="gpfw_gift_pack_bg_img" maxlength="255" size="25" value="<?php echo esc_attr($gpfw_gift_pack_bg_img); ?>">
    <input id="gpfw_gift_pack_bg_img_btn" class="button" type="button" value="Upload Image" />
</div>

<!--Gift Pack Background Color-->
    <?php 
    $current_language = apply_filters('wpml_current_language', NULL);
        if ($current_language) {
        $gpfw_gift_pack_bg_color = 'gpfw_gift_pack_bg_color' . $current_language;

        if (isset($_POST['gpfw_gift_pack_bg_color'])) {
            $updated_gift_pack_bg_color = sanitize_text_field($_POST['gpfw_gift_pack_bg_color']);
            update_option($gpfw_gift_pack_bg_color, $updated_gift_pack_bg_color);
        }

        $gpfw_gift_pack_bg_color = get_option($gpfw_gift_pack_bg_color);
        } else {
        $gpfw_gift_pack_bg_color = get_option('gpfw_gift_pack_bg_color');
        }
    ?>

<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Gift Pack Background Color','gift-pack-for-woocommerce');?><br />
        <small><?php esc_html_e('Used in Product Details (Frontend)','gift-pack-for-woocommerce');?></small>
    </div>
    <input type="text" name="gpfw_gift_pack_bg_color" id="gpfw_gift_pack_bg_color" maxlength="255" size="25" value="<?php echo esc_attr($gpfw_gift_pack_bg_color); ?>">
</div>

<!--Gift Pack Title Color-->
<?php
    $current_language = apply_filters('wpml_current_language', NULL);
    $gpfw_giftwrap_base_gift_title_color = '';
    if ($current_language) {
        $gpfw_giftwrap_base_gift_title_color = 'gpfw_giftwrap_base_gift_title_color' . $current_language;

        if (isset($_POST['gpfw_giftwrap_base_gift_title_color'])) {
            $updated_gift_pack_price_and_checkbox = sanitize_text_field($_POST['gpfw_giftwrap_base_gift_title_color']);
            update_option($gpfw_giftwrap_base_gift_title_color, $updated_gift_pack_price_and_checkbox);
        }

        $gpfw_giftwrap_base_gift_title_color = get_option($gpfw_giftwrap_base_gift_title_color);
    } else {
        $gpfw_giftwrap_base_gift_title_color = get_option('gpfw_giftwrap_base_gift_title_color');
    }
?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Gift Pack Title Color', 'gift-pack-for-woocommerce');?>
        <br />
        <small><?php esc_html_e('Used in Product Details (Frontend)', 'gift-pack-for-woocommerce');?></small>
    </div>
    <input type="text" name="gpfw_giftwrap_base_gift_title_color" id="gpfw_giftwrap_base_gift_title_color" maxlength="255" size="25" value="<?php echo esc_attr($gpfw_giftwrap_base_gift_title_color); ?>">
</div>

<!--Gift Pack Label Color-->
<?php
    $current_language = apply_filters('wpml_current_language', NULL);
    if ($current_language) {
    $gpfw_add_gift_pack_label_color = 'gpfw_add_gift_pack_label_color' . $current_language;

    if (isset($_POST['gpfw_add_gift_pack_label_color'])) {
        $updated_gift_pack_label_color = sanitize_text_field($_POST['gpfw_add_gift_pack_label_color']);
        update_option($gpfw_add_gift_pack_label_color, $updated_gift_pack_label_color);
    }

    $gpfw_add_gift_pack_label_color = get_option($gpfw_add_gift_pack_label_color);
    } else {
    $gpfw_add_gift_pack_label_color = get_option('gpfw_add_gift_pack_label_color');
    }
?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Gift Pack Label Color', 'gift-pack-for-woocommerce'); ?>
        <br />
        <small><?php esc_html_e('Used in Product Details (Frontend)', 'gift-pack-for-woocommerce'); ?></small>
    </div>
    <input type="text" name="gpfw_add_gift_pack_label_color" id="gpfw_add_gift_pack_label_color" maxlength="255" size="25" value="<?php echo esc_attr($gpfw_add_gift_pack_label_color); ?>">
</div>


<!--Gift Pack Autochange Price & Checkbox Color-->
<?php
    $current_language = apply_filters('wpml_current_language', NULL);

    if ($current_language) {
    $gpfw_add_gift_pack_price_and_checkbox = 'gpfw_add_gift_pack_price_and_checkbox' . $current_language;

    if (isset($_POST['gpfw_add_gift_pack_price_and_checkbox'])) {
        $updated_gift_pack_price_and_checkbox = sanitize_text_field($_POST['gpfw_add_gift_pack_price_and_checkbox']);
        update_option($gpfw_add_gift_pack_price_and_checkbox, $updated_gift_pack_price_and_checkbox);
    }

    $gpfw_add_gift_pack_price_and_checkbox = get_option($gpfw_add_gift_pack_price_and_checkbox);
    } else {
        $gpfw_add_gift_pack_price_and_checkbox = get_option('gpfw_add_gift_pack_price_and_checkbox');
    }
?>
<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title"><?php esc_html_e('Gift Pack Autochange Price & Checkbox Color','gift-pack-for-woocommerce');?>
        <br />
        <small><?php esc_html_e( 'Used in Product Details (Frontend)','gift-pack-for-woocommerce' );?></small>
    </div>
    <input type="text" name="gpfw_add_gift_pack_price_and_checkbox" id="gpfw_add_gift_pack_price_and_checkbox" maxlength="255" size="25" value="<?php echo esc_attr($gpfw_add_gift_pack_price_and_checkbox); ?>">
</div>

<div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title">
        <?php esc_html_e('Enable Category Wise Gift Pack Option','gift-pack-for-woocommerce');?>
    </div>
    <input type="checkbox" name="gpfw_cat_enable" id="gpfw_cat_enable" value="yes" <?php echo esc_attr($gpfw_options['gpfw_cat_enable'] == 'yes' ? 'checked' : ''); ?>>
</div>
<div class="global_price_value" id="gpfw_product_category" style="display: <?php echo esc_attr($gpfw_options['gpfw_cat_enable'] == 'yes' ? 'flex' : 'none'); ?>;">
    <div class="gpfw_gift_pack_message backend_field_title">
        <?php esc_html_e('Product Categories','gift-pack-for-woocommerce');?>
    </div>
        <ul>
            <?php
                wp_terms_checklist(
                    '',
                    array(
                        'taxonomy' => 'product_cat',
                        'selected_cats' => $gpfw_options['gpfw_pro_cat'],
                    )
                );
            ?>
        </ul>
        </div>

    <!--Enable Gift Pack Popup-->
    <div class="global_price_value">
    <div class="gpfw_gift_pack_message backend_field_title">
        <?php esc_html_e('Enable Gift Pack Popup', 'gift-pack-for-woocommerce'); ?>
    </div>
        <input type="checkbox" name="gpfw_popup_option" id="gpfw_popup_option" value="yes" <?php echo empty($gpfw_options['gpfw_popup_option']) ? '' : 'checked'; ?>>
    </div>
        <div class="submit gpfw_save_changes_btn">
            <input type="submit" name="Submit" class="button-primary" value="<?php echo esc_attr( 'Save Changes' ) ?>" />
        </div>
        </div>
    </form>
</div>