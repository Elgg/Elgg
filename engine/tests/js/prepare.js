// These modules are typically built in PHP. We can't do that with the test runner.

var elgg = elgg || {};

elgg.config = elgg.config || {};

elgg.config.wwwroot = 'http://www.elgg.org/';
elgg.config.current_language = 'en';

elgg.security = elgg.security || {};
elgg.security.interval = (24 * 60 * 60 * 1000); // make sure during tests the refresh interval does not trigger

define('elgg', function() {
	return elgg;
});
