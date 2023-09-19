<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://cedcommerce.com
 * @since             1.0.0
 * @package           Cedcommerce_Shopify_Woocommerce_Connector
 *
 * @wordpress-plugin
 * Plugin Name:       Shopify Connector for WooCommerce
 * Plugin URI:        https://cedcommerce.com
 * Description:       The plugin allows merchants to connect their Shopify store with the WooCommerce Store.
 * Version:           1.0.8
 * Author:            CedCommerce
 * Author URI:        https://cedcommerce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cedcommerce-shopify-woocommerce-connector
 * Domain Path:       /languages
 *
 * Woo: 7772646:30794755ec11744325d356e86a70e18d
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME__SWC_VERSION', '1.0.8' );
define( 'CED_SWC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cedcommerce-shopify-woocommerce-connector-activator.php
 */
function activate_cedcommerce_shopify_woocommerce_connector() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cedcommerce-shopify-woocommerce-connector-activator.php';
	Cedcommerce_Shopify_Woocommerce_Connector_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cedcommerce-shopify-woocommerce-connector-deactivator.php
 */
function deactivate_cedcommerce_shopify_woocommerce_connector() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cedcommerce-shopify-woocommerce-connector-deactivator.php';
	Cedcommerce_Shopify_Woocommerce_Connector_Deactivator::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_cedcommerce_shopify_woocommerce_connector' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cedcommerce-shopify-woocommerce-connector.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cedcommerce_shopify_woocommerce_connector() {

	$plugin = new Cedcommerce_Shopify_Woocommerce_Connector();
	$plugin->run();

}

function ced_swc_check_woocommerce_active() {
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		return true;
	}
	return false;
}


function ced_admin_notice_example_activation_hook_ced_shopify() {
	set_transient( 'ced-swc-admin-notice', true, 5 );
}


function ced_shopify_admin_notice_activation() {
	if ( get_transient( 'ced-swc-admin-notice' ) ) {?>
		<div class="updated notice is-dismissible">
			<p>Welcome to Shopify Connector For WooCommerce.</p>
		</div>
		<?php
		delete_transient( 'ced-swc-admin-notice' );
	}
}

function ced_shopify_woo_missing_notice() {
	// translators: %s: search term !!
	echo '<div class="notice notice-error is-dismissible"><p>' . sprintf( esc_html( __( 'Shopify Connector For Woocommerce requires WooCommerce to be installed and active. You can download %s from here.', 'shopify-connector-for-woocommerce' ) ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</p></div>';
}

function deactivate_ced_shopify_woo() {
	deactivate_plugins( CED_SWC_PLUGIN_BASENAME );
	add_action( 'admin_notices', 'ced_shopify_woo_missing_notice' );
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}


if ( ced_swc_check_woocommerce_active() ) {
	run_cedcommerce_shopify_woocommerce_connector();
	register_activation_hook( __FILE__, 'ced_admin_notice_example_activation_hook_ced_shopify' );
	add_action( 'admin_notices', 'ced_shopify_admin_notice_activation' );
} else {
	add_action( 'admin_init', 'deactivate_ced_shopify_woo' );
}
