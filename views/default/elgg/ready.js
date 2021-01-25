/**
 * Trigger the [ready, system] hook
 *
 * Depend on this module to guarantee all [ready, system] handlers have been called
 *
 */
define(['elgg', 'elgg/init'], function(elgg) {
	elgg.trigger_hook('ready', 'system');
});
