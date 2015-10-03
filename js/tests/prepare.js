// These modules are typically built by PHP on the server. We can't do that with the test runner.
var elgg = elgg || {};

elgg.config = elgg.config || {};

elgg.config.wwwroot = 'http://www.elgg.org/';

define('elgg', function() {
	return elgg;
});

// for ElggHooksTest.js
define('boot/example', function(require) {
	var elgg = require('elgg');
	var Plugin = require('elgg/Plugin');

	elgg._test_signals.push('boot/example define');

	elgg.register_hook_handler('init', 'system', function() {
		elgg._test_signals.push('boot/example init,system');
	});
	elgg.register_hook_handler('ready', 'system', function() {
		elgg._test_signals.push('boot/example ready,system');
	});

	return new Plugin({
		init: function () {
			elgg._test_signals.push('boot/example init');
		}
	});
});

// for ElggHooksTest.js
define('elgg/init', function (require) {
	var elgg = require('elgg');
	var plugin = require('boot/example');

	plugin._init();

	elgg.trigger_hook('init', 'system');
});

define('languages/early/en', {
	"js:lightbox:current": "image %s of %s",
	"next": "Next"
});
define('languages/late/en', {
	"ajax:error": "Unexpected error"
});
define('languages/early/es', {
	//'js:lightbox:current': "imagen %s de %s",
	'next': "Siguiente"
});
define('languages/late/es', {
	"ajax:error": "Error inesperado"
});
