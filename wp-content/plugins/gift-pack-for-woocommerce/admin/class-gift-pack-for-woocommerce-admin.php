<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Gift_Pack_For_Woocommerce
 * @subpackage Gift_Pack_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Gift_Pack_For_Woocommerce
 * @subpackage Gift_Pack_For_Woocommerce/admin
 * @author     It Path Solutions <shailm@itpathsolutions.com>
 */
class Gift_Pack_For_Woocommerce_Admin
{
    
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        
        $this->plugin_name      = $plugin_name;
        $this->version          = $version;
        $this->current_language = apply_filters('wpml_current_language', NULL); // Setting $current_language in the constructor
        
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        
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
        
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/gift-pack-for-woocommerce-admin.css', array(), $this->version, 'all');
        $screen = get_current_screen();
        if ('toplevel_page_' . $this->plugin_name == $screen->id) {
            wp_enqueue_style('wp-color-picker');
        }
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        
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
        $screen = get_current_screen();
        
        if ('toplevel_page_' . $this->plugin_name == $screen->id) {
            wp_enqueue_media();
            wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/gift-pack-for-woocommerce-admin.js', array(
                'jquery',
                'wp-color-picker'
            ), $this->version, false);
            
            /*wp_enqueue_script('custom', plugin_dir_url( __FILE__ ) . 'js/custom.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script( $this->plugin_name . '-admin-script', plugin_dir_url( __FILE__ ). 'js/admin.js', array( 'jquery', 'wp-color-picker' ),$this->version);
            wp_enqueue_script('gpfw-admin-js', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );*/
        }
        
        if ($screen->post_type == 'product') {
            wp_enqueue_script($this->plugin_name . '-admin-script', plugin_dir_url(__FILE__) . 'js/admin.js', array(), $this->version);
        }
    }
    
    /* Gift Wrapper Price Field */
    public function wc_cost_product_field()
    {
        $price = get_post_meta(get_the_ID(), 'gift_pack_wrapper_price', true);
        // Convert dot to comma for display
        $price = str_replace('.', ',', $price);

        woocommerce_wp_text_input(array(
            'id' => 'gift_pack_wrapper_price',
            'wrapper_class' => 'show_if_simple show_if_variable',
            'class' => 'wc_input_price short gpfw_checkbox_price',
            'placeholder' => 'Please enter gift pack/wrapper Price here',
            'label' => 'Gift Wrapper Cost (' . get_woocommerce_currency_symbol() . ')',
            'type' => 'text', // Handle comma input
            'value' => $price
        ));
    }
    
    // Backend: Saving product pricing option custom field value
    public function gpfw_save_product_custom_meta_data($product)
    {
        // Convert comma to dot for storage
        $price = isset($_POST['gift_pack_wrapper_price']) ? str_replace(',', '.', sanitize_text_field($_POST['gift_pack_wrapper_price'])) : '';
        $product->update_meta_data('gift_pack_wrapper_price', $price);
    }

        
    /* Check Woocommerce Plugin is Active or not */
    
    public function gpfw_check_for_woocommerce()
    {
        $class   = 'notice notice-error';
        $message = __('Woocommerce Plugin is Required for Gift Pack for Woocommerce Plugin.', 'gift-pack-for-woocommerce');
        if (!defined('WC_VERSION')) {
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        }
    }
    
    /* Image Upload Metabox */
    public function gpfw_metabox()
    {
        add_meta_box('gpfw-gallery-setting', __('Gift Pack For Woocommerce', 'gift-pack-for-woocommerce'), array(
            $this,
            'gpfw_gallery_settings'
        ), 'gift-pack-for-woocommerce');
    }
    
    /* Admin Menu Page */
    public function gpfw_plugin_admin_menu()
    {
        add_menu_page(__('Gift Pack For Woocommerce', 'gift-pack-for-woocommerce'), __('Gift Pack For Woocommerce', 'gift-pack-for-woocommerce'), 'manage_options', 'gift-pack-for-woocommerce', array(
            $this,
            'gpfw_options'
        ), 'dashicons-schedule', 99);
    }
    
    /* Settings Link*/
    public function gpfw_settings_link(array $links)
    {
        $url           = get_admin_url() . "admin.php?page=gift-pack-for-woocommerce";
        $settings_link = '<a href="' . esc_url($url) . '">' . esc_html('Settings') . '</a>';
        $links[]       = $settings_link;
        return $links;
    }
    
    /* Custom Options */
    public function gpfw_options()
    {
        // HTML Form inside this file
        include plugin_dir_path(dirname(__FILE__)) . 'admin/partials/gift-pack-for-woocommerce-admin-display.php';
    }
    
    public static function gpfw_sanitize_array($array)
    {
        $sanitize_array = array();
        
        foreach ($array as $key => $value) {
            $sanitize_array[sanitize_text_field($key)] = sanitize_text_field($value);
        }
        
        return $sanitize_array;
    }
    
    /* Update Settings */
    public function gpfw_update_settings()
    {
        
        $gpfw_object  = new Gift_Pack_For_Woocommerce();
        $gpfw_options = $gpfw_object->gpfw_get_options();
        
        
        // Restrict When Access Directly
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        //Set Category
        if (function_exists('icl_object_id') && $this->current_language) {

            $opt_cat_enable    = 'gpfw_cat_enable' . $this->current_language;
            $opt_cat_enable_value = get_option($opt_cat_enable);
            if ( isset($opt_cat_enable_value) && $opt_cat_enable_value !== false ) {
                $updated_cat_enable = isset($_POST['gpfw_cat_enable']) ? $_POST['gpfw_cat_enable'] : '';
                update_option('gpfw_cat_enable' . $this->current_language, sanitize_text_field($updated_cat_enable));
            } else {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_cat_enable' . $lang_code;
                    if (isset($_POST['gpfw_cat_enable'])) {
                        $updated_gpfw_cat_enable = sanitize_text_field(isset($_POST['gpfw_cat_enable']) ? $_POST['gpfw_cat_enable'] : '');
                        add_option($option_name, $updated_gpfw_cat_enable, null, 'no');
                    }
                }
            }
            //Set Category Which are show gift wrap 
            $opt_gpfw_pro_cat    = 'gpfw_pro_cat' . $this->current_language;
            $opt_gpfw_pro_cat_value = get_option($opt_gpfw_pro_cat);
            if ( isset($opt_gpfw_pro_cat_value) && $opt_gpfw_pro_cat_value !== false ) {
                update_option('gpfw_pro_cat' . $this->current_language, self::gpfw_sanitize_array($_POST['tax_input']['product_cat']) );
            } else {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_pro_cat' . $lang_code;
                    if (isset($_POST['tax_input']['product_cat'])) {
                        $updated_pro_cat = self::gpfw_sanitize_array($_POST['tax_input']['product_cat']);
                        add_option($option_name, $updated_pro_cat, null, 'no');
                    }
                }
            }            

            //Set Category Which are show gift wrap 
            $opt_gpfw_disable_gift_pack_images    = 'gpfw_disable_gift_pack_images' . $this->current_language;
            $opt_gpfw_disable_gift_pack_images_value = get_option($opt_gpfw_disable_gift_pack_images);
            if ( isset($opt_gpfw_pro_cat_value) && $opt_gpfw_disable_gift_pack_images_value !== false ) {
                $updated_gift_pack_images = isset($_POST['gpfw_disable_gift_pack_images']) ? $_POST['gpfw_disable_gift_pack_images'] : '';
                update_option('gpfw_disable_gift_pack_images' . $this->current_language, sanitize_text_field($updated_gift_pack_images) );
            } else {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_disable_gift_pack_images' . $lang_code;
                    if (isset($_POST['gpfw_disable_gift_pack_images'])) {
                        $updated_gift_pack_images = sanitize_text_field(isset($_POST['gpfw_disable_gift_pack_images']) ? $_POST['gpfw_disable_gift_pack_images'] : '');
                        add_option($option_name, $updated_gift_pack_images, null, 'no');
                    }
                }
            }

            // Enable / Disable Gift Pack Note
            $opt_gpfw_disable_gift_pack_note    = 'gpfw_disable_gift_pack_note' . $this->current_language;
            $opt_gpfw_disable_gift_pack_note_value = get_option($opt_gpfw_disable_gift_pack_note);
            if ( isset($opt_gpfw_disable_gift_pack_note_value) && $opt_gpfw_disable_gift_pack_note_value !== false) {
                $updated_disable_gift_pack_note = isset($_POST['gpfw_disable_gift_pack_note']) ? $_POST['gpfw_disable_gift_pack_note'] : '';
                update_option('gpfw_disable_gift_pack_note' . $this->current_language, sanitize_text_field($updated_disable_gift_pack_note) );
            } else {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_disable_gift_pack_note' . $lang_code;
                    if (isset($_POST['gpfw_disable_gift_pack_note'])) {
                        $updated_disable_gift_pack_note = sanitize_text_field(isset($_POST['gpfw_disable_gift_pack_note']) ? $_POST['gpfw_disable_gift_pack_note'] : '');
                        add_option($option_name, $updated_disable_gift_pack_note, null, 'no');
                    }
                }
            }

        } else {

            //Gift Product Cat filter enable
            if ($gpfw_options['gpfw_cat_enable'] !== false) {
                update_option('gpfw_cat_enable', sanitize_text_field($_POST['gpfw_cat_enable']));
            } else {
                add_option('gpfw_cat_enable', sanitize_text_field($_POST['gpfw_cat_enable']), null, 'no');
            }
            // Disable Gift Product Cat
            if ($gpfw_options['gpfw_disable_gift_pack_images'] !== false) {
                update_option('gpfw_pro_cat', self::gpfw_sanitize_array($_POST['tax_input']['product_cat']));
            } else {
                add_option('gpfw_pro_cat', self::gpfw_sanitize_array($_POST['tax_input']['product_cat']), null, 'no');
            }

            // Disable Gift Pack Images
            if ($gpfw_options['gpfw_disable_gift_pack_images'] !== false) {
                update_option('gpfw_disable_gift_pack_images', sanitize_text_field($_POST['gpfw_disable_gift_pack_images']));
            } else {
                add_option('gpfw_disable_gift_pack_images', sanitize_text_field($_POST['gpfw_disable_gift_pack_images']), null, 'no');
            }

            // Enable / Disable Gift Pack Note
            if ($gpfw_options['gpfw_disable_gift_pack_note'] !== false) {
                update_option('gpfw_disable_gift_pack_note', sanitize_text_field($_POST['gpfw_disable_gift_pack_note']));
            } else {
                add_option('gpfw_disable_gift_pack_note', sanitize_text_field($_POST['gpfw_disable_gift_pack_note']), null, 'no');
            }
        }

        //Set Global Price
        if (function_exists('icl_object_id') && $this->current_language) {
            $option_name    = 'gpfw_global_price' . $this->current_language;
            $existing_value = get_option($option_name);
            if ( isset($existing_value) && $existing_value !== false)  {
                $updated_global_price = isset($_POST['gpfw_global_price']) ? $_POST['gpfw_global_price'] : '';
                update_option('gpfw_global_price' . $this->current_language, sanitize_text_field($updated_global_price));
            } else {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_global_price' . $lang_code;
                    if (isset($_POST['gpfw_global_price'])) {
                        $updated_global_price = sanitize_text_field( isset($_POST['gpfw_global_price']) ? $_POST['gpfw_global_price'] : '' );
                        add_option($option_name, $updated_global_price, null, 'no');
                    }
                }
            }
        } else {
            if ($gpfw_options['gpfw_global_price'] !== false) {
                update_option('gpfw_global_price', sanitize_text_field($_POST['gpfw_global_price']));
            } else {
                add_option('gpfw_global_price', sanitize_text_field($_POST['gpfw_global_price']), null, 'no');
            }
        }
        
        // Set Default Price
        if (function_exists('icl_object_id')) {
            // WPML is active
            if ($this->current_language) {
                $gpfw_gift_price = 'gpfw_gift_price' . $this->current_language;
                
                if (isset($_POST['gpfw_gift_price']) && !empty($_POST['gpfw_gift_price'])) {
                    $updated_gift_price = sanitize_text_field($_POST['gpfw_gift_price']);
                    update_option($gpfw_gift_price, $updated_gift_price);
                } else {
                    // If the field is empty, set a default value
                    $default_gift_price = '10';
                    update_option($gpfw_gift_price, $default_gift_price);
                }
            }
        } else {
            // WPML is not active, use fallback logic
            if (isset($_POST['gpfw_gift_price']) && !empty($_POST['gpfw_gift_price'])) {
                $updated_gift_price = sanitize_text_field($_POST['gpfw_gift_price']);
                update_option('gpfw_gift_price', $updated_gift_price);
            } else {
                // If the field is empty, set a default value
                $default_gift_price = '10';
                update_option('gpfw_gift_price', $default_gift_price);
            }
        }

        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_gift_pack_message_text' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_gift_pack_message_text' . $lang_code;
                    
                    if (isset($_POST['gpfw_gift_pack_message_text'])) {
                        $updated_gift_message_text = sanitize_text_field($_POST['gpfw_gift_pack_message_text']);
                        update_option($option_name, $updated_gift_message_text);
                    }
                }
            } else {
                $updated_gift_message_text = sanitize_text_field($_POST['gpfw_gift_pack_message_text']);
                update_option('gpfw_gift_pack_message_text' . $this->current_language, $updated_gift_message_text);
            }
        } else {
            if (isset($_POST['gpfw_gift_pack_message_text'])) {
                $updated_gift_message_text = sanitize_text_field($_POST['gpfw_gift_pack_message_text']);
                update_option('gpfw_gift_pack_message_text', $updated_gift_message_text);
            }
        }
        
        // Set Gift Pack Image Label Text
        
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_gift_pack_image_text' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_gift_pack_image_text' . $lang_code;
                    
                    if (isset($_POST['gpfw_gift_pack_image_text'])) {
                        $updated_gift_image_text = sanitize_text_field($_POST['gpfw_gift_pack_image_text']);
                        update_option($option_name, $updated_gift_image_text);
                    }
                }
            } else {
                $updated_gift_image_text = sanitize_text_field($_POST['gpfw_gift_pack_image_text']);
                update_option('gpfw_gift_pack_image_text' . $this->current_language, $updated_gift_image_text);
            }
        } else {
            if (isset($_POST['gpfw_gift_pack_image_text'])) {
                $updated_gift_image_text = sanitize_text_field($_POST['gpfw_gift_pack_image_text']);
                update_option('gpfw_gift_pack_image_text', $updated_gift_image_text);
            }
        }
        
        // Set Gift Wrap Text
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gift_wrap_text' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gift_wrap_text' . $lang_code;
                    
                    if (isset($_POST['gift_wrap_text'])) {
                        $updated_gift_wrap_text = sanitize_text_field($_POST['gift_wrap_text']);
                        update_option($option_name, $updated_gift_wrap_text);
                    }
                }
            } else {
                $updated_gift_wrap_text = sanitize_text_field($_POST['gift_wrap_text']);
                update_option('gift_wrap_text' . $this->current_language, $updated_gift_wrap_text);
            }
        } else {
            if (isset($_POST['gift_wrap_text'])) {
                $updated_gift_wrap_text = sanitize_text_field($_POST['gift_wrap_text']);
                update_option('gift_wrap_text', $updated_gift_wrap_text);
            }
        }
        
        // Set Gift Pack Note Placeholder Text in Product Details    
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_gift_pack_note_placeholder' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_gift_pack_note_placeholder' . $lang_code;
                    
                    if (isset($_POST['gpfw_gift_pack_note_placeholder'])) {
                        $updated_gift_note_placeholder = sanitize_text_field($_POST['gpfw_gift_pack_note_placeholder']);
                        update_option($option_name, $updated_gift_note_placeholder);
                    }
                }
            } else {
                $updated_gift_note_placeholder = sanitize_text_field($_POST['gpfw_gift_pack_note_placeholder']);
                update_option('gpfw_gift_pack_note_placeholder' . $this->current_language, $updated_gift_note_placeholder);
            }
        } else {
            if (isset($_POST['gpfw_gift_pack_note_placeholder'])) {
                $updated_gift_note_placeholder = sanitize_text_field($_POST['gpfw_gift_pack_note_placeholder']);
                update_option('gpfw_gift_pack_note_placeholder', $updated_gift_note_placeholder);
            }
        }
        
        // Add Gift Pack Image
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_gift_wrap_text_label' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_gift_wrap_text_label' . $lang_code;
                    
                    if (isset($_POST['gpfw_gift_wrap_text_label'])) {
                        $updated_gift_wrap_text_label = sanitize_text_field($_POST['gpfw_gift_wrap_text_label']);
                        update_option($option_name, $updated_gift_wrap_text_label);
                    }
                }
            } else {
                $updated_gift_wrap_text_label = sanitize_text_field($_POST['gpfw_gift_wrap_text_label']);
                update_option('gpfw_gift_wrap_text_label' . $this->current_language, $updated_gift_wrap_text_label);
            }
        } else {
            if (isset($_POST['gpfw_gift_wrap_text_label'])) {
                $updated_gift_wrap_text_label = sanitize_text_field($_POST['gpfw_gift_wrap_text_label']);
                update_option('gpfw_gift_wrap_text_label', $updated_gift_wrap_text_label);
            }
        }
        
        // Choose Gift Pack Image
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_choose_gift_pack_img' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_choose_gift_pack_img' . $lang_code;
                    
                    if (isset($_POST['gpfw_choose_gift_pack_img'])) {
                        $updated_gift_pack_img = sanitize_text_field($_POST['gpfw_choose_gift_pack_img']);
                        update_option($option_name, $updated_gift_pack_img);
                    }
                }
            } else {
                $updated_gift_pack_img = sanitize_text_field($_POST['gpfw_choose_gift_pack_img']);
                update_option('gpfw_choose_gift_pack_img' . $this->current_language, $updated_gift_pack_img);
            }
        } else {
            if (isset($_POST['gpfw_choose_gift_pack_img'])) {
                $updated_gift_pack_img = sanitize_text_field($_POST['gpfw_choose_gift_pack_img']);
                update_option('gpfw_choose_gift_pack_img', $updated_gift_pack_img);
            }
        }
        
        // Set Add Gift Pack Msg 
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_choose_gift_pack_msg' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_choose_gift_pack_msg' . $lang_code;
                    
                    if (isset($_POST['gpfw_choose_gift_pack_msg'])) {
                        $updated_choose_gift_pack_msg = sanitize_text_field($_POST['gpfw_choose_gift_pack_msg']);
                        update_option($option_name, $updated_choose_gift_pack_msg);
                    }
                }
            } else {
                $updated_choose_gift_pack_msg = sanitize_text_field($_POST['gpfw_choose_gift_pack_msg']);
                update_option('gpfw_choose_gift_pack_msg' . $this->current_language, $updated_choose_gift_pack_msg);
            }
        } else {
            if (isset($_POST['gpfw_choose_gift_pack_msg'])) {
                $updated_choose_gift_pack_msg = sanitize_text_field($_POST['gpfw_choose_gift_pack_msg']);
                update_option('gpfw_choose_gift_pack_msg', $updated_choose_gift_pack_msg);
            }
        }
        
        // Set Add Gift Pack Bg Image 
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_gift_pack_bg_img' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_gift_pack_bg_img' . $lang_code;
                    
                    if (isset($_POST['gpfw_gift_pack_bg_img'])) {
                        $updated_gift_pack_bg_img = sanitize_text_field($_POST['gpfw_gift_pack_bg_img']);
                        update_option($option_name, $updated_gift_pack_bg_img);
                    }
                }
            } else {
                $updated_gift_pack_bg_img = sanitize_text_field($_POST['gpfw_gift_pack_bg_img']);
                update_option('gpfw_gift_pack_bg_img' . $this->current_language, $updated_gift_pack_bg_img);
            }
        } else {
            if (isset($_POST['gpfw_gift_pack_bg_img'])) {
                $updated_gift_pack_bg_img = sanitize_text_field($_POST['gpfw_gift_pack_bg_img']);
                update_option('gpfw_gift_pack_bg_img', $updated_gift_pack_bg_img);
            }
        }
        
        // Set Add Gift Pack Bg Color 
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_gift_pack_bg_color' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_gift_pack_bg_color' . $lang_code;
                    
                    if (isset($_POST['gpfw_gift_pack_bg_color'])) {
                        $updated_gift_pack_bg_color = sanitize_text_field($_POST['gpfw_gift_pack_bg_color']);
                        update_option($option_name, $updated_gift_pack_bg_color);
                    }
                }
            } else {
                $updated_gift_pack_bg_color = sanitize_text_field($_POST['gpfw_gift_pack_bg_color']);
                update_option('gpfw_gift_pack_bg_color' . $this->current_language, $updated_gift_pack_bg_color);
            }
        } else {
            if (isset($_POST['gpfw_gift_pack_bg_color'])) {
                $updated_gift_pack_bg_color = sanitize_text_field($_POST['gpfw_gift_pack_bg_color']);
                update_option('gpfw_gift_pack_bg_color', $updated_gift_pack_bg_color);
            }
        }
        
        // Set Add Gift Title Color 
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_giftwrap_base_gift_title_color' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_giftwrap_base_gift_title_color' . $lang_code;
                    
                    if (isset($_POST['gpfw_giftwrap_base_gift_title_color'])) {
                        $updated_gift_title_color = sanitize_text_field($_POST['gpfw_giftwrap_base_gift_title_color']);
                        update_option($option_name, $updated_gift_title_color);
                    }
                }
            } else {
                $updated_gift_title_color = sanitize_text_field($_POST['gpfw_giftwrap_base_gift_title_color']);
                update_option('gpfw_giftwrap_base_gift_title_color' . $this->current_language, $updated_gift_title_color);
            }
        } else {
            if (isset($_POST['gpfw_giftwrap_base_gift_title_color'])) {
                $updated_gift_title_color = sanitize_text_field($_POST['gpfw_giftwrap_base_gift_title_color']);
                update_option('gpfw_giftwrap_base_gift_title_color', $updated_gift_title_color);
            }
        }
        
        // Set Add Gift Pack Bg Color 
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_add_gift_pack_label_color' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_add_gift_pack_label_color' . $lang_code;
                    
                    if (isset($_POST['gpfw_add_gift_pack_label_color'])) {
                        $updated_pack_label_color = sanitize_text_field($_POST['gpfw_add_gift_pack_label_color']);
                        update_option($option_name, $updated_pack_label_color);
                    }
                }
            } else {
                $updated_pack_label_color = sanitize_text_field($_POST['gpfw_add_gift_pack_label_color']);
                update_option('gpfw_add_gift_pack_label_color' . $this->current_language, $updated_pack_label_color);
            }
        } else {
            if (isset($_POST['gpfw_add_gift_pack_label_color'])) {
                $updated_pack_label_color = sanitize_text_field($_POST['gpfw_add_gift_pack_label_color']);
                update_option('gpfw_add_gift_pack_label_color', $updated_pack_label_color);
            }
        }
        
        // Set Add Gift Pack autochange price & checkbox color 
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_add_gift_pack_price_and_checkbox' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_add_gift_pack_price_and_checkbox' . $lang_code;
                    
                    if (isset($_POST['gpfw_add_gift_pack_price_and_checkbox'])) {
                        $updated_gift_pack_price_and_checkbox = sanitize_text_field($_POST['gpfw_add_gift_pack_price_and_checkbox']);
                        update_option($option_name, $updated_gift_pack_price_and_checkbox);
                    }
                }
            } else {
                $updated_gift_pack_price_and_checkbox = sanitize_text_field($_POST['gpfw_add_gift_pack_price_and_checkbox']);
                update_option('gpfw_add_gift_pack_price_and_checkbox' . $this->current_language, $updated_gift_pack_price_and_checkbox);
            }
        } else {
            if (isset($_POST['gpfw_add_gift_pack_price_and_checkbox'])) {
                $updated_gift_pack_price_and_checkbox = sanitize_text_field($_POST['gpfw_add_gift_pack_price_and_checkbox']);
                update_option('gpfw_add_gift_pack_price_and_checkbox', $updated_gift_pack_price_and_checkbox);
            }
        }
        
        // Click here to add gift pack/wrapper images
        if (function_exists('icl_object_id') && $this->current_language) {
            
            $option_name    = 'gpfw_gallery' . $this->current_language;
            $existing_value = get_option($option_name);
            
            if (!$existing_value) {
                foreach (icl_get_languages('skip_missing=0') as $language) {
                    $lang_code   = $language['language_code'];
                    $option_name = 'gpfw_gallery' . $lang_code;
                    
                    if (isset($_POST['gpfw_gallery'])) {
                        $updated_gift_gpfw_gallery = sanitize_text_field($_POST['gpfw_gallery']);
                        update_option($option_name, $updated_gift_gpfw_gallery);
                    }
                }
            } else {
                $updated_gift_gpfw_gallery = sanitize_text_field($_POST['gpfw_gallery']);
                update_option('gpfw_gallery' . $this->current_language, $updated_gift_gpfw_gallery);
            }
        } else {
            if (isset($_POST['gpfw_gallery'])) {
                $updated_gift_gpfw_gallery = sanitize_text_field($_POST['gpfw_gallery']);
                update_option('gpfw_gallery', $updated_gift_gpfw_gallery);
            }
        }
        
 
