<?php
/**
 * Bootstrapping and helper procedural code available for use in Elgg core and plugins.
 *
 * @package Elgg.Core
 * @todo These functions can't be subpackaged because they cover a wide mix of
 * purposes and subsystems.  Many of them should be moved to more relevant files.
 */

// prep core classes to be autoloadable
spl_autoload_register('_elgg_autoload');
elgg_register_classes(dirname(dirname(__FILE__)) . '/classes');

/**
 * Autoload classes
 *
 * @param string $class The name of the class
 *
 * @return void
 * @throws Exception
 */
function _elgg_autoload($class) {
	global $CONFIG;

	if (!isset($CONFIG->classes[$class]) || !include($CONFIG->classes[$class])) {
		return false;
	}
}

/**
 * Register all files found in $dir as classes
 * Need to be named MyClass.php
 *
 * @param string $dir The dir to look in
 *
 * @return void
 * @since 1.8.0
 */
function elgg_register_classes($dir) {
	$classes = elgg_get_file_list($dir, array(), array(), array('.php'));

	foreach ($classes as $class) {
		elgg_register_class(basename($class, '.php'), $class);
	}
}

/**
 * Register a classname to a file.
 *
 * @param string $class    The name of the class
 * @param string $location The location of the file
 *
 * @return true
 * @since 1.8.0
 */
function elgg_register_class($class, $location) {
	global $CONFIG;

	if (!isset($CONFIG->classes)) {
		$CONFIG->classes = array();
	}

	$CONFIG->classes[$class] = $location;

	return true;
}

/**
 * Register a php library.
 *
 * @param string $name     The name of the library
 * @param string $location The location of the file
 *
 * @return void
 * @since 1.8.0
 */
function elgg_register_library($name, $location) {
	global $CONFIG;

	if (!isset($CONFIG->libraries)) {
		$CONFIG->libraries = array();
	}

	$CONFIG->libraries[$name] = $location;
}

/**
 * Load a php library.
 *
 * @param string $name The name of the library
 *
 * @return void
 * @throws InvalidParameterException
 * @since 1.8.0
 */
function elgg_load_library($name) {
	global $CONFIG;

	if (!isset($CONFIG->libraries)) {
		$CONFIG->libraries = array();
	}

	if (!isset($CONFIG->libraries[$name])) {
		$error = elgg_echo('InvalidParameterException:LibraryNotRegistered', array($name));
		throw new InvalidParameterException($error);
	}

	if (!include_once($CONFIG->libraries[$name])) {
		$error = elgg_echo('InvalidParameterException:LibraryNotRegistered', array($name));
		throw new InvalidParameterException($error);
	}
}

/**
 * Forward to $location.
 *
 * Sends a 'Location: $location' header and exists.  If headers have
 * already been sent, returns FALSE.
 *
 * @param string $location URL to forward to browser to. Can be path relative to the network's URL.
 * @param string $reason   Short explanation for why we're forwarding
 *
 * @return False False if headers have been sent. Terminates execution if forwarding.
 */
