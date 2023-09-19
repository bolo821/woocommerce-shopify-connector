<?php

	// echo'<pre>';
	// print_r(get_option( 'ced_swc_webhook_IDs' ));
	// die;

	set_time_limit( 0 );
	wp_raise_memory_limit();

	// for products on shopify collection
	$selected_collection_products = get_option( 'ced_swc_selected_collection_products' );
	$selected_collection_products = is_array( $selected_collection_products ) ? $selected_collection_products : array();
	$get_option_values            = count( $selected_collection_products );

	// product already imported
	$store_products = get_posts(
		array(
			'numberposts'  => -1,
			'post_type'    => 'product',
			'post_status'  => array( 'publish', 'draft' ),
			'meta_key'     => 'ced_swc_shopifyItemId',
			'meta_value'   => '',
			'meta_compare' => '!=',
		)
	);

	$localItemID             = wp_list_pluck( $store_products, 'ID' );
	$products_imported_count = count( $localItemID );
	update_option( 'ToRenderImportedProductsCount', $products_imported_count );

	$get_option_value_product_imported = get_option( 'ToRenderImportedProductsCount', true );
	$ced_swc_details                   = get_option( 'ced_swc_config_details', array() );
	$shopifycollection_id              = isset( $ced_swc_details['ced_swc_shopifycollectionToimport'] ) ? $ced_swc_details['ced_swc_shopifycollectionToimport'] : '';
	$shopify_collection_name           = get_option( 'shopify_collection_name', true );

	$retrieve_list_of_locations = get_option( 'retrieve_list_of_locations', true );
	$selected_location_id       = get_option( 'selected_location_id', true );
	?>

<div class="ced-swc-header ced-swc-configuration-heading-wrapper">

	<div class="ced-swc-logo">
		<img src="<?php echo esc_attr( CED_SWC_URL ) . 'admin/images/icon-100X100.png'; ?>">
	</div>

	<div class="ced-swc-header-content">

		<div class="ced-swc-title">
			<h1>All set, product settings are successfully set up on WooCommerce Store.</h1>
		</div>

	</div>

</div>

<div class="ced-swc-content-wrapper-content">
	
	<div class="ced-swc-heading-wrapper ced-swc-configuration-heading-wrapper">
		<h2 id="unique_to_run_ajax_AndGetAllLocations"><?php esc_html_e( 'Additional Information', 'cedcommerce-shopify-woocommerce-connector' ); ?></h2>
	</div>

	<div class="ced_shopify_connector_loader">
		<img src="<?php echo esc_url( CED_SWC_URL . 'admin/images/loading.gif' ); ?>" width="50px" height="50px" class="ced_shopify_loading_img" >
	</div>

	<div class="ced-swc-final-setup ced-swc-configuration-final-setup">
		<div class="ced-swc-final-setup-table-wrapper">
			<table class="ced-swc-configuration-settings-table">

			
				<tbody>
					<tr>
						<td>
							<label><?php esc_html_e( 'Collections Selected', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
						</td>

						<td>
							<div type="text" class="render_product_count" >  
								<?php

								$collection_names = array();
								if ( ! empty( $shopifycollection_id ) ) {
									foreach ( $shopifycollection_id as $key => $value ) {
										foreach ( $shopify_collection_name as $collection_id => $collection_name ) {
											if ( $value == $collection_id ) {
												$collection_names[] = $collection_name;
											}
										}
									}

									if ( ! empty( $collection_names ) ) {
										print_r( implode( ', ', $collection_names ) );
									}
								} else {
									echo '<p style="font-size: 20px;">Collection Not Selected</p>';
								}
								?>
								  
							</div>
						</td>
					</tr>
				</tbody>


				<tbody>
					<tr>
						<td>
							<label><?php esc_html_e( 'Products count in a selected collection', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
						</td>

						<td>
							<div type="text" id="render_count_belong_to_selection" class="render_product_count" >  
								<?php
								if ( empty( $shopifycollection_id ) ) {
									echo '0 As No Collection Selected';
								} else {
									echo isset( $get_option_values ) ? 'Total count is: ' . esc_attr( $get_option_values ) : '';
								}
								?>
							</div>
						</td>
					</tr>
				</tbody>


				<tbody>
					<tr>
						<td>
							<label><?php esc_html_e( 'Get Count of Product Imported', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
						</td>

						<td>
							<div type="text" class="render_product_count" >  
								<?php echo isset( $get_option_value_product_imported ) ? esc_attr( 'Product Imported Counts: ' . $get_option_value_product_imported ) : ''; ?>    
							</div>
						</td>
					</tr>
				</tbody>

			</table>
		</div>
	</div>

	
	<div class="ced-swc-heading-wrapper-additional-setting">
		<span id="additional_setting"><?php esc_html_e( 'Additional Settings', 'cedcommerce-shopify-woocommerce-connector' ); ?></span>
		<div class="tooltip">
			<i class="fas fa-info-circle fa-1x"></i>
			<span class="tooltiptext"> This is to manage stock on shopify store to do so please fetch and select your shopify store location </span>
		</div>
	</div>

	<div class="ced-swc-final-setup ced-swc-configuration-final-setup">
		<div class="ced-swc-final-setup-table-wrapper">
			<table class="ced-swc-configuration-settings-table">
				<tbody>
					<tr>
						<td>
							<label><?php esc_html_e( 'Select store location', 'cedcommerce-shopify-woocommerce-connector' ); ?></label>
						</td>

						<td>
							<?php
							if ( ! is_array( $retrieve_list_of_locations ) ) {
								$button_html = '<button id="get_location_list" class="get_location_button"> <span> Get Location </span> </button>';
								print_r( $button_html );
							} else {
								?>
										<label>
											<select id="select_from_list_of_location">
												<option>Select</option>
											<?php foreach ( $retrieve_list_of_locations as $key => $location ) : ?>
													<option value="<?php echo esc_attr( $location['id'] ); ?>"
														<?php
														if ( isset( $selected_location_id ) ) {
															if ( $location['id'] == $selected_location_id ) {
																echo 'selected';
															}
														}
														?>
														 >
													<?php echo esc_attr( $location['name'] ); ?> </option>
												<?php endforeach ?>
											</select>
										</label>
									<?php
							}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>


	<tr class="ced-swc-configuration-reset">
		<form method="post">
			<td>
				<input type="submit" name="ced_swc_goto_previous_configuration_details_step_3" class="ced-swc-save-configuration-button ced-swc-nav-to-previous" value="<?php esc_html_e( 'Previous', 'cedcommerce-shopify-woocommerce-connector' ); ?>"></input>
			</td>

			<td>
			<input type="hidden" name="ced_swc_config_details[config_setup_completed]" value="4">
				<?php wp_nonce_field( 'ced_sWc_setting_page_nonce', 'ced_sWc_setting_nonce' ); ?>
				<input type="submit" name="ced_swc_reset_configuration_details" class="ced-swc-save-configuration-button ced-swc-nav-to-next" value="<?php esc_html_e( 'Reset', 'cedcommerce-shopify-woocommerce-connector' ); ?>"></input>
			</td>
		</form>
	</tr>
</div>


	
