/**
 * Remove before merge...
 */
define(function(require) {
	var ajax = require('elgg/ajax');
	var elgg = require('elgg');

	var log = console.log.bind(console);

	elgg.register_hook_handler(ajax.REQUEST_DATA_HOOK, 'all', function (name, type, params, returnValue) {
		log(arguments);
	});

	elgg.register_hook_handler(ajax.RESPONSE_DATA_HOOK, 'all', function (name, type, params, returnValue) {
		log(arguments);
	});

	ajax.fetchPath('activity')
		.then(function (html_page) {
			log(html_page); // "<doctype ...

			return ajax.fetchView('developers/gear_popup');
		})
		.then(function (div) {
			log(div); // "<div ...

			return ajax.fetchForm('login');
		})
		.then(function (form) {
			log(form); // "<form ...

			return ajax.performAction('ajax_example');
		})
		.then(function (obj) {
			log(obj); // {foo: bar}
		});
});