// Enable / Disable Gift pop-up image option
// $opt_gpfw_popup_option    = 'gpfw_popup_option' . $this->current_language;
// $opt_gpfw_disable_gift_pack_note_value = get_option($opt_gpfw_popup_option);

if (function_exists('icl_object_id') && $this->current_language) {
    $opt_popup    = 'gpfw_popup_option' . $this->current_language;
    $opt_popup_option = get_option($opt_popup);

    if (isset($opt_popup_option) && $opt_popup_option !== false) {
        $updated_gift_popup_option = isset($_POST['gpfw_popup_option']) ? sanitize_text_field($_POST['gpfw_popup_option']) : '';
        update_option($opt_popup, $updated_gift_popup_option);
    } else {
        foreach (icl_get_languages('skip_missing=0') as $language) {
            $lang_code   = $language['language_code'];
            $option_name = 'gpfw_popup_option' . $lang_code;

            if (isset($_POST['gpfw_popup_option'])) {
                $updated_gpfw_popup_option = sanitize_text_field($_POST['gpfw_popup_option']);
                add_option($option_name, $updated_gpfw_popup_option, null, 'no');
            }
        }
    }
} else {
    // Enable / Disable Gift pop-up image option
    $gpfw_options = get_option('gpfw_options');

    if ($gpfw_options['gpfw_popup_option'] !== false) {
        $updated_popup_option = isset($_POST['gpfw_popup_option']) ? sanitize_text_field($_POST['gpfw_popup_option']) : '';
        update_option('gpfw_popup_option', $updated_popup_option);
    } else {
        $updated_popup_option = isset($_POST['gpfw_popup_option']) ? sanitize_text_field($_POST['gpfw_popup_option']) : '';
        add_option('gpfw_popup_option', $updated_popup_option, null, 'no');
    }
}

        wp_redirect(admin_url('admin.php?page=gift-pack-for-woocommerce&update-status=true'));
    }
}


