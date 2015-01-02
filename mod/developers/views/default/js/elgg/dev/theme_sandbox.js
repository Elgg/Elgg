define(function (require) {
	var Spinner = require('elgg/Spinner'),
		$ = require('jquery'),
		elgg = require('elgg');

	var spinner = new Spinner({
		$wait: $('body')
	});

	$('.theme-sandbox-content-spinner a').on('click', function () {
		spinner[ $(this).data('method') ]();
		return false;
	});

	$('#developers-system-message').click(function() {
		elgg.system_message('Elgg System Message');
	});

	$('#developers-error-message').click(function() {
		elgg.register_error('Elgg Error Message');
	});
});
