<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://cedcommerce.com
 * @since      1.0.0
 *
 * @package    Cedcommerce_Shopify_Woocommerce_Connector
 * @subpackage Cedcommerce_Shopify_Woocommerce_Connector/includes
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
 * @package    Cedcommerce_Shopify_Woocommerce_Connector
 * @subpackage Cedcommerce_Shopify_Woocommerce_Connector/includes
 */
class Cedcommerce_Shopify_Woocommerce_Connector {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      Cedcommerce_Shopify_Woocommerce_Connector_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
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
		if ( defined( 'PLUGIN_NAME__SWC_VERSION' ) ) {
			$this->version = PLUGIN_NAME__SWC_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'cedcommerce-shopify-woocommerce-connector';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->load_constants();
	}

	public function load_constants() {
		$this->define( 'CED_SWC_PATH', plugin_dir_path( dirname( __FILE__ ) ) );
		$this->define( 'CED_SWC_URL', plugin_dir_url( dirname( __FILE__ ) ) );
		$this->define( 'CED_SWC_PREFIX', 'ced_swc_' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @since  1.0.0
	 * @param  string      $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cedcommerce_Shopify_Woocommerce_Connector_Loader. Orchestrates the hooks of the plugin.
	 * - Cedcommerce_Shopify_Woocommerce_Connector_I18n. Defines internationalization functionality.
	 * - Cedcommerce_Shopify_Woocommerce_Connector_Admin. Defines all hooks for the admin area.
	 * - Cedcommerce_Shopify_Woocommerce_Connector_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cedcommerce-shopify-woocommerce-connector-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cedcommerce-shopify-woocommerce-connector-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cedcommerce-shopify-woocommerce-connector-admin.php';

		$this->loader = new Cedcommerce_Shopify_Woocommerce_Connector_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cedcommerce_Shopify_Woocommerce_Connector_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function set_locale() {

		$plugin_i18n = new Cedcommerce_Shopify_Woocommerce_Connector_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Cedcommerce_Shopify_Woocommerce_Connector_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// This for shopify sub menu under cedcommerce Admin Menu 12 april 2021
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'ced_shopify_add_menus', 22 );
		$this->loader->add_filter( 'ced_add_marketplace_menus_array', $plugin_admin, 'ced_shopify_add_marketplace_menus_to_array', 13 );

		$this->loader->add_action( 'ced_swc_get_collection', $plugin_admin, 'getCollectionProduct' );
		$this->loader->add_action( 'ced_swc_autoImportProducts', $plugin_admin, 'ced_swc_autoImportProducts' );
		$this->loader->add_action( 'ced_swc_autoImportCustomer', $plugin_admin, 'ced_swc_autoImportCustomer' );
		$this->loader->add_action( 'ced_swc_autoImportCoupons', $plugin_admin, 'ced_swc_autoImportCoupons' );
		$this->loader->add_action( 'ced_swc_autoImportPosts', $plugin_admin, 'ced_swc_autoImportPosts' );
		$this->loader->add_action( 'getCollectionfromStore', $plugin_admin, 'getCollectionfromStore' );
		$this->loader->add_action( 'ced_swc_autoImportOrders', $plugin_admin, 'ced_swc_autoImportOrders' );
		$this->loader->add_action( 'ced_swc_syncProductsFromShopify', $plugin_admin, 'ced_swc_syncProductsFromShopify' );
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'ced_swc_cronSchedules', 1, 1 );
		$this->loader->add_filter( 'manage_edit-shop_order_columns', $plugin_admin, 'woo_order_weight_column' );
		$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin, 'woo_custom_order_weight_column', 2 );
		$this->loader->add_action( 'wp_ajax_get_list_of_all_location', $plugin_admin, 'get_list_of_all_location' );
		$this->loader->add_action( 'wp_ajax_saving_selected_location_option_on_ajax', $plugin_admin, 'saving_selected_location_option_on_ajax' );
		$this->loader->add_action( 'woocommerce_order_status_processing', $plugin_admin, 'update_product_inventory_on_order_status_processing', 10, 2 );
		$this->loader->add_action( 'wp_ajax_ced_shopify_submit_feedback', $plugin_admin, 'ced_shopify_submit_feedback' );
		$this->loader->add_action( 'ced_swc_GetAllCollectionNameOfAllProducts', $plugin_admin, 'ced_swc_GetAllCollectionNameOfAllProducts' );
		$order_status = array(
			'new_order',
			'customer_processing_order',
			'cancelled_order',
			'customer_completed_order',
			'customer_on_hold_order',
			'customer_refunded_order',
			'customer_failed_order',
		);

		foreach ( $order_status as $key => $status ) {
			$this->loader->add_filter( 'woocommerce_email_enabled_' . esc_attr( $status ), $plugin_admin, 'ced_shopify_email_restriction_on_order_import', 10, 2 );
		}

		$this->loader->add_action( 'admin_init', $plugin_admin, 'ced_swc_schedulers_check'  );
		$this->loader->add_action( 'wp_ajax_ced_swc_ImportProductsCreatedOnWebhook', $plugin_admin, 'ced_swc_ImportProductsCreatedOnWebhook' );
		
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
	 * @return    Cedcommerce_Shopify_Woocommerce_Connector_Loader    Orchestrates the hooks of the plugin.
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
