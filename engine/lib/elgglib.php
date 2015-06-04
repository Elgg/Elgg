<?php
/**
 * Bootstrapping and helper procedural code available for use in Elgg core and plugins.
 *
 * @package Elgg.Core
 * @todo These functions can't be subpackaged because they cover a wide mix of
 * purposes and subsystems.  Many of them should be moved to more relevant files.
 */

/**
 * Register a PHP file as a library.
 *
 * @see elgg_load_library
 *
 * @param string $name     The name of the library
 * @param string $location The location of the file
 *
 * @return void
 * @since 1.8.0
 */
function elgg_register_library($name, $location) {
	$config = _elgg_services()->config;

	$libraries = $config->get('libraries');
	if ($libraries === null) {
		$libraries = array();
	}
	$libraries[$name] = $location;
	$config->set('libraries', $libraries);
}

/**
 * Load a PHP library.
 *
 * @see elgg_register_library
 *
 * @param string $name The name of the library
 *
 * @return void
 * @throws InvalidParameterException
 * @since 1.8.0
 */
function elgg_load_library($name) {
	static $loaded_libraries = array();

	if (in_array($name, $loaded_libraries)) {
		return;
	}

	$libraries = _elgg_services()->config->get('libraries');

	if (!isset($libraries[$name])) {
		$error = "$name is not a registered library";
		throw new \InvalidParameterException($error);
	}

	if (!include_once($libraries[$name])) {
		$error = "Could not load the $name library from {$libraries[$name]}";
		throw new \InvalidParameterException($error);
	}

	$loaded_libraries[] = $name;
}

/**
 * Forward to $location.
 *
 * Sends a 'Location: $location' header and exists.  If headers have
 * already been sent, throws an exception.
 *
 * @param string $location URL to forward to browser to. This can be a path
 *                         relative to the network's URL.
 * @param string $reason   Short explanation for why we're forwarding. Set to
 *                         '404' to forward to error page. Default message is
 *                         'system'.
 *
 * @return void
 * @throws SecurityException
 */
