<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cedcommerce.com
 * @since      1.0.0
 *
 * @package    cedcommerce-shopify-woocommerce-connector
 * @subpackage cedcommerce-shopify-woocommerce-connector/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Save G2A Credentails */
if ( isset( $_POST['ced_sWc_setting_nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['ced_sWc_setting_nonce'] ), 'ced_sWc_setting_page_nonce' ) ) {

	$sanitized_array = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );

	if ( isset( $sanitized_array['ced_swc_save_configuration_details_step_1'] ) || isset( $sanitized_array['ced_swc_save_configuration_details_step_2'] ) ) {

		$ced_swc_details = isset( $sanitized_array['ced_swc_config_details'] ) ? ( $sanitized_array['ced_swc_config_details'] ) : array();

		if ( isset( $sanitized_array['ced_swc_save_configuration_details_step_1'] ) ) {
			$ced_swc_shopifyStoreUrl                    = ! empty( $ced_swc_details['ced_swc_shopifyStoreUrl'] ) ? $ced_swc_details['ced_swc_shopifyStoreUrl'] : '';
			$StoreUrl                                   = rtrim( $ced_swc_shopifyStoreUrl, '/' ) . '/';
			$ced_swc_details['ced_swc_shopifyStoreUrl'] = $StoreUrl;
		}

		$saved_ced_swc_details = get_option( 'ced_swc_config_details', array() );
		foreach ( $ced_swc_details as $key => $value ) {
			$saved_ced_swc_details[ $key ] = $value;
		}

		update_option( 'ced_swc_config_details', $saved_ced_swc_details );

		if ( isset( $ced_swc_details['ced_swc_shopifycollectionToimport'] ) ) {
			wp_clear_scheduled_hook( 'ced_swc_get_collection' );
			wp_schedule_event( time(), 'ced_swc_5min', 'ced_swc_get_collection' );

			wp_clear_scheduled_hook( 'ced_swc_GetAllCollectionNameOfAllProducts' );
			wp_schedule_event( time(), 'ced_swc_5min', 'ced_swc_GetAllCollectionNameOfAllProducts' );
		}
	}

	if ( isset( $sanitized_array['ced_swc_save_configuration_details_step_3'] ) ) {
		$ced_swc_details       = isset( $sanitized_array['ced_swc_config_details'] ) ? ( $sanitized_array['ced_swc_config_details'] ) : array();
		$saved_ced_swc_details = get_option( 'ced_swc_config_details', array() );
		foreach ( $ced_swc_details as $key => $value ) {
			$saved_ced_swc_details[ $key ] = $value;
		}
		update_option( 'ced_swc_config_details', $saved_ced_swc_details );
		if ( is_array( $ced_swc_details ) && ! empty( $ced_swc_details ) ) {

			if ( isset( $ced_swc_details['ced_swc_enableScheduler'] ) && 'on' == $ced_swc_details['ced_swc_enableScheduler'] ) {
				wp_clear_scheduled_hook( 'ced_swc_syncProductsFromShopify' );
				wp_schedule_event( time(), 'ced_swc_10min', 'ced_swc_syncProductsFromShopify' );
			} else {
				wp_clear_scheduled_hook( 'ced_swc_syncProductsFromShopify' );
			}

			if ( isset( $ced_swc_details['ced_swc_enableAutoImport'] ) && 'on' == $ced_swc_details['ced_swc_enableAutoImport'] ) {
				update_option( 'ced_swc_enableAutoImport', 'yes' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportProducts' );
				wp_schedule_event( time(), 'ced_swc_5min', 'ced_swc_autoImportProducts' );
			} else {
				update_option( 'ced_swc_enableAutoImport', 'no' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportProducts' );
			}

			if ( isset( $ced_swc_details['ced_swc_import_customer'] ) ) {
				update_option( 'ced_swc_import_customer', 'yes' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportCustomer' );
				wp_schedule_event( time(), 'ced_swc_10min', 'ced_swc_autoImportCustomer' );
			} else {
				update_option( 'ced_swc_import_customer', 'no' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportCustomer' );
			}

			if ( isset( $ced_swc_details['ced_swc_import_coupons'] ) ) {
				update_option( 'ced_swc_import_coupons', 'yes' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportCoupons' );
				wp_schedule_event( time(), 'ced_swc_10min', 'ced_swc_autoImportCoupons' );
			} else {
				update_option( 'ced_swc_import_coupons', 'no' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportCoupons' );
			}

			if ( isset( $ced_swc_details['ced_swc_import_posts'] ) ) {
				update_option( 'ced_swc_import_posts', 'yes' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportPosts' );
				wp_schedule_event( time(), 'ced_swc_10min', 'ced_swc_autoImportPosts' );
			} else {
				update_option( 'ced_swc_import_posts', 'no' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportPosts' );
			}

			if ( isset( $ced_swc_details['ced_swc_import_orders'] ) ) {
				update_option( 'ced_swc_import_orders', 'yes' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportOrders' );
				wp_schedule_event( time(), 'ced_swc_10min', 'ced_swc_autoImportOrders' );
			} else {
				update_option( 'ced_swc_import_orders', 'no' );
				wp_clear_scheduled_hook( 'ced_swc_autoImportOrders' );
			}
		}
	}

	// AT Step 2: Collections List To Import GOTO Step 1: SWC Configuration
	if ( isset( $sanitized_array['ced_swc_goto_previous_configuration_details_step_1'] ) ) {

		delete_option( 'ced_swc_config_details' );
		delete_option( 'ced_swc_selected_collection_products' );

		wp_clear_scheduled_hook( 'ced_swc_get_collection' );
		wp_clear_scheduled_hook( 'ced_swc_GetAllCollectionNameOfAllProducts' );

		delete_option( 'ced_swc_mapping_collection_name_correspondsTo_productID' );
		delete_option( 'ced_collect_chunk_array' );
	}

	// AT Step 3: All Schedulers GOTO Step 2: Collections List To Import
	if ( isset( $sanitized_array['ced_swc_goto_previous_configuration_details_step_2'] ) ) {

		$ced_swc_details       = isset( $sanitized_array['ced_swc_config_details'] ) ? ( $sanitized_array['ced_swc_config_details'] ) : array();
		$saved_ced_swc_details = get_option( 'ced_swc_config_details', array() );

		if ( isset( $saved_ced_swc_details['ced_swc_shopifycollectionToimport'] ) ) {
			unset( $saved_ced_swc_details['ced_swc_shopifycollectionToimport'] );
		}

		foreach ( $ced_swc_details as $key => $value ) {
			$value                         = $value - 2;
			$saved_ced_swc_details[ $key ] = $value;
		}

		update_option( 'ced_swc_config_details', $saved_ced_swc_details );

	}

	// AT Final Step GOTO Step 3: All Schedulers
	if ( isset( $sanitized_array['ced_swc_goto_previous_configuration_details_step_3'] ) ) {

		$ced_swc_details       = isset( $sanitized_array['ced_swc_config_details'] ) ? ( $sanitized_array['ced_swc_config_details'] ) : array();
		$saved_ced_swc_details = get_option( 'ced_swc_config_details', array() );

		if ( isset( $saved_ced_swc_details['ced_swc_enableScheduler'] ) ) {
			unset( $saved_ced_swc_details['ced_swc_enableScheduler'] );
		}
		if ( isset( $saved_ced_swc_details['ced_swc_enableAutoImport'] ) ) {
			unset( $saved_ced_swc_details['ced_swc_enableAutoImport'] );
		}
		if ( isset( $saved_ced_swc_details['ced_swc_import_customer'] ) ) {
			unset( $saved_ced_swc_details['ced_swc_import_customer'] );
		}
		if ( isset( $saved_ced_swc_details['ced_swc_import_coupons'] ) ) {
			unset( $saved_ced_swc_details['ced_swc_import_coupons'] );
		}
		if ( isset( $saved_ced_swc_details['ced_swc_import_posts'] ) ) {
			unset( $saved_ced_swc_details['ced_swc_import_posts'] );
		}
		if ( isset( $saved_ced_swc_details['ced_swc_import_orders'] ) ) {
			unset( $saved_ced_swc_details['ced_swc_import_orders'] );
		}
		if ( isset( $saved_ced_swc_details['ced_swc_export_orders_to_shopify'] ) ) {
			unset( $saved_ced_swc_details['ced_swc_export_orders_to_shopify'] );
		}

		foreach ( $ced_swc_details as $key => $value ) {
			$value                         = $value - 2;
			$saved_ced_swc_details[ $key ] = $value;
		}

		update_option( 'ced_swc_config_details', $saved_ced_swc_details );

		wp_clear_scheduled_hook( 'ced_swc_syncProductsFromShopify' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportProducts' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportCustomer' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportCoupons' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportPosts' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportOrders' );

	}

	// ------------------------------ RESET BUTTON ------------------------------ #
	// AT Final Step GOTO Step 1
	if ( isset( $sanitized_array['ced_swc_reset_configuration_details'] ) ) {

		// Get and Delete Webhook
		require_once CED_SWC_PATH . 'admin/lib/ced-swc-productHelper.php';
		$ced_swc_productHelperObj 	= Ced_SWC_Product_Helper::get_instance();
		$getwebhhok 				= $ced_swc_productHelperObj->ced_swc_GetWebhookOnShopifyStore();
		if( ! empty($getwebhhok) && is_array($getwebhhok) ) {
			foreach( $getwebhhok['webhooks'] as $keys => $values ) {
				$deletewebhhok = $ced_swc_productHelperObj->ced_swc_DeleteWebhookOnShopifyStore( $values['id'] );
			}
		}
		delete_option( 'ced_swc_webhook_IDs' );


		delete_option( 'ced_swc_config_details' );
		delete_option( 'ced_swc_selected_collection_products' );
		delete_option( 'selected_location_id' );
		delete_option( 'retrieve_list_of_locations');

		wp_clear_scheduled_hook( 'ced_swc_get_collection' );
		wp_clear_scheduled_hook( 'ced_swc_GetAllCollectionNameOfAllProducts' );
		wp_clear_scheduled_hook( 'ced_swc_syncProductsFromShopify' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportProducts' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportCustomer' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportCoupons' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportPosts' );
		wp_clear_scheduled_hook( 'ced_swc_autoImportOrders' );

	}
}

// --------------------------------------------------------- ##
$steps_completed       = get_option( 'ced_swc_config_details', array() );
$steps_completed_value = isset( $steps_completed['config_setup_completed'] ) ? $steps_completed['config_setup_completed'] : '';

if ( empty( $steps_completed_value ) ) {
	require_once CED_SWC_PATH . 'admin/partials/ced-config-step-1.php';
} elseif ( '1' == $steps_completed_value ) {
	require_once CED_SWC_PATH . 'admin/partials/ced-config-step-2.php';
} elseif ( '2' == $steps_completed_value ) {
	require_once CED_SWC_PATH . 'admin/partials/ced-config-step-3.php';
} elseif ( '3' == $steps_completed_value ) {
	require_once CED_SWC_PATH . 'admin/partials/final-setup.php';
}

$ced_swc_details     = get_option( 'ced_swc_config_details', array() );
$collections         = get_option( 'shopify_collection_name' );
$collection          = get_option( 'ced_swc_config_details' );
$selected_collection = isset( $collection['ced_swc_shopifycollectionToimport'] ) ? $collection['ced_swc_shopifycollectionToimport'] : '';

if ( isset( $_SESSION['ced-swc-saved-settings'] ) ) {
	?>
		<div id="message" class="updated notice notice-success is-dismissible">
			<p><?php echo esc_attr( $_SESSION['ced-swc-saved-settings'] ); ?></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'cedcommerce-shopify-woocommerce-connector' ); ?></span>
			</button>
		</div>
	<?php
	unset( $_SESSION['ced-swc-saved-settings'] );
}
