<?php
	do_action( 'getCollectionfromStore' );
	
	$ced_swc_details     = get_option( 'ced_swc_config_details', array() );
	$collections         = get_option( 'shopify_collection_name' );
	$selected_collection = isset( $ced_swc_details['ced_swc_shopifycollectionToimport'] ) ? $ced_swc_details['ced_swc_shopifycollectionToimport'] : '';
?>

<div class="ced-swc-header ced-swc-configuration-heading-wrapper">

	<div class="ced-swc-logo">
		<img src="<?php echo esc_attr( CED_SWC_URL ) . 'admin/images/icon-100X100.png'; ?>">
	</div>

	<div class="ced-swc-header-content">

		<div class="ced-swc-title">
			<h1>Step 2: Collections List To Import</h1>
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
								<label><?php esc_html_e( 'Select Collections to import', 'woocommerce-g2a-importer' ); ?></label>
								<div class="tooltip">
									<i class="fas fa-info-circle fa-1x"></i>
									<span class="tooltiptext"> Select collections from list to import. To select multiple collections press and hold 'Ctrl' key </span>
								</div>
							</td>
							<td>
								<select class="select2_multi_select_box" multiple="multiple" name=ced_swc_config_details[ced_swc_shopifycollectionToimport][] >
									<?php foreach ( $collections as $key => $value ) : ?>
										<option value="<?php echo esc_attr( $key ); ?>" 
											<?php
											if ( is_array( $selected_collection ) && ! empty( is_array( $selected_collection ) ) ) {
												if ( in_array( $key, $selected_collection ) ) {
													echo 'selected';
												}
											}
											?>
										><?php echo esc_attr( $value ); ?></option>
									<?php endforeach ?>
								</select>
							</td>
						</tr>

						<tr class="ced-swc-save-config-row">
							<?php wp_nonce_field( 'ced_sWc_setting_page_nonce', 'ced_sWc_setting_nonce' ); ?>
							<td>
								<input type="submit" name="ced_swc_goto_previous_configuration_details_step_1" class="ced-swc-save-configuration-button ced-swc-nav-to-previous" value="<?php esc_html_e( 'Previous', 'cedcommerce-shopify-woocommerce-connector' ); ?>"></input>
							</td>
							<td>
								<input type="hidden" name="ced_swc_config_details[config_setup_completed]" value="2">
								<input type="submit" name="ced_swc_save_configuration_details_step_2" class="ced-swc-save-configuration-button ced-swc-nav-to-next" value="<?php esc_html_e( 'Next', 'cedcommerce-shopify-woocommerce-connector' ); ?>"></input>
							</td>
						</tr>

					</tbody>
				</table>
			</form>
		</div>
	</div>

</div>
