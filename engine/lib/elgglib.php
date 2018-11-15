<?php

use Elgg\Http\ResponseBuilder;

/**
 * Bootstrapping and helper procedural code available for use in Elgg core and plugins.
 *
 * @package Elgg.Core
 * @todo These functions can't be subpackaged because they cover a wide mix of
 * purposes and subsystems.  Many of them should be moved to more relevant files.
 */

/**
 * Get a reference to the global Application object
 *
 * @return \Elgg\Di\PublicContainer
 * @since 2.0.0
 */
function elgg() {
	return _elgg_services()->dic;
}

/**
 * Forward to $location.
 *
 * Sends a 'Location: $location' header and exits.  If headers have already been sent, throws an exception.
 *
 * @param string $location URL to forward to browser to. This can be a path
 *                         relative to the network's URL.
 * @param string $reason   Short explanation for why we're forwarding. Set to
 *                         '404' to forward to error page. Default message is
 *                         'system'.
 *
 * @return void
 * @throws SecurityException|InvalidParameterException
 */
function forward($location = "", $reason = 'system') {
	if (headers_sent($file, $line)) {
		throw new \SecurityException("Redirect could not be issued due to headers already being sent. Halting execution for security. "
			. "Output started in file $file at line $line. Search http://learn.elgg.org/ for more information.");
	}

	_elgg_services()->responseFactory->redirect($location, $reason);
	exit;
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
 * Register a JavaScript file for inclusion
 *
 * This function handles adding JavaScript to a web page. If multiple
 * calls are made to register the same JavaScript file based on the $id
 * variable, only the last file is included. This allows a plugin to add
 * JavaScript from a view that may be called more than once. It also handles
 * more than one plugin adding the same JavaScript.
 *
 * jQuery plugins often have filenames such as jquery.rating.js. A best practice
 * is to base $name on the filename: "jquery.rating". It is recommended to not
 * use version numbers in the name.
 *
 * The JavaScript files can be local to the server or remote (such as
 * Google's CDN).
 *
 * @note Since 2.0, scripts with location "head" will also be output in the footer, but before
 *       those with location "footer".
 *
 * @param string $name     An identifier for the JavaScript library
 * @param string $url      URL of the JavaScript file
 * @param string $location Page location: head or footer. (default: head)
 * @param int    $priority Priority of the JS file (lower numbers load earlier)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_js($name, $url, $location = 'head', $priority = null) {
	return elgg_register_external_file('js', $name, $url, $location, $priority);
}

/**
 * Defines a JS lib as an AMD module. This is useful for shimming
 * traditional JS or for setting the paths of AMD modules.
 *
 * Calling multiple times for the same name will:
 *     * set the preferred path to the last call setting a path
 *     * overwrite the shimmed AMD modules with the last call setting a shimmed module
 *
 * Use elgg_require_js($name) to load on the current page.
 *
 * Calling this function is not needed if your JS are in views named like `module/name.js`
 * Instead, simply call elgg_require_js("module/name").
 *
 * @note The configuration is cached in simplecache, so logic should not depend on user-
 *       specific values like get_language().
 *
 * @param string $name   The module name
 * @param array  $config An array like the following:
 *                       array  'deps'    An array of AMD module dependencies
 *                       string 'exports' The name of the exported module
 *                       string 'src'     The URL to the JS. Can be relative.
 *
 * @return void
 */
function elgg_define_js($name, $config) {
	$src = elgg_extract('src', $config);

	if ($src) {
		$url = elgg_normalize_url($src);
		_elgg_services()->amdConfig->addPath($name, $url);
	}

	// shimmed module
	if (isset($config['deps']) || isset($config['exports'])) {
		_elgg_services()->amdConfig->addShim($name, $config);
	}
}

/**
 * Unregister a JavaScript file
 *
 * @param string $name The identifier for the JavaScript library
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_unregister_js($name) {
	return elgg_unregister_external_file('js', $name);
}

/**
 * Load a JavaScript resource on this page
 *
 * This must be called before elgg_view_page(). It can be called before the
 * script is registered. If you do not want a script loaded, unregister it.
 *
 * @param string $name Identifier of the JavaScript resource
 *
 * @return void
 * @since 1.8.0
 */
function elgg_load_js($name) {
	elgg_load_external_file('js', $name);
}


/**
 * Request that Elgg load an AMD module onto the page.
 *
 * @param string $name The AMD module name.
 * @return void
 * @since 1.9.0
 */
function elgg_require_js($name) {
	_elgg_services()->amdConfig->addDependency($name);
}

/**
 * Cancel a request to load an AMD module onto the page.
 *
 * @note The elgg, jquery, and jquery-ui modules cannot be cancelled.
 *
 * @param string $name The AMD module name.
 * @return void
 * @since 2.1.0
 */
function elgg_unrequire_js($name) {
	_elgg_services()->amdConfig->removeDependency($name);
}

/**
 * Get the JavaScript URLs that are loaded
 *
 * @param string $location 'head' or 'footer'
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_loaded_js($location = 'head') {
	return elgg_get_loaded_external_files('js', $location);
}

/**
 * Register a CSS file for inclusion in the HTML head
 *
 * @param string $name     An identifier for the CSS file
 * @param string $url      URL of the CSS file
 * @param int    $priority Priority of the CSS file (lower numbers load earlier)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_css($name, $url, $priority = null) {
	return elgg_register_external_file('css', $name, $url, 'head', $priority);
}

/**
 * Unregister a CSS file
 *
 * @param string $name The identifier for the CSS file
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_unregister_css($name) {
	return elgg_unregister_external_file('css', $name);
}

/**
 * Load a CSS file for this page
 *
 * This must be called before elgg_view_page(). It can be called before the
 * CSS file is registered. If you do not want a CSS file loaded, unregister it.
 *
 * @param string $name Identifier of the CSS file
 *
 * @return void
 * @since 1.8.0
 */
function elgg_load_css($name) {
	elgg_load_external_file('css', $name);
}

/**
 * Get the loaded CSS URLs
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_loaded_css() {
	return elgg_get_loaded_external_files('css', 'head');
}

/**
 * Core registration function for external files
 *
 * @param string $type     Type of external resource (js or css)
 * @param string $name     Identifier used as key
 * @param string $url      URL
 * @param string $location Location in the page to include the file
 * @param int    $priority Loading priority of the file
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_external_file($type, $name, $url, $location, $priority = 500) {
	return _elgg_services()->externalFiles->register($type, $name, $url, $location, $priority);
}

/**
 * Unregister an external file
 *
 * @param string $type Type of file: js or css
 * @param string $name The identifier of the file
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_unregister_external_file($type, $name) {
	return _elgg_services()->externalFiles->unregister($type, $name);
}

/**
 * Load an external resource for use on this page
 *
 * @param string $type Type of file: js or css
 * @param string $name The identifier for the file
 *
 * @return void
 * @since 1.8.0
 */
function elgg_load_external_file($type, $name) {
	_elgg_services()->externalFiles->load($type, $name);
}

/**
 * Get external resource descriptors
 *
 * @param string $type     Type of file: js or css
 * @param string $location Page location
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_loaded_external_files($type, $location) {
	return _elgg_services()->externalFiles->getLoadedFiles($type, $location);
}

/**
 * Returns a list of files in $directory.
 *
 * Only returns files.  Does not recurse into subdirs.
 *
 * @param string $directory  Directory to look in
 * @param array  $exceptions Array of filenames to ignore
 * @param array  $list       Array of files to append to
 * @param mixed  $extensions Array of extensions to allow, null for all. Use a dot: array('.php').
 *
 * @return array Filenames in $directory, in the form $directory/filename.
 */
function elgg_get_file_list($directory, $exceptions = [], $list = [], $extensions = null) {

	$directory = \Elgg\Project\Paths::sanitize($directory);
	if ($handle = opendir($directory)) {
		while (($file = readdir($handle)) !== false) {
			if (!is_file($directory . $file) || in_array($file, $exceptions)) {
				continue;
			}

			if (is_array($extensions)) {
				if (in_array(strrchr($file, '.'), $extensions)) {
					$list[] = $directory . $file;
				}
			} else {
				$list[] = $directory . $file;
			}
		}
		closedir($handle);
	}

	return $list;
}

/**
 * Counts the number of messages, either globally or in a particular register
 *
 * @param string $register Optionally, the register
 *
 * @return integer The number of messages
 */
function count_messages($register = "") {
	return elgg()->system_messages->count($register);
}

/**
 * Display a system message on next page load.
 *
 * @param string|array $message Message or messages to add
 *
 * @return bool
 */
function system_message($message) {
	elgg()->system_messages->addSuccessMessage($message);
	return true;
}

/**
 * Display an error on next page load.
 *
 * @param string|array $error Error or errors to add
 *
 * @return bool
 */
function register_error($error) {
	elgg()->system_messages->addErrorMessage($error);
	return true;
}

/**
 * Get a copy of the current system messages.
 *
 * @return \Elgg\SystemMessages\RegisterSet
 * @since 2.1
 */
function elgg_get_system_messages() {
	return elgg()->system_messages->loadRegisters();
}

/**
 * Set the system messages. This will overwrite the state of all messages and errors!
 *
 * @param \Elgg\SystemMessages\RegisterSet $set Set of messages
 * @return void
 * @since 2.1
 */
function elgg_set_system_messages(\Elgg\SystemMessages\RegisterSet $set) {
	elgg()->system_messages->saveRegisters($set);
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
 * @param string $event       The event type
 * @param string $object_type The object type
 * @param string $callback    The handler callback
 * @param int    $priority    The priority - 0 is default, negative before, positive after
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
 * @param string $event       The event type
 * @param string $object_type The object type
 * @param string $callback    The callback. Since 1.11, static method callbacks will match dynamic methods
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
	return elgg()->events->trigger($event, $object_type, $object);
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
	return elgg()->events->triggerBefore($event, $object_type, $object);
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
	return elgg()->events->triggerAfter($event, $object_type, $object);
}

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
	return elgg()->hooks->registerHandler($hook, $type, $callback, $priority);
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
	elgg()->hooks->unregisterHandler($hook, $entity_type, $callback);
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
	elgg()->hooks->clearHandlers($hook, $type);
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
 * $hook is usually a verb: import, get_views, output.
 *
 * $type is usually a noun: user, ecml, page.
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
	return elgg()->hooks->trigger($hook, $type, $params, $returnvalue);
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
 * Returns an ordered array of hook handlers registered for $hook and $type.
 *
 * @param string $hook Hook name
 * @param string $type Hook type
 *
 * @return array
 *
 * @since 2.0.0
 */
function elgg_get_ordered_hook_handlers($hook, $type) {
	return elgg()->hooks->getOrderedHandlers($hook, $type);
}

/**
 * Returns an ordered array of event handlers registered for $event and $type.
 *
 * @param string $event Event name
 * @param string $type  Object type
 *
 * @return array
 *
 * @since 2.0.0
 */
function elgg_get_ordered_event_handlers($event, $type) {
	return elgg()->events->getOrderedHandlers($event, $type);
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
 * Get the current Elgg version information
 *
 * @param bool $human_readable Whether to return a human readable version (default: false)
 *
 * @return string|false Depending on success
 * @since 1.9
 */
function elgg_get_version($human_readable = false) {
	static $version, $release;
	
	if (!isset($version) || !isset($release)) {
		$path = \Elgg\Application::elggDir()->getPath('version.php');
		if (!is_file($path)) {
			return false;
		}
		include $path;
	}
	
	return $human_readable ? $release : $version;
}

/**
 * Log a notice about deprecated use of a function, view, etc.
 *
 * @param string $msg             Message to log
 * @param string $dep_version     Human-readable *release* version: 1.7, 1.8, ...
 * @param int    $backtrace_level How many levels back to display the backtrace.
 *                                Useful if calling from functions that are called
 *                                from other places (like elgg_view()). Set to -1
 *                                for a full backtrace.
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_deprecated_notice($msg, $dep_version, $backtrace_level = 1) {
	$backtrace_level += 1;
	return _elgg_services()->deprecation->sendNotice($msg, $dep_version, $backtrace_level);
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
 * @see https://github.com/Elgg/Elgg/pull/8146#issuecomment-91544585
 * @return string Full URL
 * @since 1.7.0
 */
function elgg_http_build_url(array $parts, $html_encode = true) {
	// build only what's given to us.
	$scheme = isset($parts['scheme']) ? "{$parts['scheme']}://" : '';
	$host = isset($parts['host']) ? "{$parts['host']}" : '';
	$port = isset($parts['port']) ? ":{$parts['port']}" : '';
	$path = isset($parts['path']) ? "{$parts['path']}" : '';
	$query = isset($parts['query']) ? "?{$parts['query']}" : '';
	$fragment = isset($parts['fragment']) ? "#{$parts['fragment']}" : '';

	$string = $scheme . $host . $port . $path . $query . $fragment;

	if ($html_encode) {
		return htmlspecialchars($string, ENT_QUOTES, 'UTF-8', false);
	} else {
		return $string;
	}
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
function elgg_add_action_tokens_to_url($url, $html_encode = false) {
	$url = elgg_normalize_url($url);
	$components = parse_url($url);

	if (isset($components['query'])) {
		$query = elgg_parse_str($components['query']);
	} else {
		$query = [];
	}

	if (isset($query['__elgg_ts']) && isset($query['__elgg_token'])) {
		return $url;
	}

	// append action tokens to the existing query
	$query['__elgg_ts'] = time();
	$query['__elgg_token'] = generate_action_token($query['__elgg_ts']);
	$components['query'] = http_build_query($query);

	// rebuild the full url
	return elgg_http_build_url($components, $html_encode);
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
function elgg_http_remove_url_query_element($url, $element) {
	return elgg_http_add_url_query_elements($url, [$element => null]);
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
function elgg_http_add_url_query_elements($url, array $elements) {
	$url_array = parse_url($url);

	if (isset($url_array['query'])) {
		$query = elgg_parse_str($url_array['query']);
	} else {
		$query = [];
	}

	foreach ($elements as $k => $v) {
		if ($v === null) {
			unset($query[$k]);
		} else {
			$query[$k] = $v;
		}
	}

	// why check path? A: if no path, this may be a relative URL like "?foo=1". In this case,
	// the output "" would be interpreted the current URL, so in this case we *must* set
	// a query to make sure elements are removed.
	if ($query || empty($url_array['path'])) {
		$url_array['query'] = http_build_query($query);
	} else {
		unset($url_array['query']);
	}
	$string = elgg_http_build_url($url_array, false);

	// Restore relative protocol to url if missing and is provided as part of the initial url (see #9874)
	if (!isset($url['scheme']) && (substr($url, 0, 2) == '//')) {
		$string = "//{$string}";
	}
	
	return $string;
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
function elgg_http_url_is_identical($url1, $url2, $ignore_params = ['offset', 'limit']) {
	if (!is_string($url1) || !is_string($url2)) {
		return false;
	}
	
	$url1 = elgg_normalize_url($url1);
	$url2 = elgg_normalize_url($url2);

	if ($url1 == $url2) {
		return true;
	}

	$url1_info = parse_url($url1);
	$url2_info = parse_url($url2);

	if (isset($url1_info['path'])) {
		$url1_info['path'] = trim($url1_info['path'], '/');
	}
	if (isset($url2_info['path'])) {
		$url2_info['path'] = trim($url2_info['path'], '/');
	}

	// compare basic bits
	$parts = ['scheme', 'host', 'path'];

	foreach ($parts as $part) {
		if ((isset($url1_info[$part]) && isset($url2_info[$part]))
		&& $url1_info[$part] != $url2_info[$part]) {
			return false;
		} elseif (isset($url1_info[$part]) && !isset($url2_info[$part])) {
			return false;
		} elseif (!isset($url1_info[$part]) && isset($url2_info[$part])) {
			return false;
		}
	}

	// quick compare of get params
	if (isset($url1_info['query']) && isset($url2_info['query'])
	&& $url1_info['query'] == $url2_info['query']) {
		return true;
	}

	// compare get params that might be out of order
	$url1_params = [];
	$url2_params = [];

	if (isset($url1_info['query'])) {
		if ($url1_info['query'] = html_entity_decode($url1_info['query'])) {
			$url1_params = elgg_parse_str($url1_info['query']);
		}
	}

	if (isset($url2_info['query'])) {
		if ($url2_info['query'] = html_entity_decode($url2_info['query'])) {
			$url2_params = elgg_parse_str($url2_info['query']);
		}
	}

	// drop ignored params
	foreach ($ignore_params as $param) {
		if (isset($url1_params[$param])) {
			unset($url1_params[$param]);
		}
		if (isset($url2_params[$param])) {
			unset($url2_params[$param]);
		}
	}

	// array_diff_assoc only returns the items in arr1 that aren't in arrN
	// but not the items that ARE in arrN but NOT in arr1
	// if arr1 is an empty array, this function will return 0 no matter what.
	// since we only care if they're different and not how different,
	// add the results together to get a non-zero (ie, different) result
	$diff_count = count(array_diff_assoc($url1_params, $url2_params));
	$diff_count += count(array_diff_assoc($url2_params, $url1_params));
	if ($diff_count > 0) {
		return false;
	}

	return true;
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
 * Validates if the HMAC signature of the current request is valid
 * Issues 403 response if signature is invalid
 *
 * @return void
 * @throws \Elgg\HttpException
 */
function elgg_signed_request_gatekeeper() {

	if (\Elgg\Application::isCli()) {
		return;
	}

	if (!elgg_http_validate_signed_url(current_page_url())) {
		throw new \Elgg\HttpException(elgg_echo('invalid_request_signature'), ELGG_HTTP_FORBIDDEN);
	}
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
 * Sorts a 3d array by specific element.
 *
 * @warning Will re-index numeric indexes.
 *
 * @note This operates the same as the built-in sort functions.
 * It sorts the array and returns a bool for success.
 *
 * Do this: elgg_sort_3d_array_by_value($my_array);
 * Not this: $my_array = elgg_sort_3d_array_by_value($my_array);
 *
 * @param array  $array      Array to sort
 * @param string $element    Element to sort by
 * @param int    $sort_order PHP sort order {@link http://us2.php.net/array_multisort}
 * @param int    $sort_type  PHP sort type {@link http://us2.php.net/sort}
 *
 * @return bool
 */
function elgg_sort_3d_array_by_value(&$array, $element, $sort_order = SORT_ASC, $sort_type = SORT_LOCALE_STRING) {

	$sort = [];

	foreach ($array as $v) {
		if (isset($v[$element])) {
			$sort[] = strtolower($v[$element]);
		} else {
			$sort[] = null;
		}
	};

	return array_multisort($sort, $sort_order, $sort_type, $array);
}

/**
 * Return the state of a php.ini setting as a bool
 *
 * @warning Using this on ini settings that are not boolean
 * will be inaccurate!
 *
 * @param string $ini_get_arg The INI setting
 *
 * @return bool Depending on whether it's on or off
 */
function ini_get_bool($ini_get_arg) {
	$temp = strtolower(ini_get($ini_get_arg));

	if ($temp == '1' || $temp == 'on' || $temp == 'true') {
		return true;
	}
	return false;
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
 * Returns true is string is not empty, false, or null.
 *
 * Function to be used in array_filter which returns true if $string is not null.
 *
 * @param string $string The string to test
 *
 * @return bool
 * @todo This is used once in metadata.php.  Use a lambda function instead.
 */
function is_not_null($string) {
	if (($string === '') || ($string === false) || ($string === null)) {
		return false;
	}

	return true;
}

/**
 * Get the global service provider
 *
 * @return \Elgg\Di\ServiceProvider
 * @access private
 */
function _elgg_services() {
	// This yields a more shallow stack depth in recursive APIs like views. This aids in debugging and
	// reduces false positives in xdebug's infinite recursion protection.
	return Elgg\Application::$_instance->_services;
}

/**
 * Serve individual views for Ajax.
 *
 * /ajax/view/<view_name>?<key/value params>
 * /ajax/form/<action_name>?<key/value params>
 *
 * @param string[] $segments URL segments (not including "ajax")
 * @return ResponseBuilder
 *
 * @see elgg_register_ajax_view()
 * @elgg_pagehandler ajax
 * @access private
 */
function _elgg_ajax_page_handler($segments) {
	elgg_ajax_gatekeeper();

	if (count($segments) < 2) {
		return elgg_error_response("Ajax pagehandler called with invalid segments", REFERRER, ELGG_HTTP_BAD_REQUEST);
	}

	if ($segments[0] === 'view' || $segments[0] === 'form') {
		if ($segments[0] === 'view') {
			if ($segments[1] === 'admin') {
				// protect admin views similar to all admin pages that are protected automatically in the admin_page_handler
				elgg_admin_gatekeeper();
			}
			// ignore 'view/'
			$view = implode('/', array_slice($segments, 1));
		} else {
			// form views start with "forms", not "form"
			$view = 'forms/' . implode('/', array_slice($segments, 1));
		}

		$ajax_api = _elgg_services()->ajax;
		$allowed_views = $ajax_api->getViews();

		// cacheable views are always allowed
		if (!in_array($view, $allowed_views) && !_elgg_services()->views->isCacheableView($view)) {
			return elgg_error_response("Ajax view '$view' was not registered", REFERRER, ELGG_HTTP_FORBIDDEN);
		}

		if (!elgg_view_exists($view)) {
			return elgg_error_response("Ajax view '$view' was not found", REFERRER, ELGG_HTTP_NOT_FOUND);
		}

		// pull out GET parameters through filter
		$vars = [];
		foreach (_elgg_services()->request->query->keys() as $name) {
			$vars[$name] = get_input($name);
		}

		if (isset($vars['guid'])) {
			$vars['entity'] = get_entity($vars['guid']);
		}

		if (isset($vars['river_id'])) {
			$vars['item'] = elgg_get_river_item_from_id($vars['river_id']);
		}

		$content_type = '';
		if ($segments[0] === 'view') {
			$output = elgg_view($view, $vars);

			// Try to guess the mime-type
			switch ($segments[1]) {
				case "js":
					$content_type = 'text/javascript;charset=utf-8';
					break;
				case "css":
					$content_type = 'text/css;charset=utf-8';
					break;
				default :
					if (_elgg_services()->views->isCacheableView($view)) {
						$file = _elgg_services()->views->findViewFile($view, elgg_get_viewtype());
						$content_type = (new \Elgg\Filesystem\MimeTypeDetector())->getType($file, 'text/html');
					}
					break;
			}
		} else {
			$action = implode('/', array_slice($segments, 1));
			$output = elgg_view_form($action, [], $vars);
		}

		if ($content_type) {
			elgg_set_http_header("Content-Type: $content_type");
		}

		return elgg_ok_response($output);
	}

	return false;
}

/**
 * Handle requests for /favicon.ico
 *
 * @param string[] $segments The URL segments
 * @return bool
 * @access private
 * @since 1.10
 */
function _elgg_favicon_page_handler($segments) {
	header("HTTP/1.1 404 Not Found", true, 404);

	header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+1 week")), true);
	header("Pragma: public", true);
	header("Cache-Control: public", true);

	header('Content-Type: image/x-icon');
	echo elgg_view('graphics/favicon.ico');

	return true;
}

/**
 * Checks if there are some constraints on the options array for
 * potentially dangerous operations.
 *
 * @param array  $options Options array
 * @param string $type    Options type: metadata, annotation or river
 * @return bool
 * @access private
 */
function _elgg_is_valid_options_for_batch_operation($options, $type) {
	if (!$options || !is_array($options)) {
		return false;
	}

	// at least one of these is required.
	$required = [
		// generic restraints
		'guid', 'guids'
	];

	switch ($type) {
		case 'metadata':
			$metadata_required = [
				'metadata_name', 'metadata_names',
				'metadata_value', 'metadata_values'
			];

			$required = array_merge($required, $metadata_required);
			break;

		case 'annotations':
		case 'annotation':
			$annotations_required = [
				'annotation_owner_guid', 'annotation_owner_guids',
				'annotation_name', 'annotation_names',
				'annotation_value', 'annotation_values'
			];

			$required = array_merge($required, $annotations_required);
			break;

		case 'river':
			// overriding generic restraints as guids isn't supported in river
			$required = [
				'id', 'ids',
				'subject_guid', 'subject_guids',
				'object_guid', 'object_guids',
				'target_guid', 'target_guids',
				'annotation_id', 'annotation_ids',
				'view', 'views',
			];
			break;
		
		default:
			return false;
	}

	foreach ($required as $key) {
		// check that it exists and is something.
		if (isset($options[$key]) && $options[$key]) {
			return true;
		}
	}

	return false;
}

/**
 * Checks the status of the Walled Garden and forwards to a login page
 * if required.
 *
 * If the site is in Walled Garden mode, all page except those registered as
 * plugin pages by {@elgg_hook public_pages walled_garden} will redirect to
 * a login page.
 *
 * @since 1.8.0
 * @elgg_event_handler init system
 * @return void
 * @access private
 */
function _elgg_walled_garden_init() {
	if (!_elgg_config()->walled_garden) {
		return;
	}

	elgg_register_css('elgg.walled_garden', elgg_get_simplecache_url('walled_garden.css'));

	elgg_register_plugin_hook_handler('register', 'menu:walled_garden', '_elgg_walled_garden_menu');

	if (_elgg_config()->default_access == ACCESS_PUBLIC) {
		elgg_set_config('default_access', ACCESS_LOGGED_IN);
	}

	elgg_register_plugin_hook_handler('access:collections:write', 'all', '_elgg_walled_garden_remove_public_access', 9999);

	if (!elgg_is_logged_in()) {
		// override the front page
		elgg_register_route('index', [
			'path' => '/',
			'resource' => 'walled_garden',
		]);
	}
}

/**
 * Adds home link to walled garden menu
 *
 * @param string $hook         'register'
 * @param string $type         'menu:walled_garden'
 * @param array  $return_value Current menu items
 * @param array  $params       Optional menu parameters
 *
 * @return array
 *
 * @access private
 */
function _elgg_walled_garden_menu($hook, $type, $return_value, $params) {
	
	if (current_page_url() === elgg_get_site_url()) {
		return;
	}
	
	$return_value[] = \ElggMenuItem::factory([
		'name' => 'home',
		'href' => '/',
		'text' => elgg_echo('walled_garden:home'),
		'priority' => 10,
	]);

	return $return_value;
}

/**
 * Remove public access for walled gardens
 *
 * @param string $hook     'access:collections:write'
 * @param string $type     'all'
 * @param array  $accesses current return value
 *
 * @return array
 *
 * @access private
 */
function _elgg_walled_garden_remove_public_access($hook, $type, $accesses) {
	if (isset($accesses[ACCESS_PUBLIC])) {
		unset($accesses[ACCESS_PUBLIC]);
	}
	return $accesses;
}

/**
 * Elgg's main init.
 *
 * Handles core actions, the JS pagehandler, and the shutdown function.
 *
 * @elgg_event_handler init system
 * @return void
 * @access private
 */
function _elgg_init() {
	
	elgg_register_plugin_hook_handler('head', 'page', function($hook, $type, array $result) {
		$result['links']['manifest'] = [
			'rel' => 'manifest',
			'href' => elgg_normalize_url('/manifest.json'),
		];

		return $result;
	});

	if (_elgg_config()->enable_profiling) {
		/**
		 * @see \Elgg\Profiler::handlePageOutput
		 */
		elgg_register_plugin_hook_handler('output', 'page', [\Elgg\Profiler::class, 'handlePageOutput'], 999);
	}

	elgg_register_plugin_hook_handler('commands', 'cli', '_elgg_init_cli_commands');
}

/**
 * Initialize Cli commands
 *
 * @elgg_plugin_hook commands cli
 *
 * @param \Elgg\Hook $hook Hook
 *
 * @return \Elgg\Cli\Command[]
 * @access private
 */
function _elgg_init_cli_commands(\Elgg\Hook $hook) {
	$defaults = [
		\Elgg\Cli\SimpletestCommand::class,
		\Elgg\Cli\DatabaseSeedCommand::class,
		\Elgg\Cli\DatabaseUnseedCommand::class,
		\Elgg\Cli\CronCommand::class,
		\Elgg\Cli\FlushCommand::class,
		\Elgg\Cli\PluginsListCommand::class,
		\Elgg\Cli\PluginsActivateCommand::class,
		\Elgg\Cli\PluginsDeactivateCommand::class,
	];

	return array_merge($defaults, (array) $hook->getValue());
}

/**
 * Register core routes
 * @return void
 * @internal
 */
function _elgg_register_routes() {
	$conf = \Elgg\Project\Paths::elgg() . 'engine/routes.php';
	$routes = \Elgg\Includer::includeFile($conf);

	foreach ($routes as $name => $def) {
		elgg_register_route($name, $def);
	}
}

/**
 * Register core actions
 * @return void
 * @internal
 */
function _elgg_register_actions() {
	$conf = \Elgg\Project\Paths::elgg() . 'engine/actions.php';
	$actions = \Elgg\Includer::includeFile($conf);
	
	$root_path = \Elgg\Project\Paths::elgg();

	foreach ($actions as $action => $action_spec) {
		if (!is_array($action_spec)) {
			continue;
		}
		
		$access = elgg_extract('access', $action_spec, 'logged_in');
		$handler = elgg_extract('controller', $action_spec);
		if (!$handler) {
			$handler = elgg_extract('filename', $action_spec);
			if (!$handler) {
				$handler = "$root_path/actions/{$action}.php";
			}
		}
		
		elgg_register_action($action, $handler, $access);
	}
}

/**
 * Adds unit tests for the general API.
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param array  $value  array of test files
 * @param array  $params empty
 *
 * @elgg_plugin_hook unit_tests system
 * @return array
 * @access private
 * @codeCoverageIgnore
 */
function _elgg_api_test($hook, $type, $value, $params) {
	$value[] = ElggTravisInstallTest::class;
	$value[] = ElggCoreHelpersTest::class;
	$value[] = ElggCoreRegressionBugsTest::class;
	$value[] = ElggBatchTest::class;
	return $value;
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_init');
	$events->registerHandler('init', 'system', '_elgg_walled_garden_init', 1000);

	$hooks->registerHandler('unit_test', 'system', '_elgg_api_test');
};
