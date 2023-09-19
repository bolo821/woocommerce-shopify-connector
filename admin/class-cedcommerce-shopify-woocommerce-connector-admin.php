<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cedcommerce.com
 * @since      1.0.0
 *
 * @package    Cedcommerce_Shopify_Woocommerce_Connector
 * @subpackage Cedcommerce_Shopify_Woocommerce_Connector/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cedcommerce_Shopify_Woocommerce_Connector
 * @subpackage Cedcommerce_Shopify_Woocommerce_Connector/admin
 */
class Cedcommerce_Shopify_Woocommerce_Connector_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Webhook
		add_action( 'wp_ajax_CedSWC_CreateOrderOnWebhhok', array( $this, 'CedSWC_CreateOrderOnWebhhok' ) );
		add_action( 'wp_ajax_nopriv_CedSWC_CreateOrderOnWebhhok', array( $this, 'CedSWC_CreateOrderOnWebhhok' ) );

		// add_filter('woocommerce_order_number' , array( $this , 'ced_swc_modify_order_number' ) , 10 , 2);
	}

	// function ced_swc_modify_order_number($order_id, $order ) {
	// 	$shopify_order_id = get_post_meta( $order_id, 'ced_shopify_order_check', true );
	// 	if ( ! empty ( $shopify_order_id ) ) {
	// 		return $shopify_order_id;
	// 	}
	// }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cedcommerce_Shopify_Woocommerce_Connector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cedcommerce_Shopify_Woocommerce_Connector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		*/

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cedcommerce-shopify-woocommerce-connector-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'handler_select_2_css', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'handler_fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cedcommerce_Shopify_Woocommerce_Connector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cedcommerce_Shopify_Woocommerce_Connector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		*/

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cedcommerce-shopify-woocommerce-connector-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'handler_select_2_js', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array(), $this->version, 'all' );

		$ajax_nonce     = wp_create_nonce( 'ced-shopify-ajax-seurity-string' );
		$localize_array = array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => $ajax_nonce,
		);
		wp_localize_script( $this->plugin_name, 'Ced_Shopify_connector_action_handler', $localize_array );
	}

	/**
	 * Shopify_connector_for_woocommerce_admin ced_shopify_add_menus.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopify_add_menus() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['cedcommerce-integrations'] ) ) {
			add_menu_page( __( 'CedCommerce', 'shopify-connector-for-woocommerce' ), __( 'CedCommerce', 'shopify-connector-for-woocommerce' ), 'manage_woocommerce', 'cedcommerce-integrations', array( $this, 'ced_marketplace_listing_page' ), plugins_url( 'shopify-connector-for-woocommerce/admin/images/cedcommerce_logo.png' ), 12 );
			$menus = apply_filters( 'ced_add_marketplace_menus_array', array() );
			if ( is_array( $menus ) && ! empty( $menus ) ) {
				foreach ( $menus as $key => $value ) {
					add_submenu_page( 'cedcommerce-integrations', $value['name'], $value['name'], 'manage_woocommerce', $value['menu_link'], array( $value['instance'], $value['function'] ) );
				}
			}
		}
	}

	/**
	 * Shopify_connector_for_woocommerce_admin ced_marketplace_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function ced_marketplace_listing_page() {
		$active_marketplaces = apply_filters( 'ced_add_marketplace_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require CED_SWC_PATH . 'admin/partials/marketplaces.php';
		}
	}

	/**
	 * Shopify_connector_for_woocommerce_admin ced_shopify_add_marketplace_menus_to_array.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function ced_shopify_add_marketplace_menus_to_array( $menus = array() ) {
		$menus[] = array(
			'name'            => 'Shopify',
			'slug'            => 'shopify-connector-for-woocommerce',
			'menu_link'       => 'ced_shopify',
			'instance'        => $this,
			'function'        => 'ced_shopify_accounts_page',
			'card_image_link' => CED_SWC_URL . 'admin/images/shopify_logo.jpg',
		);
		return $menus;
	}

	/**
	 * Shopify_connector_for_woocommerce_admin ced_shopify_accounts_page.
	 *
	 * @since 1.0.0
	 */
	public function ced_shopify_accounts_page() {
		if ( file_exists( CED_SWC_PATH . 'admin/partials/ced-swc-configuration.php' ) ) {
			require_once CED_SWC_PATH . 'admin/partials/ced-swc-configuration.php';
		}
	}

	public function ced_swc_autoImportProducts() {

		$fileName                 = require_once CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();

		$collection          = get_option( 'ced_swc_config_details' );
		$selected_collection = $collection['ced_swc_shopifycollectionToimport'];
		$since_id            = get_option( 'ced_swc_productPagesSince_id', '' );

		if ( '' == $since_id ) {
			$since_id = 0;
		}

		$productLimit = 10;

		if ( ! empty( $selected_collection ) ) {

			$products            = get_option( 'ced_swc_selected_collection_products' );
			$product_array_chunk = get_option( 'ced_Shopify_Product_array', array() );

			if ( empty( $product_array_chunk ) ) {
				$product_array_chunk = array_chunk( $products, 10, true );
			}

			foreach ( $product_array_chunk[0] as $selected_id => $collection_ids ) {
				$productData = $ced_swc_productHelperObj->getProductData( $selected_id );
				$this->ced_swc_manage_product_data_from_shopify( $productData['product'], $collection_ids );
			}

			unset( $product_array_chunk[0] );
			$product_array_chunk = array_values( $product_array_chunk );
			update_option( 'ced_Shopify_Product_array', $product_array_chunk );
		}

	}

	public function ced_swc_autoImportCustomer() {

		$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		require_once $fileName;
		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();

		$since_id = get_option( 'ced_swc_CustomerSince_idImported', '' );

		if ( '' == $since_id ) {
			$since_id = 0;
		}

		$customersLimit = 50;
		$checked_or_not = get_option( 'ced_swc_import_customer', true );
		$customers      = $ced_swc_productHelperObj->getCustomerFromStore( $since_id, $customersLimit );

		if ( empty( $customers['customers'] ) ) {
			update_option( 'ced_swc_CustomerSince_idImported', '' );

		}

		if ( ! empty( $customers ) ) {
			if ( ! empty( $checked_or_not ) && 'yes' == $checked_or_not ) {
				if ( ! empty( $customers['customers'] ) ) {
					foreach ( $customers['customers'] as $key => $customersData ) {
						$this->ced_swc_manage_customers_data_from_shopify( $customersData );
						$since_id = $customersData['id'];
						update_option( 'ced_swc_CustomerSince_idImported', $since_id );
					}
				} else {
					update_option( 'ced_swc_CustomerSince_idImported', '' );
				}
			}
		}
	}

	public function ced_swc_manage_customers_data_from_shopify( $customersData = array() ) {

		if ( is_array( $customersData ) && ! empty( $customersData ) ) {

			if ( get_user_by( 'email', $customersData['email'] ) ) {
				return;
			}

			$userdata = array(
				'user_pass'           => mt_rand( 1000000000, 9999999999 ),
				'user_login'          => $customersData['email'],
				'user_email'          => $customersData['email'],
				'first_name'          => $customersData['first_name'],
				'last_name'           => $customersData['last_name'],
				'description'         => '',
				'rich_editing'        => 'true',
				'syntax_highlighting' => 'true',
			);
			$user_id  = wp_insert_user( $userdata );

			if ( 1 != $user_id ) {
				wp_update_user(
					array(
						'ID'   => $user_id,
						'role' => 'customer',
					)
				);
			}

			if ( ! empty( $user_id ) ) {
				$update_user_meta = array(
					'billing_first_name'  => $customersData['default_address']['first_name'],
					'billing_last_name'   => $customersData['default_address']['last_name'],
					'billing_company'     => $customersData['default_address']['company'],
					'billing_email'       => $customersData['email'],
					'billing_phone'       => $customersData['default_address']['phone'],
					'billing_address_1'   => $customersData['default_address']['address1'],
					'billing_address_2'   => $customersData['default_address']['address2'],
					'billing_country'     => $customersData['default_address']['country'],
					'billing_state'       => $customersData['default_address']['province'],
					'billing_city'        => $customersData['default_address']['city'],
					'billing_postcode'    => $customersData['default_address']['zip'],

					'shipping_first_name' => $customersData['default_address']['first_name'],
					'shipping_last_name'  => $customersData['default_address']['last_name'],
					'shipping_address_1'  => $customersData['default_address']['address1'],
					'shipping_address_2'  => $customersData['default_address']['address2'],
					'shipping_country'    => $customersData['default_address']['country'],
					'shipping_state'      => $customersData['default_address']['province'],
					'shipping_city'       => $customersData['default_address']['city'],
					'shipping_postcode'   => $customersData['default_address']['zip'],
				);

				foreach ( $update_user_meta as $keys => $values ) {
					update_user_meta( $user_id, $keys, $values );
				}
			} else {
				echo 'User Id does not exist';
			}
		}
	}

	public function ced_swc_autoImportCoupons() {

		$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		require_once $fileName;
		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();

		$Since_id = get_option( 'ced_swc_CouponsSince_idImported', '' );

		if ( '' == $Since_id ) {
			$Since_id = 0;
		}

		$couponsLimit   = 50;
		$checked_or_not = get_option( 'ced_swc_import_coupons', true );

		$coupons = $ced_swc_productHelperObj->getCouponsFromStore( $Since_id, $couponsLimit );

		if ( empty( $coupons['price_rules'] ) ) {
			update_option( 'ced_swc_CouponsSince_idImported', '' );
		}

		if ( ! empty( $coupons ) ) {
			if ( ! empty( $checked_or_not ) && 'yes' == $checked_or_not ) {
				if ( ! empty( $coupons['price_rules'] ) ) {
					foreach ( $coupons['price_rules'] as $key => $CouponsData ) {
						$this->ced_swc_manage_coupons_data_from_shopify( $CouponsData );
						$Since_id = $CouponsData['id'];
						update_option( 'ced_swc_CouponsSince_idImported', $Since_id );
					}
				} else {
					update_option( 'ced_swc_CouponsSince_idImported', '' );
				}
			}
		}
	}

	public function ced_swc_manage_coupons_data_from_shopify( $CouponsData = array() ) {
		if ( is_array( $CouponsData ) && ! empty( $CouponsData ) ) {

			if ( ! function_exists( 'post_exists' ) ) {
				require_once ABSPATH . 'wp-admin/includes/post.php';

				if ( post_exists( $CouponsData['title'], '', '', '' ) ) {
					return;
				}
			} else {

				if ( post_exists( $CouponsData['title'], '', '', '' ) ) {
					return;
				}
			}

			$coupon_code   = $CouponsData['title'];
			$amount        = trim( $CouponsData['value'], '-' );
			$discount_type = $CouponsData['value_type'];

			$coupon    = array(
				'post_title'   => $coupon_code,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_author'  => 1,
				'post_type'    => 'shop_coupon',
			);
			$coupon_id = wp_insert_post( $coupon );

			if ( ! empty( $coupon_id ) ) {
				$add_coupons_meta = array(
					'discount_type'        => $discount_type,
					'coupon_amount'        => $amount,
					'free_shipping'        => 'no',
					'expiry_date'          => $CouponsData['ends_at'],
					'individual_use'       => 'no',
					'usage_limit'          => $CouponsData['usage_limit'],
					'usage_limit_per_user' => $CouponsData['once_per_customer'],
					'apply_before_tax'     => 'yes',
					'is_created'           => 'yes',
				);
			}

			foreach ( $add_coupons_meta as $keys => $values ) {
				update_post_meta( $coupon_id, $keys, $values );
			}
		}
	}

	// Blog
	public function ced_swc_autoImportPosts() {

		$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		require_once $fileName;
		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();

		$since_id = get_option( 'ced_swc_PostsSince_idImported', '' );

		if ( '' == $since_id ) {
			$since_id = 0;
		}

		$postsLimit     = 50;
		$checked_or_not = get_option( 'ced_swc_import_posts', true );

		$posts = $ced_swc_productHelperObj->getPostsFromStore( $since_id, $postsLimit );

		if ( empty( $posts['blogs'] ) ) {
			update_option( 'ced_swc_PostsSince_idImported', '' );
		}

		if ( ! empty( $posts ) ) {
			if ( ! empty( $checked_or_not ) && 'yes' == $checked_or_not ) {
				if ( ! empty( $posts['blogs'] ) ) {
					foreach ( $posts['blogs'] as $key => $PostsData ) {
						$this->ced_swc_manage_blogs_data_from_shopify( $PostsData );
						$since_id = $PostsData['id'];
						update_option( 'ced_swc_PostsSince_idImported', $since_id );
					}
				} else {
					update_option( 'ced_swc_PostsSince_idImported', '' );
				}
			}
		}
	}

	// Blog -> Blog_id
	public function ced_swc_manage_blogs_data_from_shopify( $PostsData = array() ) {
		if ( is_array( $PostsData ) && ! empty( $PostsData ) ) {

			$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
			require_once $fileName;
			$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();

			$since_id = get_option( 'ced_swc_ArticleSince_idImported', '' );

			if ( '' == $since_id ) {
				$since_id = 0;
			}

			$articlesLimit            = 50;
			$blog_id_to_get_articles  = $PostsData['id'];
			$title_to_create_category = $PostsData['title'];
			$article                  = $ced_swc_productHelperObj->getArticleFromStoreByPostsId( $blog_id_to_get_articles, $since_id, $articlesLimit );

			if ( empty( $article['articles'] ) ) {
				update_option( 'ced_swc_ArticleSince_idImported', '' );
			}

			if ( ! empty( $article ) ) {
				if ( ! empty( $article['articles'] ) ) {
					foreach ( $article['articles'] as $key => $ArticlesData ) {
						$this->ced_swc_manage_articles_data_from_shopify( $title_to_create_category, $blog_id_to_get_articles, $ArticlesData );
						$since_id = $ArticlesData['id'];
						update_option( 'ced_swc_ArticleSince_idImported', $since_id );
					}
				} else {
					update_option( 'ced_swc_ArticleSince_idImported', '' );
				}
			}
		}
	}

	// Blogs -> Blogs_id -> Article
	public function ced_swc_manage_articles_data_from_shopify( $title_to_create_category, $blog_id_to_get_articles, $ArticlesData = array() ) {
		if ( is_array( $ArticlesData ) && ! empty( $ArticlesData ) ) {

			if ( ! function_exists( 'post_exists' ) ) {

				require_once ABSPATH . 'wp-admin/includes/post.php';
				if ( post_exists( $ArticlesData['title'], '', '', '' ) ) {
					return;
				}
			} else {

				if ( post_exists( $ArticlesData['title'], '', '', '' ) ) {
					return;
				}
			}

			$my_posts = array(
				'post_author'   => $ArticlesData['author'],
				'post_date'     => $ArticlesData['created_at'],
				'post_content'  => wp_strip_all_tags( $ArticlesData['body_html'] ),
				'post_title'    => $ArticlesData['title'],
				'post_status'   => 'draft',
				'post_name'     => $ArticlesData['handle'],
				'post_category' => array( $title_to_create_category ),
			);

			$post_id = wp_insert_post( $my_posts );
		}
	}

	public function ced_swc_autoImportOrders() {

		$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		require_once $fileName;
		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();

		$since_id = get_option( 'ced_swc_OrdersSince_idImported', '' );
		if ( '' == $since_id ) {
			// $since_id = 0;
			//for custom solution
			$since_id = 4621286146101;
		}

		$ordersLimit    = 50;
		$checked_or_not = get_option( 'ced_swc_import_orders', true );
		$orders = $ced_swc_productHelperObj->getOrdersFromStore( $since_id, $ordersLimit );

		if ( empty( $orders['orders'] ) ) {
			update_option( 'ced_swc_OrdersSince_idImported', '' );
		}

		if ( ! empty( $orders ) ) {
			if ( ! empty( $checked_or_not ) && 'yes' == $checked_or_not ) {
				if ( ! empty( $orders['orders'] ) ) {
					foreach ( $orders['orders'] as $key => $OrdersData ) {

						$this->ced_swc_manage_orders_data_from_shopify( $OrdersData );
						$since_id = $OrdersData['id'];
						update_option( 'ced_swc_OrdersSince_idImported', $since_id );
					}
				} else {
					update_option( 'ced_swc_OrdersSince_idImported', '' );
				}
			}
		}
	}

	public function ced_swc_manage_orders_data_from_shopify( $OrdersData = array() ) {
		if ( is_array( $OrdersData ) && ! empty( $OrdersData ) ) {

			global $wpdb;
			if ( $OrdersData['id'] ) {
				$order_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s LIMIT 1", 'ced_shopify_order_check', $OrdersData['id'] ) );
				if ( $order_id ) {
					return;
				}
			}

			$Total_shipping_charge = $OrdersData['total_shipping_price_set']['shop_money']['amount'];
			$Total_tax_charge      = $OrdersData['total_tax'];
			$OrderStatus           = $OrdersData['financial_status'];
			$orderWeight           = $OrdersData['total_weight'];
			$order_number          = $OrdersData['order_number'];

			if ( isset( $Total_tax_charge ) && $Total_tax_charge > 0 ) {
				$Total_tax_charge = $Total_tax_charge;
			}

			$address = array(
				'first_name' => $OrdersData['customer']['default_address']['first_name'],
				'last_name'  => $OrdersData['customer']['default_address']['last_name'],
				'company'    => $OrdersData['customer']['default_address']['company'],
				'email'      => $OrdersData['customer']['email'],
				'phone'      => $OrdersData['customer']['default_address']['phone'],
				'address_1'  => $OrdersData['customer']['default_address']['address1'],
				'address_2'  => $OrdersData['customer']['default_address']['address2'],
				'city'       => $OrdersData['customer']['default_address']['city'],
				'state'      => $OrdersData['customer']['default_address']['province'],
				'postcode'   => $OrdersData['customer']['default_address']['zip'],
				'country'    => $OrdersData['customer']['default_address']['country'],
			);

			// Now we create the order
			$order    = wc_create_order();
			$order_id = $order->get_id();

			// Set addresses
			$order->set_address( $address, 'billing' );
			$order->set_address( $address, 'shipping' );

			// Line items
			foreach ( $OrdersData['line_items'] as $line_item ) {

				set_time_limit( 0 );
				wp_raise_memory_limit();

				$product = get_posts(
					array(
						'numberposts'  => -1,
						'post_type'    => array( 'product', 'product_variation' ),
						'meta_key'     => 'ced_swc_shopifyItemId',
						'meta_value'   => $line_item['product_id'],
						'meta_compare' => '=',
					)
				);

				$product     = wp_list_pluck( $product, 'ID' );
				$product_obj = wc_get_product( $product[0] );

				if ( is_object( $product_obj ) ) {
					$item_id = $order->add_product( $product_obj, $line_item['quantity'] );
				} else {
					$item_id = woocommerce_add_order_item(
						$order_id,
						array(
							'order_item_name' => $line_item['title'],
							'order_item_type' => 'line_item',
						)
					);

					if ( $item_id ) {
						$total_price = $line_item['quantity'] * $line_item['price'];
						woocommerce_add_order_item_meta( $item_id, '_qty', $line_item['quantity'] );
						woocommerce_add_order_item_meta( $item_id, '_line_total', $total_price );
						woocommerce_add_order_item_meta( $item_id, 'sku', $line_item['sku'] );
						woocommerce_add_order_item_meta( $item_id, 'weight', $line_item['grams'] . ' ( In Grams )' );
					}
				}
			}

			// Shipping Charges
			$Shipping_charges = new WC_Order_Item_Shipping();
			$Shipping_charges->set_method_title( 'Shopify Order' );
			$Shipping_charges->set_total( $Total_shipping_charge );

			// Add item to order and save Shipping Charges.
			$order->add_item( $Shipping_charges );

			// Set the array for tax calculations
			$calculate_tax_for = array(
				'country'  => $OrdersData['shipping_address']['country'],
				'postcode' => $OrdersData['shipping_address']['zip'],
				'city'     => $OrdersData['shipping_address']['city'],
			);

			// Get a new instance of the WC_Order_Item_Fee Object
			$item_fee = new WC_Order_Item_Fee();
			$item_fee->set_name( 'Tax Amount' );        // Generic fee name
			$item_fee->set_amount( $Total_tax_charge ); // Fee amount

			$item_fee->set_total( $Total_tax_charge ); // Fee amount
			// Calculating Fee taxes
			$item_fee->calculate_taxes( $calculate_tax_for );
			// Add Fee item to the order
			$order->add_item( $item_fee );

			// Calculate totals
			$order->calculate_totals();

			update_post_meta( $order->get_id(), 'ced_check_isit_shopify_order', 'shopify' );

			if ( 'paid' == $OrderStatus ) {
				$order->update_status( 'processing' );
			} else {
				$order->update_status( strtolower( $OrderStatus ) );
			}

			update_post_meta( $order->get_id(), 'ced_shopify_order_check', $OrdersData['id'] );
			update_post_meta( $order->get_id(), 'ced_shopify_OrderData', $OrdersData );
			update_post_meta( $order->get_id(), 'ced_shopify_Shopify_OrderNumber', $order_number );
			
			update_post_meta( $order_id, 'ced_cart_weight', $orderWeight );

			// Save
			$order->save();
		}
	}


	public function ced_shopify_email_restriction_on_order_import( $enable = '', $order = array() ) {

		if ( ! is_object( $order ) ) {
			return $enable;
		}

		$order_id   = $order->get_id();
		$order_from = get_post_meta( $order_id, 'ced_check_isit_shopify_order', true );

		if ( 'shopify' == strtolower( $order_from ) ) {
			$enable = false;
		}

		return $enable;

	}


	// Add Column "Weight" In Woo order page
	public function woo_order_weight_column( $columns ) {
		$columns['total_weight'] = __( 'Weight', 'woocommerce' );
		return $columns;
	}

	// Render Weight Data
	public function woo_custom_order_weight_column( $column ) {

		global $woocommerce, $post;

		if ( 'total_weight' == $column ) {

			$order    = new WC_Order( $post->ID );
			$order_id = $order->get_order_number();
			$weight   = get_post_meta( $order_id, 'ced_cart_weight', true );

			if ( $weight > 0 ) {
				print esc_attr( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) );
			} else {
				print 'N/A';
			}
		}
	}


	public function getCollectionProduct() {

		set_time_limit( 0 );
		wp_raise_memory_limit();

		$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		require_once $fileName;

		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();
		$collection               = get_option( 'ced_swc_config_details' );
		$selected_collection      = $collection['ced_swc_shopifycollectionToimport'];

		$productForSelectedCollection = array();
		$products                     = array();

		foreach ( $selected_collection as $value ) {
			if ( '' != $value ) {
				$pageNumber   = 0;
				$productLimit = 250;
				$productno    = $ced_swc_productHelperObj->get_count_of_collectionproducts_on_shopify( $value );

				if ( isset( $productno['errors'] ) || empty( $productno ) ) {
					continue;
				}

				$count = count( $productno['products'] );
				$loop  = (int) ( $count / $productLimit );
				$loop  = 2;

				while ( $loop >= 0 ) {

					// get Collection Products From Store
					$productsCollection = $ced_swc_productHelperObj->getAllCollectionProductsFromStore( $value, $pageNumber, $productLimit );

					$loop = --$loop;
					foreach ( $productsCollection['products'] as $productid ) {
						// array_push( $productForSelectedCollection[$productid['id']], $productid['id'] );
						$productForSelectedCollection[ $productid['id'] ][] = $value;
						$pageNumber = $productid['id'];
					}
				}
			}
		}

		update_option( 'ced_swc_selected_collection_products', $productForSelectedCollection );
	}

	public function ced_swc_GetAllCollectionNameOfAllProducts() {

		set_time_limit( 0 );
		wp_raise_memory_limit();

		$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		require_once $fileName;
		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();

		$shopify_collection_name      = get_option( 'shopify_collection_name' );
		$productForSelectedCollection = array();
		$_product_collection_id       = get_option( 'ced_swc_mapping_collection_name_correspondsTo_productID', array() );
		$collect_chunk                = get_option( 'ced_collect_chunk_array', array() );

		if ( empty( $_product_collection_id ) ) {
			$_product_collection_id = array();
		}
		if ( empty( $collect_chunk ) ) {
			$collect_chunk = array_chunk( $shopify_collection_name, 1, true );
		}

		foreach ( $collect_chunk[0] as $collection_id => $collection_name ) {
			if ( '' != $collection_id ) {

				$pageNumber   = 0;
				$productLimit = 250;
				$productno    = $ced_swc_productHelperObj->get_count_of_collectionproducts_on_shopify( $value );

				if ( isset( $productno['errors'] ) ) {
					continue;
				} else {
					$count = count( $productno['products'] );
					$loop  = (int) ( $count / $productLimit );
					$loop  = 2;

					while ( $loop >= 0 ) {

						// get Collection Products From Store
						$productsCollection = $ced_swc_productHelperObj->getAllCollectionProductsFromStore( $collection_id, $pageNumber, $productLimit );

						$loop = --$loop;

						foreach ( $productsCollection['products'] as $productid ) {
							array_push( $productForSelectedCollection, $productid['id'] );
							$_product_collection_id[ $productid['id'] ][] = $collection_name;
							$pageNumber                                   = $productid['id'];
						}
					}
				}
			}
		}

		unset( $collect_chunk[0] );
		$collect_chunk = array_values( $collect_chunk );
		update_option( 'ced_collect_chunk_array', $collect_chunk );
		update_option( 'ced_swc_mapping_collection_name_correspondsTo_productID', $_product_collection_id );

	}

	public function getCollectionfromStore() {

		$collection_name = array();
		$fileName        = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		require_once $fileName;

		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();
		$collection               = $ced_swc_productHelperObj->getCollectionsFromStore();
		$Smartcollection          = $ced_swc_productHelperObj->getSmartCollectionsFromStore();

		$collection_array      = isset( $collection['custom_collections'] ) ? $collection['custom_collections'] : '';
		$Smartcollection_array = isset( $Smartcollection['smart_collections'] ) ? $Smartcollection['smart_collections'] : '';
		if ( is_array( $collection_array ) ) {
			foreach ( $collection_array as $value ) {
				$collection_name[ $value['id'] ] = $value['title'];
			}
		}
		if ( is_array( $Smartcollection_array ) ) {
			foreach ( $Smartcollection_array as $value ) {
				$collection_name[ $value['id'] ] = $value['title'];
			}
		}
		update_option( 'shopify_collection_name', $collection_name );

		// Create Webhook
		$data_Webhook = array();
		$data_Webhook['webhooks']['1']['webhook'] = array(
			"address" 	=> home_url().'/wp-admin/admin-ajax.php?action=CedSWC_CreateOrderOnWebhhok',
			"topic" 	=> "orders/create",
			"format" 	=> "json",
		);

		foreach( $data_Webhook['webhooks'] as $keys => $values ) {
			$encode_Webhook_data 	= json_encode( $values );
			$response_webhook[] 	= $ced_swc_productHelperObj->ced_swc_createWebhookOnShopifyStore( $encode_Webhook_data );		
		}

		if( !empty($response_webhook && isset($response_webhook[0]['webhook']) ) ) {
			update_option( 'ced_swc_webhook_IDs', $response_webhook );
		}
	}

	public function ced_swc_manage_product_data_from_shopify( $productData = array(), $collection_ids = array() ) {

		set_time_limit( 0 );
		wp_raise_memory_limit();

		if ( is_array( $productData ) && ! empty( $productData ) ) {

			$shopify_product_id  = isset( $productData['id'] ) ? $productData['id'] : '';
			$shopify_product_sku = isset( $productData['variants']['0']['sku'] ) ? $productData['variants']['0']['sku'] : '';
			$store_products      = array();

			$store_products = get_posts(
				array(
					'numberposts'  => -1,
					'post_type'    => 'product',
					'post_status'  => array( 'publish', 'draft' ),
					'meta_key'     => 'ced_swc_shopifyItemId',
					'meta_value'   => $shopify_product_id,
					'meta_compare' => '=',
				)
			);
			$store_products = wp_list_pluck( $store_products, 'ID' );
			$collections    = get_option( 'ced_swc_config_details' );

			$logger  = wc_get_logger();
			$context = array( 'source' => 'CED_store_products' );
			$logger->info( wc_print_r( $store_products, true ), $context );

			if ( empty( $store_products ) ) {

				$productId = wp_insert_post(
					array(
						'post_title'   => isset( $productData['title'] ) ? $productData['title'] : 'Imported from Shopify - ' . $shopify_product_id,
						'post_status'  => 'active' == $productData['status'] ? 'publish' : 'draft',
						'post_type'    => 'product',
						'post_content' => isset( $productData['body_html'] ) ? $productData['body_html'] : '',
					)
				);

				if ( ! $productId ) {
					return;
				} else {
					update_option( 'ced_swc_productPagesSince_id', $productData['id'] );
				}

				update_post_meta( $productId, 'ced_swc_shopifyItemId', $shopify_product_id );

				// Create Product Category
				$this->ced_swc_createProductCategory( $productId, $collection_ids );
				$this->ced_swc_createProductTags( $productId, $productData['tags'] );

				update_post_meta( $productId, 'ced_swc_productData', $productData );
				update_post_meta( $productId, 'ced_swc_shopifyItemId', $shopify_product_id );
				update_post_meta( $productId, 'ced_swc_shopifyVariationItemId', $productData['variants']['0']['id'] );

				if ( isset( $productData['options'] ) && ! empty( $productData['options'] ) ) {
					if ( 1 == count( $productData['options'][0]['values'] ) ) {
						if ( isset( $productData['options'][0]['name'] ) ) {
							$this->ced_swc_importAsSimpleProduct( $productId, $productData['variants'][0] );
						}
					} else {
						$variationAttributes = isset( $productData['options'] ) ? $productData['options'] : array();
						$variations          = isset( $productData['variants'] ) ? $productData['variants'] : array();

						$this->ced_swc_importAsVariableProduct( $productId, $variationAttributes, $variations, $productData );
					}
				}

				$imageUrls = isset( $productData['images'] ) ? $productData['images'] : array();
				if ( empty( $imageUrls ) ) {
					$imageUrls[] = isset( $productData['image'] ) ? array( $productData['image'] ) : array();
				}
				if ( ! empty( $imageUrls ) ) {
					foreach ( $imageUrls as $key => $value ) {
						$image_response = $this->ced_swc_ImportProductImages( $productId, $imageUrls );
					}
				}
			}
		}
	}

	// Set Product Category
	public function ced_swc_createProductCategory( $productId = '', $collection_ids = array() ) {
		if ( empty( $productId ) || empty( $collection_ids ) ) {
			return;
		}

		$collection_list = get_option( 'shopify_collection_name', true );

		foreach ( $collection_ids as $index => $ID ) {
			if ( array_key_exists( $ID, $collection_list ) ) {

				$term = wp_insert_term(
					$collection_list[ $ID ],
					'product_cat',
					array(
						'description' => $collection_list[ $ID ],
					)
				);

				if ( isset( $term->error_data['term_exists'] ) ) {
					$term_id = $term->error_data['term_exists'];
				} elseif ( isset( $term['term_id'] ) ) {
					$term_id = $term['term_id'];
				}

				if ( $term_id ) {
					$term_ids[] = $term_id;
				}
			}

			if ( ! empty( $term_ids ) ) {
				wp_set_object_terms( $productId, $term_ids, 'product_cat' );
			}
		}
	}

	// TAGS
	public function ced_swc_createProductTags( $productId = '', $tags = array() ) {
		if ( isset( $tags ) ) {

			$tags = explode( ',', $tags );

			foreach ( $tags as $key => $tag_name ) {

				if ( empty( $tag_name ) ) {
					continue;
				}

				$term = wp_insert_term(
					$tag_name,                      // The term name to add.
					'product_tag'                       // The taxonomy to which to add the term.
				);

				if ( isset( $term->error_data['term_exists'] ) ) {
					$term_id[] = $term->error_data['term_exists'];
				} elseif ( isset( $term['errors'] ) ) {
					continue;
				} elseif ( isset( $term['term_id'] ) ) {
					$term_id[] = $term['term_id'];
				}
			}

			if ( isset( $term_id ) && ! empty( $term_id ) ) {
				wp_set_object_terms( $productId, $term_id, 'product_tag' );
			}
		}
	}

	public function ced_swc_importAsVariableProduct( $productId, $variationAttributes, $variations, $productData ) {
		if ( empty( $productId ) || empty( $variations ) || empty( $variationAttributes ) ) {
			return;
		}

		if ( ! empty( $variationAttributes ) ) {
			$this->ced_swc_createVariationAttributes( $productId, $variationAttributes );
			$this->ced_swc_createVariations( $productId, $variationAttributes, $variations, $productData );
		}

	}

	public function ced_swc_createVariations( $productId, $variationAttributes, $variations, $productData ) {
		if ( empty( $productId ) || empty( $variations ) || empty( $variationAttributes ) ) {
			return;
		}

		if ( ! empty( $variations ) ) {
			$attributes = array();
			$options    = array();
			$c          = 1;
			foreach ( $variationAttributes as $key => $attribute ) {
				$index                            = 'option' . $c;
				$attributes[ $attribute['name'] ] = $attribute['values'];
				$options[ $index ]                = $attribute['name'];
				$c++;
			}
			wp_set_object_terms( $productId, 'variable', 'product_type' );

			$total_stock = 0;
			foreach ( $variations as $key => $variation ) {

				// Setup the post data for the variation
				$variation_post = array(
					'post_title'  => $productData['title'] . ' ' . $variation['title'],
					'post_name'   => 'product-' . $productId . '-variation-' . $key,
					'post_status' => 'publish',
					'post_parent' => $productId,
					'post_type'   => 'product_variation',
					'guid'        => home_url() . '/?product_variation=product-' . $productId . '-variation-' . $key,
				);

				$variation_post_id = wp_insert_post( $variation_post );

				foreach ( $options as $key1 => $value ) {

					wp_set_object_terms( $variation_post_id, $variation[ $key1 ], $value );

					$attribute = strtolower( $value );
					$attribute = str_replace( ' ', '-', $attribute );
					update_post_meta( $variation_post_id, 'attribute_' . $attribute, $variation[ $key1 ] );
					$thedata = array(
						$attribute => array(
							'name'         => $variation[ $key1 ],
							'value'        => '',
							'is_visible'   => '1',
							'is_variation' => '1',
							'is_taxonomy'  => '1',
						),
					);
					update_post_meta( $variation_post_id, '_product_attributes', $thedata );
				}

				if ( ! empty( $variation['compare_at_price'] ) && $variation['compare_at_price'] > 0 ) {
					update_post_meta( $variation_post_id, '_regular_price', ( $variation['compare_at_price'] ) );
					update_post_meta( $variation_post_id, '_price', ( $variation['price'] ) );
					update_post_meta( $variation_post_id, '_sale_price', ( $variation['price'] ) );
				} else {
					update_post_meta( $variation_post_id, '_regular_price', $variation['price'] );
					update_post_meta( $variation_post_id, '_price', $variation['price'] );
				}

				update_post_meta( $variation_post_id, '_sku', $variation['sku'] );
				update_post_meta( $variation_post_id, 'ced_swc_barcode', $variation['barcode'] );
				update_post_meta( $variation_post_id, 'ced_swc_inventory_item_id', $variation['inventory_item_id'] );

				if ( isset( $variation['inventory_quantity'] ) ) {
					if ( $variation['inventory_quantity'] > 0 ) {
						update_post_meta( $variation_post_id, '_stock', $variation['inventory_quantity'] );
						update_post_meta( $variation_post_id, '_manage_stock', 'yes' );
						update_post_meta( $variation_post_id, '_stock_status', 'instock' );
						$total_stock = $total_stock + $variation['inventory_quantity'];
					} else {
						update_post_meta( $variation_post_id, '_stock', 0 );
						update_post_meta( $variation_post_id, '_manage_stock', 'yes' );
						update_post_meta( $variation_post_id, '_stock_status', 'outofstock' );
					}
				}
				update_post_meta( $variation_post_id, '_weight', $variation['weight'] );
				update_post_meta( $variation_post_id, 'ced_swc_shopifyVariationItemId', $variation['id'] );
				update_post_meta( $variation_post_id, 'ced_swc_shopifyVariationProductData', $variation );

				/* assign variation image */
				$variationImageId = isset( $variation['image_id'] ) ? $variation['image_id'] : '';
				if ( ! empty( $variationImageId ) ) {
					$images = $productData['images'];

					foreach ( $images as $index1 => $image ) {
						$mainImageArray = array();
						if ( $variationImageId == $image['id'] ) {

							$mainImageArray['img_url'] = $image['src'];
							$mainImageArray['width']   = isset( $image['width'] ) ? $image['width'] : '';
							$mainImageArray['height']  = isset( $image['height'] ) ? $image['height'] : '';

							$attachment_id = $this->ced_wTi_InsertProductImage( $variation_post_id, $image['src'] );

							if ( $attachment_id ) {
								// And finally assign featured image to post
								set_post_thumbnail( $variation_post_id, $attachment_id );
								update_post_meta( $variation_post_id, '_product_image_gallery', implode( ',', $mainImageArray ) );
							}
						}
					}
				}

				if ( 0 == $key ) {
					$defaultVariation = array();
					foreach ( $options as $key1 => $value ) {
						$defaultVariation[ $value ] = $variation[ $key1 ];
					}

					update_post_meta( $productId, '_default_attributes', $defaultVariation );
				}
			}

			update_post_meta( $productId, '_manage_stock', 'yes' );
			update_post_meta( $productId, '_sku', $variations[0]['sku'] );
			update_post_meta( $productId, '_stock', $total_stock );
			update_post_meta( $productId, '_stock_status', 'instock' );

			$product_type = 'variable';
			$classname    = WC_Product_Factory::get_product_classname( $productId, $product_type );
			$product      = new $classname( $productId );
			$product->save();
		}
	}

	public function ced_swc_createVariationAttributes( $productId, $variationAttributes ) {
		if ( empty( $productId ) || empty( $variationAttributes ) ) {
			return;
		}
		$count = 1;
		$data  = array();
		foreach ( $variationAttributes as $key => $attribute ) {
			$data['attribute_names'][]      = $attribute['name'];
			$data['attribute_position'][]   = $count;
			$data['attribute_values'][]     = implode( '|', $attribute['values'] );
			$data['attribute_visibility'][] = 1;
			$data['attribute_variation'][]  = 1;
			$count                          = ++$count;
		}

		if ( isset( $data['attribute_names'], $data['attribute_values'] ) ) {
			$attribute_names         = $data['attribute_names'];
			$attribute_values        = $data['attribute_values'];
			$attribute_visibility    = isset( $data['attribute_visibility'] ) ? $data['attribute_visibility'] : array();
			$attribute_variation     = isset( $data['attribute_variation'] ) ? $data['attribute_variation'] : array();
			$attribute_position      = $data['attribute_position'];
			$attribute_names_max_key = max( array_keys( $attribute_names ) );
			for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
				if ( empty( $attribute_names[ $i ] ) || ! isset( $attribute_values[ $i ] ) ) {
					continue;
				}
				$attribute_id   = 0;
				$attribute_name = wc_clean( $attribute_names[ $i ] );
				if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {
					$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
				}
				$options = isset( $attribute_values[ $i ] ) ? $attribute_values[ $i ] : '';
				if ( is_array( $options ) ) {
					// Term ids sent as array.
					$options = wp_parse_id_list( $options );
				} else {
					$options = wc_get_text_attributes( $options );
				}

				if ( empty( $options ) ) {
					continue;
				}
				$attribute = new WC_Product_Attribute();
				$attribute->set_id( $attribute_id );
				$attribute->set_name( $attribute_name );
				$attribute->set_options( $options );
				$attribute->set_position( $attribute_position[ $i ] );
				$attribute->set_visible( isset( $attribute_visibility[ $i ] ) );
				$attribute->set_variation( isset( $attribute_variation[ $i ] ) );
				$attributes[] = $attribute;
			}
		}
		$product_type = 'variable';
		$classname    = WC_Product_Factory::get_product_classname( $productId, $product_type );
		$product      = new $classname( $productId );
		$product->set_attributes( $attributes );
		$product->save();

		return;
	}

	public function ced_swc_importAsSimpleProduct( $productId = '', $productData = array() ) {
		if ( empty( $productId ) || empty( $productData ) ) {
			return;
		}

		wp_set_object_terms( $productId, 'simple', 'product_type' );

		if ( ! empty( $productData['compare_at_price'] ) && $productData['compare_at_price'] > 0 ) {
			update_post_meta( $productId, '_regular_price', ( $productData['compare_at_price'] ) );
			update_post_meta( $productId, '_price', ( $productData['price'] ) );
			update_post_meta( $productId, '_sale_price', ( $productData['price'] ) );
		} else {
			update_post_meta( $productId, '_regular_price', $productData['price'] );
			update_post_meta( $productId, '_price', $productData['price'] );
		}

		update_post_meta( $productId, '_sku', $productData['sku'] );
		update_post_meta( $productId, 'ced_swc_barcode', $productData['barcode'] );
		update_post_meta( $productId, 'ced_swc_inventory_item_id', $productData['inventory_item_id'] );

		if ( isset( $productData['inventory_quantity'] ) ) {
			if ( $productData['inventory_quantity'] > 0 ) {
				update_post_meta( $productId, '_stock', $productData['inventory_quantity'] );
				update_post_meta( $productId, '_manage_stock', 'yes' );
				update_post_meta( $productId, '_stock_status', 'instock' );
			} else {
				update_post_meta( $productId, '_stock', 0 );
				update_post_meta( $productId, '_manage_stock', 'yes' );
				update_post_meta( $productId, '_stock_status', 'outofstock' );
			}
		}

		update_post_meta( $productId, '_weight', $productData['weight'] );
	}

	// Import Product Iamges
	public function ced_swc_ImportProductImages( $productId = '', $imageUrls = array() ) {

		if ( empty( $productId ) || empty( $imageUrls ) ) {
			return;
		}

		// Delete Attachments if any
		$this->delete_all_attachment_on_product_import( $productId );

		$imageUrlArray = array();
		if ( ! is_array( $imageUrls ) || ! isset( $imageUrls[0] ) ) {
			$imageUrlArray[] = $imageUrls;
		} else {
			$imageUrlArray = $imageUrls;
		}

		$image_id = $this->ced_wTi_InsertProductImage( $productId, $imageUrlArray[0]['src'] );
		if ( '' != $image_id ) {
			set_post_thumbnail( $productId, $image_id );
		}

		$image_ids = array();
		foreach ( $imageUrlArray as $key => $value ) {
			if ( 0 == $key ) {
				continue;
			}
			$image_ids[] = $this->ced_wTi_InsertProductImage( $productId, $value['src'] );
		}
		if ( ! empty( $image_ids ) ) {
			update_post_meta( $productId, '_product_image_gallery', implode( ',', $image_ids ) );
		}
		return true;
	}

	public function delete_all_attachment_on_product_import( $productId = '' ) {

		if ( empty( $productId ) ) {
			return;
		}

		$ImageID = get_post_thumbnail_id( $productId );
		wp_delete_attachment( $ImageID );

		$get_gallery_image_id = get_post_meta( $productId, '_product_image_gallery', true );
		$get_gallery_image_id = explode( ',', $get_gallery_image_id );

		if ( is_array( $get_gallery_image_id ) && ! empty( $get_gallery_image_id ) ) {
			foreach ( $get_gallery_image_id as $key => $gallery_image_id ) {
				wp_delete_attachment( $gallery_image_id );
			}
		}
	}

	public function ced_wTi_InsertProductImage( $productId = '', $image_url = '' ) {

		$image_url  = $image_url;
		$image_url  = explode( '?', $image_url );
		$image_url  = $image_url[0];
		$image_name = basename( $image_url );

		// Set upload folder
		$upload_dir = wp_upload_dir();

		// Get image data
		$image_data = file_get_contents( $image_url );

		if ( '' == $image_data || null == $image_data ) {
			$connection = curl_init();
			curl_setopt( $connection, CURLOPT_URL, $image_url );

			curl_setopt( $connection, CURLOPT_RETURNTRANSFER, 1 );
			$image_data = curl_exec( $connection );
			curl_close( $connection );
		}

		$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
		$filename         = basename( $unique_file_name ); // Create image file name

		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		// Create the image  file on the server
		file_put_contents( $file, $image_data );
		copy( $image_url, $file );

		// Check image file type
		$wp_filetype = wp_check_filetype( $filename, null );

		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file, $productId );

		// Include image.php
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

	public function ced_swc_InsertProductImage( $productId = '', $imageurl = '' ) {
		if ( '' == $productId || empty( $imageurl ) ) {
			return false;
		}

		if ( '' == $imageurl ) {
			return false;
		}

		$image_url   = $imageurl;
		$newImageUrl = explode( '?', $image_url )[0];
		$image_name  = basename( $newImageUrl );
		$upload_dir  = wp_upload_dir(); // Set upload folder

		$arrContextOptions = array(
			'ssl' => array(
				'verify_peer'      => false,
				'verify_peer_name' => false,
			),
		);

		$image_data = file_get_contents( $image_url, false, stream_context_create( $arrContextOptions ) ); // Get image data
		if ( '' == $image_data || null == $image_data ) {
			$connection = curl_init();
			curl_setopt( $connection, CURLOPT_URL, $image_url );

			curl_setopt( $connection, CURLOPT_RETURNTRANSFER, 1 );
			$image_data = curl_exec( $connection );
			curl_close( $connection );
		}
		$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
		$filename         = basename( $unique_file_name ); // Create image file name
		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
		file_put_contents( $file, $image_data );

		$wp_filetype = wp_check_filetype( $filename, null );
		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $file, $productId );
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		return $attach_id;
	}

	public function ced_swc_cronSchedules( $schedules ) {
		if ( ! isset( $schedules['ced_swc_3min'] ) ) {
			$schedules['ced_swc_3min'] = array(
				'interval' => 3 * 60,
				'display'  => __( 'Webhook every 3 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_swc_5min'] ) ) {
			$schedules['ced_swc_5min'] = array(
				'interval' => 5 * 60,
				'display'  => __( 'Once every 5 minutes' ),
			);
		}
		if ( ! isset( $schedules['ced_swc_10min'] ) ) {
			$schedules['ced_swc_10min'] = array(
				'interval' => 3 * 60,
				'display'  => __( 'Once every 3 minutes' ),
			);
		}
		return $schedules;
	}

	public function ced_swc_syncProductsFromShopify() {

		$store_product = array();
		$page          = get_option( 'ced_swc_synced_page', '' );

		if ( '' == $page ) {
			$page = 1;
		} else {
			$page++;
		}

		$store_product = get_posts(
			array(
				'numberposts'  => 10,
				'post_type'    => 'product',
				'post_status'  => array( 'publish', 'draft' ),
				'meta_key'     => 'ced_swc_shopifyItemId',
				'meta_compare' => 'EXISTS',
				'paged'        => $page,
			)
		);

		if ( ! empty( $store_product ) ) {
			$product_ids = array();
			$product_ids = wp_list_pluck( $store_product, 'ID' );
		} else {
			delete_option( 'ced_swc_synced_page' );
		}

		if ( is_array( $product_ids ) && ! empty( $product_ids ) ) {

			$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
			require_once $fileName;
			$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();

			foreach ( $product_ids as $key => $productId ) {

				$shopify_product_id = get_post_meta( $productId, 'ced_swc_shopifyItemId', true );
				$productData        = $ced_swc_productHelperObj->getProductData( $shopify_product_id );

				// Set and Update Category
				$_product_collection_id = get_option( 'ced_swc_mapping_collection_name_correspondsTo_productID', true );

				if ( ! empty( $_product_collection_id[ $shopify_product_id ] ) ) {

					$term_ids = array();

					foreach ( $_product_collection_id[ $shopify_product_id ] as $key_1 => $collections_names ) {

						$wooCatName = $collections_names;

						$term = wp_insert_term(
							$wooCatName,
							'product_cat',
							array(
								'description' => $wooCatName,
							)
						);
						if ( isset( $term->error_data['term_exists'] ) ) {
							$term_id = $term->error_data['term_exists'];
						} elseif ( isset( $term['term_id'] ) ) {
							$term_id = $term['term_id'];
						}

						if ( $term_id ) {
							$term_ids[] = $term_id;
						}
					}

					if ( ! empty( $term_ids ) ) {
						wp_set_object_terms( $productId, $term_ids, 'product_cat' );
					}
				}

				if ( ! empty( $productData ) ) {

					$productData = isset( $productData['product'] ) ? $productData['product'] : $productData;
					update_post_meta( $productId, 'ced_swc_productData', $productData );

					if ( isset( $productData['options'] ) && ! empty( $productData['options'] ) ) {
						if ( 1 == count( $productData['options'][0]['values'] ) ) {
							if ( isset( $productData['options'][0]['name'] ) ) {
								$this->ced_swc_updateAsSimpleProduct( $productId, $productData );
							}
						} else {
							$variationAttributes = isset( $productData['options'] ) ? $productData['options'] : array();
							$variations          = isset( $productData['variants'] ) ? $productData['variants'] : array();
							$this->ced_swc_updateAsVariableProduct( $productId, $variations, $productData );
						}
					}

					// To render the hiked price
					$force_update_product = wc_get_product( $productId );
					$force_update_product->save();
				}

				update_option( 'ced_swc_synced_page', $page );
			}
		}
	}

	public function ced_swc_updateAsVariableProduct( $productId = '', $variations = array(), $productData = array() ) {
		if ( ! empty( $variations ) ) {

			// Updates Post Table
			$my_post = array(
				'ID'           => $productId,
				'post_title'   => $productData['title'],
				'post_status'  => 'active' == $productData['status'] ? 'publish' : 'draft',
				'post_content' => isset( $productData['body_html'] ) ? $productData['body_html'] : '',
			);
			wp_update_post( $my_post );

			$total_stock = 0;
			foreach ( $variations as $key => $variation ) {

				$store_product = array();
				$store_product = get_posts(
					array(
						'numberposts'  => -1,
						'post_type'    => 'product_variation',
						'meta_key'     => 'ced_swc_shopifyVariationItemId',
						'meta_value'   => $variation['id'],
						'meta_compare' => 'EXISTS',
					)
				);

				$store_product     = wp_list_pluck( $store_product, 'ID' );
				$variation_post_id = $store_product[0];

				if ( ! empty( $variation_post_id ) ) {

					if ( $variation['compare_at_price'] > 0 && ! empty( $variation['compare_at_price'] ) ) {
						update_post_meta( $variation_post_id, '_regular_price', ( $variation['compare_at_price'] ) );
						update_post_meta( $variation_post_id, '_price', ( $variation['price'] ) );
						update_post_meta( $variation_post_id, '_sale_price', ( $variation['price'] ) );
					} else {
						update_post_meta( $variation_post_id, '_regular_price', ( $variation['price'] ) );
						update_post_meta( $variation_post_id, '_price', ( $variation['price'] ) );
						delete_post_meta( $variation_post_id, '_sale_price' );
					}

					update_post_meta( $variation_post_id, '_sku', $variation['sku'] );
					update_post_meta( $variation_post_id, 'ced_swc_barcode', $variation['barcode'] );
					update_post_meta( $variation_post_id, 'ced_swc_inventory_item_id', $variation['inventory_item_id'] );

					if ( isset( $variation['inventory_quantity'] ) ) {
						if ( $variation['inventory_quantity'] > 0 ) {
							update_post_meta( $variation_post_id, '_stock', $variation['inventory_quantity'] );
							update_post_meta( $variation_post_id, '_stock_status', 'instock' );
							$total_stock = $total_stock + $variation['inventory_quantity'];
						} else {
							update_post_meta( $variation_post_id, '_stock', 0 );
							update_post_meta( $variation_post_id, '_stock_status', 'outofstock' );
						}
					}

					update_post_meta( $variation_post_id, '_weight', $variation['weight'] );
					update_post_meta( $variation_post_id, 'ced_swc_shopifyVariationItemId', $variation['id'] );
					update_post_meta( $variation_post_id, 'ced_swc_shopifyVariationProductData', $variation );
				}
			}

			update_post_meta( $productId, '_sku', $variations[0]['sku'] );
			update_post_meta( $productId, '_manage_stock', 'yes' );
			update_post_meta( $productId, '_stock', $total_stock );
			update_post_meta( $productId, '_stock_status', 'instock' );
		}
	}

	// Update Simple Product
	public function ced_swc_updateAsSimpleProduct( $productId = '', $productData = array() ) {

		if ( empty( $productId ) || empty( $productData ) ) {
			return;
		}

		// Updates Post Table
		$my_post = array(
			'ID'           => $productId,
			'post_title'   => $productData['title'],
			'post_status'  => 'active' == $productData['status'] ? 'publish' : 'draft',
			'post_content' => isset( $productData['body_html'] ) ? $productData['body_html'] : '',
		);
		wp_update_post( $my_post );

		if ( $productData['variants']['0']['compare_at_price'] > 0 && ! empty( $productData['variants']['0']['compare_at_price'] ) ) {
			update_post_meta( $productId, '_regular_price', ( $productData['variants']['0']['compare_at_price'] ) );
			update_post_meta( $productId, '_price', ( $productData['variants']['0']['price'] ) );
			update_post_meta( $productId, '_sale_price', ( $productData['variants']['0']['price'] ) );
		} else {
			update_post_meta( $productId, '_regular_price', ( $productData['variants']['0']['price'] ) );
			update_post_meta( $productId, '_price', ( $productData['variants']['0']['price'] ) );
			delete_post_meta( $productId, '_sale_price' );
		}

		update_post_meta( $productId, 'ced_swc_barcode', $productData['variants']['0']['barcode'] );
		update_post_meta( $productId, 'ced_swc_inventory_item_id', $productData['variants']['0']['inventory_item_id'] );

		if ( isset( $productData['variants']['0']['inventory_quantity'] ) ) {
			if ( $productData['variants']['0']['inventory_quantity'] > 0 ) {
				update_post_meta( $productId, '_stock', $productData['variants']['0']['inventory_quantity'] );
				update_post_meta( $productId, '_manage_stock', 'yes' );
				update_post_meta( $productId, '_stock_status', 'instock' );
			} else {
				update_post_meta( $productId, '_stock', 0 );
				update_post_meta( $productId, '_manage_stock', 'yes' );
				update_post_meta( $productId, '_stock_status', 'outofstock' );
			}
		}

		update_post_meta( $productId, '_weight', $productData['variants']['0']['weight'] );

	}

	public function ced_swc_settings_display() {
		if ( file_exists( CED_SWC_PATH . 'admin/partials/ced-swc-settings.php' ) ) {
			require_once CED_SWC_PATH . 'admin/partials/ced-swc-settings.php';
		}
	}

	//
	// --> Update the Stock of an existing product On SHOPIFY
	//
	public function get_list_of_all_location() {

		$fileName = CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		require_once $fileName;
		$ced_swc_productHelperObj = Ced_SWC_Product_Helper::get_instance();
		$response                 = $ced_swc_productHelperObj->RetrievesListOfLocations();

		if ( array_key_exists( 'locations', $response ) ) {
			update_option( 'retrieve_list_of_locations', $response['locations'] );
		}
		wp_die();

	}

	public function saving_selected_location_option_on_ajax() {

		$sanitized_array = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
		$location_id     = $sanitized_array['value'];
		$location_name   = $sanitized_array['text'];

		if ( isset( $location_id ) ) {
			update_option( 'selected_location_id', $location_id );
		}
		wp_die();

	}

	public function update_product_inventory_on_order_status_processing( $order_id, $order ) {

		global $wpdb;
		if ( $order_id ) {
			$key_exists = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key=%s AND meta_value=%s LIMIT 1", 'ced_check_isit_shopify_order', 'shopify' ) );
			if ( $key_exists ) {
				return;
			}
		}

		$fileName                  = require_once CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		$ced_swc_productHelperObj  = Ced_SWC_Product_Helper::get_instance();
		$orders_data               = $order->get_data();
		$stock_prepare_data        = array();
		$prepare_to_update_product = array();

		$ced_swc_details           = get_option( 'ced_swc_config_details', array() );
		$check_order_export_enable = $ced_swc_details['ced_swc_export_orders_to_shopify'];

		foreach ( $orders_data['line_items'] as $key => $item ) {

			$product_dat   = $item->get_product();
			$product_id    = $item->get_product_id();
			$variation_id  = $item->get_variation_id();
			$item_quantity = $item->get_quantity();
			$item_total    = $item->get_total();
			$item_name     = $item->get_name();

			$product_id           = isset( $variation_id ) && $variation_id > 0 ? $variation_id : $product_id;
			$inventory_item_id    = get_post_meta( $product_id, 'ced_swc_inventory_item_id', true );
			$selected_location_id = get_option( 'selected_location_id', true );
			$shopify_ID           = get_post_meta( $product_id, 'ced_swc_shopifyVariationItemId', true );

			$stock_prepare_data[] = array(
				'location_id'          => $selected_location_id,
				'inventory_item_id'    => $inventory_item_id,
				'available_adjustment' => '-' . $item_quantity,
			);

			if ( is_object( $product_dat ) && ! empty( $shopify_ID ) ) {

				$item_price   = $product_dat->get_price();
				$line_items[] = array(
					'variant_id' => $shopify_ID,
					'price'      => $item_price,
					'quantity'   => $item_quantity,
					'title'      => $item_name,
					'name'       => $item_name,
				);
			}
		}

		$billing_address = array(
			'first_name' => $orders_data['billing']['first_name'],
			'last_name'  => $orders_data['billing']['last_name'],
			'email'      => $orders_data['billing']['email'],
			'phone'      => $orders_data['billing']['phone'],
			'address1'   => $orders_data['billing']['address_1'] . ' ' . $orders_data['billing']['address_2'],
			'city'       => $orders_data['billing']['city'],
			'province'   => $orders_data['billing']['state'],
			'zip'        => $orders_data['billing']['postcode'],
			'country'    => $orders_data['billing']['country'],
		);

		$shipping_address = array(
			'first_name' => $orders_data['shipping']['first_name'],
			'last_name'  => $orders_data['shipping']['last_name'],
			'phone'      => $orders_data['shipping']['phone'],
			'address1'   => $orders_data['shipping']['address_1'] . ' ' . $orders_data['shipping']['address_2'],
			'city'       => $orders_data['shipping']['city'],
			'province'   => $orders_data['shipping']['state'],
			'zip'        => $orders_data['shipping']['postcode'],
			'country'    => $orders_data['shipping']['country'],
		);

		$customer = array(
			'first_name' => $orders_data['billing']['first_name'],
			'last_name'  => $orders_data['billing']['last_name'],
			'email'      => $orders_data['billing']['email'],
		);

		$shopify_note    = 'From WooCommerce Site Order #' . $order_id;
		$source_of_order = 'WooCommerce Site';
		$order_tags      = array(
			$source_of_order,
			'#' . $order_id,
		);

		$prepare_data['order']['line_items']       = $line_items;
		$prepare_data['order']['customer']         = $customer;
		$prepare_data['order']['billing_address']  = $billing_address;
		$prepare_data['order']['shipping_address'] = $shipping_address;
		$prepare_data['order']['note']             = $shopify_note;
		$prepare_data['order']['tags']             = $order_tags;

		foreach ( $order->get_used_coupons() as $coupon_code ) {

			$coupon = new WC_Coupon( $coupon_code );

			$discount_codes = array(
				'code'   => $coupon->get_code(),
				'amount' => $coupon->get_amount(),
				'type'   => $coupon->get_discount_type(),
			);
		}
		$prepare_data['order']['discount_codes'][] = $discount_codes;

		$shipping_price  = $order->get_shipping_total();
		$shipping_method = $order->get_shipping_method();

		if ( ! empty( $shipping_price ) || 0 == $shipping_price ) {

			$total_shipping_price_set                  = array(
				'code'  => 'shipping',
				'title' => ! empty( $shipping_method ) ? $shipping_method : 'shipping',
				'price' => $shipping_price,
			);
			$prepare_data['order']['shipping_lines'][] = $total_shipping_price_set;
		}

		$prepare_data['order']['email']            = $orders_data['billing']['email'];
		$prepare_data['order']['financial_status'] = 'paid';

		if ( ! empty( $line_items ) ) {

			if ( isset( $check_order_export_enable ) && 'on' == $check_order_export_enable ) {

				$encode_product_data = json_encode( $prepare_data );
				$response            = $ced_swc_productHelperObj->CreateOrderOnShopify( $encode_product_data );
			}

			if ( ! empty( $response['order'] ) ) {

				$shopify_order_id = isset( $response['order']['id'] ) ? $response['order']['id'] : '';

				if ( ! empty( $shopify_order_id ) ) {
					update_post_meta( $order_id, 'ced_shopify_order_id', $shopify_order_id );
				}

				foreach ( $stock_prepare_data as $key => $value ) {
					$encode_product_data = json_encode( $value );
					$response            = $ced_swc_productHelperObj->UpdateProductStockOnShopify( $encode_product_data );
				}
			}
		}

	}

	// Schedulers for webhook
	public function ced_swc_schedulers_check() {
		$isShceduled = wp_get_schedule( 'ced_swc_ImportProductsCreatedOnWebhook' );
		if ( ! $isShceduled ) {
			wp_schedule_event( time(), 'ced_swc_3min', 'ced_swc_ImportProductsCreatedOnWebhook' );
		}
	}

	public function CedSWC_CreateOrderOnWebhhok() {
		if( $order_data = json_decode(file_get_contents("php://input"), true) ) {

			$logger = wc_get_logger();
			$context = array('source' => 'CedSWC_CreateOrderOnWebhhok');
			$logger->info(wc_print_r( 'Shopify Order ID', true), $context);
			$logger->info(wc_print_r( $order_data['id'], true), $context);

			$shopifyOrderId = isset($order_data['id']) ? $order_data['id'] : '';

			$temp_arr = get_option( 'ced_swc_order_data_webhook' );

			if( ! is_array($temp_arr) ) {
				$temp_arr = array();
			}

			$temp_arr[] = $order_data;
			update_option( 'ced_swc_order_data_webhook', $temp_arr );
			wp_send_json_success( array( 'message' => 'All OK!' ), 200 );
		}
	}

	public function ced_swc_ImportProductsCreatedOnWebhook(){

		$OrderCreated_array_chunk = get_option( 'ced_swc_order_data_webhook_toProcessChunk' );

		if ( empty( $OrderCreated_array_chunk ) ) {
			$Get_Data 					= get_option( 'ced_swc_order_data_webhook' );
			$OrderCreated_array_chunk 	= array_chunk( $Get_Data, 5 );

			update_option( 'ced_swc_order_data_webhook', array() );
		}

		// echo'<pre>';
		// print_r(get_option( 'ced_swc_order_data_webhook' ));
		// die;

		if( ! empty( $OrderCreated_array_chunk) ) {
			foreach( $OrderCreated_array_chunk[0] as $key => $OrdersData ) {
				$this->ced_swc_manage_orders_data_from_shopify( $OrdersData );
			}

			unset( $OrderCreated_array_chunk[0] );
			$OrderCreated_array_chunk = array_values( $OrderCreated_array_chunk );
			update_option( 'ced_swc_order_data_webhook_toProcessChunk', $OrderCreated_array_chunk );
		}
	}


}
