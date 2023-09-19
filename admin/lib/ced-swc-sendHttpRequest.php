<?php
class Ced_SWC_Send_HTTP_Request {

	public $appkey;
	public $password;
	public $shopifyStoreUrl;

	public function __construct() {

		$this->loadDepenedency();
		$this->shopifyStoreUrl     = $this->ced_swc_configInstance->shopifyStoreUrl;
		$this->ced_swc_accesstoken = $this->ced_swc_configInstance->ced_swc_accesstoken;
	}

	/** SendHttpRequest
		Sends a HTTP request to the server for this session
		Input:  $requestBody
		Output: The HTTP Response as a String
	 */
	public function sendHttpRequest( $action = '' ) {

		if ( '' == $action ) {
			return false;
		}
		// initialise a CURL session
		$connection = curl_init();
		$url        = 'https://' . $this->shopifyStoreUrl . $action;
		$headers    = $this->buildShopifyApiHeaders( $this->ced_swc_accesstoken );

		curl_setopt( $connection, CURLOPT_URL, $url );
		// stop CURL from verifying the peer's certificate
		curl_setopt( $connection, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $connection, CURLOPT_SSL_VERIFYHOST, 0 );
		// set the headers using the array of headers
		curl_setopt( $connection, CURLOPT_HTTPHEADER, $headers );

		curl_setopt( $connection, CURLOPT_RETURNTRANSFER, 1 );

		$response = curl_exec( $connection );
		curl_close( $connection );
		return $this->ParseResponse( $response );
	}

	public function PostHttpRequest( $action = '', $encode_product_data = array() ) {

		if ( '' == $action ) {
			return false;
		}

		// initialise a CURL session
		$connection = curl_init();
		$url        = 'https://' . $this->shopifyStoreUrl . $action;
		$headers    = $this->buildShopifyApiHeaders( $this->ced_swc_accesstoken );

		curl_setopt( $connection, CURLOPT_URL, $url );
		curl_setopt( $connection, CURLOPT_POST, 1 );
		curl_setopt( $connection, CURLOPT_POSTFIELDS, $encode_product_data );
		curl_setopt( $connection, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $connection, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $connection, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $connection, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $connection, CURLOPT_HTTPHEADER, $headers );
		$response = curl_exec( $connection );
		curl_close( $connection );
		return $this->ParseResponse( $response );
	}

	public function DeleteHttpRequest( $action = '' ) {
		if ( '' == $action ) {
			return false;
		}

		// initialise a CURL session
		$connection = curl_init();
		$url        = 'https://' . $this->shopifyStoreUrl . $action;
		$headers    = $this->buildShopifyApiHeaders( $this->ced_swc_accesstoken );

		curl_setopt($connection, CURLOPT_URL, $url);
		curl_setopt($connection, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($connection);
		$httpCode = curl_getinfo($connection, CURLINFO_HTTP_CODE);
		curl_close($connection);
		return $this->ParseResponse( $httpCode );
	}

	public function buildShopifyApiHeaders( $ced_swc_accesstoken ) {

		$headers = array(
			'Content-type: application/json',
			'X-Shopify-Access-Token: ' . $ced_swc_accesstoken . '',
		);
		return $headers;
	}

	public function prepareApiRequestUrl( $url = '' ) {
		if ( '' == $url ) {
			return false;
		}

		$url = 'https://' . $this->appkey . ':' . $this->password . '@' . $url;
		return $url;
	}

	public function ParseResponse( $response ) {

		$res = json_decode( $response, true );

		return $res;
	}

	/**
	 * Function loadDepenedency
	 *
	 * @name loadDepenedency
	 */
	public function loadDepenedency() {

		if ( is_file( __DIR__ . '/ced-swc-config.php' ) ) {
			require_once 'ced-swc-config.php';
			$this->ced_swc_configInstance = Ced_SWC_Config::get_instance();
		}
	}
}
