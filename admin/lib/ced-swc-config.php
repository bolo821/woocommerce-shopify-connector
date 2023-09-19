<?php
if ( ! class_exists( 'Ced_SWC_Config' ) ) {
	class Ced_SWC_Config {

		public $appkey;
		public $password;
		public $shopifyStoreUrl;

		public static $_instance;

		/**
		 * Ced_SWC_Config Instance.
		 *
		 * Ensures only one instance of Ced_SWC_Config is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @return CED_UMB_EBAY_ebay_Manager instance.
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			$ced_swc_configDetails     = get_option( 'ced_swc_config_details', array() );
			$this->ced_swc_accesstoken = isset( $ced_swc_configDetails['ced_swc_accesstoken'] ) ? $ced_swc_configDetails['ced_swc_accesstoken'] : '';
			$this->shopifyStoreUrl     = isset( $ced_swc_configDetails['ced_swc_shopifyStoreUrl'] ) ? $ced_swc_configDetails['ced_swc_shopifyStoreUrl'] : '';
		}
	}
}
