<?php
/**
 * @return str
 * @deprecated 1.8 Use elgg_list_entities_from_access_id()
 */
function list_entities_from_access_id($access_id, $entity_type = "", $entity_subtype = "",
	$owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true) {
		
	elgg_deprecated_notice("All list_entities* functions were deprecated in 1.8.  Use elgg_list_entities* instead.", 1.8);
		
	echo elgg_list_entities_from_access_id(array(
		'access_id' => $access_id,
		'types' => $entity_type,
		'subtypes' => $entity_subtype,
		'owner_guids' => $owner_guid,
		'limit' => $limit,
		'full_view' => $fullview,
		'list_type_toggle' => $listtypetoggle,
		'pagination' => $pagination,
	));
}

/**
 * @deprecated 1.8 Use {@link elgg_register_action()} instead
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
 * @deprecated 1.8 Extend admin views manually
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
function get_entities_from_annotations_calculate_x($sum = "sum", $entity_type = "",
$entity_subtype = "", $name = "", $mdname = '', $mdvalue = '', $owner_guid = 0,
$limit = 10, $offset = 0, $orderdir = 'desc', $count = false) {

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

	$options['order_by'] = "calculated $orderdir";

	$options['count'] = $count;

	return elgg_get_entities_from_annotation_calculation($options);
}

/**
 * Returns entities ordered by the sum of an annotation
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
function get_entities_from_annotation_count($entity_type = "", $entity_subtype = "", $name = "",
$mdname = '', $mdvalue = '', $owner_guid = 0, $limit = 10, $offset = 0, $orderdir = 'desc',
$count = false) {

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

	$options['order_by'] = "calculated $orderdir";

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
function list_entities_from_annotation_count($entity_type = "", $entity_subtype = "", $name = "",
$limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true,
$listtypetoggle = false, $pagination = true, $orderdir = 'desc') {

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

	$options['order_by'] = "calculated $orderdir";

	return elgg_get_entities_from_annotation_calculation($options);
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
 * @deprecated 1.8
 */
function add_to_register($register_name, $subregister_name, $subregister_value,
$children_array = array()) {
	elgg_deprecated_notice("add_to_register() has been deprecated", 1.8);
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
 * @deprecated 1.8
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
 * @param string $register_name The name of the register
 *
 * @return array|false Depending on success
 * @deprecated 1.8
 */
function get_register($register_name) {
	elgg_deprecated_notice("get_register() has been deprecated", 1.8);
	global $CONFIG;

	if (isset($CONFIG->registers[$register_name])) {
		return $CONFIG->registers[$register_name];
	}

	return false;
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
 * @deprecated 1.8 Use elgg_register_event_handler() instead
 */
function register_elgg_event_handler($event, $object_type, $callback, $priority = 500) {
	elgg_deprecated_notice("register_elgg_event_handler() was deprecated by elgg_register_event_handler()", 1.8);
	return elgg_register_event_handler($event, $object_type, $callback, $priority);
}

/**
 * @deprecated 1.8 Use elgg_unregister_event_handler instead
 */
function unregister_elgg_event_handler($event, $object_type, $callback) {
	elgg_deprecated_notice('unregister_elgg_event_handler => elgg_unregister_event_handler', 1.8);
	elgg_unregister_event_handler($event, $object_type, $callback);
}

/**
 * @deprecated 1.8 Use elgg_trigger_event() instead
 */
function trigger_elgg_event($event, $object_type, $object = null) {
	elgg_deprecated_notice('trigger_elgg_event() was deprecated by elgg_trigger_event()', 1.8);
	return elgg_trigger_event($event, $object_type, $object);
}

/**
 * @deprecated 1.8 Use elgg_register_plugin_hook_handler() instead
 */
function register_plugin_hook($hook, $type, $callback, $priority = 500) {
	elgg_deprecated_notice("register_plugin_hook() was deprecated by elgg_register_plugin_hook_handler()", 1.8);
	return elgg_register_plugin_hook_handler($hook, $type, $callback, $priority);
}

/**
 * @deprecated 1.8 Use elgg_unregister_plugin_hook_handler() instead
 */
function unregister_plugin_hook($hook, $entity_type, $callback) {
	elgg_deprecated_notice("unregister_plugin_hook() was deprecated by elgg_unregister_plugin_hook_handler()", 1.8);
	elgg_unregister_plugin_hook_handler($hook, $entity_type, $callback);
}

/**
 * @deprecated 1.8 Use elgg_trigger_plugin_hook() instead
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
