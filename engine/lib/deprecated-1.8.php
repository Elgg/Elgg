<?php
/**
 * ***************************************************************************
 * NOTE: If this is ever removed from Elgg, sites lose the ability to upgrade
 * from 1.7.x and earlier to the latest version of Elgg without upgrading to
 * 1.8 first.
 * ***************************************************************************
 *
 * Upgrade the database schema in an ordered sequence.
 *
 * Executes all upgrade files in elgg/engine/schema/upgrades/ in sequential order.
 * Upgrade files must be in the standard Elgg release format of YYYYMMDDII.sql
 * where II is an incrementor starting from 01.
 *
 * Files that are < $version will be ignored.
 *
 * @warning Plugin authors should not call this function directly.
 *
 * @param int    $version The version you are upgrading from in the format YYYYMMDDII.
 * @param string $fromdir Optional directory to load upgrades from. default: engine/schema/upgrades/
 * @param bool   $quiet   If true, suppress all error messages. Only use for the upgrade from <=1.6.
 *
 * @return int The number of upgrades run.
 * @see upgrade.php
 * @see version.php
 * @deprecated 1.8 Use PHP upgrades for sql changes.
 */
function db_upgrade($version, $fromdir = "", $quiet = FALSE) {
	global $CONFIG;

	elgg_deprecated_notice('db_upgrade() is deprecated by using PHP upgrades.', 1.8);

	$version = (int) $version;

	if (!$fromdir) {
		$fromdir = $CONFIG->path . 'engine/schema/upgrades/';
	}
	
	$i = 0;

	if ($handle = opendir($fromdir)) {
		$sqlupgrades = array();

		while ($sqlfile = readdir($handle)) {
			if (!is_dir($fromdir . $sqlfile)) {
				if (preg_match('/^([0-9]{10})\.(sql)$/', $sqlfile, $matches)) {
					$sql_version = (int) $matches[1];
					if ($sql_version > $version) {
						$sqlupgrades[] = $sqlfile;
					}
				}
			}
		}

		asort($sqlupgrades);

		if (sizeof($sqlupgrades) > 0) {
			foreach ($sqlupgrades as $sqlfile) {

				// hide all errors.
				if ($quiet) {
					try {
						run_sql_script($fromdir . $sqlfile);
					} catch (DatabaseException $e) {
						error_log($e->getmessage());
					}
				} else {
					run_sql_script($fromdir . $sqlfile);
				}
				$i++;
			}
		}
	}

	return $i;
}

/**
 * Lists entities from an access collection
 *
 * @deprecated 1.8 Use elgg_list_entities_from_access_id()
 *
 * @return str
 */
function list_entities_from_access_id($access_id, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true) {

	elgg_deprecated_notice("All list_entities* functions were deprecated in 1.8.  Use elgg_list_entities* instead.", 1.8);

	echo elgg_list_entities_from_access_id(array('access_id' => $access_id,
		'types' => $entity_type, 'subtypes' => $entity_subtype, 'owner_guids' => $owner_guid,
		'limit' => $limit, 'full_view' => $fullview, 'list_type_toggle' => $listtypetoggle,
		'pagination' => $pagination,));
}

/**
 * Registers a particular action in memory
 *
 * @deprecated 1.8 Use {@link elgg_register_action()} instead
 *
 * @param string $action The name of the action (eg "register", "account/settings/save")
 * @param boolean $public Can this action be accessed by people not logged into the system?
 * @param string $filename Optionally, the filename where this action is located
 * @param boolean $admin_only Whether this action is only available to admin users.
 */
function register_action($action, $public = false, $filename = "", $admin_only = false) {
	elgg_deprecated_notice("register_action() was deprecated by elgg_register_action()", 1.8);

	if ($admin_only) {
		$access = 'admin';
	} elseif ($public) {
		$access = 'public';
	} else {
		$access = 'logged_in';
	}

	return elgg_register_action($action, $filename, $access);
}

/**
 * Register an admin page with the admin panel.
 * This function extends the view "admin/main" with the provided view.
 * This view should provide a description and either a control or a link to.
 *
 * @deprecated 1.8 Extend admin views manually
 *
 * Usage:
 * 	- To add a control to the main admin panel then extend admin/main
 *  - To add a control to a new page create a page which renders a view admin/subpage
 *    (where subpage is your new page -
 *    nb. some pages already exist that you can extend), extend the main view to point to it,
 *    and add controls to your new view.
 *
 * At the moment this is essentially a wrapper around elgg_extend_view().
 *
 * @param string $new_admin_view The view associated with the control you're adding
 * @param string $view           The view to extend, by default this is 'admin/main'.
 * @param int    $priority       Optional priority to govern the appearance in the list.
 *
 * @return void
 */
function extend_elgg_admin_page($new_admin_view, $view = 'admin/main', $priority = 500) {
	elgg_deprecated_notice('extend_elgg_admin_page() does nothing. Extend admin views manually.', 1.8);
}

/**
 * Get entities ordered by a mathematical calculation
 *
 * @deprecated 1.8 Use elgg_get_entities_from_annotation_calculation()
 *
 * @param string $sum            What sort of calculation to perform
 * @param string $entity_type    Type of Entity
 * @param string $entity_subtype Subtype of Entity
 * @param string $name           Name of annotation
 * @param string $mdname         Metadata name
 * @param string $mdvalue        Metadata value
 * @param int    $owner_guid     GUID of owner of annotation
 * @param int    $limit          Limit of results
 * @param int    $offset         Offset of results
 * @param string $orderdir       Order of results
 * @param bool   $count          Return count or entities
 *
 * @return mixed
 */
function get_entities_from_annotations_calculate_x($sum = "sum", $entity_type = "", $entity_subtype = "", $name = "", $mdname = '', $mdvalue = '', $owner_guid = 0, $limit = 10, $offset = 0, $orderdir = 'desc', $count = false) {

	$msg = 'get_entities_from_annotations_calculate_x() is deprecated by elgg_get_entities_from_annotation_calculation().';

	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	$options['calculation'] = $sum;

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($mdname) {
		$options['metadata_names'] = $mdname;
	}

	if ($mdvalue) {
		$options['metadata_values'] = $mdvalue;
	}

	// original function rewrote this to container guid.
	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['container_guids'] = $owner_guid;
		} else {
			$options['container_guid'] = $owner_guid;
		}
	}

	$options['limit'] = $limit;
	$options['offset'] = $offset;

	$options['order_by'] = "annotation_calculation $orderdir";

	$options['count'] = $count;

	return elgg_get_entities_from_annotation_calculation($options);
}

/**
 * Returns entities ordered by the sum of an annotation
 *
 * @warning This is function uses sum instead of count. THIS IS SLOW. See #3366.
 *          This should be used when you have annotations with different values and you
 *          want a list of entities ordered by the sum of all of those values.
 *          If you want a list of entities ordered by the number of annotations on each entity,
 *          use __get_entities_from_annotations_calculate_x() and pass 'count' as the first param.
 *
 * @deprecated 1.8 Use elgg_get_entities_from_annotation_calculation()
 *
 * @param string $entity_type    Type of Entity
 * @param string $entity_subtype Subtype of Entity
 * @param string $name           Name of annotation
 * @param string $mdname         Metadata name
 * @param string $mdvalue        Metadata value
 * @param int    $owner_guid     GUID of owner of annotation
 * @param int    $limit          Limit of results
 * @param int    $offset         Offset of results
 * @param string $orderdir       Order of results
 * @param bool   $count          Return count or entities
 *
 * @return unknown
 */
