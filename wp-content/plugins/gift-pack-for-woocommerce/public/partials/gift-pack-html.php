<?php 

$gpfw_object = Gift_Pack_For_Woocommerce::gpfw_get_options();

$html = '';
$default_img_arr = array();
$hidden = array();
$data = array();
$gpfwproductID = (int)stripslashes($_POST['gpfwproductID']);
$var_pro_ID = (int)stripslashes($_POST['var_pro_ID']);
if ($gpfwproductID == 0 && $var_pro_ID) {
    $product = wc_get_product($var_pro_ID);
} else {
    $product = wc_get_product($gpfwproductID);
}
$active_price = sanitize_text_field((float) $product->get_price());
$gpfw_sale = 0;

if ($gpfw_object['gpfw_global_price'] == true) {
    $gift_pack_global_price = $gpfw_object['gpfw_gift_price'];
    if ($product->is_on_sale()) {
        $gpfw_sale = 1;
    }
} else {
    if ($product->is_type('simple')) {
        $gift_pack_global_price = get_post_meta($gpfwproductID, 'gift_pack_wrapper_price', true);
    } else {
        if ($product->is_on_sale()) {
            $gpfw_sale = 1;
        }
        $gift_pack_global_price = get_post_meta($var_pro_ID, 'gift_pack_wrapper_price', true);
    }
}

$sum_price = $active_price + $gift_pack_global_price;
$gift_pack_global_price_html = wc_price($sum_price);

$default_img_arr['green'] = plugin_dir_url(__DIR__) . 'images/green_gift_pack.png';
$default_img_arr['blue'] = plugin_dir_url(__DIR__) . 'images/blue_gift_pack.png';
$default_img_arr['pink'] = plugin_dir_url(__DIR__) . 'images/pink_gift_pack.png';
$default_img_arr['yellow'] = plugin_dir_url(__DIR__) . 'images/yellow_gift_pack.png';

$images = get_posts(
    array(
        'post_type' => 'attachment',
        'orderby' => 'post__in',
        'order' => 'ASC',
        'post__in' => explode(',', $gpfw_object['gpfw_gallery']),
        'numberposts' => -1,
        'post_mime_type' => 'image'
    )
);

