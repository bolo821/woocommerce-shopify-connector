<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://cedcommerce.com
 * @since      1.0.0
 *
 * @package    Cedcommerce_Shopify_Woocommerce_Connector
 * @subpackage Cedcommerce_Shopify_Woocommerce_Connector/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cedcommerce_Shopify_Woocommerce_Connector
 * @subpackage Cedcommerce_Shopify_Woocommerce_Connector/includes
 */
class Cedcommerce_Shopify_Woocommerce_Connector_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cedcommerce-shopify-woocommerce-connector',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
