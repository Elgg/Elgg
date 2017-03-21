define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');
	var spinner = require('elgg/spinner');

	// manage Spinner manually
	var ajax = new Ajax(false);

	$(document).on('submit', '.elgg-form-login', function(e) {
		var $form = $(this);

		spinner.start();
		ajax.action($form.prop('action'), {
			data: ajax.objectify($form)
		}).done(function(json, status, jqXHR) {
			if (jqXHR.AjaxData.status == -1) {
				$('input[name=password]', $form).val('').focus();
				spinner.stop();
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

