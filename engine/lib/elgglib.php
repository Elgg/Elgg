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

	if (!include($CONFIG->classes[$class])) {
		throw new Exception("Failed to autoload $class");
	}
}

/**
 * Register all files found in $dir as classes
 * Need to be named MyClass.php
 *
 * @param string $dir The dir to look in
 *
 * @return void
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
 * @return void
 */
function elgg_register_class($class, $location) {
	global $CONFIG;

	if (!isset($CONFIG->classes)) {
		$CONFIG->classes = array();
	}

	$CONFIG->classes[$class] = $location;
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
 * Plugin authors are encouraged to use the $id variable. jQuery plugins
 * often have filenames such as jquery.rating.js. In that case, the id
 * would be "jquery.rating". It is recommended to not use version numbers
 * in the id.
 *
 * The JavaScript files can be local to the server or remote (such as
 * Google's CDN).
 *
 * @param string $url      URL of the JavaScript file
 * @param string $id       An identifier of the JavaScript library
 * @param string $location Page location: head or footer. (default: head)
 * @return bool
 */
function elgg_register_js($url, $id = '', $location = 'head') {
	return elgg_register_external_file('javascript', $url, $id, $location);
}

/**
 * Register a CSS file for inclusion in the HTML head
 *
 * @param string $url URL of the CSS file
 * @param string $id  An identifier for the CSS file
 * @return bool
 */
function elgg_register_css($url, $id = '') {
	return elgg_register_external_file('css', $url, $id, 'head');
}

/**
 * Core registration function for external files
 *
 * @param string $type     Type of external resource
 * @param string $url      URL
 * @param string $id       Identifier used as key
 * @param string $location Location in the page to include the file
 * @return bool
 */
function elgg_register_external_file($type, $url, $id, $location) {
	global $CONFIG;

	if (empty($url)) {
		return false;
	}

	$url = elgg_format_url($url);

	if (!isset($CONFIG->externals)) {
		$CONFIG->externals = array();
	}

	if (!isset($CONFIG->externals[$type])) {
		$CONFIG->externals[$type]  = array();
	}

	if (!isset($CONFIG->externals[$type][$location])) {
		$CONFIG->externals[$type][$location] = array();
	}

	if (!$id) {
		$id = count($CONFIG->externals[$type][$location]);
	} else {
		$id = trim(strtolower($id));
	}

	$CONFIG->externals[$type][$location][$id] = elgg_normalize_url($url);

	return true;
}

/**
 * Unregister a JavaScript file
 *
 * @param string $id       The identifier for the JavaScript library
 * @param string $url      Optional URL to search for if id is not specified
 * @param string $location Location in the page
 * @return bool
 */
function elgg_unregister_js($id = '', $url = '', $location = 'head') {
	return elgg_unregister_external_file('javascript', $id, $url, $location);
}

/**
 * Unregister an external file
 *
 * @param string $id  The identifier of the CSS file
 * @param string $url Optional URL to search for if id is not specified
 * @return bool
 */
function elgg_unregister_css($id = '', $url = '') {
	return elgg_unregister_external_file('css', $id, $url, 'head');
}

/**
 * Unregister an external file
 *
 * @param string $type     Type of file: javascript or css
 * @param string $id       The identifier of the file
 * @param string $url      Optional URL to search for if the id is not specified
 * @param string $location Location in the page
 * @return bool
 */
function elgg_unregister_external_file($type, $id = '', $url = '', $location = 'head') {
	global $CONFIG;

	if (!isset($CONFIG->externals)) {
		return false;
	}

	if (!isset($CONFIG->externals[$type])) {
		return false;
	}

	if (!isset($CONFIG->externals[$type][$location])) {
		return false;
	}

	if (array_key_exists($id, $CONFIG->externals[$type][$location])) {
		unset($CONFIG->externals[$type][$location][$id]);
		return true;
	}

	// was not registered with an id so do a search for the url
	$key = array_search($url, $CONFIG->externals[$type][$location]);
	if ($key) {
		unset($CONFIG->externals[$type][$location][$key]);
		return true;
	}

	return false;
}

/**
 * Get the JavaScript URLs
 *
 * @param string $location 'head' or 'footer'
 *
 * @return array
 */
function elgg_get_js($location = 'head') {
	return elgg_get_external_file('javascript', $location);
}

/**
 * Get the CSS URLs
 *
 * @return array
 */
function elgg_get_css() {
	return elgg_get_external_file('css', 'head');
}

/**
 * Get external resource descriptors
 *
 * @param string $type     Type of resource
 * @param string $location Page location
 * @return array
 */
function elgg_get_external_file($type, $location) {
	global $CONFIG;

	if (isset($CONFIG->externals) &&
		isset($CONFIG->externals[$type]) &&
		isset($CONFIG->externals[$type][$location])) {

		return array_values($CONFIG->externals[$type][$location]);
	}
	return array();
}

/**
 * Returns the HTML for "likes" and "like this" on entities.
 *
 * @param ElggEntity $entity The entity to like
 *
 * @return string|false The HTML for the likes, or false on failure
 *
 * @since 1.8
 * @see @elgg_view likes/forms/edit
 */
function elgg_view_likes($entity) {
	if (!($entity instanceof ElggEntity)) {
		return false;
	}

	$params = array('entity' => $entity);

	if ($likes = elgg_trigger_plugin_hook('likes', $entity->getType(), $params, false)) {
		return $likes;
	} else {
		$likes = elgg_view('likes/forms/edit', $params);
		return $likes;
	}
}

/**
 * Count the number of likes attached to an entity
 *
 * @param ElggEntity $entity The entity to count likes for
 *
 * @return int Number of likes
 * @since 1.8
 */
function elgg_count_likes($entity) {
	if ($likeno = elgg_trigger_plugin_hook('likes:count', $entity->getType(),
		array('entity' => $entity), false)) {
		return $likeno;
	} else {
		return count_annotations($entity->getGUID(), "", "", "likes");
	}
}

/**
 * Count the number of comments attached to an entity
 *
 * @param ElggEntity $entity The entity to count comments for
 *
 * @return int Number of comments
 */
function elgg_count_comments($entity) {
	if ($commentno = elgg_trigger_plugin_hook('comments:count', $entity->getType(),
		array('entity' => $entity), false)) {
		return $commentno;
	} else {
		return count_annotations($entity->getGUID(), "", "", "generic_comment");
	}
}

/**
 * Returns all php files in a directory.
 *
 * @deprecated 1.7 Use elgg_get_file_list() instead
 *
 * @param string $directory  Directory to look in
 * @param array  $exceptions Array of extensions (with .!) to ignore
 * @param array  $list       A list files to include in the return
 *
 * @return array
 */
function get_library_files($directory, $exceptions = array(), $list = array()) {
	elgg_deprecated_notice('get_library_files() deprecated by elgg_get_file_list()', 1.7);
	return elgg_get_file_list($directory, $exceptions, $list, array('.php'));
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
 * Adds an entry in $CONFIG[$register_name][$subregister_name].
 *
 * This is only used for the site-wide menu.  See {@link add_menu()}.
 *
 * @param string $register_name     The name of the top-level register
 * @param string $subregister_name  The name of the subregister
 * @param mixed  $subregister_value The value of the subregister
 * @param array  $children_array    Optionally, an array of children
 *
 * @return true|false Depending on success
 * @todo Can be deprecated when the new menu system is introduced.
 */
function add_to_register($register_name, $subregister_name, $subregister_value,
$children_array = array()) {

	global $CONFIG;

	if (empty($register_name) || empty($subregister_name)) {
		return false;
	}

	if (!isset($CONFIG->registers)) {
		$CONFIG->registers = array();
	}

	if (!isset($CONFIG->registers[$register_name])) {
		$CONFIG->registers[$register_name]  = array();
	}

	$subregister = new stdClass;
	$subregister->name = $subregister_name;
	$subregister->value = $subregister_value;

	if (is_array($children_array)) {
		$subregister->children = $children_array;
	}

	$CONFIG->registers[$register_name][$subregister_name] = $subregister;
	return true;
}

/**
 * Removes a register entry from $CONFIG[register_name][subregister_name]
 *
 * This is used to by {@link remove_menu()} to remove site-wide menu items.
 *
 * @param string $register_name    The name of the top-level register
 * @param string $subregister_name The name of the subregister
 *
 * @return true|false Depending on success
 * @since 1.7.0
 * @todo Can be deprecated when the new menu system is introduced.
 */
function remove_from_register($register_name, $subregister_name) {
	global $CONFIG;

	if (empty($register_name) || empty($subregister_name)) {
		return false;
	}

	if (!isset($CONFIG->registers)) {
		return false;
	}

	if (!isset($CONFIG->registers[$register_name])) {
		return false;
	}

	if (isset($CONFIG->registers[$register_name][$subregister_name])) {
		unset($CONFIG->registers[$register_name][$subregister_name]);
		return true;
	}

	return false;
}

/**
 * Constructs and returns a register object.
 *
 * @param string $register_name  The name of the register
 * @param mixed  $register_value The value of the register
 * @param array  $children_array Optionally, an array of children
 *
 * @return false|stdClass Depending on success
 * @todo Can be deprecated when the new menu system is introduced.
 */
function make_register_object($register_name, $register_value, $children_array = array()) {
	elgg_deprecated_notice('make_register_object() is deprecated by add_submenu_item()', 1.7);
	if (empty($register_name) || empty($register_value)) {
		return false;
	}

	$register = new stdClass;
	$register->name = $register_name;
	$register->value = $register_value;
	$register->children = $children_array;

	return $register;
}

/**
 * If it exists, returns a particular register as an array
 *
 * @param string $register_name The name of the register
 *
 * @return array|false Depending on success
 * @todo Can be deprecated when the new menu system is introduced.
 */
function get_register($register_name) {
	global $CONFIG;

	if (isset($CONFIG->registers[$register_name])) {
		return $CONFIG->registers[$register_name];
	}

	return false;
}

/**
 * Queues a message to be displayed.
 *
 * Messages will not be displayed immediately, but are stored in
 * for later display, usually upon next page load.
 *
 * The method of displaying these messages differs depending upon plugins and
 * viewtypes.  The core default viewtype retrieves messages in
 * {@link views/default/page_shells/default.php} and displays messages as
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
 * @param string $register Types of message: "errors", "messages" (default: messages)
 * @param bool   $count    Count the number of messages (default: false)
 *
 * @return true|false|array Either the array of messages, or a response regarding
 *                          whether the message addition was successful.
 * @todo Clean up. Separate registering messages and retrieving them.
 */
function system_messages($message = null, $register = "messages", $count = false) {
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
 * @return Bool
 */
function system_message($message) {
	return system_messages($message, "messages");
}

/**
 * Display an error on next page load.
 *
 * @see system_messages()
 *
 * @param string|array $error Error or errors to add
 *
 * @return true|false Success response
 */
function register_error($error) {
	return system_messages($error, "errors");
}

/**
 * Deprecated events core function. Code divided between elgg_register_event_handler()
 * and trigger_elgg_event().
 *
 * @param string  $event       The type of event (eg 'init', 'update', 'delete')
 * @param string  $object_type The type of object (eg 'system', 'blog', 'user')
 * @param string  $function    The name of the function that will handle the event
 * @param int     $priority    Priority to call handler. Lower numbers called first (default 500)
 * @param boolean $call        Set to true to call the event rather than add to it (default false)
 * @param mixed   $object      Optionally, the object the event is being performed on (eg a user)
 *
 * @return true|false Depending on success
 * @deprecated 1.8 Use explicit register/trigger event functions
 */
function events($event = "", $object_type = "", $function = "", $priority = 500,
$call = false, $object = null) {

	elgg_deprecated_notice('events() has been deprecated.', 1.8);

	// leaving this here just in case someone was directly calling this internal function
	if (!$call) {
		return elgg_register_event_handler($event, $object_type, $function, $priority);
	} else {
		return trigger_elgg_event($event, $object_type, $object);
	}
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
 * @deprecated 1.8 Use elgg_register_event_handler() instead
 */
function register_elgg_event_handler($event, $object_type, $callback, $priority = 500) {
	elgg_deprecated_notice("register_elgg_event_handler() was deprecated by elgg_register_event_handler()", 1.8);
	return elgg_register_event_handler($event, $object_type, $callback, $priority);
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
 * @deprecated 1.8 Use elgg_unregister_event_handler instead
 */
function unregister_elgg_event_handler($event, $object_type, $callback) {
	elgg_deprecated_notice('unregister_elgg_event_handler => elgg_unregister_event_handler', 1.8);
	elgg_unregister_event_handler($event, $object_type, $callback);
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

	$events = array(
		$CONFIG->events[$event][$object_type],
		$CONFIG->events['all'][$object_type],
		$CONFIG->events[$event]['all'],
		$CONFIG->events['all']['all'],
	);

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
 * @deprecated 1.8 Use elgg_trigger_event() instead
 */
function trigger_elgg_event($event, $object_type, $object = null) {
	elgg_deprecated_notice('trigger_elgg_event() was deprecated by elgg_trigger_event()', 1.8);
	return elgg_trigger_event($event, $object_type, $object);
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
 * @since 1.8
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
 * @deprecated 1.8 Use elgg_register_plugin_hook_handler() instead
 */
function register_plugin_hook($hook, $type, $callback, $priority = 500) {
	elgg_deprecated_notice("register_plugin_hook() was deprecated by elgg_register_plugin_hook_handler()", 1.8);
	return elgg_register_plugin_hook_handler($hook, $type, $callback, $priority);
}

/**
 * Unregister a callback as a plugin hook.
 *
 * @param string   $hook        The name of the hook
 * @param string   $entity_type The name of the type of entity (eg "user", "object" etc)
 * @param callback $callback    The PHP callback to be removed
 *
 * @return void
 * @since 1.8
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
 * @deprecated 1.8 Use elgg_unregister_plugin_hook_handler() instead
 */
function unregister_plugin_hook($hook, $entity_type, $callback) {
	elgg_deprecated_notice("unregister_plugin_hook() was deprecated by elgg_unregister_plugin_hook_handler()", 1.8);
	elgg_unregister_plugin_hook_handler($hook, $entity_type, $callback);
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
 * @since 1.8
 */
function elgg_trigger_plugin_hook($hook, $type, $params = null, $returnvalue = null) {
	global $CONFIG;

	$hooks = array(
		$CONFIG->hooks[$hook][$type],
		$CONFIG->hooks['all'][$type],
		$CONFIG->hooks[$hook]['all'],
		$CONFIG->hooks['all']['all'],
	);

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
 * @deprecated 1.8 Use elgg_trigger_plugin_hook() instead
 */
function trigger_plugin_hook($hook, $type, $params = null, $returnvalue = null) {
	elgg_deprecated_notice("trigger_plugin_hook() was deprecated by elgg_trigger_plugin_hook()", 1.8);
	return elgg_trigger_plugin_hook($hook, $type, $params, $returnvalue);
}

/**
 * Intercepts, logs, and display uncaught exceptions.
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
	//header("Internal Server Error", true, 500);

	elgg_set_viewtype('failsafe');
	$body = elgg_view("messages/exceptions/exception", array('object' => $exception));
	echo elgg_view_page(elgg_echo('exception:title'), $body);
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
 * @param str $msg         Message to log / display.
 * @param str $dep_version Human-readable *release* version: 1.7, 1.7.3
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_deprecated_notice($msg, $dep_version) {
	// if it's a major release behind, visual and logged
	// if it's a 2 minor releases behind, visual and logged
	// if it's 1 minor release behind, logged.
	// bugfixes don't matter because you're not deprecating between them, RIGHT?
	if (!$dep_version) {
		return FALSE;
	}

	$elgg_version = get_version(TRUE);
	$elgg_version_arr = explode('.', $elgg_version);
	$elgg_major_version = $elgg_version_arr[0];
	$elgg_minor_version = $elgg_version_arr[1];

	$dep_version_arr = explode('.', $dep_version);
	$dep_major_version = $dep_version_arr[0];
	$dep_minor_version = $dep_version_arr[1];

	$last_working_version = $dep_minor_version - 1;

	$visual = FALSE;

	// use version_compare to account for 1.7a < 1.7
	if (($dep_major_version < $elgg_major_version)
	|| (($elgg_minor_version - $last_working_version) > 1)) {
		$visual = TRUE;
	}

	$msg = "Deprecated in $dep_version: $msg";

	if ($visual) {
		register_error($msg);
	}

	// Get a file and line number for the log. Never show this in the UI.
	// Skip over the function that sent this notice and see who called the deprecated
	// function itself.
	$backtrace = debug_backtrace();
	$caller = $backtrace[1];
	$msg .= " (Called from {$caller['file']}:{$caller['line']})";

	elgg_log($msg, 'WARNING');

	return TRUE;
}


/**
 * Checks if code is being called from a certain function.
 *
 * To use, call this function with the function name (and optional
 * file location) that it has to be called from, it will either
 * return true or false.
 *
 * e.g.
 *
 * function my_secure_function()
 * {
 * 		if (!call_gatekeeper("my_call_function"))
 * 			return false;
 *
 * 		... do secure stuff ...
 * }
 *
 * function my_call_function()
 * {
 * 		// will work
 * 		my_secure_function();
 * }
 *
 * function bad_function()
 * {
 * 		// Will not work
 * 		my_secure_function();
 * }
 *
 * @param mixed  $function The function that this function must have in its call stack,
 * 		                   to test against a method pass an array containing a class and
 *                         method name.
 * @param string $file     Optional file that the function must reside in.
 *
 * @return bool
 *
 * @deprecated 1.8 A neat but pointless function
 */
function call_gatekeeper($function, $file = "") {
	elgg_deprecated_notice("call_gatekeeper() is neat but pointless", 1.8);
	// Sanity check
	if (!$function) {
		return false;
	}

	// Check against call stack to see if this is being called from the correct location
	$callstack = debug_backtrace();
	$stack_element = false;

	foreach ($callstack as $call) {
		if (is_array($function)) {
			if (
				(strcmp($call['class'], $function[0]) == 0) &&
				(strcmp($call['function'], $function[1]) == 0)
			) {
				$stack_element = $call;
			}
		} else {
			if (strcmp($call['function'], $function) == 0) {
				$stack_element = $call;
			}
		}
	}

	if (!$stack_element) {
		return false;
	}

	// If file then check that this it is being called from this function
	if ($file) {
		$mirror = null;

		if (is_array($function)) {
			$mirror = new ReflectionMethod($function[0], $function[1]);
		} else {
			$mirror = new ReflectionFunction($function);
		}

		if ((!$mirror) || (strcmp($file, $mirror->getFileName()) != 0)) {
			return false;
		}
	}

	return true;
}

/**
 * This function checks to see if it is being called at somepoint by a function defined somewhere
 * on a given path (optionally including subdirectories).
 *
 * This function is similar to call_gatekeeper() but returns true if it is being called
 * by a method or function which has been defined on a given path or by a specified file.
 *
 * @param string $path            The full path and filename that this function must have
 *                                in its call stack If a partial path is given and
 *                                $include_subdirs is true, then the function will return
 *                                true if called by any function in or below the specified path.
 * @param bool   $include_subdirs Are subdirectories of the path ok, or must you specify an
 *                                absolute path and filename.
 * @param bool   $strict_mode     If true then the calling method or function must be directly
 *                                called by something on $path, if false the whole call stack is
 *                                searched.
 *
 * @return void
 *
 * @deprecated 1.8 A neat but pointless function
 */
function callpath_gatekeeper($path, $include_subdirs = true, $strict_mode = false) {
	elgg_deprecated_notice("callpath_gatekeeper() is neat but pointless", 1.8);

	global $CONFIG;

	$path = sanitise_string($path);

	if ($path) {
		$callstack = debug_backtrace();

		foreach ($callstack as $call) {
			$call['file'] = str_replace("\\", "/", $call['file']);

			if ($include_subdirs) {
				if (strpos($call['file'], $path) === 0) {

					if ($strict_mode) {
						$callstack[1]['file'] = str_replace("\\", "/", $callstack[1]['file']);
						if ($callstack[1] === $call) {
							return true;
						}
					} else {
						return true;
					}
				}
			} else {
				if (strcmp($path, $call['file']) == 0) {
					if ($strict_mode) {
						if ($callstack[1] === $call) {
							return true;
						}
					} else {
						return true;
					}
				}
			}

		}
		return false;
	}

	if (isset($CONFIG->debug)) {
		system_message("Gatekeeper'd function called from {$callstack[1]['file']}:"
			. "{$callstack[1]['line']}\n\nStack trace:\n\n" . print_r($callstack, true));
	}

	return false;
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
 * @param bool $html_encode HTML encode the url?
 *
 * @return str URL with action tokens
 * @since 1.7.0
 * @link http://docs.elgg.org/Tutorials/Actions
 */
function elgg_add_action_tokens_to_url($url, $html_encode = TRUE) {
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
 * Add action tokens to URL.
 *
 * @param string $url URL
 *
 * @return string
 *
 * @deprecated 1.7 final
 */
function elgg_validate_action_url($url) {
	elgg_deprecated_notice('elgg_validate_action_url() deprecated by elgg_add_action_tokens_to_url().',
		'1.7b');

	return elgg_add_action_tokens_to_url($url);
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
	$string = elgg_http_build_url($url_array);

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
 * @return BOOL
 * @since 1.8
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
 * Load all the REQUEST variables into the sticky form cache
 *
 * Call this from an action when you want all your submitted variables
 * available if the submission fails validation and is sent back to the form
 *
 * @param string $form_name Name of the sticky form
 *
 * @return void
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 */
function elgg_make_sticky_form($form_name) {
	global $CONFIG;

	$CONFIG->active_sticky_form = $form_name;
	elgg_clear_sticky_form($form_name);

	if (!isset($_SESSION['sticky_forms'])) {
		$_SESSION['sticky_forms'] = array();
	}
	$_SESSION['sticky_forms'][$form_name] = array();

	foreach ($_REQUEST as $key => $var) {
		// will go through XSS filtering on the get function
		$_SESSION['sticky_forms'][$form_name][$key] = $var;
	}
}

/**
 * Clear the sticky form cache
 *
 * Call this if validation is successful in the action handler or
 * when they sticky values have been used to repopulate the form
 * after a validation error.
 *
 * @param string $form_name Form namespace
 *
 * @return void
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 */
function elgg_clear_sticky_form($form_name) {
	unset($_SESSION['sticky_forms'][$form_name]);
}

/**
 * Has this form been made sticky?
 *
 * @param string $form_name Form namespace
 *
 * @return boolean
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 */
function elgg_is_sticky_form($form_name) {
	return isset($_SESSION['sticky_forms'][$form_name]);
}

/**
 * Get a specific sticky variable
 *
 * @param string  $form_name     The name of the form
 * @param string  $variable      The name of the variable
 * @param mixed   $default       Default value if the variable does not exist in sticky cache
 * @param boolean $filter_result Filter for bad input if true
 *
 * @return mixed
 *
 * @todo should this filter the default value?
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 */
function elgg_get_sticky_value($form_name, $variable = '', $default = NULL, $filter_result = true) {
	if (isset($_SESSION['sticky_forms'][$form_name][$variable])) {
		$value = $_SESSION['sticky_forms'][$form_name][$variable];
		if ($filter_result) {
			// XSS filter result
			$value = filter_tags($value);
		}
		return $value;
	}
	return $default;
}

/**
 * Clear a specific sticky variable
 *
 * @param string $form_name The name of the form
 * @param string $variable  The name of the variable to clear
 *
 * @return void
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 */
function elgg_clear_sticky_value($form_name, $variable) {
	unset($_SESSION['sticky_forms'][$form_name][$variable]);
}

/**
 * Returns the current active sticky form.
 *
 * @return mixed Str | FALSE
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 */
function elgg_get_active_sticky_form() {
	global $CONFIG;

	if (isset($CONFIG->active_sticky_form)) {
		$form_name = $CONFIG->active_sticky_form;
	} else {
		return FALSE;
	}

	return (elgg_is_sticky_form($form_name)) ? $form_name : FALSE;
}

/**
 * Sets the active sticky form.
 *
 * @param string $form_name The name of the form
 *
 * @return void
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 */
function elgg_set_active_sticky_form($form_name) {
	global $CONFIG;

	$CONFIG->active_sticky_form = $form_name;
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
 *
 * @return void
 * @since 1.8
 */
function elgg_get_array_value($key, array $array, $default = NULL) {
	return (isset($array[$key])) ? $array[$key] : $default;
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
 * @param array $singulars A list of sinular words to pluralize by adding 's'.
 *
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
 * Does nothing.
 *
 * @deprecated 1.7
 * @return 0
 */
function test_ip() {
	elgg_deprecated_notice('test_ip() was removed because of licensing issues.', 1.7);

	return 0;
}

/**
 * Does nothing.
 *
 * @return bool
 * @deprecated 1.7
 */
function is_ip_in_array() {
	elgg_deprecated_notice('is_ip_in_array() was removed because of licensing issues.', 1.7);

	return false;
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
function js_page_handler($page) {
	if (is_array($page) && sizeof($page)) {
		$js = substr($page[0], 0, strpos($page[0], '.'));
		$return = elgg_view('js/' . $js);

		header('Content-type: text/javascript');
		header('Expires: ' . date('r', time() + 864000));
		header("Pragma: public");
		header("Cache-Control: public");
		header("Content-Length: " . strlen($return));

		echo $return;
	}
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
function css_page_handler($page) {
	if (!isset($page[0])) {
		// default css
		$page[0] = 'elgg';
	}

	$css = substr($page[0], 0, strpos($page[0], '.'));
	$return = elgg_view("css/$css");

	header("Content-type: text/css", true);
	header('Expires: ' . date('r', time() + 86400000), true);
	header("Pragma: public", true);
	header("Cache-Control: public", true);

	echo $return;
}

/**
 * Intercepts the index page when Walled Garden mode is enabled.
 *
 * @link http://docs.elgg.org/Tutorials/WalledGarden
 * @elgg_plugin_hook index system
 * @return void
 */
function elgg_walled_garden_index() {
	$login = elgg_view('account/login_walled_garden');

	echo elgg_view_page('', $login, 'walled_garden');

	// @hack Index must exit to keep plugins from continuing to extend
	exit;
}

/**
 * Checks the status of the Walled Garden and forwards to a login page
 * if required.
 *
 * If the site is in Walled Garden mode, all page except those registered as
 * plugin pages by {@elgg_hook public_pages walled_garden} will redirect to
 * a login page.
 *
 * @since 1.8
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
 * Handles core actions for comments and likes, the JS pagehandler, and the shutdown function.
 *
 * @elgg_event_handler init system
 * @return void
 */
function elgg_init() {
	global $CONFIG;

	elgg_register_action('comments/add');
	elgg_register_action('comments/delete');
	elgg_register_action('likes/add');
	elgg_register_action('likes/delete');

	register_page_handler('js', 'js_page_handler');
	register_page_handler('css', 'css_page_handler');

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
