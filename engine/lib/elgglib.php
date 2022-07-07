<?php
/**
 * Bootstrapping and helper procedural code available for use in Elgg core and plugins.
 */

/**
 * Get a reference to the public service provider
 *
 * @return \Elgg\Di\PublicContainer
 * @since 2.0.0
 */
function elgg() {
	return \Elgg\Application::$_instance->public_services;
}

/**
 * Set a response HTTP header
 *
 * @see header()
 *
 * @param string $header  Header
 * @param bool   $replace Replace existing header
 * @return void
 * @since 2.3
 */
function elgg_set_http_header($header, $replace = true) {
	if (!preg_match('~^HTTP/\\d\\.\\d~', $header)) {
		list($name, $value) = explode(':', $header, 2);
		_elgg_services()->responseFactory->setHeader($name, ltrim($value), $replace);
	}
}

/**
 * Registers a success system message
 *
 * @param string|array $options a single string or an array of system message options
 *
 * @see \ElggSystemMessage::factory()
 *
 * @return void
 * @since 4.2
 */
function elgg_register_success_message($options): void {
	if (!is_array($options)) {
		$options = ['message' => $options];
	}
	
	$options['type'] = 'success';
	_elgg_services()->system_messages->addMessage($options);
}

/**
 * Registers a error system message
 *
 * @param string|array $options a single string or an array of system message options
 *
 * @see \ElggSystemMessage::factory()
 *
 * @return void
 * @since 4.2
 */
function elgg_register_error_message($options): void {
	if (!is_array($options)) {
		$options = ['message' => $options];
	}
	
	$options['type'] = 'error';
	_elgg_services()->system_messages->addMessage($options);
}

/**
 * Register a callback as an Elgg event handler.
 *
 * Events are emitted by Elgg when certain actions occur.  Plugins
 * can respond to these events or halt them completely by registering a handler
 * as a callback to an event.  Multiple handlers can be registered for
 * the same event and will be executed in order of $priority.
 *
 * For most events, any handler returning false will halt the execution chain and
 * cause the event to be "cancelled". For After Events, the return values of the
 * handlers will be ignored and all handlers will be called.
 *
 * This function is called with the event name, event type, and handler callback name.
 * Setting the optional $priority allows plugin authors to specify when the
 * callback should be run.  Priorities for plugins should be 1-1000.
 *
 * The callback is passed 3 arguments when called: $event, $type, and optional $params.
 *
 * $event is the name of event being emitted.
 * $type is the type of event or object concerned.
 * $params is an optional parameter passed that can include a related object.  See
 * specific event documentation for details on which events pass what parameteres.
 *
 * @tip If a priority isn't specified it is determined by the order the handler was
 * registered relative to the event and type.  For plugins, this generally means
 * the earlier the plugin is in the load order, the earlier the priorities are for
 * any event handlers.
 *
 * @tip $event and $object_type can use the special keyword 'all'.  Handler callbacks registered
 * with $event = all will be called for all events of type $object_type.  Similarly,
 * callbacks registered with $object_type = all will be called for all events of type
 * $event, regardless of $object_type.  If $event and $object_type both are 'all', the
 * handler callback will be called for all events.
 *
 * @tip Event handler callbacks are considered in the follow order:
 *  - Specific registration where 'all' isn't used.
 *  - Registration where 'all' is used for $event only.
 *  - Registration where 'all' is used for $type only.
 *  - Registration where 'all' is used for both.
 *
 * @warning If you use the 'all' keyword, you must have logic in the handler callback to
 * test the passed parameters before taking an action.
 *
 * @tip When referring to events, the preferred syntax is "event, type".
 *
 * @param string   $event       The event type
 * @param string   $object_type The object type
 * @param callable $callback    The handler callback
 * @param int      $priority    The priority - 0 is default, negative before, positive after
 *
 * @return bool
 * @example documentation/events/basic.php
 * @example documentation/events/advanced.php
 * @example documentation/events/all.php
 */