function forward($location = "", $reason = 'system') {
	global $CONFIG;

	if (!headers_sent()) {
		if ($location === REFERER) {
			$location = $_SERVER['HTTP_REFERER'];
		}

		$location = elgg_normalize_url($location);

		// return new forward location or false to stop the forward or empty string to exit
		$current_page = current_page_url();
		$params = array('current_url' => $current_page, 'forward_url' => $location);
		$location = elgg_trigger_plugin_hook('forward', $reason, $params, $location);

		if ($location) {
			header("Location: {$location}");
			exit;
		} else if ($location === '') {
			exit;
		}
	}

	return false;
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
 * @param int    $priority Priority of the CSS file (lower numbers load earlier)
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_register_js($name, $url, $location = 'head', $priority = 500) {
	return elgg_register_external_file('js', $name, $url, $location, $priority);
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
function elgg_register_css($name, $url, $priority = 500) {
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
	global $CONFIG;

	if (empty($name) || empty($url)) {
		return false;
	}

	$url = elgg_format_url($url);
	$url = elgg_normalize_url($url);
	
	if (!isset($CONFIG->externals)) {
		$CONFIG->externals = array();
	}

	if (!isset($CONFIG->externals[$type])) {
		$CONFIG->externals[$type] = array();
	}

	$name = trim(strtolower($name));

	if (isset($CONFIG->externals[$type][$name])) {
		// update a registered item
		$item = $CONFIG->externals[$type][$name];

	} else {
		$item = new stdClass();
		$item->loaded = false;
	}

	$item->url = $url;
	$item->priority = max((int)$priority, 0);
	$item->location = $location;

	$CONFIG->externals[$type][$name] = $item;

	return true;
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
	global $CONFIG;

	if (!isset($CONFIG->externals)) {
		return false;
	}

	if (!isset($CONFIG->externals[$type])) {
		return false;
	}

	$name = trim(strtolower($name));
	
	if (array_key_exists($name, $CONFIG->externals[$type])) {
		unset($CONFIG->externals[$type][$name]);
		return true;
	}

	return false;
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
	global $CONFIG;

	if (!isset($CONFIG->externals)) {
		$CONFIG->externals = array();
	}

	if (!isset($CONFIG->externals[$type])) {
		$CONFIG->externals[$type] = array();
	}

	$name = trim(strtolower($name));

	if (isset($CONFIG->externals[$type][$name])) {
		// update a registered item
		$CONFIG->externals[$type][$name]->loaded = true;
	} else {
		$item = new stdClass();
		$item->loaded = true;
		$item->url = '';
		$item->location = '';
		$item->priority = 500;

		$CONFIG->externals[$type][$name] = $item;
	}
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
	global $CONFIG;

	if (isset($CONFIG->externals) && isset($CONFIG->externals[$type])) {
		$items = array_values($CONFIG->externals[$type]);

		$callback = "return \$v->loaded == true && \$v->location == '$location';";
		$items = array_filter($items, create_function('$v', $callback));
		if ($items) {
			usort($items, create_function('$a,$b','return $a->priority >= $b->priority;'));
			array_walk($items, create_function('&$v,$k', '$v = $v->url;'));
		}
		return $items;
	}
	return array();
}

/**
 * Returns a list of files in $directory.
 *
 * Only returns files.  Does not recurse into subdirs.
 *
 * @param string $directory  Directory to look in
 * @param array  $exceptions Array of filenames to ignore
 * @param array  $list       Array of files to append to
 * @param mixed  $extensions Array of extensions to allow, NULL for all. Use a dot: array('.php').
 *
 * @return array Filenames in $directory, in the form $directory/filename.
 */
function elgg_get_file_list($directory, $exceptions = array(), $list = array(),
$extensions = NULL) {

	$directory = sanitise_filepath($directory);
	if ($handle = opendir($directory)) {
		while (($file = readdir($handle)) !== FALSE) {
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
function sanitise_filepath($path, $append_slash = TRUE) {
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
 * @internal Messages are stored as strings in the $_SESSION['msg'][$register] array.
 *
 * @warning This function is used to both add to and clear the message
 * stack.  If $messages is null, $register will be returned and cleared.
 * If $messages is null and $register is empty, all messages will be
 * returned and removed.
 *
 * @important This function handles the standard {@link system_message()} ($register =
 * 'messages') as well as {@link register_error()} messages ($register = 'errors').
 *
 * @param mixed  $message  Optionally, a single message or array of messages to add, (default: null)
 * @param string $register Types of message: "error", "success" (default: success)
 * @param bool   $count    Count the number of messages (default: false)
 *
 * @return true|false|array Either the array of messages, or a response regarding
 *                          whether the message addition was successful.
 * @todo Clean up. Separate registering messages and retrieving them.
 */
function system_messages($message = null, $register = "success", $count = false) {
	if (!isset($_SESSION['msg'])) {
		$_SESSION['msg'] = array();
	}
	if (!isset($_SESSION['msg'][$register]) && !empty($register)) {
		$_SESSION['msg'][$register] = array();
	}
	if (!$count) {
		if (!empty($message) && is_array($message)) {
			$_SESSION['msg'][$register] = array_merge($_SESSION['msg'][$register], $message);
			return true;
		} else if (!empty($message) && is_string($message)) {
			$_SESSION['msg'][$register][] = $message;
			return true;
		} else if (is_null($message)) {
			if ($register != "") {
				$returnarray = array();
				$returnarray[$register] = $_SESSION['msg'][$register];
				$_SESSION['msg'][$register] = array();
			} else {
				$returnarray = $_SESSION['msg'];
				$_SESSION['msg'] = array();
			}
			return $returnarray;
		}
	} else {
		if (!empty($register)) {
			return sizeof($_SESSION['msg'][$register]);
		} else {
			$count = 0;
			foreach ($_SESSION['msg'] as $register => $submessages) {
				$count += sizeof($submessages);
			}
			return $count;
		}
	}
	return false;
}

/**
 * Counts the number of messages, either globally or in a particular register
 *
 * @param string $register Optionally, the register
 *
 * @return integer The number of messages
 */
function count_messages($register = "") {
	return system_messages(null, $register, true);
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
	return system_messages($message, "success");
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
	return system_messages($error, "error");
}

/**
 * Register a callback as an Elgg event handler.
 *
 * Events are emitted by Elgg when certain actions occur.  Plugins
 * can respond to these events or halt them completely by registering a handler
 * as a callback to an event.  Multiple handlers can be registered for
 * the same event and will be executed in order of $priority.  Any handler
 * returning false will halt the execution chain.
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
 * @internal Events are stored in $CONFIG->events as:
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
 * @link http://docs.elgg.org/Tutorials/Plugins/Events
 * @example events/basic.php    Basic example of registering an event handler callback.
 * @example events/advanced.php Advanced example of registering an event handler
 *                              callback and halting execution.
 * @example events/all.php      Example of how to use the 'all' keyword.
 */
function elgg_register_event_handler($event, $object_type, $callback, $priority = 500) {
	global $CONFIG;

	if (empty($event) || empty($object_type)) {
		return FALSE;
	}

	if (!isset($CONFIG->events)) {
		$CONFIG->events = array();
	}
	if (!isset($CONFIG->events[$event])) {
		$CONFIG->events[$event] = array();
	}
	if (!isset($CONFIG->events[$event][$object_type])) {
		$CONFIG->events[$event][$object_type] = array();
	}

	if (!is_callable($callback)) {
		return FALSE;
	}

	$priority = max((int) $priority, 0);

	while (isset($CONFIG->events[$event][$object_type][$priority])) {
		$priority++;
	}
	$CONFIG->events[$event][$object_type][$priority] = $callback;
	ksort($CONFIG->events[$event][$object_type]);
	return TRUE;
}

/**
 * Unregisters a callback for an event.
 *
 * @param string $event       The event type
 * @param string $object_type The object type
 * @param string $callback    The callback
 *
 * @return void
 * @since 1.7
 */
function elgg_unregister_event_handler($event, $object_type, $callback) {
	global $CONFIG;
	foreach ($CONFIG->events[$event][$object_type] as $key => $event_callback) {
		if ($event_callback == $callback) {
			unset($CONFIG->events[$event][$object_type][$key]);
		}
	}
}

/**
 * Trigger an Elgg Event and run all handler callbacks registered to that event, type.
 *
 * This function runs all handlers registered to $event, $object_type or
 * the special keyword 'all' for either or both.
 *
 * $event is usually a verb: create, update, delete, annotation.
 *
 * $object_type is usually a noun: object, group, user, annotation, relationship, metadata.
 *
 * $object is usually an Elgg* object assciated with the event.
 *
 * @warning Elgg events should only be triggered by core.  Plugin authors should use
 * {@link trigger_elgg_plugin_hook()} instead.
 *
 * @tip When referring to events, the preferred syntax is "event, type".
 *
 * @internal Only rarely should events be changed, added, or removed in core.
 * When making changes to events, be sure to first create a ticket in trac.
 *
 * @internal @tip Think of $object_type as the primary namespace element, and
 * $event as the secondary namespace.
 *
 * @param string $event       The event type
 * @param string $object_type The object type
 * @param string $object      The object involved in the event
 *
 * @return bool The result of running all handler callbacks.
 * @link http://docs.elgg.org/Tutorials/Core/Events
 * @internal @example events/emit.php Basic emitting of an Elgg event.
 */
function elgg_trigger_event($event, $object_type, $object = null) {
	global $CONFIG;

	$events = array();
	if (isset($CONFIG->events[$event][$object_type])) {
		$events[] = $CONFIG->events[$event][$object_type];
	}
	if (isset($CONFIG->events['all'][$object_type])) {
		$events[] = $CONFIG->events['all'][$object_type];
	}
	if (isset($CONFIG->events[$event]['all'])) {
		$events[] = $CONFIG->events[$event]['all'];
	}
	if (isset($CONFIG->events['all']['all'])) {
		$events[] = $CONFIG->events['all']['all'];
	}

	$args = array($event, $object_type, $object);

	foreach ($events as $callback_list) {
		if (is_array($callback_list)) {
			foreach ($callback_list as $callback) {
				if (call_user_func_array($callback, $args) === FALSE) {
					return FALSE;
				}
			}
		}
	}

	return TRUE;
}

/**
 * Register a callback as a plugin hook handler.
 *
 * Plugin hooks allow developers to losely couple plugins and features by
 * repsonding to and emitting {@link elgg_trigger_plugin_hook()} customizable hooks.
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
 * @internal Plugin hooks are stored in $CONFIG->hooks as:
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
 * @param callback $callback The name of a valid function or an array with object and method
 * @param int      $priority The priority - 500 is default, lower numbers called first
 *
 * @return bool
 *
 * @example hooks/register/basic.php Registering for a plugin hook and examining the variables.
 * @example hooks/register/advanced.php Registering for a plugin hook and changing the params.
 * @link http://docs.elgg.org/Tutorials/Plugins/Hooks
 * @since 1.8.0
 */
function elgg_register_plugin_hook_handler($hook, $type, $callback, $priority = 500) {
	global $CONFIG;

	if (empty($hook) || empty($type)) {
		return FALSE;
	}

	if (!isset($CONFIG->hooks)) {
		$CONFIG->hooks = array();
	}
	if (!isset($CONFIG->hooks[$hook])) {
		$CONFIG->hooks[$hook] = array();
	}
	if (!isset($CONFIG->hooks[$hook][$type])) {
		$CONFIG->hooks[$hook][$type] = array();
	}

	if (!is_callable($callback)) {
		return FALSE;
	}

	$priority = max((int) $priority, 0);

	while (isset($CONFIG->hooks[$hook][$type][$priority])) {
		$priority++;
	}
	$CONFIG->hooks[$hook][$type][$priority] = $callback;
	ksort($CONFIG->hooks[$hook][$type]);
	return TRUE;
}

/**
 * Unregister a callback as a plugin hook.
 *
 * @param string   $hook        The name of the hook
 * @param string   $entity_type The name of the type of entity (eg "user", "object" etc)
 * @param callback $callback    The PHP callback to be removed
 *
 * @return void
 * @since 1.8.0
 */
function elgg_unregister_plugin_hook_handler($hook, $entity_type, $callback) {
	global $CONFIG;
	foreach ($CONFIG->hooks[$hook][$entity_type] as $key => $hook_callback) {
		if ($hook_callback == $callback) {
			unset($CONFIG->hooks[$hook][$entity_type][$key]);
		}
	}
}

/**
 * Trigger a Plugin Hook and run all handler callbacks registered to that hook:type.
 *
 * This function runs all handlers regsitered to $hook, $type or
 * the special keyword 'all' for either or both.
 *
 * Use $params to send additional information to the handler callbacks.
 *
 * $returnvalue Is the initial value to pass to the handlers, which can
 * then change it.  It is useful to use $returnvalue to set defaults.
 * If no handlers are registered, $returnvalue is immediately returned.
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
 * @internal The checks for $hook and/or $type not being equal to 'all' is to
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
 * @example hooks/trigger/basic.php    Trigger a hook that determins if execution
 *                                     should continue.
 * @example hooks/trigger/advanced.php Trigger a hook with a default value and use
 *                                     the results to populate a menu.
 * @example hooks/basic.php            Trigger and respond to a basic plugin hook.
 * @link http://docs.elgg.org/Tutorials/Plugins/Hooks
 *
 * @since 1.8.0
 */
function elgg_trigger_plugin_hook($hook, $type, $params = null, $returnvalue = null) {
	global $CONFIG;

	$hooks = array();
	if (isset($CONFIG->hooks[$hook][$type])) {
		if ($hook != 'all' && $type != 'all') {
			$hooks[] = $CONFIG->hooks[$hook][$type];
		}
	}
	if (isset($CONFIG->hooks['all'][$type])) {
		if ($type != 'all') {
			$hooks[] = $CONFIG->hooks['all'][$type];
		}
	}
	if (isset($CONFIG->hooks[$hook]['all'])) {
		if ($hook != 'all') {
			$hooks[] = $CONFIG->hooks[$hook]['all'];
		}
	}
	if (isset($CONFIG->hooks['all']['all'])) {
		$hooks[] = $CONFIG->hooks['all']['all'];
	}

	foreach ($hooks as $callback_list) {
		if (is_array($callback_list)) {
			foreach ($callback_list as $hookcallback) {
				$args = array($hook, $type, $returnvalue, $params);
				$temp_return_value = call_user_func_array($hookcallback, $args);
				if (!is_null($temp_return_value)) {
					$returnvalue = $temp_return_value;
				}
			}
		}
	}

	return $returnvalue;
}

/**
 * Intercepts, logs, and displays uncaught exceptions.
 *
 * @warning This function should never be called directly.
 *
 * @see http://www.php.net/set-exception-handler
 *
 * @param Exception $exception The exception being handled
 *
 * @return void
 */
function _elgg_php_exception_handler($exception) {
	error_log("*** FATAL EXCEPTION *** : " . $exception);

	// Wipe any existing output buffer
	ob_end_clean();

	// make sure the error isn't cached
	header("Cache-Control: no-cache, must-revalidate", true);
	header('Expires: Fri, 05 Feb 1982 00:00:00 -0500', true);
	// @note Do not send a 500 header because it is not a server error

	try {
		// we don't want the 'pagesetup', 'system' event to fire
		global $CONFIG;
		$CONFIG->pagesetupdone = true;

		elgg_set_viewtype('failsafe');
		$body = elgg_view("messages/exceptions/exception", array('object' => $exception));
		echo elgg_view_page(elgg_echo('exception:title'), $body);
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
 */
function _elgg_php_error_handler($errno, $errmsg, $filename, $linenum, $vars) {
	$error = date("Y-m-d H:i:s (T)") . ": \"$errmsg\" in file $filename (line $linenum)";

	switch ($errno) {
		case E_USER_ERROR:
			error_log("PHP ERROR: $error");
			register_error("ERROR: $error");

			// Since this is a fatal error, we want to stop any further execution but do so gracefully.
			throw new Exception($error);
			break;

		case E_WARNING :
		case E_USER_WARNING :
			error_log("PHP WARNING: $error");
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
 * {@link elgg_dump()} outputs all levels but NOTICE to screen by default.
 *
 * @note No messages will be displayed unless debugging has been enabled.
 *
 * @param str $message User message
 * @param str $level   NOTICE | WARNING | ERROR | DEBUG
 *
 * @return bool
 * @since 1.7.0
 * @todo This is complicated and confusing.  Using int constants for debug levels will
 * make things easier.
 */
function elgg_log($message, $level = 'NOTICE') {
	global $CONFIG;

	// only log when debugging is enabled
	if (isset($CONFIG->debug)) {
		// debug to screen or log?
		$to_screen = !($CONFIG->debug == 'NOTICE');

		switch ($level) {
			case 'ERROR':
				// always report
				elgg_dump("$level: $message", $to_screen, $level);
				break;
			case 'WARNING':
			case 'DEBUG':
				// report except if user wants only errors
				if ($CONFIG->debug != 'ERROR') {
					elgg_dump("$level: $message", $to_screen, $level);
				}
				break;
			case 'NOTICE':
			default:
				// only report when lowest level is desired
				if ($CONFIG->debug == 'NOTICE') {
					elgg_dump("$level: $message", FALSE, $level);
				}
				break;
		}

		return TRUE;
	}

	return FALSE;
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
 * @param mixed  $value     The value
 * @param bool   $to_screen Display to screen?
 * @param string $level     The debug level
 *
 * @return void
 * @since 1.7.0
 */
function elgg_dump($value, $to_screen = TRUE, $level = 'NOTICE') {
	global $CONFIG;

	// plugin can return false to stop the default logging method
	$params = array('level' => $level,
					'msg' => $value,
					'to_screen' => $to_screen);
	if (!elgg_trigger_plugin_hook('debug', 'log', $params, true)) {
		return;
	}

	// Do not want to write to screen before page creation has started.
	// This is not fool-proof but probably fixes 95% of the cases when logging
	// results in data sent to the browser before the page is begun.
	if (!isset($CONFIG->pagesetupdone)) {
		$to_screen = FALSE;
	}

	if ($to_screen == TRUE) {
		echo '<pre>';
		print_r($value);
		echo '</pre>';
	} else {
		error_log(print_r($value, TRUE));
	}
}

/**
 * Sends a notice about deprecated use of a function, view, etc.
 *
 * This function either displays or logs the deprecation message,
 * depending upon the deprecation policies in {@link CODING.txt}.
 * Logged messages are sent with the level of 'WARNING'.
 *
 * A user-visual message will be displayed if $dep_version is greater
 * than 1 minor releases lower than the current Elgg version, or at all
 * lower than the current Elgg major version.
 *
 * @note This will always at least log a warning.  Don't use to pre-deprecate things.
 * This assumes we are releasing in order and deprecating according to policy.
 *
 * @see CODING.txt
 *
 * @param str $msg             Message to log / display.
 * @param str $dep_version     Human-readable *release* version: 1.7, 1.7.3
 * @param int $backtrace_level How many levels back to display the backtrace. Useful if calling from
 *                             functions that are called from other places (like elgg_view()). Set
 *                             to -1 for a full backtrace.
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_deprecated_notice($msg, $dep_version, $backtrace_level = 1) {
	// if it's a major release behind, visual and logged
	// if it's a 1 minor release behind, visual and logged
	// if it's for current minor release, logged.
	// bugfixes don't matter because you're not deprecating between them, RIGHT?

	if (!$dep_version) {
		return FALSE;
	}

	$elgg_version = get_version(TRUE);
	$elgg_version_arr = explode('.', $elgg_version);
	$elgg_major_version = (int)$elgg_version_arr[0];
	$elgg_minor_version = (int)$elgg_version_arr[1];

	$dep_major_version = (int)$dep_version;
	$dep_minor_version = 10 * ($dep_version - $dep_major_version);

	$visual = FALSE;

	if (($dep_major_version < $elgg_major_version) ||
		($dep_minor_version < $elgg_minor_version)) {
		$visual = TRUE;
	}

	$msg = "Deprecated in $dep_major_version.$dep_minor_version: $msg";

	if ($visual) {
		register_error($msg);
	}

	// Get a file and line number for the log. Never show this in the UI.
	// Skip over the function that sent this notice and see who called the deprecated
	// function itself.
	$msg .= " Called from ";
	$stack = array();
	$backtrace = debug_backtrace();
	// never show this call.
	array_shift($backtrace);
	$i = count($backtrace);

	foreach ($backtrace as $trace) {
		$stack[] = "[#$i] {$trace['file']}:{$trace['line']}";
		$i--;

		if ($backtrace_level > 0) {
			if ($backtrace_level <= 1) {
				break;
			}
			$backtrace_level--;
		}
	}

	$msg .= implode("<br /> -> ", $stack);

	elgg_log($msg, 'WARNING');

	return TRUE;
}

/**
 * Returns the current page's complete URL.
 *
 * The current URL is assembled using the network's wwwroot and the request URI
 * in $_SERVER as populated by the web server.  This function will include
 * any schemes, usernames and passwords, and ports.
 *
 * @return string The current page URL.
 */
function current_page_url() {
	global $CONFIG;

	$url = parse_url(elgg_get_site_url());

	$page = $url['scheme'] . "://";

	// user/pass
	if ((isset($url['user'])) && ($url['user'])) {
		$page .= $url['user'];
	}
	if ((isset($url['pass'])) && ($url['pass'])) {
		$page .= ":" . $url['pass'];
	}
	if ((isset($url['user']) && $url['user']) ||
		(isset($url['pass']) && $url['pass'])) {
		$page .= "@";
	}

	$page .= $url['host'];

	if ((isset($url['port'])) && ($url['port'])) {
		$page .= ":" . $url['port'];
	}

	$page = trim($page, "/");

	$page .= $_SERVER['REQUEST_URI'];

	return $page;
}

/**
 * Return the full URL of the current page.
 *
 * @return string The URL
 * @todo Combine / replace with current_page_url()
 */
function full_url() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0,
		strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;

	$port = ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") ?
		"" : (":" . $_SERVER["SERVER_PORT"]);

	// This is here to prevent XSS in poorly written browsers used by 80% of the population.
	// {@trac [5813]}
	$quotes = array('\'', '"');
	$encoded = array('%27', '%22');

	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port .
		str_replace($quotes, $encoded, $_SERVER['REQUEST_URI']);
}

/**
 * Builds a URL from the a parts array like one returned by {@link parse_url()}.
 *
 * @note If only partial information is passed, a partial URL will be returned.
 *
 * @param array $parts       Associative array of URL components like parse_url() returns
 * @param bool  $html_encode HTML Encode the url?
 *
 * @return str Full URL
 * @since 1.7.0
 */
function elgg_http_build_url(array $parts, $html_encode = TRUE) {
	// build only what's given to us.
	$scheme = isset($parts['scheme']) ? "{$parts['scheme']}://" : '';
	$host = isset($parts['host']) ? "{$parts['host']}" : '';
	$port = isset($parts['port']) ? ":{$parts['port']}" : '';
	$path = isset($parts['path']) ? "{$parts['path']}" : '';
	$query = isset($parts['query']) ? "?{$parts['query']}" : '';

	$string = $scheme . $host . $port . $path . $query;

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
 * @param str  $url         Full action URL
 * @param bool $html_encode HTML encode the url? (default: false)
 *
 * @return str URL with action tokens
 * @since 1.7.0
 * @link http://docs.elgg.org/Tutorials/Actions
 */
function elgg_add_action_tokens_to_url($url, $html_encode = FALSE) {
	$components = parse_url(elgg_normalize_url($url));

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
	$url_array = parse_url($url);

	if (isset($url_array['query'])) {
		$query = elgg_parse_str($url_array['query']);
	} else {
		// nothing to remove. Return original URL.
		return $url;
	}

	if (array_key_exists($element, $query)) {
		unset($query[$element]);
	}

	$url_array['query'] = http_build_query($query);
	$string = elgg_http_build_url($url_array);
	return $string;
}

/**
 * Adds an element or elements to a URL's query string.
 *
 * @param str   $url      The URL
 * @param array $elements Key/value pairs to add to the URL
 *
 * @return str The new URL with the query strings added
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
		$query[$k] = $v;
	}

	$url_array['query'] = http_build_query($query);
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
	global $CONFIG;

	// if the server portion is missing but it starts with / then add the url in.
	// @todo use elgg_normalize_url()
	if (elgg_substr($url1, 0, 1) == '/') {
		$url1 = elgg_get_site_url() . ltrim($url1, '/');
	}

	if (elgg_substr($url1, 0, 1) == '/') {
		$url2 = elgg_get_site_url() . ltrim($url2, '/');
	}

	// @todo - should probably do something with relative URLs

	if ($url1 == $url2) {
		return TRUE;
	}

	$url1_info = parse_url($url1);
	$url2_info = parse_url($url2);

	$url1_info['path'] = trim($url1_info['path'], '/');
	$url2_info['path'] = trim($url2_info['path'], '/');

	// compare basic bits
	$parts = array('scheme', 'host', 'path');

	foreach ($parts as $part) {
		if ((isset($url1_info[$part]) && isset($url2_info[$part]))
		&& $url1_info[$part] != $url2_info[$part]) {
			return FALSE;
		} elseif (isset($url1_info[$part]) && !isset($url2_info[$part])) {
			return FALSE;
		} elseif (!isset($url1_info[$part]) && isset($url2_info[$part])) {
			return FALSE;
		}
	}

	// quick compare of get params
	if (isset($url1_info['query']) && isset($url2_info['query'])
	&& $url1_info['query'] == $url2_info['query']) {
		return TRUE;
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
		return FALSE;
	}

	return TRUE;
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
 * @return void
 * @since 1.8.0
 */
function elgg_extract($key, array $array, $default = NULL, $strict = true) {
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
 *                           {@see http://us2.php.net/array_multisort}
 * @param int    $sort_type  PHP sort type
 *                           {@see http://us2.php.net/sort}
 *
 * @return bool
 */
function elgg_sort_3d_array_by_value(&$array, $element, $sort_order = SORT_ASC,
$sort_type = SORT_LOCALE_STRING) {

	$sort = array();

	foreach ($array as $k => $v) {
		if (isset($v[$element])) {
			$sort[] = strtolower($v[$element]);
		} else {
			$sort[] = NULL;
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
 * @return true|false Depending on whether it's on or off
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
 * @param str $setting The php.ini setting
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
		case 'm':
			$val *= 1024;
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
 * @access private
 * @return array
 * @since 1.7.0
 */
function elgg_normalise_plural_options_array($options, $singulars) {
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
 * @return void
 * @see register_shutdown_hook()
 */
function _elgg_shutdown_hook() {
	global $START_MICROTIME;

	elgg_trigger_event('shutdown', 'system');

	$time = (float)(microtime(TRUE) - $START_MICROTIME);
	// demoted to NOTICE from DEBUG so javascript is not corrupted
	elgg_log("Page {$_SERVER['REQUEST_URI']} generated in $time seconds", 'NOTICE');
}

/**
 * Serve javascript pages.
 *
 * Searches for views under js/ and outputs them with special
 * headers for caching control.
 *
 * @param array $page The page array
 *
 * @return void
 * @elgg_pagehandler js
 */
function elgg_js_page_handler($page) {
	return elgg_cacheable_view_page_handler($page, 'js');
}

/**
 * Serve individual views for Ajax.
 *
 * /ajax/view/<name of view>?<key/value params>
 *
 * @param array $page The page array
 *
 * @return void
 * @elgg_pagehandler ajax
 */
function elgg_ajax_page_handler($page) {
	if (is_array($page) && sizeof($page)) {
		// throw away 'view' and form the view name
		unset($page[0]);
		$view = implode('/', $page);

		// pull out GET parameters through filter
		$vars = array();
		foreach ($_GET as $name => $value) {
			$vars[$name] = get_input($name);
		}

		if (isset($vars['guid'])) {
			$vars['entity'] = get_entity($vars['guid']);
		}

		echo elgg_view($view, $vars);
	}
	
	return true;
}

/**
 * Serve CSS
 *
 * Serves CSS from the css views directory with headers for caching control
 *
 * @param array $page The page array
 *
 * @return void
 * @elgg_pagehandler css
 */
function elgg_css_page_handler($page) {
	if (!isset($page[0])) {
		// default css
		$page[0] = 'elgg';
	}
	
	return elgg_cacheable_view_page_handler($page, 'css');
}

/**
 * Serves a JS or CSS view with headers for caching.
 *
 * /<css||js>/name/of/view.<last_cache>.<css||js>
 *
 * @param array  $page The page array
 * @param string $type The type: js or css
 *
 * @return mixed
 */
function elgg_cacheable_view_page_handler($page, $type) {

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
		// translates to the url /js/calendars/jquery.fullcalendar.min.<ts>.js
		// and the view js/calendars/jquery.fullcalendar.min
		// we ignore the last two dots for the ts and the ext.
		// Additionally, the timestamp is optional.
		$page = implode('/', $page);
		$regex = '|(.+?)\.([\d]+\.)?\w+$|';
		preg_match($regex, $page, $matches);
		$view = $matches[1];
		$return = elgg_view("$type/$view");

		header("Content-type: $content_type");

		// @todo should js be cached when simple cache turned off
		//header('Expires: ' . date('r', time() + 864000));
		//header("Pragma: public");
		//header("Cache-Control: public");
		//header("Content-Length: " . strlen($return));

		echo $return;
	}

	return true;
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
 */
function elgg_sql_reverse_order_by_clause($order_by) {
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
 * Used as a callback for ElggBatch.
 *
 * @param object $object The object to enable
 * @access private
 * @return bool
 */
function elgg_batch_enable_callback($object) {
	// our db functions return the number of rows affected...
	return $object->enable() ? true : false;
}

/**
 * Disable objects with a disable() method.
 *
 * Used as a callback for ElggBatch.
 *
 * @param object $object The object to disable
 * @access private
 * @return bool
 */
function elgg_batch_disable_callback($object) {
	// our db functions return the number of rows affected...
	return $object->disable() ? true : false;
}

/**
 * Delete objects with a delete() method.
 *
 * Used as a callback for ElggBatch.
 *
 * @param object $object The object to disable
 * @access private
 * @return bool
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
 * @param string $type    Options type: metadata or annotations
 * @return bool
 */
function elgg_is_valid_options_for_batch_operation($options, $type) {
	if (!$options || !is_array($options)) {
		return false;
	}

	// at least one of these is required.
	$required = array(
		// generic restraints
		'guid', 'guids', 'limit'
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
 * @link http://docs.elgg.org/Tutorials/WalledGarden
 * @elgg_plugin_hook index system
 * @return boolean
 */
function elgg_walled_garden_index() {
	elgg_register_css('elgg.walled_garden', '/css/walled_garden.css');
	elgg_load_css('elgg.walled_garden');
	
	$login = elgg_view('core/account/login_walled_garden');

	echo elgg_view_page('', $login, 'walled_garden');

	// return true to prevent other plugins from adding a front page
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
 * @link http://docs.elgg.org/Tutorials/WalledGarden
 * @return void
 */
function elgg_walled_garden() {
	global $CONFIG;

	// check for external page view
	if (isset($CONFIG->site) && $CONFIG->site instanceof ElggSite) {
		$CONFIG->site->checkWalledGarden();
	}
}

/**
 * Elgg's main init.
 *
 * Handles core actions for comments, the JS pagehandler, and the shutdown function.
 *
 * @elgg_event_handler init system
 * @return void
 */
function elgg_init() {
	global $CONFIG;

	elgg_register_action('comments/add');
	elgg_register_action('comments/delete');

	elgg_register_page_handler('js', 'elgg_js_page_handler');
	elgg_register_page_handler('css', 'elgg_css_page_handler');
	elgg_register_page_handler('ajax', 'elgg_ajax_page_handler');

	elgg_register_js('elgg.autocomplete', 'js/lib/autocomplete.js');
	elgg_register_js('elgg.userpicker', 'js/lib/userpicker.js');
	elgg_register_js('elgg.friendspicker', 'js/lib/friends_picker.js');
	elgg_register_js('jquery.easing', 'vendors/jquery/jquery.easing.1.3.packed.js');

	// Trigger the shutdown:system event upon PHP shutdown.
	register_shutdown_function('_elgg_shutdown_hook');

	$logo_url = elgg_get_site_url() . "_graphics/elgg_toolbar_logo.gif";
	elgg_register_menu_item('topbar', array(
		'name' => 'elgg_logo',
		'href' => 'http://www.elgg.org/',
		'text' => "<img src=\"$logo_url\" alt=\"Elgg logo\" width=\"38\" height=\"20\" />",
		'priority' => 1,
		'link_class' => 'elgg-topbar-logo',
	));
	
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
 * @return void
 */
function elgg_api_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/api/entity_getter_functions.php';
	$value[] = $CONFIG->path . 'engine/tests/api/helpers.php';
	$value[] = $CONFIG->path . 'engine/tests/regression/trac_bugs.php';
	return $value;
}

/**#@+
 * Controlls access levels on ElggEntity entities, metadata, and annotations.
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
 * @var NULL
 * @since 1.7
 */
define('ELGG_ENTITIES_ANY_VALUE', NULL);

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
 * @var unknown_type
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

elgg_register_event_handler('init', 'system', 'elgg_init');
elgg_register_plugin_hook_handler('unit_test', 'system', 'elgg_api_test');

elgg_register_event_handler('init', 'system', 'add_custom_menu_items', 1000);
elgg_register_event_handler('init', 'system', 'elgg_walled_garden', 1000);