if ($_POST['gpfwcheckbox'] == 'checked') {
    if ($gpfw_object['gpfw_global_price'] == '') {
        if ($gpfw_object['gpfw_gallery'] == '' && $gpfw_object['gpfw_disable_gift_pack_images']  == '') {
            $html .= '<div class="gpfw_giftpack_default_img">';
            $html .= '<div class="gpfw_add_gift_pack_label gpfw_choose_gift_pack_img">';

            if ($gpfw_object['gpfw_choose_gift_pack_img'] != '') {
                $gpfw_choose_gift_pack_img = $gpfw_object['gpfw_choose_gift_pack_img'];
                $html .= esc_html($gpfw_choose_gift_pack_img);
            } else {
                $html .= __('Select Gift Pack Image', "gift-pack-for-woocommerce");
            }

            $html .= '</div>';

            $html .= '<div class="gpfw_giftpack_default_value">';
            $a = 1;
            foreach ($default_img_arr as $key => $value) {
                $checked = ($a == 1 ? 'checked' : '');
                $html .= '<div class="gift_pack_input">
                        <label class="gpfw-radio-img">
                            <input type="radio" name="gpfw_default_gift_pack_img" id="' . $key . '" class="gpfw-input-hidden" value="gpfw_' . $key . '_pack" ' . $checked . '>
                            <div class="gpfw_giftpack_image">
                                <img src="' . esc_url($value) . '" alt="' . ucfirst($key) . ' Gift Pack">
                            </div>
                        </label>
                    </div>';
                $a++;
            }
            $html .= '</div>';
            $html .= '</div>';
        } else {
            if ($gpfw_object['gpfw_disable_gift_pack_images'] == '') {
                $html .= '<div class="gpfw_giftpack_uploaded_value">';
                $html .= '<div class="gpfw_add_gift_pack_label gpfw_choose_gift_pack_img">';

                if ($gpfw_object['gpfw_choose_gift_pack_img'] != '') {
                    $gpfw_choose_gift_pack_img = $gpfw_object['gpfw_choose_gift_pack_img'];
                    $html .= esc_html($gpfw_choose_gift_pack_img);
                } else {
                    $html .= __('Select Gift Pack Image', "gift-pack-for-woocommerce");
                }
                $html .= '</div>';
                $html .= '<div class="gpfw_giftpack_uploaded_value">';
                if ($images) {
                    $a = 1;
                    foreach ($images as $image) {
                        $checked = ($a == 1 ? 'checked' : '');
                        $hidden[] = $image->ID;
                        $image_src = wp_get_attachment_image_src($image->ID, array(500, 500));
                        $gpfw_gift_pack_image_alt = get_post_meta($image->ID, '_wp_attachment_image_alt', TRUE);

                        $html .= '<div class="gift_pack_input">
                                <label class="gpfw-radio-img">
                                    <input type="radio" alt="' . esc_attr($giftpack_image_alt) . '" name="gpfw_giftpack_uploaded_value" value="' . esc_attr($image->ID) . '" ' . $checked . '>
                                    <div class="gpfw_giftpack_image">
                                        <img class="gpfw_radio_image" src="' . esc_url($image_src[0]) . '" attach-id="' . esc_attr($image->ID) . '" alt="' . esc_attr($giftpack_image_alt) . '" style="height: 100%;  width: 100%;"/>
                                    </div>
                                </label>
                            </div>';
                        $a++;
                    }
                }
                $html .= '</div>';
                $html .= '</div>';
            }
        }
    } else if ($gpfw_object['gpfw_global_price'] == 1) {
        if ($gpfw_object['gpfw_gallery'] == '') {
            if ($gpfw_object['gpfw_disable_gift_pack_images'] == '') {
                $html .= '<div class="gpfw_giftpack_default_img">';
                $html .= '<div class="gpfw_giftpack_default_value">';
                $a = 1;
                foreach ($default_img_arr as $key => $value) {
                    $checked = ($a == 1 ? 'checked' : '');
                    $html .= '<div class="gift_pack_input">
                            <label class="gpfw-radio-img">
                                <input type="radio" name="gpfw_default_gift_pack_img" id="' . $key . '" class="gpfw-input-hidden" value="gpfw_' . $key . '_pack" ' . $checked . '>
                                <div class="gpfw_giftpack_image">
                                    <img src="' . esc_url($value) . '" alt="' . ucfirst($key) . ' Gift Pack">
                                </div>
                            </label>
                        </div>';
                    $a++;
                }
                $html .= '</div>';
                $html .= '</div>';
            }
        } else {
            if ($gpfw_object['gpfw_disable_gift_pack_images'] == '') {
                $html .= '<div class="gpfw_giftpack_uploaded_value">';
                $html .= '<div class="gpfw_add_gift_pack_label gpfw_choose_gift_pack_img">';
                if ($gpfw_object['gpfw_choose_gift_pack_img'] != '') {
                    $gpfw_choose_gift_pack_img = $gpfw_object['gpfw_choose_gift_pack_img'];
                    $html .= esc_html($gpfw_choose_gift_pack_img);
                } else {
                    $html .= __('Select Gift Pack Image', "gift-pack-for-woocommerce");
                }
                $html .= '</div>';
                $html .= '<div class="gpfw_giftpack_uploaded_value">';
                if ($images) {
                    $a = 1;
                    foreach ($images as $image) {
                        $checked = ($a == 1 ? 'checked' : '');
                        $hidden[] = $image->ID;
                        $image_src = wp_get_attachment_image_src($image->ID, array(500, 500));
                        $gpfw_gift_pack_image_alt = get_post_meta($image->ID, '_wp_attachment_image_alt', TRUE);

                        $html .= '<div class="gift_pack_input">
                                <label class="gpfw-radio-img">
                                    <input type="radio" alt="' . esc_attr($giftpack_image_alt) . '" name="gpfw_giftpack_uploaded_value" value="' . esc_attr($image->ID) . '" ' . $checked . '>
                                    <div class="gpfw_giftpack_image">
                                        <img class="gpfw_radio_image" src="' . esc_url($image_src[0]) . '" attach-id="' . esc_attr($image->ID) . '" alt="' . esc_attr($giftpack_image_alt) . '" style="height: 100%;  width: 100%;"/>
                                    </div>
                                </label>
                            </div>';
                        $a++;
                    }
                }
                $html .= '</div>';
                $html .= '</div>';
            }
        }
    }

    if ($gpfw_object['gpfw_disable_gift_pack_note'] == '') {
        $html .= '<div class="gpfw_giftpack_note"><div class="gpfw_giftpack_val"><textarea class="gpfw-gift-pack-note" name="gpfw-gift-pack-note" placeholder="' . ($gpfw_object['gpfw_gift_pack_note_placeholder'] == '' ?  __("Gift Pack Note", "gift-pack-for-woocommerce") : esc_html($gpfw_object['gpfw_gift_pack_note_placeholder'])) . '"
                   value="" minlength="5" maxlength="1000"></textarea>
           </div>
        </div>';
    }

    $data['html'] = $html;
    $data['gift_pack_global_price_html'] = $gift_pack_global_price_html;
    $data['active_price'] = $active_price;
    $data['gpfw_sale'] = $gpfw_sale;
    if ($gpfwproductID == 0 && $var_pro_ID) {
        $data['active_price_set'] = wc_price($active_price);
    }
} else {
    $data['html'] = $html;
    $data['gift_pack_global_price_html'] = wc_price($active_price);
    $data['active_price'] = $active_price;
    $data['gpfw_sale'] = $gpfw_sale;
    if ($gpfwproductID == 0 && $var_pro_ID) {
        $data['active_price_set'] = wc_price($active_price);
    }
}
echo json_encode($data);
?>
