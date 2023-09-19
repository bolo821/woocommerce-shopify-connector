<?php
	$ced_swc_details = get_option( 'ced_swc_config_details', array() );
?>

<div class="ced-swc-header ced-swc-configuration-heading-wrapper">
	<div class="ced-swc-logo">
		<img src="<?php echo esc_attr( CED_SWC_URL ) . 'admin/images/icon-100X100.png'; ?>">
	</div>

	<div class="ced-swc-header-content">
		<div class="ced-swc-title">
			<h1>Step 1: SWC Configuration</h1>
		</div>
	</div>
</div>

<div class="ced-swc-content-wrapper">
	<div class="ced-swc-settings-wrapper ced-swc-configuration-settings-wrapper">
		<div class="ced-swc-configuration-settings-table-wrapper">
			<form method="post">
				<table class="ced-swc-configuration-settings-table">
					<tbody>
					
						<tr>
							<td>
								<label><?php esc_html_e( 'Shopify Store Url', 'woocommerce-g2a-importer' ); ?></label>
							</td>
							<td>
								<input type="text" value="<?php echo isset( $ced_swc_details['ced_swc_shopifyStoreUrl'] ) ? esc_attr( $ced_swc_details['ced_swc_shopifyStoreUrl'] ) : ''; ?>" class="ced-swc-text-field ced-swc-sellerEmail" name="ced_swc_config_details[ced_swc_shopifyStoreUrl]" placeholder="<?php esc_html_e( 'Enter Shopify Store Url eg. storename.myshopify.com/', 'cedcommerce-shopify-woocommerce-connector' ); ?>" required></input>
							</td>
						</tr>

						<tr>
							<td>
								<label><?php esc_html_e( 'Shopify Admin API access token', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
							</td>
							<td>
								<span><input type="text" value="<?php echo isset( $ced_swc_details['ced_swc_accesstoken'] ) ? esc_attr( $ced_swc_details['ced_swc_accesstoken'] ) : ''; ?>" class="ced-swc-text-field ced_swc_hashKey" name="ced_swc_config_details[ced_swc_accesstoken]" placeholder="<?php esc_html_e( 'Enter Shopify Admin API access token', 'cedcommerce-shopify-woocommerce-connector' ); ?>" required></input></span>
								<span><i>Learn how to create custom apps </i><a href="https://help.shopify.com/en/manual/apps/custom-apps" target="_blank">Click Here</a></span>
							</td>
						</tr>

						<tr class="ced-swc-save-config-row">
							<?php wp_nonce_field( 'ced_sWc_setting_page_nonce', 'ced_sWc_setting_nonce' ); ?>
							<td></td>
							<td>
								<input type="hidden" name="ced_swc_config_details[config_setup_completed]" value="1">
								<input type="submit" name="ced_swc_save_configuration_details_step_1" class="ced-swc-save-configuration-button ced-swc-nav-to-next" value="<?php esc_html_e( 'Next', 'cedcommerce-shopify-woocommerce-connector' ); ?>"></input>
							</td>
						</tr>

					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>