function forward($location = "", $reason = 'system') {
	if (!headers_sent($file, $line)) {
		if ($location === REFERER) {
			$location = _elgg_services()->request->headers->get('Referer');
		}

		$location = elgg_normalize_url($location);

		// return new forward location or false to stop the forward or empty string to exit
		$current_page = current_page_url();
		$params = array('current_url' => $current_page, 'forward_url' => $location);
		$location = elgg_trigger_plugin_hook('forward', $reason, $params, $location);

		if ($location) {
			header("Location: {$location}");
		}
		exit;
	} else {
		throw new \SecurityException("Redirect could not be issued due to headers already being sent. Halting execution for security. "
			. "Output started in file $file at line $line. Search http://learn.elgg.org/ for more information.");
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
 * Calling this function is not needed if your JS are in views named like `js/module/name.js`
 * Instead, simply call elgg_require_js("module/name").
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
	return _elgg_services()->externalFiles->load($type, $name);
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
function elgg_get_file_list($directory, $exceptions = array(), $list = array(),
$extensions = null) {

	$directory = sanitise_filepath($directory);
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
 * Sanitise file paths ensuring that they begin and end with slashes etc.
 *
 * @param string $path         The path
 * @param bool   $append_slash Add tailing slash
 *
 * @return string
 */
function sanitise_filepath($path, $append_slash = true) {
	// Convert to correct UNIX paths
	$path = str_replace('\\', '/', $path);
	$path = str_replace('../', '/', $path);
	// replace // with / except when preceeded by :
	$path = preg_replace("/([^:])\/\//", "$1/", $path);

	// Sort trailing slash
	$path = trim($path);
	// rtrim defaults plus /
	$path = rtrim($path, " \n\t\0\x0B/");

	if ($append_slash) {
		$path = $path . '/';
	}

	return $path;
}

/**
 * Queues a message to be displayed.
 *
 * Messages will not be displayed immediately, but are stored in
 * for later display, usually upon next page load.
 *
 * The method of displaying these messages differs depending upon plugins and
 * viewtypes.  The core default viewtype retrieves messages in
 * {@link views/default/page/shells/default.php} and displays messages as
 * javascript popups.
 *
 * @note Internal: Messages are stored as strings in the Elgg session as ['msg'][$register] array.
 *
 * @warning This function is used to both add to and clear the message
 * stack.  If $messages is null, $register will be returned and cleared.
 * If $messages is null and $register is empty, all messages will be
 * returned and removed.
 *
 * @param mixed  $message  Optionally, a single message or array of messages to add, (default: null)
 * @param string $register Types of message: "error", "success" (default: success)
 * @param bool   $count    Count the number of messages (default: false)
 *
 * @return bool|array Either the array of messages, or a response regarding
 *                          whether the message addition was successful.
 */
function system_messages($message = null, $register = "success", $count = false) {
	if ($count) {
		return _elgg_services()->systemMessages->count($register);
	}
	if ($message === null) {
		return _elgg_services()->systemMessages->dumpRegister($register);
	}
	return _elgg_services()->systemMessages->addMessageToRegister($message, $register);
}

/**
 * Counts the number of messages, either globally or in a particular register
 *
 * @param string $register Optionally, the register
 *
 * @return integer The number of messages
 */
function count_messages($register = "") {
	return _elgg_services()->systemMessages->count($register);
}

/**
 * Display a system message on next page load.
 *
 * @see system_messages()
 *
 * @param string|array $message Message or messages to add
 *
 * @return bool
 */
function system_message($message) {
	return _elgg_services()->systemMessages->addSuccessMessage($message);
}

/**
 * Display an error on next page load.
 *
 * @see system_messages()
 *
 * @param string|array $error Error or errors to add
 *
 * @return bool
 */
function register_error($error) {
	return _elgg_services()->systemMessages->addErrorMessage($error);
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
 * Internal note: Events are stored in $CONFIG->events as:
 * <code>
 * $CONFIG->events[$event][$type][$priority] = $callback;
 * </code>
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
 * @param string $object      The object involved in the event
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
 * @param string $object      The object involved in the event
 *
 * @return bool False if any handler returned false, otherwise true
 *
 * @see elgg_trigger_event
 * @see elgg_trigger_after_event
 */
function elgg_trigger_before_event($event, $object_type, $object = null) {
	return _elgg_services()->events->trigger("$event:before", $object_type, $object);
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
 * @see elgg_trigger_before_event
 */
function elgg_trigger_after_event($event, $object_type, $object = null) {
	$options = array(
		\Elgg\EventsService::OPTION_STOPPABLE => false,
	);
	return _elgg_services()->events->trigger("$event:after", $object_type, $object, $options);
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
 * @see elgg_trigger_event
 */
function elgg_trigger_deprecated_event($event, $object_type, $object = null, $message, $version) {
	$options = array(
		\Elgg\EventsService::OPTION_DEPRECATION_MESSAGE => $message,
		\Elgg\EventsService::OPTION_DEPRECATION_VERSION => $version,
	);
	return _elgg_services()->events->trigger($event, $object_type, $object, $options);
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
 * @note Internal: Plugin hooks are stored in $CONFIG->hooks as:
 * <code>
 * $CONFIG->hooks[$hook][$type][$priority] = $callback;
 * </code>
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
	return _elgg_services()->hooks->trigger($hook, $type, $params, $returnvalue);
}

/**
 * Intercepts, logs, and displays uncaught exceptions.
 *
 * To use a viewtype other than failsafe, create the views:
 *  <viewtype>/messages/exceptions/admin_exception
 *  <viewtype>/messages/exceptions/exception
 * See the json viewtype for an example.
 *
 * @warning This function should never be called directly.
 *
 * @see http://www.php.net/set-exception-handler
 *
 * @param Exception $exception The exception being handled
 *
 * @return void
 * @access private
 */
function _elgg_php_exception_handler($exception) {
	$timestamp = time();
	error_log("Exception #$timestamp: $exception");

	// Wipe any existing output buffer
	ob_end_clean();

	// make sure the error isn't cached
	header("Cache-Control: no-cache, must-revalidate", true);
	header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', true);

	// we don't want the 'pagesetup', 'system' event to fire
	global $CONFIG;
	$CONFIG->pagesetupdone = true;

	try {
		// allow custom scripts to trigger on exception
		// $CONFIG->exception_include can be set locally in settings.php
		// value should be a system path to a file to include
		if (!empty($CONFIG->exception_include) && is_file($CONFIG->exception_include)) {
			ob_start();
			include $CONFIG->exception_include;
			$exception_output = ob_get_clean();
			
			// if content is returned from the custom handler we will output
			// that instead of our default failsafe view
			if (!empty($exception_output)) {
				echo $exception_output;
				exit;
			}
		}

		if (elgg_is_xhr()) {
			elgg_set_viewtype('json');
			$response = new \Symfony\Component\HttpFoundation\JsonResponse(null, 500);
		} else {
			elgg_set_viewtype('failsafe');
			$response = new \Symfony\Component\HttpFoundation\Response('', 500);
		}

		if (elgg_is_admin_logged_in()) {
			$body = elgg_view("messages/exceptions/admin_exception", array(
				'object' => $exception,
				'ts' => $timestamp
			));
		} else {
			$body = elgg_view("messages/exceptions/exception", array(
				'object' => $exception,
				'ts' => $timestamp
			));
		}

		$response->setContent(elgg_view_page(elgg_echo('exception:title'), $body));
		$response->send();
	} catch (Exception $e) {
		$timestamp = time();
		$message = $e->getMessage();
		echo "Fatal error in exception handler. Check log for Exception #$timestamp";
		error_log("Exception #$timestamp : fatal error in exception handler : $message");
	}
}

/**
 * Intercepts catchable PHP errors.
 *
 * @warning This function should never be called directly.
 *
 * @internal
 * For catchable fatal errors, throws an Exception with the error.
 *
 * For non-fatal errors, depending upon the debug settings, either
 * log the error or ignore it.
 *
 * @see http://www.php.net/set-error-handler
 *
 * @param int    $errno    The level of the error raised
 * @param string $errmsg   The error message
 * @param string $filename The filename the error was raised in
 * @param int    $linenum  The line number the error was raised at
 * @param array  $vars     An array that points to the active symbol table where error occurred
 *
 * @return true
 * @throws Exception
 * @access private
 * @todo Replace error_log calls with elgg_log calls.
 */
function _elgg_php_error_handler($errno, $errmsg, $filename, $linenum, $vars) {
	$error = date("Y-m-d H:i:s (T)") . ": \"$errmsg\" in file $filename (line $linenum)";

	switch ($errno) {
		case E_USER_ERROR:
			error_log("PHP ERROR: $error");
			register_error("ERROR: $error");

			// Since this is a fatal error, we want to stop any further execution but do so gracefully.
			throw new \Exception($error);
			break;

		case E_WARNING :
		case E_USER_WARNING :
		case E_RECOVERABLE_ERROR: // (e.g. type hint violation)
			
			// check if the error wasn't suppressed by the error control operator (@)
			if (error_reporting()) {
				error_log("PHP WARNING: $error");
			}
			break;

		default:
			global $CONFIG;
			if (isset($CONFIG->debug) && $CONFIG->debug === 'NOTICE') {
				error_log("PHP NOTICE: $error");
			}
	}

	return true;
}


/**
 * Display or log a message.
 *
 * If $level is >= to the debug setting in {@link $CONFIG->debug}, the
 * message will be sent to {@link elgg_dump()}.  Messages with lower
 * priority than {@link $CONFIG->debug} are ignored.
 *
 * Outputs all levels but NOTICE to screen by default.
 *
 * @note No messages will be displayed unless debugging has been enabled.
 *
 * @param string $message User message
 * @param string $level   NOTICE | WARNING | ERROR
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_log($message, $level = 'NOTICE') {
	static $levels = array(
		'INFO' => 200,
		'NOTICE' => 250,
		'WARNING' => 300,
		'DEBUG' => 300,
		'ERROR' => 400,
	);

	if ($level == 'DEBUG') {
		elgg_deprecated_notice("The 'DEBUG' level for logging has been deprecated.", 1.9);
	}

	$level = $levels[$level];
	return _elgg_services()->logger->log($message, $level);
}

/**
 * Logs or displays $value.
 *
 * If $to_screen is true, $value is displayed to screen.  Else,
 * it is handled by PHP's {@link error_log()} function.
 *
 * A {@elgg_plugin_hook debug log} is called.  If a handler returns
 * false, it will stop the default logging method.
 *
 * @param mixed $value     The value
 * @param bool  $to_screen Display to screen?
 * @return void
 * @since 1.7.0
 */
function elgg_dump($value, $to_screen = true) {
	_elgg_services()->logger->dump($value, $to_screen);
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
	global $CONFIG;

	static $version, $release;

	if (isset($CONFIG->path)) {
		if (!isset($version) || !isset($release)) {
			if (!include($CONFIG->path . "version.php")) {
				return false;
			}
		}
		return $human_readable ? $release : $version;
	}

	return false;
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
		return elgg_format_url($string);
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
		$query = array();
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
	return elgg_http_add_url_query_elements($url, array($element => null));
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
		$query = array();
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
function elgg_http_url_is_identical($url1, $url2, $ignore_params = array('offset', 'limit')) {
	$url1 = elgg_normalize_url($url1);
	$url2 = elgg_normalize_url($url2);

	// @todo - should probably do something with relative URLs

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
	$parts = array('scheme', 'host', 'path');

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
	$url1_params = array();
	$url2_params = array();

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
 * Checks for $array[$key] and returns its value if it exists, else
 * returns $default.
 *
 * Shorthand for $value = (isset($array['key'])) ? $array['key'] : 'default';
 *
 * @param string $key     The key to check.
 * @param array  $array   The array to check against.
 * @param mixed  $default Default value to return if nothing is found.
 * @param bool   $strict  Return array key if it's set, even if empty. If false,
 *                        return $default if the array key is unset or empty.
 *
 * @return mixed
 * @since 1.8.0
 */
function elgg_extract($key, array $array, $default = null, $strict = true) {
	if (!is_array($array)) {
		return $default;
	}

	if ($strict) {
		return (isset($array[$key])) ? $array[$key] : $default;
	} else {
		return (isset($array[$key]) && !empty($array[$key])) ? $array[$key] : $default;
	}
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
 * @param array  &$array     Array to sort
 * @param string $element    Element to sort by
 * @param int    $sort_order PHP sort order
 *                           {@link http://us2.php.net/array_multisort}
 * @param int    $sort_type  PHP sort type
 *                           {@link http://us2.php.net/sort}
 *
 * @return bool
 */
function elgg_sort_3d_array_by_value(&$array, $element, $sort_order = SORT_ASC,
$sort_type = SORT_LOCALE_STRING) {

	$sort = array();

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
	switch($last) {
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
 * Normalise the singular keys in an options array to plural keys.
 *
 * Used in elgg_get_entities*() functions to support shortcutting plural
 * names by singular names.
 *
 * @param array $options   The options array. $options['keys'] = 'values';
 * @param array $singulars A list of singular words to pluralize by adding 's'.
 *
 * @return array
 * @since 1.7.0
 * @access private
 */
function _elgg_normalize_plural_options_array($options, $singulars) {
	foreach ($singulars as $singular) {
		$plural = $singular . 's';

		if (array_key_exists($singular, $options)) {
			if ($options[$singular] === ELGG_ENTITIES_ANY_VALUE) {
				$options[$plural] = $options[$singular];
			} else {
				// Test for array refs #2641
				if (!is_array($options[$singular])) {
					$options[$plural] = array($options[$singular]);
				} else {
					$options[$plural] = $options[$singular];
				}
			}
		}

		unset($options[$singular]);
	}

	return $options;
}

/**
 * Emits a shutdown:system event upon PHP shutdown, but before database connections are dropped.
 *
 * @tip Register for the shutdown:system event to perform functions at the end of page loads.
 *
 * @warning Using this event to perform long-running functions is not very
 * useful.  Servers will hold pages until processing is done before sending
 * them out to the browser.
 *
 * @see http://www.php.net/register-shutdown-function
 *
 * @return void
 * @see register_shutdown_hook()
 * @access private
 */
function _elgg_shutdown_hook() {
	global $START_MICROTIME;

	try {
		elgg_trigger_event('shutdown', 'system');

		$time = (float)(microtime(true) - $START_MICROTIME);
		$uri = _elgg_services()->request->server->get('REQUEST_URI', 'CLI');
		// demoted to NOTICE from DEBUG so javascript is not corrupted
		elgg_log("Page {$uri} generated in $time seconds", 'INFO');
	} catch (Exception $e) {
		$message = 'Error: ' . get_class($e) . ' thrown within the shutdown handler. ';
		$message .= "Message: '{$e->getMessage()}' in file {$e->getFile()} (line {$e->getLine()})";
		error_log($message);
		error_log("Exception trace stack: {$e->getTraceAsString()}");
	}

	// Prevent an APC session bug: https://bugs.php.net/bug.php?id=60657
	session_write_close();
}

/**
 * Serve javascript pages.
 *
 * Searches for views under js/ and outputs them with special
 * headers for caching control.
 *
 * @param array $page The page array
 *
 * @return bool
 * @elgg_pagehandler js
 * @access private
 */
function _elgg_js_page_handler($page) {
	return _elgg_cacheable_view_page_handler($page, 'js');
}

/**
 * Serve individual views for Ajax.
 *
 * /ajax/view/<view_name>?<key/value params>
 * /ajax/form/<action_name>?<key/value params>
 *
 * @param string[] $segments URL segments (not including "ajax")
 * @return bool
 *
 * @see elgg_register_ajax_view()
 * @elgg_pagehandler ajax
 * @access private
 */
function _elgg_ajax_page_handler($segments) {
	elgg_ajax_gatekeeper();

	if (count($segments) < 2) {
		return false;
	}

	if ($segments[0] === 'view' || $segments[0] === 'form') {
		if ($segments[0] === 'view') {
			// ignore 'view/'
			$view = implode('/', array_slice($segments, 1));
		} else {
			// form views start with "forms", not "form"
			$view = 'forms/' . implode('/', array_slice($segments, 1));
		}

		$allowed_views = elgg_get_config('allowed_ajax_views');
		if (!array_key_exists($view, $allowed_views)) {
			header('HTTP/1.1 403 Forbidden');
			exit;
		}

		// pull out GET parameters through filter
		$vars = array();
		foreach (_elgg_services()->request->query->keys() as $name) {
			$vars[$name] = get_input($name);
		}

		if (isset($vars['guid'])) {
			$vars['entity'] = get_entity($vars['guid']);
		}

		if ($segments[0] === 'view') {
			// Try to guess the mime-type
			switch ($segments[1]) {
				case "js":
					header("Content-Type: text/javascript");
					break;
				case "css":
					header("Content-Type: text/css");
					break;
			}

			echo elgg_view($view, $vars);
		} else {
			$action = implode('/', array_slice($segments, 1));
			echo elgg_view_form($action, array(), $vars);
		}
		return true;
	}

	return false;
}

/**
 * Serve CSS
 *
 * Serves CSS from the css views directory with headers for caching control
 *
 * @param array $page The page array
 *
 * @return bool
 * @elgg_pagehandler css
 * @access private
 */
function _elgg_css_page_handler($page) {
	if (!isset($page[0])) {
		// default css
		$page[0] = 'elgg';
	}
	
	return _elgg_cacheable_view_page_handler($page, 'css');
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

	// TODO in next 1.x send our default icon
	//header('Content-Type: image/vnd.microsoft.icon');
	//readfile(elgg_get_root_path() . '_graphics/favicon.ico');

	return true;
}

/**
 * Serves a JS or CSS view with headers for caching.
 *
 * /<css||js>/name/of/view.<last_cache>.<css||js>
 *
 * @param array  $page The page array
 * @param string $type The type: js or css
 *
 * @return bool
 * @access private
 */
function _elgg_cacheable_view_page_handler($page, $type) {

	switch ($type) {
		case 'js':
			$content_type = 'text/javascript';
			break;

		case 'css':
			$content_type = 'text/css';
			break;

		default:
			return false;
			break;
	}

	if ($page) {
		// the view file names can have multiple dots
		// eg: views/default/js/calendars/jquery.fullcalendar.min.php
		// translates to the url /js/<ts>/calendars/jquery.fullcalendar.min.js
		// and the view js/calendars/jquery.fullcalendar.min
		// we ignore the last two dots for the ts and the ext.
		// Additionally, the timestamp is optional.
		$page = implode('/', $page);
		$regex = '|(.+?)\.\w+$|';
		if (!preg_match($regex, $page, $matches)) {
			return false;
		}
		$view = "$type/{$matches[1]}";
		if (!elgg_view_exists($view)) {
			return false;
		}
		$return = elgg_view($view);

		header("Content-type: $content_type");

		// @todo should js be cached when simple cache turned off
		//header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
		//header("Pragma: public");
		//header("Cache-Control: public");
		//header("Content-Length: " . strlen($return));

		echo $return;
		return true;
	}
	return false;
}

/**
 * Reverses the ordering in an ORDER BY clause.  This is achived by replacing
 * asc with desc, or appending desc to the end of the clause.
 *
 * This is used mostly for elgg_get_entities() and other similar functions.
 *
 * @param string $order_by An order by clause
 * @access private
 * @return string
 * @access private
 */
function _elgg_sql_reverse_order_by_clause($order_by) {
	$order_by = strtolower($order_by);

	if (strpos($order_by, ' asc') !== false) {
		$return = str_replace(' asc', ' desc', $order_by);
	} elseif (strpos($order_by, ' desc') !== false) {
		$return = str_replace(' desc', ' asc', $order_by);
	} else {
		// no order specified, so default to desc since mysql defaults to asc
		$return = $order_by . ' desc';
	}

	return $return;
}

/**
 * Enable objects with an enable() method.
 *
 * Used as a callback for \ElggBatch.
 *
 * @todo why aren't these static methods on \ElggBatch?
 *
 * @param object $object The object to enable
 * @return bool
 * @access private
 */
function elgg_batch_enable_callback($object) {
	// our db functions return the number of rows affected...
	return $object->enable() ? true : false;
}

/**
 * Disable objects with a disable() method.
 *
 * Used as a callback for \ElggBatch.
 *
 * @param object $object The object to disable
 * @return bool
 * @access private
 */
function elgg_batch_disable_callback($object) {
	// our db functions return the number of rows affected...
	return $object->disable() ? true : false;
}

/**
 * Delete objects with a delete() method.
 *
 * Used as a callback for \ElggBatch.
 *
 * @param object $object The object to disable
 * @return bool
 * @access private
 */
function elgg_batch_delete_callback($object) {
	// our db functions return the number of rows affected...
	return $object->delete() ? true : false;
}

/**
 * Checks if there are some constraints on the options array for
 * potentially dangerous operations.
 *
 * @param array  $options Options array
 * @param string $type    Options type: metadata or annotation
 * @return bool
 * @access private
 */
function _elgg_is_valid_options_for_batch_operation($options, $type) {
	if (!$options || !is_array($options)) {
		return false;
	}

	// at least one of these is required.
	$required = array(
		// generic restraints
		'guid', 'guids'
	);

	switch ($type) {
		case 'metadata':
			$metadata_required = array(
				'metadata_owner_guid', 'metadata_owner_guids',
				'metadata_name', 'metadata_names',
				'metadata_value', 'metadata_values'
			);

			$required = array_merge($required, $metadata_required);
			break;

		case 'annotations':
		case 'annotation':
			$annotations_required = array(
				'annotation_owner_guid', 'annotation_owner_guids',
				'annotation_name', 'annotation_names',
				'annotation_value', 'annotation_values'
			);

			$required = array_merge($required, $annotations_required);
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
 * Intercepts the index page when Walled Garden mode is enabled.
 *
 * @return bool
 * @access private
 */
function _elgg_walled_garden_index() {

	elgg_load_css('elgg.walled_garden');
	elgg_load_js('elgg.walled_garden');
	
	$content = elgg_view('core/walled_garden/login');

	$params = array(
		'content' => $content,
		'class' => 'elgg-walledgarden-double',
		'id' => 'elgg-walledgarden-login',
	);
	$body = elgg_view_layout('walled_garden', $params);
	echo elgg_view_page('', $body, 'walled_garden');

	return true;
}


/**
 * Serve walled garden sections
 *
 * @param array $page Array of URL segments
 * @return string
 * @access private
 */
function _elgg_walled_garden_ajax_handler($page) {
	$view = $page[0];
	$params = array(
		'content' => elgg_view("core/walled_garden/$view"),
		'class' => 'elgg-walledgarden-single hidden',
		'id' => str_replace('_', '-', "elgg-walledgarden-$view"),
	);
	echo elgg_view_layout('walled_garden', $params);
	return true;
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
	global $CONFIG;

	elgg_register_css('elgg.walled_garden', elgg_get_simplecache_url('css', 'walled_garden'));
	elgg_register_js('elgg.walled_garden', elgg_get_simplecache_url('js', 'walled_garden'));

	elgg_register_page_handler('walled_garden', '_elgg_walled_garden_ajax_handler');

	// check for external page view
	if (isset($CONFIG->site) && $CONFIG->site instanceof \ElggSite) {
		$CONFIG->site->checkWalledGarden();
	}
}

/**
 * Remove public access for walled gardens
 *
 * @param string $hook
 * @param string $type
 * @param array $accesses
 * @return array
 * @access private
 */
function _elgg_walled_garden_remove_public_access($hook, $type, $accesses) {
	if (isset($accesses[ACCESS_PUBLIC])) {
		unset($accesses[ACCESS_PUBLIC]);
	}
	return $accesses;
}

/**
 * Boots the engine
 *
 * 1. sets error handlers
 * 2. connects to database
 * 3. verifies the installation succeeded
 * 4. loads application configuration
 * 5. loads i18n data
 * 6. loads cached autoloader state
 * 7. loads site configuration
 *
 * @access private
 */
function _elgg_engine_boot() {
	// Register the error handlers
	set_error_handler('_elgg_php_error_handler');
	set_exception_handler('_elgg_php_exception_handler');

	_elgg_services()->db->setupConnections();

	_elgg_services()->db->assertInstalled();

	_elgg_load_application_config();

	_elgg_load_autoload_cache();

	_elgg_load_site_config();

	_elgg_session_boot();

	_elgg_services()->systemCache->loadAll();

	_elgg_services()->translator->loadTranslations();
}

/**
 * Elgg's main init.
 *
 * Handles core actions for comments, the JS pagehandler, and the shutdown function.
 *
 * @elgg_event_handler init system
 * @return void
 * @access private
 */
function _elgg_init() {
	global $CONFIG;

	elgg_register_action('comment/save');
	elgg_register_action('comment/delete');

	elgg_register_page_handler('js', '_elgg_js_page_handler');
	elgg_register_page_handler('css', '_elgg_css_page_handler');
	elgg_register_page_handler('ajax', '_elgg_ajax_page_handler');
	elgg_register_page_handler('favicon.ico', '_elgg_favicon_page_handler');

	elgg_register_page_handler('manifest.json', function() {
		$site = elgg_get_site_entity();
		$resource = new \Elgg\Http\WebAppManifestResource($site);
		header('Content-Type: application/json');
		echo json_encode($resource->get());
		return true;
	});

	elgg_register_plugin_hook_handler('head', 'page', function($hook, $type, array $result) {
		$result['links']['manifest'] = [
			'rel' => 'manifest',
			'href' => elgg_normalize_url('/manifest.json'),
		];

		return $result;
	});

	elgg_register_js('elgg.autocomplete', 'js/lib/ui.autocomplete.js');
	elgg_register_js('jquery.ui.autocomplete.html', 'vendors/jquery/jquery.ui.autocomplete.html.js');

	elgg_define_js('jquery.ui.autocomplete.html', array(
		'src' => '/vendors/jquery/jquery.ui.autocomplete.html.js',
		'deps' => array('jquery.ui')
	));
	
	elgg_register_external_view('js/elgg/UserPicker.js', true);

	elgg_register_js('elgg.friendspicker', 'js/lib/ui.friends_picker.js');
	elgg_register_js('elgg.avatar_cropper', 'js/lib/ui.avatar_cropper.js');
	elgg_register_js('jquery.imgareaselect', 'vendors/jquery/jquery.imgareaselect/scripts/jquery.imgareaselect.min.js');
	elgg_register_js('elgg.ui.river', 'js/lib/ui.river.js');

	elgg_register_css('jquery.imgareaselect', 'vendors/jquery/jquery.imgareaselect/css/imgareaselect-deprecated.css');
	
	// Trigger the shutdown:system event upon PHP shutdown.
	register_shutdown_function('_elgg_shutdown_hook');
	
	// Sets a blacklist of words in the current language.
	// This is a comma separated list in word:blacklist.
	// @todo possibly deprecate
	$CONFIG->wordblacklist = array();
	$list = explode(',', elgg_echo('word:blacklist'));
	if ($list) {
		foreach ($list as $l) {
			$CONFIG->wordblacklist[] = trim($l);
		}
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
 */
function _elgg_api_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggTravisInstallTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreHelpersTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreRegressionBugsTest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggBatchTest.php';
	return $value;
}

/**#@+
 * Controls access levels on \ElggEntity entities, metadata, and annotations.
 *
 * @warning ACCESS_DEFAULT is a place holder for the input/access view. Do not
 * use it when saving an entity.
 *
 * @var int
 */
define('ACCESS_DEFAULT', -1);
define('ACCESS_PRIVATE', 0);
define('ACCESS_LOGGED_IN', 1);
define('ACCESS_PUBLIC', 2);
define('ACCESS_FRIENDS', -2);
/**#@-*/

/**
 * Constant to request the value of a parameter be ignored in elgg_get_*() functions
 *
 * @see elgg_get_entities()
 * @var null
 * @since 1.7
 */
define('ELGG_ENTITIES_ANY_VALUE', null);

/**
 * Constant to request the value of a parameter be nothing in elgg_get_*() functions.
 *
 * @see elgg_get_entities()
 * @var int 0
 * @since 1.7
 */
define('ELGG_ENTITIES_NO_VALUE', 0);

/**
 * Used in calls to forward() to specify the browser should be redirected to the
 * referring page.
 *
 * @see forward
 * @var int -1
 */
define('REFERRER', -1);

/**
 * Alternate spelling for REFERRER.  Included because of some bad documentation
 * in the original HTTP spec.
 *
 * @see forward()
 * @link http://en.wikipedia.org/wiki/HTTP_referrer#Origin_of_the_term_referer
 * @var int -1
 */
define('REFERER', -1);

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_init');
	$events->registerHandler('boot', 'system', '_elgg_engine_boot', 1);
	$hooks->registerHandler('unit_test', 'system', '_elgg_api_test');

	$events->registerHandler('init', 'system', '_elgg_walled_garden_init', 1000);
};