function elgg_register_event_handler($event, $object_type, $callback, $priority = 500) {
	return _elgg_services()->events->registerHandler($event, $object_type, $callback, $priority);
}

/**
 * Unregisters a callback for an event.
 *
 * @param string   $event       The event type
 * @param string   $object_type The object type
 * @param callable $callback    The callback. Since 1.11, static method callbacks will match dynamic methods
 *
 * @return bool true if a handler was found and removed
 * @since 1.7
 */
function elgg_unregister_event_handler($event, $object_type, $callback) {
	return _elgg_services()->events->unregisterHandler($event, $object_type, $callback);
}

/**
 * Clears all callback registrations for a event.
 *
 * @param string $event       The name of the event
 * @param string $object_type The objecttype of the event
 *
 * @return void
 * @since 2.3
 */
function elgg_clear_event_handlers($event, $object_type) {
	_elgg_services()->events->clearHandlers($event, $object_type);
}

/**
 * Trigger an Elgg Event and attempt to run all handler callbacks registered to that
 * event, type.
 *
 * This function attempts to run all handlers registered to $event, $object_type or
 * the special keyword 'all' for either or both. If a handler returns false, the
 * event will be cancelled (no further handlers will be called, and this function
 * will return false).
 *
 * $event is usually a verb: create, update, delete, annotation.
 *
 * $object_type is usually a noun: object, group, user, annotation, relationship, metadata.
 *
 * $object is usually an Elgg* object associated with the event.
 *
 * @warning Elgg events should only be triggered by core.  Plugin authors should use
 * {@link trigger_elgg_plugin_hook()} instead.
 *
 * @tip When referring to events, the preferred syntax is "event, type".
 *
 * @note Internal: Only rarely should events be changed, added, or removed in core.
 * When making changes to events, be sure to first create a ticket on Github.
 *
 * @note Internal: @tip Think of $object_type as the primary namespace element, and
 * $event as the secondary namespace.
 *
 * @param string $event       The event type
 * @param string $object_type The object type
 * @param mixed  $object      The object involved in the event
 *
 * @return bool False if any handler returned false, otherwise true.
 * @example documentation/examples/events/trigger.php
 */
function elgg_trigger_event($event, $object_type, $object = null) {
	return _elgg_services()->events->trigger($event, $object_type, $object);
}

/**
 * Trigger a "Before event" indicating a process is about to begin.
 *
 * Like regular events, a handler returning false will cancel the process and false
 * will be returned.
 *
 * To register for a before event, append ":before" to the event name when registering.
 *
 * @param string $event       The event type. The fired event type will be appended with ":before".
 * @param string $object_type The object type
 * @param mixed  $object      The object involved in the event
 *
 * @return bool False if any handler returned false, otherwise true
 *
 * @see elgg_trigger_event()
 * @see elgg_trigger_after_event()
 */
function elgg_trigger_before_event($event, $object_type, $object = null) {
	return _elgg_services()->events->triggerBefore($event, $object_type, $object);
}

/**
 * Trigger an "After event" indicating a process has finished.
 *
 * Unlike regular events, all the handlers will be called, their return values ignored.
 *
 * To register for an after event, append ":after" to the event name when registering.
 *
 * @param string $event       The event type. The fired event type will be appended with ":after".
 * @param string $object_type The object type
 * @param string $object      The object involved in the event
 *
 * @return true
 *
 * @see elgg_trigger_before_event()
 */
function elgg_trigger_after_event($event, $object_type, $object = null) {
	return _elgg_services()->events->triggerAfter($event, $object_type, $object);
}

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
 */