function get_entities_from_annotation_count($entity_type = "", $entity_subtype = "", $name = "", $mdname = '', $mdvalue = '', $owner_guid = 0, $limit = 10, $offset = 0, $orderdir = 'desc', $count = false) {

	$msg = 'get_entities_from_annotation_count() is deprecated by elgg_get_entities_from_annotation_calculation().';

	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	$options['calculation'] = 'sum';

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($mdname) {
		$options['metadata_names'] = $mdname;
	}

	if ($mdvalue) {
		$options['metadata_values'] = $mdvalue;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	$options['limit'] = $limit;
	$options['offset'] = $offset;

	$options['order_by'] = "annotation_calculation $orderdir";

	$options['count'] = $count;

	return elgg_get_entities_from_annotation_calculation($options);
}

/**
 * Lists entities by the totals of a particular kind of annotation
 *
 * @deprecated 1.8 Use elgg_list_entities_from_annotation_calculation()
 *
 * @param string  $entity_type    Type of entity.
 * @param string  $entity_subtype Subtype of entity.
 * @param string  $name           Name of annotation.
 * @param int     $limit          Maximum number of results to return.
 * @param int     $owner_guid     Owner.
 * @param int     $group_guid     Group container. Currently only supported if entity_type is object
 * @param boolean $asc            Whether to list in ascending or descending order (default: desc)
 * @param boolean $fullview       Whether to display the entities in full
 * @param boolean $listtypetoggle Can the 'gallery' view can be displayed (default: no)
 * @param boolean $pagination     Add pagination
 * @param string  $orderdir       Order desc or asc
 *
 * @return string Formatted entity list
 */
function list_entities_from_annotation_count($entity_type = "", $entity_subtype = "", $name = "", $limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true, $listtypetoggle = false, $pagination = true, $orderdir = 'desc') {

	$msg = 'list_entities_from_annotation_count() is deprecated by elgg_list_entities_from_annotation_calculation().';

	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	$options['calculation'] = 'sum';

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	$options['full_view'] = $fullview;

	$options['list_type_toggle'] = $listtypetoggle;

	$options['pagination'] = $pagination;

	$options['limit'] = $limit;

	$options['order_by'] = "annotation_calculation $orderdir";

	return elgg_get_entities_from_annotation_calculation($options);
}

/**
 * Adds an entry in $CONFIG[$register_name][$subregister_name].
 *
 * @deprecated 1.8 Use the new menu system.
 * 
 * This is only used for the site-wide menu.  See {@link add_menu()}.
 *
 * @param string $register_name     The name of the top-level register
 * @param string $subregister_name  The name of the subregister
 * @param mixed  $subregister_value The value of the subregister
 * @param array  $children_array    Optionally, an array of children
 *
 * @return true|false Depending on success
 */
function add_to_register($register_name, $subregister_name, $subregister_value, $children_array = array()) {
	elgg_deprecated_notice("add_to_register() has been deprecated", 1.8);
	global $CONFIG;

	if (empty($register_name) || empty($subregister_name)) {
		return false;
	}

	if (!isset($CONFIG->registers)) {
		$CONFIG->registers = array();
	}

	if (!isset($CONFIG->registers[$register_name])) {
		$CONFIG->registers[$register_name] = array();
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
 * @deprecated 1.8 Use the new menu system.
 *
 * This is used to by {@link remove_menu()} to remove site-wide menu items.
 *
 * @param string $register_name    The name of the top-level register
 * @param string $subregister_name The name of the subregister
 *
 * @return true|false Depending on success
 * @since 1.7.0
 */
function remove_from_register($register_name, $subregister_name) {
	elgg_deprecated_notice("remove_from_register() has been deprecated", 1.8);
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
 * If it exists, returns a particular register as an array
 *
 * @deprecated 1.8 Use the new menu system
 *
 * @param string $register_name The name of the register
 *
 * @return array|false Depending on success
 */
function get_register($register_name) {
	elgg_deprecated_notice("get_register() has been deprecated", 1.8);
	global $CONFIG;

	if ($register_name == 'menu') {
		// backward compatible code for site menu
		$menu = $CONFIG->menus['site'];
		$builder = new ElggMenuBuilder($menu);
		$menu_items = $builder->getMenu('text');
		$menu_items = $menu_items['default'];

		$menu = array();
		foreach ($menu_items as $item) {
			$subregister = new stdClass;
			$subregister->name = $item->getText();
			$subregister->value = $item->getHref();
			$menu[$subregister->name] = $subregister;
		}
		return $menu;
	}

	if (isset($CONFIG->registers[$register_name])) {
		return $CONFIG->registers[$register_name];
	}

	return false;
}

/**
 * Deprecated events core function. Code divided between elgg_register_event_handler()
 * and trigger_elgg_event().
 *
 * @deprecated 1.8 Use explicit register/trigger event functions
 *
 * @param string  $event       The type of event (eg 'init', 'update', 'delete')
 * @param string  $object_type The type of object (eg 'system', 'blog', 'user')
 * @param string  $function    The name of the function that will handle the event
 * @param int     $priority    Priority to call handler. Lower numbers called first (default 500)
 * @param boolean $call        Set to true to call the event rather than add to it (default false)
 * @param mixed   $object      Optionally, the object the event is being performed on (eg a user)
 *
 * @return true|false Depending on success
 */
function events($event = "", $object_type = "", $function = "", $priority = 500, $call = false, $object = null) {

	elgg_deprecated_notice('events() has been deprecated.', 1.8);

	// leaving this here just in case someone was directly calling this internal function
	if (!$call) {
		return elgg_register_event_handler($event, $object_type, $function, $priority);
	} else {
		return trigger_elgg_event($event, $object_type, $object);
	}
}

/**
 * Alias function for events, that registers a function to a particular kind of event
 *
 * @deprecated 1.8 Use elgg_register_event_handler() instead
 * 
 * @param string $event The event type
 * @param string $object_type The object type
 * @param string $function The function name
 * @return true|false Depending on success
 */
function register_elgg_event_handler($event, $object_type, $callback, $priority = 500) {
	elgg_deprecated_notice("register_elgg_event_handler() was deprecated by elgg_register_event_handler()", 1.8);
	return elgg_register_event_handler($event, $object_type, $callback, $priority);
}

/**
 * Unregisters a function to a particular kind of event
 * 
 * @deprecated 1.8 Use elgg_unregister_event_handler instead
 *
 * @param string $event The event type
 * @param string $object_type The object type
 * @param string $function The function name
 * @since 1.7.0
 */
function unregister_elgg_event_handler($event, $object_type, $callback) {
	elgg_deprecated_notice('unregister_elgg_event_handler => elgg_unregister_event_handler', 1.8);
	elgg_unregister_event_handler($event, $object_type, $callback);
}

/**
 * Alias function for events, that triggers a particular kind of event
 *
 * @deprecated 1.8 Use elgg_trigger_event() instead
 * 
 * @param string $event The event type
 * @param string $object_type The object type
 * @param string $function The function name
 * @return true|false Depending on success
 */
function trigger_elgg_event($event, $object_type, $object = null) {
	elgg_deprecated_notice('trigger_elgg_event() was deprecated by elgg_trigger_event()', 1.8);
	return elgg_trigger_event($event, $object_type, $object);
}

/**
 * Register a function to a plugin hook for a particular entity type, with a given priority.
 *
 * @deprecated 1.8 Use elgg_register_plugin_hook_handler() instead
 * 
 * eg if you want the function "export_user" to be called when the hook "export" for "user" entities
 * is run, use:
 *
 * 		register_plugin_hook("export", "user", "export_user");
 *
 * "all" is a valid value for both $hook and $entity_type. "none" is a valid value for $entity_type.
 *
 * The export_user function would then be defined as:
 *
 * 		function export_user($hook, $entity_type, $returnvalue, $params);
 *
 * Where $returnvalue is the return value returned by the last function returned by the hook, and
 * $params is an array containing a set of parameters (or nothing).
 *
 * @param string $hook The name of the hook
 * @param string $entity_type The name of the type of entity (eg "user", "object" etc)
 * @param string $function The name of a valid function to be run
 * @param string $priority The priority - 0 is first, 1000 last, default is 500
 * @return true|false Depending on success
 */
function register_plugin_hook($hook, $type, $callback, $priority = 500) {
	elgg_deprecated_notice("register_plugin_hook() was deprecated by elgg_register_plugin_hook_handler()", 1.8);
	return elgg_register_plugin_hook_handler($hook, $type, $callback, $priority);
}

/**
 * Unregister a function to a plugin hook for a particular entity type
 *
 * @deprecated 1.8 Use elgg_unregister_plugin_hook_handler() instead
 * 
 * @param string $hook The name of the hook
 * @param string $entity_type The name of the type of entity (eg "user", "object" etc)
 * @param string $function The name of a valid function to be run
 * @since 1.7.0
 */
function unregister_plugin_hook($hook, $entity_type, $callback) {
	elgg_deprecated_notice("unregister_plugin_hook() was deprecated by elgg_unregister_plugin_hook_handler()", 1.8);
	elgg_unregister_plugin_hook_handler($hook, $entity_type, $callback);
}

/**
 * Triggers a plugin hook, with various parameters as an array. For example, to provide
 * a 'foo' hook that concerns an entity of type 'bar', with a parameter called 'param1'
 * with value 'value1', that by default returns true, you'd call:
 *
 * @deprecated 1.8 Use elgg_trigger_plugin_hook() instead
 *
 * trigger_plugin_hook('foo', 'bar', array('param1' => 'value1'), true);
 *
 * @see register_plugin_hook
 * @param string $hook The name of the hook to trigger
 * @param string $entity_type The name of the entity type to trigger it for (or "all", or "none")
 * @param array $params Any parameters. It's good practice to name the keys, i.e. by using array('name' => 'value', 'name2' => 'value2')
 * @param mixed $returnvalue An initial return value
 * @return mixed|null The cumulative return value for the plugin hook functions
 */
function trigger_plugin_hook($hook, $type, $params = null, $returnvalue = null) {
	elgg_deprecated_notice("trigger_plugin_hook() was deprecated by elgg_trigger_plugin_hook()", 1.8);
	return elgg_trigger_plugin_hook($hook, $type, $params, $returnvalue);
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
			if ((strcmp($call['class'], $function[0]) == 0) && (strcmp($call['function'], $function[1]) == 0)) {
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
		system_message("Gatekeeper'd function called from {$callstack[1]['file']}:" . "{$callstack[1]['line']}\n\nStack trace:\n\n" . print_r($callstack, true));
	}

	return false;
}

/**
 * Returns SQL where clause for owner and containers.
 *
 * @deprecated 1.8 Use elgg_get_guid_based_where_sql();
 *
 * @param string     $table       Entity table prefix as defined in SELECT...FROM entities $table
 * @param NULL|array $owner_guids Owner GUIDs
 *
 * @return FALSE|str
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_owner_where_sql($table, $owner_guids) {
	elgg_deprecated_notice('elgg_get_entity_owner_where_sql() is deprecated by elgg_get_guid_based_where_sql().', 1.8);

	return elgg_get_guid_based_where_sql("{$table}.owner_guid", $owner_guids);
}

/**
 * Returns SQL where clause for containers.
 *
 * @deprecated 1.8 Use elgg_get_guid_based_where_sql();
 *
 * @param string     $table           Entity table prefix as defined in
 *                                    SELECT...FROM entities $table
 * @param NULL|array $container_guids Array of container guids
 *
 * @return FALSE|string
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_container_where_sql($table, $container_guids) {
	elgg_deprecated_notice('elgg_get_entity_container_where_sql() is deprecated by elgg_get_guid_based_where_sql().', 1.8);

	return elgg_get_guid_based_where_sql("{$table}.container_guid", $container_guids);
}

/**
 * Returns SQL where clause for site entities
 *
 * @deprecated 1.8 Use elgg_get_guid_based_where_sql()
 *
 * @param string     $table      Entity table prefix as defined in SELECT...FROM entities $table
 * @param NULL|array $site_guids Array of site guids
 *
 * @return FALSE|string
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_site_where_sql($table, $site_guids) {
	elgg_deprecated_notice('elgg_get_entity_site_where_sql() is deprecated by elgg_get_guid_based_where_sql().', 1.8);

	return elgg_get_guid_based_where_sql("{$table}.site_guid", $site_guids);
}

/**
 * Return an array of objects in a given container.
 *
 * @see get_entities()
 *
 * @param int    $group_guid The container (defaults to current page owner)
 * @param string $subtype    The subtype
 * @param int    $owner_guid Owner
 * @param int    $site_guid  The site
 * @param string $order_by   Order
 * @param int    $limit      Limit on number of elements to return, by default 10.
 * @param int    $offset     Where to start, by default 0.
 * @param bool   $count      Whether to return the entities or a count of them.
 *
 * @return array|false
 * @deprecated 1.8 Use elgg_get_entities() instead
 */
function get_objects_in_group($group_guid, $subtype = "", $owner_guid = 0, $site_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = FALSE) {
	elgg_deprecated_notice("get_objects_in_group was deprected in 1.8.  Use elgg_get_entities() instead", 1.8);

	global $CONFIG;

	if ($subtype === FALSE || $subtype === null || $subtype === 0) {
		return FALSE;
	}

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int)$site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$container_guid = (int)$group_guid;
	if ($container_guid == 0) {
		$container_guid = elgg_get_page_owner_guid();
	}

	$where = array();

	$where[] = "e.type='object'";

	if (!empty($subtype)) {
		if (!$subtype = get_subtype_id('object', $subtype)) {
			return FALSE;
		}
		$where[] = "e.subtype=$subtype";
	}
	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_guid = (int)$owner_guid;
			$where[] = "e.container_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			// Cast every element to the owner_guid array to int
			$owner_guid = array_map("sanitise_int", $owner_guid);
			$owner_guid = implode(",", $owner_guid);
			$where[] = "e.container_guid in ({$owner_guid})";
		}
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if (!$count) {
		$query = "SELECT * from {$CONFIG->dbprefix}entities e" . " join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
	} else {
		$query = "SELECT count(e.guid) as total from {$CONFIG->dbprefix}entities e" . " join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$count) {
		$query .= " order by $order_by";

		// Add order and limit
		if ($limit) {
			$query .= " limit $offset, $limit";
		}

		$dt = get_data($query, "entity_row_to_elggstar");
		return $dt;
	} else {
		$total = get_data_row($query);
		return $total->total;
	}
}

/**
 * Lists entities that belong to a group.
 *
 * @param string $subtype        The arbitrary subtype of the entity
 * @param int    $owner_guid     The GUID of the owning user
 * @param int    $container_guid The GUID of the containing group
 * @param int    $limit          The number of entities to display per page (default: 10)
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow gallery view (default: true)
 * @param bool   $pagination     Whether to display pagination (default: true)
 *
 * @return string List of parsed entities
 *
 * @see elgg_list_entities()
 * @deprecated 1.8 Use elgg_list_entities() instead
 */
function list_entities_groups($subtype = "", $owner_guid = 0, $container_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true) {
	elgg_deprecated_notice("list_entities_groups was deprecated in 1.8.  Use elgg_list_entities() instead.", 1.8);
	$offset = (int)get_input('offset');
	$count = get_objects_in_group($container_guid, $subtype, $owner_guid, 0, "", $limit, $offset, true);
	$entities = get_objects_in_group($container_guid, $subtype, $owner_guid, 0, "", $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $listtypetoggle, $pagination);
}

/**
 * Get all the entities from metadata from a group.
 *
 * @param int    $group_guid     The ID of the group.
 * @param mixed  $meta_name      Metadata name
 * @param mixed  $meta_value     Metadata value
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner guid
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site GUID. 0 for current, -1 for any
 * @param bool   $count          Return count instead of entities
 *
 * @return array|false
 * @deprecated 1.8 Use elgg_get_entities_from_metadata()
 */
function get_entities_from_metadata_groups($group_guid, $meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	elgg_deprecated_notice("get_entities_from_metadata_groups was deprecated in 1.8.", 1.8);
	global $CONFIG;

	$meta_n = get_metastring_id($meta_name);
	$meta_v = get_metastring_id($meta_value);

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$site_guid = (int)$site_guid;
	if (is_array($owner_guid)) {
		foreach ($owner_guid as $key => $guid) {
			$owner_guid[$key] = (int)$guid;
		}
	} else {
		$owner_guid = (int)$owner_guid;
	}
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$container_guid = (int)$group_guid;
	if ($container_guid == 0) {
		$container_guid = elgg_get_page_owner_guid();
	}

	$where = array();

	if ($entity_type != "") {
		$where[] = "e.type='$entity_type'";
	}
	if ($entity_subtype) {
		$where[] = "e.subtype=$entity_subtype";
	}
	if ($meta_name != "") {
		$where[] = "m.name_id='$meta_n'";
	}
	if ($meta_value != "") {
		$where[] = "m.value_id='$meta_v'";
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}
	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if (is_array($owner_guid)) {
		$where[] = "e.container_guid in (" . implode(",", $owner_guid) . ")";
	} else if ($owner_guid > 0) {
		$where[] = "e.container_guid = {$owner_guid}";
	}

	if (!$count) {
		$query = "SELECT distinct e.* ";
	} else {
		$query = "SELECT count(e.guid) as total ";
	}

	$query .= "from {$CONFIG->dbprefix}entities e" . " JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid " . " JOIN {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid where";

	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix("e");

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($row = get_data_row($query)) {
			return $row->total;
		}
	}
	return false;
}

/**
 * As get_entities_from_metadata_groups() but with multiple entities.
 *
 * @param int    $group_guid     The ID of the group.
 * @param array  $meta_array     Array of 'name' => 'value' pairs
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site GUID. 0 for current, -1 for any
 * @param bool   $count          Return count of entities instead of entities
 *
 * @return int|array List of ElggEntities, or the total number if count is set to false
 * @deprecated 1.8 Use elgg_get_entities_from_metadata()
 */
function get_entities_from_metadata_groups_multi($group_guid, $meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	elgg_deprecated_notice("get_entities_from_metadata_groups_multi was deprecated in 1.8.", 1.8);

	global $CONFIG;

	if (!is_array($meta_array) || sizeof($meta_array) == 0) {
		return false;
	}

	$where = array();

	$mindex = 1;
	$join = "";
	foreach ($meta_array as $meta_name => $meta_value) {
		$meta_n = get_metastring_id($meta_name);
		$meta_v = get_metastring_id($meta_value);
		$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid" . " JOIN {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid ";

		if ($meta_name != "") {
			$where[] = "m{$mindex}.name_id='$meta_n'";
		}

		if ($meta_value != "") {
			$where[] = "m{$mindex}.value_id='$meta_v'";
		}

		$mindex++;
	}

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$owner_guid = (int)$owner_guid;

	$site_guid = (int)$site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	//$access = get_access_list();

	if ($entity_type != "") {
		$where[] = "e.type = '{$entity_type}'";
	}

	if ($entity_subtype) {
		$where[] = "e.subtype = {$entity_subtype}";
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if ($owner_guid > 0) {
		$where[] = "e.owner_guid = {$owner_guid}";
	}

	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT distinct e.* ";
	}

	$query .= " from {$CONFIG->dbprefix}entities e {$join} where";
	foreach ($where as $w) {
		$query .= " $w and ";
	}
	$query .= get_access_sql_suffix("e"); // Add access controls

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}
	return false;
}

/**
 * List items within a given geographic area.
 *
 * @param real   $lat            Latitude
 * @param real   $long           Longitude
 * @param real   $radius         The radius
 * @param string $type           The type of entity (eg "user", "object" etc)
 * @param string $subtype        The arbitrary subtype of the entity
 * @param int    $owner_guid     The GUID of the owning user
 * @param int    $limit          The number of entities to display per page (default: 10)
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow gallery view
 * @param bool   $navigation     Display pagination? Default: true
 *
 * @return string A viewable list of entities
 * @deprecated 1.8
 */
function list_entities_in_area($lat, $long, $radius, $type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_entities_in_area() was deprecated. Use elgg_list_entities_from_location()', 1.8);

	$options = array();

	$options['latitude'] = $lat;
	$options['longitude'] = $long;
	$options['distance'] = $radius;

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	$options['limit'] = $limit;

	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities_from_location($options);
}

/**
 * List entities in a given location
 *
 * @param string $location       Location
 * @param string $type           The type of entity (eg "user", "object" etc)
 * @param string $subtype        The arbitrary subtype of the entity
 * @param int    $owner_guid     The GUID of the owning user
 * @param int    $limit          The number of entities to display per page (default: 10)
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow gallery view
 * @param bool   $navigation     Display pagination? Default: true
 *
 * @return string A viewable list of entities
 * @deprecated 1.8
 */
function list_entities_location($location, $type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_entities_location() was deprecated. Use elgg_list_entities_from_metadata()', 1.8);

	return list_entities_from_metadata('location', $location, $type, $subtype, $owner_guid, $limit, $fullview, $listtypetoggle, $navigation);
}

/**
 * Return entities within a given geographic area.
 *
 * @param float     $lat            Latitude
 * @param float     $long           Longitude
 * @param float     $radius         The radius
 * @param string    $type           The type of entity (eg "user", "object" etc)
 * @param string    $subtype        The arbitrary subtype of the entity
 * @param int       $owner_guid     The GUID of the owning user
 * @param string    $order_by       The field to order by; by default, time_created desc
 * @param int       $limit          The number of entities to return; 10 by default
 * @param int       $offset         The indexing offset, 0 by default
 * @param boolean   $count          Count entities
 * @param int       $site_guid      Site GUID. 0 for current, -1 for any
 * @param int|array $container_guid Container GUID
 *
 * @return array A list of entities.
 * @deprecated 1.8
 */
function get_entities_in_area($lat, $long, $radius, $type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = NULL) {
	elgg_deprecated_notice('get_entities_in_area() was deprecated by elgg_get_entities_from_location()!', 1.8);

	$options = array();

	$options['latitude'] = $lat;
	$options['longitude'] = $long;
	$options['distance'] = $radius;

	// set container_guid to owner_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
		}
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_location($options);
}

/**
 * Return a list of entities suitable for display based on the given search criteria.
 *
 * @see elgg_view_entity_list
 *
 * @deprecated 1.8 Use elgg_list_entities_from_metadata
 *
 * @param mixed  $meta_name      Metadata name to search on
 * @param mixed  $meta_value     The value to match, optionally
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Number of entities to display per page
 * @param bool   $fullview       WDisplay the full view (default: true)
 * @param bool   $listtypetoggle Allow users to toggle to the gallery view. Default: true
 * @param bool   $pagination     Display pagination? Default: true
 * @param bool   $case_sensitive Case sensitive metadata names?
 *
 * @return string
 *
 * @return string A list of entities suitable for display
 */
function list_entities_from_metadata($meta_name, $meta_value = "", $entity_type = ELGG_ENTITIES_ANY_VALUE, $entity_subtype = ELGG_ENTITIES_ANY_VALUE, $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true, $case_sensitive = true) {

	elgg_deprecated_notice('list_entities_from_metadata() was deprecated by elgg_list_entities_from_metadata()!', 1.8);

	$offset = (int)get_input('offset');
	$limit = (int)$limit;
	$options = array(
		'metadata_name' => $meta_name,
		'metadata_value' => $meta_value,
		'types' => $entity_type,
		'subtypes' => $entity_subtype,
		'limit' => $limit,
		'offset' => $offset,
		'count' => TRUE,
		'metadata_case_sensitive' => $case_sensitive
	);

	// previous function allowed falsy $owner_guid for anything
	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	$count = elgg_get_entities_from_metadata($options);

	$options['count'] = FALSE;
	$entities = elgg_get_entities_from_metadata($options);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $listtypetoggle, $pagination);
}

/**
 * Returns a viewable list of entities based on the given search criteria.
 *
 * @see elgg_view_entity_list
 *
 * @param array  $meta_array     Array of 'name' => 'value' pairs
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param bool   $fullview       WDisplay the full view (default: true)
 * @param bool   $listtypetoggle Allow users to toggle to the gallery view. Default: true
 * @param bool   $pagination     Display pagination? Default: true
 *
 * @return string List of ElggEntities suitable for display
 *
 * @deprecated 1.8 Use elgg_list_entities_from_metadata() instead
 */
