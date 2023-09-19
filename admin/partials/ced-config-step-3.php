<?php
	$ced_swc_details = get_option( 'ced_swc_config_details', array() );
	$collections     = get_option( 'shopify_collection_name' );
?>

<div class="ced-swc-header ced-swc-configuration-heading-wrapper">

	<div class="ced-swc-logo">
		<img src="<?php echo esc_attr( CED_SWC_URL ) . 'admin/images/icon-100X100.png'; ?>">
	</div>

	<div class="ced-swc-header-content">

		<div class="ced-swc-title">
			<h1>Step 3: All Schedulers</h1>
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
							<?php
							if ( isset( $ced_swc_details['ced_swc_enableScheduler'] ) ) {
								$checked = 'checked';
							} else {
								$checked = '';
							}
							?>
							<td>
								<label><?php esc_html_e( 'Enable scheduler for syncing', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
							</td>
							<td>
								<label class="switch">
									<input type="checkbox" <?php echo esc_attr( $checked ); ?> name="ced_swc_config_details[ced_swc_enableScheduler]" class="ced-swc-text-field-check ced-swc-enableScheduler">
									<span class="slider"></span>
								</label>
							</td>
						</tr>
						   
						<tr>
							<?php
							if ( isset( $ced_swc_details['ced_swc_enableAutoImport'] ) ) {
								$checked = 'checked';
							} else {
								$checked = '';
							}
							?>
							<td>
								<label><?php esc_html_e( 'Enable Import of already Created products from shopify store', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
							</td>
							<td>
								<label class="switch">
									<input type="checkbox" <?php echo esc_attr( $checked ); ?> name="ced_swc_config_details[ced_swc_enableAutoImport]" class="ced-swc-text-field-check ced-swc-enableAutoImport">
									<span class="slider"></span>
								</label>
							</td>
						</tr>

						<tr>
							<?php
							if ( isset( $ced_swc_details['ced_swc_import_customer'] ) ) {
								$checked = 'checked';
							} else {
								$checked = '';
							}
							?>
							<td>
								<label><?php esc_html_e( 'Enable auto Import customer from shopify store', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
							</td>
							<td>
								<label class="switch">
									<input type="checkbox" <?php echo esc_attr( $checked ); ?> name="ced_swc_config_details[ced_swc_import_customer]" class="ced-swc-text-field-check ced-swc-enableAutoImport">
									<span class="slider"></span>
								</label>
							</td>
						</tr>

						<tr>
							<?php
							if ( isset( $ced_swc_details['ced_swc_import_coupons'] ) ) {
								$checked = 'checked';
							} else {
								$checked = '';
							}
							?>
							<td>
								<label><?php esc_html_e( 'Enable to Import coupons from shopify store', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
							</td>
							<td>
								<label class="switch">
									<input type="checkbox" <?php echo esc_attr( $checked ); ?> name="ced_swc_config_details[ced_swc_import_coupons]" class="ced-swc-text-field-check ced-swc-enableAutoImport">
									<span class="slider"></span>
								</label>
							</td>
						</tr>

						<tr>
							<?php
							if ( isset( $ced_swc_details['ced_swc_import_posts'] ) ) {
								$checked = 'checked';
							} else {
								$checked = '';
							}
							?>
							<td>
								<label><?php esc_html_e( 'Enable to Import posts from shopify store', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
							</td>
							<td>
								<label class="switch">
									<input type="checkbox" <?php echo esc_attr( $checked ); ?> name="ced_swc_config_details[ced_swc_import_posts]" class="ced-swc-text-field-check ced-swc-enableAutoImport">
									<span class="slider"></span>
								</label>
							</td>
						</tr>

						<tr>
							<?php
							if ( isset( $ced_swc_details['ced_swc_import_orders'] ) ) {
								$checked = 'checked';
							} else {
								$checked = '';
							}
							?>
							<td>
								<label><?php esc_html_e( 'Enable to Import order from shopify store', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
							</td>
							<td>
								<label class="switch">
									<input type="checkbox" <?php echo esc_attr( $checked ); ?> name="ced_swc_config_details[ced_swc_import_orders]" class="ced-swc-text-field-check ced-swc-enableAutoImport">
									<span class="slider"></span>
								</label>
							</td>
						</tr>
						
						<tr>
							<?php
							if ( isset( $ced_swc_details['ced_swc_export_orders_to_shopify'] ) ) {
								$checked = 'checked';
							} else {
								$checked = '';
							}
							?>
							<td>
								<label><?php esc_html_e( 'Enable to export order to shopify store', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
								<div class="tooltip">
									<i class="fas fa-info-circle fa-1x"></i>
									<span class="tooltiptext"> Enable this to export an order created on 'Processing' status on woocommerce to shopify store </span>
								</div>
							</td>
							<td>
								<label class="switch">
									<input type="checkbox" <?php echo esc_attr( $checked ); ?> name="ced_swc_config_details[ced_swc_export_orders_to_shopify]" class="ced-swc-text-field-check ced-swc-enableAutoExport">
									<span class="slider"></span>
								</label>
							</td>
						</tr>

						<tr class="ced-swc-save-config-row">
							<?php wp_nonce_field( 'ced_sWc_setting_page_nonce', 'ced_sWc_setting_nonce' ); ?>
							<td>
								<input type="submit" name="ced_swc_goto_previous_configuration_details_step_2" class="ced-swc-save-configuration-button ced-swc-nav-to-previous" value="<?php esc_html_e( 'Previous', 'cedcommerce-shopify-woocommerce-connector' ); ?>"></input>
							</td>
							<td>
								<input type="hidden" name="ced_swc_config_details[config_setup_completed]" value="3">
								<input type="submit" name="ced_swc_save_configuration_details_step_3" class="ced-swc-save-configuration-button ced-swc-nav-to-next" value="<?php esc_html_e( 'Next', 'cedcommerce-shopify-woocommerce-connector' ); ?>"></input>
							</td>
						</tr>

					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>