function elgg_register_plugin_hook_handler($hook, $type, $callback, $priority = 500) {
	return _elgg_services()->hooks->registerHandler($hook, $type, $callback, $priority);
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
 */
function elgg_unregister_plugin_hook_handler($hook, $entity_type, $callback) {
	_elgg_services()->hooks->unregisterHandler($hook, $entity_type, $callback);
}

/**
 * Clears all callback registrations for a plugin hook.
 *
 * @param string $hook The name of the hook
 * @param string $type The type of the hook
 *
 * @return void
 * @since 2.0
 */
function elgg_clear_plugin_hook_handlers($hook, $type) {
	_elgg_services()->hooks->clearHandlers($hook, $type);
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
 */
function elgg_trigger_plugin_hook($hook, $type, $params = null, $returnvalue = null) {
	return _elgg_services()->hooks->trigger($hook, $type, $params, $returnvalue);
}

/**
 * Log a message.
 *
 * If $level is >= to the debug setting in {@link $CONFIG->debug}, the
 * message will be sent to {@link elgg_dump()}.  Messages with lower
 * priority than {@link $CONFIG->debug} are ignored.
 *
 * @note Use the developers plugin to display logs
 *
 * @param string $message User message
 * @param string $level   NOTICE | WARNING | ERROR
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_log($message, $level = \Psr\Log\LogLevel::NOTICE) {
	return _elgg_services()->logger->log($level, $message);
}

/**
 * Logs $value to PHP's {@link error_log()}
 *
 * A {@elgg_plugin_hook debug log} is called.  If a handler returns
 * false, it will stop the default logging method.
 *
 * @note Use the developers plugin to display logs
 *
 * @param mixed $value The value
 * @return void
 * @since 1.7.0
 */
function elgg_dump($value) {
	_elgg_services()->logger->dump($value);
}

/**
 * Builds a URL from the a parts array like one returned by {@link parse_url()}.
 *
 * @note If only partial information is passed, a partial URL will be returned.
 *
 * @param array $parts       Associative array of URL components like parse_url() returns
 *                           'user' and 'pass' parts are ignored because of security reasons
 * @param bool  $html_encode HTML Encode the url?
 *
 * @return string Full URL
 * @since 1.7.0
 */
function elgg_http_build_url(array $parts, $html_encode = true): string {
	return _elgg_services()->urls->buildUrl($parts, (bool) $html_encode);
}

/**
 * Adds action tokens to URL
 *
 * As of 1.7.0 action tokens are required on all actions.
 * Use this function to append action tokens to a URL's GET parameters.
 * This will preserve any existing GET parameters.
 *
 * @note If you are using {@elgg_view input/form} you don't need to
 * add tokens to the action.  The form view automatically handles
 * tokens.
 *
 * @param string $url         Full action URL
 * @param bool   $html_encode HTML encode the url? (default: false)
 *
 * @return string URL with action tokens
 * @since 1.7.0
 */
function elgg_add_action_tokens_to_url($url, $html_encode = false): string {
	return _elgg_services()->urls->addActionTokensToUrl((string) $url, (bool) $html_encode);
}

/**
 * Removes an element from a URL's query string.
 *
 * @note You can send a partial URL string.
 *
 * @param string $url     Full URL
 * @param string $element The element to remove
 *
 * @return string The new URL with the query element removed.
 * @since 1.7.0
 */
function elgg_http_remove_url_query_element($url, $element): string {
	return _elgg_services()->urls->addQueryElementsToUrl((string) $url, [(string) $element => null]);
}

/**
 * Sets elements in a URL's query string.
 *
 * @param string $url      The URL
 * @param array  $elements Key/value pairs to set in the URL. If the value is null, the
 *                         element is removed from the URL.
 *
 * @return string The new URL with the query strings added
 * @since 1.7.0
 */
function elgg_http_add_url_query_elements($url, array $elements): string {
	return _elgg_services()->urls->addQueryElementsToUrl((string) $url, $elements);
}

/**
 * Test if two URLs are functionally identical.
 *
 * @tip If $ignore_params is used, neither the name nor its value will be considered when comparing.
 *
 * @tip The order of GET params doesn't matter.
 *
 * @param string $url1          First URL
 * @param string $url2          Second URL
 * @param array  $ignore_params GET params to ignore in the comparison
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_http_url_is_identical($url1, $url2, $ignore_params = ['offset', 'limit']): bool {
	if (!is_string($url1) || !is_string($url2)) {
		return false;
	}
	
	return _elgg_services()->urls->isUrlIdentical($url1, $url2, (array) $ignore_params);
}

/**
 * Signs provided URL with a SHA256 HMAC key
 *
 * @note Signed URLs do not offer CSRF protection and should not be used instead of action tokens.
 *
 * @param string $url     URL to sign
 * @param string $expires Expiration time
 *                        A string suitable for strtotime()
 *                        Falsey values indicate non-expiring URL
 * @return string
 */
function elgg_http_get_signed_url($url, $expires = false) {
	return _elgg_services()->urlSigner->sign($url, $expires);
}

/**
 * Validates if the HMAC signature of the URL is valid
 *
 * @param string $url URL to validate
 * @return bool
 */
function elgg_http_validate_signed_url($url) {
	return _elgg_services()->urlSigner->isValid($url);
}

/**
 * Checks for $array[$key] and returns its value if it exists, else
 * returns $default.
 *
 * Shorthand for $value = (isset($array['key'])) ? $array['key'] : 'default';
 *
 * @param string $key     Key to check in the source array
 * @param array  $array   Source array
 * @param mixed  $default Value to return if key is not found
 * @param bool   $strict  Return array key if it's set, even if empty. If false,
 *                        return $default if the array key is unset or empty.
 *
 * @return mixed
 * @since 1.8.0
 */
function elgg_extract($key, $array, $default = null, $strict = true) {
	if (!is_array($array) && !$array instanceof ArrayAccess) {
		return $default;
	}

	if ($strict) {
		return (isset($array[$key])) ? $array[$key] : $default;
	} else {
		return (isset($array[$key]) && !empty($array[$key])) ? $array[$key] : $default;
	}
}

/**
 * Extract class names from an array, optionally merging into a preexisting set.
 *
 * @param array           $array       Source array
 * @param string|string[] $existing    Existing name(s)
 * @param string          $extract_key Key to extract new classes from
 * @return string[]
 *
 * @since 2.3.0
 */
function elgg_extract_class(array $array, $existing = [], $extract_key = 'class') {
	$existing = empty($existing) ? [] : (array) $existing;

	$merge = (array) elgg_extract($extract_key, $array, []);

	array_splice($existing, count($existing), 0, $merge);

	return array_values(array_unique($existing));
}

/**
 * Calls a callable autowiring the arguments using public DI services
 * and applying logic based on flags
 *
 * @param int     $flags   Bitwise flags
 *                         ELGG_IGNORE_ACCESS
 *                         ELGG_ENFORCE_ACCESS
 *                         ELGG_SHOW_DISABLED_ENTITIES
 *                         ELGG_HIDE_DISABLED_ENTITIES
 * @param Closure $closure Callable to call
 *
 * @return mixed
 */
function elgg_call(int $flags, Closure $closure) {
	return _elgg_services()->invoker->call($flags, $closure);
}

/**
 * Returns a PHP INI setting in bytes.
 *
 * @tip Use this for arithmetic when determining if a file can be uploaded.
 *
 * @param string $setting The php.ini setting
 *
 * @return int
 * @since 1.7.0
 * @link http://www.php.net/manual/en/function.ini-get.php
 */
function elgg_get_ini_setting_in_bytes($setting) {
	// retrieve INI setting
	$val = ini_get($setting);

	// convert INI setting when shorthand notation is used
	$last = strtolower($val[strlen($val) - 1]);
	if (in_array($last, ['g', 'm', 'k'])) {
		$val = substr($val, 0, -1);
	}
	$val = (int) $val;
	switch ($last) {
		case 'g':
			$val *= 1024;
			// fallthrough intentional
		case 'm':
			$val *= 1024;
			// fallthrough intentional
		case 'k':
			$val *= 1024;
	}

	// return byte value
	return $val;
}

/**
 * Get the global service provider
 *
 * @return \Elgg\Di\InternalContainer
 * @internal
 */
function _elgg_services() {
	// This yields a more shallow stack depth in recursive APIs like views. This aids in debugging and
	// reduces false positives in xdebug's infinite recursion protection.
	return \Elgg\Application::$_instance->internal_services;
}
