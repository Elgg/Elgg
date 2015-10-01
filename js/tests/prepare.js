// These modules are typically built by PHP on the server. We can't do that with the test runner.
var elgg = elgg || {};

define('elgg', function() {
	return elgg;
});
define('elgg/hooks/register', function () {
	return elgg._register_hook_handler;
});