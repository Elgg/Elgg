<?php
/**
 * Helper functions for event handling
 */

/**
 * Register a callback as an Elgg event handler.
 *
 * Events are emitted by Elgg when certain actions occur. Plugins
 * can respond to these events or halt them completely by registering a handler
 * as a callback to an event. Multiple handlers can be registered for
 * the same event and will be executed in order of $priority.
 *
 * For most events, any handler returning false will halt the execution chain and
 * cause the event to be "cancelled". For After Events, the return values of the
 * handlers will be ignored and all handlers will be called.
 *
 * This function is called with the event name, event type, and handler callback name.
 * Setting the optional $priority allows plugin authors to specify when the
 * callback should be run. Priorities for plugins should be 1-1000.
 *
 * @tip If a priority isn't specified it is determined by the order the handler was
 * registered relative to the event and type. For plugins, this generally means
 * the earlier the plugin is in the load order, the earlier the priorities are for
 * any event handlers.
 *
 * @tip $event and $type can use the special keyword 'all'. Handler callbacks registered
 * with $event = all will be called for all events of type $type. Similarly,
 * callbacks registered with $type = all will be called for all events of type
 * $event, regardless of $type. If $event and $type both are 'all', the
 * handler callback will be called for all events.
 *
 * @tip Event handler callbacks are considered in the follow order:
 *  - Specific registration where 'all' isn't used.
 *  - Registration where 'all' is used for $event only.
 *  - Registration where 'all' is used for $type only.
 *  - Registration where 'all' is used for both.
 *
 * @tip When referring to events, the preferred syntax is "event, type".
 *
 * @param string   $event    The event name
 * @param string   $type     The event type
 * @param callable $callback The handler callback
 * @param int      $priority The priority - 500 is default, negative before, positive after
 *
 * @return void
 */
function elgg_register_event_handler(string $event, string $type, callable|string $callback, int $priority = 500): void {
	_elgg_services()->events->registerHandler($event, $type, $callback, $priority);
}

/**
 * Unregisters a callback for an event.
 *
 * @param string   $event    The event name
 * @param string   $type     The event type
 * @param callable $callback The callback
 *
 * @return void
 * @since 1.7
 */
function elgg_unregister_event_handler(string $event, string $type, callable|string $callback): void {
	_elgg_services()->events->unregisterHandler($event, $type, $callback);
}

/**
 * Clears all callback registrations for a event.
 *
 * @param string $event The name of the event
 * @param string $type  The type of the event
 *
 * @return void
 * @since 2.3
 */
function elgg_clear_event_handlers(string $event, string $type): void {
	_elgg_services()->events->clearHandlers($event, $type);
}

/**
 * Trigger an Elgg Event and attempt to run all handler callbacks registered to that
 * event, type.
 *
 * This function attempts to run all handlers registered to $event, $type or
 * the special keyword 'all' for either or both. If a handler returns false, the
 * event will be cancelled (no further handlers will be called, and this function
 * will return false).
 *
 * $event is usually a verb: create, update, delete, annotate.
 *
 * $type is usually a noun: object, group, user, annotation, relationship, metadata.
 *
 * $object is usually an Elgg* object associated with the event.
 *
 * @tip When referring to events, the preferred syntax is "event, type".
 *
 * @param string $event  The event name
 * @param string $type   The event type
 * @param mixed  $object The object involved in the event
 *
 * @return bool False if any handler returned false, otherwise true.
 */
function elgg_trigger_event(string $event, string $type, $object = null): bool {
	return _elgg_services()->events->trigger($event, $type, $object);
}

/**
 * Triggers an event where it is expected that the mixed return value could be manipulated by event callbacks
 *
 * @param string $event       The event name
 * @param string $type        The event type
 * @param array  $params      Parameters useful for the callbacks
 * @param mixed  $returnvalue Default returnvalue
 *
 * @return mixed
 * @since 5.0
 */
function elgg_trigger_event_results(string $event, string $type, array $params = [], $returnvalue = null) {
	return _elgg_services()->events->triggerResults($event, $type, $params, $returnvalue);
}

/**
 * Trigger a "Before event" indicating a process is about to begin.
 *
 * Like regular events, a handler returning false will cancel the process and false
 * will be returned.
 *
 * To register for a before event, append ":before" to the event name when registering.
 *
 * @param string $event  The event name. The fired event name will be appended with ":before".
 * @param string $type   The event type
 * @param mixed  $object The object involved in the event
 *
 * @return bool False if any handler returned false, otherwise true
 *
 * @see elgg_trigger_event()
 * @see elgg_trigger_after_event()
 */
function elgg_trigger_before_event(string $event, string $type, $object = null): bool {
	return _elgg_services()->events->triggerBefore($event, $type, $object);
}

/**
 * Trigger an "After event" indicating a process has finished.
 *
 * Unlike regular events, all the handlers will be called, their return values ignored.
 *
 * To register for an after event, append ":after" to the event name when registering.
 *
 * @param string $event  The event name. The fired event name will be appended with ":after".
 * @param string $type   The event type
 * @param mixed  $object The object involved in the event
 *
 * @return void
 *
 * @see elgg_trigger_before_event()
 */
function elgg_trigger_after_event(string $event, string $type, $object = null): void {
	_elgg_services()->events->triggerAfter($event, $type, $object);
}

/**
 * Trigger an event normally, but send a notice about deprecated use if any handlers are registered.
 *
 * @param string $event   The event name
 * @param string $type    The event type
 * @param mixed  $object  The object involved in the event
 * @param string $message The deprecation message
 * @param string $version Human-readable *release* version: 1.9, 1.10, ...
 *
 * @return bool
 *
 * @see elgg_trigger_event()
 */
function elgg_trigger_deprecated_event(string $event, string $type, $object = null, string $message = '', string $version = '') {
	return _elgg_services()->events->triggerDeprecated($event, $type, $object, $message, $version);
}

/**
 * Triggers a deprecated event where it is expected that the mixed return value could be manipulated by event callbacks
 *
 * @param string $event       The event name
 * @param string $type        The event type
 * @param array  $params      Parameters useful for the callbacks
 * @param mixed  $returnvalue Default returnvalue
 * @param string $message     The deprecation message
 * @param string $version     Human-readable *release* version: 1.9, 1.10, ...
 *
 * @return mixed
 *
 * @since 5.0
 */
function elgg_trigger_deprecated_event_results(string $event, string $type, array $params = [], $returnvalue = null, string $message = '', string $version = '') {
	return _elgg_services()->events->triggerDeprecatedResults($event, $type, $params, $returnvalue, $message, $version);
}
