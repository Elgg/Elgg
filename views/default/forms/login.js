define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');
	var spinner = require('elgg/spinner');

	$(document).on('submit', '.elgg-form-login', function (e) {
		var $form = $(this);

		var ajax = new Ajax();

		ajax.action($form.prop('action'), {
			data: ajax.objectify($form)
		}).done(function (json, status, jqXHR) {
			if (jqXHR.AjaxData.status === -1) {
				$('input[name=password]', $form).val('').focus();
				return;
			}

			if (typeof jqXHR.AjaxData.forward_url === 'string') {
				ajax.forward(jqXHR.AjaxData.forward_url);
				return;
			} else if (jqXHR.AjaxData.forward_url === -1) {
				ajax.forward(location.href);
				return;
			}

			ajax.forward();
		});

		e.preventDefault();
	});
});

