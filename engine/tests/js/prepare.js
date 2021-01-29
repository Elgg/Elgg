// These modules are typically built in PHP. We can't do that with the test runner.

var elgg = elgg || {};

elgg.config = elgg.config || {};

elgg.config.wwwroot = 'http://www.elgg.org/';
elgg.config.current_language = 'en';

define('elgg', function() {
	return elgg;
});
