// test Ajax feature by executing 'import("theme_sandbox/demo/ajax_demo");' in your browser console.

import hooks from 'elgg/hooks';
import Ajax from 'elgg/Ajax';

var ajax = new Ajax();
var log = console.log.bind(console);

// alter request data for the action
hooks.register(
	Ajax.REQUEST_DATA_HOOK,
	'action:theme_sandbox/demo/ajax_demo',
	function (name, type, params, value) {
		// alter the data object sent to server
		value.client_request_altered = 1;
		return value;
	}
);

var got_metadata_from_server = false;

log("Expecting 4 passes...");

// alter request data response for the action
hooks.register(
	Ajax.RESPONSE_DATA_HOOK,
	'action:theme_sandbox/demo/ajax_demo',
	function (name, type, params, data) {
		// check the data wrapper for our expected metadata
		if (data.server_response_altered) {
			got_metadata_from_server = true;
		}

		// alter the return value
		data.value.altered_value = true;

		return data;
	}
);

// we make 4 successive ajax calls, here chained together by Promises

ajax.path('theme_sandbox_ajax_demo')
	.then(function (html_page) {
		if (html_page.indexOf('path demo') != -1) {
			log("PASS path()");

			return ajax.view('theme_sandbox/demo/ajax_demo.html', {
				data: {guid: 1}
			});
		}
	})
	.then(function (div) {
		if (div.indexOf('view demo') != -1) {
			log("PASS view()");

			return ajax.form('theme_sandbox/ajax_demo', {
				data: {guid: 1}
			});
		}
	})
	.then(function (form) {
		if (form.indexOf('form demo') != -1) {
			log("PASS form()");

			return ajax.action('theme_sandbox/ajax_demo', {
				data: {arg1: 2, arg2: 3},
				success: function (obj) {
					// we should not get two sets of system messages
				}
			});
		}
	})
	.then(function (obj) {
		if (obj.sum === 5) {
			log("PASS action()");
		}
		
		if (got_metadata_from_server) {
			log("PASS got metadata from server response event");
		}
		
		if (obj.altered_value) {
			log("PASS client response event altered value");
		}

		alert('Success!');
	});
