define(['jquery', 'elgg/Ajax', 'elgg/system_messages'], function ($, Ajax, system_messages) {

	$(document).on('submit', '.elgg-js-ajax-form', function (e) {
		e.preventDefault();
		
		var $form = $(this);
		var ajax = new Ajax();

		ajax.action($form.prop('action'), {
			data: ajax.objectify($form),
			beforeSend: function() {
				$form.find('[type="submit"]').prop('disabled', true);
				system_messages.clear();
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
	});
	
	$(document).on('submit', '.elgg-form-prevent-double-submit', function (e) {
		$(this).find('button[type="submit"]').prop('disabled', true);
	});
});