function list_entities_from_metadata_multi($meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true) {
	elgg_deprecated_notice(elgg_echo('deprecated:function', array(
		'list_entities_from_metadata_multi', 'elgg_get_entities_from_metadata')), 1.8);

	$offset = (int)get_input('offset');
	$limit = (int)$limit;
	$count = get_entities_from_metadata_multi($meta_array, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", $site_guid, true);
	$entities = get_entities_from_metadata_multi($meta_array, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", $site_guid, false);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $listtypetoggle, $pagination);
}

/**
 * Deprecated by elgg_register_menu_item(). Set $menu_name to 'page'.
 *
 * @see elgg_register_menu_item()
 * @deprecated 1.8
 *
 * @param string  $label    The label
 * @param string  $link     The link
 * @param string  $group    The group to store item in
 * @param boolean $onclick  Add a confirmation when clicked?
 * @param boolean $selected Is menu item selected
 *
 * @return bool
 */
function add_submenu_item($label, $link, $group = 'default', $onclick = false, $selected = NULL) {
	elgg_deprecated_notice('add_submenu_item was deprecated by elgg_register_menu_item', 1.8);

	// submenu items were added in the page setup hook usually by checking
	// the context.  We'll pass in the current context here, which will
	// emulate that effect.
	// if context == 'main' (default) it probably means they always wanted
	// the menu item to show up everywhere.
	$context = elgg_get_context();

	if ($context == 'main') {
		$context = 'all';
	}

	$item = array('name' => $label, 'text' => $label, 'href' => $link, 'context' => $context,
		'section' => $group,);

	if ($selected) {
		$item['selected'] = true;
	}

	if ($onclick) {
		$js = "onclick=\"javascript:return confirm('" . elgg_echo('deleteconfirm') . "')\"";
		$item['vars'] = array('js' => $js);
	}

	return elgg_register_menu_item('page', $item);
}

/**
 * Remove an item from submenu by label
 *
 * @deprecated 1.8
 * @see elgg_unregister_menu_item()
 *
 * @param string $label The item label
 * @param string $group The submenu group (default "a")
 * @return bool whether the item was removed or not
 * @since 1.7.8
 */
function remove_submenu_item($label, $group = 'a') {
	elgg_deprecated_notice('remove_submenu_item was deprecated by elgg_unregister_menu_item', 1.8);

	return elgg_unregister_menu_item('page', $label);
}

/**
 * Use elgg_view_menu(). Set $menu_name to 'owner_block'.
 *
 * @see elgg_view_menu()
 * @deprecated 1.8
 *
 * @return string
 */
function get_submenu() {
	elgg_deprecated_notice("get_submenu() has been deprecated by elgg_view_menu()", 1.8);
	return elgg_view_menu('owner_block', array('entity' => $owner,
		'class' => 'elgg-menu-owner-block',));
}

/**
 * Adds an item to the site-wide menu.
 *
 * You can obtain the menu array by calling {@link get_register('menu')}
 *
 * @param string $menu_name     The name of the menu item
 * @param string $menu_url      The URL of the page
 * @param array  $menu_children Optionally, an array of submenu items (not used)
 * @param string $context       (not used)
 *
 * @return true|false Depending on success
 * @deprecated 1.8 use elgg_register_menu_item() for the menu 'site'
 */
function add_menu($menu_name, $menu_url, $menu_children = array(), $context = "") {
	elgg_deprecated_notice('add_menu() deprecated by elgg_register_menu_item()', 1.8);

	return elgg_register_menu_item('site', array('name' => $menu_name, 'text' => $menu_name,
		'href' => $menu_url,));
}

/**
 * Removes an item from the menu register
 *
 * @param string $menu_name The name of the menu item
 *
 * @return true|false Depending on success
 * @deprecated 1.8
 */
function remove_menu($menu_name) {
	elgg_deprecated_notice("remove_menu() deprecated by elgg_unregister_menu_item()", 1.8);
	return elgg_unregister_menu_item('site', $menu_name);
}

/**
 * When given a title, returns a version suitable for inclusion in a URL
 *
 * @param string $title The title
 *
 * @return string The optimised title
 * @deprecated 1.8
 */
function friendly_title($title) {
	elgg_deprecated_notice('friendly_title was deprecated by elgg_get_friendly_title', 1.8);
	return elgg_get_friendly_title($title);
}

/**
 * Displays a UNIX timestamp in a friendly way (eg "less than a minute ago")
 *
 * @param int $time A UNIX epoch timestamp
 *
 * @return string The friendly time
 * @deprecated 1.8
 */
function friendly_time($time) {
	elgg_deprecated_notice('friendly_time was deprecated by elgg_view_friendly_time', 1.8);
	return elgg_view_friendly_time($time);
}

/**
 * Filters a string into an array of significant words
 *
 * @deprecated 1.8
 *
 * @param string $string A string
 *
 * @return array
 */
function filter_string($string) {
	elgg_deprecated_notice('filter_string() was deprecated!', 1.8);

	// Convert it to lower and trim
	$string = strtolower($string);
	$string = trim($string);

	// Remove links and email addresses
	// match protocol://address/path/file.extension?some=variable&another=asf%
	$string = preg_replace("/\s([a-zA-Z]+:\/\/[a-z][a-z0-9\_\.\-]*[a-z]{2,6}" . "[a-zA-Z0-9\/\*\-\?\&\%\=]*)([\s|\.|\,])/iu", " ", $string);

	// match www.something.domain/path/file.extension?some=variable&another=asf%
	$string = preg_replace("/\s(www\.[a-z][a-z0-9\_\.\-]*[a-z]{2,6}" . "[a-zA-Z0-9\/\*\-\?\&\%\=]*)([\s|\.|\,])/iu", " ", $string);

	// match name@address
	$string = preg_replace("/\s([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]" . "*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})([\s|\.|\,])/iu", " ", $string);

	// Sanitise the string; remove unwanted characters
	$string = preg_replace('/\W/ui', ' ', $string);

	// Explode it into an array
	$terms = explode(' ', $string);

	// Remove any blacklist terms
	//$terms = array_filter($terms, 'remove_blacklist');

	return $terms;
}

/**
 * Returns true if the word in $input is considered significant
 *
 * @deprecated 1.8
 *
 * @param string $input A word
 *
 * @return true|false
 */
function remove_blacklist($input) {
	elgg_deprecated_notice('remove_blacklist() was deprecated!', 1.8);

	global $CONFIG;

	if (!is_array($CONFIG->wordblacklist)) {
		return $input;
	}

	if (strlen($input) < 3 || in_array($input, $CONFIG->wordblacklist)) {
		return false;
	}

	return true;
}

/**
 * Gets the guid of the entity that owns the current page.
 *
 * @deprecated 1.8  Use elgg_get_page_owner_guid()
 *
 * @return int The current page owner guid (0 if none).
 */
function page_owner() {
	elgg_deprecated_notice('page_owner() was deprecated by elgg_get_page_owner_guid().', 1.8);
	return elgg_get_page_owner_guid();
}

/**
 * Gets the owner entity for the current page.
 *
 * @deprecated 1.8  Use elgg_get_page_owner()
 * @return ElggEntity|false The current page owner or false if none.
 */
function page_owner_entity() {
	elgg_deprecated_notice('page_owner_entity() was deprecated by elgg_get_page_owner_entity().', 1.8);
	return elgg_get_page_owner_entity();
}

/**
 * Registers a page owner handler function
 *
 * @param string $functionname The callback function
 *
 * @deprecated 1.8  Use the 'page_owner', 'system' plugin hook
 * @return void
 */
function add_page_owner_handler($functionname) {
	elgg_deprecated_notice("add_page_owner_handler() was deprecated by the plugin hook 'page_owner', 'system'.", 1.8);
}

/**
 * Set a page owner entity
 *
 * @param int $entitytoset The GUID of the entity
 *
 * @deprecated 1.8  Use elgg_set_page_owner_guid()
 * @return void
 */
function set_page_owner($entitytoset = -1) {
	elgg_deprecated_notice('set_page_owner() was deprecated by elgg_set_page_owner_guid().', 1.8);
	elgg_set_page_owner_guid($entitytoset);
}

/**
 * Sets the functional context of a page
 *
 * @deprecated 1.8  Use elgg_set_context()
 *
 * @param string $context The context of the page
 *
 * @return mixed Either the context string, or false on failure
 */
function set_context($context) {
	elgg_deprecated_notice('set_context() was deprecated by elgg_set_context().', 1.8);
	elgg_set_context($context);
	if (empty($context)) {
		return false;
	}
	return $context;
}

/**
 * Returns the functional context of a page
 *
 * @deprecated 1.8  Use elgg_get_context()
 *
 * @return string The context, or 'main' if no context has been provided
 */
function get_context() {
	elgg_deprecated_notice('get_context() was deprecated by elgg_get_context().', 1.8);
	return elgg_get_context();

	// @todo - used to set context based on calling script
	// $context = get_plugin_name(true)
}

/**
 * Returns a list of plugins to load, in the order that they should be loaded.
 *
 * @deprecated 1.8
 *
 * @return array List of plugins
 */
function get_plugin_list() {
	elgg_deprecated_notice('get_plugin_list() is deprecated by elgg_get_plugin_ids_in_dir() or elgg_get_plugins()', 1.8);

	$plugins = elgg_get_plugins('any');

	$list = array();
	if ($plugins) {
		foreach ($plugins as $i => $plugin) {
			// in <=1.7 this returned indexed by multiples of 10.
			// uh...sure...why not.
			$index = ($i + 1) * 10;
			$list[$index] = $plugin->getID();
		}
	}

	return $list;
}

/**
 * Regenerates the list of known plugins and saves it to the current site
 *
 * Important: You should regenerate simplecache and the viewpath cache after executing this function
 * otherwise you may experience view display artifacts. Do this with the following code:
 *
 * 		elgg_regenerate_simplecache();
 *		elgg_filepath_cache_reset();
 *
 * @deprecated 1.8
 *
 * @param array $pluginorder Optionally, a list of existing plugins and their orders
 *
 * @return array The new list of plugins and their orders
 */
function regenerate_plugin_list($pluginorder = FALSE) {
	$msg = 'regenerate_plugin_list() is (sorta) deprecated by elgg_generate_plugin_entities() and'
			. ' elgg_set_plugin_priorities().';
	elgg_deprecated_notice($msg, 1.8);

	// they're probably trying to set it?
	if ($pluginorder) {
		if (elgg_generate_plugin_entities()) {
			// sort the plugins by the index numerically since we used
			// weird indexes in the old system.
			ksort($pluginorder, SORT_NUMERIC);
			return elgg_set_plugin_priorities($pluginorder);
		}
		return false;
	} else {
		// they're probably trying to regenerate from disk?
		return elgg_generate_plugin_entities();
	}
}

/**
 * Get the name of the most recent plugin to be called in the
 * call stack (or the plugin that owns the current page, if any).
 *
 * i.e., if the last plugin was in /mod/foobar/, get_plugin_name would return foo_bar.
 *
 * @deprecated 1.8
 *
 * @param boolean $mainfilename If set to true, this will instead determine the
 *                              context from the main script filename called by
 *                              the browser. Default = false.
 *
 * @return string|false Plugin name, or false if no plugin name was called
 */
function get_plugin_name($mainfilename = false) {
	elgg_deprecated_notice('get_plugin_name() is deprecated by elgg_get_calling_plugin_id()', 1.8);

	return elgg_get_calling_plugin_id($mainfilename);
}

/**
 * Load and parse a plugin manifest from a plugin XML file.
 *
 * @example plugins/manifest.xml Example 1.8-style manifest file.
 *
 * @deprecated 1.8
 *
 * @param string $plugin Plugin name.
 * @return array of values
 */
function load_plugin_manifest($plugin) {
	elgg_deprecated_notice('load_plugin_manifest() is deprecated by ElggPlugin->getManifest()', 1.8);

	$xml_file = elgg_get_plugins_path() . "$plugin/manifest.xml";

	try {
		$manifest = new ElggPluginManifest($xml_file, $plugin);
	} catch(Exception $e) {
		return false;
	}

	return $manifest->getManifest();
}

/**
 * This function checks a plugin manifest 'elgg_version' value against the current install
 * returning TRUE if the elgg_version is >= the current install's version.
 *
 * @deprecated 1.8
 *
 * @param string $manifest_elgg_version_string The build version (eg 2009010201).
 * @return bool
 */
function check_plugin_compatibility($manifest_elgg_version_string) {
	elgg_deprecated_notice('check_plugin_compatibility() is deprecated by ElggPlugin->canActivate()', 1.8);

	$version = get_version();

	if (strpos($manifest_elgg_version_string, '.') === false) {
		// Using version
		$req_version = (int)$manifest_elgg_version_string;

		return ($version >= $req_version);
	}

	return false;
}

/**
 * Shorthand function for finding the plugin settings.
 *
 * @deprecated 1.8
 *
 * @param string $plugin_id Optional plugin id, if not specified
 *                          then it is detected from where you are calling.
 *
 * @return mixed
 */
function find_plugin_settings($plugin_id = null) {
	elgg_deprecated_notice('find_plugin_setting() is deprecated by elgg_get_calling_plugin_entity() or elgg_get_plugin_from_id()', 1.8);
	if ($plugin_id) {
		return elgg_get_plugin_from_id($plugin_id);
	} else {
		return elgg_get_calling_plugin_entity();
	}
}

/**
 * Return an array of installed plugins.
 *
 * @deprecated 1.8
 *
 * @param string $status any|enabled|disabled
 * @return array
 */
function get_installed_plugins($status = 'all') {
	global $CONFIG;

	elgg_deprecated_notice('get_installed_plugins() was deprecated by elgg_get_plugins()', 1.8);

	$plugins = elgg_get_plugins($status);

	if (!$plugins) {
		return array();
	}

	$installed_plugins = array();

	foreach ($plugins as $plugin) {
		if (!$plugin->isValid()) {
			continue;
		}

		$include = true;

		if ($status == 'enabled' && !$plugin->isActive()) {
			$include = false;
		} elseif ($status == 'disabled' && $plugin->isActive()) {
			$include = true;
		}

		if ($include) {
			$installed_plugins[$plugin->getID()] = array(
				'active' => $plugin->isActive(),
				'manifest' => $plugin->getManifest()->getManifest()
			);
		}
	}

	return $installed_plugins;
}

/**
 * Enable a plugin for a site (default current site)
 *
 * Important: You should regenerate simplecache and the viewpath cache after executing this function
 * otherwise you may experience view display artifacts. Do this with the following code:
 *
 * 		elgg_regenerate_simplecache();
 *		elgg_filepath_cache_reset();
 *
 * @deprecated 1.8
 *
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return array
 * @throws InvalidClassException
 */
function enable_plugin($plugin, $site_guid = null) {
	elgg_deprecated_notice('enable_plugin() was deprecated by ElggPlugin->activate()', 1.8);

	$plugin = sanitise_string($plugin);

	$site_guid = (int) $site_guid;
	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	try {
		$plugin = new ElggPlugin($plugin);
	} catch(Exception $e) {
		return false;
	}

	if (!$plugin->canActivate($site_guid)) {
		return false;
	}

	return $plugin->activate($site_guid);
}

/**
 * Disable a plugin for a site (default current site)
 *
 * Important: You should regenerate simplecache and the viewpath cache after executing this function
 * otherwise you may experience view display artifacts. Do this with the following code:
 *
 * 		elgg_regenerate_simplecache();
 *		elgg_filepath_cache_reset();
 *
 * @deprecated 1.8
 *
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return bool
 * @throws InvalidClassException
 */
function disable_plugin($plugin, $site_guid = 0) {
	elgg_deprecated_notice('disable_plugin() was deprecated by ElggPlugin->deactivate()', 1.8);

	$plugin = sanitise_string($plugin);

	$site_guid = (int) $site_guid;
	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	try {
		$plugin = new ElggPlugin($plugin);
	} catch(Exception $e) {
		return false;
	}

	return $plugin->deactivate($site_guid);
}

/**
 * Return whether a plugin is enabled or not.
 *
 * @deprecated 1.8
 *
 * @param string $plugin    The plugin name.
 * @param int    $site_guid The site id, if not specified then this is detected.
 *
 * @return bool
 */
function is_plugin_enabled($plugin, $site_guid = 0) {
	elgg_deprecated_notice('is_plugin_enabled() was deprecated by elgg_is_active_plugin()', 1.8);
	return elgg_is_active_plugin($plugin, $site_guid);
}

/**
 * Get entities based on their private data.
 *
 * @param string  $name           The name of the setting
 * @param string  $value          The value of the setting
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param string  $order_by       The field to order by; by default, time_created desc
 * @param int     $limit          The number of entities to return; 10 by default
 * @param int     $offset         The indexing offset, 0 by default
 * @param boolean $count          Return a count of entities
 * @param int     $site_guid      The site to get entities for. 0 for current, -1 for any
 * @param mixed   $container_guid The container(s) GUIDs
 *
 * @return array A list of entities.
 * @deprecated 1.8
 */
function get_entities_from_private_setting($name = "", $value = "", $type = "", $subtype = "",
$owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0,
$container_guid = null) {
	elgg_deprecated_notice('get_entities_from_private_setting() was deprecated by elgg_get_entities_from_private_setting()!', 1.8);

	$options = array();

	$options['private_setting_name'] = $name;
	$options['private_setting_value'] = $value;

	// set container_guid to owner_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
		}
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_private_settings($options);
}

/**
 * Get entities based on their private data by multiple keys.
 *
 * @param string $name           The name of the setting
 * @param mixed  $type           Entity type
 * @param string $subtype        Entity subtype
 * @param int    $owner_guid     The GUID of the owning user
 * @param string $order_by       The field to order by; by default, time_created desc
 * @param int    $limit          The number of entities to return; 10 by default
 * @param int    $offset         The indexing offset, 0 by default
 * @param bool   $count          Count entities
 * @param int    $site_guid      Site GUID. 0 for current, -1 for any.
 * @param mixed  $container_guid Container GUID
 *
 * @return array A list of entities.
 * @deprecated 1.8
 */
function get_entities_from_private_setting_multi(array $name, $type = "", $subtype = "",
$owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false,
$site_guid = 0, $container_guid = null) {

	elgg_deprecated_notice('get_entities_from_private_setting_multi() was deprecated by elgg_get_entities_from_private_setting()!', 1.8);

	$options = array();

	$pairs = array();
	foreach ($name as $setting_name => $setting_value) {
		$pairs[] = array('name' => $setting_name, 'value' => $setting_value);
	}
	$options['private_setting_name_value_pairs'] = $pairs;

	// set container_guid to owner_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
		}
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_private_settings($options);
}

