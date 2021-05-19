<?php
/**
 * Deprecation
 * Bundles helper functions for deprecation
 */

/**
 * Trigger an event normally, but send a notice about deprecated use if any handlers are registered.
 *
 * @param string $event       The event type
 * @param string $object_type The object type
 * @param string $object      The object involved in the event
 * @param string $message     The deprecation message
 * @param string $version     Human-readable *release* version: 1.9, 1.10, ...
 *
 * @return bool
 *
 * @see elgg_trigger_event()
 */
function elgg_trigger_deprecated_event($event, $object_type, $object = null, $message = null, $version = null) {
	return elgg()->events->triggerDeprecated($event, $object_type, $object, $message, $version);
}

/**
 * Trigger an plugin hook normally, but send a notice about deprecated use if any handlers are registered.
 *
 * @param string $hook        The name of the plugin hook
 * @param string $type        The type of the plugin hook
 * @param mixed  $params      Supplied params for the hook
 * @param mixed  $returnvalue The value of the hook, this can be altered by registered callbacks
 * @param string $message     The deprecation message
 * @param string $version     Human-readable *release* version: 1.9, 1.10, ...
 *
 * @return mixed
 *
 * @see elgg_trigger_plugin_hook()
 * @since 3.0
 */
function elgg_trigger_deprecated_plugin_hook($hook, $type, $params = null, $returnvalue = null, $message = null, $version = null) {
	return elgg()->hooks->triggerDeprecated($hook, $type, $params, $returnvalue, $message, $version);
}

/**
 * Log a notice about deprecated use of a function, view, etc.
 *
 * @param string $msg         Message to log
 * @param string $dep_version Human-readable *release* version: 1.7, 1.8, ...
 *
 * @return true
 * @since 1.7.0
 */
function elgg_deprecated_notice(string $msg, string $dep_version): bool {
	_elgg_services()->logger->warning("Deprecated in {$dep_version}: {$msg}");
	
	return true;
}

/**
 * Display a view with a deprecation notice. No missing view NOTICE is logged
 *
 * @param string $view       The name and location of the view to use
 * @param array  $vars       Variables to pass to the view
 * @param string $suggestion Suggestion with the deprecation message
 * @param string $version    Human-readable *release* version: 1.7, 1.8, ...
 *
 * @return string The parsed view
 *
 * @see elgg_view()
 */
function elgg_view_deprecated($view, array $vars, $suggestion, $version) {
	return _elgg_services()->views->renderDeprecatedView($view, $vars, $suggestion, $version);
}
