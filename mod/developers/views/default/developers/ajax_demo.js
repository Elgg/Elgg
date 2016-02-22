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
			// alter the data object sent to server
			value.client_request_altered = 1;
			return value;
		}
	);

	var got_metadata_from_server = false,
		num_hook_calls = 0;

	// alter request data response for the action
	elgg.register_hook_handler(
		Ajax.RESPONSE_DATA_HOOK,
		'action:developers/ajax_demo',
		function (name, type, params, data) {
			// check the data wrapper for our expected metadata
			if (data.server_response_altered) {
				got_metadata_from_server = true;
			}

			// alter the return value
			data.value.altered_value = true;

			num_hook_calls++;

			return data;
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
					data: {arg1: 2, arg2: 3},
					success: function (obj) {
						// we should not get two sets of system messages
					}
				});
			}
		})
		.then(function (obj) {
			if (obj.sum === 5
					&& got_metadata_from_server
					&& obj.altered_value
					&& num_hook_calls == 1) {
				log("action() successful!");
				alert('Success!');
			}
		});
});