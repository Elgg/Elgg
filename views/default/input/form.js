define(['jquery', 'elgg', 'elgg/Ajax'], function ($, elgg, Ajax) {

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
			if ($form.is('.elgg-form-login')) {
				$('input[name=password]', $form).val('').focus();
			}
		});

		e.preventDefault();
	});
	
	$(document).on('submit', '.elgg-form-prevent-double-submit', function (e) {
		$(this).find('button[type="submit"]').prop('disabled', true);
	});
});