/**
 * Returns a viewable list of entities by relationship
 *
 * @see elgg_view_entity_list
 *
 * @deprecated 1.8 Use elgg_list_entities_from_relationship()
 *
 * @param string $relationship The relationship eg "friends_of"
 * @param int $relationship_guid The guid of the entity to use query
 * @param bool $inverse_relationship Reverse the normal function of the query to instead say "give me all entities for whome $relationship_guid is a $relationship of"
 * @param string $type The type of entity (eg 'object')
 * @param string $subtype The entity subtype
 * @param int $owner_guid The owner (default: all)
 * @param int $limit The number of entities to display on a page
 * @param true|false $fullview Whether or not to display the full view (default: true)
 * @param true|false $viewtypetoggle Whether or not to allow gallery view
 * @param true|false $pagination Whether to display pagination (default: true)
 * @param bool $order_by SQL order by clause
 * @return string The viewable list of entities
 */
function list_entities_from_relationship($relationship, $relationship_guid,
$inverse_relationship = false, $type = ELGG_ENTITIES_ANY_VALUE,
$subtype = ELGG_ENTITIES_ANY_VALUE, $owner_guid = 0, $limit = 10,
$fullview = true, $listtypetoggle = false, $pagination = true, $order_by = '') {

	elgg_deprecated_notice("list_entities_from_relationship was deprecated by elgg_list_entities_from_relationship()!", 1.8);
	return elgg_list_entities_from_relationship(array(
		'relationship' => $relationship,
		'relationship_guid' => $relationship_guid,
		'inverse_relationship' => $inverse_relationship,
		'types' => $type,
		'subtypes' => $subtype,
		'owner_guid' => $owner_guid,
		'order_by' => $order_by,
		'limit' => $limit,
		'full_view' => $fullview,
		'list_type_toggle' => $listtypetoggle,
		'pagination' => $pagination,
	));
}

/**
 * Gets the number of entities by a the number of entities related to them in a particular way.
 * This is a good way to get out the users with the most friends, or the groups with the
 * most members.
 *
 * @deprecated 1.8 Use elgg_get_entities_from_relationship_count()
 *
 * @param string $relationship         The relationship eg "friends_of"
 * @param bool   $inverse_relationship Inverse relationship owners
 * @param string $type                 The type of entity (default: all)
 * @param string $subtype              The entity subtype (default: all)
 * @param int    $owner_guid           The owner of the entities (default: none)
 * @param int    $limit                Limit
 * @param int    $offset               Offset
 * @param bool   $count                Return a count instead of entities
 * @param int    $site_guid            Site GUID
 *
 * @return array|int|false An array of entities, or the number of entities, or false on failure
 */
function get_entities_by_relationship_count($relationship, $inverse_relationship = true, $type = "",
$subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $count = false, $site_guid = 0) {
	elgg_deprecated_notice('get_entities_by_relationship_count() is deprecated by elgg_get_entities_from_relationship_count()', 1.8);

	$options = array();

	$options['relationship'] = $relationship;

	// this used to default to true, which is wrong.
	// flip it for the new function
	$options['inverse_relationship'] = !$inverse_relationship;

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_relationship_count($options);
}

/**
 * Displays a human-readable list of entities
 *
 * @deprecated 1.8
 *
 * @param string $relationship         The relationship eg "friends_of"
 * @param bool   $inverse_relationship Inverse relationship owners
 * @param string $type                 The type of entity (eg 'object')
 * @param string $subtype              The entity subtype
 * @param int    $owner_guid           The owner (default: all)
 * @param int    $limit                The number of entities to display on a page
 * @param bool   $fullview             Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle       Whether or not to allow gallery view
 * @param bool   $pagination           Whether to display pagination (default: true)
 *
 * @return string The viewable list of entities
 */
function list_entities_by_relationship_count($relationship, $inverse_relationship = true,
$type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true,
$listtypetoggle = false, $pagination = true) {

	elgg_deprecated_notice('list_entities_by_relationship_count() was deprecated by elgg_list_entities_from_relationship_count()', 1.8);

	$options = array();

	$options['relationship'] = $relationship;

	// this used to default to true, which is wrong.
	// flip it for the new function
	$options['inverse_relationship'] = !$inverse_relationship;

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	$options['limit'] = $limit;

	$options['full_view'] = $fullview;

	return elgg_list_entities_from_relationship_count($options);
}

/**
 * Gets the number of entities by a the number of entities related to
 * them in a particular way also constrained by metadata.
 *
 * @deprecated 1.8
 *
 * @param string $relationship         The relationship eg "friends_of"
 * @param int    $relationship_guid    The guid of the entity to use query
 * @param bool   $inverse_relationship Inverse relationship owner
 * @param String $meta_name            The metadata name
 * @param String $meta_value           The metadata value
 * @param string $type                 The type of entity (default: all)
 * @param string $subtype              The entity subtype (default: all)
 * @param int    $owner_guid           The owner of the entities (default: none)
 * @param int    $limit                Limit
 * @param int    $offset               Offset
 * @param bool   $count                Return a count instead of entities
 * @param int    $site_guid            Site GUID
 *
 * @return array|int|false An array of entities, or the number of entities, or false on failure
 */
function get_entities_from_relationships_and_meta($relationship, $relationship_guid,
$inverse_relationship = false, $meta_name = "", $meta_value = "", $type = "",
$subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $count = false, $site_guid = 0) {

	elgg_deprecated_notice('get_entities_from_relationship_and_meta() was deprecated by elgg_get_entities_from_relationship()!', 1.7);

	$options = array();

	$options['relationship'] = $relationship;
	$options['relationship_guid'] = $relationship_guid;
	$options['inverse_relationship'] = $inverse_relationship;

	if ($meta_value) {
		$options['values'] = $meta_value;
	}

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_relationship($options);
}


/**
 * Retrieves items from the river. All parameters are optional.
 *
 * @param int|array $subject_guid         Acting entity to restrict to. Default: all
 * @param int|array $object_guid          Entity being acted on to restrict to. Default: all
 * @param string    $subject_relationship If set to a relationship type, this will use
 * 	                                      $subject_guid as the starting point and set the
 *                                        subjects to be all users this
 *                                        entity has this relationship with (eg 'friend').
 *                                        Default: blank
 * @param string    $type                 The type of entity to restrict to. Default: all
 * @param string    $subtype              The subtype of entity to restrict to. Default: all
 * @param string    $action_type          The type of river action to restrict to. Default: all
 * @param int       $limit                The number of items to retrieve. Default: 20
 * @param int       $offset               The page offset. Default: 0
 * @param int       $posted_min           The minimum time period to look at. Default: none
 * @param int       $posted_max           The maximum time period to look at. Default: none
 *
 * @return array|false Depending on success
 * @deprecated 1.8
 */
function get_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '',
$type = '',	$subtype = '', $action_type = '', $limit = 20, $offset = 0, $posted_min = 0,
$posted_max = 0) {
	elgg_deprecated_notice("get_river_items deprecated by elgg_get_river", 1.8);

	$options = array();

	if ($subject_guid) {
		$options['subject_guid'] = $subject_guid;
	}

	if ($object_guid) {
		$options['object_guid'] = $object_guid;
	}

	if ($subject_relationship) {
		$options['relationship'] = $subject_relationship;
		unset($options['subject_guid']);
		$options['relationship_guid'] = $subject_guid;
	}

	if ($type) {
		$options['type'] = $type;
	}

	if ($subtype) {
		$options['subtype'] = $subtype;
	}

	if ($action_type) {
		$options['action_type'] = $action_type;
	}

	$options['limit'] = $limit;
	$options['offset'] = $offset;

	if ($posted_min) {
		$options['posted_time_lower'] = $posted_min;
	}

	if ($posted_max) {
		$options['posted_time_upper'] = $posted_max;
	}

	return elgg_get_river($options);
}

/**
 * Returns a human-readable version of the river.
 *
 * @param int|array $subject_guid         Acting entity to restrict to. Default: all
 * @param int|array $object_guid          Entity being acted on to restrict to. Default: all
 * @param string    $subject_relationship If set to a relationship type, this will use
 * 	                                      $subject_guid as the starting point and set
 *                                        the subjects to be all users this entity has this
 *                                        relationship with (eg 'friend'). Default: blank
 * @param string    $type                 The type of entity to restrict to. Default: all
 * @param string    $subtype              The subtype of entity to restrict to. Default: all
 * @param string    $action_type          The type of river action to restrict to. Default: all
 * @param int       $limit                The number of items to retrieve. Default: 20
 * @param int       $posted_min           The minimum time period to look at. Default: none
 * @param int       $posted_max           The maximum time period to look at. Default: none
 * @param bool      $pagination           Show pagination?
 *
 * @return string Human-readable river.
 * @deprecated 1.8
 */
function elgg_view_river_items($subject_guid = 0, $object_guid = 0, $subject_relationship = '',
$type = '', $subtype = '', $action_type = '', $limit = 20, $posted_min = 0,
$posted_max = 0, $pagination = true) {
	elgg_deprecated_notice("elgg_view_river_items deprecated for elgg_list_river", 1.8);

	$river_items = get_river_items($subject_guid, $object_guid, $subject_relationship,
			$type, $subtype, $action_type, $limit + 1, $posted_min, $posted_max);

	// Get input from outside world and sanitise it
	$offset = (int) get_input('offset', 0);

	// view them
	$params = array(
		'items' => $river_items,
		'count' => count($river_items),
		'offset' => $offset,
		'limit' => $limit,
		'pagination' => $pagination,
		'list-class' => 'elgg-river-list',
	);

	return elgg_view('page/components/list', $params);
}

/**
 * Construct and execute the query required for the activity stream.
 *
 * @deprecated 1.8
 */
function get_activity_stream_data($limit = 10, $offset = 0, $type = "", $subtype = "",
$owner_guid = "", $owner_relationship = "") {
	elgg_deprecated_notice("get_activity_stream_data was deprecated", 1.8);

	global $CONFIG;

	$limit = (int)$limit;
	$offset = (int)$offset;

	if ($type) {
		if (!is_array($type)) {
			$type = array(sanitise_string($type));
		} else {
			foreach ($type as $k => $v) {
				$type[$k] = sanitise_string($v);
			}
		}
	}

	if ($subtype) {
		if (!is_array($subtype)) {
			$subtype = array(sanitise_string($subtype));
		} else {
			foreach ($subtype as $k => $v) {
				$subtype[$k] = sanitise_string($v);
			}
		}
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			foreach ($owner_guid as $k => $v) {
				$owner_guid[$k] = (int)$v;
			}
		} else {
			$owner_guid = array((int)$owner_guid);
		}
	}

	$owner_relationship = sanitise_string($owner_relationship);

	// Get a list of possible views
	$activity_events = array();
	$activity_views = array_merge(elgg_view_tree('activity', 'default'),
		elgg_view_tree('river', 'default'));

	$done = array();

	foreach ($activity_views as $view) {
		$fragments = explode('/', $view);
		$tmp = explode('/', $view, 2);
		$tmp = $tmp[1];

		if ((isset($fragments[0])) && (($fragments[0] == 'river') || ($fragments[0] == 'activity'))
			&& (!in_array($tmp, $done))) {

			if (isset($fragments[1])) {
				$f = array();
				for ($n = 1; $n < count($fragments); $n++) {
					$val = sanitise_string($fragments[$n]);
					switch($n) {
						case 1: $key = 'type'; break;
						case 2: $key = 'subtype'; break;
						case 3: $key = 'event'; break;
					}
					$f[$key] = $val;
				}

				// Filter result based on parameters
				$add = true;
				if ($type) {
					if (!in_array($f['type'], $type)) {
						$add = false;
					}
				}
				if (($add) && ($subtype)) {
					if (!in_array($f['subtype'], $subtype)) {
						$add = false;
					}
				}
				if (($add) && ($event)) {
					if (!in_array($f['event'], $event)) {
						$add = false;
					}
				}

				if ($add) {
					$activity_events[] = $f;
				}
			}

			$done[] = $tmp;
		}
	}

	$n = 0;
	foreach ($activity_events as $details) {
		// Get what we're talking about
		if ($details['subtype'] == 'default') {
			$details['subtype'] = '';
		}

		if (($details['type']) && ($details['event'])) {
			if ($n > 0) {
				$obj_query .= " or ";
			}

			$access = "";
			if ($details['type'] != 'relationship') {
				$access = " and " . get_access_sql_suffix('sl');
			}

			$obj_query .= "( sl.object_type='{$details['type']}'
				AND sl.object_subtype='{$details['subtype']}'
				AND sl.event='{$details['event']}' $access )";

			$n++;
		}
	}

	// User
	if ((count($owner_guid)) &&  ($owner_guid[0] != 0)) {
		$user = " and sl.performed_by_guid in (" . implode(',', $owner_guid) . ")";

		if ($owner_relationship) {
			$friendsarray = "";
			if ($friends = elgg_get_entities_from_relationship(array(
				'relationship' => $owner_relationship,
				'relationship_guid' => $owner_guid[0],
				'inverse_relationship' => FALSE,
				'types' => 'user',
				'subtypes' => $subtype,
				'limit' => 9999))
			) {

				$friendsarray = array();
				foreach ($friends as $friend) {
					$friendsarray[] = $friend->getGUID();
				}

				$user = " and sl.performed_by_guid in (" . implode(',', $friendsarray) . ")";
			}
		}
	}

	$query = "SELECT sl.* FROM {$CONFIG->dbprefix}system_log sl
		WHERE 1 $user AND ($obj_query)
		ORDER BY sl.time_created desc limit $offset, $limit";
	return get_data($query);
}

/**
 * Perform standard authentication with a given username and password.
 * Returns an ElggUser object for use with login.
 *
 * @see login
 *
 * @param string $username The username, optionally (for standard logins)
 * @param string $password The password, optionally (for standard logins)
 *
 * @return ElggUser|false The authenticated user object, or false on failure.
 *
 * @deprecated 1.8 Use elgg_authenticate
 */
function authenticate($username, $password) {
	elgg_deprecated_notice('authenticate() has been deprecated for elgg_authenticate()', 1.8);
	$pam = new ElggPAM('user');
	$credentials = array('username' => $username, 'password' => $password);
	$result = $pam->authenticate($credentials);
	if ($result) {
		return get_user_by_username($username);
	}
	return false;
}


/**
 * Get the members of a site.
 *
 * @param int $site_guid Site GUID
 * @param int $limit     User GUID
 * @param int $offset    Offset
 *
 * @return mixed
 * @deprecated 1.8 Use ElggSite::getMembers()
 */
