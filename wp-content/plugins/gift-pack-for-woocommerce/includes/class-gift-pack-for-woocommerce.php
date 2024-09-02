<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Gift_Pack_For_Woocommerce
 * @subpackage Gift_Pack_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Gift_Pack_For_Woocommerce
 * @subpackage Gift_Pack_For_Woocommerce/includes
 * @author     It Path Solutions <shailm@itpathsolutions.com>
 */
class Gift_Pack_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Gift_Pack_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GIFT_PACK_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = GIFT_PACK_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'gift-pack-for-woocommerce';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Gift_Pack_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Gift_Pack_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Gift_Pack_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Gift_Pack_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gift-pack-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-gift-pack-for-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-gift-pack-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-gift-pack-for-woocommerce-public.php';
		$this->loader = new Gift_Pack_For_Woocommerce_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Gift_Pack_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Gift_Pack_For_Woocommerce_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Gift_Pack_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action('admin_notices', $plugin_admin, 'gpfw_check_for_woocommerce' );
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action('admin_menu', $plugin_admin, 'gpfw_plugin_admin_menu' );
		$this->loader->add_action('add_meta_boxes', $plugin_admin,'gpfw_metabox');
		$this->loader->add_action('admin_post_save_gpfw_update_settings',$plugin_admin,'gpfw_update_settings');
		$this->loader->add_filter('plugin_action_links_'.$this->plugin_name.'/'.$this->plugin_name.'.php',$plugin_admin,'gpfw_settings_link',10,1 );
		$this->loader->add_action( 'woocommerce_product_options_general_product_data', $plugin_admin, 'wc_cost_product_field' ); //woocommerce_product_options_pricing
		$this->loader->add_action( 'woocommerce_admin_process_product_object', $plugin_admin, 'gpfw_save_product_custom_meta_data');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Gift_Pack_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$load_gpfw_options = $this->gpfw_get_options();
		$this->loader->add_action( 'wp_head', $plugin_public, 'head_styles');
		$this->loader->add_action('woocommerce_single_product_summary', $plugin_public,'gpfw_addgift_pack_wrapper_price_option_to_single_product');
		$this->loader->add_action('woocommerce_before_add_to_cart_button', $plugin_public, 'gpfw_product_option_custom_field');
		$this->loader->add_filter('woocommerce_add_cart_item_data', $plugin_public, 'gpfw_add_custom_product_data', 10, 3);
		$this->loader->add_action('woocommerce_before_calculate_totals', $plugin_public, 'gpfw_extra_price_add_custom_price');
		$this->loader->add_filter('woocommerce_get_item_data', $plugin_public, 'gpfw_display_custom_item_data', 10, 2);
		$this->loader->add_action('woocommerce_checkout_create_order_line_item', $plugin_public, 'gpfw_save_order_item_product_waranty', 10, 4 );
		$this->loader->add_action('wp_ajax_nopriv_gpfw_check_gift_wrap', $plugin_public, 'gpfw_check_gift_wrap_function');
		$this->loader->add_action('wp_ajax_gpfw_check_gift_wrap', $plugin_public, 'gpfw_check_gift_wrap_function');
		$this->loader->add_filter('woocommerce_post_class',$plugin_public,'gpfw_filter_woocommerce_post_class', 10, 2 );
	}

	/* Get Gift Pack for Woocommerce Custom Options */
	public static function gpfw_get_options(){
		global $post;
		
		$current_language = apply_filters('wpml_current_language', NULL);
		
		$gift_price_option_name = 'gpfw_gift_price' . $current_language;
		$options['gpfw_gift_price'] = get_option( $gift_price_option_name);

		$gpfw_disable_gift_pack_images = 'gpfw_disable_gift_pack_images' . $current_language;
		$options['gpfw_disable_gift_pack_images'] = get_option( $gpfw_disable_gift_pack_images );

		$gpfw_global_price = 'gpfw_global_price' . $current_language;
		$options['gpfw_global_price'] = get_option( $gpfw_global_price );
	
		$gpfw_disable_gift_pack_note = 'gpfw_disable_gift_pack_note' . $current_language;
		$options['gpfw_disable_gift_pack_note'] = get_option( $gpfw_disable_gift_pack_note );

		$gpfw_gallery = 'gpfw_gallery' . $current_language;
		$options['gpfw_gallery'] = get_option( $gpfw_gallery );

		$gpfw_gift_pack_message_text = 'gpfw_gift_pack_message_text' . $current_language;
		$options['gpfw_gift_pack_message_text'] = get_option($gpfw_gift_pack_message_text);

		$gift_wrap_global_text = 'gift_wrap_text' . $current_language;
		$options['gift_wrap_text'] = get_option( $gift_wrap_global_text );

		$gift_wrap_global_image_text = 'gpfw_gift_pack_image_text' . $current_language;
		$options['gpfw_gift_pack_image_text'] = get_option( $gift_wrap_global_image_text );

		$gift_wrap_global_note_placeholder = 'gpfw_gift_pack_note_placeholder' . $current_language;
		$options['gpfw_gift_pack_note_placeholder'] = get_option( $gift_wrap_global_note_placeholder );

		$gift_wrap_global_text_label = 'gpfw_gift_wrap_text_label' . $current_language;
		$options['gpfw_gift_wrap_text_label'] = get_option( $gift_wrap_global_text_label );

		$gift_wrap_global_wrap_text_label = 'gpfw_gift_wrap_text_label' . $current_language;
		$options['gpfw_gift_wrap_text_label'] = get_option( $gift_wrap_global_wrap_text_label );

		$gift_wrap_global_gift_pack_img = 'gpfw_choose_gift_pack_img' . $current_language;
		$options['gpfw_choose_gift_pack_img'] = get_option( $gift_wrap_global_gift_pack_img );
		
		$gift_wrap_global_gift_pack_msg = 'gpfw_choose_gift_pack_msg' . $current_language;
		$options['gpfw_choose_gift_pack_msg'] = get_option( $gift_wrap_global_gift_pack_msg );

		$gift_wrap_global_gift_bg_img = 'gpfw_gift_pack_bg_img' . $current_language;
		$options['gpfw_gift_pack_bg_img'] = get_option( $gift_wrap_global_gift_bg_img );

		$gift_wrap_global_gift_bg_color = 'gpfw_gift_pack_bg_color' . $current_language;
		$options['gpfw_gift_pack_bg_color'] = get_option( $gift_wrap_global_gift_bg_color );

		$gift_wrap_global_gift_title_color = 'gpfw_giftwrap_base_gift_title_color' . $current_language;
		$options['gpfw_giftwrap_base_gift_title_color'] = get_option( $gift_wrap_global_gift_title_color );

		$gift_wrap_global_gift_label_color = 'gpfw_add_gift_pack_label_color' . $current_language;
		$options['gpfw_add_gift_pack_label_color'] = get_option( $gift_wrap_global_gift_label_color );

		$gift_wrap_global_gift_price_and_checkbox = 'gpfw_add_gift_pack_price_and_checkbox' . $current_language;
		$options['gpfw_add_gift_pack_price_and_checkbox'] = get_option( $gift_wrap_global_gift_price_and_checkbox );
		
		$gpfw_cat_enable = 'gpfw_cat_enable' . $current_language;
		$options['gpfw_cat_enable'] = get_option($gpfw_cat_enable);

		
		$gpfw_pro_cat = 'gpfw_pro_cat' . $current_language;
		$options['gpfw_pro_cat'] = get_option($gpfw_pro_cat);
		
		$gpfw_popup_option = 'gpfw_popup_option' . $current_language;
		$options['gpfw_popup_option'] = get_option($gpfw_popup_option);

		return $options;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Gift_Pack_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}