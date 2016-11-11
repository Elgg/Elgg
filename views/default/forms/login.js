define(['jquery', 'elgg', 'elgg/Ajax'], function($, elgg, Ajax) {
	var ajax = new Ajax();
	
	$(document).on('submit', '.elgg-form-login', function(e) {
		var $form = $(this);

		ajax.action($form.prop('action'), {
			data: ajax.objectify($form)
		}).success(function(json, status, xhr) {
			if (typeof json.forward !== 'undefined') {
				elgg.forward(json.forward);
			} else if (json === '') {
				// BC fallback if action did not return a forward url
				// elgg_ok_response will have the forward url
				// elgg_error_response will have the error text
				// everything else is unknown, so forward
				elgg.forward();				
			}
		});
		
		e.preventDefault();
	});
});
