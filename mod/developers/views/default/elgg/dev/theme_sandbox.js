define(['jquery', 'elgg', 'elgg/spinner'], function ($, elgg, spinner) {

	$('.theme-sandbox-content-spinner a').on('click', function () {
		spinner[ $(this).data('method') ]($(this).data('spinnerText'));
		return false;
	});

	$('#developers-system-message').click(function() {
		elgg.system_message('Elgg System Message');
	});

	$('#developers-error-message').click(function() {
		elgg.register_error('Elgg Error Message');
	});
});