function get_site_members($site_guid, $limit = 10, $offset = 0) {
	elgg_deprecated_notice("get_site_members() deprecated.
		Use ElggSite::getMembers()", 1.8);

	$site = get_entity($site_guid);
	if ($site) {
		return $site->getMembers($limit, $offset);
	}

	return false;
}

/**
 * Display a list of site members
 *
 * @param int  $site_guid The GUID of the site
 * @param int  $limit     The number of members to display on a page
 * @param bool $fullview  Whether or not to display the full view (default: true)
 *
 * @return string A displayable list of members
 * @deprecated 1.8 Use ElggSite::listMembers()
 */
function list_site_members($site_guid, $limit = 10, $fullview = true) {
	elgg_deprecated_notice("list_site_members() deprecated.
		Use ElggSite::listMembers()", 1.8);

	$options = array(
		'limit' => $limit,
		'full_view' => $full_view,
	);

	$site = get_entity($site_guid);
	if ($site) {
		return $site->listMembers($options);
	}

	return '';
}


/**
 * Add a collection to a site.
 *
 * @param int $site_guid       Site GUID
 * @param int $collection_guid Collection GUID
 *
 * @return mixed
 * @deprecated 1.8
 */
function add_site_collection($site_guid, $collection_guid) {
	elgg_deprecated_notice("add_site_collection has been deprecated", 1.8);
	global $CONFIG;

	$site_guid = (int)$site_guid;
	$collection_guid = (int)$collection_guid;

	return add_entity_relationship($collection_guid, "member_of_site", $site_guid);
}

/**
 * Remove a collection from a site.
 *
 * @param int $site_guid       Site GUID
 * @param int $collection_guid Collection GUID
 *
 * @return mixed
 * @deprecated 1.8
 */
function remove_site_collection($site_guid, $collection_guid) {
	elgg_deprecated_notice("remove_site_collection has been deprecated", 1.8);
	$site_guid = (int)$site_guid;
	$collection_guid = (int)$collection_guid;

	return remove_entity_relationship($collection_guid, "member_of_site", $site_guid);
}

/**
 * Get the collections belonging to a site.
 *
 * @param int    $site_guid Site GUID
 * @param string $subtype   Subtype
 * @param int    $limit     Limit
 * @param int    $offset    Offset
 *
 * @return mixed
 * @deprecated 1.8
 */
function get_site_collections($site_guid, $subtype = "", $limit = 10, $offset = 0) {
	elgg_deprecated_notice("get_site_collections has been deprecated", 1.8);
	$site_guid = (int)$site_guid;
	$subtype = sanitise_string($subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;

	// collection isn't a valid type.  This won't work.
	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site',
		'relationship_guid' => $site_guid,
		'inverse_relationship' => TRUE,
		'types' => 'collection',
		'subtypes' => $subtype,
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Get an array of tags with weights for use with the output/tagcloud view.
 *
 * @deprecated 1.8  Use elgg_get_tags().
 *
 * @param int    $threshold      Get the threshold of minimum number of each tags to
 *                               bother with (ie only show tags where there are more
 *                               than $threshold occurances)
 * @param int    $limit          Number of tags to return
 * @param string $metadata_name  Optionally, the name of the field you want to grab for
 * @param string $entity_type    Optionally, the entity type ('object' etc)
 * @param string $entity_subtype The entity subtype, optionally
 * @param int    $owner_guid     The GUID of the tags owner, optionally
 * @param int    $site_guid      Optionally, the site to restrict to (default is the current site)
 * @param int    $start_ts       Optionally specify a start timestamp for tags used to
 *                               generate cloud.
 * @param int    $end_ts         Optionally specify an end timestamp for tags used to generate cloud
 *
 * @return array|false Array of objects with ->tag and ->total values, or false on failure
 */
function get_tags($threshold = 1, $limit = 10, $metadata_name = "", $entity_type = "object",
$entity_subtype = "", $owner_guid = "", $site_guid = -1, $start_ts = "", $end_ts = "") {

	elgg_deprecated_notice('get_tags() has been replaced by elgg_get_tags()', 1.8);

	if (is_array($metadata_name)) {
		return false;
	}

	$options = array();
	if ($metadata_name === '') {
		$options['tag_names'] = array();
	} else {
		$options['tag_names'] = array($metadata_name);
	}

	$options['threshold'] = $threshold;
	$options['limit'] = $limit;

	// rewrite owner_guid to container_guid to emulate old functionality
	$container_guid = $owner_guid;
	if ($container_guid) {
		$options['container_guids'] = $container_guid;
	}

	if ($entity_type) {
		$options['type'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtype'] = $entity_subtype;
	}

	if ($site_guid != -1) {
		$options['site_guids'] = $site_guid;
	}

	if ($end_ts) {
		$options['created_time_upper'] = $end_ts;
	}

	if ($start_ts) {
		$options['created_time_lower'] = $start_ts;
	}

	$r = elgg_get_tags($options);
	return $r;
}

/**
 * Loads and displays a tagcloud given particular criteria.
 *
 * @deprecated 1.8 use elgg_view_tagcloud()
 *
 * @param int    $threshold      Get the threshold of minimum number of each tags
 *                               to bother with (ie only show tags where there are
 *                               more than $threshold occurances)
 * @param int    $limit          Number of tags to return
 * @param string $metadata_name  Optionally, the name of the field you want to grab for
 * @param string $entity_type    Optionally, the entity type ('object' etc)
 * @param string $entity_subtype The entity subtype, optionally
 * @param int    $owner_guid     The GUID of the tags owner, optionally
 * @param int    $site_guid      Optionally, the site to restrict to (default is the current site)
 * @param int    $start_ts       Optionally specify a start timestamp for tags used to
 *                               generate cloud.
 * @param int    $end_ts         Optionally specify an end timestamp for tags used to generate
 *                               cloud.
 *
 * @return string The HTML (or other, depending on view type) of the tagcloud.
 */
function display_tagcloud($threshold = 1, $limit = 10, $metadata_name = "", $entity_type = "object",
$entity_subtype = "", $owner_guid = "", $site_guid = -1, $start_ts = "", $end_ts = "") {

	elgg_deprecated_notice('display_tagcloud() was deprecated by elgg_view_tagcloud()!', 1.8);

	$tags = get_tags($threshold, $limit, $metadata_name, $entity_type,
		$entity_subtype, $owner_guid, $site_guid, $start_ts, $end_ts);

	return elgg_view('output/tagcloud', array(
		'value' => $tags,
		'type' => $entity_type,
		'subtype' => $entity_subtype,
	));
}


/**
 * Obtains a list of objects owned by a user
 *
 * @param int    $user_guid The GUID of the owning user
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $limit     The number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return false|array An array of ElggObjects or false, depending on success
 * @deprecated 1.8 Use elgg_get_entities() instead
 */
function get_user_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0, $timelower = 0, $timeupper = 0) {
	elgg_deprecated_notice("get_user_objects() was deprecated in favor of elgg_get_entities()", 1.8);
	$ntt = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => $subtype,
		'owner_guid' => $user_guid,
		'limit' => $limit,
		'offset' => $offset,
		'container_guid' => $user_guid,
		'created_time_lower' => $timelower,
		'created_time_upper' => $timeupper
	));
	return $ntt;
}

/**
 * Counts the objects (optionally of a particular subtype) owned by a user
 *
 * @param int    $user_guid The GUID of the owning user
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return int The number of objects the user owns (of this subtype)
 * @deprecated 1.8 Use elgg_get_entities() instead
 */
function count_user_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $timelower = 0,
$timeupper = 0) {
	elgg_deprecated_notice("count_user_objects() was deprecated in favor of elgg_get_entities()", 1.8);
	$total = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => $subtype,
		'owner_guid' => $user_guid,
		'count' => TRUE,
		'container_guid' => $user_guid,
		'created_time_lower' => $timelower,
		'created_time_upper' => $timeupper
	));
	return $total;
}

/**
 * Displays a list of user objects of a particular subtype, with navigation.
 *
 * @see elgg_view_entity_list
 *
 * @param int    $user_guid      The GUID of the user
 * @param string $subtype        The object subtype
 * @param int    $limit          The number of entities to display on a page
 * @param bool   $fullview       Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow gallery view (default: true)
 * @param bool   $pagination     Whether to display pagination (default: true)
 * @param int    $timelower      The earliest time the entity can have been created. Default: all
 * @param int    $timeupper      The latest time the entity can have been created. Default: all
 *
 * @return string The list in a form suitable to display
 * @deprecated 1.8 Use elgg_list_entities() instead
 */
function list_user_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$fullview = true, $listtypetoggle = true, $pagination = true, $timelower = 0, $timeupper = 0) {
	elgg_deprecated_notice("list_user_objects() was deprecated in favor of elgg_list_entities()", 1.8);

	$offset = (int) get_input('offset');
	$limit = (int) $limit;
	$count = (int) count_user_objects($user_guid, $subtype, $timelower, $timeupper);
	$entities = get_user_objects($user_guid, $subtype, $limit, $offset, $timelower, $timeupper);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $listtypetoggle,
		$pagination);
}


/**
 * Get user objects by an array of metadata
 *
 * @param int    $user_guid The GUID of the owning user
 * @param string $subtype   Optionally, the subtype of objects
 * @param array  $metadata  An array of metadata
 * @param int    $limit     The number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 *
 * @return false|array An array of ElggObjects or false, depending on success
 * @deprecated 1.8 Use elgg_get_entities_from_metadata() instead
 */
function get_user_objects_by_metadata($user_guid, $subtype = "", $metadata = array(),
$limit = 0, $offset = 0) {
	elgg_deprecated_notice("get_user_objects_by_metadata() was deprecated in favor of elgg_get_entities_from_metadata()", 1.8);
	return get_entities_from_metadata_multi($metadata, "object", $subtype, $user_guid,
		$limit, $offset);
}

/**
 * Set the validation status for a user.
 *
 * @param bool   $status Validated (true) or false
 * @param string $method Optional method to say how a user was validated
 * @return bool
 * @deprecated 1.8
 */
function set_user_validation_status($user_guid, $status, $method = '') {
	elgg_deprecated_notice("set_user_validation_status() is deprecated", 1.8);
	return elgg_set_user_validation_status($user_guid, $status, $method);
}

/**
 * Trigger an event requesting that a user guid be validated somehow - either by email address or some other way.
 *
 * This function invalidates any existing validation value.
 *
 * @param int $user_guid User's GUID
 * @deprecated 1.8
 */
