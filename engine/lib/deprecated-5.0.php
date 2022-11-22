<?php
/**
 * Bundle all functions which have been deprecated in Elgg 5.0
 */

/**
 * Register a callback as a plugin hook handler.
 *
 * Plugin hooks allow developers to losely couple plugins and features by
 * responding to and emitting {@link elgg_trigger_plugin_hook()} customizable hooks.
 * Handler callbacks can respond to the hook, change the details of the hook, or
 * ignore it.
 *
 * Multiple handlers can be registered for a plugin hook, and each callback
 * is called in order of priority.  If the return value of a handler is not
 * null, that value is passed to the next callback in the call stack.  When all
 * callbacks have been run, the final value is passed back to the caller
 * via {@link elgg_trigger_plugin_hook()}.
 *
 * Similar to Elgg Events, plugin hook handler callbacks are registered by passing
 * a hook, a type, and a priority.
 *
 * The callback is passed 4 arguments when called: $hook, $type, $value, and $params.
 *
 *  - str $hook The name of the hook.
 *  - str $type The type of hook.
 *  - mixed $value The return value of the last handler or the default
 *  value if no other handlers have been called.
 *  - mixed $params An optional array of parameters.  Used to provide additional
 *  information to plugins.
 *
 * @tip Plugin hooks are similar to Elgg Events in that Elgg emits
 * a plugin hook when certain actions occur, but a plugin hook allows you to alter the
 * parameters, as well as halt execution.
 *
 * @tip If a priority isn't specified it is determined by the order the handler was
 * registered relative to the event and type.  For plugins, this generally means
 * the earlier the plugin is in the load order, the earlier the priorities are for
 * any event handlers.
 *
 * @tip Like Elgg Events, $hook and $type can use the special keyword 'all'.
 * Handler callbacks registered with $hook = all will be called for all hooks
 * of type $type.  Similarly, handlers registered with $type = all will be
 * called for all hooks of type $event, regardless of $object_type.  If $hook
 * and $type both are 'all', the handler will be called for all hooks.
 *
 * @tip Plugin hooks are sometimes used to gather lists from plugins.  This is
 * usually done by pushing elements into an array passed in $params.  Be sure
 * to append to and then return $value so you don't overwrite other plugin's
 * values.
 *
 * @warning Unlike Elgg Events, a handler that returns false will NOT halt the
 * execution chain.
 *
 * @param string   $hook     The name of the hook
 * @param string   $type     The type of the hook
 * @param callable $callback The name of a valid function or an array with object and method
 * @param int      $priority The priority - 500 is default, lower numbers called first
 *
 * @return bool
 *
 * @example hooks/register/basic.php Registering for a plugin hook and examining the variables.
 * @example hooks/register/advanced.php Registering for a plugin hook and changing the params.
 * @since 1.8.0
 * @deprecated 5.0 use elgg_register_event_handler()
 */
function elgg_register_plugin_hook_handler($hook, $type, $callback, $priority = 500) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_register_event_handler()', '5.0');
	
	return elgg_register_event_handler((string) $hook, (string) $type, $callback, (int) $priority);
}

/**
 * Unregister a callback as a plugin hook.
 *
 * @param string   $hook        The name of the hook
 * @param string   $entity_type The name of the type of entity (eg "user", "object" etc)
 * @param callable $callback    The PHP callback to be removed. Since 1.11, static method
 *                              callbacks will match dynamic methods
 *
 * @return void
 * @since 1.8.0
 * @deprecated 5.0 use elgg_unregister_event_handler()
 */
function elgg_unregister_plugin_hook_handler($hook, $entity_type, $callback) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_unregister_event_handler()', '5.0');
	
	elgg_unregister_event_handler((string) $hook, (string) $entity_type, $callback);
}

/**
 * Clears all callback registrations for a plugin hook.
 *
 * @param string $hook The name of the hook
 * @param string $type The type of the hook
 *
 * @return void
 * @since 2.0
 * @deprecated 5.0 use elgg_clear_event_handlers()
 */
function elgg_clear_plugin_hook_handlers($hook, $type) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_clear_event_handlers()', '5.0');
	
	elgg_clear_event_handlers((string) $hook, (string) $type);
}

/**
 * Trigger a Plugin Hook and run all handler callbacks registered to that hook:type.
 *
 * This function runs all handlers registered to $hook, $type or
 * the special keyword 'all' for either or both.
 *
 * Use $params to send additional information to the handler callbacks.
 *
 * $returnvalue is the initial value to pass to the handlers, which can
 * change it by returning non-null values. It is useful to use $returnvalue
 * to set defaults. If no handlers are registered, $returnvalue is immediately
 * returned.
 *
 * Handlers that return null (or with no explicit return or return value) will
 * not change the value of $returnvalue.
 *
 * $hook is usually a verb: import, register, output.
 *
 * $type is usually a noun: user, menu:site, page.
 *
 * @tip Like Elgg Events, $hook and $type can use the special keyword 'all'.
 * Handler callbacks registered with $hook = all will be called for all hooks
 * of type $type.  Similarly, handlers registered with $type = all will be
 * called for all hooks of type $event, regardless of $object_type.  If $hook
 * and $type both are 'all', the handler will be called for all hooks.
 *
 * @tip It's not possible for a plugin hook to change a non-null $returnvalue
 * to null.
 *
 * @note Internal: The checks for $hook and/or $type not being equal to 'all' is to
 * prevent a plugin hook being registered with an 'all' being called more than
 * once if the trigger occurs with an 'all'. An example in core of this is in
 * actions.php:
 * elgg_trigger_plugin_hook('action_gatekeeper:permissions:check', 'all', ...)
 *
 * @see elgg_register_plugin_hook_handler()
 *
 * @param string $hook        The name of the hook to trigger ("all" will
 *                            trigger for all $types regardless of $hook value)
 * @param string $type        The type of the hook to trigger ("all" will
 *                            trigger for all $hooks regardless of $type value)
 * @param mixed  $params      Additional parameters to pass to the handlers
 * @param mixed  $returnvalue An initial return value
 *
 * @return mixed|null The return value of the last handler callback called
 *
 * @example hooks/trigger/basic.php    Trigger a hook that determines if execution
 *                                     should continue.
 * @example hooks/trigger/advanced.php Trigger a hook with a default value and use
 *                                     the results to populate a menu.
 * @example hooks/basic.php            Trigger and respond to a basic plugin hook.
 *
 * @since 1.8.0
 * @deprecated 5.0 use elgg_trigger_event_results()
 */
function elgg_trigger_plugin_hook($hook, $type, $params = null, $returnvalue = null) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_trigger_event_results()', '5.0');
	
	$params = $params ?? [];
	return elgg_trigger_event_results((string) $hook, (string) $type, $params, $returnvalue);
}

/**
 * Get user by username
 *
 * @param string $username The user's username
 *
 * @return \ElggUser|false Depending on success
 * @deprecated 5.0 use elgg_get_user_by_username()
 */
function get_user_by_username($username) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_get_user_by_username()', '5.0');
	
	return elgg_get_user_by_username((string) $username);
}

/**
 * Get an array of users from an email address
 *
 * @param string $email Email address.
 *
 * @return array
 * @deprecated 5.0 use elgg_get_user_by_email()
 */
function get_user_by_email($email) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_get_user_by_email()', '5.0');
	
	return (array) elgg_get_user_by_email((string) $email);
}
