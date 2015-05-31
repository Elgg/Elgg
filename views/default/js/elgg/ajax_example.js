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

	ajax.fetch({
		endpoint: 'elgg.hello',
		data: {
			name: 'William'
		}
	});
});