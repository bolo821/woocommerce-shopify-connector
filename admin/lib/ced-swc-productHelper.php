<?php
if ( ! class_exists( 'Ced_SWC_Product_Helper' ) ) {
	class Ced_SWC_Product_Helper {

		private static $_instance;
		/**
		 * Get_instance Instance.
		 *
		 * Ensures only one instance of Ced_SWC_Product_Helper is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @return get_instance instance.
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function getProductData( $shopifyProductId = '' ) {
			$action = "admin/products/{$shopifyProductId}.json";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getProductsFromStore( $since_id = 0, $productLimit = 10 ) {
			$action = "admin/products.json?limit={$productLimit}&since_id={$since_id}";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getCountOfAllProducts() {
			$action = 'admin/api/2021-04/products/count.json';

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getCustomerFromStore( $since_id = 0, $customersLimit = 50 ) {
			$action = "admin/api/2021-04/customers.json?since_id={$since_id}&limit={$customersLimit}";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getCouponsFromStore( $since_id = 0, $couponsLimit = 50 ) {
			$action = "admin/api/2021-04/price_rules.json?since_id={$since_id}&limit={$couponsLimit}";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getPostsFromStore( $since_id = 0, $postsLimit = 50 ) {
			$action = "admin/api/2021-04/blogs.json?since_id={$since_id}&limit={$postsLimit}";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getArticleFromStoreByPostsId( $blog_id_to_get_articles = '', $since_id = 0, $postsLimit = 50 ) {
			$action = "admin/api/2021-04/blogs/{$blog_id_to_get_articles}/articles.json?since_id={$since_id}&limit={$postsLimit}";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function get_count_of_collectionproducts_on_shopify( $value = '' ) {
			$action = "admin/api/2021-04/collections/{$value}/products.json?limit=250";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getOrdersFromStore( $since_id = 0, $ordersLimit = 50 ) {
			$action = "admin/api/2021-04/orders.json?status=any&since_id={$since_id}&limit={$ordersLimit}";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getAllCollectionProductsFromStore( $collection, $pageNumber, $productLimit ) {
			$action = "admin/api/2021-04/collections/{$collection}/products.json?limit={$productLimit}&since_id={$pageNumber}";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getCollectionsFromStore() {
			$action = 'admin/custom_collections.json?limit=250';

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function getSmartCollectionsFromStore() {
			$action = 'admin/smart_collections.json?limit=250';

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function RetrievesListOfLocations() {
			$action = 'admin/api/2021-10/locations.json';

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		public function CreateOrderOnShopify( $encode_product_data ) {
			$action = 'admin/api/2021-10/orders.json';

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->PostHttpRequest( $action, $encode_product_data );
			return $response;
		}

		public function UpdateProductStockOnShopify( $encode_product_data ) {
			$action = 'admin/api/2021-10/inventory_levels/adjust.json';

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->PostHttpRequest( $action, $encode_product_data );
			return $response;
		}

		// Create Webhhok
		public function ced_swc_createWebhookOnShopifyStore( $encode_Webhook_data ) {
			$action = "admin/api/2022-04/webhooks.json";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->PostHttpRequest( $action, $encode_Webhook_data );
			return $response;
		}

		// Get Webhhok
		public function ced_swc_GetWebhookOnShopifyStore() {
			$action = "admin/api/2022-04/webhooks.json";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->sendHttpRequest( $action );
			return $response;
		}

		// Delete Webhook
		public function ced_swc_DeleteWebhookOnShopifyStore( $webhookID ) {
			$action 	= "admin/api/2022-04/webhooks/{$webhookID}.json";

			$fileNmae = CED_SWC_PATH . 'admin/lib/ced-swc-sendHttpRequest.php';
			require_once $fileNmae;
			$ced_swc_sendHttpRequestInstance = new Ced_SWC_Send_HTTP_Request();

			$response = $ced_swc_sendHttpRequestInstance->DeleteHttpRequest( $action );
			return $response;
		}

	}
}