function request_user_validation($user_guid) {
	elgg_deprecated_notice("request_user_validation() is deprecated.
		Plugins should register for the 'register, user' plugin hook", 1.8);
	$user = get_entity($user_guid);

	if (($user) && ($user instanceof ElggUser)) {
		// invalidate any existing validations
		set_user_validation_status($user_guid, false);

		// request validation
		trigger_elgg_event('validate', 'user', $user);
	}
}

/**
 * Register a user settings page with the admin panel.
 * This function extends the view "usersettings/main" with the provided view.
 * This view should provide a description and either a control or a link to.
 *
 * Usage:
 * 	- To add a control to the main admin panel then extend usersettings/main
 *  - To add a control to a new page create a page which renders a view
 *    usersettings/subpage (where subpage is your new page -
 *    nb. some pages already exist that you can extend), extend the main view
 *    to point to it, and add controls to your new view.
 *
 * At the moment this is essentially a wrapper around elgg_extend_view().
 *
 * @param string $new_settings_view The view associated with the control you're adding
 * @param string $view              The view to extend, by default this is 'usersettings/main'.
 * @param int    $priority          Optional priority to govern the appearance in the list.
 *
 * @return bool
 * @deprecated 1.8 Extend one of the views in core/settings
 */
function extend_elgg_settings_page($new_settings_view, $view = 'usersettings/main',
$priority = 500) {
	// see views: /core/settings
	elgg_deprecated_notice("extend_elgg_settings_page has been deprecated. Extend one of the settings views instead", 1.8);

	return elgg_extend_view($view, $new_settings_view, $priority);
}

/**
 * Returns a representation of a full 'page' (which might be an HTML page,
 * RSS file, etc, depending on the current viewtype)
 *
 * @param string $title
 * @param string $body
 * @return string
 *
 * @deprecated 1.8 Use elgg_view_page()
 */
function page_draw($title, $body, $sidebar = "") {
	elgg_deprecated_notice("page_draw() was deprecated in favor of elgg_view_page() in 1.8.", 1.8);

	$vars = array(
		'sidebar' => $sidebar
	);
	echo elgg_view_page($title, $body, 'default', $vars);
}

/**
 * Wrapper function to display search listings.
 *
 * @param string $icon The icon for the listing
 * @param string $info Any information that needs to be displayed.
 *
 * @return string The HTML (etc) representing the listing
 * @deprecated 1.8 use elgg_view_image_block()
 */
function elgg_view_listing($icon, $info) {
	elgg_deprecated_notice('elgg_view_listing deprecated by elgg_view_image_block', 1.8);
	return elgg_view('page/components/image_block', array('image' => $icon, 'body' => $info));
}

/**
 * Return the icon URL for an entity.
 *
 * @tip Can be overridden by registering a plugin hook for entity:icon:url, $entity_type.
 *
 * @internal This is passed an entity rather than a guid to handle non-created entities.
 *
 * @param ElggEntity $entity The entity
 * @param string     $size   Icon size
 *
 * @return string URL to the entity icon.
 * @deprecated 1.8 Use $entity->getIconURL()
 */
function get_entity_icon_url(ElggEntity $entity, $size = 'medium') {
	elgg_deprecated_notice("get_entity_icon_url() deprecated for getIconURL()", 1.8);
	global $CONFIG;

	$size = sanitise_string($size);
	switch (strtolower($size)) {
		case 'master':
			$size = 'master';
			break;

		case 'large' :
			$size = 'large';
			break;

		case 'topbar' :
			$size = 'topbar';
			break;

		case 'tiny' :
			$size = 'tiny';
			break;

		case 'small' :
			$size = 'small';
			break;

		case 'medium' :
		default:
			$size = 'medium';
	}

	$url = false;

	$viewtype = elgg_get_viewtype();

	// Step one, see if anyone knows how to render this in the current view
	$params = array('entity' => $entity, 'viewtype' => $viewtype, 'size' => $size);
	$url = elgg_trigger_plugin_hook('entity:icon:url', $entity->getType(), $params, $url);

	// Fail, so use default
	if (!$url) {
		$type = $entity->getType();
		$subtype = $entity->getSubtype();

		if (!empty($subtype)) {
			$overrideurl = elgg_view("icon/{$type}/{$subtype}/{$size}", array('entity' => $entity));
			if (!empty($overrideurl)) {
				return $overrideurl;
			}
		}

		$overrideurl = elgg_view("icon/{$type}/default/{$size}", array('entity' => $entity));
		if (!empty($overrideurl)) {
			return $overrideurl;
		}

		$url = "_graphics/icons/default/$size.png";
	}

	return elgg_normalize_url($url);
}

/**
 * Return the current logged in user, or NULL if no user is logged in.
 *
 * If no user can be found in the current session, a plugin
 * hook - 'session:get' 'user' to give plugin authors another
 * way to provide user details to the ACL system without touching the session.
 *
 * @deprecated 1.8 Use elgg_get_logged_in_user_entity()
 * @return ElggUser|NULL
 */
function get_loggedin_user() {
	elgg_deprecated_notice('get_loggedin_user() is deprecated by elgg_get_logged_in_user_entity()', 1.8);
	return elgg_get_logged_in_user_entity();
}

/**
 * Return the current logged in user by id.
 *
 * @deprecated 1.8 Use elgg_get_logged_in_user_guid()
 * @see elgg_get_logged_in_user_entity()
 * @return int
 */
function get_loggedin_userid() {
	elgg_deprecated_notice('get_loggedin_userid() is deprecated by elgg_get_logged_in_user_guid()', 1.8);
	return elgg_get_logged_in_user_guid();
}


/**
 * Returns whether or not the user is currently logged in
 *
 * @deprecated 1.8 Use elgg_is_logged_in();
 * @return bool
 */
function isloggedin() {
	elgg_deprecated_notice('isloggedin() is deprecated by elgg_is_logged_in()', 1.8);
	return elgg_is_logged_in();
}

/**
 * Returns whether or not the user is currently logged in and that they are an admin user.
 *
 * @deprecated 1.8 Use elgg_is_admin_logged_in()
 * @return bool
 */
function isadminloggedin() {
	elgg_deprecated_notice('isadminloggedin() is deprecated by elgg_is_admin_logged_in()', 1.8);
	return elgg_is_admin_logged_in();
}


/**
 * Loads plugins
 *
 * @deprecated 1.8 Use elgg_load_plugins()
 *
 * @return bool
 */
function load_plugins() {
	elgg_deprecated_notice('load_plugins() is deprecated by elgg_load_plugins()', 1.8);
	return elgg_load_plugins();
}

/**
 * Find the plugin settings for a user.
 *
 * @param string $plugin_id Plugin name.
 * @param int    $user_guid The guid who's settings to retrieve.
 *
 * @deprecated 1.8 Use elgg_get_all_plugin_user_settings() or ElggPlugin->getAllUserSettings()
 * @return StdClass Object with all user settings.
 */
function find_plugin_usersettings($plugin_id = null, $user_guid = 0) {
	elgg_deprecated_notice('find_plugin_usersettings() is deprecated by elgg_get_all_plugin_user_settings()', 1.8);
	return elgg_get_all_plugin_user_settings($user_guid, $plugin_id, true);
}

/**
 * Set a user specific setting for a plugin.
 *
 * @param string $name      The name - note, can't be "title".
 * @param mixed  $value     The value.
 * @param int    $user_guid Optional user.
 * @param string $plugin_id Optional plugin name, if not specified then it
 *                          is detected from where you are calling from.
 *
 * @return bool
 * @deprecated 1.8 Use elgg_set_plugin_user_setting() or ElggPlugin->setUserSetting()
 */
function set_plugin_usersetting($name, $value, $user_guid = 0, $plugin_id = "") {
	elgg_deprecated_notice('find_plugin_usersettings() is deprecated by elgg_get_all_plugin_user_settings()', 1.8);
	return elgg_set_plugin_user_setting($name, $value, $user_guid, $plugin_id);
}

/**
 * Clears a user-specific plugin setting
 *
 * @param str $name      Name of the plugin setting
 * @param int $user_guid Defaults to logged in user
 * @param str $plugin_id Defaults to contextual plugin name
 *
 * @deprecated 1.8 Use elgg_unset_plugin_user_setting or ElggPlugin->unsetUserSetting().
 * @return bool Success
 */
function clear_plugin_usersetting($name, $user_guid = 0, $plugin_id = '') {
	elgg_deprecated_notice('clear_plugin_usersetting() is deprecated by elgg_unset_plugin_usersetting()', 1.8);
	return elgg_unset_plugin_user_setting($name, $user_guid, $plugin_id);
}

/**
 * Get a user specific setting for a plugin.
 *
 * @param string $name      The name.
 * @param int    $user_guid Guid of owning user
 * @param string $plugin_id Optional plugin name, if not specified
 *                          it is detected from where you are calling.
 *
 * @deprecated 1.8 Use elgg_get_plugin_user_setting() or ElggPlugin->getUserSetting()
 * @return mixed
 */
function get_plugin_usersetting($name, $user_guid = 0, $plugin_id = "") {
	elgg_deprecated_notice('get_plugin_usersetting() is deprecated by elgg_get_plugin_user_setting()', 1.8);
	return elgg_get_plugin_user_setting($name, $user_guid, $plugin_id);
}

/**
 * Set a setting for a plugin.
 *
 * @param string $name      The name - note, can't be "title".
 * @param mixed  $value     The value.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @deprecated 1.8 Use elgg_set_plugin_setting() or ElggPlugin->setSetting()
 * @return int|false
 */
function set_plugin_setting($name, $value, $plugin_id = null) {
	elgg_deprecated_notice('set_plugin_setting() is deprecated by elgg_set_plugin_setting()', 1.8);
	return elgg_set_plugin_setting($name, $value, $plugin_id);
}

/**
 * Get setting for a plugin.
 *
 * @param string $name      The name.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @deprecated 1.8 Use elgg_get_plugin_setting() or ElggPlugin->getSetting()
 * @return mixed
 */
function get_plugin_setting($name, $plugin_id = "") {
	elgg_deprecated_notice('get_plugin_setting() is deprecated by elgg_get_plugin_setting()', 1.8);
	return elgg_get_plugin_setting($name, $plugin_id);
}

/**
 * Clear a plugin setting.
 *
 * @param string $name      The name.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @deprecated 1.8 Use elgg_unset_plugin_setting() or ElggPlugin->unsetSetting()
 * @return bool
 */
function clear_plugin_setting($name, $plugin_id = "") {
	elgg_deprecated_notice('clear_plugin_setting() is deprecated by elgg_unset_plugin_setting()', 1.8);
	return elgg_unset_plugin_setting($name, $plugin_id);
}

/**
 * Unsets all plugin settings for a plugin.
 *
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return bool
 * @deprecated 1.8 Use elgg_unset_all_plugin_settings() or ElggPlugin->unsetAllSettings()
 * @since 1.7.0
 */
function clear_all_plugin_settings($plugin_id = "") {
	elgg_deprecated_notice('clear_all_plugin_settings() is deprecated by elgg_unset_all_plugin_setting()', 1.8);
	return elgg_unset_all_plugin_settings($plugin_id);
}


/**
 * Get a list of annotations for a given object/user/annotation type.
 *
 * @param int|array $entity_guid       GUID to return annotations of (falsey for any)
 * @param string    $entity_type       Type of entity
 * @param string    $entity_subtype    Subtype of entity
 * @param string    $name              Name of annotation
 * @param mixed     $value             Value of annotation
 * @param int|array $owner_guid        Owner(s) of annotation
 * @param int       $limit             Limit
 * @param int       $offset            Offset
 * @param string    $order_by          Order annotations by SQL
 * @param int       $timelower         Lower time limit
 * @param int       $timeupper         Upper time limit
 * @param int       $entity_owner_guid Owner guid for the entity
 *
 * @return array
 * @deprecated 1.8
 */
function get_annotations($entity_guid = 0, $entity_type = "", $entity_subtype = "", $name = "",
$value = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "asc", $timelower = 0,
$timeupper = 0, $entity_owner_guid = 0) {

	elgg_deprecated_notice('get_annotations() is deprecated by elgg_get_annotations()', 1.8);
	$options = array();

	if ($entity_guid) {
		$options['guid'] = $entity_guid;
	}

	if ($entity_type) {
		$options['type'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtype'] = $entity_subtype;
	}

	if ($name) {
		$options['annotation_name'] = $name;
	}

	if ($value) {
		$options['annotation_value'] = $value;
	}

	if ($owner_guid) {
		$options['annotation_owner_guid'] = $owner_guid;
	}

	$options['limit'] = $limit;
	$options['offset'] = $offset;

	if ($order_by == 'desc') {
		$options['order_by'] = 'n_table.time_created desc';
	}

	if ($timelower) {
		$options['annotation_time_lower'] = $timelower;
	}

	if ($timeupper) {
		$options['annotation_time_upper'] = $timeupper;
	}

	if ($entity_owner_guid) {
		$options['owner_guid'] = $entity_owner_guid;
	}

	return elgg_get_annotations($options);
}


/**
 * Returns a human-readable list of annotations on a particular entity.
 *
 * @param int        $entity_guid The entity GUID
 * @param string     $name        The name of the kind of annotation
 * @param int        $limit       The number of annotations to display at once
 * @param true|false $asc         Display annotations in ascending order. (Default: true)
 *
 * @return string HTML (etc) version of the annotation list
 * @deprecated 1.8
 */
function list_annotations($entity_guid, $name = "", $limit = 25, $asc = true) {
	elgg_deprecated_notice('list_annotations() is deprecated by elgg_list_annotations()', 1.8);

	if ($asc) {
		$asc = "asc";
	} else {
		$asc = "desc";
	}

	$options = array(
		'guid' => $entity_guid,
		'limit' => $limit,
		'order_by' => "n_table.time_created $asc"
	);

	return elgg_list_annotations($options);
}

/**
 * Helper function to deprecate annotation calculation functions. Don't use.
 *
 * @param unknown_type $entity_guid
 * @param unknown_type $entity_type
 * @param unknown_type $entity_subtype
 * @param unknown_type $name
 * @param unknown_type $value
 * @param unknown_type $value_type
 * @param unknown_type $owner_guid
 * @param unknown_type $timelower
 * @param unknown_type $timeupper
 * @param unknown_type $calculation
 * @deprecated 1.8
 */
function elgg_deprecated_annotation_calculation($entity_guid = 0, $entity_type = "", $entity_subtype = "",
$name = "", $value = "", $value_type = "", $owner_guid = 0, $timelower = 0,
$timeupper = 0, $calculation = '') {

	$options = array('annotation_calculation' => $calculation);

	if ($entity_guid) {
		$options['guid'] = $entity_guid;
	}

	if ($entity_type) {
		$options['type'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtype'] = $entity_subtype;
	}

	if ($name) {
		$options['annotation_name'] = $name;
	}

	if ($value) {
		$options['annotation_value'] = $value;
	}

	if ($owner_guid) {
		$options['annotation_owner_guid'] = $owner_guid;
	}

	if ($order_by == 'desc') {
		$options['order_by'] = 'n_table.time_created desc';
	}

	if ($timelower) {
		$options['annotation_time_lower'] = $timelower;
	}

	if ($timeupper) {
		$options['annotation_time_upper'] = $timeupper;
	}

	return elgg_get_annotations($options);
}

/**
 * Count the number of annotations based on search parameters
 *
 * @param int    $entity_guid    Guid of Entity
 * @param string $entity_type    Type of Entity
 * @param string $entity_subtype Subtype of Entity
 * @param string $name           Name of annotation
 * @param string $value          Value of annotation
 * @param string $value_type     Type of value
 * @param int    $owner_guid     GUID of owner of annotation
 * @param int    $timelower      Lower time limit
 * @param int    $timeupper      Upper time limit
 *
 * @deprecated 1.8 Use elgg_get_annotations() and pass 'count' => true
 * @return int
 */
function count_annotations($entity_guid = 0, $entity_type = "", $entity_subtype = "",
$name = "", $value = "", $value_type = "", $owner_guid = 0, $timelower = 0,
$timeupper = 0) {
	elgg_deprecated_notice('count_annotations() is deprecated by elgg_get_annotations() and passing "count" => true', 1.8);
	return elgg_deprecated_annotation_calculation($entity_guid, $entity_type, $entity_subtype,
			$name, $value, $value_type, $owner_guid, $timelower, $timeupper, 'count');
}

/**
 * Return the sum of a given integer annotation.
 *
 * @param int    $entity_guid    Guid of Entity
 * @param string $entity_type    Type of Entity
 * @param string $entity_subtype Subtype of Entity
 * @param string $name           Name of annotation
 * @param string $value          Value of annotation
 * @param string $value_type     Type of value
 * @param int    $owner_guid     GUID of owner of annotation
 *
 * @deprecated 1.8 Use elgg_get_annotations() and pass 'annotation_calculation' => 'sum'
 * @return int
 */
function get_annotations_sum($entity_guid, $entity_type = "", $entity_subtype = "", $name = "",
$value = "", $value_type = "", $owner_guid = 0) {
	elgg_deprecated_notice('get_annotations_sum() is deprecated by elgg_get_annotations() and passing "annotation_calculation" => "sum"', 1.8);

	return elgg_deprecated_annotation_calculation($entity_guid, $entity_type, $entity_subtype,
			$name, $value, $value_type, $owner_guid, $timelower, $timeupper, 'sum');
}

/**
 * Return the max of a given integer annotation.
 *
 * @param int    $entity_guid    Guid of Entity
 * @param string $entity_type    Type of Entity
 * @param string $entity_subtype Subtype of Entity
 * @param string $name           Name of annotation
 * @param string $value          Value of annotation
 * @param string $value_type     Type of value
 * @param int    $owner_guid     GUID of owner of annotation
 *
 * @deprecated 1.8 Use elgg_get_annotations() and pass 'annotation_calculation' => 'max'
 * @return int
 */
function get_annotations_max($entity_guid, $entity_type = "", $entity_subtype = "", $name = "",
$value = "", $value_type = "", $owner_guid = 0) {
	elgg_deprecated_notice('get_annotations_max() is deprecated by elgg_get_annotations() and passing "annotation_calculation" => "max"', 1.8);

	return elgg_deprecated_annotation_calculation($entity_guid, $entity_type, $entity_subtype,
			$name, $value, $value_type, $owner_guid, $timelower, $timeupper, 'max');
}


/**
 * Return the minumum of a given integer annotation.
 *
 * @param int    $entity_guid    Guid of Entity
 * @param string $entity_type    Type of Entity
 * @param string $entity_subtype Subtype of Entity
 * @param string $name           Name of annotation
 * @param string $value          Value of annotation
 * @param string $value_type     Type of value
 * @param int    $owner_guid     GUID of owner of annotation
 *
 * @deprecated 1.8 Use elgg_get_annotations() and pass 'annotation_calculation' => 'min'
 * @return int
 */
function get_annotations_min($entity_guid, $entity_type = "", $entity_subtype = "", $name = "",
$value = "", $value_type = "", $owner_guid = 0) {
	elgg_deprecated_notice('get_annotations_min() is deprecated by elgg_get_annotations() and passing "annotation_calculation" => "min"', 1.8);

	return elgg_deprecated_annotation_calculation($entity_guid, $entity_type, $entity_subtype,
			$name, $value, $value_type, $owner_guid, $timelower, $timeupper, 'min');
}


/**
 * Return the average of a given integer annotation.
 *
 * @param int    $entity_guid    Guid of Entity
 * @param string $entity_type    Type of Entity
 * @param string $entity_subtype Subtype of Entity
 * @param string $name           Name of annotation
 * @param string $value          Value of annotation
 * @param string $value_type     Type of value
 * @param int    $owner_guid     GUID of owner of annotation
 *
 * @deprecated 1.8 Use elgg_get_annotations() and pass 'annotation_calculation' => 'min'
 *
 * @return int
 */
function get_annotations_avg($entity_guid, $entity_type = "", $entity_subtype = "", $name = "",
$value = "", $value_type = "", $owner_guid = 0) {
	elgg_deprecated_notice('get_annotations_avg() is deprecated by elgg_get_annotations() and passing "annotation_calculation" => "avg"', 1.8);

	return elgg_deprecated_annotation_calculation($entity_guid, $entity_type, $entity_subtype,
			$name, $value, $value_type, $owner_guid, $timelower, $timeupper, 'avg');
}


/**
 * Perform a mathmatical calculation on integer annotations.
 *
 * @param string $sum            What sort of calculation to perform
 * @param int    $entity_guid    Guid of Entity
 * @param string $entity_type    Type of Entity
 * @param string $entity_subtype Subtype of Entity
 * @param string $name           Name of annotation
 * @param string $value          Value of annotation
 * @param string $value_type     Type of value
 * @param int    $owner_guid     GUID of owner of annotation
 * @param int    $timelower      Lower time limit
 * @param int    $timeupper      Upper time limit
 *
 * @return int
 * @deprecated 1.8
 */
function get_annotations_calculate_x($sum = "avg", $entity_guid, $entity_type = "",
$entity_subtype = "", $name = "", $value = "", $value_type = "", $owner_guid = 0,
$timelower = 0, $timeupper = 0) {
	elgg_deprecated_notice('get_annotations_calculate_x() is deprecated by elgg_get_annotations() and passing "annotation_calculation" => "calculation"', 1.8);

	return elgg_deprecated_annotation_calculation($entity_guid, $entity_type, $entity_subtype,
			$name, $value, $value_type, $owner_guid, $timelower, $timeupper, $sum);
}


/**
 * Lists entities by the totals of a particular kind of annotation AND
 * the value of a piece of metadata
 *
 * @param string  $entity_type    Type of entity.
 * @param string  $entity_subtype Subtype of entity.
 * @param string  $name           Name of annotation.
 * @param string  $mdname         Metadata name
 * @param string  $mdvalue        Metadata value
 * @param int     $limit          Maximum number of results to return.
 * @param int     $owner_guid     Owner.
 * @param int     $group_guid     Group container. Currently only supported if entity_type is object
 * @param boolean $asc            Whether to list in ascending or descending order (default: desc)
 * @param boolean $fullview       Whether to display the entities in full
 * @param boolean $listtypetoggle Can the 'gallery' view can be displayed (default: no)
 * @param boolean $pagination     Display pagination
 * @param string  $orderdir       'desc' or 'asc'
 *
 * @deprecated 1.8 Use elgg_list_entities_from_annotation_calculation().
 *
 * @return string Formatted entity list
 */
function list_entities_from_annotation_count_by_metadata($entity_type = "", $entity_subtype = "",
$name = "", $mdname = '', $mdvalue = '', $limit = 10, $owner_guid = 0, $group_guid = 0,
$asc = false, $fullview = true, $listtypetoggle = false, $pagination = true, $orderdir = 'desc') {

	$msg = 'list_entities_from_annotation_count_by_metadata() is deprecated by elgg_list_entities_from_annotation_calculation().';

	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	$options['calculation'] = 'sum';

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($mdname) {
		$options['metadata_name'] = $mdname;
	}

	if ($mdvalue) {
		$options['metadata_value'] = $mdvalue;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	$options['full_view'] = $fullview;

	$options['list_type_toggle'] = $listtypetoggle;

	$options['pagination'] = $pagination;

	$options['limit'] = $limit;

	$options['order_by'] = "annotation_calculation $orderdir";

	return elgg_get_entities_from_annotation_calculation($options);
}

/**
 * Set an alternative base location for a view (as opposed to the default of $CONFIG->viewpath)
 *
 * @param string $view The name of the view
 * @param string $location The base location path
 *
 * @deprecated 1.8 Use elgg_set_view_location()
 */
function set_view_location($view, $location, $viewtype = '') {
	elgg_deprecated_notice("set_view_location() was deprecated by elgg_set_view_location()", 1.8);
	return elgg_set_view_location($view, $location, $viewtype);
}

/**
 * Sets the URL handler for a particular entity type and subtype
 *
 * @param string $function_name The function to register
 * @param string $entity_type The entity type
 * @param string $entity_subtype The entity subtype
 * @return true|false Depending on success
 *
 * @deprecated 1.8 Use elgg_register_entity_url_handler()
 */
function register_entity_url_handler($function_name, $entity_type = "all", $entity_subtype = "all") {
	elgg_deprecated_notice("register_entity_url_handler() was deprecated by elgg_register_entity_url_handler()", 1.8);
	return elgg_register_entity_url_handler($entity_type, $entity_subtype, $function_name);
}


/**
 * Get the metadata where the entities they are referring to match a given criteria.
 *
 * @param mixed  $meta_name      Metadata name
 * @param mixed  $meta_value     Metadata value
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site GUID. 0 for current, -1 for any
 *
 * @return mixed
 * @deprecated 1.8 Use elgg_get_metadata()
 */
function find_metadata($meta_name = "", $meta_value = "", $entity_type = "", $entity_subtype = "",
	$limit = 10, $offset = 0, $order_by = "", $site_guid = 0) {

	elgg_deprecated_notice('get_metadata() is deprecated by elgg_get_metadata()', 1.8);

	$options = array();

	if ($meta_name) {
		$options['annotation_name'] = $meta_name;
	}

	if ($meta_value) {
		$options['annotation_value'] = $meta_value;
	}

	if ($entity_type) {
		$options['type'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtype'] = $entity_subtype;
	}

	$options['limit'] = $limit;
	$options['offset'] = $offset;

	if ($order_by == 'desc') {
		$options['order_by'] = 'n_table.time_created desc';
	}

	if ($site_guid) {
		$options['site_guid'] = $site_guid;
	}

	return elgg_get_metadata($options);
}

/**
 * Get metadata objects by name.
 *
 * @param int    $entity_guid Entity GUID
 * @param string $meta_name   Metadata name
 *
 * @return mixed ElggMetadata object, an array of ElggMetadata or false.
 * @deprecated 1.8 Use elgg_get_metadata()
 */
function get_metadata_byname($entity_guid, $meta_name) {
	elgg_deprecated_notice('get_metadata_byname() is deprecated by elgg_get_metadata()', 1.8);

	if (!$entity_guid || !$meta_name) {
		return false;
	}

	$options = array(
		'guid' => $entity_guid,
		'metadata_name' => $meta_name,
		'limit' => 0
	);

	$md = elgg_get_metadata($options);

	if ($md && count($md) == 1) {
		return $md[0];
	}

	return $md;
}

/**
 * Return all the metadata for a given GUID.
 *
 * @param int $entity_guid Entity GUID
 *
 * @return mixed
 * @deprecated 1.8 Use elgg_get_metadata()
 */
function get_metadata_for_entity($entity_guid) {
	elgg_deprecated_notice('get_metadata_for_entity() is deprecated by elgg_get_metadata()', 1.8);

	if (!$entity_guid) {
		return false;
	}

	$options = array(
		'guid' => $entity_guid,
		'limit' => 0
	);

	return elgg_get_metadata($options);
}

/**
 * Get a specific metadata object.
 *
 * @param int $id The id of the metadata being retrieved.
 *
 * @return mixed False on failure or ElggMetadata
 * @deprecated 1.8 Use elgg_get_metadata_from_id()
 */
function get_metadata($id) {
	elgg_deprecated_notice('get_metadata() is deprecated by elgg_get_metadata_from_id()', 1.8);
	return elgg_get_metadata_from_id($id);
}

/**
 * Clear all the metadata for a given entity, assuming you have access to that entity.
 *
 * @param int $guid Entity GUID
 *
 * @return bool
 * @deprecated 1.8 Use elgg_delete_metadata()
 */
function clear_metadata($guid) {
	elgg_deprecated_notice('clear_metadata() is deprecated by elgg_delete_metadata()', 1.8);
	if (!$guid) {
		return false;
	}
	return elgg_delete_metadata(array('guid' => $guid, 'limit' => 0));
}

/**
 * Clear all metadata belonging to a given owner_guid
 *
 * @param int $owner_guid The owner
 *
 * @return bool
 * @deprecated 1.8 Use elgg_delete_metadata()
 */
function clear_metadata_by_owner($owner_guid) {
	elgg_deprecated_notice('clear_metadata() is deprecated by elgg_delete_metadata()', 1.8);
	if (!$owner_guid) {
		return false;
	}
	return elgg_delete_metadata(array('metadata_owner_guid' => $owner_guid, 'limit' => 0));
}

/**
 * Delete a piece of metadata, where the current user has access.
 *
 * @param int $id The id of metadata to delete.
 *
 * @return bool
 * @deprecated 1.8 Use elgg_delete_metadata()
 */
function delete_metadata($id) {
	elgg_deprecated_notice('delete_metadata() is deprecated by elgg_delete_metadata()', 1.8);
	if (!$id) {
		return false;
	}
	return elgg_delete_metadata(array('metadata_id' => $id));
}

/**
 * Removes metadata on an entity with a particular name, optionally with a given value.
 *
 * @param int    $guid  The entity GUID
 * @param string $name  The name of the metadata
 * @param string $value The value of the metadata (useful to remove a single item of a set)
 *
 * @return bool Depending on success
 * @deprecated 1.8 Use elgg_delete_metadata()
 */
function remove_metadata($guid, $name, $value = "") {
	elgg_deprecated_notice('delete_metadata() is deprecated by elgg_delete_metadata()', 1.8);

	// prevent them from deleting everything
	if (!$guid) {
		return false;
	}

	$options = array(
		'guid' => $guid,
		'metadata_name' => $name,
		'limit' => 0
	);

	if ($value) {
		$options['metadata_value'] = $value;
	}

	return elgg_delete_metadata($options);
}

/**
 * Get a specific annotation.
 *
 * @param int $annotation_id Annotation ID
 *
 * @return ElggAnnotation
 * @deprecated 1.8 Use elgg_get_annotation_from_id()
 */
function get_annotation($annotation_id) {
	elgg_deprecated_notice('get_annotation() is deprecated by elgg_get_annotation_from_id()', 1.8);
	return elgg_get_annotation_from_id($annotation_id);
}

/**
 * Delete a given annotation.
 *
 * @param int $id The annotation id
 *
 * @return bool
 * @deprecated 1.8 Use elgg_delete_annotations()
 */
function delete_annotation($id) {
	elgg_deprecated_notice('delete_annotation() is deprecated by elgg_delete_annotations()', 1.8);
	if (!$id) {
		return false;
	}
	return elgg_delete_annotations(array('annotation_id' => $annotation_id));
}

/**
 * Clear all the annotations for a given entity, assuming you have access to that metadata.
 *
 * @param int    $guid The entity guid
 * @param string $name The name of the annotation to delete.
 *
 * @return int Number of annotations deleted or false if an error
 * @deprecated 1.8 Use elgg_delete_annotations()
 */
function clear_annotations($guid, $name = "") {
	elgg_deprecated_notice('clear_annotations() is deprecated by elgg_delete_annotations()', 1.8);

	if (!$guid) {
		return false;
	}

	$options = array(
		'guid' => $guid,
		'limit' => 0
	);

	if ($name) {
		$options['annotation_name'] = $name;
	}

	return elgg_delete_annotations($options);
}

/**
 * Clear all annotations belonging to a given owner_guid
 *
 * @param int $owner_guid The owner
 *
 * @return int Number of annotations deleted
 * @deprecated 1.8 Use elgg_delete_annotations()
 */
function clear_annotations_by_owner($owner_guid) {
	elgg_deprecated_notice('clear_annotations_by_owner() is deprecated by elgg_delete_annotations()', 1.8);

	if (!$owner_guid) {
		return false;
	}

	$options = array(
		'annotation_owner_guid' => $guid,
		'limit' => 0
	);

	return elgg_delete_annotations($options);
}

/**
 * Registers a page handler for a particular identifier
 *
 * For example, you can register a function called 'blog_page_handler' for handler type 'blog'
 * Now for all URLs of type http://yoururl/pg/blog/*, the blog_page_handler() function will be called.
 * The part of the URL marked with * above will be exploded on '/' characters and passed as an
 * array to that function.
 * For example, the URL http://yoururl/blog/username/friends/ would result in the call:
 * blog_page_handler(array('username','friends'), blog);
 *
 * Page handler functions should return true or the default page handler will be called.
 *
 * A request to register a page handler with the same identifier as previously registered
 * handler will replace the previous one.
 *
 * The context is set to the page handler identifier before the registered
 * page handler function is called. For the above example, the context is set to 'blog'.
 *
 * @param string $handler The page type to handle
 * @param string $function Your function name
 * @return true|false Depending on success
 *
 * @deprecated 1.8 Use {@link elgg_register_page_handler()}
 */
function register_page_handler($handler, $function){
	elgg_deprecated_notice("register_page_handler() was deprecated by elgg_register_page_handler()", 1.8);
	return elgg_register_page_handler($handler, $function);
}

/**
 * Unregister a page handler for an identifier
 *
 * Note: to replace a page handler, call register_page_handler()
 *
 * @param string $handler The page type identifier
 * @since 1.7.2
 * 
 * @deprecated 1.8 Use {@link elgg_unregister_page_handler()}
 */
function unregister_page_handler($handler) {
	elgg_deprecated_notice("unregister_page_handler() was deprecated by elgg_unregister_page_handler()", 1.8);
	return elgg_unregister_page_handler($handler);
}

/**
 * Register an annotation url handler.
 *
 * @param string $function_name The function.
 * @param string $extender_name The name, default 'all'.
 *
 * @deprecated 1.8 Use {@link elgg_register_annotation_url_handler()}
 */
function register_annotation_url_handler($function, $extender_name) {
	elgg_deprecated_notice("register_annotation_url_handler() was deprecated by elgg_register_annotation_url_handler()", 1.8);
	return elgg_register_annotation_url_handler($extender_name, $function);
}

/**
 * Sets the URL handler for a particular extender type and name.
 * It is recommended that you do not call this directly, instead use one of the wrapper functions in the
 * subtype files.
 *
 * @param string $function_name The function to register
 * @param string $extender_type Extender type
 * @param string $extender_name The name of the extender
 * @return true|false Depending on success
 *
 * @deprecated 1.8 Use {@link elgg_register_extender_url_handler()}
 */
function register_extender_url_handler($function, $type = "all", $name = "all") {
	elgg_deprecated_notice("register_extender_url_handler() was deprecated by elgg_register_extender_url_handler()", 1.8);
	return elgg_register_extender_url_handler($type, $name, $function);
}

/**
 * Registers and entity type and subtype to return in search and other places.
 * A description in the elgg_echo languages file of the form item:type:subtype
 * is also expected.
 *
 * @param string $type The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 * @return true|false Depending on success
 * 
 * @deprecated 1.8 Use {@link elgg_register_entity_type()}
 */
function register_entity_type($type, $subtype = null) {
	elgg_deprecated_notice("register_entity_type() was deprecated by elgg_register_entity_type()", 1.8);
	return elgg_register_entity_type($type, $subtype);
}

/**
 * Register a metadata url handler.
 *
 * @param string $function_name The function.
 * @param string $extender_name The name, default 'all'.
 * 
 * @deprecated 1.8 Use {@link elgg_register_metadata_url_handler()}
 */
function register_metadata_url_handler($function, $extender_name = "all") {
	return elgg_register_metadata_url_handler($extender_name, $function);
}

/**
 * Sets the URL handler for a particular relationship type
 *
 * @param string $function_name The function to register
 * @param string $relationship_type The relationship type.
 * @return true|false Depending on success
 * 
 * @deprecated 1.8 Use {@link elgg_register_relationship_url_handler()}
 */
function register_relationship_url_handler($function_name, $relationship_type = "all") {
	elgg_deprecated_notice("register_relationship_url_handler() was deprecated by elgg_register_relationship_url_handler()", 1.8);
	return elgg_register_relationship_url_handler($relationship_type, $function_name);
}

/**
 * Registers a view to be simply cached
 *
 * Views cached in this manner must take no parameters and be login agnostic -
 * that is to say, they look the same no matter who is logged in (or logged out).
 *
 * CSS and the basic jS views are automatically cached like this.
 *
 * @param string $viewname View name
 *
 * @deprecated 1.8 Use {@link elgg_register_simplecache_view()}
 */
function elgg_view_register_simplecache($viewname) {
	elgg_deprecated_notice("elgg_view_register_simplecache() was deprecated by elgg_register_simplecache_view()", 1.8);
	return elgg_register_simplecache_view($viewname);
}

/**
 * Regenerates the simple cache.
 *
 * @param string $viewtype Optional viewtype to regenerate
 * @see elgg_view_register_simplecache()
 *
 * @deprecated 1.8 Use {@link elgg_regenerate_simplecache()}
 */
function elgg_view_regenerate_simplecache($viewtype = NULL) {
	elgg_deprecated_notice("elgg_view_regenerate_simplecache() was deprecated by elgg_regenerate_simplecache()", 1.8);
	return elgg_regenerate_simplecache($viewtype);
}

/**
 * Enables the simple cache.
 *
 * @see elgg_view_register_simplecache()
 * 
 * @deprecated 1.8 Use {@link elgg_enable_simplecache()}
 */
function elgg_view_enable_simplecache() {
	elgg_deprecated_notice("elgg_view_enable_simplecache() was deprecated by elgg_enable_simplecache()", 1.8);
	return elgg_enable_simplecache();
}

/**
 * Disables the simple cache.
 *
 * @see elgg_view_register_simplecache()
 * 
 * @deprecated 1.8 Use {@link elgg_disable_simplecache()}
 */
function elgg_view_disable_simplecache() {
	elgg_deprecated_notice("elgg_view_disable_simplecache() was deprecated by elgg_disable_simplecache()", 1.8);
	return elgg_disable_simplecache();
}

// these were internal functions that perhaps can be removed rather than deprecated
/**
 * @deprecated 1.8
 */
function is_db_installed() {
	elgg_deprecated_notice('is_db_installed() has been deprecated', 1.8);
	return true;
}

/**
 * @deprecated 1.8
 */
function is_installed() {
	elgg_deprecated_notice('is_installed() has been deprecated', 1.8);
	return true;
}

/**
 * Attempt to authenticate.
 * This function will process all registered PAM handlers or stop when the first
 * handler fails. A handler fails by either returning false or throwing an
 * exception. The advantage of throwing an exception is that it returns a message
 * through the global $_PAM_HANDLERS_MSG which can be used in communication with
 * a user. The order that handlers are processed is determined by the order that
 * they were registered.
 *
 * If $credentials are provided the PAM handler should authenticate using the
 * provided credentials, if not then credentials should be prompted for or
 * otherwise retrieved (eg from the HTTP header or $_SESSION).
 *
 * @param mixed $credentials Mixed PAM handler specific credentials (e.g. username, password)
 * @param string $policy - the policy type, default is "user"
 * @return bool true if authenticated, false if not.
 *
 * @deprecated 1.8 See {@link ElggPAM}
 */
function pam_authenticate($credentials = NULL, $policy = "user") {
	elgg_deprecated_notice('pam_authenticate has been deprecated for ElggPAM', 1.8);
	global $_PAM_HANDLERS, $_PAM_HANDLERS_MSG;

	$_PAM_HANDLERS_MSG = array();

	$authenticated = false;

	foreach ($_PAM_HANDLERS[$policy] as $k => $v) {
		$handler = $v->handler;
		$importance = $v->importance;

		try {
			// Execute the handler
			if ($handler($credentials)) {
				// Explicitly returned true
				$_PAM_HANDLERS_MSG[$k] = "Authenticated!";

				$authenticated = true;
			} else {
				$_PAM_HANDLERS_MSG[$k] = "Not Authenticated.";

				// If this is required then abort.
				if ($importance == 'required') {
					return false;
				}
			}
		} catch (Exception $e) {
			$_PAM_HANDLERS_MSG[$k] = "$e";

			// If this is required then abort.
			if ($importance == 'required') {
				return false;
			}
		}
	}

	return $authenticated;
}


/**
 * When given a widget entity and a new requested location, saves the new location
 * and also provides a sensible ordering for all widgets in that column
 *
 * @param ElggObject $widget The widget entity
 * @param int        $order  The order within the column
 * @param int        $column The column (1, 2 or 3)
 *
 * @return bool Depending on success
 * @deprecated 1.8 use ElggWidget::move()
 */
function save_widget_location(ElggObject $widget, $order, $column) {
	elgg_deprecated_notice('save_widget_location() is deprecated', 1.8);
	if ($widget instanceof ElggObject) {
		if ($widget->subtype == "widget") {
			// If you can't move the widget, don't save a new location
			if (!$widget->draggable) {
				return false;
			}

			// Sanitise the column value
			if ($column != 1 || $column != 2 || $column != 3) {
				$column = 1;
			}

			$widget->column = (int) $column;

			$ordertmp = array();
			$params = array(
				'context' => $widget->context,
				'column' => $column,
			);

			if ($entities = get_entities_from_metadata_multi($params, 'object', 'widget')) {
				foreach ($entities as $entity) {
					$entityorder = $entity->order;
					if ($entityorder < $order) {
						$ordertmp[$entityorder] = $entity;
					}
					if ($entityorder >= $order) {
						$ordertmp[$entityorder + 10000] = $entity;
					}
				}
			}

			$ordertmp[$order] = $widget;
			ksort($ordertmp);

			$orderticker = 10;
			foreach ($ordertmp as $orderval => $entity) {
				$entity->order = $orderticker;
				$orderticker += 10;
			}

			return true;
		} else {
			register_error($widget->subtype);
		}

	}

	return false;
}

/**
 * Get widgets for a particular context and column, in order of display
 *
 * @param int    $user_guid The owner user GUID
 * @param string $context   The context (profile, dashboard etc)
 * @param int    $column    The column (1 or 2)
 *
 * @return array|false An array of widget ElggObjects, or false
 * @deprecated 1.8 Use elgg_get_widgets()
 */
function get_widgets($user_guid, $context, $column) {
	elgg_deprecated_notice('get_widgets is depecated for elgg_get_widgets', 1.8);
	$params = array(
		'column' => $column,
		'context' => $context
	);
	$widgets = get_entities_from_private_setting_multi($params, "object",
		"widget", $user_guid, "", 10000);

	if ($widgets) {
		$widgetorder = array();
		foreach ($widgets as $widget) {
			$order = $widget->order;
			while (isset($widgetorder[$order])) {
				$order++;
			}
			$widgetorder[$order] = $widget;
		}

		ksort($widgetorder);

		return $widgetorder;
	}

	return false;
}

/**
 * Add a new widget instance
 *
 * @param int    $entity_guid GUID of entity that owns this widget
 * @param string $handler     The handler for this widget
 * @param string $context     The page context for this widget
 * @param int    $order       The order to display this widget in
 * @param int    $column      The column to display this widget in (1, 2 or 3)
 * @param int    $access_id   If not specified, it is set to the default access level
 *
 * @return int|false Widget GUID or false on failure
 * @deprecated 1.8 use elgg_create_widget()
 */
function add_widget($entity_guid, $handler, $context, $order = 0, $column = 1, $access_id = null) {
	elgg_deprecated_notice('add_widget has been deprecated for elgg_create_widget', 1.8);
	if (empty($entity_guid) || empty($context) || empty($handler) || !widget_type_exists($handler)) {
		return false;
	}

	if ($entity = get_entity($entity_guid)) {
		$widget = new ElggWidget;
		$widget->owner_guid = $entity_guid;
		$widget->container_guid = $entity_guid;
		if (isset($access_id)) {
			$widget->access_id = $access_id;
		} else {
			$widget->access_id = get_default_access();
		}

		$guid = $widget->save();

		// private settings cannot be set until ElggWidget saved
		$widget->handler = $handler;
		$widget->context = $context;
		$widget->column = $column;
		$widget->order = $order;

		return $guid;
	}

	return false;
}

/**
 * Define a new widget type
 *
 * @param string $handler     The identifier for the widget handler
 * @param string $name        The name of the widget type
 * @param string $description A description for the widget type
 * @param string $context     A comma-separated list of contexts where this
 *                            widget is allowed (default: 'all')
 * @param bool   $multiple    Whether or not multiple instances of this widget
 *                            are allowed on a single dashboard (default: false)
 * @param string $positions   A comma-separated list of positions on the page
 *                            (side or main) where this widget is allowed (default: "side,main")
 *
 * @return bool Depending on success
 * @deprecated 1.8 Use elgg_register_widget_type
 */
function add_widget_type($handler, $name, $description, $context = "all",
$multiple = false, $positions = "side,main") {
	elgg_deprecated_notice("add_widget_type deprecated for elgg_register_widget_type", 1.8);

	return elgg_register_widget_type($handler, $name, $description, $context, $multiple);
}

/**
 * Remove a widget type
 *
 * @param string $handler The identifier for the widget handler
 *
 * @return void
 * @since 1.7.1
 * @deprecated 1.8 Use elgg_unregister_widget_type
 */
function remove_widget_type($handler) {
	elgg_deprecated_notice("remove_widget_type deprecated for elgg_unregister_widget_type", 1.8);
	return elgg_unregister_widget_type($handler);
}

/**
 * Determines whether or not widgets with the specified handler have been defined
 *
 * @param string $handler The widget handler identifying string
 *
 * @return bool Whether or not those widgets exist
 * @deprecated 1.8 Use elgg_is_widget_type
 */
function widget_type_exists($handler) {
	elgg_deprecated_notice("widget_type_exists deprecated for elgg_is_widget_type", 1.8);
	return elgg_is_widget_type($handler);
}

/**
 * Returns an array of stdClass objects representing the defined widget types
 *
 * @return array A list of types defined (if any)
 * @deprecated 1.8 Use elgg_get_widget_types
 */
function get_widget_types() {
	elgg_deprecated_notice("get_widget_types deprecrated for elgg_get_widget_types", 1.8);
	return elgg_get_widget_types();
}

/**
 * Saves a widget's settings (by passing an array of
 * (name => value) pairs to save_{$handler}_widget)
 *
 * @param int   $widget_guid The GUID of the widget we're saving to
 * @param array $params      An array of name => value parameters
 *
 * @return bool
 * @deprecated 1.8 Use elgg_save_widget_settings
 */
function save_widget_info($widget_guid, $params) {
	elgg_deprecated_notice("save_widget_info() is deprecated for elgg_save_widget_settings", 1.8);
	if ($widget = get_entity($widget_guid)) {

		$subtype = $widget->getSubtype();

		if ($subtype != "widget") {
			return false;
		}
		$handler = $widget->handler;
		if (empty($handler) || !widget_type_exists($handler)) {
			return false;
		}

		if (!$widget->canEdit()) {
			return false;
		}

		// Save the params to the widget
		if (is_array($params) && sizeof($params) > 0) {
			foreach ($params as $name => $value) {

				if (!empty($name) && !in_array($name, array(
					'guid', 'owner_guid', 'site_guid'
				))) {
					if (is_array($value)) {
						// @todo Handle arrays securely
						$widget->setMetaData($name, $value, "", true);
					} else {
						$widget->$name = $value;
					}
				}
			}
			$widget->save();
		}

		$function = "save_{$handler}_widget";
		if (is_callable($function)) {
			return $function($params);
		}

		return true;
	}

	return false;
}

/**
 * Reorders the widgets from a widget panel
 *
 * @param string $panelstring1 String of guids of ElggWidget objects separated by ::
 * @param string $panelstring2 String of guids of ElggWidget objects separated by ::
 * @param string $panelstring3 String of guids of ElggWidget objects separated by ::
 * @param string $context      Profile or dashboard
 * @param int    $owner        Owner guid
 *
 * @return void
 * @deprecated 1.8
 */
function reorder_widgets_from_panel($panelstring1, $panelstring2, $panelstring3, $context, $owner) {
	elgg_deprecated_notice("reorder_widgets_from_panel() is deprecated", 1.8);
	$return = true;

	$mainwidgets = explode('::', $panelstring1);
	$sidewidgets = explode('::', $panelstring2);
	$rightwidgets = explode('::', $panelstring3);

	$handlers = array();
	$guids = array();

	if (is_array($mainwidgets) && sizeof($mainwidgets) > 0) {
		foreach ($mainwidgets as $widget) {

			$guid = (int) $widget;

			if ("{$guid}" == "{$widget}") {
				$guids[1][] = $widget;
			} else {
				$handlers[1][] = $widget;
			}
		}
	}
	if (is_array($sidewidgets) && sizeof($sidewidgets) > 0) {
		foreach ($sidewidgets as $widget) {

			$guid = (int) $widget;

			if ("{$guid}" == "{$widget}") {
				$guids[2][] = $widget;
			} else {
				$handlers[2][] = $widget;
			}

		}
	}
	if (is_array($rightwidgets) && sizeof($rightwidgets) > 0) {
		foreach ($rightwidgets as $widget) {

			$guid = (int) $widget;

			if ("{$guid}" == "{$widget}") {
				$guids[3][] = $widget;
			} else {
				$handlers[3][] = $widget;
			}

		}
	}

	// Reorder existing widgets or delete ones that have vanished
	foreach (array(1, 2, 3) as $column) {
		if ($dbwidgets = get_widgets($owner, $context, $column)) {

			foreach ($dbwidgets as $dbwidget) {
				if (in_array($dbwidget->getGUID(), $guids[1])
				|| in_array($dbwidget->getGUID(), $guids[2]) || in_array($dbwidget->getGUID(), $guids[3])) {

					if (in_array($dbwidget->getGUID(), $guids[1])) {
						$pos = array_search($dbwidget->getGUID(), $guids[1]);
						$col = 1;
					} else if (in_array($dbwidget->getGUID(), $guids[2])) {
						$pos = array_search($dbwidget->getGUID(), $guids[2]);
						$col = 2;
					} else {
						$pos = array_search($dbwidget->getGUID(), $guids[3]);
						$col = 3;
					}
					$pos = ($pos + 1) * 10;
					$dbwidget->column = $col;
					$dbwidget->order = $pos;
				} else {
					$dbguid = $dbwidget->getGUID();
					if (!$dbwidget->delete()) {
						$return = false;
					} else {
						// Remove state cookie
						setcookie('widget' + $dbguid, null);
					}
				}
			}

		}
		// Add new ones
		if (sizeof($guids[$column]) > 0) {
			foreach ($guids[$column] as $key => $guid) {
				if ($guid == 0) {
					$pos = ($key + 1) * 10;
					$handler = $handlers[$column][$key];
					if (!add_widget($owner, $handler, $context, $pos, $column)) {
						$return = false;
					}
				}
			}
		}
	}

	return $return;
}

/**
 * Register a particular context for use with widgets.
 *
 * @param string $context The context we wish to enable context for
 *
 * @return void
 * @deprecated 1.8
 */
function use_widgets($context) {
	elgg_deprecated_notice("use_widgets is deprecated", 1.8);
	global $CONFIG;

	if (!isset($CONFIG->widgets)) {
		$CONFIG->widgets = new stdClass;
	}

	if (!isset($CONFIG->widgets->contexts)) {
		$CONFIG->widgets->contexts = array();
	}

	if (!empty($context)) {
		$CONFIG->widgets->contexts[] = $context;
	}
}

/**
 * Determines whether or not the current context is using widgets
 *
 * @return bool Depending on widget status
 * @deprecated 1.8
 */
function using_widgets() {
	elgg_deprecated_notice("using_widgets is deprecated", 1.8);
	global $CONFIG;

	$context = elgg_get_context();
	if (isset($CONFIG->widgets->contexts) && is_array($CONFIG->widgets->contexts)) {
		if (in_array($context, $CONFIG->widgets->contexts)) {
			return true;
		}
	}

	return false;
}

/**
 * Displays a particular widget
 *
 * @param ElggObject $widget The widget to display
 * @return string The HTML for the widget, including JavaScript wrapper
 * 
 * @deprecated 1.8
 */
function display_widget(ElggObject $widget) {
	elgg_deprecated_notice("display_widget() was been deprecated. Use elgg_view_entity().", 1.8);
	return elgg_view_entity($widget);
}

/**
 * Count the number of comments attached to an entity
 *
 * @param ElggEntity $entity
 * @return int Number of comments
 */
function elgg_count_comments($entity) {
	elgg_deprecated_notice('elgg_count_comments() is deprecated by ElggEntity->countComments()', 1.8);

	if ($entity instanceof ElggEntity) {
		return $entity->countComments();
	}

	return 0;
}

/**
 * Removes all items relating to a particular acting entity from the river
 *
 * @param int $subject_guid The GUID of the entity
 *
 * @return bool Depending on success
 * @deprecated 1.8 Use elgg_delete_river()
 */
function remove_from_river_by_subject($subject_guid) {
	elgg_deprecated_notice("remove_from_river_by_subject() deprecated by elgg_delete_river()", 1.8);

	return elgg_delete_river(array('subject_guid' => $subject_guid));
}

/**
 * Removes all items relating to a particular entity being acted upon from the river
 *
 * @param int $object_guid The GUID of the entity
 *
 * @return bool Depending on success
 * @deprecated 1.8 Use elgg_delete_river()
 */
function remove_from_river_by_object($object_guid) {
	elgg_deprecated_notice("remove_from_river_by_object() deprecated by elgg_delete_river()", 1.8);

	return elgg_delete_river(array('object_guid' => $object_guid));
}

/**
 * Removes all items relating to a particular annotation being acted upon from the river
 *
 * @param int $annotation_id The ID of the annotation
 *
 * @return bool Depending on success
 * @since 1.7.0
 * @deprecated 1.8 Use elgg_delete_river()
 */
function remove_from_river_by_annotation($annotation_id) {
	elgg_deprecated_notice("remove_from_river_by_annotation() deprecated by elgg_delete_river()", 1.8);

	return elgg_delete_river(array('annotation_id' => $annotation_id));
}

/**
 * Removes a single river entry
 *
 * @param int $id The ID of the river entry
 *
 * @return bool Depending on success
 * @since 1.7.2
 * @deprecated 1.8 Use elgg_delete_river()
 */
function remove_from_river_by_id($id) {
	elgg_deprecated_notice("remove_from_river_by_id() deprecated by elgg_delete_river()", 1.8);

	return elgg_delete_river(array('id' => $id));
}
