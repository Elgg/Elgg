/**
 * Trigger the [ready, system] hook
 *
 * Depend on this module to guarantee all [ready, system] handlers have been called
 *
 */
define(function(require) {
	var elgg = require('elgg');
	require('elgg/init');

	elgg.trigger_hook('ready', 'system');
});

