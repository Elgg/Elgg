define(['jquery', 'elgg', 'elgg/Ajax'], function($, elgg, Ajax) {
	var ajax = new Ajax();
	
	$(document).on('submit', '.elgg-form-login', function(e) {
		var $form = $(this);

		ajax.action($form.prop('action'), {
			data: ajax.objectify($form)
		}).done(function(json, status, jqXHR) {
			if (jqXHR.AjaxData.status == -1) {
				// remove the form and re-enable the /login link
				$('#login-dropdown-box').remove();
				$('#login-dropdown a').off('click');
				return;
			}

			if (json && (typeof json.forward === 'string')) {
				elgg.forward(json.forward);
			} else {
				elgg.forward();
			}
		});
		
		e.preventDefault();
	});
});
