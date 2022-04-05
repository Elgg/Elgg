define(['jquery', 'elgg/spinner', 'elgg/system_messages'], function ($, spinner, system_messages) {

	$('.theme-sandbox-content-spinner a').on('click', function () {
		spinner[ $(this).data('method') ]($(this).data('spinnerText'));
		return false;
	});

	$('#developers-system-message').click(function() {
		system_messages.success('Elgg System Message');
	});

	$('#developers-error-message').click(function() {
		system_messages.error('Elgg Error Message');
	});
});
