define(function(require) {
	var Ajax = require('elgg/Ajax');
	var elgg = require('elgg');

	var ajax = new Ajax();
	var log = console.log.bind(console);

	// log data passed through all hooks
	//elgg.register_hook_handler(Ajax.REQUEST_DATA_HOOK, 'all', function (name, type, params, value) {
	//	log(arguments);
	//});
	//elgg.register_hook_handler(Ajax.RESPONSE_DATA_HOOK, 'all', function (name, type, params, value) {
	//	log(arguments);
	//});

	// alter request data for the action
	elgg.register_hook_handler(
		Ajax.REQUEST_DATA_HOOK,
		'action:developers/ajax_demo',
		function (name, type, params, value) {
			value.client_request_altered = 1;
			return value;
		}
	);

	// alter request data response for the action
	elgg.register_hook_handler(
		Ajax.RESPONSE_DATA_HOOK,
		'action:developers/ajax_demo',
		function (name, type, params, value) {
			value.client_response_altered = 3;
			return value;
		}
	);

	// we make 4 successive ajax calls, here chained together by Promises

	ajax.path('developers_ajax_demo')
		.then(function (html_page) {
			if (html_page.indexOf('path demo') != -1) {
				log("path() successful!");

				return ajax.view('developers/ajax_demo.html');
			}
		})
		.then(function (div) {
			if (div.indexOf('view demo') != -1) {
				log("view() successful!");

				return ajax.form('developers/ajax_demo');
			}
		})
		.then(function (form) {
			if (form.indexOf('form demo') != -1) {
				log("form() successful!");

				return ajax.action('developers/ajax_demo', {
					data: {arg1: 2, arg2: 3}
				});
			}
		})
		.then(function (obj) {
			if (obj.sum === 5
					&& obj.server_response_altered == 2
					&& obj.client_response_altered == 3) {
				log("action() successful!");
				alert('Success!');
			}
		});
});