jQuery( document ).on(
	'click',
	'#get_location_list',
	function(){
		jQuery( '.ced_shopify_connector_loader' ).show();
		jQuery.ajax(
			{
				url : Ced_Shopify_connector_action_handler.ajax_url,
				type : 'post',
				data : {
					action : 'get_list_of_all_location',
				},
				success : function(response) {
					console.log( response );
					jQuery( '.ced_shopify_connector_loader' ).hide();
					location.reload();
				}
			}
		)
	}
);

jQuery( document ).change(
	'#select_from_list_of_location',
	function() {

		jQuery( '.ced_shopify_connector_loader' ).show();

		var text  = jQuery( "#select_from_list_of_location option:selected" ).text();
		var value = jQuery( "#select_from_list_of_location" ).val();

		jQuery.ajax(
			{
				url : Ced_Shopify_connector_action_handler.ajax_url,
				type : 'post',
				data : {
					text: text,
					value: value,
					action: 'saving_selected_location_option_on_ajax',
				},
				success : function(response) {
					console.log( response );
					jQuery( '.ced_shopify_connector_loader' ).hide();
				}
			}
		)
	}
);
