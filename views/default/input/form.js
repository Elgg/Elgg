define(function (require) {
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');

	$(document).on('submit', '.elgg-js-ajax-form', function (e) {
		var $form = $(this);

		var ajax = new Ajax();

		ajax.action($form.prop('action'), {
			data: ajax.objectify($form),
			beforeSend: function() {
				$form.find('[type="submit"]').prop('disabled', true);
				elgg.clear_system_messages();
			}
		}).done(function (json, status, jqXHR) {
			if (jqXHR.AjaxData.status === -1) {
				$form.find('[type="submit"]').prop('disabled', false);
				if ($form.is('.elgg-form-login')) {
					$('input[name=password]', $form).val('').focus();
				}
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
		}).fail(function() {
			$form.find('[type="submit"]').prop('disabled', false);
		});

		e.preventDefault();
	});
});

